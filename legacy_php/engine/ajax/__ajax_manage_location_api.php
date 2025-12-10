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

set_time_limit(0);
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'add') :
        $errors = [];
        $response = [];

        if (count($_POST['source_location']) == 0) :
            $errors['source_location_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter source location !!!</div>';
        elseif (count($_POST['destination_location']) == 0) :
            $errors['destination_location_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter destination location !!!</div>';
        endif;
        //SANITIZE
        $source_location = $_POST['source_location'];
        $destination_location = $_POST['destination_location'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $success = true;

            $all_combinations = [];

            // Source and Destination combination
            foreach ($source_location as $source) {
                foreach ($destination_location as $destination) {
                    $all_combinations[] = ['source' => $source, 'destination' => $destination];
                }
            }

            // Source and Source combination
            foreach ($source_location as $source1) {
                foreach ($source_location as $source2) {
                    $all_combinations[] = ['source' => $source1, 'destination' => $source2];
                }
            }

            // Destination and Destination combination
            foreach ($destination_location as $destination1) {
                foreach ($destination_location as $destination2) {
                    $all_combinations[] = ['source' => $destination1, 'destination' => $destination2];
                }
            }

            // Same Source and Destination combination
            foreach ($source_location as $source) {
                $all_combinations[] = ['source' => $source, 'destination' => $source];
            }

            // Same Destination and Destination combination
            foreach ($destination_location as $destination) {
                $all_combinations[] = ['source' => $destination, 'destination' => $destination];
            }
            //echo "<pre>";
            //print_r($all_combinations);
            // die;


            foreach ($all_combinations as $combination) {
                $selected_source_location = $combination['source'];
                $selected_destination_location = $combination['destination'];

                $selected_source_location = addslashes($selected_source_location);
                $selected_destination_location = addslashes($selected_destination_location);

                $check_source_to_des_already_exists = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location`='$selected_source_location' AND  `destination_location` ='$selected_destination_location' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($check_source_to_des_already_exists) == 0) :

                    $distance_duration = getDistanceAndDuration($selected_source_location, $selected_destination_location, $travelMode = 'driving', $GOOGLEMAP_API_KEY);
                    $distance = $distance_duration['distance'];
                    $duration = $distance_duration['duration'];

                    $selected_source_location_latitude_longitude_city = getPlaceLatLng($selected_source_location, $GOOGLEMAP_API_KEY);
                    $selected_source_location_latitude = $selected_source_location_latitude_longitude_city['latitude'];
                    $selected_source_location_longitude = $selected_source_location_latitude_longitude_city['longitude'];
                    $selected_source_location_city = $selected_source_location_latitude_longitude_city['city'];
                    $selected_source_location_state = $selected_source_location_latitude_longitude_city['state'];

                    $selected_destination_location_latitude_longitude_city = getPlaceLatLng($selected_destination_location, $GOOGLEMAP_API_KEY);
                    $selected_destination_location_latitude =
                        $selected_destination_location_latitude_longitude_city['latitude'];
                    $selected_destination_location_longitude = $selected_destination_location_latitude_longitude_city['longitude'];
                    $selected_destination_location_city = $selected_destination_location_latitude_longitude_city['city'];
                    $selected_destination_location_state = $selected_destination_location_latitude_longitude_city['state'];

                    // echo $selected_source_location . "--" . $selected_destination_location . "<br><br>";

                    //echo $distance . "--" . $duration . "--" . $selected_source_location_latitude . "--" . $selected_source_location_longitude . "--" . $selected_source_location_city . "--" . $selected_source_location_state . "--" . $selected_destination_location_latitude . "--" . $selected_destination_location_longitude . "--" . $selected_destination_location_city . "--" . $selected_destination_location_state . "<br><br><br><br>";

                    //SOURCE TO DESTINATION
                    $arrFields = array('`source_location`', '`source_location_lattitude`', '`source_location_longitude`', '`source_location_city`', '`source_location_state`', '`destination_location`', '`destination_location_lattitude`', '`destination_location_longitude`', '`destination_location_city`', '`destination_location_state`', '`distance`', '`duration`', '`createdby`', '`status`');

                    $arrValues_src_to_des = array("$selected_source_location", "$selected_source_location_latitude", "$selected_source_location_longitude", "$selected_source_location_city", "$selected_source_location_state", "$selected_destination_location", "$selected_destination_location_latitude", "$selected_destination_location_longitude", "$selected_destination_location_city", "$selected_destination_location_state", "$distance", "$duration", "$logged_user_id", "1");

                    //echo "source to destination<br>";
                    //print_r($arrValues_src_to_des);

                    if (sqlACTIONS("INSERT", "dvi_stored_locations", $arrFields, $arrValues_src_to_des, '')) :
                        //SUCCESS

                        if ($selected_source_location != $selected_destination_location) :
                            //DESTINATION  TO SOURCE 

                            $check_des_to_source_already_exists = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location`='$selected_destination_location' AND  `destination_location` ='$selected_source_location' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($check_des_to_source_already_exists) == 0) :

                                $arrValues_des_to_src = array("$selected_destination_location", "$selected_destination_location_latitude", "$selected_destination_location_longitude", "$selected_destination_location_city", "$selected_destination_location_state", "$selected_source_location", "$selected_source_location_latitude", "$selected_source_location_longitude", "$selected_source_location_city", "$selected_source_location_state", "$distance", "$duration", "$logged_user_id", "1");

                                //echo "destination to source<br>";
                                // print_r($arrValues_des_to_src);

                                if (sqlACTIONS("INSERT", "dvi_stored_locations", $arrFields, $arrValues_des_to_src, '')) :
                                //SUCCESS
                                else :
                                // $success = false;
                                endif;
                            endif;
                        endif;
                    else :
                        $success = false;
                    endif;

                endif;
            }
            //die;
            if ($success == true) :
                //SUCCESS
                $response['result'] = true;
            else :
                $response['result'] = false;
            endif;


        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'add_via_route') :
        $errors = [];
        $response = [];

        if (count($_POST['via_route']) == 0) :
            $errors['via_route_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Via Route !!!</div>';
        endif;
        //SANITIZE
        $via_route = $_POST['via_route'];
        $hidden_location_id = $_POST['hidden_location_id'];
        $hidden_source_location = $_POST['hidden_source_location'];
        $hidden_source_location_lattitude = $_POST['hidden_source_location_lattitude'];
        $hidden_source_location_longitude = $_POST['hidden_source_location_longitude'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $success = true;

            for ($i = 0; $i < count($via_route); $i++) :
                $selected_via_route = trim($via_route[$i]);

                $check_via_route_already_exists = sqlQUERY_LABEL("SELECT `via_route_location_ID` FROM `dvi_stored_location_via_routes` WHERE `via_route_location`='$selected_via_route' AND  `location_id` ='$hidden_location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($check_via_route_already_exists) == 0) :

                    $distance_duration = getDistanceAndDuration($hidden_source_location, $selected_via_route, $travelMode = 'driving', $GOOGLEMAP_API_KEY);
                    $distance_from_source_to_via_route = $distance_duration['distance'];
                    $duration_from_source_to_via_route = $distance_duration['duration'];

                    $selected_via_route_location_latitude_longitude_city = getPlaceLatLng($selected_via_route, $GOOGLEMAP_API_KEY);

                    // Check if $myArray is an array
                    if ($selected_via_route_location_latitude_longitude_city != '0' && is_array($selected_via_route_location_latitude_longitude_city)) {
                        $selected_via_route_location_latitude = $selected_via_route_location_latitude_longitude_city['latitude'];
                        $selected_via_route_location_longitude = $selected_via_route_location_latitude_longitude_city['longitude'];
                        $selected_via_route_location_city = $selected_via_route_location_latitude_longitude_city['city'];


                        $arrFields = array('`location_id`', '`via_route_location`', '`via_route_location_lattitude`', '`via_route_location_longitude`', '`via_route_location_city`',  '`distance_from_source_to_via_route`', '`duration_from_source_to_via_route`', '`createdby`', '`status`');

                        $arrValues = array("$hidden_location_id", "$selected_via_route", "$selected_via_route_location_latitude", "$selected_via_route_location_longitude", "$selected_via_route_location_city", "$distance_from_source_to_via_route", "$duration_from_source_to_via_route", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_stored_location_via_routes", $arrFields, $arrValues, '')) :
                        //SUCCESS
                        else :
                        // $success = false;
                        endif;
                    } else {
                        $response['success'] = false;
                        $errors['location_not_available'] = 'Entered via location is not available. Enter a valid via location';
                        $response['errors'] = $errors;
                    }

                endif;
            endfor;

            if ($success == true) :
                //SUCCESS
                $response['result'] = true;
            else :
                $response['result'] = false;
            endif;


        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'add_toll_charge') :

        $errors = [];
        $response = [];

        $_hid_location_id = trim($_POST['hid_location_id']);
        $_hid_source_location = $_POST['hid_source_location'];
        $_hid_source_location = htmlspecialchars_decode($_hid_source_location, ENT_QUOTES);
        $_hid_source_location = trim($_hid_source_location);

        $_hid_destination_location = $_POST['hid_destination_location'];
        $_hid_destination_location = htmlspecialchars_decode($_hid_destination_location, ENT_QUOTES);
        $_hid_destination_location = trim($_hid_destination_location);

        $location_id_dest_src = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($_hid_destination_location, $_hid_source_location);

        $_vehicle_toll_charge = $_POST['vehicle_toll_charge'];
        $_vehicle_type_id = $_POST['vehicle_type_id'];
        $_vehicle_toll_charge_ID = $_POST['vehicle_toll_charge_ID'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            for ($j = 0; $j < count($_vehicle_toll_charge); $j++) :

                $toll_charge_id = $_vehicle_toll_charge_ID[$j];
                $vehicle_type_id = $_vehicle_type_id[$j];
                $vehicle_toll_charge = $_vehicle_toll_charge[$j];

                if ($toll_charge_id != '' && $toll_charge_id != 0) :

                    //UPDATE(SOURCE TO DESTINATION)
                    $arrFields_src_des = array('`toll_charge`');
                    $arrValues_src_des = array("$vehicle_toll_charge");
                    $sqlWhere_src_des = " `location_id` = '$_hid_location_id' AND `vehicle_type_id`= '$vehicle_type_id' ";

                    //UPDATE TOLL DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields_src_des, $arrValues_src_des, $sqlWhere_src_des)) :

                        //UPDATE (DESTINATION TO SOURCE )
                        $arrFields_des_src = array('`toll_charge`');
                        $arrValues_des_src = array("$vehicle_toll_charge");
                        $sqlWhere_des_src = " `location_id` = '$location_id_dest_src' AND `vehicle_type_id`= '$vehicle_type_id' ";

                        if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, $sqlWhere_des_src)) :
                        endif;

                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;


                else :
                    //INSERT (SOURCE TO DESTINATION)
                    $arrFields_src_des = array('`location_id`', '`vehicle_type_id`',  '`toll_charge`', '`createdby`', '`status`');

                    $arrValues_src_des = array("$_hid_location_id", "$vehicle_type_id",  "$vehicle_toll_charge",  "$logged_user_id", "1");

                    //INSERT TOLL DETAILS
                    if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields_src_des, $arrValues_src_des, '')) :

                        if ($location_id_dest_src) :

                            //INSERT (DESTINATION TO SOURCE )
                            $arrFields_des_src = array('`location_id`', '`vehicle_type_id`',  '`toll_charge`', '`createdby`', '`status`');

                            $arrValues_des_src = array("$location_id_dest_src", "$vehicle_type_id",  "$vehicle_toll_charge",  "$logged_user_id", "1");

                            //INSERT TOLL DETAILS
                            if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, '')) :
                            endif;
                        endif;

                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;

                endif;

            endfor;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_via_route') :
        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
?>
        <div class="modal-body">
            <div class="row">
                <?php //if ($TOTAL_USED_COUNT == 0) : 
                ?>
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmVIAROUTEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
                <?php /* else : ?>
                    <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this record.</h6>
                    <p class="text-center"> Since its assigned to specific hotel with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; */ ?>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_delete_via_route') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_HOTEL = sqlQUERY_LABEL("UPDATE `dvi_stored_location_via_routes` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `via_route_location_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_HOTEL) :

            $response['result'] = true;

        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
