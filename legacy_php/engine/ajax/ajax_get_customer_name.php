<?php
// Ensure to include necessary files for database connection

include_once('../../jackus.php');
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    // Get the itinerary_plan_ID from the request
    $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

    // Query to fetch the customer name
    $getstatus_query = sqlQUERY_LABEL("SELECT `customer_name` FROM `dvi_confirmed_itinerary_customer_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `primary_customer` = '1' AND `status` = '1' AND `deleted` = '0'")
        or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

    // Fetch the customer name
    if ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
        echo $getstatus_fetch['customer_name'];  // Return the customer name to the AJAX response
    } else {
        echo "No customer found";  // Optional: handle cases where no customer is found
    }
else:
    echo "Request Ignored";
endif;
