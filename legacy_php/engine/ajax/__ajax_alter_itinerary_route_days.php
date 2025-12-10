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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    //DELETE OPERATION - CONDITION BASED

    if ($_GET['type'] == 'show_modal') :

        $ID = $_GET['ID'];
        $total_no_of_days = $_GET['total_no_of_days'];
        $itineraryRouteID = $_GET['itineraryRouteID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
        $total_no_of_days = $validation_globalclass->sanitize($total_no_of_days);
        $itineraryRouteID = $validation_globalclass->sanitize($itineraryRouteID);

?>
        <div class="row">

            <div class="text-center">
                <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>

            <p class="text-center">Total No of days will be exceeded. </p>
            <?php if ($itineraryRouteID) : ?>
                <p class="text-center">Do you really want to increment the no of days? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">Close</span>
                    </button>
                    <button type="button" onclick="confirmALTERDAYS('<?= $ID; ?>','<?= $itineraryRouteID ?>','<?= $total_no_of_days ?>','UPDATE_ROUTE_AND_PLAN');" class="btn btn-danger">Yes</button>
                </div>
            <?php endif; ?>

        </div>
<?php
    elseif ($_GET['type'] == 'confirm_alter_days') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        $itinerary_route_ID  = $_POST['itinerary_route_ID'];
        $NO_OF_DAYS =  $_POST['NO_OF_DAYS'];
        $TYPE =  $_POST['TYPE'];

        if ($itinerary_route_ID != '' && $itinerary_route_ID != 0 && $itinerary_route_ID != 'undefined') :
            if ($TYPE == 'UPDATE_ROUTE_AND_PLAN') :

                $alter_days = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_details` SET `no_of_days` = '$NO_OF_DAYS', `no_of_nights` = `no_of_nights`+1,`trip_end_date_and_time` = DATE_ADD(`trip_end_date_and_time`, INTERVAL   1 DAY),`updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `itinerary_plan_ID` = '$_ID'") or die("#1-UNABLE_TO_ALTER_DAYS:" . sqlERROR_LABEL());

                if ($alter_days == '1') :

                    $alter_route_days = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_details` SET `no_of_days` = `no_of_days`+1 ,`itinerary_route_date` = DATE_ADD(`itinerary_route_date`, INTERVAL   1 DAY), `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_ALTER_DAYS:" . sqlERROR_LABEL());

                    if ($alter_route_days == '1') :
                        $response['result'] = true;
                    else :
                        $response['result'] = false;
                    // $response['response_error'] = true;
                    endif;
                endif;
            elseif ($TYPE == 'UPDATE_ROUTE') :

                $alter_days = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_details` SET `no_of_days` = '$NO_OF_DAYS' ,`updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_ALTER_DAYS:" . sqlERROR_LABEL());

                if ($alter_days == '1') :
                    $response['result'] = true;
                else :
                    $response['result'] = false;
                // $response['response_error'] = true;
                endif;

            endif;
        else :

            $alter_days = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_details` SET `no_of_days` = '$NO_OF_DAYS', `no_of_nights` = `no_of_nights`+1,`updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `itinerary_plan_ID` = '$_ID'") or die("#1-UNABLE_TO_ALTER_DAYS:" . sqlERROR_LABEL());

            if ($alter_days) :
                $response['result'] = true;
            else :
                $response['result'] = false;
            endif;
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
?>