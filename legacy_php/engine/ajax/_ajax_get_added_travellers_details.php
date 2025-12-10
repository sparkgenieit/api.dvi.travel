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

$itinerary_plan_ID = $_POST['itinerary_plan_ID'];

if ($_POST['type'] == 'total_adult') :
    // Execute SQL query to retrieve existing adult traveller details
    $sql_existing_adults = "SELECT `traveller_details_ID`, `traveller_name`, `traveller_age`, `traveller_type` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `traveller_type` = '1'";
    $result_existing_adults = sqlQUERY_LABEL($sql_existing_adults);

    // Check if the query executed successfully
    if ($result_existing_adults) {
        $existingAdults = array();

        // Fetch the retrieved data
        while ($row = sqlFETCHARRAY_LABEL($result_existing_adults)) {
            // Add each row to the existingAdults array
            $existingAdults[] = $row;
        }

        // Encode the existingAdults array into JSON format
        $json_existing_adults = json_encode($existingAdults);
    } else {
        // Handle the case where the query fails
        $json_existing_adults = "[]"; // Set empty array as default value
    }

    // Echo the JSON-encoded existing adult traveller details
    echo $json_existing_adults;

elseif ($_POST['type'] == 'total_children') :
    // Execute SQL query to retrieve existing adult traveller details
    $sql_existing_children = "SELECT `traveller_details_ID`, `traveller_name`, `traveller_age`, `traveller_type` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `traveller_type` = '2'";
    $result_existing_children = sqlQUERY_LABEL($sql_existing_children);

    // Check if the query executed successfully
    if ($result_existing_children) {
        $existing_Children = array();

        // Fetch the retrieved data
        while ($row = sqlFETCHARRAY_LABEL($result_existing_children)) {
            // Add each row to the existingchildren array
            $existing_Children[] = $row;
        }

        // Encode the existingchildren array into JSON format
        $json_existing_children = json_encode($existing_Children);
    } else {
        // Handle the case where the query fails
        $json_existing_children = "[]"; // Set empty array as default value
    }

    // Echo the JSON-encoded existing adult traveller details
    echo $json_existing_children;

elseif ($_POST['type'] == 'total_infants') :

    // Execute SQL query to retrieve existing adult traveller details
    $sql_existing_infants = "SELECT `traveller_details_ID`, `traveller_name`, `traveller_age`, `traveller_type` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `traveller_type` = '3'";
    $result_existing_infants = sqlQUERY_LABEL($sql_existing_infants);

    // Check if the query executed successfully
    if ($result_existing_infants) {
        $existingInfants = array();

        // Fetch the retrieved data
        while ($row = sqlFETCHARRAY_LABEL($result_existing_infants)) {
            // Add each row to the existinginfants array
            $existingInfants[] = $row;
        }

        // Encode the existinginfants array into JSON format
        $json_existing_infants = json_encode($existingInfants);
    } else {
        // Handle the case where the query fails
        $json_existing_infants = "[]"; // Set empty array as default value
    }

    // Echo the JSON-encoded existing adult traveller details
    echo $json_existing_infants;

endif;
