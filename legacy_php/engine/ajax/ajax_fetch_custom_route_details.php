<?php
// Assuming this is your PHP endpoint to fetch custom route details
include_once('../../jackus.php');
$itinerary_session_id = session_id();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    if ($_GET['type'] == 'fetch_custom_route') {
        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'");
        $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);

        $customRouteDetails = [];
        if ($select_itinerary_route_details_count > 0) {
            while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) {
                $customRouteDetails[] = [
                    'itinerary_route_ID' => $fetch_itineary_route_data['itinerary_route_ID'],
                    'location_name' => $fetch_itineary_route_data['location_name'],
                    'itinerary_route_date' => $fetch_itineary_route_data['itinerary_route_date'],
                    'direct_to_next_visiting_place' => $fetch_itineary_route_data['direct_to_next_visiting_place'],
                    'next_visiting_location' => $fetch_itineary_route_data['next_visiting_location'],
                ];
            }
        }

        echo json_encode($customRouteDetails);
    }
}
?>
