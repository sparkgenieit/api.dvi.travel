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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'show_form') :

        $route_hotspot_ID = $_GET['route_hotspot_ID'];
        $hotspot_ID = $_GET['hotspot_ID'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
        $itinerary_route_ID = $_GET['itinerary_route_ID'];
        $GROUP_TYPE = $_GET['GROUP_TYPE'];

        $get_hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
        $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');

        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
            $total_adult = $fetch_itinerary_plan_data['total_adult'];
            $total_children = $fetch_itinerary_plan_data['total_children'];
            $total_infants = $fetch_itinerary_plan_data['total_infants'];
        endwhile;
?>
        <div class="col-md-12 col-sm-12">
            <div class="card overflow-hidden" style="height: 650px;">
                <div class="card-body ps ps--active-y" id="vertical-example" style="overflow-y: scroll !important;">
                    <div class="row">
                        <?php
                        $select_hotspot_activity_details_data = sqlQUERY_LABEL("SELECT `activity_id`, `activity_title`,`max_allowed_person_count`, `activity_duration`, `activity_description` FROM `dvi_activity` WHERE `deleted` = '0' and `status` = '1' and `hotspot_id` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                        $total_hotspot_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_activity_details_data);
                        if ($total_hotspot_activity_list_num_rows_count > 0) :
                            while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_hotspot_activity_details_data)) :
                                $activity_id = $fetch_hotspot_activity_data['activity_id'];
                                $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                $max_allowed_person_count = $fetch_hotspot_activity_data['max_allowed_person_count'];
                                $activity_duration = $fetch_hotspot_activity_data['activity_duration'];
                                $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_id, 'get_first_activity_image_gallery_name');

                                $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/activity_gallery/' . $get_first_activity_image_gallery_name;
                                $image_path = BASEPATH . 'uploads/activity_gallery/' . $get_first_activity_image_gallery_name;
                                $default_image = BASEPATH . 'uploads/no-photo.png';

                                // Check if the image file exists
                                $image_src = file_exists($image_already_exist) ? $image_path : $default_image;

                                $activity_operating_hours = NULL;
                                $total_activity_changes = NULL;
                                $select_activity_timing_list_data = sqlQUERY_LABEL("SELECT `activity_time_slot_ID`, `time_slot_type`, `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status`='1' AND `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_ACTIVITY_TIMING_LIST:" . sqlERROR_LABEL());
                                $total_activity_num_rows_count = sqlNUMOFROW_LABEL($select_activity_timing_list_data);

                                if ($total_activity_num_rows_count > 0) :
                                    $add_onclick_attribute = 'onclick="add_ITINEARY_ROUTE_HOTSPOT_ACTIVITY(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $route_hotspot_ID . ', ' . $hotspot_ID . ', ' . $activity_id . ')"';
                                    $remove_onclick_attribute = 'onclick="show_REMOVE_ITINEARY_ROUTE_HOTSPOT_ACTIVITY_MODAL(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $route_hotspot_ID . ', ' . $hotspot_ID . ', ' . $activity_id . ')"';
                                    $add_onclick_disable_attribute = '';
                                else :
                                    $add_onclick_attribute = 'onclick=javascript:void(0)';
                                    $remove_onclick_attribute = 'onclick="add_ITINEARY_ROUTE_HOTSPOT_ACTIVITY(' . $itinerary_plan_ID . ', ' . $itinerary_route_ID . ', ' . $route_hotspot_ID . ', ' . $hotspot_ID . ', ' . $activity_id . ')"';
                                    $add_onclick_disable_attribute = 'disabled style="cursor: not-allowed;pointer-events: all;"';
                                endif;

                                if ($total_activity_num_rows_count > 0) :
                                    while ($fetch_activity_timing_data = sqlFETCHARRAY_LABEL($select_activity_timing_list_data)) :
                                        $activity_time_slot_ID = $fetch_activity_timing_data['activity_time_slot_ID'];
                                        $time_slot_type = $fetch_activity_timing_data['time_slot_type'];
                                        $special_date = $fetch_activity_timing_data['special_date'];
                                        $start_time = $fetch_activity_timing_data['start_time'];
                                        $end_time = $fetch_activity_timing_data['end_time'];;

                                        if ($special_date == $itinerary_route_date && $time_slot_type == 2) :
                                            $activity_operating_hours .= date('g:i A', strtotime($start_time)) . ' - ' . date('g:i A', strtotime($end_time)) . ', ';
                                        else :
                                            $activity_operating_hours .= date('g:i A', strtotime($start_time)) . ' - ' . date('g:i A', strtotime($end_time)) . ', ';
                                        endif;

                                        $get_activity_charges_for_adult = get_ITINEARY_HOTSPOT_ACTIVITY_COST_DETAILS($activity_id, $itinerary_route_date, 'get_activity_charges_for_adult');
                                        $get_activity_charges_for_children = get_ITINEARY_HOTSPOT_ACTIVITY_COST_DETAILS($activity_id, $itinerary_route_date, 'get_activity_charges_for_children');
                                        $get_activity_charges_for_infant = get_ITINEARY_HOTSPOT_ACTIVITY_COST_DETAILS($activity_id, $itinerary_route_date, 'get_activity_charges_for_infant');

                                        $total_adult_activity_changes = $total_adult * $get_activity_charges_for_adult;
                                        $total_children_activity_changes = $total_children * $get_activity_charges_for_children;
                                        $total_infant_activity_changes = $total_infants * $get_activity_charges_for_infant;

                                        $total_activity_changes = $total_adult_activity_changes + $total_children_activity_changes + $total_infant_activity_changes;
                                    endwhile;
                                else :
                                    $activity_operating_hours = "Don't have any Opening Hours";
                                endif;
                        ?>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                    <div class="card w-100">
                                        <label class="form-check-label custom-option-content p-0" for="">
                                            <div class="p-2 position-relative">
                                                <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showHOTSPOTACTIVITYGALLERY('<?= $activity_id; ?>');">
                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                </div>
                                                <img src="<?= $image_src; ?>" class="hotspot_image_container" alt="Hotspot Img" height="180" width="100%">
                                            </div>
                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                    <h6 class="custom-option-title mb-0 text-start"><?= $activity_title; ?></h6>
                                                    <div data-toggle="tooltip" class="tooltip-container" data-bs-html="true" placement="top" title="<?= '<b>Description:</b> ' . $activity_description; ?>">
                                                        <i class="ti ti-info-circle"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                    <p class="mb-0">
                                                        <?= $activity_operating_hours; ?>
                                                    </p>
                                                </div>
                                                <div class="d-flex mt-3">
                                                    <?php if ($get_activity_charges_for_adult > 0) : ?>
                                                        <p class="mb-0">
                                                            Adult - <?= general_currency_symbol . ' ' . number_format($get_activity_charges_for_adult, 2); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex">
                                                    <?php if ($get_activity_charges_for_children > 0) : ?>
                                                        <p class="mb-0">
                                                            Children - <?= general_currency_symbol . ' ' . number_format($get_activity_charges_for_children, 2); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex">
                                                    <?php if ($get_activity_charges_for_infant > 0) : ?>
                                                        <p class="mb-0">
                                                            Infant - <?= general_currency_symbol . ' ' . number_format($get_activity_charges_for_infant, 2); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($total_activity_changes > 0) : ?>
                                                    <div class="d-flex mt-3">
                                                        <p class="mb-0">
                                                            Total Charges - <?= general_currency_symbol . ' ' . number_format($total_activity_changes, 2); ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                        <?php
                                        $check_hotspot_activity_already_added = get_ITINEARY_HOTSPOT_PLACES_ACTIVITY_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $hotspot_ID, $activity_id, 'check_hotspot_activity_already_existin_itineray_plan');

                                        $select_itinerary_route_hotspot_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID` FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `hotspot_ID`='$hotspot_ID' and `itinerary_plan_ID`='$itinerary_plan_ID' and `itinerary_route_ID`='$itinerary_route_ID' AND `activity_ID` = '$activity_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                        $total_itinerary_route_hotspot_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_hotspot_activity_list_query);
                                        if ($check_hotspot_activity_already_added > 0) :
                                            if ($total_itinerary_route_hotspot_activity_list_num_rows_count == 0) :
                                        ?>
                                                <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" <?= $add_onclick_disable_attribute; ?> <?= $add_onclick_attribute; ?>>
                                                    <span class="ti ti-circle-plus ti-xs me-1"></span> Visit Again
                                                </button>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" <?= $remove_onclick_attribute; ?>>
                                                    <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                </button>
                                            <?php endif; ?>
                                            <?php else :
                                            if ($total_itinerary_route_hotspot_activity_list_num_rows_count == 0) : ?>
                                                <?php if ($total_activity_changes > 0) : ?>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" <?= $add_onclick_disable_attribute; ?> <?= $add_onclick_attribute; ?>>
                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                    </button>
                                                <?php else : ?>
                                                    <button type="button" class="btn btn-outline-danger mx-auto waves-effect waves-light btn-sm hotspot_item_footer">Don't have a Price Chart</button>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" <?= $remove_onclick_attribute; ?>>
                                                    <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile;
                        else : ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="card overflow-hidden align-items-center d-flex" style="height: 300px; border:2px solid darkgrey;">
                                    <img src="assets/img/activity_not_found.jpg" width="200px" height="200px" />
                                    <h4 class="text-primary">Activity Not found !!!</h4>
                                    <p>Don't have any hotspot activity against <b>"<?= $get_hotspot_name; ?>"</b></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function showHOTSPOTACTIVITYGALLERY(activity_ID) {
                $('.receiving-gallery-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_show_hotspot_activity_gallery.php?type=show_form&activity_ID=' + activity_ID, function() {
                    const container = document.getElementById("GALLERYMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function add_ITINEARY_ROUTE_HOTSPOT_ACTIVITY(itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_ID, activity_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=add_itineary_route_hotspot_activity',
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        route_hotspot_ID: route_hotspot_ID,
                        hotspot_ID: hotspot_ID,
                        activity_ID: activity_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.operating_hours_not_available) {
                                TOAST_NOTIFICATION('warning', response.errors.operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_operating_hours_not_available) {
                                /* TOAST_NOTIFICATION('warning', response.errors.operating_hours_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', ''); */
                                let conflict_hotspot_ID = response.errors.hotspot_operating_hours_not_available_hotspot_ID;
                                let dayOfWeekNumeric = response.errors.hotspot_operating_hours_not_available_dayOfWeekNumeric;
                                let itinerary_plan_ID = response.errors.hotspot_operating_hours_not_available_itinerary_plan_ID;
                                let itinerary_route_ID = response.errors.hotspot_operating_hours_not_available_itinerary_route_ID;
                                let itinerary_route_hotspot_ID = response.errors.itinerary_route_hotspot_ID;
                                let itinerary_hotspot_ID = response.errors.itinerary_hotspot_ID;
                                let try_to_add_new_activity_ID = response.errors.try_to_add_new_activity_ID;
                                replaceHOTSPOTS_WITH_ACTIVITY(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, itinerary_route_hotspot_ID, itinerary_hotspot_ID, try_to_add_new_activity_ID);
                            } else if (response.errors.activity_already_exist) {
                                TOAST_NOTIFICATION('warning', response.errors.activity_already_exist, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.route_time_limit_exceeded) {
                                TOAST_NOTIFICATION('warning', response.errors.route_time_limit_exceeded, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPONSE
                            TOAST_NOTIFICATION('success', 'Successfully Activity Added !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#hotspot_ACTIVITYMODAL').modal('hide');
                            showDAYWISEHOTSPOT_DETAILS(itinerary_route_ID, itinerary_plan_ID, '<?= $GROUP_TYPE; ?>');
                            $('#addactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).addClass('d-none');
                            $('#closeactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).removeClass('d-none');
                        }
                    }
                });
            }

            function replaceHOTSPOTS_WITH_ACTIVITY(conflict_hotspot_ID, dayOfWeekNumeric, itinerary_plan_ID, itinerary_route_ID, itinerary_route_hotspot_ID, itinerary_hotspot_ID, try_to_add_new_activity_ID) {
                var group_type = '<?= $GROUP_TYPE; ?>';
                $('#MODALINFODATA').modal('hide');
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotspot_distance_alert.php?type=confirm_replace_hotspots_with_activity&conflict_hotspot_ID=' + conflict_hotspot_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&itinerary_route_hotspot_ID=' + itinerary_route_hotspot_ID + '&itinerary_hotspot_ID=' + itinerary_hotspot_ID + '&try_to_add_new_activity_ID=' + try_to_add_new_activity_ID + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function show_REMOVE_ITINEARY_ROUTE_HOTSPOT_ACTIVITY_MODAL(itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_ID, activity_ID) {
                var group_type = '<?= $selected_group_type; ?>';
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=delete_itineary_hotspot&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_ID=' + hotspot_ID + '&route_activity_ID=' + route_activity_ID + '&activity_ID=' + activity_ID + '&GROUP_TYPE=' + group_type, function() {
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

            function showITINEARY_ROUTE_HOTSPOT_ACTIVITY_LIST(route_hotspot_ID, hotspot_ID, itinerary_plan_ID, itinerary_route_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_show_hotspot_activity_list.php?type=show_form",
                    data: {
                        route_hotspot_ID: route_hotspot_ID,
                        hotspot_ID: hotspot_ID,
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: itinerary_plan_ID
                    },
                    success: function(response) {
                        $('#show_list_of_hotspots_activity_' + hotspot_ID + '_' + itinerary_route_ID).html(response)
                    }
                });
            }
        </script>
<?php
    endif;

else :
    echo "Request Ignored";
endif;
