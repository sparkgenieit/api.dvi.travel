<?php
include_once('../../jackus.php');
$itinerary_session_id = session_id();

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

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
        /* $TOTAL_DISTANCE = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');
        $distance_limit = getGLOBALSETTING('itinerary_distance_limit'); */

        $select_stored_routes = sqlQUERY_LABEL("SELECT `stored_route_ID` FROM `dvi_stored_routes` WHERE `location_id` = '$location_id' AND `deleted` = '0' AND `status` = '1'");

        if (sqlNUMOFROW_LABEL($select_stored_routes) > 0) {
            $response['no_routes_found'] = false;
            $matching_routes = [];

            while ($fetch_route_data = sqlFETCHARRAY_LABEL($select_stored_routes)) {
                $stored_route_ID = $fetch_route_data['stored_route_ID'];
                $STOREDROUTE_LOCATION_COUNT = get_STOREDROUTE_LOCATION_COUNT($stored_route_ID);
                if ($STOREDROUTE_LOCATION_COUNT >= $no_of_route_days) {
                    $matching_routes[] = $stored_route_ID;
                }
            }

            /* print_r($matching_routes); */

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
                    $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `stored_route_location_ID`, `route_location_name` FROM `dvi_stored_route_location_details` WHERE `deleted` = '0' AND `status` = '1' AND `stored_route_id` = '$selected_stored_route_ID' ORDER BY RAND() LIMIT $no_of_route_days");

                    if (sqlNUMOFROW_LABEL($select_itinerary_route_details) > 0) {
                        $itinerary_route_date = str_replace("-", "/", $formattedStartDate);
                        $route_day_counter = 0;
                        $next_visiting_location = '';

                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) {
                            $route_day_counter++;
                            $stored_route_location_ID = $fetch_itinerary_route_data['stored_route_location_ID'];
                            $route_location_name = $fetch_itinerary_route_data['route_location_name'];

                            if ($route_day_counter == 1) {
                                $location_name = $arrival_location;
                                $next_visiting_location = $route_location_name;
                            } else {
                                $location_name = $next_visiting_location;
                                $next_visiting_location = $route_location_name;
                            }

                            // Build each table row
                            $tabContent .= '<tr id="route_details_' . $tab_index . '_' . $stored_route_location_ID . '" class="route_details" data-itinerary_route_ID="" data-day-no="' . $route_day_counter . '">
                                <td class="day text-start" width="8%">DAY ' . $route_day_counter . '</td>
                                <td class="date" id="route_date_' . $tab_index . '_' . $route_day_counter . '">' . $itinerary_route_date . '</td>
                                <td>
                                    <input type="text" name="source_location_' . $tab_index . '[]" id="source_location_' . $tab_index . '_' . $route_day_counter . '" class="bg-body form-select form-control location" value="' . htmlspecialchars($location_name) . '">
                                    <input type="hidden" name="hidden_itinerary_route_ID_' . $tab_index . '[]" value="' . $itinerary_route_date . '">
                                    <input type="hidden" id="itinerary_route_date_' . $tab_index . '_' . $route_day_counter . '" name="hidden_itinerary_route_date_' . $tab_index . '[]" value="' . $itinerary_route_date . '">
                                </td>
                                <td>
                                    <select name="next_visiting_location_' . $tab_index . '[]" id="next_visiting_location_' . $tab_index . '_' . $route_day_counter . '" class="next_visiting_location text-start form-select form-control location" required>
                                        <option value="' . htmlspecialchars($next_visiting_location) . '" ' . (($departure_location == $next_visiting_location) ? 'selected' : '') . '>' . htmlspecialchars($next_visiting_location) . '</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(' . $route_day_counter . ', \'\', \'\')"><i class="ti ti-route ti-tada-hover"></i></button>
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
                                    <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(' . $route_day_counter . ', \'\', \'\')"><i class="ti ti-route ti-tada-hover"></i></button>
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

                    $tabContent .= '</tbody></table></div>';
                    $response['tabContents'] .= $tabContent;
                }
            } else {
                $response['no_matching_routes_found'] = true;
            }
        } else {
            $response['no_routes_found'] = true;
        }

        echo json_encode($response);
    }
} else {
    echo "Request Ignored";
}
