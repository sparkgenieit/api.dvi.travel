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

    if ($_GET['type'] == 'show_form') :
        $LOCATION_ID = $_GET['LOCATION_ID'];
        $select_LOCATIONLIST_query = sqlQUERY_LABEL("SELECT `location_ID`, `source_location`, `source_location_lattitude`, `source_location_longitude`, `source_location_city`, `source_location_state`, `destination_location`, `destination_location_lattitude`, `destination_location_longitude`, `destination_location_city`,`destination_location_state`, `distance`, `duration`, `location_description`,  `status` FROM `dvi_stored_locations` WHERE `location_ID` = '$LOCATION_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($select_LOCATIONLIST_query) > 0) :
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) :
                $source_location = $fetch_list_data['source_location'];
                $source_location_state = $fetch_list_data['source_location_state'];
                $source_location_city = $fetch_list_data["source_location_city"];
                $source_location_lattitude = $fetch_list_data['source_location_lattitude'];
                $source_location_longitude = $fetch_list_data["source_location_longitude"];

                $destination_location = $fetch_list_data['destination_location'];
                $destination_location_state = $fetch_list_data['destination_location_state'];
                $destination_location_city = $fetch_list_data["destination_location_city"];
                $destination_location_lattitude = $fetch_list_data['destination_location_lattitude'];
                $destination_location_longitude = $fetch_list_data["destination_location_longitude"];
                $distance = $fetch_list_data['distance'];
                $duration = $fetch_list_data['duration'];
                $location_description = $fetch_list_data['location_description'];
            endwhile;
            $btn_label = "Update";
        else :
            $btn_label = "Save";
        endif;
?>
        <style>
            .pac-container.pac-logo.hdpi,
            .pac-container.pac-logo {
                z-index: 9999999;
            }

            .easy-autocomplete.eac-square {
                width: 100% !important;
                /* Ensure the div has the correct width */
            }

            .easy-autocomplete input[type="text"] {
                width: 100%;
                /* Ensure the input field has the correct width */
            }
        </style>
        <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />

        <form id="ajax_location_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="LOCATIONFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <div class="row ">
                <div class="col-md-4 mb-3">
                    <label class="form-label source_location" for="source_location">Source Location<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="source_location" id="source_location1" class="form-control" placeholder="Enter the Source Location" autocomplete="off" required value="<?= $source_location ?>" />

                        <input type="hidden" name="old_source_location1" id="old_source_location1" value="<?= $source_location ?>" />
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label" for="source_location_city">Source Location City<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="source_location_city" id="source_location_city" class="form-control" placeholder="Enter the Source Location City" autocomplete="off" required value="<?= $source_location_city ?>" />
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="source_location_state">Source Location State<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="source_location_state" id="source_location_state" class="form-control" placeholder="Enter the Source Location State" autocomplete="off" required value="<?= $source_location_state ?>" />
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label" for="source_location_lattitude">Source Location Lattiude<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="source_location_lattitude" id="source_location_lattitude" class="form-control" placeholder="Enter the Source Location Lattiude" autocomplete="off" required value="<?= $source_location_lattitude ?>" data-parsley-type="number" />
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="source_location_longitude">Source Location Longitude<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="source_location_longitude" id="source_location_longitude" class="form-control" placeholder="Enter the Source Location Longitude" autocomplete="off" required value="<?= $source_location_longitude ?>" data-parsley-type="number" />
                    </div>
                </div>

                <?php if ($LOCATION_ID != "") : ?>
                    <div class="col-md-4 mb-3">
                        <label class="form-label destination_location" for="destination_location">Destination Location<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location" id="destination_location1" class="form-control" placeholder="Enter the Destination Location" autocomplete="off" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check-location-duplicates data-parsley-check-location-duplicates-message="Entered Source and Destination Locations Already Exists" value="<?= $destination_location ?>" />

                            <input type="hidden" name="old_destination_location1" id="old_destination_location1" value="<?= $destination_location ?>" />

                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="destination_location_city">Destination Location City<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location_city" id="destination_location_city" class="form-control" placeholder="Enter the Destination Location City" autocomplete="off" value="<?= $destination_location_city ?>" />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="destination_location_state">Destination Location State<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location_state" id="destination_location_state" class="form-control" placeholder="Enter the Destination Location State" autocomplete="off" value="<?= $destination_location_state ?>" />
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="destination_location_lattitude">Destination Location Lattiude<span class="text-danger"> *</span></label>
                        <div class="form-group">

                            <input type="text" name="destination_location_lattitude" id="destination_location_lattitude" class="form-control" placeholder="Enter the Destination Location Lattiude" autocomplete="off" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check-location-duplicates-latitide data-parsley-check-location-duplicates-latitide-message="Entered Source and Destination Locations Already Exists" value="<?= $destination_location_lattitude ?>" data-parsley-type="number" />

                            <input type="hidden" name="old_destination_location_lattitude" id="old_destination_location_lattitude" value="<?= $destination_location_lattitude ?>" />

                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="destination_location_longitude">Destination Location Longitude<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location_longitude" id="destination_location_longitude" class="form-control" placeholder="Enter the Destination Location Longitude" autocomplete="off" value="<?= $destination_location_longitude ?>" data-parsley-type="number" />
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="loaction_distance">Distance<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="loaction_distance" id="loaction_distance" class="form-control" placeholder="Enter the Distance" autocomplete="off" value="<?= $distance ?>" />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="loaction_duration">Duration(In hours and minutes)<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="loaction_duration" id="loaction_duration" class="form-control" placeholder="XX hours YY mins" autocomplete="off" value="<?= $duration ?>" />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="location_description">Description</label>
                        <div class="form-group">
                            <textarea id="location_description" name="location_description" class="form-control" placeholder="Enter the Address" type="text" rows="3"><?= $location_description; ?></textarea>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn"><?= $btn_label ?></button>
            </div>

            <input type="hidden" name="hid_location_ID" value="<?= $LOCATION_ID ?>" />

        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/jquery.easy-autocomplete.min.js"></script>

        <script>
            $(document).ready(function() {


                $('#ajax_location_details_form').parsley();
                var source_location = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=source";
                    },
                    getValue: "get_source_location",
                    list: {
                        match: {
                            enabled: true
                        },
                        onChooseEvent: function() {
                            get_SOURCE_STATE_CITY_COUNTRY();
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#source_location1").easyAutocomplete(source_location);

                var source_city = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchcity.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=city";
                    },
                    getValue: "get_city",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#source_location_city").easyAutocomplete(source_city);

                var source_state = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchstate.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=state";
                    },
                    getValue: "get_state",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#source_location_state").easyAutocomplete(source_state);

                <?php if ($LOCATION_ID != "") : ?>

                    var destination_location = {
                        url: function(phrase) {
                            return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                    phrase) +
                                "&format=json&type=source";
                        },
                        getValue: "get_source_location",
                        list: {
                            match: {
                                enabled: true
                            },
                            onChooseEvent: function() {
                                $('#destination_location1').parsley().validate();
                                get_DESTINATION_STATE_CITY_COUNTRY();
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };
                    $("#destination_location1").easyAutocomplete(destination_location);

                    var destination_city = {
                        url: function(phrase) {
                            return "engine/json/__JSONsearchcity.php?phrase=" + encodeURIComponent(
                                    phrase) +
                                "&format=json&type=city";
                        },
                        getValue: "get_city",
                        list: {
                            match: {
                                enabled: true
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };
                    $("#destination_location_city").easyAutocomplete(destination_city);

                    var destination_state = {
                        url: function(phrase) {
                            return "engine/json/__JSONsearchstate.php?phrase=" + encodeURIComponent(
                                    phrase) +
                                "&format=json&type=state";
                        },
                        getValue: "get_state",
                        list: {
                            match: {
                                enabled: true
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };
                    $("#destination_location_state").easyAutocomplete(destination_state);

                    //CHECK DUPLICATE SOURCE AND DESTINATION
                    $('#destination_location1').parsley();
                    var old_destination_location1 = document.getElementById("old_destination_location1").value;

                    var source_location1 = $('#source_location1').val();
                    var old_source_location1 = $('#old_source_location1').val();
                    window.ParsleyValidator.addValidator('check-location-duplicates', {
                        validateString: function(value) {
                            return $.ajax({
                                url: 'engine/ajax/__ajax_check_duplicate_location.php?type=location_name',
                                method: "POST",
                                data: {
                                    destination_location: value,
                                    old_destination_location: old_destination_location1,
                                    source_location: source_location1,
                                    old_source_location: old_source_location1
                                },
                                dataType: "json",
                                success: function(data) {
                                    return true;
                                }
                            });
                        }
                    });

                <?php endif; ?>

                // AJAX Form Submit
                $("#ajax_location_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent default form submission
                    var form = $('#ajax_location_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    $(this).find("button[type='submit']").prop('disabled', true);
                    spinner.show();

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_location.php?type=add',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 900000, // Increased timeout duration (5 minutes)
                        dataType: 'json',
                        success: function(response) {
                            spinner.hide();
                            if (!response.success) {
                                // Handle error response
                                if (response.errors && response.errors.source_location_required) {
                                    TOAST_NOTIFICATION('warning', 'Source location is required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to submit. The location is already existing', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                                $('#add_places_form_submit_btn').prop('disabled', false);
                            } else {
                                if (!response.result) {
                                    // Handle unsuccessful result
                                    TOAST_NOTIFICATION('error', 'Unable to submit. The location is already existing', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                    $('#add_places_form_submit_btn').prop('disabled', false);
                                } else {
                                    // Handle successful result
                                    $('#ajax_location_details_form')[0].reset();
                                    $('#addLOCATIONFORM').modal('hide');
                                    $('#location_LIST').DataTable().ajax.reload(function() {
                                        // Callback function code here
                                    }, false);
                                    TOAST_NOTIFICATION('success', 'Location Updated successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // Handle AJAX errors
                            spinner.hide();
                            TOAST_NOTIFICATION('error', 'AJAX error: ' + textStatus + ' - ' + errorThrown, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            $('#add_places_form_submit_btn').prop('disabled', false);
                            console.error('AJAX error: ', textStatus, errorThrown, jqXHR.responseText);
                        }
                    });
                });
            });

            function get_SOURCE_STATE_CITY_COUNTRY() {
                var source_location1 = $('#source_location1').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_stored_location_details.php?type=GET_LOCATION_DETAILS',
                    data: {
                        vehicle_orign: source_location1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#source_location_state').val(response.source_location_state);
                        $('#source_location_city').val(response.source_location_city);
                        $('#source_location_lattitude').val(response.source_location_lattitude);
                        $('#source_location_longitude').val(response.source_location_longitude);
                    }
                });
            }

            function get_DESTINATION_STATE_CITY_COUNTRY() {
                var destination_location1 = $('#destination_location1').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_stored_location_details.php?type=GET_LOCATION_DETAILS',
                    data: {
                        vehicle_orign: destination_location1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#destination_location_state').val(response.source_location_state);
                        $('#destination_location_city').val(response.source_location_city);
                        $('#destination_location_lattitude').val(response.source_location_lattitude);
                        $('#destination_location_longitude').val(response.source_location_longitude);
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'preview') :

        $location_ID = $_POST['ID'];

        $select_hotspot_query = sqlQUERY_LABEL("SELECT `location_ID`, `source_location`, `source_location_lattitude`, `source_location_longitude`, `source_location_city`, `destination_location`, `destination_location_lattitude`, `destination_location_longitude`, `destination_location_city`, `distance`, `duration` FROM `dvi_stored_locations` WHERE `deleted` = '0' and `location_ID` = '$location_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
            $counter++;
            $location_ID = $fetch_list_data['location_ID'];
            $source_location = $fetch_list_data['source_location'];
            $source_location_lattitude = $fetch_list_data['source_location_lattitude'];
            $source_location_longitude = $fetch_list_data['source_location_longitude'];
            $source_location_city = $fetch_list_data['source_location_city'];
            $destination_location = $fetch_list_data['destination_location'];
            $destination_location_lattitude = $fetch_list_data['destination_location_lattitude'];
            $destination_location_longitude = $fetch_list_data['destination_location_longitude'];
            $destination_location_city = $fetch_list_data['destination_location_city'];
            $distance = $fetch_list_data['distance'];
            $duration = $fetch_list_data['duration'];
        endwhile;
    ?>
        <style>
            .itinerary-header-title-sticky {
                position: sticky;
                top: 2px;
                background-color: #ffffff;
                z-index: 1001;
            }
        </style>

        <div class="card itinerary-header-title-sticky p-3 py-2">
            <div class="row align-items-end mb-4 stickey-element">
                <div class="col-md-4">
                    <label class="form-label" for="source_location">Source Location <span class="text-danger">*</span></label>
                    <!--<input type="text" name="source_location" id="source_location" class="form-control">-->
                    <div class="form-group">
                        <select name="source_location" id="source_location" class="form-select form-control location" required>
                            <?= getSOURCE_LOCATION_DETAILS($source_location, 'select_source'); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="destination_location">Destination Location <span class="text-danger">*</span></label>
                    <!--<input type="text" name="destination_location" id="destination_location" class="form-control">-->
                    <div class="form-group">
                        <select name="destination_location" id="destination_location" class="form-select form-control location" required>
                            <option value=""> Choose Location</option>
                            <?php // getSOURCE_LOCATION_DETAILS($selected_value, 'select_destination'); 
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="getLOCATION_DETAILS()">Get Info</a>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="row">
                <h4 class="text-primary">Location Details</h4>
                <div class="col-md-3">
                    <label>Source</label>
                    <p class="text-light">
                        <?= $source_location ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source Latitude</label>
                    <p class="text-light">
                        <?= $source_location_lattitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source Longitude </label>
                    <p class="text-light">
                        <?= $source_location_longitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source City</label>
                    <p class="text-light">
                        <?= $source_location_city ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination</label>
                    <p class="text-light">
                        <?= $destination_location ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination Latitude</label>
                    <p class="text-light">
                        <?= $destination_location_lattitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination Longitude </label>
                    <p class="text-light">
                        <?= $destination_location_longitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination City</label>
                    <p class="text-light">
                        <?= $destination_location_city ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Distance</label>
                    <p class="text-light">
                        <?= $distance ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Duration</label>
                    <p class="text-light">
                        <?= $duration ?>
                    </p>
                </div>
            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>
            <div class="row" id="vehicle_toll_details_container">
                <h4 class="text-primary">Vehicle Toll Details</h4>

                <form id="ajax_toll_details_form" class="row" action="" method="post" data-parsley-validate>
                    <input type="hidden" name="hid_location_id" value="<?= $location_ID ?>" />
                    <input type="hidden" name="hid_source_location" value="<?= $source_location ?>" />
                    <input type="hidden" name="hid_destination_location" value="<?= $destination_location ?>" />

                    <div class="col-12" id="vehicle_toll_details">
                        <div class="" id="cost_type_local">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <?php
                                        $select_vehicletype_details = sqlQUERY_LABEL("SELECT V.`vehicle_type_id`, V.`vehicle_type_title`,T.`vehicle_toll_charge_ID`,T.`toll_charge` FROM `dvi_vehicle_type` V LEFT JOIN `dvi_vehicle_toll_charges` T ON T.`vehicle_type_id` = V.`vehicle_type_id` AND T.`location_id` = '$location_ID' WHERE V.`deleted` = '0' AND V.`status` = '1' ") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                        if (sqlNUMOFROW_LABEL($select_vehicletype_details) > 0) :
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicletype_details)) :
                                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                $vehicle_type_title = $fetch_data['vehicle_type_title'];
                                                $vehicle_toll_charge_ID = $fetch_data['vehicle_toll_charge_ID'];
                                                $toll_charge = $fetch_data['toll_charge'];
                                        ?>
                                                <div class="col-3 mb-3">
                                                    <label class="form-label" for="vehicle_parking_charge">
                                                        <?= $vehicle_type_title ?>
                                                    </label>

                                                    <input type="hidden" id="vehicle_type_id" name="vehicle_type_id[]" value="<?= $vehicle_type_id ?>" />
                                                    <input type="hidden" id="vehicle_toll_charge_ID" name="vehicle_toll_charge_ID[]" value="<?= $vehicle_toll_charge_ID ?>" />

                                                    <div class="form-group" style="position: relative;">
                                                        <span style="position: absolute; top: 50%; transform: translateY(-50%); left: 10px; font-size: 16px;">₹</span>
                                                        <input type="text" id="vehicle_toll_charge" name="vehicle_toll_charge[]" required class="form-control" placeholder="Enter Toll Charge" value="<?= ($toll_charge == "") ? 0 : $toll_charge ?>" required autocomplete="off" style="padding-left: 25px;" />
                                                    </div>
                                                </div>

                                        <?php
                                            endwhile;
                                        endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-right text-center pt-4">
                                    <button type="submit" class="btn btn-primary" id="add_toll_form_submit_btn">Update Toll Charges</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-0">
                    <div class="card-body dataTable_select text-nowrap pt-0 px-2">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="m-0 text-primary">List Of Via Routes</h4>
                            <a class="btn btn-label-primary waves-effect" onclick="addVIAROUTE('<?= $location_ID ?>', '')">+ Add Via Route</a>
                        </div>
                        <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover " id="via_route_LIST">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">Via Route Location</th>
                                        <th scope="col">Latitude</th>
                                        <th scope="col">Longtitude</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-end">
                            <a href="locations.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-0">
                    <div class="card-body dataTable_select text-nowrap pt-0 px-2">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="m-0 text-primary">List Of Routes Suggestions</h4>
                            <a class="btn btn-label-primary waves-effect" onclick="addSUGGESTEDROUTE(event,'<?= $location_ID ?>', '')">+ Add Route</a>
                        </div>
                        <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover " id="suggested_route_LIST">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">Routes</th>
                                        <th scope="col">No of Nights</th>
                                        <th scope="col">Route Details</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-end">
                            <a href="locations.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Via Route Modal -->
        <div class="modal fade" id="viarouteModal" tabindex="-1" aria-labelledby="viarouteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-via-route-form-data">

                    </div>
                </div>
            </div>
        </div>

        <!-- Suggested Route Modal -->
        <div class="modal fade" id="suggestedrouteModal" tabindex="-1" aria-labelledby="suggestedrouteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-suggested-route-form-data">
                    </div>
                </div>
            </div>
        </div>

        <!--Delte via route Modal -->
        <div class="modal fade" id="confirmDELETEVIAROUTEINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body receiving-confirm-delete-via-route-form-data">
                    </div>
                </div>
            </div>
        </div>

        <!--Delte via route Modal -->
        <div class="modal fade" id="confirmDELETEROUTEINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body receiving-confirm-delete-route-form-data">
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {

                $('.location').selectize();

                <?php if ($source_location != '') : ?>
                    get_destination_location_details();
                <?php endif; ?>

                $('#source_location').change(function() {
                    get_destination_location_details();
                });

                //TOLL CHARGE AJAX FORM SUBMIT
                $("#ajax_toll_details_form").submit(function(event) {
                    var form = $('#ajax_toll_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    //$(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_location.php?type=add_toll_charge',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(response); // Add this line for debugging
                        if (!response.success) {
                            spinner.hide();
                            TOAST_NOTIFICATION('error', 'Something Went wrong..', 'Error !!!');
                        } else {
                            if (response.result_success === false) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!');
                            }
                            if (response.result_success === true) {
                                TOAST_NOTIFICATION('success', 'Toll Details Updated Successfully', 'Success !!!');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error: ', textStatus, errorThrown); // Log AJAX errors
                        TOAST_NOTIFICATION('error', 'Something Went wrong!!!', 'Error !!!');
                    });
                    event.preventDefault();
                });

                $('#via_route_LIST').DataTable({
                    dom: 'Blfrtip',
                    "bFilter": false,
                    buttons: [{
                            extend: 'copy',
                            text: window.copyButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 5], // Only name, email and role
                            }
                        },
                        {
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 5], // Only name, email and role
                            }
                        },
                        {
                            extend: 'csv',
                            text: window.csvButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 5], // Only name, email and role
                            }
                        }
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                        $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                        $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');
                    },
                    ajax: {
                        "url": "engine/json/__JSONlocation_viaroute.php?location_ID=<?= $location_ID ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "via_route_location"
                        }, //1
                        {
                            data: "via_route_location_lattitude"
                        }, //2
                        {
                            data: "via_route_location_longitude"
                        }, //3
                        {
                            data: "modify"
                        } //4
                    ],
                    columnDefs: [{
                        "targets": 4,
                        "data": "modify",
                        "render": function(data, type, full) {
                            return '<div class="flex align-items-center list-user-action"> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEVIAROUTEMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" onclick="addVIAROUTE(' + <?= $location_ID ?> + ',' + data + ');" href="#" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a></div>';
                        }
                    }],
                });

                $('#suggested_route_LIST').DataTable({
                    dom: 'Blfrtip',
                    "bFilter": false,
                    buttons: [{
                            extend: 'copy',
                            text: window.copyButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2], // Only name, email and role
                            }
                        },
                        {
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2], // Only name, email and role
                            }
                        },
                        {
                            extend: 'csv',
                            text: window.csvButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2], // Only name, email and role
                            }
                        }
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                        $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                        $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');


                    },
                    ajax: {
                        "url": "engine/json/__JSONlocation_suggestedroute.php?location_ID=<?= $location_ID ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "routes"
                        }, //1
                        {
                            data: "no_of_nights"
                        }, //2
                        {
                            data: "route_details",
                        }, //3
                        {
                            data: "modify"
                        } //4
                    ],
                    columnDefs: [{
                        "targets": 4,
                        "data": "modify",
                        "render": function(data, type, full) {
                            return '<div class="flex align-items-center list-user-action"> <a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" onclick="addSUGGESTEDROUTE(event,' + <?= $location_ID ?> + ',' + data + ');" href="#"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEROUTEMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                        }
                    }],
                });
            });

            function get_destination_location_details() {
                var source_location = $("#source_location").val();
                var destination_selectize = $("#destination_location")[0].selectize;
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_get_location_dropdown.php?type=selectize_destination_location",
                    data: {
                        source_location: source_location
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Append the response to the dropdown.
                        destination_selectize.clear();
                        destination_selectize.clearOptions();
                        destination_selectize.addOption(response);
                        <?php if ($destination_location != "") { ?>
                            destination_selectize.setValue(<?= json_encode($destination_location) ?>);
                        <?php } ?>
                    }
                });
            }

            function getLOCATION_DETAILS() {
                var source_location = $("#source_location").val();
                var destination_location = $("#destination_location").val();
                if (source_location == "" || destination_location == "") {
                    TOAST_NOTIFICATION('error', 'Please select both Source and Destination Locations', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_get_location_dropdown.php?type=get_location_ID",
                    data: {
                        source_location: source_location,
                        destination_location: destination_location
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response?.success && response?.location_ID) {
                            window.location.href =
                                "locations.php?route=preview&formtype=preview&id=" +
                                encodeURIComponent(response.location_ID);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to get the Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }

                    }
                });

            }


            //SHOW DELETE POPUP
            function showDELETEVIAROUTEMODAL(ID) {
                $('.receiving-confirm-delete-via-route-form-data').load('engine/ajax/__ajax_manage_location.php?type=delete_via_route&ID=' + ID, function() {
                    const container = document.getElementById("confirmDELETEVIAROUTEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            //CONFIRM DELETE POPUP
            function confirmVIAROUTEDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_location.php?type=confirm_delete_via_route",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#via_route_LIST').DataTable().ajax.reload();
                            $('#confirmDELETEVIAROUTEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Via Route Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete the Via Route', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            function addVIAROUTE(LOCATION_ID, VIA_ROUTE_ID) {
                $('.receiving-via-route-form-data').load('engine/ajax/__ajax_add_location.php?type=add_location_viaroute&LOCATION_ID=' + LOCATION_ID + '&VIA_ROUTE_ID=' + VIA_ROUTE_ID + '', function() {
                    const container = document.getElementById("viarouteModal");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                    if (VIA_ROUTE_ID != "") {
                        $('#VIAROUTEFORMLabel').html('Update Via Route');
                    } else {
                        $('#VIAROUTEFORMLabel').html('Add Via Route');
                    }
                });

            }

            function addSUGGESTEDROUTE(e, LOCATION_ID, SUGGESTED_ROUTE_ID) {
                // Prevent default anchor or button behavior
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                // Load modal content dynamically
                $('.receiving-suggested-route-form-data').load(
                    'engine/ajax/__ajax_add_location.php?type=add_location_suggestedroute&LOCATION_ID=' +
                    LOCATION_ID +
                    '&SUGGESTED_ROUTE_ID=' + SUGGESTED_ROUTE_ID,
                    function() {
                        const container = document.getElementById("suggestedrouteModal");
                        const modal = new bootstrap.Modal(container, {
                            backdrop: 'static',
                            keyboard: false
                        });
                        modal.show();

                        // Update title
                        if (SUGGESTED_ROUTE_ID !== "" && SUGGESTED_ROUTE_ID !== null) {
                            $('#SUGGESTEDROUTEFORMLabel').html('Update Route Locations');
                        } else {
                            $('#SUGGESTEDROUTEFORMLabel').html('Add Route Locations');
                        }

                        // Optional: Scroll to top inside modal (not the page)
                        setTimeout(() => {
                            $('.modal-body').animate({
                                scrollTop: 0
                            }, 200);
                        }, 150);
                    }
                );
            }

            //SHOW DELETE POPUP
            function showDELETEROUTEMODAL(ID) {
                $('.receiving-confirm-delete-route-form-data').load('engine/ajax/__ajax_manage_location.php?type=delete_route&ID=' + ID, function() {
                    const container = document.getElementById("confirmDELETEROUTEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            //CONFIRM DELETE POPUP
            function confirmROUTEDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_location.php?type=confirm_delete_route",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#suggested_route_LIST').DataTable().ajax.reload();
                            $('#confirmDELETEROUTEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Route Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete the Route', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        </script>

    <?php
    elseif ($_GET['type'] == 'add_location_viaroute') :

        $LOCATION_ID = $_GET['LOCATION_ID'];
        $VIA_ROUTE_ID = $_GET['VIA_ROUTE_ID'];
        $select_LOCATIONLIST_query = sqlQUERY_LABEL("SELECT `via_route_location_ID`, `location_id`, `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude`, `via_route_location_state`, `via_route_location_city`, `distance_from_source_to_via_route`, `duration_from_source_to_via_route`,`distance_from_via_route_to_destination`, `duration_from_via_route_to_destination` FROM `dvi_stored_location_via_routes` WHERE `location_id`='$LOCATION_ID' AND `deleted` = '0' AND `via_route_location_ID`='$VIA_ROUTE_ID' ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_LOCATIONLIST_query) > 0) :
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) :
                $via_route_location_ID = $fetch_list_data['via_route_location_ID'];
                $via_route_location = html_entity_decode($fetch_list_data['via_route_location']);
                $via_route_location_lattitude = $fetch_list_data['via_route_location_lattitude'];
                $via_route_location_longitude = $fetch_list_data['via_route_location_longitude'];
                $via_route_location_state = $fetch_list_data['via_route_location_state'];
                $via_route_location_city = html_entity_decode($fetch_list_data['via_route_location_city']);
                $distance_from_source_to_via_route = html_entity_decode($fetch_list_data['distance_from_source_to_via_route']);
                $duration_from_source_to_via_route = html_entity_decode($fetch_list_data['duration_from_source_to_via_route']);
                $distance_from_via_route_to_destination = $fetch_list_data['distance_from_via_route_to_destination'];
                $duration_from_via_route_to_destination = $fetch_list_data['duration_from_via_route_to_destination'];
            endwhile;
            $btn_label = "Update";
        else :
            $btn_label = "Save";
        endif;

    ?>
        <style>
            .easy-autocomplete.eac-square {
                width: 100% !important;
                /* Ensure the div has the correct width */
            }

            .easy-autocomplete input[type="text"] {
                width: 100%;
                /* Ensure the input field has the correct width */
            }
        </style>

        <form id="ajax_via_route_details_form" action="" method="post" data-parsley-validate="" novalidate="">
            <div class="text-center">
                <h4 class="mb-3" id="VIAROUTEFORMLabel">Add Via Route</h4>
            </div>
            <div class="row ">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="via_route_location">Via Route Location<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="via_route_location" id="via_route_location" class="form-control" placeholder="Enter the Via Route Location" autocomplete="off" required="" value="<?= $via_route_location ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check-viaroute-location-duplicates data-parsley-check-viaroute-location-duplicates-message="Entered Via route Locations Already Exists">

                        <input type="hidden" name="old_via_route_location" id="old_via_route_location" value="<?= $via_route_location ?>" />

                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="via_route_location_state">Via Route Location State<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="via_route_location_state" id="via_route_location_state" class="form-control" placeholder="Enter the Via Route Location State" autocomplete="off" required="" value="<?= $via_route_location_state; ?>">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="via_route_location_city">Via Route Location City<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="via_route_location_city" id="via_route_location_city" class="form-control" placeholder="Enter the Via Route Location City" autocomplete="off" required="" value="<?= $via_route_location_city ?>">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="via_route_location_longitude">Via Route Location Longitude<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="via_route_location_longitude" id="via_route_location_longitude" class="form-control" placeholder="Enter the Via Route Location Longitude" autocomplete="off" required="" value="<?= $via_route_location_longitude ?>" data-parsley-type="number">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="via_route_location_lattitude">Via Route Location Lattiude<span class="text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="via_route_location_lattitude" id="via_route_location_lattitude" class="form-control" placeholder="Enter the Via Route Location Lattiude" autocomplete="off" required="" value="<?= $via_route_location_lattitude ?>" data-parsley-type="number">
                    </div>
                </div>
                <?php if ($VIA_ROUTE_ID != "") : ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="distance_from_source_location">Distance from source Location (KM)<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="distance_from_source_location" id="distance_from_source_location" class="form-control" placeholder="Enter the Distance" autocomplete="off" required="" value="<?= $distance_from_source_to_via_route ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="duration_from_source_location">Duration from source Location (In Hours and minutes)<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="duration_from_source_location" id="duration_from_source_location" class="form-control" placeholder="XX hours YY mins" autocomplete="off" required="" value="<?= $duration_from_source_to_via_route ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="distance_from_source_location">Distance from Via route to destination (KM)<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="distance_from_via_route_to_destination" id="distance_from_via_route_to_destination" class="form-control" placeholder="Enter the Distance " autocomplete="off" required="" value="<?= $distance_from_via_route_to_destination ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="duration_from_via_route_to_destination">Duration from Via route to destination (In Hours and minutes)<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="duration_from_via_route_to_destination" id="duration_from_via_route_to_destination" class="form-control" placeholder="XX hours YY mins" autocomplete="off" required="" value="<?= $duration_from_via_route_to_destination ?>">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn"><?= $btn_label ?></button>
            </div>
            <input type="hidden" name="hid_location_id" value="<?= $LOCATION_ID ?>">
            <input type="hidden" name="hid_via_route_id" value="<?= $VIA_ROUTE_ID ?>">

        </form>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {


                $('#ajax_location_details_form').parsley();
                var via_route_location = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=source";
                    },
                    getValue: "get_source_location",
                    list: {
                        match: {
                            enabled: true
                        },
                        onChooseEvent: function() {
                            get_SOURCE_STATE_CITY_COUNTRY();
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#via_route_location").easyAutocomplete(via_route_location);

                var via_route_location_state = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchstate.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=state";
                    },
                    getValue: "get_state",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#via_route_location_state").easyAutocomplete(via_route_location_state);

                var via_route_location_city = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchcity.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=city";
                    },
                    getValue: "get_city",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#via_route_location_city").easyAutocomplete(via_route_location_city);

                var via_route_location = $('#via_route_location').val();
                var old_via_route_location = $('#old_via_route_location').val();
                window.ParsleyValidator.addValidator('check-viaroute-location-duplicates', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_duplicate_location.php?type=via_route_location_name',
                            method: "POST",
                            data: {
                                location_id: '<?= $LOCATION_ID ?>',
                                via_route_location: value,
                                old_via_route_location: old_via_route_location,
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //VIA ROUTE AJAX FORM SUBMIT
                $("#ajax_via_route_details_form").submit(function(event) {
                    var form = $('#ajax_via_route_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_location.php?type=add_via_route',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            if (response.errors.via_route_required) {
                                TOAST_NOTIFICATION('error', 'Via Route Location is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.location_not_available) {
                                TOAST_NOTIFICATION('error', 'Entered via location is already Existing.Please check lattitude or longitude or via route location name entered.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (!response.success) {
                                //NOT SUCCESS RESPONSE
                                if (response.result_success) {
                                    TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            } else {
                                //SUCCESS RESPOSNE
                                if (response.result) {
                                    $('#ajax_via_route_details_form')[0].reset();
                                    $('#via_route_LIST').DataTable().ajax.reload();
                                    TOAST_NOTIFICATION('success', 'Via Route Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            }
                            $('#viarouteModal').modal('hide');
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });

            });

            function get_SOURCE_STATE_CITY_COUNTRY() {
                var via_route_location = $('#via_route_location').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_stored_location_details.php?type=GET_LOCATION_DETAILS',
                    data: {
                        vehicle_orign: via_route_location,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#via_route_location_state').val(response.source_location_state);
                        $('#via_route_location_city').val(response.source_location_city);
                        $('#via_route_location_lattitude').val(response.source_location_lattitude);
                        $('#via_route_location_longitude').val(response.source_location_longitude);
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'add_location_suggestedroute') :

        $LOCATION_ID = $_GET['LOCATION_ID'];
        $SUGGESTED_ROUTE_ID = $_GET['SUGGESTED_ROUTE_ID'];

        $source_location = getSTOREDLOCATIONDETAILS($LOCATION_ID, 'SOURCE_LOCATION');
        $destination_location = getSTOREDLOCATIONDETAILS($LOCATION_ID, 'DESTINATION_LOCATION');

        // Fetch existing rows (if any)
        $rows = [];
        $select_LOCATIONLIST_query = sqlQUERY_LABEL("
        SELECT stored_route_location_ID, stored_route_id, route_location_id, route_location_name
        FROM dvi_stored_route_location_details
        WHERE deleted = '0' AND stored_route_id = '$SUGGESTED_ROUTE_ID'
        ORDER BY stored_route_location_ID ASC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($select_LOCATIONLIST_query) > 0) {
            while ($r = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) {
                $rows[] = $r;
            }
            $btn_label = "Update";
        } else {
            $btn_label = "Save";
        }

        // Nights logic:
        // We’ll treat “No. of Nights” as the desired number of segments between Source and Destination.
        // That means total locations = nights + 1.
        $initial_total_locations = max(1, count($rows) ?: 1); // at least 1 line to start
        $initial_nights = max(0, $initial_total_locations - 1);
    ?>
        <style>
            .easy-autocomplete.eac-square {
                width: 100% !important;
            }

            .easy-autocomplete input[type="text"] {
                width: 100%;
            }

            .route-legend {
                display: flex;
                gap: 12px;
                justify-content: center;
                align-items: center;
                font-size: .9rem;
                color: #555;
                margin-top: .25rem;
            }

            .badge-tag {
                display: inline-block;
                padding: .15rem .5rem;
                border-radius: 999px;
                font-weight: 600;
                font-size: .75rem;
            }

            .badge-source {
                background: #e6f4ea;
                color: #1a7f37;
            }

            .badge-destination {
                background: #e8f0fe;
                color: #1a73e8;
            }

            .badge-stop {
                background: #f1f3f4;
                color: #444;
            }

            .route-row {
                /* border: 1px dashed #e5e7eb;
                border-radius: .5rem;
                padding: .75rem; */
            }

            .route-label {
                font-weight: 600;
                margin-bottom: .25rem;
                display: inline-flex;
                gap: .5rem;
                align-items: center;
            }

            .route-chip {
                font-size: .7rem;
                padding: .1rem .45rem;
                border-radius: .5rem;
                background: #f1f5f9;
                color: #334155;
            }

            .nights-wrap {
                display: grid;
                grid-template-columns: 180px 1fr;
                gap: 10px;
                align-items: center;
            }

            @media (max-width: 576px) {
                .nights-wrap {
                    grid-template-columns: 1fr;
                }
            }

            .btn-icon {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
            }

            .btn-gradient {
                background: linear-gradient(135deg, #a855f7, #ec4899);
                color: #fff;
                border: none;
                border-radius: .75rem;
                padding: .5rem .9rem;
                font-weight: 600;
                box-shadow: 0 6px 18px rgba(236, 72, 153, .25);
            }

            .btn-gradient:hover {
                filter: brightness(1.05);
            }

            .route-chip {
                font-size: .7rem;
                padding: .1rem .45rem;
                border-radius: .5rem;
                background: #eef2f6;
                color: #334155;
            }

            /* Make selectize/easyAutocomplete inputs line up with .form-select height */
            .selectize-control .selectize-input {
                padding: .47rem .75rem;
                min-height: calc(1.5em + .94rem + 2px);
                border-radius: .375rem;
            }

            .easy-autocomplete input[type="text"] {
                height: calc(1.5em + .94rem + 2px);
            }

            /* ensure scrolling is inside the modal body */
            #suggestedrouteModal .modal-body {
                max-height: 70vh;
                overflow-y: auto;
            }

            /* Make Selectize dropdown render above Bootstrap modal */
            .selectize-dropdown {
                z-index: 2001;
            }

            /* Bootstrap modal is ~1055 */
        </style>

        <form id="ajax_suggested_route_details_form" action="" method="post" data-parsley-validate novalidate>
            <div class="text-center">
                <h4 class="mb-4" id="SUGGESTEDROUTEFORMLabel"></h4>
                <div class="route-legend">
                    <span class="badge-tag badge-source"><?= $source_location; ?></span>
                    <span class="badge-tag badge-destination"><?= $destination_location; ?></span>
                </div>
            </div>

            <div class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-sm-12 col-12">
                        <label for="nights_count" class="form-label mb-1">Total No. of Nights</label>
                        <input type="number" id="nights_count" name="nights_count" min="0" step="1"
                            inputmode="numeric" pattern="\d*"
                            class="form-control" value="<?= (int)$initial_nights; ?>" autocomplete="off" />
                    </div>
                    <div class="d-none col-md-4 col-4 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-primary btn-add-night">+ Add</button>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Each day below corresponds to one night in the itinerary.</small>
                    </div>
                </div>
            </div>

            <div id="route_rows_container">
                <?php
                // Render rows (if present), else one blank row
                if ($rows) {
                    $idx = 0;
                    foreach ($rows as $row) {
                        $idx++;
                        $label = 'Day ' . $idx;
                ?>
                        <div class="row route-row add-rows mb-2" data-row-index="<?= $idx ?>">
                            <input type="hidden" name="hid_stored_route_location_id[]" value="<?= htmlspecialchars($row['stored_route_location_ID']) ?>">
                            <div class="col-md-10">
                                <label class="form-label route-label">
                                    <span class="route-chip">#<?= $label ?></span>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <!-- Selectize select -->
                                    <select id="route_location_<?= $idx ?>" name="route_location[]" class="form-select route-location-select" required>
                                        <?php if (!empty($row['route_location_id'])): ?>
                                            <option value="<?= htmlspecialchars($row['route_location_name']); ?>" selected>
                                                <?= htmlspecialchars($row['route_location_name']); ?>
                                            </option>
                                        <?php else: ?>
                                            <option value="" selected></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end justify-content-end">
                                <button type="button" class="btn btn-outline-primary btn-sm remove-route" data-stored-id="<?= (int)$row['stored_route_location_ID'] ?>">
                                    <i class="ti ti-trash ti-tada-hover"></i>
                                </button>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    // one blank Source row
                    ?>
                    <div class="row route-row add-rows mb-2" data-row-index="1">
                        <input type="hidden" name="hid_stored_route_location_id[]" value="">
                        <div class="col-md-10">
                            <label class="form-label route-label">
                                <span class="route-chip">#1</span>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="form-group">
                                <select id="route_location_1" name="route_location[]" class="form-select route-location-select" required></select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn"><?= $btn_label ?></button>
            </div>

            <input type="hidden" name="hid_location_id" value="<?= (int)$LOCATION_ID ?>">
            <input type="hidden" name="hid_suggested_route_id" value="<?= (int)$SUGGESTED_ROUTE_ID ?>">
        </form>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(function() {
                // ===== CONFIG =====
                const USE_SELECTIZE = true; // set false to use EasyAutocomplete
                const SEARCH_URL = "engine/json/__JSONsearchsourcelocation.php"; // expects ?phrase=...&format=json&type=source

                // ===== NIGHTS INPUT: numeric-only, select-all, Enter/blur applies =====
                const $nights = $('#nights_count');

                // Prevent mouse wheel from changing value
                /* $nights.on('wheel', function(e) {
                    e.preventDefault();
                }); */

                // Select all when focusing
                $nights.on('focus', function() {
                    this.select();
                });

                // Keep selection on mouse up
                /* $nights.on('mouseup', function(e) {
                    e.preventDefault();
                }); */

                // Guard keystrokes
                $nights.on('keydown', function(e) {
                    const k = e.key;
                    const ctrl = e.ctrlKey || e.metaKey;

                    const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Home', 'End', 'Tab'];
                    if (allowed.includes(k)) return;
                    if (ctrl && ['a', 'c', 'v', 'x', 'z', 'y'].includes(k.toLowerCase())) return;
                    if (/^[0-9]$/.test(k)) return;

                    // Enter applies immediately
                    if (k === 'Enter') {
                        e.preventDefault();
                        let v = (this.value || '').replace(/[^\d]/g, '');
                        if (v === '') v = '0';
                        this.value = String(parseInt(v, 10));
                        $(this).trigger('change').blur();
                        return;
                    }

                    e.preventDefault();
                });

                // Sanitize any pasted content
                $nights.on('input', function() {
                    let v = (this.value || '').replace(/[^\d]/g, '');
                    if (v === '') v = '0';
                    this.value = String(parseInt(v, 10));
                });

                // Apply on blur as well
                $nights.on('blur', function() {
                    $(this).trigger('change');
                });

                // ===== HELPERS =====
                function transformToOptions(res) {
                    let out = [];
                    if (Array.isArray(res)) {
                        out = res.map(item => {
                            const name = item.get_source_location || item.route_location_name || item.text || item.name || '';
                            const id = item.route_location_id || item.id || name; // fallback to name if no id
                            return {
                                value: id,
                                text: name
                            };
                        });
                    } else if (res && res.results) {
                        out = res.results.map(r => ({
                            value: r.id || r.text,
                            text: r.text || r.name
                        }));
                    }
                    return out;
                }

                function initSelectize($select) {
                    if (!USE_SELECTIZE) return;

                    const $row = $select.closest('.route-row');

                    $select.selectize({
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        placeholder: 'Search location…',
                        allowEmptyOption: false,
                        preload: false,
                        create: false,
                        openOnFocus: true,
                        maxOptions: 20,

                        load: function(query, callback) {
                            if (!query || query.length < 1) return callback();
                            $.ajax({
                                url: SEARCH_URL,
                                data: {
                                    phrase: query,
                                    format: 'json',
                                    type: 'source'
                                },
                                type: 'GET',
                                dataType: 'json',
                                success: function(res) {
                                    callback(transformToOptions(res));
                                },
                                error: function(xhr) {
                                    // Recover from non-strict JSON, e.g. {get_source_location:"Chennai"}
                                    let txt = xhr && xhr.responseText ? xhr.responseText.trim() : '';
                                    if (!txt) return callback();
                                    try {
                                        if (txt[0] !== '[') txt = '[' + txt + ']';
                                        txt = txt.replace(/([{,]\s*)([A-Za-z0-9_]+)\s*:/g, '$1"$2":');
                                        if (txt.indexOf("'") !== -1) txt = txt.replace(/'/g, '"');
                                        const fixed = JSON.parse(txt);
                                        callback(transformToOptions(fixed));
                                    } catch {
                                        callback();
                                    }
                                }
                            });
                        },

                        // After user picks a location, jump to the next Day
                        onItemAdd: function() {
                            const $sel = $row.find('.route-location-select');
                            setTimeout(() => {
                                focusNextRouteFrom($sel);
                            }, 50);
                        }
                    });
                }

                function initEasyAutocomplete($selectAsInput) {
                    const id = $selectAsInput.attr('id');
                    if ($selectAsInput.is('select')) {
                        const $input = $('<input type="text" class="form-control source-input" autocomplete="off" required />').attr('id', id);
                        $selectAsInput.replaceWith($input);
                        $selectAsInput = $input;
                    }
                    var cfg = {
                        url: function(phrase) {
                            return SEARCH_URL + "?phrase=" + encodeURIComponent(phrase) + "&format=json&type=source";
                        },
                        getValue: "get_source_location",
                        list: {
                            match: {
                                enabled: true
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };
                    $selectAsInput.easyAutocomplete(cfg);
                }

                function initLocationControl($el) {
                    if (USE_SELECTIZE) initSelectize($el);
                    else initEasyAutocomplete($el);
                }

                function isPersistedRow($row) {
                    const val = $row.find('input[name="hid_stored_route_location_id[]"]').val();
                    return val && String(val).trim() !== '';
                }

                // --- helpers for nights input ---
                function debounce(fn, wait) {
                    let t;
                    return function(...args) {
                        clearTimeout(t);
                        t = setTimeout(() => fn.apply(this, args), wait);
                    };
                }

                function clampInt(v, min = 0) {
                    v = parseInt(v, 10);
                    return isNaN(v) || v < min ? min : v;
                }

                function isEmptySelect($sel) {
                    const inst = $sel[0] && $sel[0].selectize;
                    if (inst) {
                        const v = inst.getValue();
                        return !v || (Array.isArray(v) && v.length === 0);
                    }
                    const val = $sel.val();
                    return !val || String(val).trim() === '';
                }

                async function focusFirstEmptyRoute() {
                    const $target = getFirstEmptySelect();
                    if (!$target || !$target.length) return;

                    await scrollToFieldInModal($target);
                    const inst = await waitForSelectizeReady($target);
                    if (inst) {
                        inst.focus();
                        inst.open();
                    } else {
                        $target.trigger('focus');
                    }
                }

                async function focusNextRouteFrom($currentSelect) {
                    const $row = $currentSelect.closest('.route-row');
                    const idx = parseInt($row.attr('data-row-index'), 10) || 1;
                    const $next = $('#route_rows_container .route-row[data-row-index="' + (idx + 1) + '"] .route-location-select');
                    if (!$next.length) return;

                    await scrollToFieldInModal($next);
                    const inst = await waitForSelectizeReady($next);
                    if (inst) {
                        inst.focus();
                        inst.open();
                    } else {
                        $next.trigger('focus');
                    }
                }

                // ===== RELABEL & STATE =====
                function relabelRows() {
                    const $rows = $('#route_rows_container .route-row');
                    const total = $rows.length;

                    $rows.each(function(i) {
                        const idx = i + 1;
                        $(this).attr('data-row-index', idx);
                        $(this).find('.route-chip').text('#Day ' + idx);
                    });

                    // reflect total back to nights
                    $('#nights_count').val(total);

                    // disable delete when only one row left
                    const $allDeletes = $rows.find('.remove-route');
                    $allDeletes.prop('disabled', false);
                    if (total === 1) $rows.first().find('.remove-route').prop('disabled', true);
                }

                // ===== ROW ADD/REMOVE =====
                function addRow(suppressFocus = false) {
                    const currentCount = $('#route_rows_container .route-row').length;
                    const nextIndex = currentCount + 1;

                    const rowHTML = `
    <div class="row route-row add-rows mb-3" data-row-index="${nextIndex}">
      <input type="hidden" name="hid_stored_route_location_id[]" value="">
      <div class="col-md-10">
        <label class="form-label route-label mb-1">
          <span class="route-chip">#Day ${nextIndex}</span>
          <span class="text-danger">*</span>
        </label>
        <div class="form-group">
          <select id="route_location_${nextIndex}" name="route_location[]"
                  class="form-select route-location-select" required></select>
        </div>
      </div>
      <div class="col-md-2 d-flex align-items-end justify-content-end">
        <button type="button" class="btn btn-outline-primary btn-sm remove-route">
          <i class="ti ti-trash ti-tada-hover"></i>
        </button>
      </div>
    </div>`;

                    if (currentCount === 0) {
                        $('#route_rows_container').append(rowHTML);
                    } else {
                        $('#route_rows_container .route-row:last').after(rowHTML);
                    }

                    const $sel = $('#route_location_' + nextIndex);
                    initLocationControl($sel);
                    relabelRows();

                    if (!suppressFocus) {
                        scrollToFieldInModal($sel).then(async () => {
                            const inst = await waitForSelectizeReady($sel);
                            if (inst) {
                                inst.focus();
                                inst.open();
                            } else {
                                $sel.trigger('focus');
                            }
                        });
                    }
                }

                function getModalBody($fromEl) {
                    const $mb = $fromEl.closest('.modal-body');
                    if ($mb.length) return $mb;
                    const $fallback = $('#suggestedrouteModal .modal-body');
                    return $fallback.length ? $fallback : null;
                }

                function scrollToFieldInModal($el, offset = 12, duration = 250) {
                    return new Promise(resolve => {
                        const $container = getModalBody($el);
                        if (!$container) {
                            resolve();
                            return;
                        }

                        const $row = $el.closest('.route-row');
                        const $target = $row.length ? $row : $el;

                        // robust math: container->target distance in document space
                        const containerTop = $container.offset().top;
                        const targetTop = $target.offset().top;
                        const currentScroll = $container.scrollTop();

                        const delta = targetTop - containerTop; // px inside container
                        const next = Math.max(0, currentScroll + delta - offset);

                        $container.animate({
                            scrollTop: next
                        }, duration, resolve);
                    });
                }

                function waitForSelectizeReady($sel, attempts = 20, delay = 25) {
                    return new Promise(resolve => {
                        function tick(n) {
                            const inst = $sel[0] && $sel[0].selectize;
                            if (inst) return resolve(inst);
                            if (n <= 0) return resolve(null);
                            setTimeout(() => tick(n - 1), delay);
                        }
                        tick(attempts);
                    });
                }

                function getFirstEmptySelect() {
                    const $selects = $('#route_rows_container .route-location-select');
                    if ($selects.length === 0) return $();

                    // 1) first truly empty (Selectize value or native value)
                    for (let i = 0; i < $selects.length; i++) {
                        const el = $selects[i];
                        const $el = $(el);
                        const inst = el.selectize;
                        const val = inst ? inst.getValue() : $el.val();
                        const empty = !val || (Array.isArray(val) && val.length === 0);
                        if (empty) return $el;
                    }
                    // 2) fallback to first
                    return $($selects.get(0));
                }

                // Core handler (your existing logic moved here, unchanged semantically)
                function handleNightsChange() {
                    let nights = clampInt($('#nights_count').val(), 0);

                    const $rows = $('#route_rows_container .route-row');
                    let currentTotal = $rows.length;
                    const desiredTotal = nights;

                    if (desiredTotal > currentTotal) {
                        for (let i = 0; i < (desiredTotal - currentTotal); i++) {
                            addRow(true); // silent add
                        }
                        relabelRows();
                        setTimeout(() => {
                            focusFirstEmptyRoute();
                        }, 60);
                        return;
                    }

                    if (desiredTotal < currentTotal) {
                        // remove only NEW rows (no stored id), from the end
                        let toRemove = currentTotal - desiredTotal;
                        const list = $('#route_rows_container .route-row').get().reverse();

                        for (const node of list) {
                            if (toRemove <= 0) break;
                            const $row = $(node);
                            const val = $row.find('input[name="hid_stored_route_location_id[]"]').val();
                            const persisted = val && String(val).trim() !== '';
                            if (!persisted) {
                                const $sel = $row.find('.route-location-select');
                                const inst = $sel[0] && $sel[0].selectize;
                                if (inst) inst.destroy();
                                $row.remove();
                                toRemove--;
                            }
                        }

                        const newCount = $('#route_rows_container .route-row').length;
                        if (newCount !== desiredTotal) {
                            // couldn't remove enough because persisted rows remain; reflect actual count
                            $('#nights_count').val(newCount);
                        }
                        relabelRows();
                        focusFirstEmptyRoute();
                        return;
                    }

                    // equal
                    relabelRows();
                    focusFirstEmptyRoute();
                }

                // Debounced wrapper to avoid runaway updates from spinner/auto-repeat
                const applyNightsChange = debounce(handleNightsChange, 150);

                // Bind debounced handler
                $('#nights_count').on('input change', applyNightsChange);

                // Remove row (destroy selectize to avoid leaks)
                $(document).on('click', '.remove-route', function() {
                    const storedId = $(this).data('stored-id');
                    const $row = $(this).closest('.route-row');

                    const $sel = $row.find('.route-location-select');
                    const inst = $sel[0] && $sel[0].selectize;
                    if (inst) inst.destroy();

                    if (storedId) {
                        $.ajax({
                            type: "POST",
                            url: "engine/ajax/__ajax_manage_location.php?type=confirm_delete_route_location",
                            data: {
                                _ID: storedId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.result === true) {
                                    TOAST_NOTIFICATION('success', 'Route Location Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to delete the Route Location', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            }
                        });
                    }

                    $row.remove();
                    if ($('#route_rows_container .route-row').length === 0) {
                        addRow(); // always keep at least 1 row
                    } else {
                        relabelRows();
                        focusFirstEmptyRoute();
                    }
                });

                // ===== INIT EXISTING SELECTS =====
                $('#route_rows_container .route-location-select').each(function() {
                    initLocationControl($(this));
                });
                relabelRows(); // sync chips + nights on load
                setTimeout(focusFirstEmptyRoute, 50);

                // ===== ADD BUTTONS =====
                // Preferred: single "+ Add" button near nights input
                $(document).on('click', '.btn-add-night', function() {
                    const $n = $('#nights_count');
                    let val = parseInt($n.val(), 10) || 0;
                    $n.val(val + 1).trigger('change');
                });

                // Backward-compat: if you still have any ".add-route-location" buttons
                $(document).on('click', '.add-route-location', function() {
                    addRow();
                });

                // ===== NIGHTS CONTROL (add diff; remove only new rows) =====
                $('#nights_count').on('input change', function() {
                    let nights = parseInt($(this).val(), 10);
                    if (isNaN(nights) || nights < 0) nights = 0;

                    const $rows = $('#route_rows_container .route-row');
                    let currentTotal = $rows.length;
                    const desiredTotal = nights;

                    if (desiredTotal > currentTotal) {
                        for (let i = 0; i < (desiredTotal - currentTotal); i++) addRow(true);
                        relabelRows();
                        // wait a moment so Selectize instances attach, then focus
                        setTimeout(() => {
                            focusFirstEmptyRoute();
                        }, 60);
                        return;
                    }

                    if (desiredTotal < currentTotal) {
                        // remove only NEW rows (no stored id), from the end
                        let toRemove = currentTotal - desiredTotal;
                        const list = $('#route_rows_container .route-row').get().reverse();

                        for (const node of list) {
                            if (toRemove <= 0) break;
                            const $row = $(node);
                            if (!isPersistedRow($row)) {
                                const $sel = $row.find('.route-location-select');
                                const inst = $sel[0] && $sel[0].selectize;
                                if (inst) inst.destroy();
                                $row.remove();
                                toRemove--;
                            }
                        }

                        const newCount = $('#route_rows_container .route-row').length;
                        if (newCount !== desiredTotal) {
                            // couldn't remove enough because persisted rows remain; reflect actual count
                            $('#nights_count').val(newCount);
                        }
                        relabelRows();
                        focusFirstEmptyRoute();
                        return;
                    }

                    // equal
                    relabelRows();
                    focusFirstEmptyRoute();
                });

                // ===== SUBMIT =====
                $("#ajax_suggested_route_details_form").on('submit', function(event) {
                    event.preventDefault();
                    var data = new FormData(this);
                    var spinner = $("#spinner");

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_location.php?type=add_route_suggestions',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                    }).done(function(response) {
                        spinner.hide();

                        if (!response || response.success === false) {
                            if (response?.errors?.route_required) {
                                TOAST_NOTIFICATION('error', 'Route Location is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response?.errors?.route_not_available) {
                                TOAST_NOTIFICATION('error', 'Something went wrong. Error in creating routes', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            return;
                        }

                        if (response.result) {
                            $('#suggested_route_LIST').DataTable().ajax.reload();
                            TOAST_NOTIFICATION('success', 'Route Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#suggestedrouteModal').modal('hide');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    });
                });

                // ===== FALLBACK TO EASY AUTOCOMPLETE =====
                if (!USE_SELECTIZE) {
                    $('#route_rows_container .route-location-select').each(function() {
                        initEasyAutocomplete($(this));
                    });
                }
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>