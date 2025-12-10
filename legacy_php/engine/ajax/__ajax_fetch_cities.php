<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

    if (isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['term'])) {

        $state_name = $_POST['state_name'];
        $search_term = $_POST['term']; // Get the search term typed by the user

        $options = [];

        // Fetch the state ID based on state name
        $get_state_ID = sqlFETCHARRAY_LABEL(sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE `id` = '$state_name'"))['id'];

        // Fetch cities that match the search term and belong to the selected state
        $selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_cities` WHERE `deleted` = '0' AND `state_id` = '$get_state_ID' AND `name` LIKE '%$search_term%' ORDER BY `name` ASC") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) {
            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
                $options[] = [
                    "id" => $fetch_data['id'],
                    "name" => $fetch_data['name'] // Send only the city name to the frontend
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($options);
    } else {
        echo json_encode(["error" => "Missing parameters"]);
    }
} else {
    echo "Request Ignored";
}
