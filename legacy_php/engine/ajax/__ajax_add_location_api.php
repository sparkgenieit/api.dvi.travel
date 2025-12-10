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
?>
        <style>
            .pac-container.pac-logo.hdpi,
            .pac-container.pac-logo {
                z-index: 9999999;
            }
        </style>

        <form id="ajax_location_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="LOCATIONFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <div class="col-md-12 add-rows">
                <div class="row location-row">
                    <div class="col-md-5">
                        <label class="form-label source_location" for="source_location">Source Location<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="source_location[]" autofocus class="form-control source-input" placeholder="Enter the Source Location" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label destination_location" for="destination_location">Destination Location<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location[]" class="form-control destination-input" placeholder="Enter the Destination Location" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-2 default_add">
                        <label class="form-label w-100">&nbsp;</label>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary add-location">+Add</button>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-between text-center pt-4">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn">Save</button>
                </div>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $(".add-rows").on("click", ".add-location", function() {
                    var newLocationField = `
                <div class="row location-row">
                    <div class="col-md-5 mt-2">
                        <label class="form-label source_location" for="source_location">Source Location<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="source_location[]" class="form-control source-input" placeholder="Enter the Source Location" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-5 mt-2">
                        <label class="form-label destination_location" for="destination_location">Destination Location<span class="text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="destination_location[]" class="form-control destination-input" placeholder="Enter the Destination Location" autocomplete="off" required />
                        </div>
                    </div>
                </div>`;

                    $(".location-row:last").after(newLocationField);

                    // Initialize Google Places Autocomplete for the new fields
                    var sourceInput = $(".location-row:last .source-input")[0];
                    var destinationInput = $(".location-row:last .destination-input")[0];

                    var sourceAutocomplete = new google.maps.places.Autocomplete(sourceInput, {
                        componentRestrictions: {
                            country: 'IN'
                        }
                    });

                    var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
                        componentRestrictions: {
                            country: 'IN'
                        }
                    });

                    // If there is a previous row, set its destination as the source for the new row
                    var previousRow = $(".location-row").eq(-2);
                    if (previousRow.length) {
                        var previousDestination = previousRow.find(".destination-input").val();
                        sourceInput.value = previousDestination;
                    }
                });

                // Initialize Google Places Autocomplete for the first fields
                var initialSourceInput = $('.source-input').first()[0];
                var initialDestinationInput = $('.destination-input').first()[0];

                var initialSourceAutocomplete = new google.maps.places.Autocomplete(initialSourceInput, {
                    componentRestrictions: {
                        country: 'IN'
                    }
                });

                var initialDestinationAutocomplete = new google.maps.places.Autocomplete(initialDestinationInput, {
                    componentRestrictions: {
                        country: 'IN'
                    }
                });

                // Add Google Places Autocomplete to initial source and destination inputs
                var initialSourceInput = $('.source-input').first()[0];
                var initialDestinationInput = $('.destination-input').first()[0];
                initializeAutocomplete(initialSourceInput, false);
                initializeAutocomplete(initialDestinationInput, true);

                // Add click event to update next source location when selecting a result
                $(".add-rows").on("click", ".pac-item", function() {
                    var currentRow = $(this).closest(".location-row");
                    var nextRow = currentRow.next(".location-row");

                    if (nextRow.length) {
                        var nextSourceInput = nextRow.find(".source-input");
                        nextSourceInput.val($(this).text());
                    }
                });
            });

            // Function to initialize Google Places Autocomplete for an input
            function initializeAutocomplete(input, updateSource) {
                var autocomplete = new google.maps.places.Autocomplete(input, {
                    componentRestrictions: {
                        country: 'IN'
                    }
                });
                // Add a listener for the place_changed event
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    if (place && place.formatted_address) {
                        if (updateSource) {
                            // Update the next source location with the full address
                            var currentRow = $(input).closest(".location-row");
                            var nextRow = currentRow.next(".location-row");

                            if (nextRow.length) {
                                var nextSourceInput = nextRow.find(".source-input");
                                nextSourceInput.val(place.formatted_address);
                            }
                        }
                    }
                });
            }

            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#ajax_location_details_form").submit(function(event) {
                    var form = $('#ajax_location_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    $(this).find("button[type='submit']").prop('disabled', true);
                    spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_location.php?type=add',
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
                            if (response.errors.hotel_category_code) {
                                MODAL_ALERT(response.errors.hotel_category_code_required);
                                $('#hotel_category_code').focus();
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
                                $('#ajax_location_details_form')[0].reset();
                                $('#addLOCATIONFORM').modal('hide');
                                $('#location_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
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

                                                    <input type="text" id="vehicle_toll_charge" name="vehicle_toll_charge[]" required class="form-control" placeholder="Enter Toll Charge" value="<?= ($toll_charge == "") ? 0 : $toll_charge ?>" required autocomplete="off" />
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
                <form id="ajax_via_route_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                    <input type="hidden" name="hidden_location_id" value="<?= $location_ID ?>" />
                    <input type="hidden" name="hidden_source_location" value="<?= $source_location ?>" />
                    <input type="hidden" name="hidden_source_location_lattitude" value="<?= $source_location_lattitude ?>" />
                    <input type="hidden" name="hidden_source_location_longitude" value="<?= $source_location_longitude ?>" />

                    <?php /* <div class="col-md-4 text-center p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="m-0"><strong>Add Via</strong></h5>
                                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addVIAROUTE(this, '1')">
                                        Add More
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="operating-hours-entry">
                                    <div class="time-fields mb-2 viaroute-row" id="time-fields-1">
                                        <div class="form-group">
                                            <input id="via_route_1" name="via_route[]" class="form-control viaroute-input" type="text" placeholder="Select Via Location" required value="" />

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between text-center pt-4">
                                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn">Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8  p-2">
                        <h5 class="p-1 text-center"><strong>List Of Via Routes</strong></h5>
                        <div class="card-body dataTable_select text-nowrap">
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

                        <div class="mt-5">
                            <div class="text-end">
                                <a href="locations.php" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div> */ ?>

                    <div class="col-md-12 m-0">
                        <div class="card-body dataTable_select text-nowrap pt-0 px-2">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="m-0"><strong>List Of Via Routes</strong></h5>
                                <a class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#viarouteModal">+ Add Via Route</a>
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
                </form>
            </div>
        </div>

        <!-- Via Route Modal -->
        <div class="modal fade" id="viarouteModal" tabindex="-1" aria-labelledby="viarouteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-location-form-data">
                        <form id="ajax_location_details_form" action="" method="post" data-parsley-validate="" novalidate="">
                            <div class="text-center">
                                <h4 class="mb-3" id="LOCATIONFORMLabel">Add Via Route</h4>
                            </div>
                            <div class="row ">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="via_route_location">Via Route Location<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="via_route_location" autofocus="" class="form-control" placeholder="Enter the Via Route Location" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="via_route_location_longitude">Via Route Location Longitude<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="via_route_location_longitude" class="form-control" placeholder="Enter the Via Route Location Longitude" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="via_route_location_lattitude">Via Route Location Lattiude<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="via_route_location_lattitude" class="form-control" placeholder="Enter the Via Route Location Lattiude" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="via_route_location_city">Via Route Location City<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="via_route_location_city" class="form-control" placeholder="Enter the Via Route Location City" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="distance_from_source_location">Distance from source Location<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="distance_from_source_location" class="form-control" placeholder="Enter the Distance from source Location" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="duration_from_source_location">Duration from source Location<span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="duration_from_source_location" class="form-control" placeholder="Enter the Duration from source Location" autocomplete="off" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between text-center pt-4">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn">Save</button>
                            </div>
                        </form>

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

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {


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
                        //console.log(data);
                        if (!response.success) {
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            TOAST_NOTIFICATION('error', 'Something Went wrong..', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE

                            if (response.result_success == false) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                //SUCCESS RESPOSNE
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                                TOAST_NOTIFICATION('success', 'Toll Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });


                var sourceLatitude = <?= $source_location_longitude ?>;
                var sourceLongitude = <?= $source_location_longitude ?>;
                var destinationLatitude = <?= $destination_location_lattitude ?>;
                var destinationLongitude = <?= $destination_location_longitude ?>;

                var viarouteInput = $(".viaroute-row:last .viaroute-input")[0];

                var minLatitude = Math.min(sourceLatitude, destinationLatitude);
                var maxLatitude = Math.max(sourceLatitude, destinationLatitude);
                var minLongitude = Math.min(sourceLongitude, destinationLongitude);
                var maxLongitude = Math.max(sourceLongitude, destinationLongitude);

                // Creating autocomplete without bounds
                var viarouteAutocomplete = new google.maps.places.Autocomplete(viarouteInput, {
                    types: ['geocode'],
                    componentRestrictions: {
                        country: 'IN' // Restricting to India
                    }
                });


                var selectedFromAutocomplete = false;

                // Adding listener for place_changed event

                /*viarouteAutocomplete.addListener('place_changed', function() {
                    var place = viarouteAutocomplete.getPlace();
                    if (!place || !place.geometry || !isLocationBetween(place.geometry.location.lat(), place.geometry.location.lng())) {
                        // Clear the input field or handle invalid input
                        viarouteInput.value = '';
                        TOAST_NOTIFICATION('error', 'Please select the place between source and destination', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }
                });*/

                /* viarouteInput.addEventListener('input', function() {
                     var inputText = viarouteInput.value;
                     if (!selectedFromAutocomplete) {
                         // Initialize AutocompleteService
                         //var autocompleteService = new google.maps.places.AutocompleteService();

                         // Fetch predictions based on input text
                         viarouteAutocomplete.getPlacePredictions({
                             input: inputText
                         }, function(predictions, status) {
                             if (status === google.maps.places.PlacesServiceStatus.OK) {
                                 if (Array.isArray(predictions) && predictions.length > 0) {
                                     var matches = predictions.some(function(prediction) {
                                         return prediction.structured_formatting.main_text.toLowerCase().includes(inputText.toLowerCase()) ||
                                             prediction.structured_formatting.secondary_text.toLowerCase().includes(inputText.toLowerCase());
                                     });
                                     console.log("Matches:", matches); // Log matches to debug
                                     if (!matches) {
                                         // Clear the input field if the typed value is not in the autocomplete suggestions
                                         viarouteInput.value = '';
                                     }
                                 } else {
                                     console.log("No predictions found."); // Log if no predictions are found
                                 }
                             } else {
                                 console.log("Places service status not OK."); // Log if Places service status is not OK
                             }
                         });
                     }
                     selectedFromAutocomplete = false;
                 });*/


                // Function to check if the location is between Kanchipuram and Vellore
                function isLocationBetween(latitude, longitude) {
                    //alert(latitude);
                    //alert(longitude);
                    return latitude >= minLatitude && latitude <= maxLatitude && longitude >= minLongitude && longitude <= maxLongitude;
                }

                //VIA ROUTE AJAX FORM SUBMIT
                $("#ajax_via_route_details_form").submit(function(event) {
                    var form = $('#ajax_via_route_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    //$(this).find("button[type='submit']").prop('disabled', true);
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
                                TOAST_NOTIFICATION('error', 'Via Route Missing', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.location_not_available) {
                                TOAST_NOTIFICATION('error', 'Entered via location is not available. Enter a valid via location', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#via_route_LIST').DataTable().ajax.reload();
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
                                $('#ajax_via_route_details_form')[0].reset();
                                $('#via_route_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
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
                            return '<div class="flex align-items-center list-user-action"> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEVIAROUTEMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                        }
                    }],
                });
            });

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

            function addVIAROUTE(button, day_text) {
                var parentElement = button.parentElement;

                // Get the grandparent element (parent of the parent)
                var grandparentElement_sub = parentElement.parentNode;
                var grandparentElement = grandparentElement_sub.parentNode;

                // Find the last .operating-hours-entry element within the parent element
                var operatingHoursEntry = grandparentElement.querySelector('.operating-hours-entry:last-child');

                var time_filed_count = $('.operating-hours-entry .time-fields#time-fields-' + day_text).length++;
                // Append the cloned time-fields to the operatingHoursEntry
                //operatingHoursEntry.parentNode.insertBefore(newTimeFields, operatingHoursEntry.nextSibling);
                operatingHoursEntry.insertAdjacentHTML('beforeend', '<div class="time-fields mb-2 viaroute-row" id="time-fields-1"><div class="form-group"><input id="via_route_' + day_text + '" name="via_route[]" class="form-control viaroute-input" type="text" placeholder="Select Via Location" required value="" /></div></div>');

                var sourceLatitude = <?= $source_location_longitude ?>;
                var sourceLongitude = <?= $source_location_longitude ?>;
                var destinationLatitude = <?= $destination_location_lattitude ?>;
                var destinationLongitude = <?= $destination_location_longitude ?>;

                var viarouteInput = $(".viaroute-row:last .viaroute-input")[0];

                var minLatitude = Math.min(sourceLatitude, destinationLatitude);
                var maxLatitude = Math.max(sourceLatitude, destinationLatitude);
                var minLongitude = Math.min(sourceLongitude, destinationLongitude);
                var maxLongitude = Math.max(sourceLongitude, destinationLongitude);

                // Creating autocomplete without bounds
                var viarouteAutocomplete = new google.maps.places.Autocomplete(viarouteInput, {
                    types: ['geocode'],
                    componentRestrictions: {
                        country: 'IN' // Restricting to India
                    }
                });

                // Adding listener for place_changed event
                /*viarouteAutocomplete.addListener('place_changed', function() {
                    var place = viarouteAutocomplete.getPlace();
                    if (!place.geometry || !isLocationBetween(place.geometry.location.lat(), place.geometry.location.lng())) {
                        // Clear the input field or handle invalid input
                        viarouteInput.value = '';
                        TOAST_NOTIFICATION('error', 'Please select the place between source and destination', 'Error !!!', '', '', '', '', '', '', '', '', '');

                    }
                });*/

                // Function to check if the location is between Kanchipuram and Vellore
                function isLocationBetween(latitude, longitude) {
                    return latitude >= minLatitude && latitude <= maxLatitude && longitude >= minLongitude && longitude <= maxLongitude;
                }

            }
        </script>

<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>