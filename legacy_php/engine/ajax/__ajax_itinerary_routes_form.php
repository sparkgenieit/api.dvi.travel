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

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`,`itinerary_preference` ,`arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $arrival_location = $fetch_list_data['arrival_location'];
                $departure_location = $fetch_list_data['departure_location'];
                //$trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                //$trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                $trip_start_date = date('d-m-Y', strtotime($fetch_list_data['trip_start_date_and_time']));
                $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));

                $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
                $trip_end_date = date('d-m-Y', strtotime($fetch_list_data['trip_end_date_and_time']));
                $expecting_budget = $fetch_list_data['expecting_budget'];
                $no_of_routes = $fetch_list_data['no_of_routes'];
                $total_no_of_days = $fetch_list_data["no_of_days"];
                $total_no_of_nights = $fetch_list_data['no_of_nights'];
                $itinerary_preference =  $fetch_list_data['itinerary_preference'];
            endwhile;
            $btn_label = 'Update & Continue';
        else :
            $btn_label = 'Save & Continue';
        endif;
        //FETCH GLOBAL SETTINGS DETAILS
        $select_global_settings = sqlQUERY_LABEL("SELECT `global_settings_ID`, `itinerary_distance_limit`, `itinerary_travel_by_flight_buffer_time`, `itinerary_travel_by_train_buffer_time`, `itinerary_travel_by_road_buffer_time` FROM `dvi_global_settings` WHERE `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_global_settings) > 0) :
            while ($fetch_settings_data = sqlFETCHARRAY_LABEL($select_global_settings)) :
                $itinerary_distance_limit = $fetch_settings_data['itinerary_distance_limit'];
                $itinerary_travel_by_flight_buffer_time = $fetch_settings_data['itinerary_travel_by_flight_buffer_time'];
                $itinerary_travel_by_train_buffer_time = $fetch_settings_data['itinerary_travel_by_train_buffer_time'];
                $itinerary_travel_by_road_buffer_time = $fetch_settings_data['itinerary_travel_by_road_buffer_time'];
            endwhile;
        endif;


        $select_itineary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`,`location_id`, `location_name`,  `no_of_days`, `no_of_km`, `location_via_route`, `itinerary_route_date`,`direct_to_next_visiting_place`,`next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_num_of_route_details = sqlNUMOFROW_LABEL($select_itineary_route_details);
?>

        <div id="se-pre-con"></div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h5 class="card-header text-capitalize">Trip <b><?= $arrival_location; ?></b> to <b><?= $departure_location; ?></b> [<b><?= $total_no_of_nights . ' Nights'; ?></b>, <b><?= $total_no_of_days . ' Days'; ?>]</h5>
                    <a href="newitinerary.php?route=edit&formtype=basic_info&id=<?= $itinerary_plan_ID ?>" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Edit Plan</a>
                </div>


                <?php if ($total_num_of_route_details > 0) : ?>
                    <div class="col-md-6 col-12 mb-md-0 mb-6">
                        <form id="itinerary_routes_form" method="post" action="" data-parsley-validate>
                            <?php for ($i = 1; $i <= $no_of_routes; $i++) : ?>

                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <p>Route <?= $i; ?></p>
                                                    <input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon1" />
                                                </div>
                                                <ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_<?= $i; ?>">
                                                    <?php
                                                    while ($fetch_itinerary_data = sqlFETCHARRAY_LABEL($select_itineary_route_details)) :
                                                        $itinerary_route_count++;
                                                        $itinerary_route_ID = $fetch_itinerary_data['itinerary_route_ID'];
                                                        $itinerary_plan_ID = $fetch_itinerary_data['itinerary_plan_ID'];
                                                        $location_id = $fetch_itinerary_data['location_id'];
                                                        $location_name = $fetch_itinerary_data['location_name'];

                                                        $no_of_days = $fetch_itinerary_data['no_of_days'];
                                                        $no_of_km = $fetch_itinerary_data['no_of_km'];
                                                        $location_via_route = $fetch_itinerary_data['location_via_route'];

                                                        $direct_to_next_visiting_place = $fetch_itinerary_data['direct_to_next_visiting_place'];
                                                        $next_visiting_location = $fetch_itinerary_data['next_visiting_location'];

                                                        if ($departure_location == $location_name) :
                                                            $show_route_border = 'border-start';
                                                        else :
                                                            $show_route_border = 'border-start';
                                                        endif;
                                                        $itinerary_route_date = $fetch_itinerary_data['itinerary_route_date'];
                                                    ?>
                                                        <span id="show_location_<?= $itinerary_route_ID ?>">
                                                            <li class="list-group-item drag-item d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 <?= $show_route_border; ?> p-0">
                                                                <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                    <i class="ti ti-map-pin"></i>
                                                                </span>
                                                                <div class="itinerary_timeline_event route timeline-event ps-0 pb-0 px-0">
                                                                    <?php //if (($arrival_location == $location_name) || ($departure_location == $location_name)) :
                                                                    if ($arrival_location == $location_name) :
                                                                    ?>
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">
                                                                                    <span class="day_heading fw-bold">DAY <?= $itinerary_route_count . ' - ' . dateformat_datepicker($itinerary_route_date); ?></span>
                                                                                </h6>

                                                                                <h6 class="mb-1 text-capitalize"><?= $location_name  ?></h6>

                                                                                <input type="hidden" name="hidden_itinerary_route_ID" value="<?= $itinerary_route_ID; ?>" class="itinerary_route_ID">
                                                                                <input type="hidden" name="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>">
                                                                                <input type="hidden" name="hidden_location_id" value="<?= $location_id; ?>">
                                                                                <input type=" hidden" class="form-control form-control-sm" id="location_name_<?= $itinerary_route_count ?>" name="location_name[]" hidden value="<?= $location_name; ?>">

                                                                                <?php //if ($departure_location != $location_name) :
                                                                                ?>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Via Route</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list" id="show_via_route_input_<?= $itinerary_route_count; ?>">
                                                                                            <input type="text" class="form-control form-control-sm " placeholder="Search via route" name="via_route[]" id="via_route_<?= $itinerary_route_count; ?>" value="<?= $location_via_route; ?>" readonly style="cursor: no-drop;" />

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php // endif; 
                                                                                ?>
                                                                            </div>
                                                                            <div>
                                                                                <?php
                                                                                if ($arrival_location != $location_name && $trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                                ?>
                                                                                    <br> <button type="button" data-route-id="<?php echo    $itinerary_route_ID; ?>" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled btn_delete_location text-danger" data-id="<?= $itinerary_route_count; ?>">
                                                                                        </i>
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                                <?php
                                                                                if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                                ?>
                                                                                    <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect btn_add_more" data-id="<?= $itinerary_route_count; ?>"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php else : ?>

                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start col-md-9">
                                                                                <h6 class="mb-1 text-capitalize"><span class="day_heading fw-bold">DAY <?= $itinerary_route_count . ' - ' . dateformat_datepicker($itinerary_route_date); ?></span></h6>

                                                                                <input type="text" class="form-control mb-2" placeholder="Search Location Name" required name="location_name[]" id="location_name_<?= $itinerary_route_count ?>" value="<?= $location_name  ?>">

                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Via Route</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list" id="show_via_route_input_<?= $itinerary_route_count; ?>">
                                                                                            <input type="text" class="form-control form-control-sm " placeholder="Search via route" id="via_route_<?= $itinerary_route_count; ?>" name="via_route[]" value="<?= $location_via_route; ?>" readonly style="cursor: no-drop;" />

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <?php
                                                                                if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                                ?>
                                                                                    <button type="button" data-id="<?= $itinerary_route_count ?>" class="btn btn-sm btn-icon btn-label-primary waves-effect btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </li>
                                                            <?php
                                                            //if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) : 
                                                            ?>
                                                            <li class="list-group-item drag-item d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 pb-4 border-left-dashed border-0 p-0 <?= ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) ? "border-start" : ""; ?>" id="show_next_location_input_<?= $itinerary_route_count; ?>">
                                                                <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <circle cx="5.5" cy="5.5" r="2.5" fill="#29C770" />
                                                                        <circle cx="5.5" cy="5.5" r="4" stroke="#29C770" />
                                                                    </svg>
                                                                </span>
                                                                <div class="float-start col-md-12">
                                                                    <div class="mb-2 text-start d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <?php
                                                                            if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                            ?>
                                                                                <small class="text-light">Next Visiting place</small>
                                                                            <?php else :  ?>
                                                                                <small class="text-light">Destination</small>
                                                                            <?php endif;
                                                                            ?>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input w-px-16 h-px-16" type="checkbox" style="margin-top: 2px !important;" id="direct_to_next_visiting_place<?= $itinerary_route_count - 1; ?>" name="direct_to_next_visiting_place[<?= $itinerary_route_count - 1; ?>]" <?= ($direct_to_next_visiting_place == 1) ? "checked " : " " ?> />
                                                                            <label class="form-check-label" for="direct_to_next_visiting_place<?= $itinerary_route_count - 1; ?>" style="font-weight: 400;">
                                                                                <?php
                                                                                if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                                ?>
                                                                                    <small>Direct Transit to Next Visiting Place</small>
                                                                                <?php else :  ?>
                                                                                    <small>Direct Transit to Destination</small>
                                                                                <?php endif;
                                                                                ?>


                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <span class="d-flex justify-content-between align-items-center">

                                                                            <?php
                                                                            if ($trip_end_date != date('d-m-Y', strtotime($itinerary_route_date))) :
                                                                            ?>
                                                                                <input type="text" class="form-control form-control-sm mb-2" placeholder="Search Visiting Place" name="next_visiting_place[]" value="<?= $next_visiting_location ?>" id="next_visiting_place_<?= $itinerary_route_count ?>" required />
                                                                            <?php else :  ?>
                                                                                <h6 class="mb-1 text-capitalize"><?= $location_name  ?></h6>

                                                                                <input type="hidden" class="form-control form-control-sm mb-2" placeholder="Search Visiting Place" name="next_visiting_place[]" value="<?= $location_name ?>" id="next_visiting_place_<?= $itinerary_route_count ?>" required />
                                                                            <?php endif;
                                                                            ?>

                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php //endif; 
                                                            ?>
                                                        </span>
                                                        <span class="show_added_routes" id="show_added_routes_<?= $itinerary_route_count; ?>"></span>
                                                    <?php
                                                    endwhile; ?>
                                                </ul>
                                                <button type="submit" class="btn btn-outline-dribbble waves-effect d-none">
                                                    <span class="ti-xs ti ti-circle-plus me-1"></span>Update Itinerary
                                                </button>
                                            </div>
                                        </div>
                                    </label>
                                </div>


                            <?php endfor; ?>

                            <div class="text-center my-4">
                                <?php if ($no_of_routes > 1) :
                                    if ($total_num_of_route_details > 0) :
                                        $btn_label_name = 'Update';
                                    else :
                                        $btn_label_name = 'Create';
                                    endif;
                                ?>
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <span class="ti-xs ti ti-circle-plus me-1"></span><?= $btn_label_name; ?> All Itinerary
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="col-md-6 col-12 mb-md-0 mb-6 pb-4">
                    <div id="map" class="rounded" style="height: 100%;width: 100%;"></div>
                </div>
                <?php if ($total_num_of_route_details > 0) : ?>
                    <div class="row">
                        <div class="d-flex justify-content-center">
                            <button type="button" onclick="generate_ITINERARY()" class="btn btn-outline-dribbble waves-effect">
                                Update & Generate Itinerary
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>

        <!--  DELETE LOCATION MODAL -->
        <div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body receiving-confirm-delete-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmDISTANCEEXCEEDSINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">

                            <div class="text-center">
                                <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>

                            <p class="text-center">Total Distance is exceeded !!! </p>

                            <p class="text-center">The total distance should not be exceeded more than <?= $itinerary_distance_limit ?> KM . <br /> </p>
                            <div class="text-center pb-0">
                                <button type="button" class="btn btn-secondary close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">Close</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $(document).ready(function() {

                $(document).on('click', '.input_plus_button', function(e) {
                    var total_no_of_days = 0;

                    $('.input_plus_minus').each(function() {
                        no_of_days = parseInt($(this).val());
                        total_no_of_days += no_of_days;
                    });

                    var quantityField = $(this).siblings('.quantity-field');
                    var parentElement = $(this).closest('.text-start');
                    var itineraryRouteID = parentElement.find('.itinerary_route_ID').val();

                    if (itineraryRouteID !== "") {
                        var currentValue = parseInt(quantityField.val());

                        if (total_no_of_days < <?= $total_no_of_nights ?>) {
                            quantityField.val(currentValue + 1);
                            // confirmALTERDAYS('<?= $itinerary_plan_ID ?>', itineraryRouteID, (currentValue + 1), 'UPDATE_ROUTE');
                        } else {
                            updated_days = total_no_of_days + 1;

                            $('.receiving-confirm-alter-day-form-data').load('engine/ajax/__ajax_alter_itinerary_route_days.php?type=show_modal&ID=' + '<?= $itinerary_plan_ID ?>' + '&total_no_of_days=' + updated_days + '&itineraryRouteID=' + itineraryRouteID, function() {
                                const container = document.getElementById("confirmALTERDAYINFODATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }
                    } else {
                        var currentValue = parseInt(quantityField.val());

                        if (total_no_of_days < <?= $total_no_of_nights ?>) {
                            quantityField.val(currentValue + 1);
                        } else {
                            $('.receiving-confirm-alter-day-form-data').load('engine/ajax/__ajax_alter_itinerary_route_days.php?type=show_modal&ID=' + '<?= $itinerary_plan_ID ?>', function() {
                                const container = document.getElementById("confirmALTERDAYINFODATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }
                    }
                });

                $('.input_minus_button').click(function(e) {
                    var quantityField = $(this).siblings('.quantity-field');
                    var currentValue = parseInt(quantityField.val());

                    if (currentValue > 0) {
                        quantityField.val(currentValue - 1);
                    }
                });

                route_count = parseInt('<?= $itinerary_route_count ?>');

                for (i = 1; i <= route_count; i++) {
                    (function(index) {
                        var location_name = $("#location_name_" + index).val();

                        if (location_name != "") {
                            var next_visiting_place = {
                                url: function(phrase) {
                                    return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                            phrase) +
                                        "&format=json&type=destination&source_location=" + location_name;
                                },
                                getValue: "get_destination_location",
                                list: {
                                    onChooseEvent: function() {
                                        set_next_location_name(index);
                                    },
                                    match: {
                                        enabled: true
                                    },
                                    hideOnEmptyPhrase: true
                                },
                                theme: "square"
                            };
                            $("#next_visiting_place_" + index).easyAutocomplete(next_visiting_place);
                            var next_visiting_place_name = $("#next_visiting_place_" + index).val();
                            if (location_name != "" && next_visiting_place_name != "") {

                                //SET VIA ROUTE
                                var via_route = {
                                    url: function(phrase) {
                                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                                phrase) +
                                            "&format=json&type=via_route&source_location=" + location_name + "&destination_location=" + next_visiting_place_name;
                                    },
                                    getValue: "get_via_route_location",
                                    list: {
                                        onChooseEvent: function() {
                                            //set_next_location_name(index);
                                        },
                                        match: {
                                            enabled: true
                                        },
                                        hideOnEmptyPhrase: true
                                    },
                                    theme: "square"
                                };
                                $("#via_route_" + index).easyAutocomplete(via_route);
                                $("#via_route_" + index).removeAttr('readonly');
                                $("#via_route_" + index).removeAttr("style");
                            }

                            next_search_loc_index = parseInt(index) + 1;
                            //alert(next_search_loc_index);
                            $("#location_name_" + next_search_loc_index).easyAutocomplete(next_visiting_place);

                        }
                    })(i);
                }


                <?php if ($total_num_of_route_details == 0) : ?>
                <?php else : ?>

                    function addLocation(count, parentDiv) {

                        new_count = parseInt('<?= $itinerary_route_count ?>') + 1;
                        day_no = parseInt(count) + 1;
                        //alert(day_no);
                        var newLocationHtml = `<span class="new_location_"` + new_count + `>
                        <li class="list-group-item drag-item d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                            <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                <i class="ti ti-map-pin"></i>
                            </span>
                            <div class="itinerary_timeline_event route timeline-event ps-0 pb-0 px-0">
                                <div class="d-flex justify-content-between">
                                    <div class="text-start col-md-9">
                                        <h6 class="mb-1 text-capitalize"><span class="day_heading fw-bold">DAY ` + day_no + `</span></h6>
                                        <input type="text" id="location_name_` + day_no + `" class="form-control mb-2" placeholder="Search Location Name" required name="location_name[]" >
                                       
                                        <div class="mb-1 row">
                                            <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Via Route</small>
                                            <div class="col-auto px-0">
                                                <div class="input-group input_group_plus_minus input_itinerary_list">
                                                    <input type="text" class="form-control form-control-sm" placeholder="Search via route" id="via_route_` + day_no + `"  name="via_route[]" readonly style="cursor: no-drop;"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect btn_delete_location" data-route-id="" data-id="` + day_no + `"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                        <button type="button" data-id="` + day_no + `" class="btn btn-sm btn-icon btn-label-primary waves-effect btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li id="show_via_route_text_` + day_no + `"  class="list-group-item drag-item d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 pb-4 border-left-dashed border-0 border-start p-0">
                            <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5.5" cy="5.5" r="2.5" fill="#29C770" />
                                    <circle cx="5.5" cy="5.5" r="4" stroke="#29C770" />
                                </svg>
                            </span>
                            <div class="float-start col-md-12">								
								<div class="mb-2 text-start d-flex justify-content-between align-items-center">
									<div>
										<small class="text-light">Next Visiting place</small>
									</div>
									<div class="form-check">
										<input class="form-check-input w-px-16 h-px-16" type="checkbox" id="direct_to_next_visiting_place` + day_no + `" style="margin-top: 2px !important;" name="direct_to_next_visiting_place[` + day_no + `]">
										<label class="form-check-label" for="direct_to_next_visiting_place` + day_no + `" style="font-weight: 400;">
											<small>Direct to Next Visiting Place</small>
										</label>
									</div>
								</div>
                                <div>
                                    <span class="d-flex justify-content-between align-items-center">
                                        <input type="text" class="form-control form-control-sm" placeholder="Search Next Visiting Place" id="next_visiting_place_` + day_no + `" name="next_visiting_place[]"  required/>
                                       
                                    </span>
                                </div>
                            </div>
                        </li></span>
                         <span class="show_added_routes" id="show_added_routes_` + day_no + `"></span>
                    `;

                        // Append the new location HTML to the form
                        $('#show_added_routes_' + count).append(newLocationHtml);

                        prev_day_no = parseInt(day_no) - 1;

                        var prev_visiting_place_name = $("#next_visiting_place_" + prev_day_no).val();

                        $("#location_name_" + day_no).val(prev_visiting_place_name);

                        //var location_name = $("#location_name_" + day_no).val();

                        var next_visiting_place = {
                            url: function(phrase) {
                                return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                        phrase) +
                                    "&format=json&type=destination&source_location=" + prev_visiting_place_name;
                            },
                            getValue: "get_destination_location",
                            list: {
                                onChooseEvent: function() {
                                    set_next_location_name(day_no);
                                },
                                match: {
                                    enabled: true
                                },
                                hideOnEmptyPhrase: true
                            },
                            theme: "square"
                        };

                        $("#next_visiting_place_" + day_no).easyAutocomplete(next_visiting_place);
                        next_search_loc_index = parseInt(day_no) + 1;

                        $("#location_name_" + next_search_loc_index).easyAutocomplete(next_visiting_place);

                        //NEXT_VISIT_PLACE
                        NEXT_VISIT_PLACE_COUNT = 0;
                        $('input[name^="next_visiting_place"]').each(function() {
                            if ($(this).attr('type') === 'text') {
                                NEXT_VISIT_PLACE_COUNT++;
                            }
                            $(this).attr('id', "next_visiting_place_" + NEXT_VISIT_PLACE_COUNT);
                        });

                        LOCATION_COUNT = 0;
                        $('input[name^="location_name"]').each(function() {
                            LOCATION_COUNT++;
                            $(this).attr('id', "location_name_" + LOCATION_COUNT);
                        });

                        //VIA ROUTE
                        VIA_ROUTE_COUNT = 0;
                        $('input[name^="via_route"]').each(function() {
                            VIA_ROUTE_COUNT++;
                            $(this).attr('id', "via_route_" + VIA_ROUTE_COUNT);
                        });

                        //ADD MORE BUTTON
                        $('.btn_add_more').each(function(index) {
                            // Update data-id attribute with the new order (index + 1)
                            $(this).data('id', index + 1);
                        });
                        R_COUNT = 0;
                        $('.show_added_routes').each(function(index) {
                            R_COUNT++;
                            $(this).attr('id', "show_added_routes_" + R_COUNT);
                        });




                        let day_COUNT = 1;
                        let start_date = '<?= $trip_start_date ?>';
                        let currentDate;

                        // Check if $trip_start_date is a valid date string
                        if (start_date) {
                            // Parse the date string to a format that JavaScript understands
                            const parsedDate = parseDateString(start_date);

                            if (parsedDate) {
                                currentDate = new Date(parsedDate);

                                function addLeadingZero(number) {
                                    return number < 10 ? '0' + number : number;
                                }

                                $('.day_heading').each(function() {
                                    let formattedDate = addLeadingZero(currentDate.getDate()) +
                                        '/' + addLeadingZero(currentDate.getMonth() + 1) +
                                        '/' + currentDate.getFullYear();

                                    $(this).html("DAY " + day_COUNT + " - " + formattedDate);


                                    // Update checkbox IDs and names
                                    updateCheckboxIdsAndNames(day_COUNT);

                                    currentDate.setDate(currentDate.getDate() + 1);
                                    day_COUNT++;
                                });
                            } else {
                                console.error('Invalid date format:', start_date);
                            }
                        } else {
                            console.error('$trip_start_date is not set or invalid.');
                        }

                    }

                    function updateCheckboxIdsAndNames(dayCount) {
                        // Specify the class selector for your checkboxes
                        var checkboxClass = '.form-check-input';
                        var checboxlabel = '.form-check-label';

                        // Use a filter to target specific checkboxes based on the dayCount
                        $(checkboxClass).filter('[id^="direct_to_next_visiting_place"]').each(function(index) {
                            var checkbox = $(this);
                            checkbox.attr({
                                'id': 'direct_to_next_visiting_place' + index,
                                'name': 'direct_to_next_visiting_place[' + index + ']',
                                'for': 'direct_to_next_visiting_place' + index,
                            });
                        });
                        // Use a filter to target specific checkboxes based on the dayCount
                        $(checboxlabel).filter('[for^="direct_to_next_visiting_place"]').each(function(index) {
                            var checkbox_text = $(this);
                            checkbox_text.attr({
                                'for': 'direct_to_next_visiting_place' + index,
                            });
                        });
                    }

                    function parseDateString(dateString) {
                        // Assuming the date string is in the format "DD-MM-YYYY"
                        const parts = dateString.split('-');
                        if (parts.length === 3) {
                            // Rearrange the parts to the format "YYYY-MM-DD"
                            return `${parts[2]}/${parts[1]}/${parts[0]}`;
                        }
                        // Return null for invalid date strings
                        return null;
                    }

                <?php endif; ?>

                // Event listener for the "Add More" button
                $(document).on('click', '.btn_add_more', function(event) {
                    var row_count = $(this).data('id');
                    // alert(row_count);
                    var parentDiv = $(this).closest('.show_route');
                    if (row_count == "") {
                        row_count = 1;
                    }
                    addLocation(row_count, parentDiv);
                });

                $(document).on('click', '.btn_delete_location', function() {
                    let row = $(this).data('id');
                    let itinerary_route_ID = $(this).closest('[data-route-id]').data('route-id');
                    if (itinerary_route_ID != "") {
                        var currentLi = $(this).closest('li');
                        var nextLi = currentLi.next('li');
                        currentLi.remove();
                        $('#show_via_route_label_' + row).remove();
                    } else {
                        var currentLi = $(this).closest('li');
                        var nextLi = currentLi.next('li');
                        currentLi.remove();
                        $('#show_via_route_text_' + row).remove();
                    }

                    //NEXT_VISIT_PLACE
                    NEXT_VISIT_PLACE_COUNT = 0;
                    $('input[name^="next_visiting_place"]').each(function() {
                        if ($(this).attr('type') === 'text') {
                            NEXT_VISIT_PLACE_COUNT++;
                        }
                        $(this).attr('id', "next_visiting_place_" + NEXT_VISIT_PLACE_COUNT);
                    });

                    //VIA ROUTE
                    VIA_ROUTE_COUNT = 0;
                    $('input[name^="via_route"]').each(function() {
                        VIA_ROUTE_COUNT++;
                        $(this).attr('id', "via_route_" + VIA_ROUTE_COUNT);
                    });

                    LOCATION_COUNT = 0;
                    $('input[name^="location_name"]').each(function() {
                        LOCATION_COUNT++;
                        $(this).attr('id', "location_name_" + LOCATION_COUNT);

                    });

                    //ADD MORE BUTTON
                    $('.btn_add_more').each(function(index) {
                        // Update data-id attribute with the new order (index + 1)
                        $(this).data('id', index + 1);
                    });



                    //SHOW ROUTE SPAN
                    R_COUNT = 0;
                    $('.show_added_routes').each(function(index) {
                        R_COUNT++;
                        $(this).attr('id', "show_added_routes_" + R_COUNT);
                    });
                    //alert(row);

                    prev_day_no = parseInt(row) - 1;
                    // alert(prev_day_no);
                    var prev_visiting_place_name = $("#next_visiting_place_" + prev_day_no).val();
                    //alert(prev_visiting_place_name);
                    $("#location_name_" + day_no).val(prev_visiting_place_name);

                    //var location_name = $("#location_name_" + day_no).val();

                    var next_visiting_place = {
                        url: function(phrase) {
                            return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                    phrase) +
                                "&format=json&type=destination&source_location=" + prev_visiting_place_name;
                        },
                        getValue: "get_destination_location",
                        list: {
                            onChooseEvent: function() {
                                set_next_location_name(day_no);
                            },
                            match: {
                                enabled: true
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };

                    $("#next_visiting_place_" + day_no).easyAutocomplete(next_visiting_place);
                    next_search_loc_index = parseInt(day_no) + 1;

                    $("#location_name_" + next_search_loc_index).easyAutocomplete(next_visiting_place);


                    let day_COUNT = 1;
                    let start_date = '<?= $trip_start_date ?>';
                    let currentDate;

                    // Check if $trip_start_date is a valid date string
                    if (start_date) {
                        // Parse the date string to a format that JavaScript understands
                        const parsedDate = parseDateString(start_date);

                        if (parsedDate) {
                            currentDate = new Date(parsedDate);

                            function addLeadingZero(number) {
                                return number < 10 ? '0' + number : number;
                            }

                            $('.day_heading').each(function() {
                                let formattedDate = addLeadingZero(currentDate.getDate()) +
                                    '-' + addLeadingZero(currentDate.getMonth() + 1) +
                                    '-' + currentDate.getFullYear();

                                $(this).html("DAY " + day_COUNT + " - " + formattedDate);
                                currentDate.setDate(currentDate.getDate() + 1);
                                day_COUNT++;
                            });
                        } else {
                            console.error('Invalid date format:', start_date);
                        }
                    } else {
                        console.error('$trip_start_date is not set or invalid.');
                    }

                    function parseDateString(dateString) {
                        // Assuming the date string is in the format "DD-MM-YYYY"
                        const parts = dateString.split('-');
                        if (parts.length === 3) {
                            // Rearrange the parts to the format "YYYY-MM-DD"
                            return `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                        // Return null for invalid date strings
                        return null;
                    }
                });

                //AJAX FORM SUBMIT
                $("#itinerary_routes_form").submit(function(event) {
                    var form = $('#itinerary_routes_form')[0];
                    //alert("submit");
                    var data = new FormData(form);
                    $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_newitinerary.php?type=itinerary_route_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                TOAST_NOTIFICATION('success', 'Itinerary Basic Details Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                //location.assign(response.redirect_URL);

                                $.ajax({
                                    type: "POST",
                                    url: "engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=select_hotels",
                                    data: {
                                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>',
                                        itinerary_preference: '<?= $itinerary_preference; ?>',
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.result == true) {
                                            location.assign('newitinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID; ?>');
                                        } else {
                                            TOAST_NOTIFICATION('error', 'Unable to Proceed!!! ' + response.vehicle_type + ' Not Available. Please check the Vehicle FC Expiry Date !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                        }
                                    }
                                });

                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Basic Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                //location.assign(response.redirect_URL);

                                $.ajax({
                                    type: "POST",
                                    url: "engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=select_hotels",
                                    data: {
                                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>',
                                        itinerary_preference: '<?= $itinerary_preference; ?>',
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.result == true) {
                                            location.assign('newitinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID; ?>');
                                        } else {
                                            TOAST_NOTIFICATION('error', 'Unable to Proceed!!! ' + response.vehicle_type + ' Not Available. Please check the Vehicle FC Expiry Date !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                        }
                                    }
                                });

                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Add Itinerary  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Itinerary  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
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

            function set_next_location_name(route_count) {
                var source_location_name = $("#location_name_" + route_count).val();
                var next_visiting_place_name = $("#next_visiting_place_" + route_count).val();

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_check_location_distancelimit.php',
                    data: {
                        source_location: source_location_name,
                        destination_location: next_visiting_place_name
                    },
                    success: function(response) {
                        if (response == "true") {
                            //SET VIA ROUTE
                            var via_route = {
                                url: function(phrase) {
                                    return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                            phrase) +
                                        "&format=json&type=via_route&source_location=" + source_location_name + "&destination_location=" + next_visiting_place_name;
                                },
                                getValue: "get_via_route_location",
                                list: {
                                    onChooseEvent: function() {
                                        //set_next_location_name(index);
                                    },
                                    match: {
                                        enabled: true
                                    },
                                    hideOnEmptyPhrase: true
                                },
                                theme: "square"
                            };
                            if (source_location_name != "" || next_visiting_place_name != "") {
                                $("#via_route_" + route_count).removeAttr('readonly');
                                $("#via_route_" + route_count).removeAttr("style");
                            }
                            $("#via_route_" + route_count).easyAutocomplete(via_route);

                            next_route_count = parseInt(route_count) + 1;

                            $("#location_name_" + next_route_count).val(next_visiting_place_name);
                            // $("#next_visiting_place_" + next_route_count).easyAutocomplete(next_visiting_place);
                            // next_search_loc_index = parseInt(next_route_count) + 1;
                            // //alert(next_search_loc_index);
                            // $("#location_name_" + next_search_loc_index).easyAutocomplete(next_visiting_place);

                            //SET NEXT LOCATION 
                            total_route_count = parseInt('<?= $itinerary_route_count ?>');
                            for (i = route_count; i <= total_route_count; i++) {
                                (function(index) {
                                    var location_name = $("#location_name_" + index).val();
                                    if (location_name != "") {
                                        var next_visiting_place = {
                                            url: function(phrase) {
                                                return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                                        phrase) +
                                                    "&format=json&type=destination&source_location=" + location_name;
                                            },
                                            getValue: "get_destination_location",
                                            list: {
                                                onChooseEvent: function() {
                                                    set_next_location_name(index);
                                                },
                                                match: {
                                                    enabled: true
                                                },
                                                hideOnEmptyPhrase: true
                                            },
                                            theme: "square"
                                        };
                                        $("#next_visiting_place_" + index).easyAutocomplete(next_visiting_place);
                                        var next_visiting_place_name = $("#next_visiting_place_" + index).val();
                                        if (location_name != "" && next_visiting_place_name != "") {
                                            //SET VIA ROUTE
                                            var via_route = {
                                                url: function(phrase) {
                                                    return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                                            phrase) +
                                                        "&format=json&type=via_route&source_location=" + location_name + "&destination_location=" + next_visiting_place_name;
                                                },
                                                getValue: "get_via_route_location",
                                                list: {
                                                    onChooseEvent: function() {
                                                        //set_next_location_name(index);
                                                    },
                                                    match: {
                                                        enabled: true
                                                    },
                                                    hideOnEmptyPhrase: true
                                                },
                                                theme: "square"
                                            };
                                            $("#via_route_" + index).easyAutocomplete(via_route);
                                            $("#via_route_" + index).removeAttr('readonly');
                                            $("#via_route_" + index).removeAttr("style");
                                        }
                                        next_search_loc_index = parseInt(index) + 1;
                                        //alert(next_search_loc_index);
                                        $("#location_name_" + next_search_loc_index).easyAutocomplete(next_visiting_place);

                                    }
                                })(i);
                            }
                            //SET NEXT VISITING PLACE AUTOCOMPLETE
                            // var next_visiting_place = {
                            //     url: function(phrase) {
                            //         return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                            //                 phrase) +
                            //             "&format=json&type=destination&source_location=" + next_visiting_place_name;
                            //     },
                            //     getValue: "get_destination_location",
                            //     list: {
                            //         onChooseEvent: function() {
                            //             set_next_location_name(next_route_count);
                            //         },
                            //         match: {
                            //             enabled: true
                            //         },
                            //         hideOnEmptyPhrase: true
                            //     },
                            //     theme: "square"
                            // };
                        } else {
                            TOAST_NOTIFICATION('error', 'Selected Location exceeds the distance limit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            $("#next_visiting_place_" + route_count).val('');
                        }
                    }
                });
            }

            function confirmLOCATIONDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_newitinerary.php?type=confirm_delete_itinerary_route_location",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#confirmDELETEINFODATA').modal('hide');
                            $('#confirmDELETEINFODATA').find('.close').trigger('click');
                            TOAST_NOTIFICATION('success', 'Location Deleted Successfully', 'Success !!!', '', '', '',
                                '', '', '', '', '', '');
                            $("#show_location_" + ID).remove();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Delete the Location', 'Error !!!', '', '', '', '', '', '', '',
                                '', '');
                        }
                    }
                });
            }

            function confirmALTERDAYS(ID, itineraryRouteID, NO_OF_DAYS, TYPE) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_alter_itinerary_route_days.php?type=confirm_alter_days",
                    data: {
                        _ID: ID,
                        NO_OF_DAYS: NO_OF_DAYS,
                        itinerary_route_ID: itineraryRouteID,
                        TYPE: TYPE
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $(this).siblings('.quantity-field').val(NO_OF_DAYS);
                            TOAST_NOTIFICATION('success', 'Days Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#confirmALTERDAYINFODATA').modal('hide');
                            $('#confirmALTERDAYINFODATA').find('.close').trigger('click');
                            location.reload();
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Update the days', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });

            }

            function generate_ITINERARY() {

                $('#itinerary_routes_form').submit();

                /*  $.ajax({
                      type: "POST",
                      url: "engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=select_hotels",
                      data: {
                          itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>',
                      },
                      dataType: 'json',
                      success: function(response) {
                         
                          alert("generate");
                          location.assign('newitinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID; ?>');
                      }
                  });*/
            }

            function edit_via_ROUTE(ID) {
                $('#show_via_route_label_' + ID).addClass('d-none');
                $('#show_via_route_input_' + ID).removeClass('d-none');
            }


            <?php
        endif;
    else :
        echo "Request Ignored";
    endif;
