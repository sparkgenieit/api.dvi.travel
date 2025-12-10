<?php
include_once('../../jackus.php');
$itinerary_session_id = session_id();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    
    if ($_GET['type'] == 'show_form') {
        
        $response = [];
        $no_of_route_days = $_POST['_no_of_route_days'];
        $no_of_route_days = $no_of_route_days - 1;
        $arrival_location = trim($_POST['_arrival_location']);
        $departure_location = trim($_POST['_departure_location']);
        $formattedStartDate = trim($_POST['_formattedStartDate']);
        $formattedEndDate = trim($_POST['_formattedEndDate']);
        $location_id = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($arrival_location, $departure_location);

        $select_stored_routes = sqlQUERY_LABEL("SELECT `stored_route_ID` FROM `dvi_stored_routes` WHERE `location_id` = '$location_id' AND `deleted` = '0' AND `status` = '1' AND `no_of_nights`= '$no_of_route_days'");

        if (sqlNUMOFROW_LABEL($select_stored_routes) > 0) {
            $response['no_routes_found'] = false;
            $matching_routes = [];

            while ($fetch_route_data = sqlFETCHARRAY_LABEL($select_stored_routes)) {
                $stored_route_ID = $fetch_route_data['stored_route_ID'];
                $matching_routes[] = $stored_route_ID;
                /*$STOREDROUTE_LOCATION_COUNT = get_STOREDROUTE_LOCATION_COUNT($stored_route_ID);
                if ($STOREDROUTE_LOCATION_COUNT >= $no_of_route_days) {
                    $matching_routes[] = $stored_route_ID;
                }*/
            }

            if (!empty($matching_routes)) {
                $response['no_matching_routes_found'] = false;
                $selected_routes = array_slice($matching_routes, 0, 5);
                $response['tabs'] = '';
                $response['tabContents'] = '';
                $tab_index = 0;

                foreach ($selected_routes as $selected_stored_route_ID) {
                    $tab_index++;
                    $is_active = ($tab_index == 1) ? 'active' : '';

                    // Generate Tab Header
                    $response['tabs'] .= '<li class="nav-item">
                        <a class="nav-link ' . $is_active . '" id="route-tab-' . $tab_index . '" data-bs-toggle="tab" href="#route-' . $tab_index . '" role="tab" aria-controls="route-' . $tab_index . '" aria-selected="' . ($is_active ? 'true' : 'false') . '">
                            Route ' . $tab_index . '
                        </a>
                    </li>';

                    // Start Tab Content
                    $tabContent = '<div class="tab-pane fade ' . ($is_active ? 'show active' : '') . '" id="route-' . $tab_index . '" role="tabpanel" aria-labelledby="route-tab-' . $tab_index . '">';

                    // Build the route details table for this route
                    $tabContent .= '<table id="custom_route_details_LIST_' . $tab_index . '" class="table table-borderless" style="width:100%">
                        <thead class="table-header-color">
                            <tr>
                                <th class="text-start" width="8%">DAY</th>
                                <th class="text-start">DATE</th>
                                <th class="text-start">SOURCE DESTINATION</th>
                                <th class="text-start">NEXT DESTINATION</th>
                                <th class="text-start">VIA ROUTE</th>
                                <th class="text-start" colspan="2">DIRECT DESTINATION VISIT</th>
                                <th style="width: 0; padding: 0px;"></th>
                            </tr>
                        </thead>
                        <tbody id="custom_route_details_tbody_' . $tab_index . '">';

                    // Fetch route details
                    $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `stored_route_location_ID`, `route_location_name` FROM `dvi_stored_route_location_details` WHERE `deleted` = '0' AND `status` = '1' AND `stored_route_id` = '$selected_stored_route_ID' LIMIT $no_of_route_days");

                    if (sqlNUMOFROW_LABEL($select_itinerary_route_details) > 0) {
                        $itinerary_route_date = str_replace("-", "/", $formattedStartDate);
                        $route_day_counter = 0;
                        $next_visiting_location = '';

                        $source_location = $arrival_location;

                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) {
                            $route_day_counter++;
                            $stored_route_location_ID = $fetch_itinerary_route_data['stored_route_location_ID'];
                            $route_location_name = $fetch_itinerary_route_data['route_location_name'];

                            /* if ($route_day_counter == 1) {
                                $location_name = $arrival_location;
                                $next_visiting_location = $route_location_name;
                            } else {
                                $location_name = $next_visiting_location;
                                $next_visiting_location = $route_location_name;
                            } */

                            $next_visiting_location = $route_location_name;

                            // Build each table row
                            $tabContent .= '<tr id="route_details_' . $tab_index . '_' . $stored_route_location_ID . '" class="route_details" data-itinerary_route_ID="" data-day-no="' . $route_day_counter . '">
                                <td class="day text-start" width="8%">DAY ' . $route_day_counter . '</td>
                                <td class="date" id="route_date_' . $tab_index . '_' . $route_day_counter . '">' . $itinerary_route_date . '</td>
                                <td>
                                    <input type="text" name="source_location_' . $tab_index . '[]" id="source_location_' . $tab_index . '_' . $route_day_counter . '" class="bg-body form-select form-control location" value="' . htmlspecialchars($source_location) . '">
                                    <input type="hidden" name="hidden_itinerary_route_ID_' . $tab_index . '[]" value="' . $itinerary_route_date . '">
                                    <input type="hidden" id="itinerary_route_date_' . $tab_index . '_' . $route_day_counter . '" name="hidden_itinerary_route_date_' . $tab_index . '[]" value="' . $itinerary_route_date . '">
                                </td>
                                <td>
                                    <select name="next_visiting_location_' . $tab_index . '[]" id="next_visiting_location_' . $tab_index . '_' . $route_day_counter . '" class="next_visiting_location text-start form-select form-control location" required>
                                        <option value="' . htmlspecialchars($next_visiting_location) . '" ' . (($departure_location == $next_visiting_location) ? 'selected' : '') . '>' . htmlspecialchars($next_visiting_location) . '</option>
                                    </select>
                                </td>
                                <td>
                                      <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addDEFAULTVIAROUTE(' . $route_day_counter . ', \'\', \'\', ' . $tab_index . ')"><i class="ti ti-route ti-tada-hover"></i></button>
                                </td>
                                <td>
                                    <label class="switch switch-sm">
                                        <input type="checkbox" id="direct_destination_visit_' . $tab_index . '_' . $route_day_counter . '" name="direct_destination_visit_' . $tab_index . '[' . $route_day_counter . '][]" class="switch-input">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                    </label>
                                </td>
                                <td></td>
                            </tr>';

                            // Update date for the next day
                            if ($itinerary_route_date) {
                                $itinerary_route_date = date('d/m/Y', strtotime(str_replace("/", "-", $itinerary_route_date) . ' +1 day'));
                            }

                            $source_location = $next_visiting_location;
                        }

                        // Handle last day's route if necessary
                        $route_day_counter++;
                        if ($route_day_counter == $no_of_route_days + 1) {
                            $location_name = $next_visiting_location;
                            $next_visiting_location = $departure_location;
                            $tabContent .= '<tr id="route_details_' . $tab_index . '_last" class="route_details" data-itinerary_route_ID="" data-day-no="' . $route_day_counter . '">
                                <td class="day text-start" width="8%">DAY ' . $route_day_counter . '</td>
                                <td class="date" id="route_date_' . $tab_index . '_' . $route_day_counter . '">' . $itinerary_route_date . '</td>
                                <td>
                                    <input type="text" name="source_location_' . $tab_index . '[]" id="source_location_' . $tab_index . '_' . $route_day_counter . '" class="bg-body form-select form-control location" value="' . htmlspecialchars($location_name) . '">
                                    <input type="hidden" name="hidden_itinerary_route_ID_' . $tab_index . '[]" value="">
                                    <input type="hidden" id="itinerary_route_date_' . $tab_index . '_' . $route_day_counter . '" name="hidden_itinerary_route_date_' . $tab_index . '[]" value="' . $itinerary_route_date . '">
                                </td>
                                <td>
                                    <select name="next_visiting_location_' . $tab_index . '[]" id="next_visiting_location_' . $tab_index . '_' . $route_day_counter . '" class="next_visiting_location text-start form-select form-control location" required>
                                        <option value="' . htmlspecialchars($next_visiting_location) . '" selected>' . htmlspecialchars($next_visiting_location) . '</option>
                                    </select>
                                </td>
                                <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addDEFAULTVIAROUTE(' . $route_day_counter . ', \'\', \'\', ' . $tab_index . ')"><i class="ti ti-route ti-tada-hover"></i></button>

                                </td>
                                <td>
                                    <label class="switch switch-sm">
                                        <input type="checkbox" id="direct_destination_visit_' . $tab_index . '_' . $route_day_counter . '" name="direct_destination_visit_' . $tab_index . '[' . $route_day_counter . '][]" class="switch-input">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                    </label>
                                </td>
                                <td></td>
                            </tr>';
                        }
                    } else {
                        $tabContent .= '<tr><td colspan="7">No route details available for this route.</td></tr>';
                    }

                    $tabContent .= '</tbody></table>';

                    // Add "Add Day" button
                    $tabContent .= '<div class="text-start">
                        <button type="button" id="route_add_days_btn_' . $tab_index . '" class="btn btn-outline-dribbble btn-sm addNextDayPlan" onclick="addDayToRoute(' . $tab_index . ')" data-tab-index="' . $tab_index . '">
    <i class="ti ti-plus ti-tada-hover"></i>Add Day
</button>

                    </div>';

                    $tabContent .= '</div>';
                    $response['tabContents'] .= $tabContent;
                }
            } else {
                $response['no_matching_routes_found'] = true;
            }
        } else {
            $response['no_routes_found'] = true;
            // Cast values for safety
            $location_id = (int)$location_id;
            $no_of_route_days = (int)$no_of_route_days;

            // === CASE 1: Check if routes exist exactly matching given days ===
            $check_exact_routes = sqlQUERY_LABEL("
                SELECT COUNT(`stored_route_ID`) AS EXACT_COUNT
                FROM `dvi_stored_routes`
                WHERE `location_id` = '$location_id'
                AND `deleted` = '0'
                AND `status` = '1'
                AND `no_of_nights` = '$no_of_route_days'
            ") or die("#1-UNABLE_TO_COLLECT_DATA: " . sqlERROR_LABEL());

            $fetch_exact_routes = sqlFETCHARRAY_LABEL($check_exact_routes);
            $exact_count = (int)$fetch_exact_routes['EXACT_COUNT'];

            // === CASE 2: Check if any routes exist with more nights ===
            $check_greater_routes = sqlQUERY_LABEL("
                SELECT COUNT(`stored_route_ID`) AS GREATER_COUNT, MIN(`no_of_nights`) AS MIN_NIGHTS
                FROM `dvi_stored_routes`
                WHERE `location_id` = '$location_id'
                AND `deleted` = '0'
                AND `status` = '1'
                AND `no_of_nights` > '$no_of_route_days'
            ") or die("#2-UNABLE_TO_COLLECT_DATA: " . sqlERROR_LABEL());

            $fetch_greater_routes = sqlFETCHARRAY_LABEL($check_greater_routes);
            $greater_count = (int)$fetch_greater_routes['GREATER_COUNT'];
            $min_nights = (int)$fetch_greater_routes['MIN_NIGHTS'];

            // === LOGIC HANDLING ===
            if ($exact_count > 0) {

                // Exact match found
                $response['no_routes_message'] = "Routes are available for exactly $no_of_route_days nights.";

            } elseif ($greater_count > 0) {

                // No exact match, but longer routes available
                // You could also show the minimum available nights (like 2 in your example)
                $response['no_routes_message'] = "Routes are not available for $no_of_route_days night(s), but available for the minimum no_of_nights: $min_nights and above.";

            } else {

                // No exact or longer routes — check for shorter ones
                $select_shorter_routes = sqlQUERY_LABEL("
                    SELECT DISTINCT `no_of_nights`
                    FROM `dvi_stored_routes`
                    WHERE `location_id` = '$location_id'
                    AND `deleted` = '0'
                    AND `status` = '1'
                    AND `no_of_nights` < '$no_of_route_days'
                    ORDER BY `no_of_nights` ASC
                ") or die("#3-UNABLE_TO_COLLECT_DATA: " . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($select_shorter_routes) > 0) {
                    $available_nights = [];
                    while ($fetch_shorter = sqlFETCHARRAY_LABEL($select_shorter_routes)) {
                        $available_nights[] = $fetch_shorter['no_of_nights'];
                    }

                    $response['no_routes_message'] = "Routes are not available for $no_of_route_days nights, but available for the following no_of_nights: " . implode(', ', $available_nights) . ".";

                } else {
                    $response['no_routes_message'] = "No routes are available for this location.";
                }
            }


        }

        echo json_encode($response);
    }
} else {
    echo "Request Ignored";
}
