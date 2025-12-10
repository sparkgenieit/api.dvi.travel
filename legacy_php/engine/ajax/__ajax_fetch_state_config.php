<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

    if ($_GET['type'] == 'fetch_numbers') {
        // Retrieve and sanitize input data
        $state_id = isset($_POST['state_id']) ? intval($_POST['state_id']) : 0;
        $country_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;

        // Initialize filter for excluding a specific state if needed
        $filter_by_state = "";
        if ($state_id) {
            $filter_by_state = " and `id` = '$state_id' ";
        }

        // Query the database to fetch the latest numbers for the given country and excluding the current state if specified
        $query = "SELECT `vehicle_onground_support_number`, `vehicle_escalation_call_number` FROM `dvi_states` WHERE `deleted` = '0' and `country_id` = '$country_id' {$filter_by_state} ORDER BY `id` DESC LIMIT 0,1";
        $result = sqlQUERY_LABEL($query) or die("#1-fetch_numbers: " . sqlERROR_LABEL());


        // Initialize default values for the numbers
        $vehicle_onground_support_number = '';
        $vehicle_escalation_call_number = '';

        // Check if any rows were returned
        if (sqlNUMOFROW_LABEL($result) > 0) {
            // Fetch the data
            while ($row = sqlFETCHARRAY_LABEL($result)) {
                $vehicle_onground_support_number = $row['vehicle_onground_support_number'];
                $vehicle_escalation_call_number = $row['vehicle_escalation_call_number'];
            }
        } else {
            // Default values if no data is found
            $vehicle_onground_support_number = '';
            $vehicle_escalation_call_number = '';
        }

        // Prepare the response data
        $response = array(
            'vehicle_onground_support_number' => $vehicle_onground_support_number,
            'vehicle_escalation_call_number' => $vehicle_escalation_call_number
        );

        // Output the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    echo "Request Ignored";
}
