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

    if ($_GET['type'] == 'basic_info') :

        $pemitcost_id = $_GET['ID'];

?>

        <div class="row mt-3">
            <div class="col-md-12">
                <form id="" action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="vehicle_type" id="vehicle_type" required>
                                <?= getVEHICLETYPE('', 'select');   ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="selected_state">State <span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="selected_state[]" id="permit_state" onchange="toggleStateInputs();" required>
                                <option value="">Select Any One</option>
                            </select>
                        </div>

                        <input type="hidden" name="permit_cost_ID[]" id="hidden_permit_cost_ID" value="<?= $permit_cost; ?>" hidden>
                        <input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
                    </div>
                    <div class="row" id="stateInputContainer">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="selected_state"><?= $destination_state_id; ?><span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="state_cost[]" value="<?= $permit_cost; ?>">
                            <input class="form-control" type="hidden" name="state_cost[]" id="permit_cost_id">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="#" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l14 0"></path>
                                <path d="M5 12l4 4"></path>
                                <path d="M5 12l4 -4"></path>
                            </svg>Back</a>
                        <button type="submit" class="btn btn-primary float-end ms-2" id="permit_cost_form_submit">Save<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l14 0"></path>
                                <path d="M15 16l4 -4"></path>
                                <path d="M15 8l4 4"></path>
                            </svg></button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Make an AJAX request to fetch the state data from your PHP script using local jQuery
                $.ajax({
                    url: "engine/json/__JSONpermitstate.php",
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        // The 'data' variable contains the parsed JSON response
                        stateInputMap = data;
                        console.log(stateInputMap);

                        // Populate the dropdown and initialize Selectize here
                        populateDropdown();
                        initializeSelectize();
                    },
                    error: function(xhr, status, error) {
                        console.log("Request failed with status: " + status);
                    }
                });
                $("#permit_cost_form").submit(function(event) {
                    var form = $('#permit_cost_form')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vendor.php?type=permit_cost',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            // if (response.errros.hotel_room_type_title_required) {
                            //     TOAST_NOTIFICATION('warning', 'Room Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.preferred_for_required) {
                            //     TOAST_NOTIFICATION('warning', 'Choose Preferred for Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.hotel_room_title_required) {
                            //     TOAST_NOTIFICATION('warning', 'Room Title Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.air_conditioner_avilability_required) {
                            //     TOAST_NOTIFICATION('warning', 'Air Conditioner Availability Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.room_status_required) {
                            //     TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.room_ref_code_required) {
                            //     TOAST_NOTIFICATION('warning', 'Room Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.total_max_adult_required) {
                            //     TOAST_NOTIFICATION('warning', 'Max Adults Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.total_max_children_required) {
                            //     TOAST_NOTIFICATION('warning', 'Max Children Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.check_in_time_required) {
                            //     TOAST_NOTIFICATION('warning', 'Check-In Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // } else if (response.errros.check_out_time_required) {
                            //     TOAST_NOTIFICATION('warning', 'Check-Out Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            // }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Room Details Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Room Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

            var stateInputMap = {}; // Initialize an empty object

            function populateDropdown() {
                // Get the select element
                var select = document.getElementById("permit_state");

                // Clear existing options
                select.innerHTML = '<option value="">Select Any One</option>';

                // Populate the dropdown with valid options from stateInputMap
                for (var stateValue in stateInputMap) {
                    var option = document.createElement("option");
                    option.value = stateValue;
                    option.text = stateInputMap[stateValue];
                    select.appendChild(option);
                }
            }

            function initializeSelectize() {
                // Initialize Selectize for the select input
                $("select").selectize();
            }

            function toggleStateInputs() {
                // Get the selected state
                var selectedState = document.getElementById("permit_state").value;

                // Get the container for state-specific input fields
                var stateInputContainer = document.getElementById("stateInputContainer");

                // Clear any existing input fields
                stateInputContainer.innerHTML = "";

                // Check if a state is selected
                if (selectedState !== "") {
                    // Generate and append input fields for the states not selected
                    for (var stateValue in stateInputMap) {
                        if (stateValue !== selectedState) {
                            var stateName = stateInputMap[stateValue];

                            // Create a div with col-md-6 class
                            var columnDiv = document.createElement("div");
                            columnDiv.className = "col-md-2 mb-3";

                            var stateInputLabel = document.createElement("label");
                            stateInputLabel.className = "form-label";
                            stateInputLabel.textContent = stateName;
                            stateInputLabel.htmlFor = "state_cost_" + stateValue;

                            var stateInput = document.createElement("input");
                            stateInput.type = "text";
                            stateInput.id = "state_cost_" + stateValue;
                            stateInput.name = "state_cost[]";
                            stateInput.className = "form-control";
                            stateInput.placeholder = "₹";
                            // stateInput.required = true;
                            stateInput.setAttribute("data-parsley-trigger", "keyup");
                            stateInput.setAttribute("data-parsley-type", "number");
                            stateInput.setAttribute("data-parsley-whitespace", "trim");
                            stateInput.setAttribute("autocomplete", "off");

                            var stateInputhidden = document.createElement("input");
                            stateInputhidden.type = "hidden";
                            stateInputhidden.id = "state_cost_" + stateValue;
                            stateInputhidden.name = "statehidden_cost[]";
                            stateInputhidden.value = stateValue;
                            // Append the label and input field to the column div
                            columnDiv.appendChild(stateInputLabel);
                            columnDiv.appendChild(stateInput);
                            columnDiv.appendChild(stateInputhidden);
                            columnDiv.appendChild(stateInputhidden);

                            // Append the column div to the container
                            stateInputContainer.appendChild(columnDiv);
                        }
                    }
                }
            }

            // Initially populate the dropdown and hide all input fields
            populateDropdown();
            toggleStateInputs();
        </script>
<?php
    endif;
endif;

?>