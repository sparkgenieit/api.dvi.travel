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
$itinerary_plan_ID = 10;

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Itinerary Preview</title>

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
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/rateyo/rateyo.css">

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
    <style>
        .timeline-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap
        }

        .timeline-steps .timeline-step {
            align-items: center;
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 1rem
        }

        @media (min-width:768px) {
            .timeline-steps .timeline-step:not(:last-child):after {
                content: "";
                display: block;
                border-top: .25rem dotted #dbdade;
                width: 3.46rem;
                position: absolute;
                left: 7.5rem;
                top: 1.3125rem
            }

            .timeline-steps .timeline-step:not(:first-child):before {
                content: "";
                display: block;
                border-top: .25rem dotted #dbdade;
                width: 3.8125rem;
                position: absolute;
                right: 7.5rem;
                top: 1.3125rem
            }
        }

        .timeline-steps .timeline-content {
            width: 10rem;
            text-align: center
        }

        /* .timeline-steps .timeline-content .inner-circle {
            border-radius: 1.5rem;
            height: 1rem;
            width: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6
        }

        .timeline-steps .timeline-content .inner-circle:before {
            content: "";
            background-color: #3b82f6;
            display: inline-block;
            height: 3rem;
            width: 3rem;
            min-width: 3rem;
            border-radius: 6.25rem;
            opacity: .5
        } */
		
		.testing {
			display: inline;
		}
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
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
                    <div class="container-xxl flex-grow-1 container-p-y" id="contentToCopy">
    <button class="copy-button" id="copy-button" onclick="copyToClipboard()">
      Copy
    </button>
                        <div class=" d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="font-weight-bold">Itinerary Preview - <span class="text-primary fs-5">DVIADMIN00A</span></h4>
                            </div>
                        </div>
						
						<div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4" style="background-image: linear-gradient(140deg, #e594cb 0%, #db68b6 50%, #e098e1 75%);">
							<div>				
								<div class="d-flex justify-content-between">
									<h5 class="text-capitalize mt-1"> Itinerary for
										<b>March 29, 2024</b> to
										<b>March 31, 2024</b> (<b>2</b> Nights,
										<b>3</b> Days)
									</h5>
									<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Budget<strong class="ms-2">₹ 50,000</strong></span>
									</h5>
								</div>
								
								<div class="d-flex justify-content-between">
									<div class="d-flex align-items-center">
										<div>
											<h3 class="text-capitalize mb-0">Chennai, Tamil Nadu, India</h3>
											<p class="lead mb-0">Arrival By Road</p>
										</div>
										<h3 class="text-capitalize mx-3 mb-0"><i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i></h3> 
										<div>
											<h3 class="text-capitalize mb-0">Rameswaram, Tamil Nadu, India</h3>
											<p class="lead mb-0">Departure By Road</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-12">
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-md-2">
												<label>Itinerary Type</label>
												<p class="text-light">Customize</p>
											</div>
											<div class="col-md-2">
												<label>Guide for Itineary</label>
												<p class="text-light">No</p>
											</div>
											<div class="col-md-2">
												<label>Nationality</label>
												<p class="text-light">Indian</p>
											</div>
											<div class="col-md-2">
												<label>Number of Routes</label>
												<p class="text-light">2</p>
											</div>
											<div class="col-md-2">
												<label>Number of Rooms</label>
												<p class="text-light">2</p>
											</div>
											<div class="col-md-2">
												<label>Child Bed</label>
												<p class="text-light">0</p>
											</div>
											<div class="col-md-2">
												<label>Extra Beds </label>
												<p class="text-light">0</p>
											</div>
											<div class="col-md-2">
												<label>Meal Plan</label>
												<p class="text-light mb-0">Breakfast,Lunch,Dinner</p>
											</div>
											<div class="col-md-2">
												<label>Food Preferences</label>
												<p class="text-light mb-0">Veg & Non-Veg</p>
											</div>
											<div class="col-md-6">
												<label>Special Instructions</label>
												<p class="text-light mb-0">Notes</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="d-flex justify-content-between mb-2">
												<h5 class="text-primary mb-0">Travellers Details</h5>
												<div>
													<p class="badge alert-primary bg-glow ms-2 mb-0"><strong class="me-1">1</strong> Adults , <strong class="me-1">1</strong> Children , <strong class="me-1">2</strong> Infants</p>
												</div>
											</div>
											<div class="col-12">										
												<div class="row mt-2">
													<div class="col">
														<label>Adult #1</label>
														<p class="text-light mb-0">23</p>
													</div>
													<div class="col">
														<label>Children #1</label>
														<p class="text-light mb-0">10</p>
													</div>
													<div class="col">
														<label>Infants #1</label>
														<p class="text-light mb-0">4</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="d-flex justify-content-between mb-2">
												<h5 class="text-primary mb-0">Vehicle Details</h5>
												<div>
													<span class="badge alert-primary bg-glow ms-2">Count<strong class="ms-2">2</strong></span>
												</div>
											</div>
											<div class="col-12">										
												<div class="row mt-2">
													<div class="col-4">
														<label>Vehicle #1</label>
														<p class="text-light mb-0">Sedan (Count 2)</p>
													</div>
													<div class="col-4">
														<label>Vehicle #2</label>
														<p class="text-light mb-0">Tempo (Count 2)</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Basic Info -->
							
						<div class="row">
							<div class="col-12">
								<div class="card mb-4">
									<ul class="list-group list-group-flush">
										<li class="list-group-item">
											<div class="text-center align-items-center justify-content-center">
												<h6 class="testing mb-0">Chennai, Tamil Nadu, India</h6>
												<h6 class="testing mb-0"><i class="tf-icons ti ti-arrow-big-right-lines-filled mx-3 text-primary"></i></h6>
												<h6 class="testing mb-0">
													<div class="d-inline-block"><small>Via Mahabalipuram, Tamil Nadu, India</small>
												<br/>
													Pondicherry, Puducherry, India</div></h6>
												<h6 class="testing mb-0"><i class="tf-icons ti ti-arrow-big-right-lines-filled mx-3 text-primary"></i></h6>
												<h6 class="testing mb-0">Rameswaram, Tamil Nadu, India</h6>
											</div>
										</li>
									</ul>
									
									<hr class="mt-1"/>
															
									<div class="nav-align-top">
										<ul class="nav nav-tabs ms-0 me-0" role="tablist">
											<li class="nav-item">
												<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#route1" aria-controls="route1" aria-selected="true">Route 1</button>
											</li>
											<li class="nav-item">
												<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#route2" aria-controls="route2" aria-selected="true">Route 2</button>
											</li>
										</ul>
										<div class="tab-content px-0 pt-3 pb-0">
											<div class="tab-pane fade show active" id="route1" role="tabpanel">
												<ul class="list-group list-group-flush">
													<li class="list-group-item bg-primary">
														<h5 class="mb-0 text-white text-uppercase text-center fw-bold">Overall Trip Cost ₹ 10,517</h5>
													</li>
												</ul>
												<div class="p-3" style="background-color: #f5e0f1;">
													<div class="accordion" id="accordionWithIcon">
													
													<!-- Day 1 -->
													  <div class="card accordion-item active">
														<h2 class="accordion-header d-flex align-items-center">
														  <button type="button" class="accordion-button accordion-button-itinerary-preview" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-1" aria-expanded="true">
															<div class="d-flex align-items-center justify-content-between w-100 me-2">
																<div class="d-flex align-items-center">
																	<div class="avatar-wrapper">
																		<div class="avatar me-2">
																			<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-xs"></i></span>
																		</div>
																	</div>
																	<span class="d-flex">
																		<h6 class="mb-0"> <b>DAY 1</b> -
																			March 29, 2024 (Friday)                                                                                    | Chennai, Tamil Nadu, India</h6>
																	</span>
																</div>
																<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Fare<strong class="ms-2">₹ 1,000</strong></span></h5>
															</div>
														  </button>
														</h2>

														<div id="accordionWithIcon-1" class="accordion-collapse collapse show">
														  <div class="accordion-body">
															
															<ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
																<li class="timeline-item timeline-item-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Refresh / Relief Period</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			9:00 AM To 10:00 AM					</div>
																	</div>
																</li>
																<li class="timeline-item timeline-item-transparent" id="remove_travel_to_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-warning">
																		<i class="ti ti-road rounded-circle"></i>
																	</span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance 5.4 KM</span>, <span class="text-primary">estimated time 6 Mins</span> and this may vary due to traffic conditions.
																				</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">10:00 AM To 10:18 AM</div>
																	</div>
																</li>

																<li class="timeline-item pb-4 timeline-item-success border-left-dashed" id="remove_added_itinerary_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-primary">
																		<i class="ti ti-map-pin rounded-circle text-primary"></i>
																	</span>
																	<div class="timeline-event py-2 px-3">
																		<div class="d-flex flex-sm-row flex-column align-items-center">	
																			<img src="assets/img/elements/9.jpg" class="rounded me-1" alt="Hotspot" height="110" width="110" />
																			<div class="w-100 px-3 mb-2 mt-2">
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<h6 class="mb-0 text-capitalize">Marina Beach</h6>
																					<h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
																				</div>
																				<p class="my-1">
																					<i class="ti ti-clock-filled me-1 mb-1"></i>
																					10:18 AM - 10:48 AM
																				</p>
																				<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i>Marina Beach Road, Chennai (Madras) 600005 India</p>
																				<p class="my-1"><i class="ti ti-ticket me-1 mb-1"></i>No Fare</p>
																			</div>
																		</div>
																		
																		<div class="timeline-event-time timeline-event-time-itinerary">10:18 AM To 11:48 PM</div>
																	</div>
																	
																	<!-- Activities -->
																	<div class="row">
																		<div class="col-12">
																			<ul class="timeline timeline-center mt-1">
																					<li class="timeline-item timeline-item-activities">
																						<span class="timeline-indicator timeline-indicator-primary">
																							<i class="ti ti-trekking ti-sm"></i>
																						</span>
																						<div class="timeline-event timeline-event-activities py-2 px-3">
																							<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																								<h6 class="mb-0 text-capitalize"><b>Activity 1</b></h6>
																										<h6 class="text-primary mb-0">
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																										</h6>
																							</div>
																							<div class="d-flex flex-sm-row flex-column align-items-center">
																							
															<img src="assets/img/elements/9.jpg" class="rounded me-3" alt="Show img" height="90" width="90" />
																								<div class="w-100">
																									<p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">10:48 AM - 11:48 AM</span></p>
																									<p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum 3 Persons Allowed</span></p>
																									<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																										<p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>₹ 1,000</p>
																										</h6>
																									</div>
																								</div>
																							<div class="timeline-event-time timeline-event-time-activities"></div>
																							</div>
																						</div>
																					</li>
																			</ul>
																		</div>
																	</div>
																	<!-- Activities -->
																</li>
													
																<li class="timeline-item timeline-item-transparent border-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Return To Hotel</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			6:00 PM To 7:00 PM					</div>
																	</div>
																</li>
															</ul>
														  </div>
														</div>
													  </div>
														<!-- Day 1 -->
													  
													  
													<!-- Day 2 -->
													  <div class="card accordion-item">
														<h2 class="accordion-header d-flex align-items-center">
														  <button type="button" class="accordion-button accordion-button-itinerary-preview collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-2" aria-expanded="true">
															<div class="d-flex align-items-center justify-content-between w-100 me-2">
															
																<div class="d-flex align-items-center">
																	<div class="avatar-wrapper">
																		<div class="avatar me-2">
																			<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
																		</div>
																	</div>
																	<span class="d-flex">
																		<h6 class="mb-0"> <b>DAY 2</b> -
																			March 30, 2024 (Saturday)                                                                                    | Mahabalipuram, Tamil Nadu, India</h6>
																	</span>
																</div>
																<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Fare<strong class="ms-2">₹ 1,000</strong></span></h5>
															</div>
														  </button>
														</h2>

														<div id="accordionWithIcon-2" class="accordion-collapse collapse">
														  <div class="accordion-body">
															
															<ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
																<li class="timeline-item timeline-item-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Refresh / Relief Period</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			9:00 AM To 10:00 AM					</div>
																	</div>
																</li>
																<li class="timeline-item timeline-item-transparent" id="remove_travel_to_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-warning">
																		<i class="ti ti-road rounded-circle"></i>
																	</span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance 5.4 KM</span>, <span class="text-primary">estimated time 6 Mins</span> and this may vary due to traffic conditions.
																				</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">10:00 AM To 10:18 AM</div>
																	</div>
																</li>

																<li class="timeline-item pb-4 timeline-item-success border-left-dashed" id="remove_added_itinerary_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-primary">
																		<i class="ti ti-map-pin rounded-circle text-primary"></i>
																	</span>
																	<div class="timeline-event py-2 px-3">
																		<div class="d-flex flex-sm-row flex-column align-items-center">	
																			<img src="assets/img/elements/9.jpg" class="rounded me-1" alt="Hotspot" height="110" width="110" />
																			<div class="w-100 px-3 mb-2 mt-2">
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<h6 class="mb-0 text-capitalize">Marina Beach</h6>
																					<h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
																				</div>
																				<p class="my-1">
																					<i class="ti ti-clock-filled me-1 mb-1"></i>
																					10:18 AM - 10:48 AM
																				</p>
																				<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i>Marina Beach Road, Chennai (Madras) 600005 India</p>
																				<p class="my-1"><i class="ti ti-ticket me-1 mb-1"></i>No Fare</p>
																			</div>
																		</div>
																		
																		<div class="timeline-event-time timeline-event-time-itinerary">10:18 AM To 11:48 PM</div>
																	</div>
																	
																	<!-- Activities -->
																	<div class="row">
																		<div class="col-12">
																			<ul class="timeline timeline-center mt-1">
																					<li class="timeline-item timeline-item-activities">
																						<span class="timeline-indicator timeline-indicator-primary">
																							<i class="ti ti-trekking ti-sm"></i>
																						</span>
																						<div class="timeline-event timeline-event-activities py-2 px-3">
																							<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																								<h6 class="mb-0 text-capitalize"><b>Activity 1</b></h6>
																										<h6 class="text-primary mb-0">
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																										</h6>
																							</div>
																							<div class="d-flex flex-sm-row flex-column align-items-center">
																							
															<img src="assets/img/elements/9.jpg" class="rounded me-3" alt="Show img" height="90" width="90" />
																								<div class="w-100">
																									<p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">10:48 AM - 11:48 AM</span></p>
																									<p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum 3 Persons Allowed</span></p>
																									<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																										<p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>₹ 1,000</p>
																										</h6>
																									</div>
																								</div>
																							<div class="timeline-event-time timeline-event-time-activities"></div>
																							</div>
																						</div>
																					</li>
																			</ul>
																		</div>
																	</div>
																	<!-- Activities -->
																</li>
													
																<li class="timeline-item timeline-item-transparent border-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Return To Hotel</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			6:00 PM To 7:00 PM					</div>
																	</div>
																</li>
															</ul>
														  </div>
														</div>
													  </div>
														<!-- Day 2 -->
														
													</div>
													
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col my-auto">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Hotel List</h5>
														</div>
														<div class="col text-end">
															<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Hotel Fare<strong class="ms-2">₹ 6,800</strong></span></h5>
														</div>
													</div>
																										
													<div class="col-md-12">
														<div class="row mt-3">
														
															<!-- Day 1 -->
														  <div class="col-md-6">
															<div class="card mb-3">
																  <div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between">
																		<h6 class="mb-0">
																			<span>DAY 1 - March 29, 2024</span>
																		</h6>
																	</div>
																		<h6 class="mt-1 mb-0 text-primary">
																			<i class="ti ti-map-pin ti-xs text-primary me-1 mb-1"></i>Mahabalipuram, Tamil Nadu, India
																		</h6>
																	
																	<div class="row">
																		<div class="col-12">
																			<div class="row justify-content-between mb-2  align-items-center">
																				<div class="col-8">
																				
																					<div class="mt-1 mb-2">
																						<div class="d-flex align-items-center 	justify-content-between">
																							<div>
																							<h6 class="mb-0"><b>MAMALLA HERITAGE</b></h6>
																										
																								</div>
																						</div>
																																															<small class="mb-0 d-flex align-items-center">
																								<p class="badge me-1 p-1 mb-0" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																									<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																								</p>
																								<a href="javascript:;" class="text-dark text-decoration-underline ms-1">1,300 reviews</a>
																							</small>
																					</div>
																				</div>
																				<div class="col-4 text-primary mb-0 text-end">
																					<div>
																					<h5 class="mb-0">
																						<span class="room_rate_176_1 room_rate_176 p-2">₹ 3,366</span>
																					<small class="d-block text-primary">(+ ₹ <span class="gst_rate_176">34</span> Tax)</small>
																					</h5>
																					</div>
																				</div>
																					
																				<div class="col-8">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - DELUXE
																					</h6>
																				</div>
																				<div class="col-4 text-end">
																					<small class="">₹ 3,366 (+ 34 Tax)</small>
																				</div>
																				
																				<div class="col-8">
																					<small><i class="ti ti-air-conditioning ti-xs me-1"></i>AC Available</small>
																					<br/>
																					<small><i class="ti ti-bowl ti-xs me-1"></i>Breakfast</small>
																					<br/>
																					<small><i class="ti ti-users ti-xs me-1"></i><span class="total_max_adults_176">3</span> Adults, <span class="total_max_childrens_176">1</span> Children</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check In - 2:00 PM</span>
																					</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check Out - 11:00 PM</span>
																					</small>
																				</div>
																				
																				<div class="col-4 text-primary mb-0 text-end">
																					<img class="card-img rounded" height="100" src="assets/img/elements/9.jpg" alt="Card image">
																				</div>
																			</div>
																		</div>
																	</div>
															  </div>
															</div>
														  </div>
															<!-- Day 1 -->
															
														
															<!-- Day 2 -->
														  <div class="col-md-6">
															<div class="card mb-3">
																  <div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between">
																		<h6 class="mb-0">
																			<span>DAY 2 - March 30, 2024 </span>
																		</h6>
																	</div>
																		<h6 class="mt-1 mb-0 text-primary">
																			<i class="ti ti-map-pin ti-xs text-primary me-1 mb-1"></i>Pondicherry, Puducherry, India
																		</h6>
																	
																	<div class="row">
																		<div class="col-12">
																			<div class="row justify-content-between mb-2  align-items-center">
																				<div class="col-8">
																				
																					<div class="mt-1 mb-2">
																						<div class="d-flex align-items-center 	justify-content-between">
																							<div>
																							<h6 class="mb-0"><b>SHENBAGA</b></h6>
																										
																								</div>
																						</div>
																																															<small class="mb-0 d-flex align-items-center">
																								<p class="badge me-1 p-1 mb-0" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																									<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																								</p>
																								<a href="javascript:;" class="text-dark text-decoration-underline ms-1">2,300 reviews</a>
																							</small>
																					</div>
																				</div>
																				<div class="col-4 text-primary mb-0 text-end">
																					<div>
																					<h5 class="mb-0">
																						<span class="room_rate_176_1 room_rate_176 p-2">₹ 3,666</span>
																					<small class="d-block text-primary">(+ ₹ <span class="gst_rate_176">34</span> Tax)</small>
																					</h5>
																					</div>
																				</div>
																					
																				<div class="col-8">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - MAJESTIC
																					</h6>
																				</div>
																				<div class="col-4 text-end">
																					<small class="">₹ 3,666 (+ 34 Tax)</small>
																				</div>
																				
																				<div class="col-8">
																					<small><i class="ti ti-air-conditioning ti-xs me-1"></i>AC Available</small>
																					<br/>
																					<small><i class="ti ti-bowl ti-xs me-1"></i>Breakfast</small>
																					<br/>
																					<small><i class="ti ti-users ti-xs me-1"></i><span class="total_max_adults_176">3</span> Adults, <span class="total_max_childrens_176">1</span> Children</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check In - 2:00 PM</span>
																					</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check Out - 11:00 PM</span>
																					</small>
																				</div>
																				
																				<div class="col-4 text-primary mb-0 text-end">
																					<img class="card-img rounded" height="100" src="assets/img/elements/9.jpg" alt="Card image">
																				</div>
																			</div>
																		</div>
																	</div>
															  </div>
															</div>
														  </div>
															<!-- Day 2 -->
														  
														</div>
													</div>
													
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col my-auto">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Vehicle List</h5>
														</div>
														<div class="col text-end">
															<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Vehicle Fare<strong class="ms-2">₹ 1,717</strong></span></h5>
														</div>
													</div>
													
													<div class="col-md-12">
														<div class="row mt-3">
														
															<!-- Day 1 -->
														  <div class="col-md-12">
															<div class="card mb-3">
																<div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between mt-2">
																		<h6 class="mb-0">
																			<span>DAY 1 - March 29, 2024 </span>
																		</h6>
																		<h6 class="mb-0">
																			<span class="text-primary me-1">
																				<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>
																				Chennai, Tamil Nadu, India To Mahabalipuram, Tamil Nadu, India                                            </span>
																		</h6>
																		<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Total <strong class="ms-2">₹ 1,700 (+ ₹ <span class="gst_rate_176">17</span> Tax)</strong></span></h5>
																	</div>
																
																	<hr/>
																	
																	<div class="row justify-content-center">
																		<div class="col-4">
																			<small class="mb-0">Travel Distance &amp; Time</small>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>56.4 KM
																			</p>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				1 Hours 1 Minutes                                             </p>
																		</div>
																		<div class="col-4 text-center">
																			<small class="mb-0">Sight-seeing Distance &amp; Time</small>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i> 0KM
																			</p>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				0 Hours 0 Minutes                                             </p>
																		</div>
																		<div class="col-4 text-end">
																			<small class="mb-0 text-primary">Total Distance &amp; Time</small>
																			<p class="mb-0 text-primary fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>57 KM
																			</p>
																			<p class="mb-0 text-primary fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				1 Hours 1 Minutes                                             </p>
																		</div>
																	</div>
																	
																	<hr/>
																	
																	<div class="row align-items-center">
																		<div class="col-12">
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
																								Max Occupancy: 5                                                        </h6>
																						</div>
																						<div class="col-md-auto text-primary mb-0 text-end">
																							<h5 class="mb-0 lh-1">
																								₹ 1,700.00                                                        </h5>

																							<small>+ ₹ 0 Vehicle + ₹ 17.00 Driver Tax</small>
																						</div>
																					</div>
																					<div class="row mb-2">
																						<div class="col-md-2">
																							<div class="d-flex flex-column align-items-center">
																																									<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="assets/img/exterior (1).jpg" alt="Sedan" data-bs-toggle="modal" data-bs-target="#modalCenter1_121_4" width="150" height="110" style="border: 1px solid #c33ca6;">
																																												<a href="https://www.youtube.com/watch?v=VIDEO_ID_HERE" target="_blank" class="button">Play Video</a>
																										
																																							</div>
																							<div class="modal fade" id="modalCenter1_121_4" tabindex="-1" aria-hidden="true">
																								<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																									<div class="modal-content">
																										<div class="modal-header">
																											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																										</div>
																										<div class="modal-body pt-0">

																											<div class="text-center mb-2">
																												<h5 class="modal-title" id="modalCenterTitle">Sedan </h5>
																												<h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"></h5>
																											</div>
																											<div id="swiper-gallery">
																												<div class="swiper gallery-top">
																													<div class="swiper-wrapper">
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-177541710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-10091710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-12141710760087.jpeg)"></div>
																																																		</div>
																													<!-- Add Arrows -->
																													<div class="swiper-button-next swiper-button-white"></div>
																													<div class="swiper-button-prev swiper-button-white"></div>
																												</div>
																												<div class="swiper gallery-thumbs">
																													<div class="swiper-wrapper">
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-177541710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-10091710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-12141710760087.jpeg)"></div>
																																																		</div>
																												</div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>

																						</div>
																						<div class="col-md-10">
																							<div class="row g-4">
																								<div class="col-2">
																									<small class="mb-0">Allowed kms</small>
																									<p class="mb-0 fw-bolder text-success">100</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra kms</small>
																									<p class="mb-0 fw-bolder">0</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Per day rental</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra kms</small>
																									<p class="mb-0 fw-bolder"><span class="text-primary">₹ 20.00</span></p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Permit charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>					
																								<div class="col-2">
																									<small class="mb-0">Driver Bhatta</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 500.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Driver Food Cost</small>
																									<p class="mb-0 fw-bolder"><span class="text-primary">₹ 400.00</span>
																									</p><p>
																								</p></div>
																								<div class="col-2">
																									<small class="mb-0">Accomdation Cost</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 500.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra Cost</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 300.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Toll Charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Parking Charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
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
														  </div>
															<!-- Day 1 -->
														  
														</div>
													</div>
				
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Summary</h5>
														</div>
													</div>
																<div class="card border p-0 mt-3">
																	<div class="order-calculations p-3">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotspot</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_hotspot_package">0</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Activity</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_activity_package">2,000</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotel</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_hotel_package">6,800</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Vehicle</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_vehicle_package">1,717</span></h6>
                                                                        </div>

                                                                        <hr>
                                                                        
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Gross Total for The Package</span>
                                                                            <h6 class="mb-0">₹  <span id="gross_total_package">10,517</span></h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">GST @ 5 % On The total Package
                                                                            </span>
                                                                            <h6 class="mb-0">₹  <span id="gst_total_package">526</span></h6>
                                                                        </div>
                                                                    </div>
																	<div class="d-flex justify-content-between  px-3 py-3 bg-primary" style="border-bottom-left-radius: 6px;border-bottom-right-radius:  6px;">
																		<h5 class="text-white fw-bold mb-0">Net Payable To Doview
                                                                                Holidays India Pvt ltd </h5>
																		<h5 class="mb-0 fw-bold text-white">₹ <span id="overall_vehicle_cost">
																				11,043                             </span></h5>
																	</div>
																</div>
												</div>
											</div>
											<div class="tab-pane fade show" id="route2" role="tabpanel">
											
												
												<ul class="list-group list-group-flush">
													<li class="list-group-item bg-primary">
														<h5 class="mb-0 text-white text-uppercase text-center fw-bold">Overall Trip Cost ₹ 3,570</h5>
													</li>
												</ul>
												<div class="p-3" style="background-color: #f5e0f1;">
													<div class="accordion" id="accordionWithIcon">
													
													<!-- Day 1 -->
													  <div class="card accordion-item active">
														<h2 class="accordion-header d-flex align-items-center">
														  <button type="button" class="accordion-button accordion-button-itinerary-preview" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-1" aria-expanded="true">
															<div class="d-flex align-items-center justify-content-between w-100 me-2">
																<div class="d-flex align-items-center">
																	<div class="avatar-wrapper">
																		<div class="avatar me-2">
																			<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-xs"></i></span>
																		</div>
																	</div>
																	<span class="d-flex">
																		<h6 class="mb-0"> <b>DAY 1</b> -
																			March 29, 2024 (Friday)                                                                                    | Chennai, Tamil Nadu, India</h6>
																	</span>
																</div>
																<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Fare<strong class="ms-2">₹ 5,000</strong></span></h5>
															</div>
														  </button>
														</h2>

														<div id="accordionWithIcon-1" class="accordion-collapse collapse show">
														  <div class="accordion-body">
															
															<ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
																<li class="timeline-item timeline-item-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Refresh / Relief Period</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			9:00 AM To 10:00 AM					</div>
																	</div>
																</li>
																<li class="timeline-item timeline-item-transparent" id="remove_travel_to_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-warning">
																		<i class="ti ti-road rounded-circle"></i>
																	</span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance 5.4 KM</span>, <span class="text-primary">estimated time 6 Mins</span> and this may vary due to traffic conditions.
																				</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">10:00 AM To 10:18 AM</div>
																	</div>
																</li>

																<li class="timeline-item pb-4 timeline-item-success border-left-dashed" id="remove_added_itinerary_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-primary">
																		<i class="ti ti-map-pin rounded-circle text-primary"></i>
																	</span>
																	<div class="timeline-event p-0">
																		<div class="d-flex flex-sm-row flex-column align-items-center">
																			<div class="w-100 px-3">
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<h6 class="mb-0 text-capitalize">Marina Beach</h6>
																					<h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
																				</div>
																				<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i>Marina Beach Road, Chennai (Madras) 600005 India</p>
																				<p class="my-1">
																					<i class="ti ti-clock-filled me-1 mb-1"></i>
																					4:00 AM - 11:00 PM
																				</p>
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>No Fare</p>
																				</div>
																			</div>
																			
																			<img class="card-img card-img-right" src="assets/img/elements/9.jpg" alt="Card image" style="width:150px">
																		</div>
																		
																		<div class="timeline-event-time timeline-event-time-itinerary">10:18 AM To 11:18 AM</div>
																	</div>
																	
																	<!-- Activities -->
																	<div class="row">
																		<div class="col-12">
																			<ul class="timeline timeline-center mt-1">
																					<li class="timeline-item timeline-item-activities">
																						<span class="timeline-indicator timeline-indicator-primary">
																							<i class="ti ti-trekking ti-sm"></i>
																						</span>
																						<div class="timeline-event timeline-event-activities pb-3 py-2 px-3">
																							<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																								<h6 class="mb-0 text-capitalize"><b>Activity 1</b></h6>
																										<h6 class="text-primary mb-0">
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																										</h6>
																							</div>
																							<div class="d-flex flex-sm-row flex-column align-items-center">
																							
															<img src="assets/img/elements/9.jpg" class="rounded me-3" alt="Show img" height="90" width="90" />
																								<div class="w-100">
																									<p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">4:00 AM - 11:00 PM</span></p>
																									<p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum 3 Persons Allowed</span></p>
																									<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																										<p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
																										</h6>
																									</div>
																								</div>
																							<div class="timeline-event-time timeline-event-time-activities">10:30 AM To 11;00 AM</div>
																							</div>
																						</div>
																					</li>
																			</ul>
																		</div>
																	</div>
																	<!-- Activities -->
																</li>
													
																<li class="timeline-item timeline-item-transparent border-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Return To Hotel</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			6:00 PM To 7:00 PM					</div>
																	</div>
																</li>
															</ul>
														  </div>
														</div>
													  </div>
														<!-- Day 1 -->
													  
													  
													<!-- Day 2 -->
													  <div class="card accordion-item">
														<h2 class="accordion-header d-flex align-items-center">
														  <button type="button" class="accordion-button accordion-button-itinerary-preview" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-2" aria-expanded="true">
															<div class="d-flex align-items-center justify-content-between w-100">
															
																<div class="d-flex align-items-center">
																	<div class="avatar-wrapper">
																		<div class="avatar me-2">
																			<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
																		</div>
																	</div>
																	<span class="d-flex">
																		<h6 class="mb-0"> <b>DAY 2</b> -
																			March 30, 2024 (Saturday)                                                                                    | Mahabalipuram, Tamil Nadu, India</h6>
																	</span>
																</div>
																<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Fare<strong class="ms-2">₹ 5,000</strong></span></h5>
															</div>
														  </button>
														</h2>

														<div id="accordionWithIcon-2" class="accordion-collapse collapse">
														  <div class="accordion-body">
															
															<ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
																<li class="timeline-item timeline-item-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Refresh / Relief Period</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			9:00 AM To 10:00 AM					</div>
																	</div>
																</li>
																<li class="timeline-item timeline-item-transparent" id="remove_travel_to_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-warning">
																		<i class="ti ti-road rounded-circle"></i>
																	</span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance 5.4 KM</span>, <span class="text-primary">estimated time 6 Mins</span> and this may vary due to traffic conditions.
																				</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">10:00 AM To 10:18 AM</div>
																	</div>
																</li>

																<li class="timeline-item pb-4 timeline-item-success border-left-dashed" id="remove_added_itinerary_hotspot_5">
																	<span class="timeline-indicator-advanced timeline-indicator-primary">
																		<i class="ti ti-map-pin rounded-circle text-primary"></i>
																	</span>
																	<div class="timeline-event p-0">
																		<div class="d-flex flex-sm-row flex-column align-items-center">
																			<div class="w-100 px-3">
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<h6 class="mb-0 text-capitalize">Marina Beach</h6>
																					<h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
																				</div>
																				<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i>Marina Beach Road, Chennai (Madras) 600005 India</p>
																				<p class="my-1">
																					<i class="ti ti-clock-filled me-1 mb-1"></i>
																					4:00 AM - 11:00 PM
																				</p>
																				<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																					<p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>No Fare</p>
																				</div>
																			</div>
																			
																			<img class="card-img card-img-right" src="assets/img/elements/9.jpg" alt="Card image" style="width:150px">
																		</div>
																		
																		<div class="timeline-event-time timeline-event-time-itinerary">10:18 AM To 11:18 AM</div>
																	</div>
																	
																	<!-- Activities -->
																	<div class="row">
																		<div class="col-12">
																			<ul class="timeline timeline-center mt-1">
																					<li class="timeline-item timeline-item-activities">
																						<span class="timeline-indicator timeline-indicator-primary">
																							<i class="ti ti-trekking ti-sm"></i>
																						</span>
																						<div class="timeline-event timeline-event-activities pb-3 py-2 px-3">
																							<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																								<h6 class="mb-0 text-capitalize"><b>Activity 1</b></h6>
																										<h6 class="text-primary mb-0">
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																												<i class="ti ti-star-filled"></i>
																										</h6>
																							</div>
																							<div class="d-flex flex-sm-row flex-column align-items-center">
																							
															<img src="assets/img/elements/9.jpg" class="rounded me-3" alt="Show img" height="90" width="90" />
																								<div class="w-100">
																									<p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">4:00 AM - 11:00 PM</span></p>
																									<p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum 3 Persons Allowed</span></p>
																									<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																										<p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
																										</h6>
																									</div>
																								</div>
																							<div class="timeline-event-time timeline-event-time-activities">10:30 AM To 11;00 AM</div>
																							</div>
																						</div>
																					</li>
																			</ul>
																		</div>
																	</div>
																	<!-- Activities -->
																</li>
													
																<li class="timeline-item timeline-item-transparent border-transparent">
																	<span class="timeline-point timeline-point-success"></span>
																	<div class="timeline-event">
																		<div class="timeline-header mb-sm-0 mb-3">
																			<h6 class="mb-0">Return To Hotel</h6>
																		</div>
																		<div class="timeline-event-time timeline-event-time-itinerary">
																			6:00 PM To 7:00 PM					</div>
																	</div>
																</li>
															</ul>
														  </div>
														</div>
													  </div>
														<!-- Day 2 -->
														
													</div>
													
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Hotel List</h5>
														</div>
														<div class="col text-end">
															<div class="mb-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹ <span id="total_amount_for_hotel">3,366</span></span></div>
														</div>
													</div>
																										
													<div class="col-md-12">
														<div class="row mt-3">
														
															<!-- Day 1 -->
														  <div class="col-md-6">
															<div class="card mb-3">
																  <div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between">
																		<h6 class="mb-0 text-muted">
																			<span>DAY 1 - March 29, 2024</span>
																			<span class="mx-1">|</span>
																			<small class="text-primary me-1">
																				<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Mahabalipuram, Tamil Nadu, India</small>
																		</h6>
																	</div>
																	
																	<div class="row">
																		<div class="col-12">
																			<div class="row justify-content-between mb-2  align-items-center">
																				<div class="col-8">
																				
																					<div class="mt-1 mb-2">
																						<div class="d-flex align-items-center 	justify-content-between">
																							<div>
																							<h6 class="mb-0"><b>MAMALLA HERITAGE</b></h6>
																										
																								</div>
																						</div>
																																															<small class="mb-0 d-flex align-items-center">
																								<p class="badge me-1 p-1 mb-0" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																									<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																								</p>
																								<a href="javascript:;" class="text-dark text-decoration-underline ms-1">1,300 reviews</a>
																							</small>
																					</div>
																				</div>
																				<div class="col-4 text-primary mb-0 text-end">
																					<div>
																					<h5 class="mb-0">
																						<span class="room_rate_176_1 room_rate_176 p-2">₹ 3,366</span>
																					<small class="d-block text-primary">(+ ₹ <span class="gst_rate_176">33</span> Tax)</small>
																					</h5>
																					</div>
																				</div>
																					
																				<div class="col-8">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - DELUXE
																					</h6>
																				</div>
																				<div class="col-4 text-end">
																					<small class="">₹ 3,366 (+ 33 Tax)</small>
																				</div>
																				
																				<div class="col-8">
																					<small><i class="ti ti-air-conditioning ti-xs me-1"></i>AC Available</small>
																					<br/>
																					<small><i class="ti ti-bowl ti-xs me-1"></i>Breakfast</small>
																					<br/>
																					<small><i class="ti ti-users ti-xs me-1"></i><span class="total_max_adults_176">3</span> Adults, <span class="total_max_childrens_176">1</span> Children</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check In - 2:00 PM</span>
																					</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check Out - 11:00 PM</span>
																					</small>
																				</div>
																				
																				<div class="col-4 text-primary mb-0 text-end">
																					<img class="card-img rounded" height="100" src="assets/img/elements/9.jpg" alt="Card image">
																				</div>
																			</div>
																		</div>
																	</div>
															  </div>
															</div>
														  </div>
															<!-- Day 1 -->
															
														
															<!-- Day 2 -->
														  <div class="col-md-6">
															<div class="card mb-3">
																  <div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between">
																		<h6 class="mb-0 text-muted">
																			<span>DAY 2 - March 30, 2024 </span>
																			<span class="mx-1">|</span>
																			<small class="text-primary me-1">
																				<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Pondicherry, Puducherry, India</small>
																		</h6>
																	</div>
																	
																	<div class="row">
																		<div class="col-12">
																			<div class="row justify-content-between mb-2  align-items-center">
																				<div class="col-8">
																				
																					<div class="mt-1 mb-2">
																						<div class="d-flex align-items-center 	justify-content-between">
																							<div>
																							<h6 class="mb-0"><b>SHENBAGA</b></h6>
																										
																								</div>
																						</div>
																																															<small class="mb-0 d-flex align-items-center">
																								<p class="badge me-1 p-1 mb-0" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																									<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																								</p>
																								<a href="javascript:;" class="text-dark text-decoration-underline ms-1">2,300 reviews</a>
																							</small>
																					</div>
																				</div>
																				<div class="col-4 text-primary mb-0 text-end">
																					<div>
																					<h5 class="mb-0">
																						<span class="room_rate_176_1 room_rate_176 p-2">₹ 3,000</span>
																					<small class="d-block text-primary">(+ ₹ <span class="gst_rate_176">30</span> Tax)</small>
																					</h5>
																					</div>
																				</div>
																					
																				<div class="col-8">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - MAJESTIC
																					</h6>
																				</div>
																				<div class="col-4 text-end">
																					<small class="">₹ 3,000 (+ 30 Tax)</small>
																				</div>
																				
																				<div class="col-8">
																					<small><i class="ti ti-air-conditioning ti-xs me-1"></i>AC Available</small>
																					<br/>
																					<small><i class="ti ti-bowl ti-xs me-1"></i>Breakfast</small>
																					<br/>
																					<small><i class="ti ti-users ti-xs me-1"></i><span class="total_max_adults_176">3</span> Adults, <span class="total_max_childrens_176">1</span> Children</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check In - 2:00 PM</span>
																					</small>
																					<br/>
																					<small><i class="ti ti-clock ti-xs me-1"></i>
																						<span>Check Out - 11:00 PM</span>
																					</small>
																				</div>
																				
																				<div class="col-4 text-primary mb-0 text-end">
																					<img class="card-img rounded" height="100" src="assets/img/elements/9.jpg" alt="Card image">
																				</div>
																			</div>
																		</div>
																	</div>
															  </div>
															</div>
														  </div>
															<!-- Day 2 -->
														  
														</div>
													</div>
													
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Vehicle List</h5>
														</div>
														<div class="col text-end">
															<div class="mb-0"><strong>Total Amount For Vehicle</strong><span class="badge bg-primary bg-glow ms-2">₹ <span id="total_amount_for_hotel">5,469</span></span></div>
														</div>
													</div>
													
													<div class="col-md-12">
														<div class="row mt-3">
														
															<!-- Day 1 -->
														  <div class="col-md-12">
															<div class="card mb-3">
																<div class="card-body p-0 px-3 py-2">
																	<div class="d-flex align-items-center justify-content-between mt-2">
																		<h6 class="mb-0">
																			<span>DAY 1 - March 29, 2024 </span>
																		</h6>
																		<h6 class="mb-0">
																			<span class="text-primary me-1">
																				<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>
																				Chennai, Tamil Nadu, India To Mahabalipuram, Tamil Nadu, India                                            </span>
																		</h6>
																		<h5 class="mb-0"><span class="badge alert-primary bg-glow ms-2">Total <strong class="ms-2">₹ 1,700 (+ ₹ <span class="gst_rate_176">17</span> Tax)</strong></span></h5>
																	</div>
																
																	<hr/>
																	
																	<div class="row justify-content-center">
																		<div class="col-4">
																			<small class="mb-0">Travel Distance &amp; Time</small>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>56.4 KM
																			</p>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				1 Hours 1 Minutes                                             </p>
																		</div>
																		<div class="col-4 text-center">
																			<small class="mb-0">Sight-seeing Distance &amp; Time</small>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i> 0KM
																			</p>
																			<p class="mb-0 fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				0 Hours 0 Minutes                                             </p>
																		</div>
																		<div class="col-4 text-end">
																			<small class="mb-0 text-primary">Total Distance &amp; Time</small>
																			<p class="mb-0 text-primary fw-bolder">
																				<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>57 KM
																			</p>
																			<p class="mb-0 text-primary fw-bolder">
																				<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
																				1 Hours 1 Minutes                                             </p>
																		</div>
																	</div>
																	
																	<hr/>
																	
																	<div class="row align-items-center">
																		<div class="col-12">
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
																								Max Occupancy: 5                                                        </h6>
																						</div>
																						<div class="col-md-auto text-primary mb-0 text-end">
																							<h5 class="mb-0 lh-1">
																								₹ 1,700.00                                                        </h5>

																							<small>+ ₹ 0 Vehicle + ₹ 17.00 Driver Tax</small>
																						</div>
																					</div>
																					<div class="row mb-2">
																						<div class="col-md-2">
																							<div class="d-flex flex-column align-items-center">
																																									<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="assets/img/exterior (1).jpg" alt="Sedan" data-bs-toggle="modal" data-bs-target="#modalCenter1_121_4" width="150" height="110" style="border: 1px solid #c33ca6;">
																																												<a href="https://www.youtube.com/watch?v=VIDEO_ID_HERE" target="_blank" class="button">Play Video</a>
																										
																																							</div>
																							<div class="modal fade" id="modalCenter1_121_4" tabindex="-1" aria-hidden="true">
																								<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																									<div class="modal-content">
																										<div class="modal-header">
																											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																										</div>
																										<div class="modal-body pt-0">

																											<div class="text-center mb-2">
																												<h5 class="modal-title" id="modalCenterTitle">Sedan </h5>
																												<h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"></h5>
																											</div>
																											<div id="swiper-gallery">
																												<div class="swiper gallery-top">
																													<div class="swiper-wrapper">
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-177541710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-10091710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-12141710760087.jpeg)"></div>
																																																		</div>
																													<!-- Add Arrows -->
																													<div class="swiper-button-next swiper-button-white"></div>
																													<div class="swiper-button-prev swiper-button-white"></div>
																												</div>
																												<div class="swiper gallery-thumbs">
																													<div class="swiper-wrapper">
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-177541710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-10091710760087.jpeg)"></div>
																																																					<div class="swiper-slide" style="background-image:url(http://localhost/dvi_travels/head//uploads/vehicle_gallery/DVIV-759047-12141710760087.jpeg)"></div>
																																																		</div>
																												</div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>

																						</div>
																						<div class="col-md-10">
																							<div class="row g-4">
																								<div class="col-2">
																									<small class="mb-0">Allowed kms</small>
																									<p class="mb-0 fw-bolder text-success">100</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra kms</small>
																									<p class="mb-0 fw-bolder">0</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Per day rental</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra kms</small>
																									<p class="mb-0 fw-bolder"><span class="text-primary">₹ 20.00</span></p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Permit charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>					
																								<div class="col-2">
																									<small class="mb-0">Driver Bhatta</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 500.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Driver Food Cost</small>
																									<p class="mb-0 fw-bolder"><span class="text-primary">₹ 400.00</span>
																									</p><p>
																								</p></div>
																								<div class="col-2">
																									<small class="mb-0">Accomdation Cost</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 500.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Extra Cost</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 300.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Toll Charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
																								</div>
																								<div class="col-2">
																									<small class="mb-0">Parking Charge</small>
																									<p class="mb-0 fw-bolder text-primary">₹ 0.00</p>
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
														  </div>
															<!-- Day 1 -->
														  
														</div>
													</div>
				
													<div class="divider my-4 divider-primary">
														<div class="divider-text">
															<i class="ti ti-map-2 ti-sm text-primary"></i>
														</div>
													</div>
													
													<div class="row align-items-center justify-content-between mb-4">
														<div class="col">
															<h5 class="card-header p-0 text-dark mb-0 text-uppercase">Summary</h5>
														</div>
													</div>
																<div class="card border p-0 mt-3">
																	<div class="order-calculations p-3">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotspot</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_hotspot_package">0</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Activity</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_activity_package">2,000</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotel</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_hotel_package">6,800</span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Vehicle</span>
                                                                            <h6 class="mb-0">₹ <span id="gross_total_vehicle_package">1,717</span></h6>
                                                                        </div>

                                                                        <hr>
                                                                        
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Gross Total for The Package</span>
                                                                            <h6 class="mb-0">₹  <span id="gross_total_package">2,069.00</span></h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">GST @ 5 % On The total Package
                                                                            </span>
                                                                            <h6 class="mb-0">₹  <span id="gst_total_package">103.45</span></h6>
                                                                        </div>
                                                                    </div>
																	<div class="d-flex justify-content-between  px-3 py-3 bg-primary" style="border-bottom-left-radius: 6px;border-bottom-right-radius:  6px;">
																		<h5 class="text-white fw-bold mb-0">Net Payable To Doview
                                                                                Holidays India Pvt ltd </h5>
																		<h5 class="mb-0 fw-bold text-white">₹ <span id="overall_vehicle_cost">
																				5,469.00                                </span></h5>
																	</div>
																</div>
												</div>
											
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

                        <!-- Social Button -->

                        <div class="text-end">
                            <button type="button" class="btn btn-google-plus downloadPdfBtn">
                                <img src="assets/img/icons/pdf-icon.svg" class="me-2"> PDF Download
                            </button>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include_once('public/__footer.php'); ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/js/forms-file-upload.js"></script>
    <script src="assets/vendor/libs/rateyo/rateyo.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $(".form-select").selectize();
        });
      function copyToClipboard() {
        var contentToCopy = document.getElementById("contentToCopy");
        var copybutton = document.getElementById("copy-button");
        copybutton.style.display = "none";
        var range = document.createRange();
        range.selectNode(contentToCopy);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        copybutton.style.display = "inline-block";

        // alert('Content copied to clipboard!');
      }
    </script>

</body>

</html>

<!-- beautify ignore:end -->
<!-- beautify ignore:end -->