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
        $response = [];
        $no_of_route_count = $_POST['_no_of_route_days'];
        $no_of_route_days = $_POST['_no_of_route_days'] - 1;
        $arrival_location = trim($_POST['_arrival_location']);
        $departure_location = trim($_POST['_departure_location']);
        $formattedStartDate = trim($_POST['_formattedStartDate']);
        $formattedEndDate = trim($_POST['_formattedEndDate']);
        $location_id =  getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($arrival_location, $departure_location);

        $select_stored_routes = sqlQUERY_LABEL("SELECT `stored_route_ID` FROM `dvi_stored_routes` WHERE `location_id` = '$location_id' AND `deleted` = '0' AND `status` = '1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($select_stored_routes) > 0) :
            $response['no_routes_found'] = false;
            $matching_routes = []; // To store matching route IDs

            while ($fetch_route_data = sqlFETCHARRAY_LABEL($select_stored_routes)) :

                $stored_route_ID = $fetch_route_data['stored_route_ID'];
                $STOREDROUTE_LOCATION_COUNT = get_STOREDROUTE_LOCATION_COUNT($stored_route_ID);

                if ($STOREDROUTE_LOCATION_COUNT == $no_of_route_days):
                    $matching_routes[] = $stored_route_ID; // Add to matching routes
                endif;
            endwhile;

            if (!empty($matching_routes)):
                $response['no_matching_routes_found'] = false;

                // Fetch a random route from the matching routes
                $selected_stored_route_ID = $matching_routes[array_rand($matching_routes)];
                $response['route_details'] =
                    '<table id="route_details_LIST" class="table table-borderless" style="width:100%">
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
                                <tbody id="route_details_tbody">';

                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `stored_route_location_ID`, `stored_route_id`, `route_location_id`, `route_location_name` FROM `dvi_stored_route_location_details` WHERE `deleted` = '0' and `status` = '1' and `stored_route_id` = '$selected_stored_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);

                if ($select_itinerary_route_details_count > 0):
                    $itinerary_route_date =  str_replace("-", "/", $formattedStartDate);
                    while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)):
                        $route_day_counter++;
                        $stored_route_location_ID = $fetch_itineary_route_data['stored_route_location_ID'];
                        if ($route_day_counter == 1):
                            $location_name = $arrival_location;
                            $next_visiting_location = $fetch_itineary_route_data['route_location_name'];
                        else:
                            $location_name = $next_visiting_location;
                            $next_visiting_location = $fetch_itineary_route_data['route_location_name'];
                        endif;

                        $response['route_details'] .= '<tr id="route_details_' . $stored_route_location_ID . '" class="route_details" data-itinerary_route_ID="" data-day-no="' . $route_day_counter . '">
                                        <td class="day text-start" width="8%">DAY ' . $route_day_counter . '</td>
                                        <td class="date" id="route_date_' . $route_day_counter . '">' . $itinerary_route_date . '</td>
                                        <td>
                                            <input type="text" name="source_location[]" id="source_location_' . $route_day_counter . '" class="bg-body form-select form-control location" value="' . htmlspecialchars($location_name) . '">
                                            <input type="hidden" name="hidden_itinerary_route_ID[]" value="" hidden>
                                            <input type="hidden" id="itinerary_route_date_' . $route_day_counter . '" name="hidden_itinerary_route_date[]" value="' . $itinerary_route_date . '" hidden>
                                        </td>
                                        <td>
                                            <select name="next_visiting_location[]" id="next_visiting_location_' . $route_day_counter . '" class="next_visiting_location text-start form-select form-control location" required>
                                                <option value="' . htmlspecialchars($next_visiting_location) . '" ' . (($departure_location == $next_visiting_location) ? 'selected' : '') . '>' . htmlspecialchars($next_visiting_location) . '</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(\'' . $route_day_counter . '\',\'\', \'\')"><i class="ti ti-route ti-tada-hover"></i></button>
                                        </td>
                                        <td>
                                            <label class="switch switch-sm">
                                                <input type="checkbox" ' . (($direct_to_next_visiting_place == 1) ? 'checked' : '') . ' id="direct_destination_visit_' . $route_day_counter . '" name="direct_destination_visit[' . $route_day_counter . '][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span>
                                            </label>
                                        </td>
                                        <td></td>
                                    </tr>';
                        if ($itinerary_route_date):
                            $itinerary_route_date = str_replace("/", "-", $itinerary_route_date);
                            $itinerary_route_date = date('d/m/Y', strtotime($itinerary_route_date . ' +1 day'));
                        endif;

                    endwhile;

                    //ROUTE FOR LAST DAY
                    $route_day_counter++;
                    if ($route_day_counter == $no_of_route_count):
                        $location_name = $next_visiting_location;
                        $next_visiting_location = $departure_location;
                    endif;
                    $response['route_details'] .=
                        '<tr id="route_details_" class="route_details" data-itinerary_route_ID="" data-day-no="' . $route_day_counter . '">
                                        <td class="day text-start" width="8%">DAY ' . $route_day_counter . '</td>
                                        <td class="date" id="route_date_' . $route_day_counter . '">' . $itinerary_route_date . '</td>
                                        <td>
                                            <input type="text" name="source_location[]" id="source_location_' . $route_day_counter . '" class="bg-body form-select form-control location" value="' . htmlspecialchars($location_name) . '">
                                            <input type="hidden" name="hidden_itinerary_route_ID[]" value="" hidden>
                                            <input type="hidden" id="itinerary_route_date_' . $route_day_counter . '" name="hidden_itinerary_route_date[]" value="' . $itinerary_route_date . '" hidden>
                                        </td>
                                        <td>
                                            <select name="next_visiting_location[]" id="next_visiting_location_' . $route_day_counter . '" class="next_visiting_location text-start form-select form-control location" required>
                                                <option value="' . htmlspecialchars($next_visiting_location) . '" ' . (($departure_location == $next_visiting_location) ? 'selected' : '') . '>' . htmlspecialchars($next_visiting_location) . '</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(\'' . $route_day_counter . '\',\'\', \'\')"><i class="ti ti-route ti-tada-hover"></i></button>
                                        </td>
                                        <td>
                                            <label class="switch switch-sm">
                                                <input type="checkbox" ' . (($direct_to_next_visiting_place == 1) ? 'checked' : '') . ' id="direct_destination_visit_' . $route_day_counter . '" name="direct_destination_visit[' . $route_day_counter . '][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span>
                                            </label>
                                        </td>
                                        <td></td>
                                    </tr>';
                else:
                    $response['route_details'] .= '<tr><td colspan="7">No default Route Suggestions Available</td></tr>';
                endif;

                $response['route_details'] .= '</tbody></table>';

            else:
                $response['no_matching_routes_found'] = true;
            endif;
        else:
            $response['no_routes_found'] = true;
        endif;


        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
