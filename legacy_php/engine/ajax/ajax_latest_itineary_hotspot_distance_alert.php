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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
        $itinerary_route_ID = $_GET['itinerary_route_ID'];
        $hotspot_ID = $_GET['hotspot_ID'];
        $conflict_hotspot_ID = $_GET['conflict_hotspot_ID'];
        $alert_TYPE = $_GET['alert_TYPE'];
        $group_type = $_GET['group_type'];

        $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');
        $itinerary_preference = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');

        $select_last_itineary_hotspot_details = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`hotspot_ID`, HOTSPOT.`hotspot_location`, ROUTE_HOTSPOT.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` WHERE ROUTE_HOTSPOT.`deleted` = '0' and ROUTE_HOTSPOT.`status` = '1' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_HOTSPOT.`item_type` = '4' ORDER BY ROUTE_HOTSPOT.`route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
        while ($fetch_last_hotspot_data = sqlFETCHARRAY_LABEL($select_last_itineary_hotspot_details)) :
            $get_last_hotspot_ID = $fetch_last_hotspot_data['hotspot_ID'];
            $get_last_hotspot_location = $fetch_last_hotspot_data['hotspot_location'];
            $get_last_hotspot_end_time = $fetch_last_hotspot_data['hotspot_end_time'];
        endwhile;

        $select_previous_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_name`, `hotspot_description`, `hotspot_latitude`, `hotspot_longitude`, `hotspot_location` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$get_last_hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_PR_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_previous_hotspot_data = sqlFETCHARRAY_LABEL($select_previous_hotspot_details)) :
            $previous_hotspot_name = $fetch_previous_hotspot_data['hotspot_name'];
            $previous_hotspot_description = $fetch_previous_hotspot_data['hotspot_description'];
            $previous_hotspot_latitude = $fetch_previous_hotspot_data['hotspot_latitude'];
            $previous_hotspot_longitude = $fetch_previous_hotspot_data['hotspot_longitude'];
            $previous_hotspot_location = $fetch_previous_hotspot_data['hotspot_location'];
        endwhile;

        $select_next_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_name`, `hotspot_description`, `hotspot_latitude`, `hotspot_longitude`, `hotspot_location` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_NXT_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_next_hotspot_data = sqlFETCHARRAY_LABEL($select_next_hotspot_details)) :
            $next_hotspot_name = $fetch_next_hotspot_data['hotspot_name'];
            $next_hotspot_description = $fetch_next_hotspot_data['hotspot_description'];
            $next_hotspot_latitude = $fetch_next_hotspot_data['hotspot_latitude'];
            $next_hotspot_longitude = $fetch_next_hotspot_data['hotspot_longitude'];
            $next_hotspot_location = $fetch_next_hotspot_data['hotspot_location'];
        endwhile;

        $get_start_location_name = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_name');
        $get_start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');
        $get_start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $get_start_location_id);
        $get_end_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $get_start_location_id);
        $get_itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');

        // Convert the date string to a Unix timestamp using strtotime
        $timestamp = strtotime($get_itinerary_route_date);

        if ($timestamp !== false) :
            // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
            $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;

            // If you want to get the day name (Sunday, Monday, etc.), you can use:
            $dayOfWeekName = date('l', $timestamp);
        //echo "Day of the week (name): $dayOfWeekName";
        endif;

        // Determine the travel location type
        $travel_location_type_for_previous = getTravelLocationType($previous_hotspot_location, $next_hotspot_location);
        $travel_location_type_for_next = getTravelLocationType($get_last_hotspot_location, $next_hotspot_location);

        $check_previous_travelling_distance = calculateDistanceAndDuration($get_start_longitude, $get_end_longitude, $previous_hotspot_latitude, $previous_hotspot_longitude, $travel_location_type_for_previous);

        $check_next_travelling_distance = calculateDistanceAndDuration($get_start_longitude, $get_end_longitude, $next_hotspot_latitude, $next_hotspot_longitude, $travel_location_type_for_next);
?>
        <div class="row">
            <button type="button" id="close_hotspot_distance_alert_modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center">
                <svg class="icon-44 text-warning" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <?php if ($alert_TYPE == 'true'): ?>
                <h4 class="text-center mt-3">Hotspot Distance Alert !!!</h4>
                <p class="text-start mt-3">Since you have already added <b><?= $previous_hotspot_name; ?></b> to your itinerary, we recommend visiting <b><?= $next_hotspot_name; ?></b> first for a smoother experience.<br /><br /> To follow the recommended route, click <b>Add Suggested Way.</b> If you prefer a different order, click <b>Add Anyway</b> to include the final hotspot around <b><?= date('h:i A', strtotime($get_last_hotspot_end_time)); ?></b>.</p>
                <div class="text-center mt-3 pb-0">
                    <button type="button" class="btn btn-outline-primary" onclick="declineHOTSPOTDISTANCEALERT('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $get_last_hotspot_ID; ?>','<?= $hotspot_ID; ?>','<?= $conflict_hotspot_ID; ?>')">Add Anyway</button>
                    <button type="button" class="btn btn-primary" onclick="confirmHOTSPOTDISTANCEALERT('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $get_last_hotspot_ID; ?>','<?= $hotspot_ID; ?>','<?= $conflict_hotspot_ID; ?>')">Add Suggested Way</button>
                </div>
            <?php else: ?>
                <h4 class="text-center mt-3">Add Hotspots Around <b><?= date('h:i A', strtotime($get_last_hotspot_end_time)); ?></b></h4>
                <p class="text-start mt-3">
                    After visiting <b><?= $previous_hotspot_name; ?></b>, the system recommends adding <b><?= $next_hotspot_name; ?></b> next to optimize your itinerary.<br /><br />
                    To proceed with this suggestion, click <b>Add Anyway</b> to include the hotspot around <b><?= date('h:i A', strtotime($get_last_hotspot_end_time)); ?></b>.
                </p>
                <div class="text-center mt-3 pb-0">
                    <button type="button" class="btn btn-outline-primary" onclick="declineHOTSPOTDISTANCEALERT('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $get_last_hotspot_ID; ?>','<?= $hotspot_ID; ?>','<?= $conflict_hotspot_ID; ?>')">Add Anyway</button>
                </div>
            <?php endif; ?>
        </div>
        <script>
            function declineHOTSPOTDISTANCEALERT(itinerary_plan_ID, itinerary_route_ID, get_last_hotspot_ID, hotspot_ID, conflict_hotspot_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=decline_hotspot_distance_alert',
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        get_last_hotspot_ID: get_last_hotspot_ID,
                        hotspot_ID: hotspot_ID,
                        conflict_hotspot_ID: conflict_hotspot_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.something_went_wrong) {
                                TOAST_NOTIFICATION('warning', response.errors.something_went_wrong, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                TOAST_NOTIFICATION('warning', response.errors.hotspot_operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.exceeds_route_end_time) {
                                TOAST_NOTIFICATION('warning', response.errors.exceeds_route_end_time, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            TOAST_NOTIFICATION('success', 'Successfully Hotspot Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_hotspot_distance_alert_modal').click();
                            $('.btn-close').click();
                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                setTimeout(function() {
                                    getVEHICLEPLANDETAILS(itinerary_plan_ID);
                                }, 1000); // 30 seconds in milliseconds

                                setTimeout(function() {
                                    showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                                    showITINEARY_ROUTE_HOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID);
                                    showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID);
                                }, 300); // 30 seconds in milliseconds
                            <?php else: ?>
                                showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                                showITINEARY_ROUTE_HOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID);
                            <?php endif; ?>
                        }
                    }
                });
            }

            function confirmHOTSPOTDISTANCEALERT(itinerary_plan_ID, itinerary_route_ID, get_last_hotspot_ID, hotspot_ID, conflict_hotspot_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=proceed_with_suggested_order_itinerary_route_hotspot',
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        get_last_hotspot_ID: get_last_hotspot_ID,
                        hotspot_ID: hotspot_ID,
                        conflict_hotspot_ID: conflict_hotspot_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.something_went_wrong) {
                                TOAST_NOTIFICATION('warning', response.errors.something_went_wrong, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                /* TOAST_NOTIFICATION('warning', response.errors.hotspot_operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', ''); */
                                let conflict_hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID; // Assuming this is an array
                                // Convert array to a comma-separated string
                                let conflict_hotspot_ID_string = conflict_hotspot_ID.join(',');
                                let dayOfWeekNumeric = '<?= $dayOfWeekNumeric; ?>';
                                let itinerary_plan_ID = '<?= $itinerary_plan_ID; ?>';
                                let itinerary_route_ID = '<?= $itinerary_route_ID; ?>'
                                replaceHOTSPOTS(conflict_hotspot_ID_string, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, hotspot_ID);
                            } else if (response.errors.exceeds_route_end_time) {
                                TOAST_NOTIFICATION('warning', response.errors.exceeds_route_end_time, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                setTimeout(function() {
                                    getVEHICLEPLANDETAILS(itinerary_plan_ID);
                                    showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                                    showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID);
                                }, 300); // 30 seconds in milliseconds
                            <?php else: ?>
                                showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                            <?php endif; ?>
                            TOAST_NOTIFICATION('success', 'Successfully Hotspot Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_hotspot_distance_alert_modal').click();
                            $('.btn-close').click();
                        }
                    }
                });
            }

            function replaceHOTSPOTS(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID) {
                var group_type = '<?= $group_type; ?>';
                $('#HOTSPOTCONFLICTMODALINFODATA').modal('hide');
                $('.receiving-hotspot-conflict-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=confirm_replace_hotspots&conflict_hotspot_ID=' + conflict_hotspot_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&new_hotspot_ID=' + new_hotspot_ID + '&group_type=' + group_type, function() {
                    const container = document.getElementById("HOTSPOTCONFLICTMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function getVEHICLEPLANDETAILS(itinerary_plan_ID) {
                return new Promise(function(resolve, reject) {
                    $('#show_itineary_loader').addClass('d-block');
                    $('#show_itineary_loader').removeClass('d-none');
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_latest_itineary_manage_vehicle_details.php?type=add_vehicle_plan',
                        data: {
                            _ID: itinerary_plan_ID
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#show_itineary_loader').addClass('d-none');
                            $('#show_itineary_loader').removeClass('d-block');
                            if (!response.success) {
                                // Handle errors here
                                var errorMessage = "";
                                $.each(response.errors, function(key, value) {
                                    errorMessage = value;
                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '',
                                        '', '', '', '', '', '');
                                });
                            } else {
                                // Handle success here
                                resolve();
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX error here
                            reject(error);
                        }
                    });
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'confirm_replace_hotspots') :

        // Retrieve parameters from the GET request
        $conflict_hotspot_ID = $_GET['conflict_hotspot_ID'];
        $dayOfWeekNumeric = $_GET['dayOfWeekNumeric'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
        $itinerary_route_ID = $_GET['itinerary_route_ID'];
        $new_hotspot_ID = $_GET['new_hotspot_ID'];
        $group_type = $_GET['group_type'];

        if (isset($_GET['conflict_hotspot_ID'])):
            $conflict_hotspot_ID_array = explode(',', $_GET['conflict_hotspot_ID']);
        endif;

        $itinerary_preference = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');

        // Get the new hotspot name
        $new_hotspot_name = getHOTSPOTDETAILS($new_hotspot_ID, 'label');

        // Prepare an array to hold conflict hotspot details
        $conflict_hotspots = [];

        // Loop through each hotspot ID to get details
        foreach ($conflict_hotspot_ID_array as $hotspot_ID) :
            $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
            $operating_hours = getHOTSPOT_OPERATING_HOURS($hotspot_ID, $dayOfWeekNumeric, 'get_hotspot_operating_hours');

            // Query to get activities related to the conflicting hotspots
            $activity_query = sqlQUERY_LABEL("SELECT a.`activity_title`, a.`activity_duration` FROM `dvi_activity` AS a JOIN `dvi_itinerary_route_activity_details` AS ira ON a.`activity_id` = ira.`activity_ID` WHERE ira.`hotspot_ID` = '$hotspot_ID' AND ira.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ira.`itinerary_route_ID` = '$itinerary_route_ID' AND ira.`status` = '1' AND ira.`deleted` = '0'") or die("#1-getActivities: " . sqlERROR_LABEL());

            // Prepare an array to hold the activities for the current hotspot
            $activities = [];
            while ($activity_data = sqlFETCHARRAY_LABEL($activity_query)) :
                $activities[] = [
                    'title' => $activity_data['activity_title'],
                    'duration' => $activity_data['activity_duration']
                ];
            endwhile;

            // Add the conflict hotspot details along with activities to the array
            $conflict_hotspots[] = [
                'name' => $hotspot_name,
                'hours' => $operating_hours,
                'activities' => $activities
            ];
        endforeach;
    ?>

        <div class="row">
            <button type="button" id="close_hotspot_distance_alert_modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center">
                <svg class="icon-44 text-warning" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <h4 class="text-center mt-3">Hotspot Hours Overlap Issue</h4>

            <p class="text-start mt-2">
                You have expressed interest in visiting <strong><?= $new_hotspot_name; ?></strong>. To facilitate this addition to your itinerary, the following hotspots must be removed due to conflicting operating hours:
            </p>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Hotspot Name</th>
                            <th>Operating Hours</th>
                            <th>Conflicting Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conflict_hotspots as $conflict): ?>
                            <tr>
                                <td><?= $conflict['name']; ?></td>
                                <td><?= $conflict['hours']; ?></td>
                                <td>
                                    <?php if (!empty($conflict['activities'])): ?>
                                        <ul>
                                            <?php foreach ($conflict['activities'] as $activity): ?>
                                                <li><?= $activity['title']; ?> (Duration: <?= $activity['duration']; ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span>No conflicting activities</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-start mt-3">
                <strong>Time Constraint Notice:</strong> Please note that visiting both the new hotspot and the existing ones may lead to tight scheduling. We recommend adjusting your itinerary accordingly.
            </p>

            <div class="text-center mt-3 pb-0">
                <button type="button" class="btn btn-secondary" id="close_hotspot_distance_alert_modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmreplaceHOTSPOTS('<?= $conflict_hotspot_ID; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $new_hotspot_ID; ?>')">Yes, Delete and Add</button>
            </div>
        </div>

        <script>
            function confirmreplaceHOTSPOTS(delete_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID) {
                const deleteHotspotArray = delete_hotspot_ID.split(',');
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_replace_hotspots',
                    data: {
                        delete_hotspot_ID: deleteHotspotArray,
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        new_hotspot_ID: new_hotspot_ID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.something_went_wrong) {
                                TOAST_NOTIFICATION('warning', response.errors.something_went_wrong, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                /* TOAST_NOTIFICATION('warning', response.errors.hotspot_operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', ''); */
                                let hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID;
                                let dayOfWeekNumeric = response.errors.hotspot_operating_hours_not_available_dayOfWeekNumeric;
                                let itinerary_plan_ID = response.errors.hotspot_operating_hours_not_available_itinerary_plan_ID;
                                let itinerary_route_ID = response.errors.hotspot_operating_hours_not_available_itinerary_route_ID;
                                let new_hotspot_ID = response.errors.try_to_add_new_hotspot_ID;
                                replaceHOTSPOTS(hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID);
                            } else if (response.errors.exceeds_route_end_time) {
                                TOAST_NOTIFICATION('warning', response.errors.exceeds_route_end_time, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            TOAST_NOTIFICATION('success', 'Successfully Hotspot Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_hotspot_distance_alert_modal').click();
                            $('.btn-close').click();
                            <?php if (in_array(array($itinerary_preference), array(2, 3))) : ?>
                                setTimeout(function() {
                                    getVEHICLEPLANDETAILS(itinerary_plan_ID);
                                    showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                                    showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID);
                                }, 300); // 30 seconds in milliseconds
                            <?php else: ?>
                                showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                            <?php endif; ?>
                        }
                    }
                });
            }

            function replaceHOTSPOTS(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID) {
                var group_type = '<?= $group_type; ?>';
                $('#MODALINFODATA').modal('hide');
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=confirm_replace_hotspots&conflict_hotspot_ID=' + conflict_hotspot_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&new_hotspot_ID=' + new_hotspot_ID + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_vehicle_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#showVEHICLEINFO').html('');
                        $('#showVEHICLEINFO').html(response);
                    }
                });
            }

            function getVEHICLEPLANDETAILS(itinerary_plan_ID) {
                return new Promise(function(resolve, reject) {
                    $('#show_itineary_loader').addClass('d-block');
                    $('#show_itineary_loader').removeClass('d-none');
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_latest_itineary_manage_vehicle_details.php?type=add_vehicle_plan',
                        data: {
                            _ID: itinerary_plan_ID
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#show_itineary_loader').addClass('d-none');
                            $('#show_itineary_loader').removeClass('d-block');
                            if (!response.success) {
                                // Handle errors here
                                var errorMessage = "";
                                $.each(response.errors, function(key, value) {
                                    errorMessage = value;
                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '',
                                        '', '', '', '', '', '');
                                });
                            } else {
                                // Handle success here
                                resolve();
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX error here
                            reject(error);
                        }
                    });
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'confirm_replace_hotspots_with_activity') :

        // Retrieve parameters from the GET request
        $conflict_hotspot_ID = $_GET['conflict_hotspot_ID'];
        $dayOfWeekNumeric = $_GET['dayOfWeekNumeric'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
        $itinerary_route_ID = $_GET['itinerary_route_ID'];
        $itinerary_route_hotspot_ID = $_GET['itinerary_route_hotspot_ID'];
        $itinerary_hotspot_ID = $_GET['itinerary_hotspot_ID'];
        $try_to_add_new_activity_ID = $_GET['try_to_add_new_activity_ID'];
        $group_type = $_GET['group_type'];

        if (isset($_GET['conflict_hotspot_ID'])):
            $conflict_hotspot_ID_array = explode(',', $_GET['conflict_hotspot_ID']);
        endif;

        $itinerary_preference = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');

        // Get the new hotspot name
        $new_hotspot_name = getHOTSPOTDETAILS($itinerary_hotspot_ID, 'label');
        $activity_name = getACTIVITYDETAILS($try_to_add_new_activity_ID, 'label', '');

        // Prepare an array to hold conflict hotspot details
        $conflict_hotspots = [];

        // Loop through each hotspot ID to get details
        foreach ($conflict_hotspot_ID_array as $hotspot_ID) :
            $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
            $operating_hours = getHOTSPOT_OPERATING_HOURS($hotspot_ID, $dayOfWeekNumeric, 'get_hotspot_operating_hours');

            // Query to get activities related to the conflicting hotspots
            $activity_query = sqlQUERY_LABEL("SELECT a.`activity_title`, a.`activity_duration` FROM `dvi_activity` AS a JOIN `dvi_itinerary_route_activity_details` AS ira ON a.`activity_id` = ira.`activity_ID` WHERE ira.`hotspot_ID` = '$hotspot_ID' AND ira.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ira.`itinerary_route_ID` = '$itinerary_route_ID' AND ira.`status` = '1' AND ira.`deleted` = '0'") or die("#1-getActivities: " . sqlERROR_LABEL());

            // Prepare an array to hold the activities for the current hotspot
            $activities = [];
            while ($activity_data = sqlFETCHARRAY_LABEL($activity_query)) :
                $activities[] = [
                    'title' => $activity_data['activity_title'],
                    'duration' => $activity_data['activity_duration']
                ];
            endwhile;

            // Add the conflict hotspot details along with activities to the array
            $conflict_hotspots[] = [
                'name' => $hotspot_name,
                'hours' => $operating_hours,
                'activities' => $activities
            ];
        endforeach;
    ?>

        <div class="row">
            <button type="button" id="close_hotspot_distance_alert_modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center">
                <svg class="icon-44 text-warning" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <h4 class="text-center mt-3">Hotspot Hours Overlap Issue</h4>

            <p class="text-start mt-2">
                You have expressed interest in visiting <strong><?= $new_hotspot_name; ?> - <?= $activity_name; ?></strong>. To facilitate this addition to your itinerary, the following hotspots and activities must be removed due to conflicting operating hours:
            </p>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Hotspot Name</th>
                            <th>Operating Hours</th>
                            <th>Conflicting Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conflict_hotspots as $conflict): ?>
                            <tr>
                                <td><?= $conflict['name']; ?></td>
                                <td><?= $conflict['hours']; ?></td>
                                <td>
                                    <?php if (!empty($conflict['activities'])): ?>
                                        <ul>
                                            <?php foreach ($conflict['activities'] as $activity): ?>
                                                <li><?= $activity['title']; ?> (Duration: <?= $activity['duration']; ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span>No conflicting activities</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-start mt-3">
                <strong>Time Constraint Notice:</strong> Please note that visiting both the new hotspot and the existing ones may lead to tight scheduling. We recommend adjusting your itinerary accordingly.
            </p>

            <div class="text-center mt-3 pb-0">
                <button type="button" class="btn btn-secondary" id="close_hotspot_distance_alert_modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmreplaceHOTSPOTSWITHACTIVITY('<?= $conflict_hotspot_ID; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $itinerary_route_hotspot_ID; ?>','<?= $itinerary_hotspot_ID; ?>','<?= $try_to_add_new_activity_ID; ?>')">Yes, Delete and Add</button>
            </div>
        </div>

        <script>
            function confirmreplaceHOTSPOTSWITHACTIVITY(delete_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, itinerary_route_hotspot_ID, itinerary_hotspot_ID, try_to_add_new_activity_ID) {
                const deleteHotspotArray = delete_hotspot_ID.split(',');
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_replace_hotspots_with_activity',
                    data: {
                        delete_hotspot_ID: deleteHotspotArray,
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_route_hotspot_ID: itinerary_route_hotspot_ID,
                        itinerary_hotspot_ID: itinerary_hotspot_ID,
                        try_to_add_new_activity_ID: try_to_add_new_activity_ID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.something_went_wrong) {
                                TOAST_NOTIFICATION('warning', response.errors.something_went_wrong, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                /* TOAST_NOTIFICATION('warning', response.errors.hotspot_operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', ''); */
                                let conflict_hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID;
                                let dayOfWeekNumeric = response.errors.hotspot_operating_hours_not_available_dayOfWeekNumeric;
                                let itinerary_plan_ID = response.errors.hotspot_operating_hours_not_available_itinerary_plan_ID;
                                let itinerary_route_ID = response.errors.hotspot_operating_hours_not_available_itinerary_route_ID;
                                let itinerary_route_hotspot_ID = response.errors.itinerary_route_hotspot_ID;
                                let itinerary_hotspot_ID = response.errors.itinerary_hotspot_ID;
                                let try_to_add_new_activity_ID = response.errors.try_to_add_new_activity_ID;
                                replaceHOTSPOTS_WITH_ACTIVITY(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, itinerary_route_hotspot_ID, itinerary_hotspot_ID, try_to_add_new_activity_ID);
                            } else if (response.errors.exceeds_route_end_time) {
                                TOAST_NOTIFICATION('warning', response.errors.exceeds_route_end_time, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            TOAST_NOTIFICATION('success', 'Successfully Hotspot Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_hotspot_distance_alert_modal').click();
                            $('.btn-close').click();
                            <?php if (in_array(array($itinerary_preference), array(2, 3))) : ?>
                                setTimeout(function() {
                                    getVEHICLEPLANDETAILS(itinerary_plan_ID);
                                    showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                                    showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID);
                                }, 300); // 30 seconds in milliseconds
                            <?php else: ?>
                                showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $group_type; ?>');
                            <?php endif; ?>
                        }
                    }
                });
            }

            function replaceHOTSPOTS_WITH_ACTIVITY(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, itinerary_route_hotspot_ID, itinerary_hotspot_ID, try_to_add_new_activity_ID) {
                var group_type = '<?= $group_type; ?>';
                $('#MODALINFODATA').modal('hide');
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=confirm_replace_hotspots_with_activity&conflict_hotspot_ID=' + conflict_hotspot_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&itinerary_route_hotspot_ID=' + itinerary_route_hotspot_ID + '&itinerary_hotspot_ID=' + itinerary_hotspot_ID + '&try_to_add_new_activity_ID=' + try_to_add_new_activity_ID + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_vehicle_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#showVEHICLEINFO').html('');
                        $('#showVEHICLEINFO').html(response);
                    }
                });
            }

            function getVEHICLEPLANDETAILS(itinerary_plan_ID) {
                return new Promise(function(resolve, reject) {
                    $('#show_itineary_loader').addClass('d-block');
                    $('#show_itineary_loader').removeClass('d-none');
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_latest_itineary_manage_vehicle_details.php?type=add_vehicle_plan',
                        data: {
                            _ID: itinerary_plan_ID
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#show_itineary_loader').addClass('d-none');
                            $('#show_itineary_loader').removeClass('d-block');
                            if (!response.success) {
                                // Handle errors here
                                var errorMessage = "";
                                $.each(response.errors, function(key, value) {
                                    errorMessage = value;
                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '',
                                        '', '', '', '', '', '');
                                });
                            } else {
                                // Handle success here
                                resolve();
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX error here
                            reject(error);
                        }
                    });
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
