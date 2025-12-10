<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST
    if ($_GET['type'] == 'show_form') :

        $itinerary_type = $_POST['itinerary_type'];
        $startDateStr = $_POST['startDateStr'];
        $endDateStr = $_POST['endDateStr'];
        $no_of_days = $_POST['no_of_days'];
        $departure_location = $_POST['departure_location'];
        $arrival_location = $_POST['arrival_location'];
        $location_ID = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($arrival_location, $departure_location);

?>
        <!-- ITINERARY ROUTE START -->
        <div id="itinerary_routes" class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Route Suggestions</strong></h5>
                    <div class="card-header pt-2">
                        <ul class="nav nav-tabs hotel-list-nav card-header-tabs" role="tablist">
                            <?php
                            $select_LOCATIONLIST_query = sqlQUERY_LABEL("SELECT ROUTE.`stored_route_ID`, ROUTE.`route_name` FROM `dvi_stored_routes` ROUTE LEFT JOIN `dvi_stored_route_location_details` ROUTE_LOCATION ON ROUTE.`stored_route_ID` = ROUTE_LOCATION.`stored_route_id` WHERE ROUTE.`location_id`='$location_ID' AND ROUTE.`deleted` = '0' AND ROUTE_LOCATION.`deleted` = '0'  GROUP BY ROUTE.`stored_route_ID` HAVING  COUNT(ROUTE_LOCATION.`stored_route_id`) = '$no_of_days' ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                            $itinerary_routes = sqlNUMOFROW_LABEL($select_LOCATIONLIST_query);
                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) :
                                $counter++;
                                $stored_route_ID = $fetch_list_data['stored_route_ID'];
                                $route_name = $fetch_list_data['route_name'];

                                if ($counter == 1) :
                                    $first_route_id = $stored_route_ID;
                                    $add_active_tab_class = 'active';
                                else :
                                    $add_active_tab_class = '';
                                endif;
                            ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= $add_active_tab_class; ?>" id="route_<?= $counter; ?>" onclick="showROUTETAB('<?= $stored_route_ID; ?>','<?= $counter; ?>')" role="tab" aria-selected="true">Route #<?= $counter; ?>
                                        <span class="arrow <?= $add_active_tab_class; ?>" id="arrow_group_tab_<?= $counter; ?>"></span>
                                    </button>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <?php

                    $select_itinerary_route_details = sqlQUERY_LABEL("SELECT ROUTE_LOCATION.`route_location_name`,ROUTE_LOCATION.`stored_route_location_ID`  FROM `dvi_stored_routes` ROUTES LEFT JOIN `dvi_stored_route_location_details` ROUTE_LOCATION ON ROUTES.`stored_route_ID` = ROUTE_LOCATION.`stored_route_id` WHERE ROUTES.`location_id`='$location_ID' AND ROUTES.`stored_route_ID`='$first_route_id' AND ROUTES.`deleted` = '0' AND ROUTE_LOCATION.`deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                    $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                    if ($select_itinerary_route_details_count > 0) :
                        while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                            $route_day_counter++;
                            $stored_route_location_ID = $fetch_itineary_route_data['stored_route_location_ID'];
                            $route_locations[] = $fetch_itineary_route_data['route_location_name'];

                        endwhile;
                    endif;

                    // Add departure and arrival locations to the list of locations
                    array_unshift($route_locations, $departure_location);
                    array_push($route_locations, $arrival_location);

                    // Remove duplicates and re-index array
                    $unique_locations = array_values(array_unique($route_locations));

                    // Construct the SQL query to retrieve all distances
                    $location_placeholders = "'" . implode("','", $unique_locations) . "'";
                    $query = "SELECT `source_location`, `destination_location`, `distance`  FROM `dvi_stored_locations`  WHERE `source_location` IN ($location_placeholders) AND `destination_location` IN ($location_placeholders) AND `deleted` = '0'";

                    $result = sqlQUERY_LABEL($query) or die("#1_UNABLE_TO_FETCH_DATA: " . sqlERROR_LABEL());
                    $distances = [];
                    while ($row = sqlFETCHARRAY_LABEL($result)) {
                        $distances[] = $row;
                    }

                    // Create the distance matrix from the retrieved data
                    $location_indices = array_flip($unique_locations);
                    $num_locations = count($unique_locations);
                    $distance_matrix = array_fill(0, $num_locations, array_fill(0, $num_locations, PHP_INT_MAX));
                    foreach ($distances as $row) {
                        $from = $location_indices[$row['source_location']];
                        $to = $location_indices[$row['destination_location']];
                        $distance_matrix[$from][$to] = $row['distance'];
                        $distance_matrix[$to][$from] = $row['distance'];  // Assuming bidirectional distances
                    }

                    // Function to solve TSP with a daily travel limit
                    function solve_tsp_with_limit($distance_matrix, $daily_limit, $no_of_days)
                    {
                        $num_locations = count($distance_matrix);
                        $current_location = 0;  // Start from the first location (departure location)
                        $visited = array_fill(0, $num_locations, false);
                        $route = [];
                        $day = 1;
                        $daily_distance = 0;

                        while (count(array_filter($visited)) < $num_locations) {
                            $visited[$current_location] = true;
                            $route[] = $current_location;

                            // Find the nearest unvisited location
                            $nearest_distance = PHP_INT_MAX;
                            $nearest_location = -1;
                            for ($i = 0; $i < $num_locations; $i++) {
                                if (!$visited[$i] && $distance_matrix[$current_location][$i] < $nearest_distance) {
                                    $nearest_distance = $distance_matrix[$current_location][$i];
                                    $nearest_location = $i;
                                }
                            }

                            if ($nearest_location == -1 || $daily_distance + $nearest_distance > $daily_limit) {
                                // End the day if no unvisited location or distance exceeds daily limit
                                $current_location = 0;  // Return to the starting location for simplicity
                                $daily_distance = 0;
                                $day++;
                            } else {
                                $daily_distance += $nearest_distance;
                                $current_location = $nearest_location;
                            }
                        }

                        return $route;
                    }

                    // Solve TSP with the given daily travel limit and number of days
                    $daily_limit = 350;  // 350 km daily travel limit
                    $optimal_route = solve_tsp_with_limit($distance_matrix, $daily_limit, $no_of_days);
                    echo "fff";
                    print_r($optimal_route);
                    //die;
                    // Generate the itinerary
                    $current_date = $startDateStr;

                    foreach ($optimal_route as  $location_index) {

                        $location_name = $unique_locations[$location_index];
                        echo  $location_name . "\n";
                    }

                    die;
                    ?>
                    <div class="tab-content px-0">
                        <div class="tab-pane fade active show" id="show_recommended_hotel_details" role="tabpanel">
                            <div class="text-nowrap mb-3 table-responsive">
                                <table id="route_details_LIST" class="table table-borderless" style="width:100%">
                                    <thead class="table-header-color">
                                        <tr>
                                            <th class="text-start" width="8%">DAY</th>
                                            <th class="text-start">DATE</th>
                                            <th class="text-start">SOURCE DESTINATION</th>
                                            <th class="text-start">NEXT DESTINATION</th>
                                            <th class="text-start">VIA ROUTE</th>
                                            <th class="text-start" colspan="2">DIRECT DESTINATION VISIT</th>
                                            <th style="width: 0;padding: 0px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="route_details_tbody">
                                        <?php
                                        $select_itinerary_route_details = sqlQUERY_LABEL("SELECT ROUTE_LOCATION.`route_location_name`,ROUTE_LOCATION.`stored_route_location_ID`  FROM `dvi_stored_routes` ROUTES LEFT JOIN `dvi_stored_route_location_details` ROUTE_LOCATION ON ROUTES.`stored_route_ID` = ROUTE_LOCATION.`stored_route_id` WHERE ROUTES.`location_id`='$location_ID' AND ROUTES.`stored_route_ID`='$first_route_id' AND ROUTES.`deleted` = '0' AND ROUTE_LOCATION.`deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                                        $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                                        if ($select_itinerary_route_details_count > 0) :
                                            while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                                                $route_day_counter++;
                                                $stored_route_location_ID = $fetch_itineary_route_data['stored_route_location_ID'];
                                                $location_name = $fetch_itineary_route_data['route_location_name'];

                                                if ($route_day_counter == 1) :
                                                    $itinerary_route_date = dateformat_datepicker($startDateStr);
                                                else :
                                                    // Increment the route date by 1 day
                                                    $itinerary_route_date = date('Y-m-d', strtotime($itinerary_route_date . ' +1 day'));
                                                endif;
                                                //$direct_to_next_visiting_place = $fetch_itineary_route_data['direct_to_next_visiting_place'];
                                                $next_visiting_location = $fetch_itineary_route_data['route_location_name'];
                                        ?>
                                                <tr id="route_details_<?= $itinerary_route_ID; ?>">
                                                    <td class=" day text-start" width="8%">DAY
                                                        <?= $route_day_counter; ?></td>
                                                    <td class="date" id="route_date_<?= $route_day_counter; ?>">
                                                        <?= dateformat_datepicker($itinerary_route_date); ?></td>
                                                    <td>
                                                        <input type="text" readonly required class="bg-body form-control" id="source_location_<?= $route_day_counter; ?>" name="source_location[]" style="cursor:not-allowed;" placeholder="Source Location" value="<?= $location_name; ?>" aria-describedby="defaultFormControlHelp" autocomplete="off">
                                                        <input type="hidden" name="hidden_itinerary_route_ID[]" value="<?= $itinerary_route_ID; ?>" hidden>
                                                        <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= $itinerary_route_date; ?>" hidden>
                                                    </td>
                                                    <td>
                                                        <?php if ($departure_location == $next_visiting_location && $route_day_counter == $select_itinerary_route_details_count) : ?>
                                                            <input type="text" class="bg-body form-control" required id="next_visiting_location_<?= $route_day_counter; ?>" name="next_visiting_location[]" readonly placeholder="Next Visiting Place" value="<?= $next_visiting_location; ?>" aria-describedby="defaultFormControlHelp" style="cursor:not-allowed;" autocomplete="off">
                                                        <?php else : ?>
                                                            <input type="text" class="form-control" required id="next_visiting_location_<?= $route_day_counter; ?>" name="next_visiting_location[]" placeholder="Next Visiting Place" value="<?= $next_visiting_location; ?>" aria-describedby="defaultFormControlHelp" autocomplete="off">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(<?= $route_day_counter; ?>,<?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>)"><i class="ti ti-route ti-tada-hover"></i></button>
                                                    </td>
                                                    <td>
                                                        <label class="switch switch-sm"><input type="checkbox" <?php
                                                                                                                if ($direct_to_next_visiting_place == 1) :
                                                                                                                    echo 'checked';
                                                                                                                endif;
                                                                                                                ?> id="direct_destination_visit_<?= $route_day_counter; ?>" name="direct_destination_visit[<?= $route_day_counter; ?>][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span></label>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (
                                                            ($arrival_location != $location_name && $departure_location != $next_visiting_location)
                                                            && ($route_day_counter > 1 && $route_day_counter <= $select_itinerary_route_details_count - 2)
                                                        ) :
                                                        ?> <div onclick="deleteROUTE(<?= $itinerary_route_ID; ?>, <?= $route_day_counter; ?>);">
                                                                <i class="ti ti-x ti-danger ti-tada-hover ti-md" style="color: #F32013; cursor: pointer;"></i>

                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile;
                                        else : ?>
                                            <tr>
                                                <td colspan="7">Please Select the Trip Start and End Date First
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-start">
                                <button type="button" id="route_add_days_btn" class="btn btn-outline-dribbble btn-sm addNextDayPlan" <?= $add_days_disabled; ?>><i class="ti ti-plus ti-tada-hover"></i>Add
                                    Day</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="form-check custom-option custom-option-icon border-0">
                    <label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
                        <div class="nav-align-top nav-tabs-shadow mb-4">
                            <ul class="nav nav-tabs route-details-nav-tabs" role="presentation">
                                <li class="nav-item route-details-nav-item" role="presentation">
                                    <button type="button" class="nav-link route-details-nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#route-one" aria-controls="route-one" aria-selected="true">Route Details</button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="route-one" role="tabpanel">
                                    <div class="table-responsive">
                                        <table id="route_details_LIST" class="table table-borderless" style="width:100%">
                                            <thead class="table-header-color">
                                                <tr>
                                                    <th class="text-start" width="8%">DAY</th>
                                                    <th class="text-start">DATE</th>
                                                    <th class="text-start">SOURCE DESTINATION</th>
                                                    <th class="text-start">NEXT DESTINATION</th>
                                                    <th class="text-start">VIA ROUTE</th>
                                                    <th class="text-start" colspan="2">DIRECT DESTINATION VISIT</th>
                                                    <th style="width: 0;padding: 0px"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="route_details_tbody">
                                                <?php
                                                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                                                $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                                                if ($select_itinerary_route_details_count > 0) :
                                                    while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                                                        $route_day_counter++;
                                                        $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                                                        $location_name = $fetch_itineary_route_data['location_name'];
                                                        $itinerary_route_date = $fetch_itineary_route_data['itinerary_route_date'];
                                                        $direct_to_next_visiting_place = $fetch_itineary_route_data['direct_to_next_visiting_place'];
                                                        $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                                                ?>
                                                        <tr id="route_details_<?= $itinerary_route_ID; ?>">
                                                            <td class=" day text-start" width="8%">DAY
                                                                <?= $route_day_counter; ?></td>
                                                            <td class="date" id="route_date_<?= $route_day_counter; ?>">
                                                                <?= dateformat_datepicker($itinerary_route_date); ?></td>
                                                            <td>
                                                                <input type="text" readonly required class="bg-body form-control" id="source_location_<?= $route_day_counter; ?>" name="source_location[]" style="cursor:not-allowed;" placeholder="Source Location" value="<?= $location_name; ?>" aria-describedby="defaultFormControlHelp" autocomplete="off">
                                                                <input type="hidden" name="hidden_itinerary_route_ID[]" value="<?= $itinerary_route_ID; ?>" hidden>
                                                                <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= $itinerary_route_date; ?>" hidden>
                                                            </td>
                                                            <td>
                                                                <?php if ($departure_location == $next_visiting_location && $route_day_counter == $select_itinerary_route_details_count) : ?>
                                                                    <input type="text" class="bg-body form-control" required id="next_visiting_location_<?= $route_day_counter; ?>" name="next_visiting_location[]" readonly placeholder="Next Visiting Place" value="<?= $next_visiting_location; ?>" aria-describedby="defaultFormControlHelp" style="cursor:not-allowed;" autocomplete="off">
                                                                <?php else : ?>
                                                                    <input type="text" class="form-control" required id="next_visiting_location_<?= $route_day_counter; ?>" name="next_visiting_location[]" placeholder="Next Visiting Place" value="<?= $next_visiting_location; ?>" aria-describedby="defaultFormControlHelp" autocomplete="off">
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(<?= $route_day_counter; ?>,<?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>)"><i class="ti ti-route ti-tada-hover"></i></button>
                                                            </td>
                                                            <td>
                                                                <label class="switch switch-sm"><input type="checkbox" <?php if ($direct_to_next_visiting_place == 1) : echo 'checked';
                                                                                                                        endif; ?> id="direct_destination_visit_<?= $route_day_counter; ?>" name="direct_destination_visit[<?= $route_day_counter; ?>][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span></label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if (
                                                                    ($arrival_location != $location_name && $departure_location != $next_visiting_location)
                                                                    && ($route_day_counter > 1 && $route_day_counter <= $select_itinerary_route_details_count - 2)
                                                                ) :
                                                                ?> <div onclick="deleteROUTE(<?= $itinerary_route_ID; ?>, <?= $route_day_counter; ?>);">
                                                                        <i class="ti ti-x ti-danger ti-tada-hover ti-md" style="color: #F32013; cursor: pointer;"></i>

                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile;
                                                else : ?>
                                                    <tr>
                                                        <td colspan="7">Please Select the Trip Start and End Date First
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-start">
                                        <button type="button" id="route_add_days_btn" class="btn btn-outline-dribbble btn-sm addNextDayPlan" <?= $add_days_disabled; ?>><i class="ti ti-plus ti-tada-hover"></i>Add
                                            Day</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </label>
                </div>-->
            </div>
        </div>


        <script>
            $(document).ready(function() {


            });

            function showROUTETAB(stored_route_ID, route_count) {
                // Remove active class from all nav-link buttons
                let tabs = document.querySelectorAll('.nav-link');
                tabs.forEach(function(tab) {
                    tab.classList.remove('active'); // Assuming 'active' is the class you want to remove
                });

                // Add active class to the clicked button
                let currentTab = document.getElementById('group_tab_' + route_count);
                let arrowgroupcurrentTab = document.getElementById('arrow_group_tab_' + route_count);
                currentTab.classList.add('active');
                arrowgroupcurrentTab.classList.add('active');

                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_show_recommended_hotel_details_form.php?type=show_form",
                    data: {
                        _planID: itinerary_plan_id,
                        _groupTYPE: group_type,
                    },
                    success: function(response) {
                        $('#show_recommended_hotel_details').html('')
                        $('#show_recommended_hotel_details').html(response);
                        showOVERALL_COST_DETAILS(itinerary_plan_id, group_type);
                        showOVERALLCOSTAMOUNT(itinerary_plan_id, group_type);
                    }
                });
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/_ajax_latest_default_itineary_routes.php?type=show_form',
                    data: {
                        itinerary_type: itinerary_type,
                        startDateStr: startDateStr,
                        endDateStr: endDateStr,
                        no_of_days: no_of_days,
                        departure_location: departure_location,
                        arrival_location: arrival_location,
                    },
                    success: function(response) {
                        $('#itinerary_routes').html('');
                        $('#itinerary_routes').html(response);
                        //if (!response.success) {
                        //NOT SUCCESS RESPONSE
                        //    TOAST_NOTIFICATION('error', 'Unable to Delete the Vehicle', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        // } else {
                        //SUCCESS RESPOSNE
                        //$('#vehicle_' + vehicle_details_ID).remove();
                        //TOAST_NOTIFICATION('success', 'Vehicle Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

                        // }
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>