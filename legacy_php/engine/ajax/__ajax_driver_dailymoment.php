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

    if ($_GET['type'] == 'show_welcome_driver') :

        $itinerary_plan_id = $_POST['ID'];
        $guide_ID = $_POST['GUIDE_ID'];
        $CSTMRID = $_POST['CSTMRID'];
        if ($CSTMRID != ''):
            $cstmr_id = Encryption::Decode($CSTMRID, SECRET_KEY);
            $itinerary_plan_id = Encryption::Decode($itinerary_plan_id, SECRET_KEY);

            $getstatus_query = sqlQUERY_LABEL("SELECT  `customer_salutation`, `customer_name`, `customer_age`, `primary_contact_no` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_id' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0' and `confirmed_itinerary_customer_ID` ='$cstmr_id'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
            while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
                $customer_salutation = $getstatus_fetch['customer_salutation'];
                $customer_name = $getstatus_fetch['customer_name'];
                $customer_age = $getstatus_fetch['customer_age'];
                $primary_contact_no = $getstatus_fetch['primary_contact_no'];
            endwhile;

        endif;

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT 
    cip.`confirmed_itinerary_plan_ID`, 
    cip.`itinerary_plan_ID`, 
    cip.`agent_id`, 
    cip.`staff_id`, 
    cip.`location_id`, 
    cip.`arrival_location`, 
    cip.`departure_location`, 
    cip.`itinerary_quote_ID`, 
    cip.`trip_start_date_and_time`, 
    cip.`trip_end_date_and_time`, 
    cip.`arrival_type`, 
    cip.`departure_type`, 
    cip.`expecting_budget`, 
    cip.`itinerary_type`, 
    cip.`entry_ticket_required`, 
    cip.`no_of_routes`, 
    cip.`no_of_days`, 
    cip.`no_of_nights`, 
    cip.`total_adult`, 
    cip.`total_children`, 
    cip.`total_infants`, 
    vid.`vendor_id`, 
    vid.`vendor_vehicle_type_id`, 
    vid.`vehicle_id`, 
    vid.`driver_id` 
FROM 
    `dvi_confirmed_itinerary_plan_details` cip
JOIN 
    `dvi_confirmed_itinerary_vendor_driver_assigned` vid 
ON 
    cip.`itinerary_plan_ID` = vid.`itinerary_plan_ID`
WHERE 
    cip.`deleted` = '0' 
    AND cip.`status` = '1' 
    AND vid.`deleted` = '0' 
    AND vid.`status` = '1' 
    AND cip.`itinerary_plan_ID` = '$itinerary_plan_id'
") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_data['itinerary_quote_ID'];
            $driver_id = $fetch_data['driver_id'];
            $vendor_id = $fetch_data['vendor_id'];
            $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
            $vehicle_id = $fetch_data['vehicle_id'];
            $vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'get_vehicle_type_id');
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $driver_name =  getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
            $driver_mobile_no =  getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
            $vehicle_no =  getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_registration_number', "");
            $vehicle_type_title =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        endwhile;

        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;
?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 px-md-5 welcome-card">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="mb-3">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'):
                                ?>

                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                            </div>
                            <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>

                                <h4 class="text-white fw-bold mb-1 driver-name">
                                    Hi, <?= $customer_salutation; ?> <?= $customer_name; ?>
                                </h4>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $itinerary_quote_ID; ?>
                                </p>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $customer_age; ?>
                                </p>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $primary_contact_no; ?>
                                </p>
                            <?php else: ?>
                                <h4 class="text-white fw-bold mb-1 driver-name">
                                    Hi, <?= $driver_name; ?>
                                </h4>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $itinerary_quote_ID; ?>
                                </p>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $driver_mobile_no; ?>
                                </p>
                                <p class="text-white m-0 driver-vehicle-type">
                                    <?= $vehicle_type_title; ?> - <?= $vehicle_no; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <img src="assets/img/driver.png" width="170px" height="170px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3 px-2">
            <div class="col-12">
                <h5 class="my-3 fw-bold your-ride-title">Your Ride</h5>
                <div>
                    <h6 class="plan-datatime-text">
                        <span class="me-2"><img
                                src="assets/img/dailymoment/calendar.png"
                                width="15px"
                                height="15px" /></span><?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                    </h6>

                    <h5 class="plan-location-title m-0 mb-2">
                        <?= $arrival_location; ?>
                        <span class="mx-2"><img
                                src="assets/img/dailymoment/right-arrow.png"
                                width="26px"
                                height="26px" /></span>
                        <?= $departure_location; ?>
                    </h5>
                    <div class="d-flex align-items-center">
                        <?php if ($total_adult != '0'): ?>
                            <h6 class="person-count-text">
                                Adult <span class="person-count ms-1"><?= $total_adult; ?></span>
                            </h6>
                        <?php endif; ?>
                        <?php if ($total_children != '0'): ?>
                            <div class="vl mx-2 mb-1"></div>
                            <h6 class="person-count-text">
                                Child <span class="person-count ms-1"><?= $total_children; ?></span>
                            </h6>
                        <?php endif; ?>
                        <?php if ($total_infants != '0'): ?>
                            <div class="vl mx-2 mb-1"></div>
                            <h6 class="person-count-text">
                                Infant <span class="person-count ms-1"><?= $total_infants; ?></span>
                            </h6>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php

        $select_itinerary_plan_customer = sqlQUERY_LABEL("SELECT c.`confirmed_itinerary_customer_ID`, c.`confirmed_itinerary_plan_ID`, c.`itinerary_plan_ID`, c.`agent_id`, c.`primary_customer`, c.`customer_type`, c.`customer_name`, c.`customer_age`, c.`primary_contact_no`, c.`altenative_contact_no`, c.`arrival_date_and_time`, c.`arrival_place`,  c.`arrival_flight_details`, c.`departure_date_and_time`, c.`departure_place`, c.`departure_flight_details`,c.`email_id`, c.`createdby`, c.`createdon`, c.`updatedon`, c.`status`, c.`deleted`, r.`confirmed_itinerary_route_ID`, r.`itinerary_route_ID`, r.`itinerary_route_date`, date_range.day_1_itinerary_route_date, date_range.last_itinerary_route_date FROM `dvi_confirmed_itinerary_customer_details` AS c JOIN `dvi_confirmed_itinerary_route_details` AS r ON c.`itinerary_plan_ID` = r.`itinerary_plan_ID` JOIN (SELECT `itinerary_plan_ID`, MIN(`itinerary_route_date`) AS day_1_itinerary_route_date, MAX(`itinerary_route_date`) AS last_itinerary_route_date FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' GROUP BY `itinerary_plan_ID` ) AS date_range ON r.`itinerary_plan_ID` = date_range.`itinerary_plan_ID` WHERE c.`deleted` = '0' AND c.`status` = '1' AND c.`primary_customer` = '1' AND r.`deleted` = '0' AND r.`status` = '1' AND r.`itinerary_plan_ID` = '$itinerary_plan_id' AND c.`itinerary_plan_ID` = '$itinerary_plan_id' ORDER BY r.`itinerary_route_date` ASC LIMIT 1;") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_itinerary_plan_customer) > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_customer)) :
                $agent_id = $fetch_data['agent_id'];
                $customer_name = $fetch_data['customer_name'];
                $primary_contact_no = $fetch_data['primary_contact_no'];
                $arrival_place = $fetch_data['arrival_place'];
                $primary_contact_no = $fetch_data['primary_contact_no'];
                $arrival_flight_details = $fetch_data['arrival_flight_details'];
                $departure_flight_details = $fetch_data['departure_flight_details'];
                if ($arrival_place):
                    $arrival_place = $arrival_place;
                else:
                    $arrival_place = '';
                endif;
                if ($departure_place):
                    $departure_place = $departure_place;
                else:
                    $departure_place = '';
                endif;
                $arrival_date_and_time = $fetch_data['arrival_date_and_time'];
                $departure_date_and_time = $fetch_data['departure_date_and_time'];
                if ($arrival_date_and_time):
                    $formattedArrivalDateTime = date('d M Y H.i A', strtotime($arrival_date_and_time));
                else:
                    $formattedArrivalDateTime = '';
                endif;
                if ($departure_date_and_time):
                    $formattedDepartureDateTime = date('d M Y H.i A', strtotime($departure_date_and_time));
                else:
                    $formattedDepartureDateTime = '';
                endif;
                $formatted_arrival_flight_details   = '';
                $formatted_departure_flight_details   = '';
                if ($arrival_flight_details):
                    $formatted_arrival_flight_details .= ',<span style="font-size:16px; font-weight:600;">' . $arrival_flight_details . '</span>';
                else:
                    $formatted_arrival_flight_details = '';
                endif;
                if ($departure_flight_details):
                    $formatted_departure_flight_details .=  ',<span style="font-size:16px;font-weight:600;"> ' . $departure_flight_details . '</span>';
                else:
                    $formatted_departure_flight_details = '';
                endif;
                $email_id = $fetch_data['email_id'];
                $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
                $travel_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
                $travel_contactno = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
                $travel_emailid = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                $day_1_itinerary_route_date = $fetch_data['day_1_itinerary_route_date'];
                $last_itinerary_route_date = $fetch_data['last_itinerary_route_date'];

                if ($primary_contact_no != ''):
                    $primary_contact_no_updated .= ', ' . $fetch_data['primary_contact_no'];
                else:
                    $primary_contact_no_updated .= '';
                endif;

                if ($email_id_updated != ''):
                    $email_id_updated .= ', ' . $fetch_data['email_id'];
                else:
                    $email_id_updated .= '';
                endif;

                $total_children = $fetch_data['total_children'];
                $total_infants = $fetch_data['total_infants'];
            endwhile;

            $day_1_itinerary_route_date_formatted = date('Y-m-d', strtotime($day_1_itinerary_route_date)); // Ensures proper format
            $last_itinerary_route_date = date('Y-m-d', strtotime($last_itinerary_route_date)); // Ensures proper format
            // Get current date in the same format
            $current_date = date('Y-m-d');

        ?>
            <div class="row px-2">
                <?php if ($cstmr_id == '' || $cstmr_id == '0'): ?>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card p-3 py-2" style="background: #f5e9ff">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="travel-flex-container">
                                    <h5 class="guest-title fw-bold mb-3">Guest Details</h5>
                                    <span>
                                        <span class="guest-name mb-1 fw-bold"><?= $customer_name ?></span>
                                        <span class="guest-contact-no mb-0"><?= $primary_contact_no_updated ?> <?= $email_id_updated ?></span>
                                    </span>
                                    <p class="guest-emailid mb-1"><?= $arrival_place . ' ' . $formattedArrivalDateTime . ' ' . $formatted_arrival_flight_details . ' ==> ' . $departure_place . ' ' . $formattedDepartureDateTime . ' ' . $formatted_departure_flight_details ?></p>
                                </div>
                                <div>
                                    <img
                                        src="assets/img/svg/Group 21.svg"
                                        width="150px"
                                        height="150px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card p-3 py-2" style="background: #feebde">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="travel-flex-container">
                                    <h5 class="guest-title fw-bold mb-3">Travel Expert Details</h5>
                                    <h6 class="guest-name mb-1 fw-bold"><?= $travel_name; ?></h6>
                                    <p class="guest-contact-no mb-0"><?= $travel_contactno; ?></p>
                                    <p class="guest-emailid mb-1"><?= $travel_emailid; ?></p>
                                </div>
                                <div>
                                    <img
                                        src="assets/img/travel-expert.png"
                                        width="150px"
                                        height="150px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $special_instructions = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'spl_instructions');
                    if ($special_instructions != '' && $special_instructions != 'NULL') : ?>
                        <div class="col-12 col-md-12 mb-3">
                            <div class="card p-3 py-2" style="background: rgb(255 235 59 / 35%)">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="travel-flex-container">
                                        <h5 class="guest-title fw-bold mb-3">Special Instructions</h5>
                                        <p class="mt-2 mb-0 guest-emailid" style="text-align: justify;">
                                            <?= nl2br(html_entity_decode($special_instructions, ENT_QUOTES, 'UTF-8')) ?>
                                        </p>
                                    </div>
                                    <div class="p-3">
                                        <img
                                            src="assets/img/boy_with_shoe.png"
                                            width="100px"
                                            height="150px" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php
                $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$current_date'") or die("#1-UNABLE_TO_COLLECT_DRIVER_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                    $format_itinerary_route_date = $fetch_list_data['itinerary_route_date'];
                endwhile;

                $select_hotel_list_query = sqlQUERY_LABEL("SELECT `driver_opening_km`, `opening_speedmeter_image` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_id' and `vendor_vehicle_type_id` = '$vendor_vehicle_type_id' and `vehicle_id` = '$vehicle_id' LIMIT 1") or die("#1-UNABLE_TO_COLLECT_DRIVER_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                    $driver_opening_km = $fetch_list_data['driver_opening_km'];
                    $opening_speedmeter_image = $fetch_list_data['opening_speedmeter_image'];
                endwhile;

                ?>

                <?php if ($day_1_itinerary_route_date_formatted < $current_date && $last_itinerary_route_date < $current_date): ?>
                    <div class="col-12 justify-content-center blink d-flex align-items-center my-3">
                        <img src="assets/img/svg/chronometer.svg" class="me-1 blink trip-blink-notification" width="24px" />
                        <h6 class="fw-bold m-0"><span class="text-danger">Trip already Completed,</span><span> Click the below button view your trip details.<span></h6>
                    </div>
                <?php elseif ($day_1_itinerary_route_date_formatted === $current_date && empty($driver_opening_km) && empty($opening_speedmeter_image)): ?>
                    <div class="col-12 justify-content-center blink d-flex align-items-center my-3">
                        <img src="assets/img/svg/chronometer.svg" class="me-1 blink trip-blink-notification" width="24px" />
                        <h6 class="fw-bold m-0"><span class="text-danger">Trip not started,</span><span> Click start trip button view your trip details.<span></h6>
                    </div>
                <?php endif; ?>

                <div class="col-12 mb-3 driver-start-sticky">
                    <?php
                    if ($day_1_itinerary_route_date_formatted === $current_date && empty($driver_opening_km) && empty($opening_speedmeter_image) && $cstmr_id == ''): ?>
                        <button type="button"
                            onclick="showDRIVERKILOMETERMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>,<?= $vendor_id; ?>, <?= $vendor_vehicle_type_id; ?>, <?= $vehicle_id; ?>);"
                            data-bs-dismiss="modal"
                            class="start-trip-button">
                            Start Your Trip
                            <span><img class="ms-2" src="assets/img/fast-forward.png" width="20px" height="20px" /></span>
                        </button>
                    <?php elseif ($cstmr_id != '' && $cstmr_id != '0'): $encoded_itinerary_plan_id = Encryption::Encode($itinerary_plan_id, SECRET_KEY);
                    ?>
                        <a type="button"
                            href="dailymoment.php?formtype=show_daylist&cstmrid=<?= $CSTMRID; ?>&id=<?= $encoded_itinerary_plan_id; ?>"
                            class="start-trip-button">
                            View Your Trip
                            <span><img class="ms-2" src="assets/img/fast-forward.png" width="20px" height="20px" /></span>
                        </a>
                    <?php else: ?>
                        <a type="button"
                            href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_id; ?>"
                            class="start-trip-button">
                            View Your Trip
                            <span><img class="ms-2" src="assets/img/fast-forward.png" width="20px" height="20px" /></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
        <div class="modal fade" id="addDRIVERKILOMETERFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-driverkilometer-form-data">
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showDRIVERKILOMETERMODAL(PLAN_ID, ROUTE_ID, VENDOR_ID, VEHICLE_TYPE, VEHICLE_ID) {

                $('.receiving-driverkilometer-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=upload_Kilometer&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&VENDOR_ID=' + VENDOR_ID + '&VEHICLE_TYPE=' + VEHICLE_TYPE + '&VEHICLE_ID=' + VEHICLE_ID + '', function() {
                    const container = document.getElementById("addDRIVERKILOMETERFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }
        </script>


    <?php
    elseif ($_GET['type'] == 'show_welcome_guide') :

        $itinerary_plan_id = $_POST['ID'];
        $guide_ID = $_POST['GUIDE_ID'];

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
        endwhile;
        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;

    ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 px-md-5 welcome-card">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="mb-3">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                            </div>
                            <h4 class="text-white fw-bold mb-1 driver-name">
                                Hi, guide
                            </h4>
                            <p class="text-white m-0 driver-vehicle-type">
                                95956565666
                            </p>
                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <img src="assets/img/guide.png" width="170px" height="170px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3 px-2">
            <div class="col-12">
                <h5 class="my-3 fw-bold your-ride-title">Your Ride</h5>
                <div>
                    <h6 class="plan-datatime-text">
                        <span class="me-2"><img
                                src="assets/img/dailymoment/calendar.png"
                                width="15px"
                                height="15px" /></span><?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                    </h6>
                    <h5 class="plan-location-title m-0 mb-2">
                        <?= $arrival_location; ?>
                        <span class="mx-2"><img
                                src="assets/img/dailymoment/right-arrow.png"
                                width="26px"
                                height="26px" /></span>
                        <?= $departure_location; ?>
                    </h5>
                    <div class="d-flex align-items-center">
                        <?php if ($total_adult != '0'): ?>
                            <h6 class="person-count-text">
                                Adult <span class="person-count ms-1"><?= $total_adult; ?></span>
                            </h6>
                        <?php endif; ?>
                        <?php if ($total_children != '0'): ?>
                            <div class="vl mx-2 mb-1"></div>
                            <h6 class="person-count-text">
                                Child <span class="person-count ms-1"><?= $total_children; ?></span>
                            </h6>
                        <?php endif; ?>
                        <?php if ($total_infants != '0'): ?>
                            <div class="vl mx-2 mb-1"></div>
                            <h6 class="person-count-text">
                                Child <span class="person-count ms-1"><?= $total_infants; ?></span>
                            </h6>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_customer_ID`, `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `primary_customer`, `customer_type`, `customer_name`, `customer_age`, `primary_contact_no`, `altenative_contact_no`, `email_id`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_customer_details` WHERE `deleted` = '0' AND `status` = '1' AND `primary_customer` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $agent_id = $fetch_data['agent_id'];
            $customer_name = $fetch_data['customer_name'];
            $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
            $travel_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
            $travel_contactno = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
            $travel_emailid = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
            $arrival_place = $fetch_data['arrival_place'];
            $departure_place = $fetch_data['departure_place'];
            if ($arrival_place):
                $arrival_place = $arrival_place;
            else:
                $arrival_place = '';
            endif;
            if ($departure_place):
                $departure_place = $departure_place;
            else:
                $departure_place = '';
            endif;
            $arrival_date_and_time = $fetch_data['arrival_date_and_time'];
            $departure_date_and_time = $fetch_data['departure_date_and_time'];
            if ($arrival_date_and_time):
                $formattedArrivalDateTime = date('d M Y H.i A', strtotime($arrival_date_and_time));
            else:
                $formattedArrivalDateTime = '';
            endif;
            if ($departure_date_and_time):
                $formattedDepartureDateTime = date('d M Y H.i A', strtotime($departure_date_and_time));
            else:
                $formattedDepartureDateTime = '';
            endif;
            if ($primary_contact_no != ''):
                $primary_contact_no = $fetch_data['primary_contact_no'];
            else:
                $primary_contact_no = '--';
            endif;

            if ($email_id != ''):
                $email_id = $fetch_data['email_id'];
            else:
                $email_id = '--';
            endif;

            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
        endwhile;

        ?>
        <div class="row px-2">
            <div class="col-12 col-md-6 mb-3">
                <div class="card p-3 py-2" style="background: #f5e9ff">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="travel-flex-container">
                            <h5 class="guest-title fw-bold mb-3">Guest Details</h5>
                            <h6 class="guest-name mb-1 fw-bold"><?= $customer_name; ?></h6>
                            <p class="guest-contact-no mb-0"><?= $primary_contact_no . ' , ' . $email_id ?></p>
                            <p class="guest-emailid mb-1"><?= $arrival_place . ' ' . $formattedArrivalDateTime . '==>' . $departure_place . ' ' . $formattedDepartureDateTime ?></p>
                        </div>
                        <div>
                            <img
                                src="assets/img/svg/Group 21.svg"
                                width="150px"
                                height="150px" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="card p-3 py-2" style="background: #feebde">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="travel-flex-container">
                            <h5 class="guest-title fw-bold mb-3">Travel Expert Details</h5>
                            <h6 class="guest-name mb-1 fw-bold"><?= $travel_name_label; ?></h6>
                            <p class="guest-contact-no mb-0"><?= $travel_contactno_label; ?></p>
                            <p class="guest-emailid mb-1"><?= $travel_emailid_label; ?></p>
                        </div>
                        <div>
                            <img
                                src="assets/img/travel-expert.png"
                                width="150px"
                                height="150px" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3 driver-start-sticky">
                <a type="button" href="dailymoment.php?formtype=show_daylist_guide&id=<?= $itinerary_plan_id; ?>&GUIDE_ID=<?= $guide_ID; ?>" class="start-trip-button">Start Your Trip <span><img class="ms-2" src="assets/img/fast-forward.png" width="20px" height="20px" /></span></a>
            </div>
        </div>

    <?php
    elseif ($_GET['type'] == 'show_daylist') :

        $itinerary_plan_id = $_POST['ID'];
        $CSTMRID = $_POST['CSTMRID'];
        if ($CSTMRID != ''):
            $cstmr_id = Encryption::Decode($CSTMRID, SECRET_KEY);
            $encoded_itinerary_plan_id =  $_POST['ID'];

            $itinerary_plan_id = Encryption::Decode($itinerary_plan_id, SECRET_KEY);

        endif;

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_data['itinerary_quote_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
        endwhile;
        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;
    ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 pt-0 px-md-5 welcome-card">
                    <div class="row">
                        <div class="col-12 pt-4 col-md-8">
                            <div class="mb-3">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                            </div>
                            <p class="text-white m-0 driver-vehicle-type">
                                <?= $itinerary_quote_ID; ?>
                            </p>
                            <p class="text-white mb-1 driver-vehicle-type">
                                <?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                            </p>
                            <h5 class="text-white plan-location-title m-0 mb-2">
                                <?= $arrival_location; ?>
                                <span class="mx-2"><img
                                        src="assets/img/dailymoment/right.png"
                                        width="26px"
                                        height="26px" /></span>
                                <?= $departure_location; ?>
                            </h5>

                            <div class="d-flex align-items-center">
                                <?php if ($total_adult != '0'): ?>
                                    <h6 class="text-white person-count-text">
                                        Adult <span class="person-count-day ms-1"><?= $total_adult; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_children != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Child <span class="person-count-day ms-1"><?= $total_children; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_infants != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Infant <span class="person-count-day ms-1"><?= $total_infants; ?></span>
                                    </h6>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <div>
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <div class="text-end mb-4 mt-2"><a href="dailymoment.php?formtype=driver&cstmrid=<?= $CSTMRID; ?>&id=<?= $encoded_itinerary_plan_id; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                                    <img src="assets/img/driver.png" width="170px" height="170px" />
                                <?php else: ?>
                                    <div class="text-end mb-4 mt-2"><a href="dailymoment.php?formtype=driver&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                                    <img src="assets/img/driver.png" width="170px" height="170px" />
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row px-2">
            <h5 class="my-3 fw-bold your-ride-title">List of Days</h5>

            <?php
            $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `driver_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
            $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
            if ($total_itinerary_plan_details_count > 0) :
                $daycount = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                    $daycount++;
                    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                    $encoded_itinerary_route_ID = Encryption::Encode($itinerary_route_ID, SECRET_KEY);
                    $itinerary_route_date = $fetch_data['itinerary_route_date'];
                    $formattedroute_date = date('M d,Y', strtotime($itinerary_route_date));
                    $location_name = $fetch_data['location_name'];
                    $next_visiting_location = $fetch_data['next_visiting_location'];
                    $total_children = $fetch_data['total_children'];
                    $total_infants = $fetch_data['total_infants'];
                    $driver_trip_completed = $fetch_data['driver_trip_completed'];
                    $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_without_format');
                    $current_date = date('Y-m-d');

                    if ($current_date == $itinerary_route_date):
                        $dailymoment_card = 'dailymoment-daycard-currentdate';
                    elseif ($driver_trip_completed == '1'):
                        $dailymoment_card = 'dailymoment-daycard-successdate';
                    else:
                        $dailymoment_card = 'dailymoment-daycard';
                    endif;

                    if ($current_date == $itinerary_route_date):
                        $dailymoment_badge = 'day-container-badge-current';
                    elseif ($driver_trip_completed == '1'):
                        $dailymoment_badge = 'day-container-badge-success';
                    else:
                        $dailymoment_badge = 'day-container-badge';
                    endif;

                    if ($current_date == $itinerary_route_date):
                        $dailymoment_blink = 'blink';
                    else:
                        $dailymoment_blink = '';
                    endif;
            ?>
                    <div class="col-12 mb-3">
                        <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                            <a href="dailymoment.php?formtype=show_hotspot&cstmrid=<?= $CSTMRID; ?>&id=<?= $encoded_itinerary_plan_id; ?>&routeid=<?= $encoded_itinerary_route_ID; ?>&day=<?= $daycount; ?>" class="card p-3 ps-4 py-2 <?= $dailymoment_card; ?>">
                            <?php else: ?>
                                <a href="dailymoment.php?formtype=show_hotspot&id=<?= $itinerary_plan_id; ?>&routeid=<?= $itinerary_route_ID; ?>&day=<?= $daycount; ?>" class="card p-3 ps-4 py-2 <?= $dailymoment_card; ?>">
                                <?php endif; ?>
                                <div class="d-flex align-items-center ms-4">
                                    <div class="<?= $dailymoment_badge; ?> d-flex align-items-center">
                                        <div>
                                            <h6 class="day-count-title <?= $dailymoment_blink; ?> pb-2 m-0">DAY-<?= $daycount; ?></h6>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="plan-datatime-text">
                                            <span class="me-1"><img
                                                    src="assets/img/dailymoment/calendar.png"
                                                    width="15px"
                                                    height="15px" /></span>
                                            <?= $formattedroute_date ?>

                                        </h6>
                                        <h5 class="plan-location-title m-0">
                                            <?= $location_name; ?>
                                            <span class="mx-2"><img
                                                    src="assets/img/dailymoment/right-arrow.png"
                                                    width="26px"
                                                    height="26px" /></span>
                                            <?php if ($get_via_route_details_without_format): ?>
                                                <?= $get_via_route_details_without_format; ?>
                                                <span class="mx-2"><img
                                                        src="assets/img/dailymoment/right-arrow.png"
                                                        width="26px"
                                                        height="26px" /></span>
                                            <?php endif; ?>
                                            <?= $next_visiting_location; ?>
                                        </h5>
                                    </div>
                                </div>
                                </a>
                    </div>
            <?php endwhile;
            endif;
            ?>

        </div>
    <?php
    elseif ($_GET['type'] == 'show_daylist_guide') :

        $itinerary_plan_id = $_POST['ID'];
        $guide_ID = $_POST['GUIDE_ID'];

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
        endwhile;

        $select_itinerary_plan_guide = sqlQUERY_LABEL("SELECT `guide_type` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_guide = sqlNUMOFROW_LABEL($select_itinerary_plan_guide);
        if ($total_itinerary_plan_details_guide > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_guide)) :
                $guide_type = $fetch_data['guide_type'];
            endwhile;
        endif;
        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;
    ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 pt-0 px-md-5 welcome-card">
                    <div class="row">
                        <div class="col-12 pt-4 col-md-8">
                            <!-- <div class="mb-3 d-flex justify-content-between">
                                <img
                                    src="assets/img/rounded-icon.png"
                                    width="60px"
                                    height="60px" />
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=guide&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div> -->
                            <div class="mb-3 d-flex justify-content-between">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=guide&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div>
                            <p class="text-white mb-1 driver-vehicle-type">
                                <?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                            </p>
                            <h5 class="text-white plan-location-title m-0 mb-2">
                                <?= $arrival_location; ?>
                                <span class="mx-2"><img
                                        src="assets/img/dailymoment/right.png"
                                        width="26px"
                                        height="26px" /></span>
                                <?= $departure_location; ?>
                            </h5>

                            <div class="d-flex align-items-center">
                                <?php if ($total_adult != '0'): ?>
                                    <h6 class="text-white person-count-text">
                                        Adult <span class="person-count-day ms-1"><?= $total_adult; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_children != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Child <span class="person-count-day ms-1"><?= $total_children; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_infants != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Infant <span class="person-count-day ms-1"><?= $total_infants; ?></span>
                                    </h6>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <div>
                                <div class="text-end mb-4 mt-2"><a href="dailymoment.php?formtype=guide&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                                <img src="assets/img/guide.png" width="170px" height="170px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-2">
            <h5 class="my-3 fw-bold your-ride-title">List of Days</h5>

            <?php if ($guide_type == 2): ?>
                <?php
                $select_itinerary_plan_guide = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                $total_itinerary_plan_details_guide = sqlNUMOFROW_LABEL($select_itinerary_plan_guide);
                if ($total_itinerary_plan_details_guide > 0) :
                    $daycount = 0;
                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_guide)) :
                        $daycount++;
                        $guide_itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                        $guide_itinerary_route_ID = $fetch_data['itinerary_route_ID'];

                        $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `guide_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$guide_itinerary_plan_ID' AND `itinerary_route_ID` = '$guide_itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
                        if ($total_itinerary_plan_details_count > 0) :

                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :

                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                                $formattedroute_date = date('M d,Y', strtotime($itinerary_route_date));
                                $location_name = $fetch_data['location_name'];
                                $next_visiting_location = $fetch_data['next_visiting_location'];
                                $total_children = $fetch_data['total_children'];
                                $total_infants = $fetch_data['total_infants'];
                                $guide_trip_completed = $fetch_data['guide_trip_completed'];
                                $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_without_format');
                                $current_date = date('Y-m-d');

                                if ($current_date == $itinerary_route_date):
                                    $dailymoment_card = 'dailymoment-daycard-currentdate';
                                elseif ($guide_trip_completed == '1'):
                                    $dailymoment_card = 'dailymoment-daycard-successdate';
                                else:
                                    $dailymoment_card = 'dailymoment-daycard';
                                endif;

                                if ($current_date == $itinerary_route_date):
                                    $dailymoment_badge = 'day-container-badge-current';
                                elseif ($guide_trip_completed == '1'):
                                    $dailymoment_badge = 'day-container-badge-success';
                                else:
                                    $dailymoment_badge = 'day-container-badge';
                                endif;

                                if ($current_date == $itinerary_route_date):
                                    $dailymoment_blink = 'blink';
                                else:
                                    $dailymoment_blink = '';
                                endif;
                ?>
                                <div class="col-12 mb-3">
                                    <a href="dailymoment.php?formtype=show_guide&id=<?= $itinerary_plan_id; ?>&routeid=<?= $itinerary_route_ID; ?>&day=<?= $daycount; ?>" class="card p-3 ps-4 py-2 <?= $dailymoment_card; ?>">
                                        <div class="d-flex align-items-center ms-4">
                                            <div class="<?= $dailymoment_badge; ?> d-flex align-items-center">
                                                <div>
                                                    <h6 class="day-count-title <?= $dailymoment_blink; ?> pb-2 m-0">DAY-<?= $daycount; ?></h6>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="plan-datatime-text">
                                                    <span class="me-1"><img
                                                            src="assets/img/dailymoment/calendar.png"
                                                            width="15px"
                                                            height="15px" /></span>
                                                    <?= $formattedroute_date ?>

                                                </h6>
                                                <h5 class="plan-location-title m-0">
                                                    <?= $location_name; ?>
                                                    <span class="mx-2"><img
                                                            src="assets/img/dailymoment/right-arrow.png"
                                                            width="26px"
                                                            height="26px" /></span>
                                                    <?= $next_visiting_location; ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                <?php endwhile;
                        endif;
                    endwhile;
                endif;
                ?>
            <?php elseif ($guide_type == 1): ?>
                <?php
                $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `guide_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
                if ($total_itinerary_plan_details_count > 0) :
                    $daycount = 0;
                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                        $daycount++;
                        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                        $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                        $itinerary_route_date = $fetch_data['itinerary_route_date'];
                        $formattedroute_date = date('M d,Y', strtotime($itinerary_route_date));
                        $location_name = $fetch_data['location_name'];
                        $next_visiting_location = $fetch_data['next_visiting_location'];
                        $total_children = $fetch_data['total_children'];
                        $total_infants = $fetch_data['total_infants'];
                        $guide_trip_completed = $fetch_data['guide_trip_completed'];
                        $current_date = date('Y-m-d');

                        if ($current_date == $itinerary_route_date):
                            $dailymoment_card = 'dailymoment-daycard-currentdate';
                        elseif ($guide_trip_completed == '1'):
                            $dailymoment_card = 'dailymoment-daycard-successdate';
                        else:
                            $dailymoment_card = 'dailymoment-daycard';
                        endif;

                        if ($current_date == $itinerary_route_date):
                            $dailymoment_badge = 'day-container-badge-current';
                        elseif ($guide_trip_completed == '1'):
                            $dailymoment_badge = 'day-container-badge-success';
                        else:
                            $dailymoment_badge = 'day-container-badge';
                        endif;

                        if ($current_date == $itinerary_route_date):
                            $dailymoment_blink = 'blink';
                        else:
                            $dailymoment_blink = '';
                        endif;
                ?>
                        <div class="col-12 mb-3">
                            <a href="dailymoment.php?formtype=show_guide&id=<?= $itinerary_plan_id; ?>&routeid=<?= $itinerary_route_ID; ?>&day=<?= $daycount; ?>" class="card p-3 ps-4 py-2 <?= $dailymoment_card; ?>">
                                <div class="d-flex align-items-center ms-4">
                                    <div class="<?= $dailymoment_badge; ?> d-flex align-items-center">
                                        <div>
                                            <h6 class="day-count-title <?= $dailymoment_blink; ?> pb-2 m-0">DAY-<?= $daycount; ?></h6>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="plan-datatime-text">
                                            <span class="me-1"><img
                                                    src="assets/img/dailymoment/calendar.png"
                                                    width="15px"
                                                    height="15px" /></span>
                                            <?= $formattedroute_date ?>

                                        </h6>
                                        <h5 class="plan-location-title m-0">
                                            <?= $location_name; ?>
                                            <span class="mx-2"><img
                                                    src="assets/img/dailymoment/right-arrow.png"
                                                    width="26px"
                                                    height="26px" /></span>
                                            <?= $next_visiting_location; ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                <?php endwhile;
                endif;
                ?>
            <?php endif; ?>

        </div>

    <?php
    elseif ($_GET['type'] == 'show_hotspot') :

        $itinerary_plan_id = $_POST['ID'];
        $itinerary_route_ID = $_POST['ROUTEID'];
        $day_count = $_POST['DAY'];
        $CSTMRID = $_POST['CSTMRID'];
        if ($CSTMRID != ''):
            $cstmr_id = Encryption::Decode($CSTMRID, SECRET_KEY);
            $encoded_itinerary_plan_id =  $_POST['ID'];
            $encoded_itinerary_route_ID =  $_POST['ROUTEID'];

            $itinerary_plan_id = Encryption::Decode($itinerary_plan_id, SECRET_KEY);
            $itinerary_route_ID = Encryption::Decode($itinerary_route_ID, SECRET_KEY);
        endif;

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_data['itinerary_quote_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $formattedimagestart_date = date('Y-m-d', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $formattedimageend_date = date('Y-m-d', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $guide_for_itinerary = $fetch_data['guide_for_itinerary'];
        endwhile;


        $total_pax_count = $total_adult + $total_children + $total_infants;

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT  `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id` FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $vendor_id = $fetch_data['vendor_id'];
            $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
            $vehicle_id = $fetch_data['vehicle_id'];
        endwhile;
    ?>
        <?php
        $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `driver_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
        if ($total_itinerary_plan_details_count > 0) :
            $daycount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                $daycount++;
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                $formattedroute_date = date('D, M d,Y', strtotime($itinerary_route_date));
                $location_name = $fetch_data['location_name'];
                $driver_trip_completed = $fetch_data['driver_trip_completed'];
                $next_visiting_location = $fetch_data['next_visiting_location'];
                $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_without_format');
                $current_date = date('Y-m-d');
            endwhile;
        endif;
        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;
        ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 px-md-5 pt-0 welcome-card">
                    <div class="row">
                        <div class="col-12 pt-4 col-md-8">
                            <!-- <div class="mb-3 d-flex justify-content-between">
                                <img
                                    src="assets/img/rounded-icon.png"
                                    width="60px"
                                    height="60px" />
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div> -->
                            <div class="mb-3 d-flex justify-content-between">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div>
                            <p class="text-white m-0 driver-vehicle-type">
                                <?= $itinerary_quote_ID; ?>
                            </p>
                            <p class="text-white mb-1 driver-vehicle-type">
                                Day <?= $day_count; ?> - <?= $formattedroute_date; ?>
                            </p>
                            <h5 class="text-white plan-location-title m-0 mb-2">
                                <?= $location_name; ?>
                                <span class="mx-2"><img
                                        src="assets/img/dailymoment/right.png"
                                        width="26px"
                                        height="26px" /></span>
                                <?php if ($get_via_route_details_without_format): ?>
                                    <?= $get_via_route_details_without_format; ?>
                                    <span class="mx-2"><img
                                            src="assets/img/dailymoment/right.png"
                                            width="26px"
                                            height="26px" /></span>
                                <?php endif; ?>
                                <?= $next_visiting_location; ?>
                            </h5>
                            <div class="d-flex align-items-center">
                                <?php if ($total_adult != '0'): ?>
                                    <h6 class="text-white person-count-text">
                                        Adult <span class="person-count-day ms-1"><?= $total_adult; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_children != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Child <span class="person-count-day ms-1"><?= $total_children; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_infants != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Infant <span class="person-count-day ms-1"><?= $total_infants; ?></span>
                                    </h6>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <div>
                                <div class="text-end mb-4 mt-2">


                                    <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                        <a href="dailymoment.php?formtype=show_daylist&cstmrid=<?= $CSTMRID; ?>&id=<?= $encoded_itinerary_plan_id; ?>">
                                        <?php else: ?>
                                            <a href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_ID; ?>">
                                            <?php endif; ?>


                                            <img src="assets/img/arrow.png" width="20px" /></a>
                                </div>
                                <img src="assets/img/driver.png" width="170px" height="170px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-2">
            <div class="d-md-flex align-items-center justify-content-between my-3 hotspot-icon-container">
                <h5 class="fw-bold your-ride-title">
                    List of Visits
                    <?php if ($cstmr_id == ''): if ($formattedimagestart_date == $itinerary_route_date || $formattedimageend_date == $itinerary_route_date): ?>
                            <span class="badge badge-center rounded-pill bg-label-secondary p-3 px-2 cursor-pointer"
                                onclick="showDRIVERGALLERY('<?= $itinerary_plan_ID ?>', '<?= $itinerary_route_ID; ?>');"
                                data-bs-toggle="modal" data-bs-target="#GALLERYMODALINFODATA">
                                <img src="assets/img/image.png" width="20px">
                            </span>
                    <?php endif;
                    endif; ?>
                    <?php if ($cstmr_id == ''): ?>
                        <span class="badge badge-center rounded-pill bg-label-secondary p-3 px-2 cursor-pointer"
                            onclick="showDRIVERSHOWKILOMETERMODAL(<?= $itinerary_plan_ID; ?>,<?= $itinerary_route_ID; ?>,<?= $vendor_id; ?>,<?= $vendor_vehicle_type_id; ?>,<?= $vehicle_id; ?>);"
                            data-bs-toggle="modal" data-bs-target="#viewDRIVERKILOMETERFORM">
                            <img src="assets/img/meter.png" width="22px">
                        </span>

                    <?php endif; ?>
                </h5>
                <?php if ($driver_trip_completed != '1' && $cstmr_id == ''): ?>
                    <div>
                        <!-- <a type="button" href="dailymoment.php?formtype=view_charges&id=<?= $itinerary_plan_ID; ?>&routeid=<?= $itinerary_route_ID; ?>&day=<?= $day_count; ?>" class="view-charge-btn"><img src="assets/img/svg/eye.svg" class="me-1" width="18px" height="18px" />View Charge</a> -->
                        <button type="button" class="view-charge-btn mb-1" onclick="showDRIVERVIEWCHARGEMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal"><img src="assets/img/svg/eye.svg" class="me-1" width="18px" height="18px" />View Charge</button>
                        <?php if ($formattedimagestart_date == $itinerary_route_date || $formattedimageend_date == $itinerary_route_date): ?>
                            <button type="button" class="upload-image-btn mb-1" onclick="showDRIVERIMAGEMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal">+ Add Image</button>
                        <?php endif; ?>
                        <button type="button" class="add-charge-btn mb-1" onclick="showDRIVERCHARGEMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal">+ Add Charge</button>
                    </div>
                <?php endif; ?>
            </div>


            <?php
            $pricebook_true = check_guide_pricebook($itinerary_route_date, $total_pax_count);

            if ($guide_for_itinerary == 0 && $pricebook_true) :
                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_id`, `driver_guide_status`, `route_guide_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                if ($total_itinerary_guide_route_count > 0) :
                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                        $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                        $driver_guide_status = $fetch_itinerary_guide_route_data['driver_guide_status'];
                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                        $guide_name = getGUIDEDETAILS($guide_id, 'label');
                    endwhile;

                    if ($driver_guide_status == 1):
                        $guide_card_color = "dailymoment-daycard-successdate";
                    else:
                        $guide_card_color = "dailymoment-guidecard";
                    endif;
            ?>
                    <div class="col-12 mb-3 position-relative">
                        <div class="card p-3 <?= $guide_card_color; ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="dailymoment-guidename m-0">
                                        <img
                                            src="assets/img/svg/guide.svg"
                                            class="mb-2 me-2"
                                            width="24px"
                                            height="24px" />
                                        Guide Name - <span class="plan-location-title"><?= $guide_name; ?></span>
                                    </h5>
                                </div>
                                <div id="guidecontainer-<?= $route_guide_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                    <?php if ($driver_guide_status == 1): ?>
                                        <div id="visited-badge-<?= $route_guide_ID; ?>" class="badge dailymoment-visited-badge">
                                            <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                        </div>
                                    <?php elseif ($driver_guide_status == 2): ?>
                                        <div id="not-visited-badge-<?= $route_guide_ID; ?>" class="badge dailymoment-notvisited-badge">
                                            <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                        </div>
                                    <?php elseif ($current_date == $itinerary_route_date): ?>
                                        <button id="visited-btn-<?= $route_guide_ID; ?>" class="dailymoment-visited-btn" onclick="toggleguidestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)">
                                            <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                        </button>

                                        <button id="not-visited-btn-<?= $route_guide_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedguideModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)">
                                            <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php elseif ($guide_for_itinerary == 1 && $pricebook_true) :

                $select_itinerar_route_details = sqlQUERY_LABEL("SELECT `wholeday_guidehotspot_status` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID`='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerar_route_details);
                if ($total_itinerary_route_count > 0) :
                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerar_route_details)) :
                        $wholeday_guidehotspot_status = $fetch_itinerary_route_data['wholeday_guidehotspot_status'];
                    endwhile;
                endif;

                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_id`, `driver_guide_status`, `route_guide_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                if ($total_itinerary_guide_route_count > 0) :
                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                        $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                        $driver_guide_status = $fetch_itinerary_guide_route_data['driver_guide_status'];
                        $guide_name = getGUIDEDETAILS($guide_id, 'label');
                    endwhile;

                    if ($driver_guide_status == 1):
                        $guide_card_color = "dailymoment-daycard-successdate";
                    else:
                        $guide_card_color = "dailymoment-guidecard";
                    endif;
                ?>
                    <div class="col-12 mb-3 position-relative">
                        <div class="card p-3 <?= $guide_card_color; ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="dailymoment-guidename m-0">
                                        <img
                                            src="assets/img/svg/guide.svg"
                                            class="mb-2 me-2"
                                            width="24px"
                                            height="24px" />
                                        Guide Name - <span class="plan-location-title"><?= $guide_name; ?></span>
                                    </h5>
                                </div>
                                <?php if ($current_date == $itinerary_route_date): ?>
                                    <div id="wholedayguidecontainer-<?= $route_guide_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                        <?php if ($wholeday_guidehotspot_status == 1): ?>
                                            <div id="visited-badge-<?= $route_guide_ID; ?>" class="badge dailymoment-visited-badge">
                                                <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                            </div>
                                        <?php elseif ($wholeday_guidehotspot_status == 2): ?>
                                            <div id="not-visited-badge-<?= $route_guide_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </div>
                                        <?php elseif ($current_date == $itinerary_route_date): ?>
                                            <button id="visited-btn-<?= $route_guide_ID; ?>" class="dailymoment-visited-btn" onclick="togglewholedayguidestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)">
                                                <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                            </button>

                                            <button id="not-visited-btn-<?= $route_guide_ID; ?>" class="dailymoment-notvisited-btn" onclick="showWholedayNotVisitedguideModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)">
                                                <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php
            $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_route_hotspot_ID`, `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `item_type`, `hotspot_order`, `hotspot_ID`, `driver_hotspot_status`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost`, `hotspot_amout`, `hotspot_traveling_time`, `itinerary_travel_type_buffer_time`, `hotspot_travelling_distance`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_plan_own_way` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE  `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '4'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
            $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
            if ($total_itinerary_plan_details_count > 0) :
                $daycount = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                    $daycount++;
                    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                    $item_type = $fetch_data['item_type'];
                    $hotspot_ID = $fetch_data['hotspot_ID'];
                    $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
                    $hotspot_start_time = $fetch_data['hotspot_start_time'];
                    $route_hotspot_ID = $fetch_data['route_hotspot_ID'];
                    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                    $hotspot_start_time = $fetch_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_data['hotspot_end_time'];
                    $hotspot_traveling_time = $fetch_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_data['hotspot_travelling_distance'];
                    if ($driver_hotspot_status == 1):
                        $hotspot_card_color = "dailymoment-daycard-successdate";
                    else:
                        $hotspot_card_color = "dailymoment-daycard";
                    endif;
            ?>

                    <?php if ($item_type == 4): ?>
                        <div class="col-12 mb-3 position-relative">
                            <div class="card p-3 <?= $hotspot_card_color; ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- <a href="./head/engine/ajax/ajax_dailymoment_manage.php">jcj</a> -->
                                        <h5 class="plan-location-title m-0 mb-2">#<?= $daycount; ?> <?= $hotspot_name; ?></h5>
                                        <div
                                            class="hotspot-icon-container d-flex align-items-center gap-3">
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/clock.svg"
                                                        width="15px"
                                                        height="15px" /></span>
                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                            </h6>
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/deadline.svg"
                                                        width="15px"
                                                        height="15px" /></span>
                                                <?= formatTimeDuration($hotspot_traveling_time); ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div id="container-<?= $route_hotspot_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                        <?php if ($driver_hotspot_status == 1): ?>
                                            <div id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-visited-badge">
                                                <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                            </div>
                                        <?php elseif ($driver_hotspot_status == 2): ?>
                                            <div id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </div>
                                        <?php elseif ($current_date == $itinerary_route_date): ?>
                                            <button id="visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                            </button>

                                            <button id="not-visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php


                                $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`, ROUTE_ACTIVITY.`route_hotspot_ID`, ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`, ROUTE_ACTIVITY.`driver_activity_status`, ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_confirmed_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                                $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                                if ($total_hotspot_activity_num_rows_count > 0) :
                                    $activitycount = 0;
                                ?>

                                    <hr />

                                    <div>
                                        <h5 class="activity-header m-0 mb-2">Activity</h5>
                                        <?php while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_activity_details)) :
                                            $activitycount++;
                                            $route_activity_ID = $fetch_hotspot_activity_data['route_activity_ID'];
                                            $route_hotspot_ID = $fetch_hotspot_activity_data['route_hotspot_ID'];
                                            $driver_activity_status = $fetch_hotspot_activity_data['driver_activity_status'];
                                            $activity_order = $fetch_hotspot_activity_data['activity_order'];
                                            $activity_ID = $fetch_hotspot_activity_data['activity_ID'];
                                            $activity_amout = $fetch_hotspot_activity_data['activity_amout'];
                                            $activity_traveling_time = $fetch_hotspot_activity_data['activity_traveling_time'];
                                            $activity_start_time = $fetch_hotspot_activity_data['activity_start_time'];
                                            $activity_end_time = $fetch_hotspot_activity_data['activity_end_time'];
                                            $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                            $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                            $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                        ?>
                                            <div class="card mx-0 mx-lg-2 p-3 mb-2">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-8">
                                                        <h5 class="plan-location-title m-0 mb-2">
                                                            #<?= $activitycount; ?> <?= $activity_title; ?>
                                                        </h5>
                                                        <div
                                                            class="hotspot-icon-container d-flex align-items-center gap-3">
                                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                                <span class="me-2"><img
                                                                        src="assets/img/svg/clock.svg"
                                                                        width="15px"
                                                                        height="15px" /></span>
                                                                <?= date('h:i A', strtotime($activity_start_time)); ?> - <?= date('h:i A', strtotime($activity_end_time)); ?>
                                                            </h6>
                                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                                <span class="me-2"><img
                                                                        src="assets/img/svg/deadline.svg"
                                                                        width="15px"
                                                                        height="15px" /></span>
                                                                <?= formatTimeDuration($activity_traveling_time); ?>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <div id="activitycontainer-<?= $route_activity_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                                        <?php if ($driver_activity_status == 1): ?>
                                                            <div id="visited-badge-<?= $route_activity_ID; ?>" class="badge dailymoment-visited-badge">
                                                                <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                                            </div>
                                                        <?php elseif ($driver_activity_status == 2): ?>
                                                            <div id="not-visited-badge-<?= $route_activity_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                                <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                                            </div>
                                                        <?php elseif ($current_date == $itinerary_route_date): ?>
                                                            <button id="visited-btn-<?= $route_activity_ID; ?>" class="dailymoment-visited-btn" onclick="toggleactivitystatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)">
                                                                <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                                            </button>

                                                            <button id="not-visited-btn-<?= $route_activity_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedActivityModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)">
                                                                <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        endwhile;
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="dailymoment-daywise-border"></div>
                        </div>
                    <?php endif; ?>
            <?php endwhile;
            endif;
            ?>

            <?php

            $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `next_visiting_location`, `route_end_time` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
            while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :

                $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];
            endwhile;

            $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_route_hotspot_ID`, `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `item_type`, `driver_hotspot_status`,`hotspot_start_time` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE  `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('6','7') ") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
            $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
            if ($total_itinerary_plan_details_count > 0) :
                $daycount = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                    $daycount++;
                    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                    $item_type = $fetch_data['item_type'];
                    $hotspot_start_time = $fetch_data['hotspot_start_time'];
                    $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
                    $route_hotspot_ID = $fetch_data['route_hotspot_ID'];
                    if ($driver_hotspot_status == 1):
                        $hotspot_card_color = "dailymoment-daycard-successdate";
                    else:
                        $hotspot_card_color = "dailymoment-daycard-hotel";
                    endif;
            ?>
                    <?php if ($item_type == 6):
                        $get_hotel_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
                        $get_hotel_address = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'hotel_address');
                        if ($get_hotel_title) :
                            $get_hotel_name = $get_hotel_title;
                        else :
                            $get_hotel_name = 'N/A';
                        endif;

                        if ($get_hotel_address) :
                            $get_hotel_address_format = $get_hotel_address;
                        else :
                            $get_hotel_address_format = $next_visiting_location;
                        endif;

                        if ($get_hotel_name) :
                            $display_hotspot_start_time = $hotspot_start_time;
                        else :
                            $display_hotspot_start_time = $route_end_time;
                        endif;


                    ?>
                        <div class="col-12 mb-3 position-relative">
                            <div class="card p-3 <?= $hotspot_card_color; ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php if ($get_hotel_title): ?>
                                            <h5 class="plan-location-title m-0 mb-2">
                                                <img
                                                    src="assets/img/svg/hotel.svg"
                                                    width="18px"
                                                    height="20px" />
                                                <?= $get_hotel_name; ?>
                                            </h5>
                                        <?php endif; ?>
                                        <div class="hotspot-icon-container">
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/pin.svg"
                                                        width="18px"
                                                        height="18px" /></span>
                                                <?= $get_hotel_address_format; ?>
                                            </h6>
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/clock.svg"
                                                        width="15px"
                                                        height="15px" /></span>
                                                <?= date('h:i A', strtotime($display_hotspot_start_time)); ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div id="container-<?= $route_hotspot_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                        <?php if ($driver_hotspot_status == 1): ?>
                                            <div id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-visited-badge">
                                                <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                            </div>
                                        <?php elseif ($driver_hotspot_status == 2): ?>
                                            <div id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </div>
                                        <?php elseif ($current_date == $itinerary_route_date): ?>
                                            <button id="visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                            </button>

                                            <button id="not-visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($item_type == 7):
                    ?>
                        <div class="col-12 mb-3 position-relative">
                            <div class="card p-3 <?= $hotspot_card_color; ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="plan-location-title m-0 mb-2">
                                            <img
                                                src="assets/img/svg/hotel.svg"
                                                width="18px"
                                                height="20px" />
                                            <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>
                                        </h5>
                                        <div class="hotspot-icon-container">
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/clock.svg"
                                                        width="15px"
                                                        height="15px" /></span>
                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                -
                                                <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                            </h6>
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img
                                                        src="assets/img/svg/pin.svg"
                                                        width="18px"
                                                        height="18px" /></span>
                                                <?= $hotspot_travelling_distance; ?>
                                                KM
                                            </h6>
                                            <h6 class="plan-datatime-text d-flex align-items-center">
                                                <span class="me-2"><img src="assets/img/svg/deadline.svg" width="15px" height="15px"></span>
                                                <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                (This may vary due to traffic
                                                conditions)
                                            </h6>
                                        </div>
                                    </div>
                                    <div id="container-<?= $route_hotspot_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                        <?php if ($driver_hotspot_status == 1): ?>
                                            <div id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-visited-badge">
                                                <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                            </div>
                                        <?php elseif ($driver_hotspot_status == 2): ?>
                                            <div id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </div>
                                        <?php elseif ($current_date == $itinerary_route_date): ?>
                                            <button id="visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                            </button>

                                            <button id="not-visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                                <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
            <?php endwhile;
            endif;
            ?>
            <?php if ($driver_trip_completed != '1' && $current_date == $itinerary_route_date): ?>
                <div class="col-12 mb-3 driver-start-sticky">
                    <!-- <a href="dailymoment.php?formtype=show_daycomplete&id=<?= $itinerary_plan_id; ?>&routeid=<?= $itinerary_route_ID; ?>" class="start-trip-button">
                        Trip Completed
                        <span><img
                                class="ms-2"
                                src="assets/img/fast-forward.png"
                                width="20px"
                                height="20px" /></span>
                    </a> -->
                    <button type="button" onclick="showDRIVERKILOMETERMODAL(<?= $itinerary_plan_ID; ?>,<?= $itinerary_route_ID; ?>,<?= $vendor_id; ?>,<?= $vendor_vehicle_type_id; ?>,<?= $vehicle_id; ?>);" data-bs-dismiss="modal" class="start-trip-button"> Trip Completed <span><img class="ms-2" src="assets/img/fast-forward.png" width="20px" height="20px" /></span></button>
                </div>

                <?php elseif ($driver_trip_completed == '1' && $cstmr_id != ''):

                $select_itinerary_plan_cstmr_details = sqlQUERY_LABEL("SELECT `customer_id`, `itinerary_plan_ID`, `itinerary_route_ID` FROM `dvi_confirmed_itinerary_customer_feedback` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID' ") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                $check_customer_completed_trip_result = sqlNUMOFROW_LABEL($select_itinerary_plan_cstmr_details);
                if ($check_customer_completed_trip_result == 0): ?>
                    <div class="col-12 mb-3 driver-start-sticky">

                        <a href="dailymoment.php?formtype=show_daycomplete&cstmrid=<?= $CSTMRID; ?>&id=<?= $encoded_itinerary_plan_id; ?>&routeid=<?= $encoded_itinerary_route_ID; ?>" class="start-trip-button">
                            Trip Completed <span><img
                                    class="ms-2"
                                    src="assets/img/fast-forward.png"
                                    width="20px"
                                    height="20px" /></span>
                        </a>
                    </div>
            <?php endif;
            endif; ?>

        </div>
        </div>

        <div class="modal fade" id="addDRIVERKILOMETERFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-driverkilometer-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addDRIVERCHARGEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-drivercharge-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="viewDRIVERCHARGEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-viewdrivercharge-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="viewDRIVERKILOMETERFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-lg modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-viewdriverkm-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addDRIVERIMAGEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-driverimage-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDGUIDEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-guideform-data">
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="addwholedayNOTVISITEDGUIDEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-wholedayguideform-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDACTIVITYFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-activityform-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="GALLERYMODALINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top modal-lg receiving-gallery-modal-info-form-data">
            </div>
        </div>



        <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
        <script src="assets/vendor/libs/toastr/toastr.js"></script>

        <script>
            $(document).ready(function() {
                toggleTripCompletedButton();
            });

            function showDRIVERKILOMETERMODAL(PLAN_ID, ROUTE_ID, VENDOR_ID, VEHICLE_TYPE, VEHICLE_ID) {

                $('.receiving-driverkilometer-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=upload_closing_Kilometer&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&VENDOR_ID=' + VENDOR_ID + '&VEHICLE_TYPE=' + VEHICLE_TYPE + '&VEHICLE_ID=' + VEHICLE_ID + '', function() {
                    const container = document.getElementById("addDRIVERKILOMETERFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function TOAST_NOTIFICATION(type, message, title) {

                switch (type) {
                    case 'success':
                        toastr.success(message, title);
                        break;
                    case 'error':
                        toastr.error(message, title);
                        break;
                    case 'info':
                        toastr.info(message, title);
                        break;
                    case 'warning':
                        toastr.warning(message, title);
                        break;
                    default:
                        toastr.info(message, title); // Default to info
                        break;
                }
            }

            function showDRIVERCHARGEMODAL(PLAN_ID, ROUTE_ID) {

                $('.receiving-drivercharge-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=show_form&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("addDRIVERCHARGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERVIEWCHARGEMODAL(PLAN_ID, ROUTE_ID) {

                $('.receiving-viewdrivercharge-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=view_charges&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("viewDRIVERCHARGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERSHOWKILOMETERMODAL(PLAN_ID, ROUTE_ID, VENDOR_ID, VEHICLE_TYPE, VEHICLE_ID) {

                $('.receiving-viewdriverkm-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=view_kilometer&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&VENDOR_ID=' + VENDOR_ID + '&VEHICLE_TYPE=' + VEHICLE_TYPE + '&VEHICLE_ID=' + VEHICLE_ID + '', function() {
                    const container = document.getElementById("viewDRIVERKILOMETERFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERIMAGEMODAL(PLAN_ID, ROUTE_ID) {

                $('.receiving-driverimage-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=show_form_image&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("addDRIVERIMAGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERGALLERY(PLAN_ID, ROUTE_ID) {
                $('.receiving-gallery-modal-info-form-data').load(
                    './head/engine/ajax/__ajax_driver_dailymoment.php?type=showgallerymodal&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID,
                    function() {
                        const container = document.getElementById("GALLERYMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showNotVisitedguideModal(STATUS, PLAN_ID, ROUTE_ID, GUIDE_ID) {
                $('.receiving-notvisited-guideform-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=not_visiting_driver_guide&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDE_ID=' + GUIDE_ID, function() {
                    const container = document.getElementById("addNOTVISITEDGUIDEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showWholedayNotVisitedguideModal(STATUS, PLAN_ID, ROUTE_ID, GUIDE_ID) {
                $('.receiving-notvisited-wholedayguideform-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=not_visiting_driver_wholedayguide&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDE_ID=' + GUIDE_ID, function() {
                    const container = document.getElementById("addwholedayNOTVISITEDGUIDEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedHotspotModal(STATUS, PLAN_ID, ROUTE_ID, route_hotspot_ID, item_type) {
                $('.receiving-notvisited-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=not_visiting&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&HOTSPOT_ID=' + route_hotspot_ID + '&TYPE_ID=' + item_type, function() {
                    const container = document.getElementById("addNOTVISITEDFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedActivityModal(STATUS, PLAN_ID, ROUTE_ID, ACTIVITY_ID, HOTSPOT_ID) {
                $('.receiving-notvisited-activityform-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=not_visiting_activity&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&ACTIVITY_ID=' + ACTIVITY_ID + '&HOTSPOT_ID=' + HOTSPOT_ID, function() {
                    const container = document.getElementById("addNOTVISITEDACTIVITYFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'hotspotstatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            var updatedContent = generateStatusHTML(SELECTED_STATUS, ROUTE_HOTSPOT_ID, PLAN_ID, ROUTE_ID, TYPE_ID);
                            $('#container-' + ROUTE_HOTSPOT_ID).html(updatedContent);

                            // Determine correct card class based on item_type
                            var cardClassToAdd = (SELECTED_STATUS === 1) ?
                                'dailymoment-daycard-successdate' :
                                (TYPE_ID == 4 ? 'dailymoment-daycard' : 'dailymoment-daycard-hotel');

                            var cardClassToRemove = (TYPE_ID == 4) ?
                                'dailymoment-daycard' :
                                'dailymoment-daycard-hotel';

                            $('#container-' + ROUTE_HOTSPOT_ID).closest('.card')
                                .removeClass(cardClassToRemove)
                                .addClass(cardClassToAdd);

                            // Call the function to toggle the trip completion button
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }


            function generateStatusHTML(status, route_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, item_type) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${route_hotspot_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${route_hotspot_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${route_hotspot_ID}" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${route_hotspot_ID}" class="dailymoment-notvisited-btn" onclick="togglestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }

            function toggleguidestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_guide_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = route_guide_ID;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'guidestatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedguideContent = generateguideStatusHTML(SELECTED_STATUS, GUIDE_ID, PLAN_ID, ROUTE_ID);
                            $('#guidecontainer-' + GUIDE_ID).html(updatedguideContent);
                            if (SELECTED_STATUS === 1) {
                                $('#guidecontainer-' + GUIDE_ID).closest('.card').removeClass('dailymoment-guidecard').addClass('dailymoment-daycard-successdate');
                            } else {
                                $('#guidecontainer-' + GUIDE_ID).closest('.card').removeClass('dailymoment-daycard-successdate').addClass('dailymoment-guidecard');
                            }
                            // Call the function after updating the badges/buttons
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }


            function generateguideStatusHTML(status, GUIDE_ID, itinerary_plan_ID, itinerary_route_ID) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${GUIDE_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${GUIDE_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${GUIDE_ID}" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${GUIDE_ID}" class="dailymoment-notvisited-btn" onclick="togglestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }


            function togglewholedayguidestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_guide_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = route_guide_ID;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'wholeday_guidestatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedguideContent = generatewholedayguideStatusHTML(SELECTED_STATUS, GUIDE_ID, PLAN_ID, ROUTE_ID);
                            $('#wholedayguidecontainer-' + GUIDE_ID).html(updatedguideContent);
                            if (SELECTED_STATUS === 1) {
                                $('#guidecontainer-' + GUIDE_ID).closest('.card').removeClass('dailymoment-guidecard').addClass('dailymoment-daycard-successdate');
                            } else {
                                $('#guidecontainer-' + GUIDE_ID).closest('.card').removeClass('dailymoment-daycard-successdate').addClass('dailymoment-guidecard');
                            }
                            // Call the function after updating the badges/buttons
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }


            function generatewholedayguideStatusHTML(status, GUIDE_ID, itinerary_plan_ID, itinerary_route_ID) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${GUIDE_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${GUIDE_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${GUIDE_ID}" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${GUIDE_ID}" class="dailymoment-notvisited-btn" onclick="togglestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }

            function toggleactivitystatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_activity_ID, route_hotspot_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_ACTIVITY_ID = route_activity_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'activitystatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ROUTE_ACTIVITY_ID,
                        route_hotspot_ID: ROUTE_HOTSPOT_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedactivityContent = generateActivityStatusHTML(SELECTED_STATUS, ROUTE_ACTIVITY_ID, PLAN_ID, ROUTE_ID, ROUTE_HOTSPOT_ID);
                            $('#activitycontainer-' + ROUTE_ACTIVITY_ID).html(updatedactivityContent);
                            // Call the function after updating the badges/buttons
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generateActivityStatusHTML(status, ACTIVITY_ID, itinerary_plan_ID, itinerary_route_ID, hotspot_route_ID) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${ACTIVITY_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${ACTIVITY_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${ACTIVITY_ID}" class="dailymoment-visited-btn" onclick="toggleactivitystatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${ACTIVITY_ID}" class="dailymoment-notvisited-btn" onclick="toggleactivitystatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }

            function toggleTripCompletedButton() {
                // Check if any "Visited" or "Not Visited" buttons are present
                var hasVisitedButton = document.querySelector('.dailymoment-visited-btn') !== null;
                var hasNotVisitedButton = document.querySelector('.dailymoment-notvisited-btn') !== null;

                // Reference the "Start Trip" button
                var tripCompletedButton = document.querySelector('.start-trip-button, .start-trip-button-disable');

                if (tripCompletedButton) {
                    if (hasVisitedButton || hasNotVisitedButton) {
                        // Disable the "Start Trip" button
                        tripCompletedButton.classList.add('start-trip-button-disable');
                        tripCompletedButton.classList.remove('start-trip-button');
                        tripCompletedButton.style.pointerEvents = 'none';
                    } else {
                        // Enable the "Start Trip" button
                        tripCompletedButton.classList.remove('start-trip-button-disable');
                        tripCompletedButton.classList.add('start-trip-button');
                        tripCompletedButton.style.pointerEvents = 'auto';
                    }
                }
            }
        </script>


    <?php
    elseif ($_GET['type'] == 'show_guide') :

        $itinerary_plan_id = $_POST['ID'];
        $itinerary_route_ID = $_POST['ROUTEID'];
        $day_count = $_POST['DAY'];

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $formattedimagestart_date = date('Y-m-d', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $formattedimageend_date = date('Y-m-d', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $guide_for_itinerary = $fetch_data['guide_for_itinerary'];
        endwhile;


        $total_pax_count = $total_adult + $total_children + $total_infants;
    ?>
        <?php
        $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `guide_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
        if ($total_itinerary_plan_details_count > 0) :
            $daycount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                $daycount++;
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                $formattedroute_date = date('D, M d,Y', strtotime($itinerary_route_date));
                $location_name = $fetch_data['location_name'];
                $guide_trip_completed = $fetch_data['guide_trip_completed'];
                $next_visiting_location = $fetch_data['next_visiting_location'];
                $current_date = date('Y-m-d');
            endwhile;
        endif;
        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        if ($agent_id):
            $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
            if ($total_agent_details_count > 0) :
                while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                    $agent_logo = $fetch_agent_details_data['site_logo'];
                endwhile;
            endif;
        endif;
        ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card p-4 px-md-5 pt-0 welcome-card">
                    <div class="row">

                        <div class="col-12 pt-4 col-md-8">
                            <!-- <div class="mb-3 d-flex justify-content-between">
                                <img
                                    src="assets/img/rounded-icon.png"
                                    width="60px"
                                    height="60px" />
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div> -->
                            <div class="mb-3 d-flex justify-content-between">
                                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>
                                    <?php if ($agent_logo): ?>
                                        <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="60px"
                                            height="60px" />
                                    <?php else: ?>
                                        <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="60px"
                                            height="60px" />
                                    <?php endif ?>
                                <?php else: ?>
                                    <img
                                        src="assets/img/rounded-icon.png"
                                        width="60px"
                                        height="60px" />
                                <?php endif; ?>
                                <div class="d-flex d-md-none"><a href="dailymoment.php?formtype=show_daylist&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                            </div>
                            <p class="text-white mb-1 driver-vehicle-type">
                                Day <?= $day_count; ?> - <?= $formattedroute_date; ?>
                            </p>
                            <h5 class="text-white plan-location-title m-0 mb-2">
                                <?= $location_name; ?>
                                <span class="mx-2"><img
                                        src="assets/img/dailymoment/right.png"
                                        width="26px"
                                        height="26px" /></span>
                                <?= $next_visiting_location; ?>
                            </h5>

                            <div class="d-flex align-items-center">
                                <?php if ($total_adult != '0'): ?>
                                    <h6 class="text-white person-count-text">
                                        Adult <span class="person-count-day ms-1"><?= $total_adult; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_children != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Child <span class="person-count-day ms-1"><?= $total_children; ?></span>
                                    </h6>
                                <?php endif; ?>
                                <?php if ($total_infants != '0'): ?>
                                    <div class="vl mx-2 mb-1"></div>
                                    <h6 class="text-white person-count-text">
                                        Infant <span class="person-count-day ms-1"><?= $total_infants; ?></span>
                                    </h6>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-none d-md-flex col-md-4 justify-content-end">
                            <div>
                                <div class="text-end mb-4 mt-2"><a href="dailymoment.php?formtype=show_daylist_guide&id=<?= $itinerary_plan_ID; ?>"><img src="assets/img/arrow.png" width="20px" /></a></div>
                                <img src="assets/img/guide.png" width="170px" height="170px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-2">
            <div class="d-flex align-items-center justify-content-between my-4 hotspot-icon-container">
                <h5 class="fw-bold your-ride-title">
                    List of Visits
                </h5>
            </div>
            <?php


            $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_route_hotspot_ID`, `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `item_type`, `hotspot_order`, `hotspot_ID`, `guide_hotspot_status`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost`, `hotspot_amout`, `hotspot_traveling_time`, `itinerary_travel_type_buffer_time`, `hotspot_travelling_distance`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_plan_own_way` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE  `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type`='4'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
            $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
            if ($total_itinerary_plan_details_count > 0) :
                $daycount = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                    $daycount++;
                    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                    $item_type = $fetch_data['item_type'];
                    $hotspot_ID = $fetch_data['hotspot_ID'];
                    $guide_hotspot_status = $fetch_data['guide_hotspot_status'];
                    $hotspot_start_time = $fetch_data['hotspot_start_time'];
                    $route_hotspot_ID = $fetch_data['route_hotspot_ID'];
                    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                    $hotspot_start_time = $fetch_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_data['hotspot_end_time'];
                    $hotspot_traveling_time = $fetch_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_data['hotspot_travelling_distance'];


            ?>

                    <div class="col-12 mb-3 position-relative">
                        <div class="card p-3 dailymoment-daycard">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- <a href="./head/engine/ajax/ajax_dailymoment_manage.php">jcj</a> -->
                                    <h5 class="plan-location-title m-0 mb-2">#<?= $daycount; ?> <?= $hotspot_name; ?></h5>
                                    <div
                                        class="hotspot-icon-container d-flex align-items-center gap-3">
                                        <h6 class="plan-datatime-text d-flex align-items-center">
                                            <span class="me-2"><img
                                                    src="assets/img/svg/clock.svg"
                                                    width="15px"
                                                    height="15px" /></span>
                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                        </h6>
                                        <h6 class="plan-datatime-text d-flex align-items-center">
                                            <span class="me-2"><img
                                                    src="assets/img/svg/deadline.svg"
                                                    width="15px"
                                                    height="15px" /></span>
                                            <?= formatTimeDuration($hotspot_traveling_time); ?>
                                        </h6>
                                    </div>
                                </div>
                                <div id="container-<?= $route_hotspot_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                    <?php if ($guide_hotspot_status == 1): ?>
                                        <div id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-visited-badge">
                                            <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                        </div>
                                    <?php elseif ($guide_hotspot_status == 2): ?>
                                        <div id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge dailymoment-notvisited-badge">
                                            <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                        </div>
                                    <?php elseif ($current_date == $itinerary_route_date): ?>
                                        <button id="visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                            <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                        </button>

                                        <button id="not-visited-btn-<?= $route_hotspot_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)">
                                            <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                        </button>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <?php


                            $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`, ROUTE_ACTIVITY.`route_hotspot_ID`, ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`, ROUTE_ACTIVITY.`guide_activity_status`, ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_confirmed_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                            $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                            if ($total_hotspot_activity_num_rows_count > 0) :
                                $activitycount = 0;
                            ?>

                                <hr />

                                <div>
                                    <h5 class="activity-header m-0 mb-2">Activity</h5>
                                    <?php while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_activity_details)) :
                                        $activitycount++;
                                        $route_activity_ID = $fetch_hotspot_activity_data['route_activity_ID'];
                                        $route_hotspot_ID = $fetch_hotspot_activity_data['route_hotspot_ID'];
                                        $guide_activity_status = $fetch_hotspot_activity_data['guide_activity_status'];
                                        $activity_order = $fetch_hotspot_activity_data['activity_order'];
                                        $activity_ID = $fetch_hotspot_activity_data['activity_ID'];
                                        $activity_amout = $fetch_hotspot_activity_data['activity_amout'];
                                        $activity_traveling_time = $fetch_hotspot_activity_data['activity_traveling_time'];
                                        $activity_start_time = $fetch_hotspot_activity_data['activity_start_time'];
                                        $activity_end_time = $fetch_hotspot_activity_data['activity_end_time'];
                                        $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                        $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                        $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                    ?>
                                        <div class="card mx-0 mx-lg-2 p-3 mb-2">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-8">
                                                    <h5 class="plan-location-title m-0 mb-2">
                                                        #<?= $activitycount; ?> <?= $activity_title; ?>
                                                    </h5>
                                                    <div
                                                        class="hotspot-icon-container d-flex align-items-center gap-3">
                                                        <h6 class="plan-datatime-text d-flex align-items-center">
                                                            <span class="me-2"><img
                                                                    src="assets/img/svg/clock.svg"
                                                                    width="15px"
                                                                    height="15px" /></span>
                                                            <?= date('h:i A', strtotime($activity_start_time)); ?> - <?= date('h:i A', strtotime($activity_end_time)); ?>
                                                        </h6>
                                                        <h6 class="plan-datatime-text d-flex align-items-center">
                                                            <span class="me-2"><img
                                                                    src="assets/img/svg/deadline.svg"
                                                                    width="15px"
                                                                    height="15px" /></span>
                                                            <?= formatTimeDuration($activity_traveling_time); ?>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div id="activitycontainer-<?= $route_activity_ID; ?>" class="col-md-4 text-start mt-2 mt-md-0 text-md-end">
                                                    <?php if ($guide_activity_status == 1): ?>
                                                        <div id="visited-badge-<?= $route_activity_ID; ?>" class="badge dailymoment-visited-badge">
                                                            <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                                                        </div>
                                                    <?php elseif ($guide_activity_status == 2): ?>
                                                        <div id="not-visited-badge-<?= $route_activity_ID; ?>" class="badge dailymoment-notvisited-badge">
                                                            <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                                                        </div>
                                                    <?php elseif ($current_date == $itinerary_route_date): ?>
                                                        <button id="visited-btn-<?= $route_activity_ID; ?>" class="dailymoment-visited-btn" onclick="toggleactivitystatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)">
                                                            <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                                                        </button>

                                                        <button id="not-visited-btn-<?= $route_activity_ID; ?>" class="dailymoment-notvisited-btn" onclick="showNotVisitedActivityModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)">
                                                            <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    endwhile;
                                    ?>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="dailymoment-daywise-border"></div>
                    </div>

            <?php endwhile;
            endif;
            ?>


            <?php if ($driver_trip_completed != '1' && $current_date == $itinerary_route_date): ?>
                <div class="col-12 mb-3 driver-start-sticky">
                    <a href="dailymoment.php?formtype=show_daycomplete_guide&id=<?= $itinerary_plan_id; ?>&routeid=<?= $itinerary_route_ID; ?>" class="start-trip-button">
                        Trip Completed
                        <span><img
                                class="ms-2"
                                src="assets/img/fast-forward.png"
                                width="20px"
                                height="20px" /></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        </div>



        <div class="modal fade" id="addNOTVISITEDFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDACTIVITYFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-activityform-data">
                    </div>
                </div>
            </div>
        </div>


        <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
        <script src="assets/vendor/libs/toastr/toastr.js"></script>

        <script>
            $(document).ready(function() {
                toggleTripCompletedButton();
            });

            function TOAST_NOTIFICATION(type, message, title) {

                switch (type) {
                    case 'success':
                        toastr.success(message, title);
                        break;
                    case 'error':
                        toastr.error(message, title);
                        break;
                    case 'info':
                        toastr.info(message, title);
                        break;
                    case 'warning':
                        toastr.warning(message, title);
                        break;
                    default:
                        toastr.info(message, title); // Default to info
                        break;
                }
            }

            function showNotVisitedHotspotModal(STATUS, PLAN_ID, ROUTE_ID, route_hotspot_ID, item_type) {
                $('.receiving-notvisited-form-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=hostpot_guide_not_visiting&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&HOTSPOT_ID=' + route_hotspot_ID + '&TYPE_ID=' + item_type, function() {
                    const container = document.getElementById("addNOTVISITEDFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedActivityModal(STATUS, PLAN_ID, ROUTE_ID, ACTIVITY_ID, HOTSPOT_ID) {
                $('.receiving-notvisited-activityform-data').load('./head/engine/ajax/__ajax_driver_dailymoment.php?type=guide_not_visiting_activity&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&ACTIVITY_ID=' + ACTIVITY_ID + '&HOTSPOT_ID=' + HOTSPOT_ID, function() {
                    const container = document.getElementById("addNOTVISITEDACTIVITYFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        type: 'guide_hotspotstatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedContent = generateguideStatusHTML(SELECTED_STATUS, ROUTE_HOTSPOT_ID, PLAN_ID, ROUTE_ID, TYPE_ID);
                            $('#container-' + ROUTE_HOTSPOT_ID).html(updatedContent);
                            // Call the function after updating the badges/buttons
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generateguideStatusHTML(status, route_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, item_type) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${route_hotspot_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${route_hotspot_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${route_hotspot_ID}" class="dailymoment-visited-btn" onclick="togglestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${route_hotspot_ID}" class="dailymoment-notvisited-btn" onclick="togglestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }

            function toggleactivitystatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_activity_ID, route_hotspot_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_ACTIVITY_ID = route_activity_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'guide_activitystatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ROUTE_ACTIVITY_ID,
                        route_hotspot_ID: ROUTE_HOTSPOT_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedactivityContent = guideActivityStatusHTML(SELECTED_STATUS, ROUTE_ACTIVITY_ID, PLAN_ID, ROUTE_ID, ROUTE_HOTSPOT_ID);
                            $('#activitycontainer-' + ROUTE_ACTIVITY_ID).html(updatedactivityContent);
                            // Call the function after updating the badges/buttons
                            toggleTripCompletedButton();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function guideActivityStatusHTML(status, ACTIVITY_ID, itinerary_plan_ID, itinerary_route_ID, hotspot_route_ID) {
                if (status === 1) { // Visited
                    return `
                <div id="visited-badge-${ACTIVITY_ID}" class="badge dailymoment-visited-badge">
                    <img src="assets/img/svg/check-tick-green.svg" class="me-1" width="12px" height="14px" />Visited
                </div>`;
                } else if (status === 2) { // Not Visited
                    return `
                <div id="not-visited-badge-${ACTIVITY_ID}" class="badge dailymoment-notvisited-badge">
                    <img src="assets/img/svg/cross-label.svg" class="me-1" width="12px" height="14px" />Not Visited
                </div>`;
                } else {
                    return `
                <button id="visited-btn-${ACTIVITY_ID}" class="dailymoment-visited-btn" onclick="toggleactivitystatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})">
                    <img src="assets/img/svg/check-tick.svg" class="me-1" width="12px" height="14px" />Visited
                </button>

                <button id="not-visited-btn-${ACTIVITY_ID}" class="dailymoment-notvisited-btn" onclick="toggleactivitystatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})">
                    <img src="assets/img/svg/cross.svg" class="me-1" width="12px" height="14px" />Not Visited
                </button>`;
                }
            }

            function toggleTripCompletedButton() {
                var visitedButtons = document.querySelectorAll('.dailymoment-visited-btn');
                var notVisitedButtons = document.querySelectorAll('.dailymoment-notvisited-btn');
                var tripCompletedButton = document.querySelector('.start-trip-button');

                // If any Visited or Not Visited buttons exist, disable the Trip Completed button
                if (visitedButtons.length > 0 || notVisitedButtons.length > 0) {
                    tripCompletedButton.classList.add('start-trip-button-disable');
                    tripCompletedButton.classList.remove('start-trip-button');
                    tripCompletedButton.style.pointerEvents = 'none'; // Restrict href navigation
                } else {
                    tripCompletedButton.classList.remove('start-trip-button-disable');
                    tripCompletedButton.classList.add('start-trip-button');
                    tripCompletedButton.style.pointerEvents = 'auto'; // Enable href navigation
                }
            }
        </script>

    <?php elseif ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];

    ?>
        <!-- Plugins css Ends-->
        <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Add Charges</h4>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>

            <div class="col-12">
                <label class="form-label w-100" for="visited_charge">Charge Type<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="visited_charge" name="visited_charge" required class="form-control" placeholder="Enter the Charge" value="" autocomplete="off">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="visited_charge_amount">Charge Amount<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="visited_charge_amount" name="visited_charge_amount" required class="form-control" placeholder="Enter the Charge" value="" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off">
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="cancel-charge-button" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-charge-button">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });


                //AJAX FORM SUBMIT
                $("#drivercharge_details_form").submit(function(event) {
                    var form = $('#drivercharge_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=drivercharge&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            if (response.errors.visited_charge_required) {
                                TOAST_NOTIFICATION('warning', 'Visited Charge Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.visited_charge_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Visited Charge Amount Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#drivercharge_details_form')[0].reset();
                                $('#addDRIVERCHARGEFORM').modal('hide');
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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
    elseif ($_GET['type'] == 'view_charges') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        // $day_count = $_POST['DAY'];

        $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `driver_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
        if ($total_itinerary_plan_details_count > 0) :
            $daycount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                $daycount++;
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                $formattedroute_date = date('D, M d,Y', strtotime($itinerary_route_date));
                $location_name = $fetch_data['location_name'];
                $driver_trip_completed = $fetch_data['driver_trip_completed'];
                $next_visiting_location = $fetch_data['next_visiting_location'];
                $current_date = date('Y-m-d');
            endwhile;
        endif;
    ?>

        <div>

            <div class="row g-3 px-2 d-flex justify-content-center">
                <div class="col-12 col-md-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="text-center">
                            <h5 class="mb-2">List of Charges <img src="assets/img/svg/price-tag.svg" width="20px" height="20px" /></h5>
                        </div>
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <?php
                    $select_itinerary_driver_charge = sqlQUERY_LABEL("SELECT `charge_type`, `charge_amount` FROM `dvi_confirmed_itinerary_dailymoment_charge` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                    $total_itinerary_driver_charge_count = sqlNUMOFROW_LABEL($select_itinerary_driver_charge);
                    if ($total_itinerary_driver_charge_count > 0) :
                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_driver_charge)) :
                            $charge_type = $fetch_data['charge_type'];
                            $charge_amount = $fetch_data['charge_amount'];
                            $total_charge_amount +=  $charge_amount;
                    ?>
                            <div class="d-flex align-items-center justify-content-between my-3 cost-details-success">
                                <h6 class="cost-details-title"><?= $charge_type; ?></h6>
                                <h6 class="cost-details-amount"><?= general_currency_symbol; ?> <?= number_format($charge_amount, 2); ?></h6>
                            </div>
                        <?php
                        endwhile;
                        ?>
                        <div class="d-flex align-items-center justify-content-between my-3">
                            <h6 class="cost-details-title"><b>Total Charge</b></h6>
                            <h6 class="cost-details-amount"><b><?= general_currency_symbol; ?> <?= number_format($total_charge_amount, 2); ?></b></h6>
                        </div>
                    <?php else: ?>
                        <h6 class="cost-details-title">No Charges Added</h6>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>

    <?php elseif ($_GET['type'] == 'view_kilometer') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $vendor_ID = $_GET['VENDOR_ID'];
        $vehicle_type_ID = $_GET['VEHICLE_TYPE'];
        $vehicle_ID = $_GET['VEHICLE_ID'];

        $select_itinerary_vendor_vehicle = sqlQUERY_LABEL("SELECT `driver_opening_km`, `opening_speedmeter_image`, `driver_closing_km`, `closing_speedmeter_image` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and  `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_ID' and `vendor_vehicle_type_id` = '$vehicle_type_ID' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_vendor_vehicle = sqlNUMOFROW_LABEL($select_itinerary_vendor_vehicle);
        if ($total_itinerary_plan_details_vendor_vehicle > 0) :
            $daycount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_vendor_vehicle)) :
                $driver_opening_km = $fetch_data['driver_opening_km'];
                $opening_speedmeter_image = $fetch_data['opening_speedmeter_image'];
                $driver_closing_km = $fetch_data['driver_closing_km'];
                $closing_speedmeter_image = $fetch_data['closing_speedmeter_image'];
            endwhile;
        endif;
    ?>

        <div>

            <div class="row g-3 px-2 d-flex justify-content-center">
                <div class="col-12 col-md-12">
                    <div class="modal-header">
                        <h5 class="modal-title">Show Kilometer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="d-flex align-items-center my-3">
                                <h6 class="cost-details-title">Opening Kilometer :</h6>
                                <?php if ($driver_opening_km): ?>
                                    <h4 class="cost-details-amount fw-bold fs-5 mx-2"><?= $driver_opening_km; ?> KM</h4>
                                <?php else: ?>
                                    <h4 class="cost-details-amount fw-bold fs-5 mx-2">NAN</h4>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($opening_speedmeter_image): ?>
                                    <img src="head/uploads/driver_speedmeter_gallery/<?= $opening_speedmeter_image; ?>" width="300px" height="150px" />
                                <?php else: ?>
                                    <h5 class="plan-location-title ">No Image Found</h5>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="d-flex align-items-center my-3">
                                <h6 class="cost-details-title">Closing Kilometer :</h6>
                                <?php if ($driver_closing_km): ?>
                                    <h4 class="cost-details-amount fw-bold fs-5 mx-2"><?= $driver_closing_km; ?> KM</h4>
                                <?php else: ?>
                                    <h4 class="cost-details-amount fw-bold fs-5 mx-2">NAN</h4>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($closing_speedmeter_image): ?>
                                    <img src="head/uploads/driver_speedmeter_gallery/<?= $closing_speedmeter_image; ?>" width="300px" height="150px" />
                                <?php else: ?>
                                    <h5 class="plan-location-title ">No Image Found</h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($_GET['type'] == 'show_form_image') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];

    ?>
        <!-- Plugins css Ends-->
        <form id="driver" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Upload Image</h4>
                </div>
                <button type="button" class="btn-close text-end" id="closeModalButton" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>

            <div class="col-12">
                <label class="form-label w-100" for="dailymoment_uploadimage">Upload Image</label>
                <div class="form-group">
                    <input type="file" id="dailymoment_uploadimage" name="dailymoment_uploadimage[]" class="form-control" multiple>
                </div>
                <!-- Container for image previews -->
                <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="cancel-charge-button" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-charge-button">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#driver").submit(function(event) {
                    var form = $('#driver')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=driverImage&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            TOAST_NOTIFICATION('error', 'Image not Uploaded', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#driver')[0].reset();
                                $('#addDRIVERIMAGEFORM').modal('hide');
                                TOAST_NOTIFICATION('success', 'Upload Image Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

            document.getElementById('dailymoment_uploadimage').addEventListener('change', function(event) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                imagePreviewContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-3';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close position-absolute';
                        closeButton.style.top = '-15px';
                        closeButton.style.width = '2px';
                        closeButton.style.right = '-11px';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('dailymoment_uploadimage').files = dataTransfer.files;
                }
            });
        </script>

    <?php elseif ($_GET['type'] == 'showgallerymodal') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];

    ?>
        <style>
            .swiper-slide {
                background-size: cover;
                background-position: center;
                min-height: 200px;
            }

            .gallery-top,
            .gallery-thumbs {
                height: 100%;
            }
        </style>
        <div class="modal-content">
            <div class="modal-body pt-0">
                <div class="d-flex align-items-center justify-content-between my-3 mx-2">
                    <div class="text-center">
                        <h4 class="mb-2">Gallery</h4>
                    </div>
                    <button type="button" class="btn-close text-end" id="closeModalImage" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <span id="response_modal"></span>
                <div id="swiper-gallery">
                    <div class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            <?php
                            // Fetch images from the database
                            $select_driver_gallery_list_query = sqlQUERY_LABEL("SELECT `driver_uploadimage_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `driver_upload_image` FROM `dvi_confirmed_driver_uploadimage` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_GALLERY_LIST:" . sqlERROR_LABEL());

                            // Check if any images are available
                            if (sqlNUMOFROW_LABEL($select_driver_gallery_list_query) > 0) :
                                // Loop through the images and generate Swiper slides
                                while ($fetch_driver_gallery_data = sqlFETCHARRAY_LABEL($select_driver_gallery_list_query)) :
                                    $driver_photo_url = BASEPATH . 'uploads/driver_dailymoment_gallery/' . $fetch_driver_gallery_data['driver_upload_image'];
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $driver_photo_url; ?>');"></div>
                                <?php
                                endwhile;
                            else :
                                ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h4>No more gallery found !!!</h4>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper gallery-thumbs mt-2">
                        <div class="swiper-wrapper">
                            <?php
                            // Rewind the query result pointer to fetch the same images for the thumbnails
                            sqlDATASEEK_LABEL($select_driver_gallery_list_query, 0);
                            while ($fetch_driver_gallery_data = sqlFETCHARRAY_LABEL($select_driver_gallery_list_query)) :
                                $driver_photo_url = BASEPATH . 'uploads/driver_dailymoment_gallery/' . $fetch_driver_gallery_data['driver_upload_image'];
                            ?>
                                <div class="swiper-slide" style="background-image:url('<?= $driver_photo_url; ?>')"></div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/vendor/libs/swiper/swiper.js"></script>
        <script src="assets/js/ui-carousel.js"></script>
        <script>
            $('#GALLERYMODALINFODATA').on('shown.bs.modal', function() {
                var galleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesVisibility: true,
                    watchSlidesProgress: true,
                });

                var galleryTop = new Swiper('.gallery-top', {
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs: {
                        swiper: galleryThumbs
                    }
                });
            });

            // Ensure the modal close button functionality works
            document.getElementById('closeModalImage').addEventListener('click', function() {
                console.log('Close button clicked');
                const modalElement = document.querySelector('.modal'); // Select the modal container
                const bootstrapModal = bootstrap.Modal.getInstance(modalElement); // Get Bootstrap modal instance

                if (bootstrapModal) {
                    bootstrapModal.hide(); // Hide the modal programmatically
                }
            });
        </script>


    <?php elseif ($_GET['type'] == 'upload_Kilometer') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $vendor_ID = $_GET['VENDOR_ID'];
        $vehicle_type_ID = $_GET['VEHICLE_TYPE'];
        $vehicle_ID = $_GET['VEHICLE_ID'];
    ?>
        <!-- Plugins css Ends-->
        <form id="driveruploadkm" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Upload KiloMeter</h4>
                </div>
                <button type="button" class="btn-close text-end" id="closeModalButton" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12">
                <label class="form-label w-100" for="starting_kilometer">Starting KiloMeter<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="starting_kilometer" name="starting_kilometer" required class="form-control" placeholder="Enter the KiloMeter" value="" autocomplete="off">
                </div>
            </div>

            <div class="col-12">
                <label class="form-label w-100" for="driver_speedmeter_image">Upload Image<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="file" id="driver_speedmeter_image" name="driver_speedmeter_image" required class="form-control">
                </div>
                <!-- Container for image previews -->
                <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="cancel-charge-button" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-charge-button">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#driveruploadkm").submit(function(event) {
                    var form = $('#driveruploadkm')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=driveruploadkilometer&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>&Vendor_id=<?= $vendor_ID; ?>&Vehicle_type_ID=<?= $vehicle_type_ID; ?>&Vehicle_ID=<?= $vehicle_ID; ?>',
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
                            if (response.errors && response.errors.starting_kilometer_required) {
                                TOAST_NOTIFICATION('warning', 'Kilometer is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors && response.errors.visited_charge_amount_required) {
                                TOAST_NOTIFICATION('error', 'Image not Uploaded', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPONSE
                            if (response.result_success === true) {
                                // RESULT SUCCESS
                                spinner.hide();
                                $('#driveruploadkm')[0].reset();
                                window.location.href = response.redirect_URL;
                                $('#addDRIVERKILOMETERFORM').modal('hide');
                                TOAST_NOTIFICATION('success', 'Upload Image Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result_success === false) {
                                // RESULT FAILED
                                spinner.hide();
                                ERROR_ALERT(response.result_error || 'An error occurred during the update.');
                            }
                        }

                    });
                    event.preventDefault();
                });
            });

            document.getElementById('closeModalButton').addEventListener('click', function() {
                console.log('Close button clicked');
            });

            document.getElementById('driver_speedmeter_image').addEventListener('change', function(event) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                imagePreviewContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-2';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close position-absolute';
                        closeButton.style.top = '-10px';
                        closeButton.style.width = '6px';
                        closeButton.style.right = '-22px';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('driver_speedmeter_image').files = dataTransfer.files;
                }
            });
        </script>
    <?php elseif ($_GET['type'] == 'upload_closing_Kilometer') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $vendor_ID = $_GET['VENDOR_ID'];
        $vehicle_type_ID = $_GET['VEHICLE_TYPE'];
        $vehicle_ID = $_GET['VEHICLE_ID'];
    ?>
        <!-- Plugins css Ends-->
        <form id="driveruploadclosingkm" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Upload KiloMeter</h4>
                </div>
                <button type="button" class="btn-close text-end" id="closeModalButton" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12">
                <label class="form-label w-100" for="closing_kilometer">Closing KiloMeter<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="closing_kilometer" name="closing_kilometer" required class="form-control" placeholder="Enter the KiloMeter" value="" autocomplete="off">
                </div>
            </div>

            <div class="col-12">
                <label class="form-label w-100" for="driver_speedmeter_image">Upload Image<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="file" id="driver_speedmeter_image" name="driver_speedmeter_image" required class="form-control">
                </div>
                <!-- Container for image previews -->
                <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="cancel-charge-button" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-charge-button">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#driveruploadclosingkm").submit(function(event) {
                    var form = $('#driveruploadclosingkm')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=driveruploadclosingkilometer&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>&Vendor_id=<?= $vendor_ID; ?>&Vehicle_type_ID=<?= $vehicle_type_ID; ?>&Vehicle_ID=<?= $vehicle_ID; ?>',
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
                            $(this).find("button[type='submit']").prop('disabled', false);
                            if (response.errors && response.errors.closing_kilometer_required) {
                                TOAST_NOTIFICATION('warning', 'Kilometer is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors && response.errors.closing_kilometer_min_error) {
                                TOAST_NOTIFICATION('warning', 'Closing Kilometer should be greater than Opening Kilometer', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors && response.errors.visited_charge_amount_required) {
                                TOAST_NOTIFICATION('error', 'Image not Uploaded', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPONSE
                            if (response.result_success === true) {
                                // RESULT SUCCESS
                                spinner.hide();
                                $('#driveruploadclosingkm')[0].reset();
                                window.location.href = response.redirect_URL;
                                $('#addDRIVERKILOMETERFORM').modal('hide');
                                TOAST_NOTIFICATION('success', 'Upload Image Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result_success === false) {
                                // RESULT FAILED
                                spinner.hide();
                                ERROR_ALERT(response.result_error || 'An error occurred during the update.');
                            }
                        }

                    });
                    event.preventDefault();
                });
            });

            document.getElementById('closeModalButton').addEventListener('click', function() {
                console.log('Close button clicked');
            });

            document.getElementById('driver_speedmeter_image').addEventListener('change', function(event) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                imagePreviewContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-2';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close position-absolute';
                        closeButton.style.top = '-10px';
                        closeButton.style.width = '6px';
                        closeButton.style.right = '-22px';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('driver_speedmeter_image').files = dataTransfer.files;
                }
            });
        </script>


    <?php elseif ($_GET['type'] == 'show_daycomplete') :

        $cstmr_ID = $_POST['CSTMRID'];

        if ($cstmr_ID != ''):
            $id = $_POST['ID'];
            $routeid = $_POST['ROUTEID'];
            $cstmr_id = Encryption::Decode($cstmr_ID, SECRET_KEY);
            $itinerary_plan_id = Encryption::Decode($id, SECRET_KEY);
            $itinerary_route_ID = Encryption::Decode($routeid, SECRET_KEY);

        else:
            $itinerary_plan_id = $_POST['ID'];
            $itinerary_route_ID = $_POST['ROUTEID'];
            $cstmr_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_id, 'primary_customer_contact_no');
        endif;



        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $guide_for_itinerary = $fetch_data['guide_for_itinerary'];
        endwhile;

    ?>


        <form id="driver_rating" class="row g-3 px-2 d-flex justify-content-center" action="" method="post" data-parsley-validate>
            <div class="col-12 text-center mt-2 mt-md-4">
                <img src="assets/img/success.gif" width="170px" height="130px" />
                <h5 class="mb-4 fw-bold day-success-text ">
                    Your Trip is Succesfully Completed
                </h5>
            </div>


            <div class="col-12 col-md-7 mb-3">

                <div class="card p-3 mb-4" style="background: #dad7fa52;">
                    <h5 class="mb-3 fw-bold your-ride-title">Your Ride</h5>
                    <div>
                        <h6 class="plan-datatime-text">
                            <span class="me-2"><img src="assets/img/dailymoment/calendar.png" width="15px" height="15px"></span> <?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                        </h6>
                        <h5 class="plan-location-title m-0 mb-2">
                            <?= $arrival_location; ?>
                            <span class="mx-2"><img src="assets/img/dailymoment/right-arrow.png" width="26px" height="26px"></span>
                            <?= $departure_location; ?>
                        </h5>
                    </div>
                </div>
                <?php if ($cstmr_id == ''): ?>
                    <div>
                        <?php
                        $select_itinerary_driver_charge = sqlQUERY_LABEL("SELECT `charge_type`, `charge_amount` FROM `dvi_confirmed_itinerary_dailymoment_charge` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                        $total_itinerary_driver_charge_count = sqlNUMOFROW_LABEL($select_itinerary_driver_charge);
                        if ($total_itinerary_driver_charge_count > 0) :
                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_driver_charge)) :
                                $charge_type = $fetch_data['charge_type'];
                                $charge_amount = $fetch_data['charge_amount'];
                                $total_charge_amount +=  $charge_amount;
                        ?>
                                <div class="d-flex align-items-center justify-content-between my-3 cost-details-success">
                                    <h6 class="cost-details-title"><?= $charge_type; ?></h6>
                                    <h6 class="cost-details-amount"><?= general_currency_symbol; ?> <?= number_format($charge_amount, 2); ?></h6>
                                </div>
                            <?php
                            endwhile;
                            ?>


                            <div class="d-flex align-items-center justify-content-between my-3">
                                <h6 class="cost-details-title"><b>Total Charge</b></h6>
                                <h6 class="cost-details-amount"><b><?= general_currency_symbol; ?> <?= number_format($total_charge_amount, 2); ?></b></h6>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
                <?php if ($cstmr_id != '' && $cstmr_id != '0'): ?>

                    <h5 class="mt-4 mb-2 fw-bold your-ride-title">Trip Review</h5>
                    <div>
                        <div class="mb-3" id="edited_star_ratings" data-rateyo-full-star="true"></div>
                        <input type="hidden" name="driver_rating" id="driver_rating_value" value="" />
                        <input type="hidden" name="cstmr_id" id="cstmr_id" value="<?= $cstmr_id ?>" />
                    </div>
                    <textarea class="form-control" id="review_description" name="review_description" rows="3" placeholder="Enter your Feedback"></textarea>
                <?php endif; ?>
            </div>
            <?php if ($cstmr_id != '' && $cstmr_id != '0'):

                $encodedPlanId = Encryption::Encode($itinerary_plan_id, SECRET_KEY);
                $encodedCustomerId = Encryption::Encode(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_id, 'primary_customer_id'), SECRET_KEY); ?>
                <div class="col-12 mb-3 text-center">
                    <button type="submit" class="start-trip-button">
                        Submit
                    </button>
                    <a href="<?= PUBLICPATH ?>dailymoment.php?formtype=driver&cstmrid=<?= $encodedCustomerId ?>&id=<?= $encodedPlanId ?>" class="end-trip-button ms-2">Back to Trip
                    </a>
                </div>
            <?php elseif ($cstmr_no == '0' || $cstmr_id == ''):
            ?>
                <div class="col-12 mb-3 text-center">
                    <a
                        href="javascript:void(0);"
                        onclick="ShareWhatsApp('<?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_id, 'primary_customer_contact_no'); ?>')"
                        class="start-trip-button">
                        Send Customer Feedback Link
                    </a>
                    <a href="<?= PUBLICPATH ?>dailymoment.php?formtype=driver&id=<?= $itinerary_plan_id ?>" class="end-trip-button ms-2">Back to Trip
                    </a>
                </div>
            <?php endif; ?>
        </form>



        <script>
            $(function() {
                $("#edited_star_ratings").rateYo({
                    fullStar: true, // To enable full star rating
                    onSet: function(rating, rateYoInstance) {
                        $("#driver_rating_value").val(rating); // Set the rating value in the hidden input field
                    }
                });
            });

            <?php if ($cstmr_no != '' && $cstmr_id == ''): ?>

                function ShareWhatsApp(contactNumber) {

                    <?php
                    // Encrypt the necessary IDs
                    $encodedPlanId = Encryption::Encode($itinerary_plan_id, SECRET_KEY);
                    $encodedCustomerId = Encryption::Encode(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_id, 'primary_customer_id'), SECRET_KEY);
                    ?>


                    const feedbackLink = "<?= PUBLICPATH ?>dailymoment.php?formtype=driver&cstmrid=<?= $encodedCustomerId ?>&id=<?= $encodedPlanId ?>";


                    // Ensure the contact number is in the correct format (e.g., without special characters)
                    const sanitizedNumber = contactNumber.replace(/\D/g, '');
                    console.log(sanitizedNumber);
                    if (sanitizedNumber) {
                        // Construct the WhatsApp share URL
                        const whatsappUrl = `https://wa.me/${sanitizedNumber}?text=Hello,%20please%20share%20your%20trip%20feedback%20through%20this%20link:%20${encodeURIComponent(feedbackLink)}`;

                        // Open the WhatsApp URL in a new tab or window
                        window.open(whatsappUrl, '_blank');
                    } else {
                        TOAST_NOTIFICATION('error', 'Invalid contact no: <?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_id, 'primary_customer_contact_no'); ?>  !', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }
                }
            <?php endif; ?>

            $(document).ready(function() {

                //AJAX FORM SUBMIT
                $("#driver_rating").submit(function(event) {
                    var form = $('#driver_rating')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=driverrating&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            TOAST_NOTIFICATION('error', 'Trip Not Completed', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#driver_rating')[0].reset();
                                window.location.href = response.redirect_URL;
                                TOAST_NOTIFICATION('success', 'Trip Completed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

    <?php elseif ($_GET['type'] == 'show_daycomplete_guide') :

        $itinerary_plan_id = $_POST['ID'];
        $itinerary_route_ID = $_POST['ROUTEID'];

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $guide_for_itinerary = $fetch_data['guide_for_itinerary'];
        endwhile;

    ?>


        <form id="guide_rating" class="row g-3 px-2 d-flex justify-content-center" action="" method="post" data-parsley-validate>
            <div class="col-12 text-center mt-2 mt-md-4">
                <img src="assets/img/success.gif" width="170px" height="130px" />
                <h5 class="mb-4 fw-bold day-success-text ">
                    Your Trip is Succesfully Completed
                </h5>
            </div>


            <div class="col-12 col-md-7 mb-3">

                <div class="card p-3 mb-4" style="background: #dad7fa52;">
                    <h5 class="mb-3 fw-bold your-ride-title">Your Ride</h5>
                    <div>
                        <h6 class="plan-datatime-text">
                            <span class="me-2"><img src="assets/img/dailymoment/calendar.png" width="15px" height="15px"></span> <?= $formattedstart_date; ?> to <?= $formattedend_date; ?> (<?= $no_of_nights; ?>N/<?= $no_of_days; ?>D)
                        </h6>
                        <h5 class="plan-location-title m-0 mb-2">
                            <?= $arrival_location; ?>
                            <span class="mx-2"><img src="assets/img/dailymoment/right-arrow.png" width="26px" height="26px"></span>
                            <?= $departure_location; ?>
                        </h5>
                    </div>
                </div>
                <h5 class="mt-4 mb-2 fw-bold your-ride-title">Review</h5>
                <div>
                    <div class="mb-3" id="guide_edited_star_ratings" data-rateyo-full-star="true"></div>
                    <input type="hidden" name="guide_rating" id="guide_rating_value" value="1" />
                </div>
                <textarea class="form-control" id="review_description" name="review_description" rows="3" placeholder="Enter your Feedback"></textarea>
            </div>
            <div class="col-12 mb-3 text-center">
                <button type="submit" class="start-trip-button">
                    Submit
                </button>
            </div>
        </form>



        <script>
            $(function() {
                $("#guide_edited_star_ratings").rateYo({
                    fullStar: true, // To enable full star rating
                    onSet: function(rating, rateYoInstance) {
                        $("#guide_rating_value").val(rating); // Set the rating value in the hidden input field
                    }
                });
            });


            $(document).ready(function() {

                //AJAX FORM SUBMIT
                $("#guide_rating").submit(function(event) {
                    var form = $('#guide_rating')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: './head/engine/ajax/ajax_dailymoment_manage.php?type=guiderating&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            TOAST_NOTIFICATION('error', 'Trip Completed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#guide_rating')[0].reset();
                                window.location.href = response.redirect_URL;
                                TOAST_NOTIFICATION('success', 'Trip Not Completed', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

    <?php elseif ($_GET['type'] == 'not_visiting_driver_guide') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_guide_ID = $_GET['GUIDE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT  `guide_id` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_guide_ID` = '$itinerary_guide_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $guide_id = $fetch_data['guide_id'];
        endwhile;

    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Guide NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[<?= getGUIDEDETAILS($guide_id, 'label'); ?> ]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-guide-id="<?php echo $itinerary_guide_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <link rel="stylesheet" href="assets/css/parsley_validation.css">
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var guideID = saveButton.getAttribute('data-guide-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, guideID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, guideID, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = guideID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=guidestatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDGUIDEFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'not_visiting_driver_wholedayguide') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_guide_ID = $_GET['GUIDE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT  `guide_id` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `route_guide_ID` = '$itinerary_guide_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $guide_id = $fetch_data['guide_id'];
        endwhile;

    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Guide NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[<?= getGUIDEDETAILS($guide_id, 'label'); ?> ]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-guide-id="<?php echo $itinerary_guide_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <link rel="stylesheet" href="assets/css/parsley_validation.css">
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var guideID = saveButton.getAttribute('data-guide-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, guideID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, guideID, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = guideID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=wholeday_guidestatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDGUIDEFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>


    <?php elseif ($_GET['type'] == 'not_visiting') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $route_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_type_ID = $_GET['TYPE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT `driver_hotspot_status`, `driver_not_visited_description`, `hotspot_ID`, `item_type` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$itinerary_type_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
            $item_type = $fetch_data['item_type'];
            $hotspot_ID = $fetch_data['hotspot_ID'];
            $driver_not_visited_description = $fetch_data['driver_not_visited_description'];
        endwhile;

        if ($item_type == 4):
            $get_title = getHOTSPOTDETAILS($hotspot_ID, 'label');
        elseif ($item_type == 6):
            $get_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
        elseif ($item_type == 7):
            $get_title = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location');
        endif;

    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Hotspot NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[ <?= $get_title; ?>]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" class="form-control" required placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-hotspot-id="<?php echo $route_hotspot_ID; ?>"
                    data-type-id="<?php echo $itinerary_type_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var typeID = saveButton.getAttribute('data-type-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, hotspotID, typeID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=hotspotstatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'hostpot_guide_not_visiting') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $route_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_type_ID = $_GET['TYPE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT `driver_hotspot_status`, `guide_not_visited_description`, `hotspot_ID`, `item_type` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$itinerary_type_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
            $item_type = $fetch_data['item_type'];
            $hotspot_ID = $fetch_data['hotspot_ID'];
            $guide_not_visited_description = $fetch_data['guide_not_visited_description'];
        endwhile;

        if ($item_type == 4):
            $get_title = getHOTSPOTDETAILS($hotspot_ID, 'label');
        elseif ($item_type == 6):
            $get_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
        elseif ($item_type == 7):
            $get_title = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location');
        endif;

    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Hotspot NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[ <?= $get_title; ?>]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" class="form-control" required placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-hotspot-id="<?php echo $route_hotspot_ID; ?>"
                    data-type-id="<?php echo $itinerary_type_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var typeID = saveButton.getAttribute('data-type-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, hotspotID, typeID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=guide_hotspotstatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'not_visiting_activity') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_activity_ID = $_GET['ACTIVITY_ID'];
        $itinerary_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$itinerary_hotspot_ID' AND `route_activity_ID` = '$itinerary_activity_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $activity_ID = $fetch_data['activity_ID'];
        endwhile;
    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Activity NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[ <?= getACTIVITYDETAILS($activity_ID, 'label', ''); ?>]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-activity-id="<?php echo $itinerary_activity_ID; ?>"
                    data-hotspot-id="<?php echo $itinerary_hotspot_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var activityID = saveButton.getAttribute('data-activity-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, activityID, hotspotID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, planID, routeID, route_activity_ID, route_hotspot_ID, not_description) {
                var PLAN_ID = planID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = routeID;
                var ACTIVITY_ID = route_activity_ID;
                var HOTSPOT_ID = route_hotspot_ID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=activitystatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ACTIVITY_ID,
                        route_hotspot_ID: HOTSPOT_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDACTIVITYFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'guide_not_visiting_activity') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_activity_ID = $_GET['ACTIVITY_ID'];
        $itinerary_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$itinerary_hotspot_ID' AND `route_activity_ID` = '$itinerary_activity_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $activity_ID = $fetch_data['activity_ID'];
        endwhile;
    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-2">Activity NotVisited Confirmation</h5>
                    <h5 class="plan-location-title">[ <?= getACTIVITYDETAILS($activity_ID, 'label', ''); ?>]</h5>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="save-button-modal" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-activity-id="<?php echo $itinerary_activity_ID; ?>"
                    data-hotspot-id="<?php echo $itinerary_hotspot_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var activityID = saveButton.getAttribute('data-activity-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, activityID, hotspotID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, planID, routeID, route_activity_ID, route_hotspot_ID, not_description) {
                var PLAN_ID = planID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = routeID;
                var ACTIVITY_ID = route_activity_ID;
                var HOTSPOT_ID = route_hotspot_ID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: './head/engine/ajax/ajax_dailymoment_manage.php?type=guide_activitystatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ACTIVITY_ID,
                        route_hotspot_ID: HOTSPOT_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDACTIVITYFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>
<?php
    endif;
endif;
?>