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

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];
?>
        <div id="se-pre-con"></div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Tour Itinerary Plan</b></h5>
                    <a href="newitinerary.php?route=add&formtype=basic_info" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Route List</a>
                </div>
            </div>
            <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                <div>
                    <h5 class="text-capitalize"> Itinerary for <b>October 14, 2023</b> to <b>October 24, 2023</b> (<b>10</b> Day, <b>9</b> Night)</h5>
                    <h3 class="text-capitalize">Chennai <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i> Trivandrum airport Drop</h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">2</span></span>
                            <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                            <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                        </div>
                        <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2">₹ 55,000</span></h5>
                    </div>
                </div>
                <div>
                </div>
            </div>

            <div class="nav-align-top my-2 p-0">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary1" aria-controls="navs-top-itinerary1" aria-selected="true">Route Itinerary 1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary2" aria-controls="navs-top-itinerary2" aria-selected="false" tabindex="-1">Route Itinerary 2</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary3" aria-controls="navs-top-itinerary3" aria-selected="false" tabindex="-1">Route Itinerary 3</button>
                    </li>
                </ul>
                <div class="tab-content p-0 mt-3">
                    <div class="tab-pane fade active show" id="navs-top-itinerary1" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary">
                                        <div class=" d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                            <h5 class="card-title mb-sm-0 me-2">Route Itinerary 1</h5>
                                            <h4 class="card-title mb-sm-0 me-2">Overall Trip Cost <b class="text-primary">₹ 1,07,957</b></h4>
                                            <div class="action-btns">
                                                <button class="btn btn-label-github me-3" id="scrollToTopButton">
                                                    <span class="align-middle"> Back To Top</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-end">
                                            <label class="switch switch-square mb-3">
                                                <input type="checkbox" class="switch-input" id="switch_map" onchange="toggleMap()">
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on"></span>
                                                    <span class="switch-off"></span>
                                                </span>
                                                <span class="switch-label">Map</span>
                                            </label>
                                        </div>
                                        <div id="map-container" style="height: 300px; width: 100%; display: none;">
                                            <div id="map-container">
                                                <div class="row app-logistics-fleet-wrapper mb-3" id="itinerary_map_div">
                                                    <!-- Map Menu Button when screen is < md -->
                                                    <div class="flex-shrink-0 position-fixed m-4 d-md-none w-auto zindex-1">
                                                        <button class="btn btn-label-white border border-2 zindex-2 p-2" data-bs-toggle="sidebar" data-overlay="" data-target="#app-logistics-fleet-sidebar"><i class="ti ti-menu-2"></i></button>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <!-- Mapbox Map container -->
                                                        <div class="col h-100 map-container" id="google-map-container" style="display: none;">
                                                            <!-- Map -->
                                                            <div id="google-map" class="h-100 w-100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <button onclick="addLocationByName()">Add Location by Name</button> -->
                                        <!-- Menu Accordion -->
                                        <div class="accordion" id="day" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar" style="--bs-accordion-bg: #f8f7fa;">

                                            <!-- Day 1 -->
                                            <div class="accordion-item border-0 active bg-white rounded-3" id="fl-1">
                                                <div class="accordion-header itinerary-sticky-title p-0 mb-3" id="dayOne">
                                                    <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#day1" aria-expanded="true">
                                                        <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-wrapper">
                                                                    <div class="avatar me-2">
                                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="d-flex">
                                                                    <h6 class="mb-0">October 14, 2023 (Saturday)</h6>
                                                                </span>
                                                            </div>

                                                            <div class="d-none" id="itinerary_customized_cost">
                                                                <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
                                                            </div>
                                                            <!-- <button type="button" class="btn btn-icon btn-label-primary waves-effect mx-2 bg-transparent" id="edit_itinerary_btn_click" onclick="edit_itinerary_btn_click()"><i class="tf-icons ti ti-edit text-primary fs-xlarge"></i></button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="day1" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-1 pb-0">
                                                        <div id="itinerary_hotspot_list_day1">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="text-uppercase mb-0">Itinerary</h5>
                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_itinerary_daywise_click()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                            </div>
                                                            <ul class="timeline pt-3 px-3 mb-0">
                                                                <li class="timeline-item timeline-item-transparent">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success">
                                                                        <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event">
                                                                        <div class="timeline-header">
                                                                            <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                        </div>
                                                                        <p class="mb-0">Depart from stay</p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 hotspot_selection_section">
                                                                    <div class="hotspot_section">
                                                                        <div class="col-12 d-flex align-items-center">
                                                                            <div class="col-6">
                                                                                <h5 class="card-header px-2">Select Hotspot Places</h5>
                                                                                <div class="mb-3 row px-2">
                                                                                    <label for="html5-search-input" class="col-md-2 col-form-label">Search</label>
                                                                                    <div class="col-md-10">
                                                                                        <input class="form-control" type="search" value="Museum" id="html5-search-input" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <label class="switch switch-primary">
                                                                                    <input type="checkbox" class="switch-input" name="formValidationSwitch" id="showHotspotSwitch" />
                                                                                    <span class="switch-toggle-slider">
                                                                                        <span class="switch-on">
                                                                                            <i class="ti ti-check"></i>
                                                                                        </span>
                                                                                        <span class="switch-off">
                                                                                            <i class="ti ti-x"></i>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="switch-label">Show Suggested Hotspot Places</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <label class="pb-4 p-3 card me-3 mb-3" for="selectedhotspot">
                                                                            <div class="mb-5">
                                                                                <input type="radio" name="hotspotRadio" id="selectedhotspot" class="selectedhotspot" checked>
                                                                                <div class="custom-radio"></div>
                                                                            </div>
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Government Museum</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore.
                                                                            </p>
                                                                            <div class="col-md p-4 guide">
                                                                                <small class="text-light fw-medium d-block">Do you need a guide?</small>
                                                                                <div class="form-check form-check-inline mt-3">
                                                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Yes">
                                                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Yes">
                                                                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="No">
                                                                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                                                                </div>
                                                                            </div>
                                                                        </label>

                                                                        <div class="timeline pt-3 mb-0 px-1 row show_hotspot show-hotspot-content">
                                                                            <div class="col-12">
                                                                                <h6 class="mt-2">Suggested Hotspot Places</h6>
                                                                                <hr class="mt-0" />
                                                                            </div>
                                                                            <div class="col-12 d-flex">
                                                                                <label class="pb-4 col-4 p-3 card me-3" for="hotspot1">
                                                                                    <div class="mb-5">
                                                                                        <input type="radio" name="hotspotRadio" id="hotspot1">
                                                                                        <div class="custom-radio"></div>
                                                                                    </div>
                                                                                    <div class="d-flex flex-sm-row flex-column">
                                                                                        <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                        <div class="w-100">
                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                                <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                            </div>
                                                                                            <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                            <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                            <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                        Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore.
                                                                                    </p>
                                                                                </label>
                                                                                <label class="pb-4 col-4 p-3 card me-3" for="hotspot2">
                                                                                    <div class="mb-5">
                                                                                        <input type="radio" name="hotspotRadio" id="hotspot2">
                                                                                        <div class="custom-radio"></div>
                                                                                    </div>
                                                                                    <div class="d-flex flex-sm-row flex-column">
                                                                                        <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                        <div class="w-100">
                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                <h6 class="mb-0 text-capitalize">Government Museum/h6>
                                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                            </div>
                                                                                            <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                            <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                            <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                        Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore.
                                                                                    </p>
                                                                                </label>
                                                                                <label class="pb-4 col-4 p-3 card me-3" for="hotspot3">
                                                                                    <div class="mb-5">
                                                                                        <input type="radio" name="hotspotRadio" id="hotspot3">
                                                                                        <div class="custom-radio"></div>
                                                                                    </div>
                                                                                    <div class="d-flex flex-sm-row flex-column">
                                                                                        <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                        <div class="w-100">
                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                                <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                            </div>
                                                                                            <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                            <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                            <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                        Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore.
                                                                                    </p>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 my-3 text-end">
                                                                            <button type="submit" name="submitButton" class="btn btn-primary">Add</button>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <!-- <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                        </p>
                                                                    </div>
                                                                </li> -->
                                                                <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                        <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event">
                                                                        <div class="timeline-header">
                                                                            <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                        </div>
                                                                        <p class="mb-0">Relax at stay</p>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="d-none" id="edit_itinerary_daywise_div">
                                                            <!-- Itinerary Customization -->
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div>
                                                                    <h5 class="text-capitalize mb-0">Itinerary Customization</h5>
                                                                    <p class="text-secondary mb-0">Select the hotspots you would like to include for visit.</p>
                                                                </div>
                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button>
                                                            </div>
                                                            <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                            <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                <option value="">Search Hotspot</option>
                                                                <option value="1">B.M. Birla Planetarium</option>
                                                                <option value="2">Chennai Snake Park</option>
                                                            </select>

                                                            <div class="row mb-5">
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox1" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox2">
                                                                            <div class="itinerary_card_image">
                                                                                <img src="../assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                <div class="itinerary_card_activity_label">Activity Available</div>
                                                                            </div>
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Kapaleeshwarar Temple </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox2" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">10 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox3">
                                                                            <img src="../assets/img/itinerary/hotspots/government_museum_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Government Museum Chennai </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox3" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">12 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">4 Hours</p>
                                                                                    <p class="mb-0">₹ 250</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox4">
                                                                            <img src="../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox4" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">₹ 25</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox5">
                                                                            <img src="../assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Pondy Bazaar </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox5" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">6 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">3 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                        <div>
                                                                            <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                        </div>
                                                                        <h5 class="text-primary">Add Hotspot To Visit</h5>
                                                                    </button>
                                                                </div>

                                                                <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                    <div class="custom-option custom-option-icon h-100">
                                                                        <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                        <div class="row">
                                                                            <!-- With arrows -->
                                                                            <div class="col-md-12 mb-1">
                                                                                <div class="swiper" id="swiper-with-arrows-itinerary">
                                                                                    <div class="swiper-wrapper">
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                    </div>
                                                                                    <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                    </div>
                                                                                    <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span class="custom-option-body px-4">
                                                                            <div class="d-flex justify-content-between align-items-center my-2">
                                                                                <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>

                                                                                <div class="d-flex">
                                                                                    <button type="button" class="btn rounded-pill btn-outline-dribbble waves-effect me-3">
                                                                                        <span class="ti-xs ti ti-circle-plus me-1"></span>Add Activity
                                                                                    </button>
                                                                                    <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="text-primary mb-0 d-flex">
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                            </h6>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <p class="mb-0 d-flex">Average Visit Duration
                                                                                    <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                    <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                    <table class="table table-borderless text-start table-sm mb-0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Adults
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Children
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Infants
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <p class="mb-1 d-flex px-4 pt-2">
                                                                                        <b> Total Visit Cost</b>
                                                                                        <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                        <input class="form-check-input" type="checkbox" value="" id="itinerary_addguide">
                                                                                        <label class="form-check-label me-2" for="addguide">
                                                                                            Add Guide
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="p-2 rounded text-center d-none" id="itinerary_guide_form" style="background-color: rgba(75,75,75,.04);">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 text-start">
                                                                                                <label class="itinerary-destination-text-label w-100 text-black mb-2" for="itinerary_guide_language">Language<span class=" text-danger">
                                                                                                        *</span></label>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox1">Tamil</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox2">English</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox3">Hindi</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex justify-content-between mt-4">
                                                                                <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                <button type="button" class="btn btn-primary waves-effect">
                                                                                    <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                </button>
                                                                            </div>
                                                                        </span>
                                                                        <!-- </label> -->
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <!-- Itinerary Customization -->


                                                            <!-- Activity Customization -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="text-capitalize mb-0">Activity Customization</h5>
                                                                <!-- <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button> -->
                                                            </div>

                                                            <p class="text-secondary">Select the activities you would like to include for visit.</p>
                                                            <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                            <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                <option value="">Search Activity</option>
                                                                <option value="1">B.M. Birla Planetarium</option>
                                                                <option value="2">Chennai Snake Park</option>
                                                            </select>

                                                            <div class="row mb-5">
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1">
                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon1" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                        <div>
                                                                            <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                        </div>
                                                                        <h5 class="text-primary">Add Activity</h5>
                                                                    </button>
                                                                </div>

                                                                <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                    <div class="custom-option custom-option-icon h-100">
                                                                        <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                        <div class="row">
                                                                            <!-- With arrows -->
                                                                            <div class="col-md-12 mb-1">
                                                                                <div class="swiper" id="swiper-with-arrows-activity">
                                                                                    <div class="swiper-wrapper">
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                    </div>
                                                                                    <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                    </div>
                                                                                    <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span class="custom-option-body px-2">
                                                                            <div class="d-flex justify-content-between align-items-center my-2">
                                                                                <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                            </div>
                                                                            <h6 class="text-primary mb-0 d-flex">
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                            </h6>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <p class="mb-0 d-flex">Average Visit Duration
                                                                                    <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                    <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                            </div>

                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                    <table class="table table-borderless text-start table-sm mb-0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Adults
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Children
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Infants
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <p class="mb-1 d-flex px-4 pt-2">
                                                                                        <b> Total Visit Cost</b>
                                                                                        <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                        <input class="form-check-input" type="checkbox" value="" id="itinerary_addguide">
                                                                                        <label class="form-check-label me-2" for="addguide">
                                                                                            Add Guide
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="p-2 rounded text-center d-none" id="itinerary_guide_form" style="background-color: rgba(75,75,75,.04);">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 text-start">
                                                                                                <label class="itinerary-destination-text-label w-100 text-black mb-2" for="itinerary_guide_language">Language<span class=" text-danger">
                                                                                                        *</span></label>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox1">Tamil</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox2">English</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox3">Hindi</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between mt-4">
                                                                                <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                <button type="button" class="btn btn-primary waves-effect">
                                                                                    <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                </button>
                                                                            </div>
                                                                        </span>
                                                                        <!-- </label> -->
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <!-- Activity Customization -->


                                                            <div class="text-center">
                                                                <button type="button" class="btn btn-label-linkedin waves-effect" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Build a day trip </button>
                                                            </div>

                                                            <!-- <div
                                                                                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row"
                                                                                        >
                                                                                        <h5 class="card-title mb-sm-0 me-2">Sticky Action Bar</h5>
                                                                                        <div class="action-btns">
                                                                                            <button class="btn btn-label-primary me-3">
                                                                                            <span class="align-middle"> Back</span>
                                                                                            </button>
                                                                                            <button class="btn btn-primary">Place Order</button>
                                                                                        </div>
                                                                                        </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Day 2 -->
                                            <div class="accordion-item border-0 active bg-white rounded-3 d-none" id="fl-2">
                                                <div class="accordion-header itinerary-sticky-title p-0 mb-3" id="dayTwo">
                                                    <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#day2" aria-expanded="true">
                                                        <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-wrapper">
                                                                    <div class="avatar me-2">
                                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="d-flex">
                                                                    <h6 class="mb-0">October 15, 2023 (Sunday)</h6>
                                                                </span>
                                                            </div>

                                                            <div class="d-none" id="itinerary_customized_cost">
                                                                <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
                                                            </div>
                                                            <!-- <button type="button" class="btn btn-icon btn-label-primary waves-effect mx-2 bg-transparent" id="edit_itinerary_btn_click" onclick="edit_itinerary_btn_click()"><i class="tf-icons ti ti-edit text-primary fs-xlarge"></i></button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="day2" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-1 pb-0">
                                                        <div id="itinerary_hotspot_list_day1">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="text-uppercase mb-0">Itinerary</h5>
                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_itinerary_daywise_click()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                            </div>
                                                            <ul class="timeline pt-3 px-3 mb-0">
                                                                <li class="timeline-item timeline-item-transparent">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success">
                                                                        <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event">
                                                                        <div class="timeline-header">
                                                                            <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                        </div>
                                                                        <p class="mb-0">Depart from stay</p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore. Situated on the beach, are the Samadhis or memorials dedicated to C.N.Annadurai and M.G.Ramachandran, both former Chief Ministers of the state.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>234, Ramakrishna Mutt Rd, Mylapore, Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>10 AM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                        <i class="ti ti-map-pin rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event pb-3">
                                                                        <div class="d-flex flex-sm-row flex-column">
                                                                            <img src="../assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                            <div class="w-100">
                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                    <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                    <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                </div>
                                                                                <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mt-2" style="text-align: justify;">
                                                                            Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                        </p>
                                                                    </div>
                                                                </li>
                                                                <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                        <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                    </span>
                                                                    <div class="timeline-event">
                                                                        <div class="timeline-header">
                                                                            <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                        </div>
                                                                        <p class="mb-0">Relax at stay</p>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="d-none" id="edit_itinerary_daywise_div">
                                                            <!-- Itinerary Customization -->
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div>
                                                                    <h5 class="text-capitalize mb-0">Itinerary Customization</h5>
                                                                    <p class="text-secondary mb-0">Select the hotspots you would like to include for visit.</p>
                                                                </div>
                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button>
                                                            </div>
                                                            <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                            <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                <option value="">Search Hotspot</option>
                                                                <option value="1">B.M. Birla Planetarium</option>
                                                                <option value="2">Chennai Snake Park</option>
                                                            </select>

                                                            <div class="row mb-5">
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox1" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox2">
                                                                            <div class="itinerary_card_image">
                                                                                <img src="../assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                <div class="itinerary_card_activity_label">Activity Available</div>
                                                                            </div>
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Kapaleeshwarar Temple </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox2" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">10 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox3">
                                                                            <img src="../assets/img/itinerary/hotspots/government_museum_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Government Museum Chennai </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox3" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">12 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">4 Hours</p>
                                                                                    <p class="mb-0">₹ 250</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox4">
                                                                            <img src="../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox4" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">₹ 25</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox5">
                                                                            <img src="../assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Pondy Bazaar </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox5" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">6 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">3 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                        <div>
                                                                            <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                        </div>
                                                                        <h5 class="text-primary">Add Hotspot To Visit</h5>
                                                                    </button>
                                                                </div>

                                                                <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                    <div class="custom-option custom-option-icon h-100">
                                                                        <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                        <div class="row">
                                                                            <!-- With arrows -->
                                                                            <div class="col-md-12 mb-1">
                                                                                <div class="swiper" id="swiper-with-arrows-itinerary">
                                                                                    <div class="swiper-wrapper">
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                    </div>
                                                                                    <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                    </div>
                                                                                    <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span class="custom-option-body px-4">
                                                                            <div class="d-flex justify-content-between align-items-center my-2">
                                                                                <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>

                                                                                <div class="d-flex">
                                                                                    <button type="button" class="btn rounded-pill btn-outline-dribbble waves-effect me-3">
                                                                                        <span class="ti-xs ti ti-circle-plus me-1"></span>Add Activity
                                                                                    </button>
                                                                                    <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="text-primary mb-0 d-flex">
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                            </h6>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <p class="mb-0 d-flex">Average Visit Duration
                                                                                    <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                    <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                    <table class="table table-borderless text-start table-sm mb-0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Adults
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Children
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Infants
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <p class="mb-1 d-flex px-4 pt-2">
                                                                                        <b> Total Visit Cost</b>
                                                                                        <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                        <input class="form-check-input" type="checkbox" value="" id="itinerary_addguide">
                                                                                        <label class="form-check-label me-2" for="addguide">
                                                                                            Add Guide
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="p-2 rounded text-center d-none" id="itinerary_guide_form" style="background-color: rgba(75,75,75,.04);">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 text-start">
                                                                                                <label class="itinerary-destination-text-label w-100 text-black mb-2" for="itinerary_guide_language">Language<span class=" text-danger">
                                                                                                        *</span></label>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox1">Tamil</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox2">English</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox3">Hindi</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex justify-content-between mt-4">
                                                                                <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                <button type="button" class="btn btn-primary waves-effect">
                                                                                    <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                </button>
                                                                            </div>
                                                                        </span>
                                                                        <!-- </label> -->
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <!-- Itinerary Customization -->


                                                            <!-- Activity Customization -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="text-capitalize mb-0">Activity Customization</h5>
                                                                <!-- <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button> -->
                                                            </div>

                                                            <p class="text-secondary">Select the activities you would like to include for visit.</p>
                                                            <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                            <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                <option value="">Search Activity</option>
                                                                <option value="1">B.M. Birla Planetarium</option>
                                                                <option value="2">Chennai Snake Park</option>
                                                            </select>

                                                            <div class="row mb-5">
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <div class="form-check custom-option custom-option-icon h-100">
                                                                        <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1">
                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                            <span class="custom-option-body px-2">
                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                    <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                    <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon1" checked />
                                                                                </div>
                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                </h6>
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="mb-0">2 Hours</p>
                                                                                    <p class="mb-0">No Fare</p>
                                                                                </div>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                    <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                        <div>
                                                                            <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                        </div>
                                                                        <h5 class="text-primary">Add Activity</h5>
                                                                    </button>
                                                                </div>

                                                                <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                    <div class="custom-option custom-option-icon h-100">
                                                                        <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                        <div class="row">
                                                                            <!-- With arrows -->
                                                                            <div class="col-md-12 mb-1">
                                                                                <div class="swiper" id="swiper-with-arrows-activity">
                                                                                    <div class="swiper-wrapper">
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                        <div class="swiper-slide" style="background-image:url(../assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                    </div>
                                                                                    <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                    </div>
                                                                                    <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span class="custom-option-body px-2">
                                                                            <div class="d-flex justify-content-between align-items-center my-2">
                                                                                <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                            </div>
                                                                            <h6 class="text-primary mb-0 d-flex">
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                                <i class="ti ti-star-filled ti-xs"></i>
                                                                            </h6>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="my-1 d-flex">
                                                                                    Trip Time
                                                                                    <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                                <p class="mb-0 d-flex">Average Visit Duration
                                                                                    <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                    <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                </p>
                                                                            </div>

                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                    <table class="table table-borderless text-start table-sm mb-0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Adults
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Children
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                        <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                            Infants
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <p class="mb-1 d-flex px-4 pt-2">
                                                                                        <b> Total Visit Cost</b>
                                                                                        <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <div class="form-check mt-1 me-1 ps-0">
                                                                                        <input class="form-check-input" type="checkbox" value="" id="itinerary_addguide">
                                                                                        <label class="form-check-label me-2" for="addguide">
                                                                                            Add Guide
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="p-2 rounded text-center d-none" id="itinerary_guide_form" style="background-color: rgba(75,75,75,.04);">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 text-start">
                                                                                                <label class="itinerary-destination-text-label w-100 text-black mb-2" for="itinerary_guide_language">Language<span class=" text-danger">
                                                                                                        *</span></label>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox1">Tamil</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox2">English</label>
                                                                                                </div>
                                                                                                <div class="form-check ps-0">
                                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option1">
                                                                                                    <label class="form-check-label" for="inlineCheckbox3">Hindi</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between mt-4">
                                                                                <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                <button type="button" class="btn btn-primary waves-effect">
                                                                                    <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                </button>
                                                                            </div>
                                                                        </span>
                                                                        <!-- </label> -->
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <!-- Activity Customization -->


                                                            <div class="text-center">
                                                                <button type="button" class="btn btn-label-linkedin waves-effect" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Build a day trip </button>
                                                            </div>

                                                            <!-- <div
                                                                                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row"
                                                                                        >
                                                                                        <h5 class="card-title mb-sm-0 me-2">Sticky Action Bar</h5>
                                                                                        <div class="action-btns">
                                                                                            <button class="btn btn-label-primary me-3">
                                                                                            <span class="align-middle"> Back</span>
                                                                                            </button>
                                                                                            <button class="btn btn-primary">Place Order</button>
                                                                                        </div>
                                                                                        </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card border border-primary">
                                                <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                                                    <h5 class="card-header p-0">Hotel List</h5>
                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" id="customize_hotel_btn" onclick="edit_itinerary_hotel_customize()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize Hotel </button>
                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm d-none" id="customize_back_hotel_btn" onclick="back_itinerary_hotel_customize()"> <i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back To Hotel List </button>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex p-3">
                                                        <span class="mb-0 me-4"><strong>Total Rooms</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                        <span class="mb-0 me-4"><strong>Total Extra Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                        <span class="mb-0 me-4"><strong>Child No Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                    </div>

                                                    <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹28,900</span></div>
                                                </div>

                                                <div id="hotel_preview_table_div">
                                                    <div class="table-responsive text-nowrap">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Dates</th>
                                                                    <th>Location</th>
                                                                    <th>Hotel Name</th>
                                                                    <th>Room</th>
                                                                    <th>Meal</th>
                                                                    <th>Cost</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                <tr>
                                                                    <td>
                                                                        October 14, 2023
                                                                        <br />
                                                                        October 15, 2023
                                                                        <br />
                                                                        (2N)
                                                                    </td>
                                                                    <td>Chennai</td>
                                                                    <td id="hotel_name_edit"><span class="fw-medium">Zion by the Park</span></td>
                                                                    <td id="hotel_room_edit">Standard</td>
                                                                    <td id="hotel_meal_edit">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 10,282</td>
                                                                    <td id="hotel_rowwise_submit">
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 16, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Pondicherry</td>
                                                                    <td><span class="fw-medium">Misty Ocean</span></td>
                                                                    <td>Premium</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 17, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Tanjore</td>
                                                                    <td><span class="fw-medium">Grand Ashoka</span></td>
                                                                    <td>Premium</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 18, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Trichy</td>
                                                                    <td><span class="fw-medium">Hotel Rockfort View</span></td>
                                                                    <td>Standard</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 19, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Madurai</td>
                                                                    <td><span class="fw-medium">Mmr Garden</span></td>
                                                                    <td>Executive</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 20, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Rameswaram</td>
                                                                    <td><span class="fw-medium">Star Palace</span></td>
                                                                    <td>Executive</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 21, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Kanyakumari</td>
                                                                    <td><span class="fw-medium">Gopi Niva Grand</span></td>
                                                                    <td>Superior</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 22, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Trivandrum</td>
                                                                    <td><span class="fw-medium">Biverah</span></td>
                                                                    <td>Executive</td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                            <span class="ti ti-edit"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="d-none" id="hotel_customization_div">
                                                    <div class="mx-2 demo-inline-spacing">
                                                        <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect"><i class="tf-icons ti ti-check ti-xs me-1"></i> 5 Star Hotel</button>
                                                        <button type="button" class="btn rounded-pill btn-label-pinterest waves-effect"> 4 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-info waves-effect"> 3 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-slack waves-effect"> 2 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-github waves-effect"> 1 Star Hotel</button>
                                                    </div>

                                                    <div class="table-responsive text-nowrap mt-3">
                                                        <table class="table table-striped  table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Dates</th>
                                                                    <th>Location</th>
                                                                    <th>Hotel Name</th>
                                                                    <th>Room</th>
                                                                    <th>Meal</th>
                                                                    <th>Cost</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        Oct 14, 2023
                                                                        <br />
                                                                        Oct 15, 2023
                                                                        <br />
                                                                        (2N)
                                                                    </td>
                                                                    <td>Chennai</td>
                                                                    <td id="hotel_name_edit_customize">
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Zion by the Park <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Lemon Tree <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">The Residency Towers <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td id="hotel_room_edit_customize">
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td id="hotel_meal_edit_customize">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 10,282</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 16, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Pondicherry</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Misty Ocean <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Shenbaga <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Le Pondy <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 17, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Tanjore</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Grand Ashoka <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Sangam <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 18, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Trichy</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 19, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Madurai</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 20, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Rameswaram</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 21, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Kanyakumari</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 22, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Trivandrum</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Biverah <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>₹ 5,140</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="card border border-primary mt-4">
                                                <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                                                    <h5 class="card-header p-0">Vehicle List</h5>
                                                    <!-- <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" id="customize_vehicle_btn" onclick="edit_itinerary_vehicle_customize()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize vehicle </button>
                                                                        <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm d-none" id="customize_back_vehicle_btn" onclick="back_itinerary_vehicle_customize()"> <i
                                                                            class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back To vehicle List </button> -->
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex p-3">
                                                        <span class="mb-0 me-4"><strong>Total Passengers</strong><span class="badge badge-center bg-primary bg-glow mx-2">6</span></span>
                                                        <span class="mb-0 me-4"><strong>Total vehicle</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                    </div>
                                                    <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For vehicle</strong><span class="badge bg-primary bg-glow ms-2">₹10,000</span></div>
                                                </div>
                                                <div id="vehicle_preview_table_div">
                                                    <div class="table-responsive text-nowrap">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Travel Date</th>
                                                                    <th>Travel Places</th>
                                                                    <th>Distance (Kms)</th>
                                                                    <th>Sight-seeing distance (Kms)</th>
                                                                    <th>Total Distance (Kms)</th>
                                                                    <th>Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                <tr>
                                                                    <td>
                                                                        October 14, 2023
                                                                    </td>
                                                                    <td>Chennai Local</td>
                                                                    <td>0</td>
                                                                    <td>30</td>
                                                                    <td>30</td>
                                                                    <td>2 hours 30 minutes</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 15, 2023
                                                                    </td>
                                                                    <td>Chennai to
                                                                        <br />
                                                                        Tanjore
                                                                    </td>
                                                                    <td>345</td>
                                                                    <td>25</td>
                                                                    <td>370</td>
                                                                    <td>8 hours 30 minutes</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 17, 2023
                                                                    </td>
                                                                    <td>Tanjore to
                                                                        <br />
                                                                        Trichy
                                                                    </td>
                                                                    <td>60</td>
                                                                    <td>10</td>
                                                                    <td>70</td>
                                                                    <td>5 hours 00 minutes</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 18, 2023
                                                                    </td>
                                                                    <td>Trichy to
                                                                        <br />
                                                                        Madurai
                                                                    </td>
                                                                    <td>132</td>
                                                                    <td>28</td>
                                                                    <td>160</td>
                                                                    <td>N/A</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 19, 2023
                                                                    </td>
                                                                    <td>Madurai to
                                                                        <br />
                                                                        Kanyakumari
                                                                    </td>
                                                                    <td>244</td>
                                                                    <td>46</td>
                                                                    <td>290</td>
                                                                    <td>N/A</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>October 20, 2023
                                                                    </td>
                                                                    <td>Kanyakumari to
                                                                        <br />
                                                                        Trivandrum
                                                                    </td>
                                                                    <td>95</td>
                                                                    <td>120</td>
                                                                    <td>215</td>
                                                                    <td>N/A</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="p-3">
                                                        <h5 class="card-header p-0 mb-2">Vehicle Details</h5>
                                                        <div class="order-calculations">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-heading">Indigo * 2</span>
                                                                <h6 class="mb-0">₹2,760</h6>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-heading fw-bold">Total Vehicle Cost</span>
                                                                <h6 class="mb-0 fw-bold">₹2,760</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="hr hr-blurry" />
                                                <div class="col-md-12">
                                                    <div class="p-3">
                                                        <h5 class="card-header p-0 mb-2">Overall Cost</h5>
                                                        <div class="order-calculations">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-heading">Gross Total for The Package</span>
                                                                <h6 class="mb-0">₹1,37,304</h6>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-heading">GST @ 5 % On The total Package </span>
                                                                <h6 class="mb-0">₹6,865</h6>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-heading fw-bold">Nett Payable To Doview Holidays India Pvt ltd</span>
                                                                <h6 class="mb-0 fw-bold">₹1,44,169</h6>
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
                                                    <button type="button" class="btn rounded-pill btn-success waves-effect waves-light">
                                                        <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share Via Whatsapp
                                                    </button>
                                                </div>
                                                <div class="demo-inline-spacing">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light">
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
                <div class="tab-pane fade" id="navs-top-itinerary2" role="tabpanel">
                    <p>
                        Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice cream. Gummies
                        halvah
                        tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake fruitcake.
                    </p>
                    <p class="mb-0">
                        Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah cotton candy
                        liquorice caramels.
                    </p>
                </div>
                <div class="tab-pane fade" id="navs-top-itinerary3" role="tabpanel">
                    <p>
                        Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies cupcake gummi
                        bears
                        cake chocolate.
                    </p>
                    <p class="mb-0">
                        Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet roll icing
                        sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly jelly-o tart brownie
                        jelly.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">B.M. Birla Planetarium</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12 mb-0">
                                <label for="emailWithTitle" class="form-label text-black">Time Range</label>
                            </div>
                            <div class="col mb-0">
                                <label for="emailWithTitle" class="form-label text-black">From</label>
                                <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_from" />
                            </div>
                            <div class="col mb-0">
                                <label for="emailWithTitle" class="form-label text-black">To</label>
                                <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_to" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/vendor/libs/leaflet/leaflet.js"></script>
        <script src="assets/js/maps-leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script src="https://unpkg.com/leaflet-curved/src/leaflet-curved.js"></script>
        <script>
            var map;
            var indiaCenter = [20.5937, 78.9629]; // Center of India
            var locations = [{
                    name: 'Delhi',
                    lat: 28.6139,
                    lng: 77.2090
                },
                {
                    name: 'Maharashtra',
                    lat: 19.7515,
                    lng: 75.7139
                },
                {
                    name: 'Tamil Nadu',
                    lat: 11.1271,
                    lng: 78.6569
                },
                {
                    name: 'Karnataka',
                    lat: 15.3173,
                    lng: 75.7139
                },
                // Add more states as needed
            ];

            function toggleMap() {
                var mapContainer = document.getElementById('map-container');
                var switchMap = document.getElementById('switch_map');

                if (switchMap.checked) {
                    mapContainer.style.display = 'block';
                    initializeMap();
                } else {
                    mapContainer.style.display = 'none';
                }
            }

            function initializeMap() {
                map = L.map('map-container').setView(indiaCenter, 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Custom icon for markers (Google Maps pin icon with number)
                function createNumberedIcon(number) {
                    return L.divIcon({
                        className: 'numbered-marker',
                        html: '<img class="google-pin" src="https://maps.google.com/mapfiles/ms/icons/red-dot.png"><div class="number">' + number + '</div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                    });
                }

                // Add markers for each state
                var markers = locations.map(function(location, index) {
                    return L.marker([location.lat, location.lng], {
                        icon: createNumberedIcon(index + 1)
                    }).bindPopup(location.name);
                });

                // Add a polyline connecting the markers
                var polyline = L.polyline(
                    markers.map(function(marker) {
                        return marker.getLatLng();
                    }), {
                        color: 'blue'
                    }
                ).addTo(map);

                // Add markers and polyline to a feature group
                var featureGroup = L.featureGroup(markers.concat(polyline)).addTo(map);

                // Fit the map to the bounds of the feature group
                map.fitBounds(featureGroup.getBounds());
            }

            // Show hotspot places
            let showHotspotSwitch = document.getElementById("showHotspotSwitch");
            let hotspotContent = document.querySelector(".show-hotspot-content");
            let selectedHotspotRadio = document.getElementById("selectedhotspot");

            let toggleHotspotContent = () => {
                hotspotContent.classList.toggle("show-hotspot-content");
            };

            window.addEventListener("load", toggleHotspotContent);
            showHotspotSwitch.addEventListener("change", toggleHotspotContent);

            document.addEventListener("DOMContentLoaded", function() {
                // Get all radio buttons and card elements
                let radioButtons = document.querySelectorAll('input[name="hotspotRadio"]');
                let cards = document.querySelectorAll('.show-hotspot-content .card');

                // Default selection
                selectedHotspotRadio.checked = true;

                // Add event listeners to each radio button
                radioButtons.forEach((radio, index) => {
                    radio.addEventListener('change', () => {
                        // Remove the 'highlight' class from all cards
                        cards.forEach(card => card.classList.remove('highlight'));

                        // Add the 'highlight' class to the selected card
                        if (radio.checked) {
                            cards[index].classList.add('highlight');
                        }
                    });
                });
            });

            // Function to add style to the label when radio is checked
            function handleCheckboxChange() {
                var checkbox = document.getElementById('showHotspotSwitch');
                var label = document.querySelector('label[for="selectedhotspot"]');
                var cards = document.querySelectorAll('.show-hotspot-content .card');

                // Check if the checkbox is checked
                if (checkbox.checked) {
                    // Add a class to the label
                    label.classList.add('checked-style');

                    // Remove the 'highlight' class from all cards
                    cards.forEach(card => card.classList.remove('highlight'));

                    // Add the 'highlight' class to the selected card if any radio is checked
                    if (selectedHotspotRadio.checked) {
                        let index = Array.from(cards).indexOf(document.querySelector('label[for="selectedhotspot"]'));
                        cards[index].classList.add('highlight');
                    }
                } else {
                    // Remove the class if the checkbox is not checked
                    label.classList.remove('checked-style');

                    // Remove border color from all cards
                    cards.forEach(card => card.classList.remove('highlight'));
                }
            }

            // Add event listener to the checkbox
            document.getElementById('showHotspotSwitch').addEventListener('change', handleCheckboxChange);
        </script>
        <style>
            #map-container {
                height: 500px;
            }

            .numbered-marker {
                text-align: center;
                font-weight: bold;
                color: white;
                position: relative;
            }

            .number {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }



            <?php
        endif;
    else :
        echo "Request Ignored";
    endif;
