<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/
set_time_limit(0);
ini_set('memory_limit', '256G');
include_once('../../jackus.php');
include_once('../../smtp_functions.php');

$itinerary_session_id = session_id();
/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'incidentalcharge'):

        $errors = [];
        $response = [];

        $itinerary_plan_ID = $_POST['Plan_id'];
        $components_type = $_POST['components_type'];

        $charge_amount = $_POST['incidental_charge_amount'];
        $incidental_reason = trim($_POST['incidental_reason']);


        $itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
        $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');


        if ($getguide == 1 && $gethotspot == 1 && $getactivity == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges') / 3);
            $guide_amount = $agent_margin_charges;
            $hotspot_amount = $agent_margin_charges;
            $activity_amount = $agent_margin_charges;
        elseif ($getguide == 1 && $gethotspot == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges') / 2);
            $guide_amount = $agent_margin_charges;
            $hotspot_amount = $agent_margin_charges;
        elseif ($getguide == 1 && $getactivity == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges') / 2);
            $guide_amount = $agent_margin_charges;
            $activity_amount = $agent_margin_charges;
        elseif ($gethotspot == 1 && $getactivity == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges') / 2);
            $hotspot_amount = $agent_margin_charges;
            $activity_amount = $agent_margin_charges;
        elseif ($getguide == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges'));
            $guide_amount = $agent_margin_charges;
        elseif ($gethotspot == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges'));
            $hotspot_amount = $agent_margin_charges;
        elseif ($getactivity == 1):

            $agent_margin_charges = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges'));
            $activity_amount = $agent_margin_charges;
        endif;


        // $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
        // $divisor = 0;
        // $guide_amount = $hotspot_amount = $activity_amount = 0;

        // // Count the enabled options
        // if ($getguide == 1) $divisor++;
        // if ($gethotspot == 1) $divisor++;
        // if ($getactivity == 1) $divisor++;

        // // Calculate charges if at least one option is enabled
        // if ($divisor > 0) {
        //     $agent_margin_charges = round($agent_margin_charges / $divisor);

        //     if ($getguide == 1) $guide_amount = $agent_margin_charges;
        //     if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
        //     if ($getactivity == 1) $activity_amount = $agent_margin_charges;
        // }

        if ($components_type == 1):
            $confirmed_route_guide_ID = $_POST['guide_name'];
            $component_id = getINCIDENTALEXPENSES_CONFIRMEDID($confirmed_route_guide_ID, $itinerary_plan_ID, 'guide_ID');
            $itinerary_route_id = getINCIDENTALEXPENSES_ROUTEID($confirmed_route_guide_ID, $itinerary_plan_ID, 'route_guide_ID');
            $confirmed_itinerary_incidental_expenses_main_ID = getINCIDENTALEXPENSES_MAINID($itinerary_plan_ID, $components_type, '','confirmed_itinerary_incidental_expenses_main_ID');
            $total_amount = $guide_amount;
        elseif ($components_type == 2):
            $confirmed_route_hotspot_ID = $_POST['hotspot_name'];
            $component_id = getINCIDENTALEXPENSES_CONFIRMEDID($confirmed_route_hotspot_ID, $itinerary_plan_ID, 'hotspot_ID');
            $itinerary_route_id = getINCIDENTALEXPENSES_ROUTEID($confirmed_route_hotspot_ID, $itinerary_plan_ID, 'route_hotspot_ID');
            $confirmed_itinerary_incidental_expenses_main_ID = getINCIDENTALEXPENSES_MAINID($itinerary_plan_ID, $components_type, '','confirmed_itinerary_incidental_expenses_main_ID');
            $total_amount = $hotspot_amount;
        elseif ($components_type == 3):
            $confirmed_route_activity_ID = $_POST['activity_name'];
            $component_id = getINCIDENTALEXPENSES_CONFIRMEDID($confirmed_route_activity_ID, $itinerary_plan_ID, 'activity_ID');
            $itinerary_route_id = getINCIDENTALEXPENSES_ROUTEID($confirmed_route_activity_ID, $itinerary_plan_ID, 'route_activity_ID');
            $confirmed_itinerary_incidental_expenses_main_ID = getINCIDENTALEXPENSES_MAINID($itinerary_plan_ID, $components_type, '','confirmed_itinerary_incidental_expenses_main_ID');
            $total_amount = $activity_amount;
        elseif ($components_type == 4):
            $confirmed_itinerary_plan_hotel_details_ID = $_POST['hotel_name'];
            $component_id = getINCIDENTALEXPENSES_CONFIRMEDID($confirmed_itinerary_plan_hotel_details_ID, $itinerary_plan_ID, 'hotel_ID');
            $main_component_id = getINCIDENTALEXPENSES_CONFIRMEDID($confirmed_itinerary_plan_hotel_details_ID, $itinerary_plan_ID, 'hotel_ID');
            $itinerary_route_id = getINCIDENTALEXPENSES_ROUTEID($confirmed_itinerary_plan_hotel_details_ID, $itinerary_plan_ID, 'route_hotel_ID');
            $confirmed_itinerary_incidental_expenses_main_ID =  getINCIDENTALEXPENSES_MAINID($itinerary_plan_ID, $components_type, $main_component_id,'hotel_vehicle_incidental_expenses_main_ID');
            $hotel_amount = round(getINCIDENTALEXPENSES_MARGIN($confirmed_itinerary_plan_hotel_details_ID, 'margin_hotel'));
            $total_amount = $hotel_amount;
        elseif ($components_type == 5):
            $itinerary_plan_vendor_eligible_ID = $_POST['vendor_name'];
            $component_id = getINCIDENTALEXPENSES_CONFIRMEDID($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'vendor_ID');
            $main_component_id = getINCIDENTALEXPENSES_CONFIRMEDID($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'vendor_ID');
            $confirmed_itinerary_incidental_expenses_main_ID =  getINCIDENTALEXPENSES_MAINID($itinerary_plan_ID, $components_type, $main_component_id,'hotel_vehicle_incidental_expenses_main_ID');
            $vendor_amount = round(getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor'));
            $total_amount = $vendor_amount;
        endif;


        if (empty($charge_amount)) :
            $errors['charge_amount_required'] = true;
        endif;


        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

        
            if ($confirmed_itinerary_incidental_expenses_main_ID == '' || (empty($confirmed_itinerary_incidental_expenses_main_ID))) :

                $total_balance = $total_amount - $charge_amount;

                $arrFields = array('`itinerary_plan_id`', '`component_type`', '`component_id`', '`total_amount`', '`total_payed`', '`total_balance`', '`status`', '`deleted`');

                $arrValues = array("$itinerary_plan_ID", "$components_type", "$main_component_id",  "$total_amount", "$charge_amount", "$total_balance", "1", "0");

                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_incidental_expenses", $arrFields, $arrValues, '')) :

                    $confirmed_itinerary_incidental_expenses_main_ID = sqlINSERTID_LABEL();

                    $arrFields = array('`confirmed_itinerary_incidental_expenses_main_ID`', '`itinerary_plan_id`', '`itinerary_route_id`', '`confirmed_route_guide_ID`', '`confirmed_route_hotspot_ID`', '`confirmed_route_activity_ID`', '`confirmed_itinerary_plan_hotel_details_ID`', '`confirmed_itinerary_plan_vendor_eligible_ID`', '`component_type`', '`component_id`', '`incidental_amount`', '`reason`', '`status`', '`deleted`');

                    $arrValues = array("$confirmed_itinerary_incidental_expenses_main_ID", "$itinerary_plan_ID", "$itinerary_route_id", "$confirmed_route_guide_ID", "$confirmed_route_hotspot_ID", "$confirmed_route_activity_ID", "$confirmed_itinerary_plan_hotel_details_ID", "$itinerary_plan_vendor_eligible_ID", "$components_type", "$component_id", "$charge_amount", "$incidental_reason", "1", "0");

                    if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_incidental_expenses_history", $arrFields, $arrValues, '')) :
                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    endif;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            else:

                $selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_incidental_expenses_main_ID`, `total_amount`, `total_payed`, `total_balance` FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `deleted` = '0' and `confirmed_itinerary_incidental_expenses_main_ID` = $confirmed_itinerary_incidental_expenses_main_ID") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                    $confirmed_itinerary_incidental_expenses_main_ID = $fetch_data['confirmed_itinerary_incidental_expenses_main_ID'];
                    $total_payed = $fetch_data['total_payed'];
                    $total_balance = $fetch_data['total_balance'];
                    $total_payed = $total_payed + $charge_amount;
                    $total_balance = $total_balance - $charge_amount;
                endwhile;

                $main_arrFields = array('`total_payed`', '`total_balance`');
                $main_arrValues = array("$total_payed", "$total_balance");
                $sqlWhere = " `confirmed_itinerary_incidental_expenses_main_ID` = '$confirmed_itinerary_incidental_expenses_main_ID' ";

                if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_incidental_expenses", $main_arrFields, $main_arrValues, $sqlWhere)) :

                    $arrFields = array('`confirmed_itinerary_incidental_expenses_main_ID`', '`itinerary_plan_id`', '`itinerary_route_id`', '`confirmed_route_guide_ID`', '`confirmed_route_hotspot_ID`', '`confirmed_route_activity_ID`', '`confirmed_itinerary_plan_hotel_details_ID`', '`confirmed_itinerary_plan_vendor_eligible_ID`', '`component_type`', '`component_id`', '`incidental_amount`', '`reason`', '`status`', '`deleted`');

                    $arrValues = array("$confirmed_itinerary_incidental_expenses_main_ID", "$itinerary_plan_ID", "$itinerary_route_id", "$confirmed_route_guide_ID", "$confirmed_route_hotspot_ID", "$confirmed_route_activity_ID", "$confirmed_itinerary_plan_hotel_details_ID", "$itinerary_plan_vendor_eligible_ID", "$components_type", "$component_id", "$charge_amount", "$incidental_reason", "1", "0");

                    sqlACTIONS("INSERT", "dvi_confirmed_itinerary_incidental_expenses_history", $arrFields, $arrValues, '');

                    $response['i_result'] = true;
                    $response['result_success'] = true;
                endif;
            endif;
        endif;

        echo json_encode($response);
    elseif ($_GET['type'] == 'incidental_delete_modal') :

        $ID = $_GET['ID'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

?>
        <div class="modal-body py-5">
            <div class="row">
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? </p>
                <div class="text-center mt-4 pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'show_avail_margin_cost') :

        $errors = [];
        $response = [];

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $component_type = $_POST['component_type'];

        $selected_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' and `status` = '1' and `deleted` = '0' and `component_type` = $component_type") or die("#1get_DETAILS: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $total_avail_cost = round($fetch_data['total_balance']);
            endwhile;
        else:
            // Helper function for amount calculation
            function calculateAgentMargin($itinerary_plan_ID, $divisor = 1)
            {
                return round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges') / $divisor);
            }

            // Determine agent margins for different components
            $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
            $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
            $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

            $guide_amount = $hotspot_amount = $activity_amount = 0;

            if ($getguide && $gethotspot && $getactivity) {
                $guide_amount = $hotspot_amount = $activity_amount = calculateAgentMargin($itinerary_plan_ID, 3);
            } elseif ($getguide && $gethotspot) {
                $guide_amount = $hotspot_amount = calculateAgentMargin($itinerary_plan_ID, 2);
            } elseif ($getguide && $getactivity) {
                $guide_amount = $activity_amount = calculateAgentMargin($itinerary_plan_ID, 2);
            } elseif ($gethotspot && $getactivity) {
                $hotspot_amount = $activity_amount = calculateAgentMargin($itinerary_plan_ID, 2);
            } else {
                if ($getguide) $guide_amount = calculateAgentMargin($itinerary_plan_ID);
                if ($gethotspot) $hotspot_amount = calculateAgentMargin($itinerary_plan_ID);
                if ($getactivity) $activity_amount = calculateAgentMargin($itinerary_plan_ID);
            }

            if ($component_type == 1):
                $total_avail_cost = $guide_amount;
            elseif ($component_type == 2):
                $total_avail_cost = $hotspot_amount;
            elseif ($component_type == 3):
                $total_avail_cost = $activity_amount;
            endif;
        endif;



        $response['total_avail_cost'] = $total_avail_cost;

        echo json_encode($response);

    elseif ($_GET['type'] == 'show_hotel_avail_margin_cost') :

        $errors = [];
        $response = [];

        $_cnf_itinerary_hotel_eligible_ID = $_POST['_cnf_itinerary_hotel_eligible_ID'];
        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        $selected_query = sqlQUERY_LABEL("
        SELECT 
            b.total_balance
        FROM 
            dvi_confirmed_itinerary_incidental_expenses_history a
        INNER JOIN 
            dvi_confirmed_itinerary_incidental_expenses b
        ON 
            a.confirmed_itinerary_incidental_expenses_main_ID = b.confirmed_itinerary_incidental_expenses_main_ID
        WHERE 
            a.confirmed_itinerary_plan_hotel_details_ID = '$_cnf_itinerary_hotel_eligible_ID'
            AND a.itinerary_plan_id = '$itinerary_plan_ID'
            AND a.status = '1'
            AND a.deleted = '0'
            AND b.status = '1'
            AND b.deleted = '0'
    ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $total_balance = $fetch_data['total_balance'];
            endwhile;
        else:
            $selected_query = sqlQUERY_LABEL("SELECT `hotel_margin_rate` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `confirmed_itinerary_plan_hotel_details_ID` = '$_cnf_itinerary_hotel_eligible_ID' and `status` = '1' and `deleted` = '0' and `hotel_id` != 0 ") or die("#1get_DETAILS: " . sqlERROR_LABEL());

            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $total_balance = round($fetch_data['hotel_margin_rate']);
            endwhile;
        endif;

        $response['total_avail_cost'] = $total_balance;

        echo json_encode($response);
    elseif ($_GET['type'] == 'show_vendor_avail_margin_cost') :

        $errors = [];
        $response = [];

        $_cnf_itinerary_vendor_eligible_ID = $_POST['_cnf_itinerary_vendor_eligible_ID'];
        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        $selected_query = sqlQUERY_LABEL("
        SELECT 
            b.total_balance
        FROM 
            dvi_confirmed_itinerary_incidental_expenses_history a
        INNER JOIN 
            dvi_confirmed_itinerary_incidental_expenses b
        ON 
            a.confirmed_itinerary_incidental_expenses_main_ID = b.confirmed_itinerary_incidental_expenses_main_ID
        WHERE 
            a.confirmed_itinerary_plan_vendor_eligible_ID = '$_cnf_itinerary_vendor_eligible_ID'
            AND a.itinerary_plan_id = '$itinerary_plan_ID'
            AND a.status = '1'
            AND a.deleted = '0'
            AND b.status = '1'
            AND b.deleted = '0'
    ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($selected_query) > 0) :
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $total_balance = $fetch_data['total_balance'];
        endwhile;
    else:

        $selected_query = sqlQUERY_LABEL("SELECT `vendor_margin_amount` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_vendor_eligible_ID` = '$_cnf_itinerary_vendor_eligible_ID' and `status` = '1' and `deleted` = '0' and `vendor_id` != 0") or die("#1get_DETAILS: " . sqlERROR_LABEL());

        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $total_balance = round($fetch_data['vendor_margin_amount']);
        endwhile;
    endif;

        $response['total_avail_cost'] = $total_balance;

        echo json_encode($response);

    elseif ($_GET['type'] == 'incidental_delete') :

        $errors = [];
        $response = [];

        $ID = $_POST['ID'];

        if ($ID):

            $delete_LIST = sqlQUERY_LABEL("DELETE FROM `dvi_confirmed_itinerary_incidental_expenses_history` WHERE `confirmed_itinerary_incidental_expenses_history_ID` = '$ID' ");
            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);
    endif;

else :
    echo "Request Ignored";
endif;
