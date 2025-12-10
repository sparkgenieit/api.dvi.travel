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
        <div class="row mb-4">
            <div class="col-12 px-0">
                <div class="card p-3">
                    <h5 class="card-title text-uppercase">Filter</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="quote_id">Quote ID</label>
                            <input type="text" id="quote_id" name="quote_id" class="form-control" placeholder="Enter the Quote ID">
                        </div>
                        <div class="col-2">
                            <?php if ($logged_vendor_id != '' && $logged_vendor_id != '0'):
                                $SELECTED_VALUE = '5';
                                $component_disabled = 'disabled';
                            else:
                                $SELECTED_VALUE = '';
                                $component_disabled = '';
                            endif;
                            ?>
                            <label for="components_type" class="form-label">Component Type</label>
                            <select class="form-select" id="components_type" name="components_type" autocomplete="off" aria-label="Default select example" <?= $component_disabled ?>>
                                <?= getCOMPONENTS_LEDGER($SELECTED_VALUE, 'select'); ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="itinerary_from_date" class="form-label">From Date</label>
                            <input type="text" class="form-control" required id="itinerary_from_date" name="itinerary_from_date" autocomplete="off" value="" placeholder="dd/mm/yyy" />
                        </div>
                        <div class="col-2">
                            <label for="itinerary_to_date" class="form-label">To Date</label>
                            <input type="text" class="form-control" required id="itinerary_to_date" name="itinerary_to_date" autocomplete="off" value="" placeholder="dd/mm/yyy" />
                        </div>
                        <div class="col-3" id="guide_names" style="display: none;">
                            <label for="guide_name" class="form-label">Guide Name</label>
                            <div class="form-group">
                                <select class="form-select form-control guide" id="guide_name" name="guide_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getGUIDEDETAILS('', 'select'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="hotspot_names" style="display: none;">
                            <label for="hotspot_name" class="form-label">Hotspot Name</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="hotspot_name" name="hotspot_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getHOTSPOTDETAILS('', 'multiselect'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="activity_names" style="display: none;">
                            <label for="activity_name" class="form-label">Activity Name</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="activity_name" name="activity_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getACTIVITYDETAILS('', 'select', ''); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="hotel_names" style="display: none;">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="hotel_name" name="hotel_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getHOTEL_DETAIL('', '', 'select'); ?>
                                </select>
                            </div>
                        </div>
                        <?php if ($logged_vendor_id != '' && $logged_vendor_id != '0'):
                            $SELECTED_VALUE = $logged_vendor_id;
                            $component_disabled = 'disabled';
                        else:
                            $SELECTED_VALUE = '';
                            $component_disabled = '';
                        endif;
                        ?>
                        <div class="col-3" id="vendor_names" style="display: none;">
                            <label for="vendor_name" class="form-label">Vendor</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="vendor_name" name="vendor_name" autocomplete="off" aria-label="Default select example" <?= $component_disabled ?>>
                                    <option value="0"> All</option>
                                    <?= getVENDOR_DETAILS($SELECTED_VALUE, 'select'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="branch_names" style="display: none;">
                            <label for="branch_name" class="form-label">Branch</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="branch_name" name="branch_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getVENDORBRANCHDETAIL('', $SELECTED_VALUE, 'select'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="vehicle_names" style="display: none;">
                            <label for="vehicle_name" class="form-label">Vehicle</label>
                            <div class="form-group">
                                <select class="form-select form-control" id="vehicle_name" name="vehicle_name" autocomplete="off" aria-label="Default select example">
                                    <option value="0"> All</option>
                                    <?= getVEHICLETYPE('', 'select'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-3" id="agent_names" style="display: none;">
                            <label for="agent_name" class="form-label">Agent</label>
                            <div class="form-group">
                                <select name="agent_name" id="agent_name" class="form-select form-control" autocomplete="off" aria-label="Default select example">
                                <option value="0"> All</option>    
                                <?= getAGENT_details('', '', 'agent_with_company') ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <a type="button" href="accountsmanagerledger.php" class="btn btn-primary text-white">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 px-0">
                <span id="showACCOUNTSMANAGERALLLIST"></span>
                <span id="showACCOUNTSMANAGERGUIDELIST"></span>
                <span id="showACCOUNTSMANAGERHOTSPOTLIST"></span>
                <span id="showACCOUNTSMANAGERACTIVITYLIST"></span>
                <span id="showACCOUNTSMANAGERHOTELLIST"></span>
                <span id="showACCOUNTSMANAGERVEHICLELIST"></span>
                <span id="showACCOUNTSMANAGERAGENTLIST"></span>
            </div>
        </div>

         <!-- Global Center Loader Overlay -->
         <div id="globalLoader" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.6);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
">
            <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!--/ Account Manager Payout Pay Now Modal -->
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
// Functions to show/hide loader
function showGlobalLoader() {
                    $('#globalLoader').fadeIn(100);
                }

                function hideGlobalLoader() {
                    $('#globalLoader').fadeOut(100);
                }

                // Auto show loader on ANY AJAX request globally
                $(document).ajaxStart(function() {
                    showGlobalLoader();
                });

                $(document).ajaxStop(function() {
                    hideGlobalLoader();
                });
                $("select").selectize();

                // Initialize the flatpickr datepickers
                let fromDate, toDate, guide_name, hotspot_name, activity_name, hotel_name, vendor_name, branch_name, vehicle_name, agent_name;
                // Calculate current date and 30 days before
                const currentDate = new Date();
                const formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                const thirtyDaysBefore = new Date();
                thirtyDaysBefore.setDate(currentDate.getDate() - 30); // Subtract 30 days
                const formattedThirtyDaysBefore = flatpickr.formatDate(thirtyDaysBefore, "d/m/Y");

                // Initialize the "From Date" Flatpickr with default value
                let fromDatePicker = flatpickr("#itinerary_from_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedThirtyDaysBefore, // Set default to 30 days before
                    onChange: function(selectedDates, dateStr, instance) {
                        fromDate = dateStr; // Update the fromDate variable

                        // Update minDate of To Date picker to restrict dates before the selected From Date
                        toDatePicker.set("minDate", selectedDates[0]);
                        updateComponentData();
                    }
                });

                // Initialize the "To Date" Flatpickr with default value
                let toDatePicker = flatpickr("#itinerary_to_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedCurrentDate, // Set default to current date
                    minDate: thirtyDaysBefore, // Initially set minimum date to 30 days before current date
                    onChange: function(selectedDates, dateStr, instance) {
                        toDate = dateStr; // Update the toDate variable
                        updateComponentData();
                    }
                });

                // Default values
                fromDate = formattedThirtyDaysBefore;
                toDate = formattedCurrentDate;

                // Global variable to store selectedQuoteId
                let selectedQuoteId = null;

                // Inside the EasyAutocomplete configuration
                var quote_id = {
                    url: function(phrase) {
                        return "engine/json/__JSONaccountsmangerquote.php?phrase=" + encodeURIComponent(phrase) + "&format=json";
                    },
                    getValue: "get_quote_ID", // Assuming the response contains 'get_quote_ID' key
                    list: {
                        onChooseEvent: function() {
                            selectedQuoteId = $("#quote_id").val(); // Store the selected Quote ID value
                            updateComponentData(); // Update component data when a quote is selected
                        },
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#quote_id").easyAutocomplete(quote_id);

                $("#quote_id").click(function() {
                    $(this).focus().select();
                });

                $("#quote_id").on('input', function() {
                    if (!$(this).val().trim()) {
                        selectedQuoteId = ''; // ? CLEAR THE STORED QUOTE ID
                        $('#export-accounts-quoteid-btn').addClass('d-none'); // Hide Export P&L Button
                        updateComponentData(); // Reload normal data
                        updateExportBtn();
                        updateExportPURCHASEBtn();
                    }
                });

                // Trigger branch name through vendor name selection
                var vendorNameSelectize = $('#vendor_name').selectize()[0].selectize;
                var vendorBranchSelectize = $('#branch_name').selectize()[0].selectize;

                // Listen for the change event on Selectize for Vendor Name
                vendorNameSelectize.on('change', function() {
                    var vendorNameValue = vendorNameSelectize.getValue();
                    if (vendorNameValue !== '' && vendorNameValue !== '0') {
                        show_branch_of_the_vendor('select_branch', vendorNameValue);
                    }
                });

                function show_branch_of_the_vendor(TYPE, ID) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_export_vehicle_overall_pricebook.php',
                        data: {
                            ID: ID,
                            TYPE: TYPE
                        },
                        success: function(response) {
                            $('#branch_names').html(response);
                            var vendorBranchSelectize = $('#branch_name').selectize()[0].selectize;

                            vendorBranchSelectize.on('change', function() {
                                handlevehicleChange();
                            });
                        }
                    });
                }

                <?php if ($logged_vendor_id != '' && $logged_vendor_id != '0'): ?>
                    let components_type = $('#components_type').val();
                    updateComponentData(components_type);

                    vendor_name = $('#vendor_name').val();

                    branch_name = $('#branch_name').val();
                    updateComponentData();

                <?php else: ?>
                    // Default component type value (Guide)
                    const defaultComponentType = '0';

                    // Set the default component type to 'Guide' and call the function to show guide data
                    $('#components_type').val(defaultComponentType);
                    updateComponentData();
                <?php endif; ?>

                $('#guide_name').on('change', function() {
                    guide_name = $(this).val();
                    updateComponentData();
                });

                $('#hotspot_name').on('change', function() {
                    hotspot_name = $(this).val();
                    updateComponentData();
                });

                $('#activity_name').on('change', function() {
                    activity_name = $(this).val();
                    updateComponentData();
                });

                $('#hotel_name').on('change', function() {
                    hotel_name = $(this).val();
                    updateComponentData();
                });

                $('#vendor_name').on('change', function() {
                    vendor_name = $(this).val();
                    updateComponentData();
                });

                $('#branch_name').on('change', function() {
                    branch_name = $(this).val();
                    updateComponentData();
                });

                $('#vehicle_name').on('change', function() {
                    vehicle_name = $(this).val();
                    updateComponentData();
                });

                $('#agent_name').on('change', function() {
                    agent_name = $(this).val();
                    console.log(agent_name);
                    updateComponentData();
                });

                // Handle change event on dropdown
                $('#components_type').on('change', function() {
                    const selectedValue = $(this).val(); // Get the selected value
                    updateComponentData(selectedValue);
                });

                // Function to update the component data based on selected values
                function updateComponentData(componentType = $('#components_type').val()) {
                    // Hide all elements first
                    $('#showACCOUNTSMANAGERALLSUMMARY').hide();
                    $('#showACCOUNTSMANAGERALLLIST').hide();
                    $('#showACCOUNTSMANAGERGUIDELIST').hide();
                    $('#showACCOUNTSMANAGERHOTSPOTLIST').hide();
                    $('#showACCOUNTSMANAGERACTIVITYLIST').hide();
                    $('#showACCOUNTSMANAGERHOTELLIST').hide();
                    $('#showACCOUNTSMANAGERVEHICLELIST').hide();
                    $('#showACCOUNTSMANAGERAGENTLIST').hide();

                    // Show the corresponding list based on selected component type
                    switch (componentType) {
                        case '0':
                            show_ACCOUNTSMANAGER_ALL_DATA(fromDate, toDate, selectedQuoteId);
                            $('#showACCOUNTSMANAGERALLLIST').show(); // Show the ALL list
                            break;
                        case '1':
                            show_ACCOUNTSMANAGER_GUIDE_DATA(fromDate, toDate, selectedQuoteId, guide_name);
                            $('#showACCOUNTSMANAGERGUIDELIST').show(); // Show the guide list
                            break;
                        case '2':
                            show_ACCOUNTSMANAGER_HOTSPOT_DATA(fromDate, toDate, selectedQuoteId, hotspot_name);
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').show(); // Show the hotspot list
                            break;
                        case '3':
                            show_ACCOUNTSMANAGER_ACTIVITY_DATA(fromDate, toDate, selectedQuoteId, activity_name);
                            $('#showACCOUNTSMANAGERACTIVITYLIST').show(); // Show the activity list
                            break;
                        case '4':
                            show_ACCOUNTSMANAGER_HOTEL_DATA(fromDate, toDate, selectedQuoteId, hotel_name);
                            $('#showACCOUNTSMANAGERHOTELLIST').show(); // Show the hotel list
                            break;
                        case '5':
                            show_ACCOUNTSMANAGER_VEHICLE_DATA(fromDate, toDate, selectedQuoteId, vendor_name, branch_name, vehicle_name);
                            $('#showACCOUNTSMANAGERVEHICLELIST').show(); // Show the vehicle list
                            break;
                        case '6':
                            show_ACCOUNTSMANAGER_AGENT_DATA(fromDate, toDate, selectedQuoteId, agent_name);
                            $('#showACCOUNTSMANAGERAGENTLIST').show(); // Show the vehicle list
                            break;
                        default:
                            console.log('Invalid component');
                    }
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_DATA(fromDate, toDate, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_ledger.php?type=show_form_all',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLLIST').html(response).show();
                        }
                    });
                }

                // Function to show guide data
                function show_ACCOUNTSMANAGER_GUIDE_DATA(fromDate, toDate, selectedQuoteId, guide_name) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerguide_ledger.php?type=show_form_guide',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            guide_name: guide_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERGUIDELIST').html(response).show();
                        }
                    });
                }

                // Function to show hotspot data
                function show_ACCOUNTSMANAGER_HOTSPOT_DATA(fromDate, toDate, selectedQuoteId, hotspot_name) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotspot_ledger.php?type=show_form_hotspot',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            hotspot_name: hotspot_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').html(response).show();
                        }
                    });
                }

                // Function to show activity data
                function show_ACCOUNTSMANAGER_ACTIVITY_DATA(fromDate, toDate, selectedQuoteId, activity_name) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanageractivity_ledger.php?type=show_form_activity',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            activity_name: activity_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERACTIVITYLIST').html(response).show();
                        }
                    });
                }

                // Function to show hotel data
                function show_ACCOUNTSMANAGER_HOTEL_DATA(fromDate, toDate, selectedQuoteId, hotel_name) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotel_ledger.php?type=show_form_hotel',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            hotel_name: hotel_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTELLIST').html(response).show();
                        }
                    });
                }

                // Function to show vehicle data
                function show_ACCOUNTSMANAGER_VEHICLE_DATA(fromDate, toDate, selectedQuoteId, vendor_name, branch_name, vehicle_name) {

                    console.log("Sending AJAX request with data:", {
                        from_date: fromDate,
                        to_date: toDate,
                        quote_id: selectedQuoteId,
                        vendor_name: vendor_name,
                        branch_name: branch_name,
                        vehicle_name: vehicle_name
                    });
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagervehicle_ledger.php?type=show_form_vehicle',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            vendor_name: vendor_name,
                            branch_name: branch_name,
                            vehicle_name: vehicle_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERVEHICLELIST').html(response).show();
                        }
                    });
                }

                // Function to show vehicle data
                function show_ACCOUNTSMANAGER_AGENT_DATA(fromDate, toDate, selectedQuoteId, agent_name) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanageragent.php?type=show_form_agent',
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            quote_id: selectedQuoteId,
                            agent_name: agent_name
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERAGENTLIST').html(response).show();
                        }
                    });
                }

                function toggleFields() {
                    const selectedComponent = $('#components_type').val();

                    // Hide all fields initially
                    $('#guide_names, #hotspot_names, #activity_names, #hotel_names, #vendor_names, #branch_names, #vehicle_names, #agent_names').hide();
                    console.log('components_type:', selectedComponent);

                    // Show the field corresponding to the selected component type
                    switch (selectedComponent) {
                        case '1': // Guide
                            $('#guide_names').show();
                            break;
                        case '2': // Hotspot
                            $('#hotspot_names').show();
                            break;
                        case '3': // Activity
                            $('#activity_names').show();
                            break;
                        case '4': // Hotel
                            $('#hotel_names').show();
                            break;
                        case '5': // Hotel
                            $('#vendor_names').show();
                            $('#branch_names').show();
                            $('#vehicle_names').show();
                            break;
                        case '6': // Hotel
                            $('#agent_names').show();
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