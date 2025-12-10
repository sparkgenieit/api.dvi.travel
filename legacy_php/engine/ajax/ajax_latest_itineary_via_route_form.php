<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');
$itinerary_session_id = session_id();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $DAY_NO = $_GET['DAY_NO'];
        $selected_source_location = trim($_GET['selected_source_location']);
        $selected_next_visiting_location = trim($_GET['selected_next_visiting_location']);
        $itinerary_route_ID = $_GET['itinerary_route_ID'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
        $itinerary_route_date = $_GET['itinerary_route_date'];
        $itinerary_route_date_db = dateformat_database($itinerary_route_date);
        if ($itinerary_plan_ID && $itinerary_route_ID) :
            //$itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date', '');
            $filter_by_via_route = " AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
        else :
            $filter_by_via_route = " AND `itinerary_route_date` = '$itinerary_route_date_db' AND `itinerary_session_id` = '$itinerary_session_id' ";
        endif;
?>
        <style>
            .timeline {
                height: auto;
            }
        </style>
        <div class="row">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
                <h5 class="address-title mb-2">
                    <i class="text-dark col-md-12 mb-3 ti ti-map-2 ti-md rounded-circle scaleX-n1-rtl" style="color: #aa008e !important"></i>
                    <?= 'Day ' . $DAY_NO; ?> | <?= $itinerary_route_date; ?> | Via Route
                </h5>
            </div>
            <div class="row justify-content-center border-bottom">
                <div class="col-md-6 my-auto">
                    <div class="row">
                        <div class="col-md-2">
                            <h6 class=""><i class="ti ti-current-location rounded-circle" style="color: #aa008e !important"></i></h6>
                        </div>
                        <div class="col-md-10">
                            <h6 class="mb-0 text-truncate"><?= $selected_source_location; ?></h6>
                            <p class="mt-1 text-muted text-start" style="font-size: 12px;">Source Location</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 my-auto">
                    <div class="row">
                        <div class="col-md-2">
                            <h6 class=""><i class="ti ti-map-pin rounded-circle scaleX-n1-rtl px-2" style="color: #aa008e !important"></i>
                            </h6>
                        </div>
                        <div class="col-md-10">
                            <h6 class="mb-0 text-truncate"><?= $selected_next_visiting_location; ?></h6>
                            <p class="mt-1 text-muted text-start" style="font-size: 12px;">Next Visiting Place</p>
                        </div>
                    </div>
                </div>
            </div>
            <form class="row g-3" id="itineary_via_route_form" action="" method="post" data-parsley-validate>
                <ul class="timeline mb-0 add-route-timeline routesContainer" id="add-route-timeline">
                    <?php
                    $select_itinerary_via_route_list_query = sqlQUERY_LABEL("SELECT `itinerary_via_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `itinerary_route_date`, `source_location`, `destination_location`, `itinerary_via_location_name`,`itinerary_via_location_ID` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' AND `status` = '1' {$filter_by_via_route}") or die("#1-UNABLE_TO_ITINEARY_ROUTE_VIA_LOCATION_LIST:" . sqlERROR_LABEL());
                    $select_itinerary_via_route_count = sqlNUMOFROW_LABEL($select_itinerary_via_route_list_query);
                    if ($select_itinerary_via_route_count > 0) :

                        while ($fetch_itinerary_via_route_list_data = sqlFETCHARRAY_LABEL($select_itinerary_via_route_list_query)) :
                            $via_route_count++;
                            $itinerary_via_route_ID = $fetch_itinerary_via_route_list_data['itinerary_via_route_ID'];
                            $itinerary_route_ID = $fetch_itinerary_via_route_list_data['itinerary_route_ID'];
                            $itinerary_plan_ID = $fetch_itinerary_via_route_list_data['itinerary_plan_ID'];
                            $itinerary_route_date = $fetch_itinerary_via_route_list_data['itinerary_route_date'];
                            $source_location = $fetch_itinerary_via_route_list_data['source_location'];
                            $destination_location = $fetch_itinerary_via_route_list_data['destination_location'];
                            $itinerary_via_location_name = $fetch_itinerary_via_route_list_data['itinerary_via_location_name'];
                            $itinerary_via_location_ID = $fetch_itinerary_via_route_list_data['itinerary_via_location_ID'];
                    ?>
                            <li class="timeline-item timeline-item-transparent pb-3 route-list" id="via_route_list_<?= $itinerary_via_route_ID; ?>" style="margin-left: 3rem;">
                                <div class="col-md-10 d-flex route align-items-center">
                                    <span class="timeline-indicator-advanced timeline-indicator-primary"></span>
                                    <span class="timeline-indicator-advanced timeline-indicator-primary" style="top: 0.6rem;">
                                        <i class="ti ti-send rounded-circle scaleX-n1-rtl" style="color: #28c76f !important; background: #fff;"></i>
                                    </span>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <select name="via_route_location[]" id="via_route_location_<?= $via_route_count ?>" class="form-select form-control via_route_location" required>
                                                <?= getSTOREDLOCATION_VIAROUTE_DROPDOWN($itinerary_via_location_ID, $selected_source_location, $selected_next_visiting_location, 'select'); ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="hidden_route_date" value="<?= $itinerary_route_date; ?>" hidden>
                                        <input type="hidden" name="hidden_source_location" value="<?= $selected_source_location; ?>" hidden>
                                        <input type="hidden" name="hidden_destination_location" value="<?= $selected_next_visiting_location; ?>" hidden>
                                        <input type="hidden" name="hidden_itineary_via_route_id[]" value="<?= $itinerary_via_route_ID; ?>" hidden>
                                        <input type="hidden" name="hidden_itinerary_route_ID" value="<?= $itinerary_route_ID; ?>" hidden>
                                        <input type="hidden" name="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>
                                    </div>
                                    <?php if ($via_route_count == 1) : ?>
                                        <div class="col-md-4 d-flex justify-content-evenly">
                                            <div class="col-md-2 text-center">
                                                <button type="button" onclick="removeVIAROUTE('<?= $itinerary_via_route_ID; ?>')" class="btn btn-outline-primary btn-sm remove-route"><i class="ti ti-trash ti-tada-hover"></i></button>
                                            </div>
                                            <div class="col-md-2 ms-3 text-center">
                                                <button type="button" class="btn btn-primary btn-sm addRoute"><i class="ti ti-plus ti-tada-hover"></i></button>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="col-md-2 d-flex justify-content-evenly">
                                            <div class="col-md-2 me-3 text-center">
                                                <button type="button" onclick="removeVIAROUTE('<?= $itinerary_via_route_ID; ?>')" class="btn btn-outline-primary btn-sm remove-route"><i class="ti ti-trash ti-tada-hover"></i></button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endwhile;
                    else : ?>
                        <li class="timeline-item timeline-item-transparent pb-3 route-list" style="margin-left: 3rem;">
                            <div class="col-md-10 d-flex route align-items-center">
                                <span class="timeline-indicator-advanced timeline-indicator-primary"></span>
                                <span class="timeline-indicator-advanced timeline-indicator-primary" style="top: 0.6rem;">
                                    <i class="ti ti-send rounded-circle scaleX-n1-rtl" style="color: #28c76f !important; background: #fff;"></i>
                                </span>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <select name="via_route_location[]" id="via_route_location_1" class="form-select form-control via_route_location" required>
                                            <?= getSTOREDLOCATION_VIAROUTE_DROPDOWN('', $selected_source_location, $selected_next_visiting_location, 'select'); ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="hidden_route_date" value="<?= $itinerary_route_date; ?>" hidden>
                                    <input type="hidden" name="hidden_source_location" value="<?= $selected_source_location; ?>" hidden>
                                    <input type="hidden" name="hidden_destination_location" value="<?= $selected_next_visiting_location; ?>" hidden>
                                    <input type="hidden" name="hidden_itineary_via_route_id[]" value="<?= $itinerary_via_route_ID; ?>" hidden>
                                    <input type="hidden" name="hidden_itinerary_route_ID" value="<?= $itinerary_route_ID; ?>" hidden>
                                    <input type="hidden" name="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>
                                </div>

                                <div class="col-md-2 d-flex justify-content-evenly">
                                    <div class="col-md-2 text-center">
                                        <button type="button" class="btn btn-primary btn-sm addRoute"><i class="ti ti-plus ti-tada-hover"></i></button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="col-12 text-center">
                    <button type="submit" id="save_itineary_via_route_btn" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    <button type="button" class="btn btn-label-secondary" id="close_via_modal" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </form>
        </div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                <?php if ($select_itinerary_via_route_count == 0): ?>
                    // Initialize existing Selectize dropdowns
                    $('#via_route_location_1').selectize();
                <?php else: ?>
                    var max_count = '<?= $select_itinerary_via_route_count; ?>';
                    for (route = 1; route <= max_count; route++) {
                        $('#via_route_location_' + route).selectize();
                    }
                <?php endif; ?>

                $(".addRoute").click(function() {
                    let routeCount = $(".route-list").length;
                    if (routeCount <= 1) {
                        routeCount++; // Increment the counter for each new route
                        let newRouteInput = `
                <li class="timeline-item timeline-item-transparent pb-3 route-list" style="margin-left: 3rem;">
                    <div class="col-md-10 d-flex route align-items-center">
                        <span class="timeline-indicator-advanced timeline-indicator-primary" style="top: 0.6rem;">
                            <i class="ti ti-send rounded-circle scaleX-n1-rtl" style="color: #28c76f !important; background: #fff;"></i>
                        </span>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select name="via_route_location[]" id="via_route_location_${routeCount}" class="form-select form-control via_route_location" required>
                                    <?= getSTOREDLOCATION_VIAROUTE_DROPDOWN('', $selected_source_location, $selected_next_visiting_location, 'select'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-evenly">
                            <div class="col-md-2 text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm remove-route"><i class="ti ti-trash ti-tada-hover"></i></button>
                            </div>
                        </div>
                    </div>
                </li>
            `;
                        $(".routesContainer").append(newRouteInput);
                        // Initialize Selectize for the new dropdown
                        $('#via_route_location_' + routeCount).selectize();
                        // Call function to remove already added options from the new dropdown

                        // Update dropdown options to disable already selected routes
                        updateDropdownOptions();
                    } else {
                        TOAST_NOTIFICATION('warning', 'Maximum Limit Reached', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                    }
                });

                $(".routesContainer").on("click", ".remove-route", function() {
                    var $routeList = $(this).closest(".route-list");
                    $routeList.remove();
                    // Update dropdown options to disable already selected routes
                    updateDropdownOptions();
                });

                // AJAX form submission
                $("#itineary_via_route_form").submit(function(event) {
                    event.preventDefault(); // Prevent default form submission
                    // First, validate the distance before submitting
                    calculateDistance(function(distanceValid) {
                        if (distanceValid) {
                            // Proceed with form submission if distance is valid
                            var form = $('#itineary_via_route_form')[0];
                            var data = new FormData(form);
                            $(this).find("button[id='save_itineary_via_route_btn']").prop('disabled', true);
                            $.ajax({
                                type: "post",
                                url: 'engine/ajax/ajax_latest_manage_itineary.php?type=add_via_route',
                                data: data,
                                processData: false,
                                contentType: false,
                                cache: false,
                                timeout: 80000,
                                dataType: 'json',
                                encode: true,
                            }).done(function(response) {
                                if (!response.success) {
                                    if (response.errors.via_route_location_required) {
                                        TOAST_NOTIFICATION('warning', 'Via Route Location Required', 'Warning !!!');
                                    }
                                    $('#save_itineary_via_route_btn').removeAttr('disabled');
                                } else {
                                    if (response.i_result == true) {
                                        TOAST_NOTIFICATION('success', 'Via Route Added Successfully', 'Success !!!');
                                        $('.btn-close').click();
                                    } else if (response.i_result == false) {
                                        TOAST_NOTIFICATION('error', 'Unable to Add the Via Route', 'Error !!!');
                                    } else if (response.u_result == true) {
                                        TOAST_NOTIFICATION('success', 'Via Route Updated Successfully', 'Success !!!');
                                        $('.btn-close').click();
                                    } else if (response.u_result == false) {
                                        TOAST_NOTIFICATION('error', 'Unable to Update the Via Route', 'Error !!!');
                                    } else {
                                        TOAST_NOTIFICATION('error', 'Unable to Add the Via Route', 'Error !!!');
                                    }
                                }
                                if (response == "OK") {
                                    return true;
                                } else {
                                    return false;
                                }
                            });
                        } else {
                            // Display an error message if distance validation fails
                            TOAST_NOTIFICATION('warning', 'Distance validation failed. Please check your routes.', 'Warning !!!');
                        }
                    });
                });
            });

            $(document).ready(function() {
                let selectedViaRoutes = []; // Array to track selected via routes

                // When the via route location changes
                $(".routesContainer").on("change", ".via_route_location", function() {
                    let selectedRoute = $(this).val(); // Get the selected via route
                    selectedViaRoutes = []; // Reset array to update the current selection state

                    // Collect all selected via routes from all dropdowns
                    $('.via_route_location').each(function() {
                        let selectedValue = $(this).val();
                        if (selectedValue) {
                            selectedViaRoutes.push(selectedValue); // Add current selected via route to the array
                        }
                    });

                    // Update all dropdowns to disable the already selected options
                    updateDropdownOptions();

                    // Call distance calculation function (handle total distance calculation across all via routes)
                    calculateDistance();
                });

                // Add another via route dropdown dynamically (example)
                $('#add_via_route_btn').on('click', function() {
                    let routeCount = $('.via_route_location').length + 1;
                    let newViaRouteHtml = `
            <div class="form-group">
                <select name="via_route_location[]" id="via_route_location_${routeCount}" class="form-select form-control via_route_location" required>
                    <?= getSTOREDLOCATION_VIAROUTE_DROPDOWN($itinerary_via_location_ID, $selected_source_location, $selected_next_visiting_location, 'select'); ?>
                </select>
            </div>
            <div class="distance-info" id="distance_info_${routeCount}"></div>
        `;
                    $('.routesContainer').append(newViaRouteHtml);
                    updateDropdownOptions(); // Update the new dropdown options based on already selected routes
                });
            });

            // Function to update dropdown options
            function updateDropdownOptions() {
                $('.via_route_location').each(function() {
                    let currentDropdown = $(this);
                    let selectedValue = currentDropdown.val();

                    // Remove all options from the dropdown except the currently selected one
                    currentDropdown.find('option').each(function() {
                        if ($(this).val() !== selectedValue && selectedViaRoutes.includes($(this).val())) {
                            $(this).prop('disabled', true); // Disable previously selected options
                        } else {
                            $(this).prop('disabled', false); // Enable options not yet selected
                        }
                    });
                });
            }

            // Function to calculate the total distance and validate it via an AJAX call
            function calculateDistance(callback) {
                let source = $('input[name="hidden_source_location"]').val();
                let destination = $('input[name="hidden_destination_location"]').val();
                let viaRoutes = [];

                // Collect all selected via routes in order
                $('.via_route_location').each(function() {
                    let viaRoute = $(this).val();
                    if (viaRoute) {
                        viaRoutes.push(viaRoute);
                    }
                });

                // Make sure we have at least source, one via route, and destination
                if (source && viaRoutes.length > 0 && destination) {
                    // Make the AJAX call to validate the distance
                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/ajax_check_via_route_distance_limit.php?type=check_distance_limit', // Server-side script for total distance
                        data: {
                            source: source,
                            via_routes: viaRoutes, // Send all via routes as an array
                            destination: destination
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                callback(true); // Distance is valid, proceed with form submission
                            } else {
                                callback(false); // Distance exceeded or error, don't proceed
                            }
                        },
                        error: function() {
                            callback(false); // Error with AJAX, don't proceed
                        }
                    });
                } else {
                    callback(false); // If source, via, or destination is missing, validation fails
                }
            }

            /* function removealreadyaddedviaroute() {
                // Get all selected options
                let selectedOptions = [];
                $(".via_route_location").each(function() {
                    let selectedValue = $(this).val();
                    if (selectedValue) {
                        selectedOptions.push(selectedValue);
                    }
                });

                // Iterate over all Selectize dropdowns and remove selected options
                $(".via_route_location").each(function() {
                    let selectizeInstance = this.selectize;
                    if (selectizeInstance) {
                        $.each(selectedOptions, function(index, value) {
                            if (selectizeInstance.options[value] && value !== selectizeInstance.getValue()) {
                                selectizeInstance.removeOption(value);
                            }
                        });
                        selectizeInstance.refreshOptions(false); // Refresh the options in the dropdown
                    }
                });
            } */

            function removeVIAROUTE(itinerary_via_route_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=remove_via_route',
                    data: {
                        itinerary_via_route_ID: itinerary_via_route_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Unable to Remove Via Route', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            $('#via_route_list_' + itinerary_via_route_ID).remove();
                            TOAST_NOTIFICATION('success', 'Via Route Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_via_modal').click();
                            addVIAROUTE('<?= $DAY_NO; ?>', '<?= $itinerary_route_ID; ?>', '<?= $itinerary_plan_ID; ?>');
                        }
                    }
                });
            }

            function addVIAROUTE(DAY_NO, itinerary_route_ID, itinerary_plan_ID) {
                var itinerary_route_date = encodeURIComponent($('#itinerary_route_date_' + DAY_NO).val()).trim();
                var selected_source_location = encodeURIComponent($('#source_location_' + DAY_NO).val()).trim();
                var selected_next_visiting_location = encodeURIComponent($('#next_visiting_location_' + DAY_NO).val()).trim();

                if (selected_source_location && selected_next_visiting_location) {
                    $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_via_route_form.php?type=show_form&DAY_NO=' + DAY_NO + '&selected_source_location=' + selected_source_location + '&selected_next_visiting_location=' + selected_next_visiting_location + '&itinerary_route_ID=' + itinerary_route_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_date=' + itinerary_route_date, function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                } else {
                    TOAST_NOTIFICATION('error', 'Source location & next visiting place should be required !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                }
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
