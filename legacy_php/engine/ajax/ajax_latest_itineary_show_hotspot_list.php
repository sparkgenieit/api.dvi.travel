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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $itinerary_route_ID = $_POST['itinerary_route_ID'];
        $itinerary_route_date = $_POST['itinerary_route_date'];
        $selected_group_type = $_POST['group_type'];

        $stored_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');
        // $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');
        $route_start_time = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'route_start_time');
        $itinerary_preference = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'direct_to_next_visiting_place');

        // Convert the date string to a Unix timestamp using strtotime
        $timestamp = strtotime($itinerary_route_date);

        if ($timestamp !== false) :
            // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
            $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
        endif;

        //LOCATION LOCATION NAME
        if ($direct_to_next_visiting_place != 1) :
            $location_name = getSTOREDLOCATIONDETAILS($stored_location_id, 'SOURCE_LOCATION');
            $filter_location_name = " hp.`hotspot_location` LIKE '%$location_name%' OR ";
        else :
            $location_name = '';
        endif;

        //NEXT VISITING PLACE LOCATION NAME
        $next_visiting_name = getSTOREDLOCATIONDETAILS($stored_location_id, 'DESTINATION_LOCATION');

        $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

        if ($get_via_route_IDs) :
            if ($get_via_route_IDs) :
                $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
            endif;

            // VIA ROUTE LOCATION NAME
            $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($stored_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

            // Ensure $via_route_name is an array
            if (is_array($via_route_name) && !empty($via_route_name)) :
                // Create conditions for each via route using LIKE
                $via_route_conditions = array_map(
                    function ($location) {
                        return " `hotspot_location` LIKE '%$location%' ";
                    },
                    $via_route_name
                );

                // Join all conditions with ' OR '
                $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);

                // Prepare via route label for display, if needed
                $via_route_name_label = implode(' & ', $via_route_name);
            else :
                $add_filter_via_route_location = '';
                $via_route_name_label = '';
            endif;
        else:
            $via_route_name = [];
            $add_filter_via_route_location = '';
            $via_route_name_label = '';
        endif;

        // Concatenate the variables
        if ($location_name && $next_visiting_name && $via_route_name) :
            $get_all_the_name = $location_name . ' & ' . $next_visiting_name . ' & ' . $via_route_name_label;
        elseif ($location_name && $next_visiting_name) :
            $get_all_the_name = $location_name . ' & ' . $next_visiting_name;
        endif;
?>
        <style>
            .tooltip-inner {
                max-width: 600px;
                /* Adjust the width as needed */
                width: auto;
                /* Allow the tooltip to expand based on content */
                text-align: left;
            }

            .location-title {
                font-size: 24px;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
                color: #2c3e50;
                text-transform: uppercase;
                border-bottom: 2px solid #2980b9;
                padding-bottom: 5px;
            }

            .fixed-location-title {
                position: fixed;
                top: 0;
                width: 100%;
                background: #ffffff;
                z-index: 1000;
                padding: 10px;
                text-align: center;
                border-bottom: 2px solid #2980b9;
                font-size: 24px;
                font-weight: bold;
                color: #2c3e50;
                text-transform: uppercase;
                display: none;
                /* Initially hidden */
            }

            .hotspot-card {
                border: 1px solid #dfe6e9;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s;
                padding: 0em 0px 45px 0 !important;
            }

            .hotspot-card:hover {
                transform: scale(1.05);
            }

            .hotspot-card img {
                border-bottom: 1px solid #dfe6e9;
            }

            .hotspot-card-body {
                padding: 15px;
            }

            .hotspot-card-title {
                font-size: 18px;
                font-weight: bold;
                color: #34495e;
            }

            .hotspot-card-text {
                font-size: 14px;
                color: #7f8c8d;
            }

            .hotspot-card-footer {
                padding: 10px 15px;
                border-top: 1px solid #dfe6e9;
                background-color: #ecf0f1;
            }

            .hotspot-card-footer .btn {
                width: 100%;
            }

            .location-section {
                margin-bottom: 30px;
            }

            .hotspot-button-card {
                position: absolute;
                bottom: 15px;
            }

            .sticky-hotspot-element {
                position: sticky;
                top: -26px;
                z-index: 999;
                background-color: #fff;
            }
        </style>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card overflow-hidden" style="height: 650px;">
                    <div class="card-body ps ps--active-y" id="vertical-example" style="overflow-y: scroll !important;">
                        <div class="row" id="hotspotContainer">
                            <?php
                            // Fetch the hotspot data
                            $select_hotspot_details_data = sqlQUERY_LABEL("SELECT hp.`hotspot_ID`, hp.`hotspot_name`, hp.`hotspot_latitude`, hp.`hotspot_longitude`, hp.`hotspot_description`, hp.`hotspot_address`, hp.`hotspot_location`, hp.`hotspot_duration`, hp.`hotspot_video_url`, ht.`hotspot_start_time`, ht.`hotspot_end_time`, ht.`hotspot_closed`, ht.`hotspot_open_all_time` FROM `dvi_hotspot_place` hp LEFT JOIN `dvi_hotspot_timing` ht ON hp.`hotspot_ID` = ht.`hotspot_ID` WHERE hp.`deleted` = '0' AND hp.`status` = '1' AND ht.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ht.`status` = '1' AND ht.`deleted` = '0' AND ({$filter_location_name} hp.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY hp.`hotspot_ID` ORDER BY hp.`hotspot_location`, ht.`hotspot_start_time`") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                            $total_no_of_hotspots_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);
                            // Group the hotspots by normalized location
                            $hotspots_by_location = [];
                            while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)) :
                                $normalized_location = getGeneralLocation($fetch_hotspot_data['hotspot_location']);
                                $hotspots_by_location[$normalized_location][] = $fetch_hotspot_data;
                            endwhile;

                            if (!empty($hotspots_by_location) && $total_no_of_hotspots_count > 0) :
                                foreach ($hotspots_by_location as $location => $hotspots) :
                            ?>
                                    <div class="col-12 location-section sticky-hotspot-element" data-location="<?= $location; ?>">
                                        <h3 class="location-title"><?= $location; ?> <span class="fs-5">(<?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>)</span></h3>
                                    </div>
                                    <?php
                                    $index = 1; // Initialize the index for numerical indicator
                                    foreach ($hotspots as $fetch_hotspot_data) :
                                        $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                        $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                        $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                        $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                        $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                        $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                        $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                        $hotspot_video_url = $fetch_hotspot_data['hotspot_video_url'];
                                        $hotspot_gallery_name = getHOTSPOT_GALLERY_DETAILS($hotspot_ID, 'hotspot_gallery_name');

                                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                        $image_path = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                        $default_image = BASEPATH . 'uploads/no-photo.png';

                                        // Check if the image file exists
                                        $image_src = file_exists($image_already_exist) ? $image_path : $default_image;

                                        $hotspot_operating_hours = NULL;
                                        $allow_add = true; // Flag to allow adding hotspot

                                        $select_hotspot_timing_list_data = sqlQUERY_LABEL("SELECT `hotspot_timing_day`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID`='$hotspot_ID' AND `hotspot_timing_day`='$dayOfWeekNumeric' AND `status`='1' AND `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                        $total_hotspot_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_timing_list_data);

                                        if ($total_hotspot_num_rows_count > 0) :
                                            $add_onclick_attribute = 'onclick="add_ITINEARY_ROUTE_HOTSPOT(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $hotspot_ID . ')"';
                                            $remove_onclick_attribute = 'onclick="show_REMOVE_ITINEARY_ROUTE_HOTSPOT_MODAL(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $hotspot_ID . ')"';
                                            $add_onclick_disable_attribute = '';
                                        else :
                                            $add_onclick_attribute = 'onclick=javascript:void(0)';
                                            $remove_onclick_attribute = 'onclick="add_ITINEARY_ROUTE_HOTSPOT(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $hotspot_ID . ')"';
                                            $add_onclick_disable_attribute = 'disabled style="cursor: not-allowed;pointer-events: all;"';
                                        endif;

                                        if ($total_hotspot_num_rows_count > 0) :
                                            while ($fetch_hotspot_timing_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_data)) :
                                                $hotspot_timing_day = $fetch_hotspot_timing_data['hotspot_timing_day'];
                                                $hotspot_start_time = $fetch_hotspot_timing_data['hotspot_start_time'];
                                                $hotspot_end_time = $fetch_hotspot_timing_data['hotspot_end_time'];
                                                $hotspot_closed = $fetch_hotspot_timing_data['hotspot_closed'];
                                                $hotspot_open_all_time = $fetch_hotspot_timing_data['hotspot_open_all_time'];

                                                if ($hotspot_closed == 1) :
                                                    $hotspot_operating_hours = 'Closed, ';
                                                    $allow_add = false; // Do not allow adding if closed
                                                elseif ($hotspot_open_all_time == 1) :
                                                    $hotspot_operating_hours = 'Open 24 Hours, ';
                                                else :
                                                    $hotspot_operating_hours .= date('g:i A', strtotime($hotspot_start_time)) . ' - ' . date('g:i A', strtotime($hotspot_end_time)) . ', ';
                                                endif;

                                            endwhile;
                                        else :
                                            $hotspot_operating_hours = "Don't have any Opening Hours";
                                        endif;

                                    ?>
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                            <div class="card hotspot-card w-100">
                                                <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                    <div class="p-2 position-relative">
                                                        <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showHOTSPOTGALLERY('<?= $hotspot_ID; ?>');">
                                                            <img class="ti-tada-hover" src="assets/img/svg/image.svg" />
                                                        </div>
                                                        <?php if ($hotspot_video_url) : ?>
                                                            <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                <a href="<?= $hotspot_video_url; ?>" target="_blank"><img class="ti-tada-hover" src="assets/img/svg/video-player.svg"></a>
                                                            </div>
                                                        <?php endif; ?>
                                                        <img src="<?= $image_src; ?>" class="hotspot_image_container" alt="Hotspot Img" height="180" width="100%">
                                                        <?php if ($allow_add) : ?>
                                                            <div class="badge badge-primary position-absolute top-0 start-0 m-2"><?= $index; ?></div> <!-- Numerical indicator -->
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="card-body hotspot-card-body">
                                                        <div class="my-2 d-flex justify-content-between">
                                                            <h6 class="text-break hotspot-card-title mb-0 text-start"><?= $hotspot_name . ' (' . formatTimeDuration($hotspot_duration) . ')'; ?></h6>
                                                            <div data-toggle="tooltip" class="tooltip-container" data-bs-html="true" placement="top" title="<?= '<b>Hotspot Name:</b> ' . $hotspot_name . '<br /><br /> <b>Description:</b> ' . $hotspot_description . ' <br /><br /> <b>Location:</b> ' . $hotspot_location . '<br /><br /> <b>Address:</b> ' . $hotspot_address; ?>"><i class="ti ti-info-circle"></i></div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <i class="ti ti-clock me-1 mb-1"></i>
                                                            <p class="mb-0 hotspot-card-text"><?= $hotspot_operating_hours; ?></p>
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="w-100 px-3 hotspot-button-card">
                                                    <?php
                                                    $check_hotspot_already_added = get_ITINEARY_HOTSPOT_PLACES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $hotspot_ID, 'check_hotspot_already_existin_itineray_plan');

                                                    $select_itinerary_route_hotspot_list_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$hotspot_ID' and `itinerary_plan_ID`='$itinerary_plan_ID' and `itinerary_route_ID`='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_hotspot_list_query);
                                                    if ($allow_add) :
                                                        if ($check_hotspot_already_added > 0) :
                                                            if ($total_itinerary_route_hotspot_list_num_rows_count == 0) :
                                                    ?>
                                                                <button type="button" class="btn btn-primary waves-effect waves-light btn-sm" <?= $add_onclick_disable_attribute; ?> <?= $add_onclick_attribute; ?>>
                                                                    <span class="ti ti-circle-plus ti-xs me-1"></span> Visit Again
                                                                </button>
                                                            <?php else : ?>
                                                                <button type="button" class="btn btn-success waves-effect waves-light btn-sm" <?= $remove_onclick_attribute; ?>>
                                                                    <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                </button>
                                                            <?php endif;
                                                        else :
                                                            if ($total_itinerary_route_hotspot_list_num_rows_count == 0) :
                                                            ?>
                                                                <button type="button" class="btn btn-primary waves-effect waves-light btn-sm" <?= $add_onclick_disable_attribute; ?> <?= $add_onclick_attribute; ?>>
                                                                    <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                </button>
                                                            <?php else : ?>
                                                                <button type="button" class="btn btn-success waves-effect waves-light btn-sm" <?= $remove_onclick_attribute; ?>>
                                                                    <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                </button>
                                                        <?php endif;
                                                        endif;
                                                    else : ?>
                                                        <button type="button" class="btn btn-secondary waves-effect waves-light btn-sm" disabled style="cursor: not-allowed;pointer-events: all;">
                                                            <span class="ti ti-lock ti-xs me-1"></span> Closed
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        if ($allow_add) :
                                            $index++; // Increment the index for the next addable hotspot
                                        endif;
                                    endforeach; ?>
                                <?php endforeach;
                            else : ?>
                                <div class="col-md-12 col-sm-12">
                                    <div class="card overflow-hidden align-items-center d-flex" style="height: 300px; border:2px solid darkgrey;">
                                        <img src="assets/img/hotspot_not_found.jpg" width="200px" height="200px">
                                        <h4 class="text-primary">Hotspot Not Found !!!</h4>
                                        <p>Don't have any hotspot places against <b>"<?= $get_all_the_name; ?>"</b></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showHOTSPOTGALLERY(hotspot_ID) {
                $('.receiving-gallery-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_show_hotspot_gallery.php?type=show_form&hotspot_ID=' + hotspot_ID, function() {
                    const container = document.getElementById("GALLERYMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showHOTSPOTDISTANCECHECKERALERT(itinerary_plan_ID, itinerary_route_ID, hotspot_ID, alert_TYPE) {
                var group_type = '<?= $selected_group_type; ?>';
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=show_form&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_ID=' + hotspot_ID + "&alert_TYPE=" + alert_TYPE + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function add_ITINEARY_ROUTE_HOTSPOT(itinerary_plan_ID, itinerary_route_ID, hotspot_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=check_hotspot_distance_alert',
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        hotspot_ID: hotspot_ID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.something_went_wrong) {
                                TOAST_NOTIFICATION('warning', response.errors.something_went_wrong, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_distance_calculate_checker) {
                                /* let conflict_hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID; // Assuming this is an array
                                // Convert array to a comma-separated string
                                let conflict_hotspot_ID_string = conflict_hotspot_ID.join(','); */
                                showHOTSPOTDISTANCECHECKERALERT(itinerary_plan_ID, itinerary_route_ID, hotspot_ID, response.errors.hotspot_distance_alert_type);
                            } else if (response.errors.exceeds_route_end_time) {
                                TOAST_NOTIFICATION('warning', response.errors.exceeds_route_end_time, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                /* TOAST_NOTIFICATION('warning', response.errors.hotspot_operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', ''); */
                                let conflict_hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID;
                                let dayOfWeekNumeric = response.errors.hotspot_operating_hours_not_available_dayOfWeekNumeric;
                                let itinerary_plan_ID = response.errors.hotspot_operating_hours_not_available_itinerary_plan_ID;
                                let itinerary_route_ID = response.errors.hotspot_operating_hours_not_available_itinerary_route_ID;
                                let new_hotspot_ID = response.errors.try_to_add_new_hotspot_ID;
                                replaceHOTSPOTS(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID);
                            }
                        } else {
                            // SUCCESS RESPONSE
                            TOAST_NOTIFICATION('success', 'Successfully Hotspot Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                setTimeout(function() {
                                    getVEHICLEPLANDETAILS(itinerary_plan_ID);
                                }, 1000); // 30 seconds in milliseconds

                                setTimeout(function() {
                                    showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $selected_group_type; ?>');
                                    showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID);
                                }, 300); // 30 seconds in milliseconds
                            <?php else: ?>
                                showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $selected_group_type; ?>');
                            <?php endif; ?>
                            $('#close_hotspot_distance_alert_modal').click();
                            $('.btn-close').click();
                            $('#addhotspot_' + itinerary_route_ID).addClass('d-none');
                            $('#closehotspot_' + itinerary_route_ID).removeClass('d-none');
                        }
                    }
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

            function replaceHOTSPOTS(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, new_hotspot_ID) {
                var group_type = '<?= $selected_group_type; ?>';
                $('#MODALINFODATA').modal('hide');
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=confirm_replace_hotspots&conflict_hotspot_ID=' + conflict_hotspot_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&new_hotspot_ID=' + new_hotspot_ID + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showITINEARY_ROUTE_HOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID) {
                var group_type = '<?= $selected_group_type; ?>';
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_show_hotspot_list.php?type=show_form",
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        group_type: group_type
                    },
                    success: function(response) {
                        $('#show_list_of_hotspots_' + itinerary_route_ID).html(response)
                    }
                });
            }

            function showDAYWISEHOTSPOT_DETAILS(routeID, planID, group_type) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_step2_form.php?type=show_form&selected_group_type=" + group_type,
                    data: {
                        _ID: planID,
                        routeID: routeID,
                    },
                    success: function(response) {
                        $('#showITINEARYSTEP1').html('')
                        $('#showITINEARYSTEP2').html(response);
                    }
                });
            }

            function show_REMOVE_ITINEARY_ROUTE_HOTSPOT_MODAL(itinerary_plan_ID, itinerary_route_ID, hotspot_ID) {
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=delete_itineary_hotspot&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_ID=' + hotspot_ID, function() {
                    const container = document.getElementById("MODALINFODATA");
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
<?php endif;
else :
    echo "Request Ignored";
endif;
?>