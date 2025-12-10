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

        $itinerary_plan_ID = $_GET['ITINERARY_ID'];
        $itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
        $entry_ticket_required = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'entry_ticket_required');
        $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');


?>
        <!-- Plugins css Ends-->
        <form id="incidental_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <h4 class="mb-2">Add Incidental Expenses</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12">
                <label for="components_type" class="form-label">Component Type<span class=" text-danger"> *</span></label>
                <select class="form-select" id="components_type" name="components_type" required autocomplete="off" placeholder="Choose the Component Type" aria-label="Default select example">
                    <option value=""></option>
                    <?php
                    if ($getguide == 1): ?>
                        <option value="1">Guide</option>
                    <?php endif; ?>
                    <?php if ($gethotspot == 1 && $entry_ticket_required == 1): ?>
                        <option value="2">Hotspot</option>
                    <?php endif; ?>
                    <?php if ($getactivity == 1): ?>
                        <option value="3">Activity</option>
                    <?php endif; ?>
                    <?php if ($itinerary_preference == 1 || $itinerary_preference == 3): ?>
                        <option value="4">Hotel</option>
                    <?php endif; ?>
                    <?php if ($itinerary_preference == 2 || $itinerary_preference == 3): ?>
                        <option value="5">Vendor</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12" id="guide_names" style="display: none;">
                <label for="guide_name" class="form-label">Guide Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select class="form-select form-control guide" id="guide_name" name="guide_name" autocomplete="off" aria-label="Default select example">
                        <?= getINCIDENTALEXPENSES_CHOOSE($itinerary_plan_ID, 'guide_select'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12" id="hotspot_names" style="display: none;">
                <label for="hotspot_name" class="form-label">Hotspot Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select class="form-select form-control" id="hotspot_name" name="hotspot_name" autocomplete="off" aria-label="Default select example">
                        <?= getINCIDENTALEXPENSES_CHOOSE($itinerary_plan_ID, 'hotspot_select'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12" id="activity_names" style="display: none;">
                <label for="activity_name" class="form-label">Activity Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select class="form-select form-control" id="activity_name" name="activity_name" autocomplete="off" aria-label="Default select example">
                        <option value="0">Choose Activity</option>
                        <?= getINCIDENTALEXPENSES_CHOOSE($itinerary_plan_ID, 'activity_select'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12" id="hotel_names" style="display: none;">
                <label for="hotel_name" class="form-label">Hotel Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select class="form-select form-control" id="hotel_name" name="hotel_name" autocomplete="off" aria-label="Default select example">
                        <?= getINCIDENTALEXPENSES_CHOOSE($itinerary_plan_ID, 'hotel_select'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12" id="vendor_names" style="display: none;">
                <label for="vendor_name" class="form-label">Vendor<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select class="form-select form-control" id="vendor_name" name="vendor_name" autocomplete="off" aria-label="Default select example">
                        <?= getINCIDENTALEXPENSES_CHOOSE($itinerary_plan_ID, 'vendor_select'); ?>
                    </select>
                </div>
            </div>
            <!-- Cost display -->
            <div id="show_guide_cost_available" style="display: none;" class="col-5 badge text-start bg-label-primary mt-2 mx-2">
            </div>
            <div id="show_hotspot_cost_available" style="display: none;" class="col-5 badge text-start bg-label-primary mt-2 mx-2"></div>
            <div id="show_activity_cost_available" style="display: none;" class="col-5 badge text-start bg-label-primary mt-2 mx-2"></div>
            <div id="show_hotel_cost_available" style="display: none;" class="col-5 badge text-start bg-label-primary mt-2 mx-2"></div>
            <div id="show_vehicle_cost_available" style="display: none;" class="col-5 badge text-start bg-label-primary mt-2 mx-2"></div>
            <div class="col-12">
                <label class="form-label w-100" for="incidental_charge_amount">Amount<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="incidental_charge_amount" name="incidental_charge_amount" required class="form-control" placeholder="Enter the Amount" value="" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off">
                    <input type="hidden" id="Plan_id" name="Plan_id" value="<?= $itinerary_plan_ID; ?>" hidden>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="incidental_reason">Reason<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="incidental_reason" name="incidental_reason" placeholder="Enter the Reason" required class="form-control required-field"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                var itinerary_plan_ID = "<?= $itinerary_plan_ID ?>"; // Embed PHP variable correctly
                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                $("select").selectize();

                //AJAX FORM SUBMIT
                $("#incidental_details_form").submit(function(event) {
                    var form = $('#incidental_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_incidentalexpenses_manage.php?type=incidentalcharge',
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
                            if (response.errors.charge_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Amount Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#addDRIVERCHARGEFORM').modal('hide');
                                window.location.reload();
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

                function refreshIncidentalChargeAmount() {
                    $('#incidental_charge_amount').val('');
                }

                $('#components_type').on('change', function() {
                    const selectedComponent = $(this).val();
                    $('#guide_names, #hotspot_names, #activity_names, #vendor_names, #show_guide_cost_available, #show_hotspot_cost_available, #show_activity_cost_available, #show_hotel_cost_available, #show_vehicle_cost_available').hide();

                    if (selectedComponent === '1') {
                        $('#guide_names').show();
                    } else if (selectedComponent === '2') {
                        $('#hotspot_names').show();
                    } else if (selectedComponent === '3') {
                        $('#activity_names').show();
                    } else if (selectedComponent === '4') {
                        $('#hotel_names').show();
                    } else if (selectedComponent === '5') {
                        $('#vendor_names').show();
                    }
                });


                // Listen for changes in the guide dropdown
                $('#guide_name').on('change', function() {
                    showOVERALLCOSTAMOUNTGUIDE(itinerary_plan_ID);
                });

                function showOVERALLCOSTAMOUNTGUIDE(itinerary_plan_ID) {
                    const generalCurrencySymbol = '₹';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=show_avail_margin_cost",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID, // Send the ID to the server
                            component_type: 1,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Update the cost in the relevant fields
                            $('#show_guide_cost_available').html(
                                `Available Amount: <span class="text-dark ms-1">${generalCurrencySymbol} ${parseFloat(response.total_avail_cost).toFixed(2)}</span>`
                            ).show();
                            $('#show_hotspot_cost_available').hide();
                            $('#show_activity_cost_available').hide();
                            $('#show_hotel_cost_available').hide();
                            $('#show_vehicle_cost_available').hide();
                            refreshIncidentalChargeAmount();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred:", error); // Log any errors
                        }
                    });
                }

                // Listen for changes in the hotspot dropdown
                $('#hotspot_name').on('change', function() {
                    showOVERALLCOSTAMOUNTHOTSPOT(itinerary_plan_ID);
                });

                function showOVERALLCOSTAMOUNTHOTSPOT(itinerary_plan_ID) {
                    const generalCurrencySymbol = '₹';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=show_avail_margin_cost",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID, // Send the ID to the server
                            component_type: 2,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Update the cost in the relevant fields
                            $('#show_hotspot_cost_available').html(
                                `Available Amount: <span class="text-dark ms-1">${generalCurrencySymbol} ${parseFloat(response.total_avail_cost).toFixed(2)}</span>`
                            ).show();
                            $('#show_guide_cost_available').hide();
                            $('#show_activity_cost_available').hide();
                            $('#show_hotel_cost_available').hide();
                            $('#show_vehicle_cost_available').hide();
                            refreshIncidentalChargeAmount();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred:", error); // Log any errors
                        }
                    });
                }


                // Listen for changes in the guide dropdown
                $('#activity_name').on('change', function() {
                    showOVERALLCOSTAMOUNTACTIVITY(itinerary_plan_ID);
                });

                function showOVERALLCOSTAMOUNTACTIVITY(itinerary_plan_ID) {
                    const generalCurrencySymbol = '₹';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=show_avail_margin_cost",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID, // Send the ID to the server
                            component_type: 3,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Update the cost in the relevant fields
                            $('#show_activity_cost_available').html(
                                `Available Amount: <span class="text-dark ms-1">${generalCurrencySymbol} ${parseFloat(response.total_avail_cost).toFixed(2)}</span>`
                            ).show();
                            $('#show_guide_cost_available').hide();
                            $('#show_hotspot_cost_available').hide();
                            $('#show_hotel_cost_available').hide();
                            $('#show_vehicle_cost_available').hide();
                            refreshIncidentalChargeAmount();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred:", error); // Log any errors
                        }
                    });
                }

                // Listen for changes in the hotel dropdown
                $('#hotel_name').on('change', function() {
                    var _cnf_itinerary_hotel_eligible_ID = $(this).val(); // Get the selected hotel ID
                    showOVERALLCOSTAMOUNT(_cnf_itinerary_hotel_eligible_ID, itinerary_plan_ID);
                });

                function showOVERALLCOSTAMOUNT(_cnf_itinerary_hotel_eligible_ID, itinerary_plan_ID) {
                    const generalCurrencySymbol = '₹';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=show_hotel_avail_margin_cost",
                        data: {
                            _cnf_itinerary_hotel_eligible_ID: _cnf_itinerary_hotel_eligible_ID,
                            itinerary_plan_ID: itinerary_plan_ID,
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#show_hotel_cost_available').html(
                                `Available Amount: <span class="text-dark ms-1">${generalCurrencySymbol} ${parseFloat(response.total_avail_cost).toFixed(2)}</span>`
                            ).show();
                            $('#show_guide_cost_available').hide();
                            $('#show_hotspot_cost_available').hide();
                            $('#show_activity_cost_available').hide();
                            $('#show_vehicle_cost_available').hide();
                            refreshIncidentalChargeAmount();
                        }
                    });
                }


                // Listen for changes in the hotel dropdown
                $('#vendor_name').on('change', function() {
                    var _cnf_itinerary_vendor_eligible_ID = $(this).val(); // Get the selected hotel ID
                    showOVERALLCOSTAMOUNTVENDOR(_cnf_itinerary_vendor_eligible_ID, itinerary_plan_ID);
                });

                function showOVERALLCOSTAMOUNTVENDOR(_cnf_itinerary_vendor_eligible_ID, itinerary_plan_ID) {
                    const generalCurrencySymbol = '₹';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=show_vendor_avail_margin_cost",
                        data: {
                            _cnf_itinerary_vendor_eligible_ID: _cnf_itinerary_vendor_eligible_ID,
                            itinerary_plan_ID: itinerary_plan_ID,
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#show_vehicle_cost_available').html(
                                `Available Amount: <span class="text-dark ms-1">${generalCurrencySymbol} ${parseFloat(response.total_avail_cost).toFixed(2)}</span>`
                            ).show();
                            $('#show_guide_cost_available').hide();
                            $('#show_hotspot_cost_available').hide();
                            $('#show_activity_cost_available').hide();
                            $('#show_hotel_cost_available').hide();
                            refreshIncidentalChargeAmount();
                        }
                    });
                }

                // Unified validation for incidental charge amount
                $('#incidental_charge_amount').on('input', function() {
                    const hotelCostVisible = $('#show_hotel_cost_available').is(':visible');
                    const vehicleCostVisible = $('#show_vehicle_cost_available').is(':visible');
                    const guideCostVisible = $('#show_guide_cost_available').is(':visible');
                    const hotspotCostVisible = $('#show_hotspot_cost_available').is(':visible');
                    const activityCostVisible = $('#show_activity_cost_available').is(':visible');

                    function getAmount(selector) {
                        const text = $(selector).text();
                        // Remove non-numeric characters (except for .) and parse as float
                        return parseFloat(text.replace(/[^\d.]/g, '')) || 0;
                    }

                    const availableAmount = guideCostVisible ?
                        getAmount('#show_guide_cost_available') :
                        hotspotCostVisible ?
                        getAmount('#show_hotspot_cost_available') :
                        activityCostVisible ?
                        getAmount('#show_activity_cost_available') :
                        hotelCostVisible ?
                        getAmount('#show_hotel_cost_available') :
                        vehicleCostVisible ?
                        getAmount('#show_vehicle_cost_available') :
                        0;

                    const enteredAmount = parseFloat($(this).val());
                    const saveButton = $('#incidental_details_form button[type="submit"]');

                    console.log(availableAmount);
                    console.log(enteredAmount);

                    if (!isNaN(enteredAmount) && enteredAmount > availableAmount) {
                        // Show error and disable save button
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds available cost', 'Error !!!');
                        saveButton.prop('disabled', true);
                    } else if (enteredAmount <= 0) {
                        TOAST_NOTIFICATION('warning', 'Payment amount must be greater than zero.', 'Error');
                        saveButton.prop('disabled', true);
                    } else {
                        // Remove error and enable save button
                        saveButton.prop('disabled', false);
                    }
                });


                function toggleFields() {
                    const selectedComponent = $('#components_type').val();

                    // Hide all fields initially and remove the 'required' attribute
                    $('#guide_names, #hotspot_names, #activity_names, #hotel_names, #vendor_names').hide();
                    $('#guide_name, #hotspot_name, #activity_name, #hotel_name, #vendor_name').prop('required', false);

                    // Show the field corresponding to the selected component type and add the 'required' attribute
                    switch (selectedComponent) {
                        case '1': // Guide
                            $('#guide_names').show();
                            $('#guide_name').prop('required', true);
                            break;
                        case '2': // Hotspot
                            $('#hotspot_names').show();
                            $('#hotspot_name').prop('required', true);
                            break;
                        case '3': // Activity
                            $('#activity_names').show();
                            $('#activity_name').prop('required', true);
                            break;
                        case '4': // Hotel
                            $('#hotel_names').show();
                            $('#hotel_name').prop('required', true);
                            break;
                        case '5': // Vendor
                            $('#vendor_names').show();
                            $('#vendor_name').prop('required', true);
                            break;
                        default:
                            // Do nothing for other cases
                            break;
                    }
                }

                // Initialize by checking the current selection
                toggleFields();

                // Attach event listener for change in component type
                $('#components_type').on('change', function() {
                    toggleFields();
                });
            });
        </script>

<?php
    endif;
endif;
?>