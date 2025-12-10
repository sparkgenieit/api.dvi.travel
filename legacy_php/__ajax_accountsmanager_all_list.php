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

        $dashboard_from = isset($_GET['dashboard_from']) && $_GET['dashboard_from'] != '' ? $_GET['dashboard_from'] : '';
        $dashboard_to = isset($_GET['dashboard_to']) && $_GET['dashboard_to'] != '' ? $_GET['dashboard_to'] : '';

?>
        <div class="row mb-4">
            <div class="col-12 px-0">
                <div class="card p-3">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title text-uppercase mb-0">Filter</h5>
                        <div class="d-flex align-items-center">
                            <button id="export-purchase-tax-btn" class="btn btn-sm btn-label-success me-2 d-none"><i class="ti ti-download me-2"></i>Export Purchase Tax</button>
                            <button id="export-sales-tax-btn" class="btn btn-sm btn-label-success me-2 d-none"><i class="ti ti-download me-2"></i>Export Sales Tax</button>
                            <button id="export-accounts-quoteid-btn" class="btn btn-sm btn-label-success d-none">
                                <i class="ti ti-download me-2"></i>Export P&l Report
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="quote_id">Quote ID</label>
                            <input type="text" id="quote_id" name="quote_id" class="form-control" placeholder="Enter the Quote ID">
                        </div>
                        <div class="col-2">
                            <label for="components_type" class="form-label">Component Type</label>
                            <select class="form-select" id="components_type" name="components_type" autocomplete="off" aria-label="Default select example">
                                <?= getCOMPONENTS('', 'select'); ?>
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
                        <div class="col-3">
                            <label for="agent_name" class="form-label">Agent</label>
                            <div class="form-group">
                                <select name="agent_name" id="agent_name" class="form-select form-control location">
                                    <?= getAGENT_details('', '', 'agent_with_company') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <a type="button" href="accountsmanager.php" class="btn btn-primary text-white">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 px-0">
                <span id="showACCOUNTSMANAGERALLSUMMARY"></span>
                <span id="showACCOUNTSMANAGERALLLIST"></span>
                <span id="showACCOUNTSMANAGERGUIDELIST"></span>
                <span id="showACCOUNTSMANAGERHOTSPOTLIST"></span>
                <span id="showACCOUNTSMANAGERACTIVITYLIST"></span>
                <span id="showACCOUNTSMANAGERHOTELLIST"></span>
                <span id="showACCOUNTSMANAGERVEHICLELIST"></span>
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

                // ? Place this at the top
                function convertDateToDMY(dateStr) {
                    if (!dateStr) return '';
                    const parts = dateStr.split('-'); // [YYYY, MM, DD]
                    if (parts.length !== 3) return '';
                    return parts[2] + '/' + parts[1] + '/' + parts[0];
                }

                const dashboardFrom = "<?= $dashboard_from ?>";
                const dashboardTo = "<?= $dashboard_to ?>";

                let fromDate, toDate, agentName;
                let formattedCurrentDate, formattedThirtyDaysBefore;

                if (dashboardFrom && dashboardTo) {
                    formattedThirtyDaysBefore = convertDateToDMY(dashboardFrom);
                    formattedCurrentDate = convertDateToDMY(dashboardTo);

                    // Fallback if conversion fails
                    if (!formattedCurrentDate || formattedCurrentDate.trim() === '') {
                        const currentDate = new Date();
                        formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                    }
                } else {
                    const currentDate = new Date();
                    formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                    const thirtyDaysBefore = new Date();
                    thirtyDaysBefore.setDate(currentDate.getDate() - 30);
                    formattedThirtyDaysBefore = flatpickr.formatDate(thirtyDaysBefore, "d/m/Y");
                }

                // ? Continue with your picker initialization
                let fromDatePicker = flatpickr("#itinerary_from_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedThirtyDaysBefore,
                    onChange: function(selectedDates, dateStr, instance) {
                        fromDate = dateStr;
                        toDatePicker.set("minDate", selectedDates[0]);
                        updateComponentData();
                        updateExportBtn();
                        updateExportPURCHASEBtn();
                    }
                });

                let toDatePicker = flatpickr("#itinerary_to_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedCurrentDate,
                    onChange: function(selectedDates, dateStr, instance) {
                        toDate = dateStr;
                        updateComponentData();
                        updateExportBtn();
                        updateExportPURCHASEBtn();
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

                            if (selectedQuoteId) {
                                $('#export-accounts-quoteid-btn').removeClass('d-none');
                            } else {
                                $('#export-accounts-quoteid-btn').addClass('d-none');
                            }
                            updateComponentData();
                            updateExportBtn();
                            updateExportPURCHASEBtn();
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

                $('#agent_name').on('change', function() {
                    // Update the agentName when it changes
                    agentName = $(this).val();
                    // Call the function with updated values for the selected component
                    updateComponentData();
                });

                // Default component type value (Guide)
                const defaultComponentType = '0';

                // Set the default component type to 'Guide' and call the function to show guide data
                $('#components_type').val(defaultComponentType);
                updateComponentData();

                // Handle change event on dropdown
                $('#components_type').on('change', function() {
                    const selectedValue = $(this).val(); // Get the selected value
                    updateComponentData(selectedValue);
                });


                $('#export-accounts-quoteid-btn').click(function() {
                    if (selectedQuoteId) {
                        window.location.href = 'excel_export_Profit_loss.php?quote_id=' + encodeURIComponent(selectedQuoteId);
                    }
                });

                $('#export-purchase-tax-btn').click(function() {
                    window.location.href = 'excel_export_purchase_tax.php?itinerary_fromdate_format=' + encodeURIComponent(fromDate) + '&itinerary_todate_format=' + encodeURIComponent(toDate) + '&selectedQuoteId=' + encodeURIComponent(selectedQuoteId);
                });

                $('#export-sales-tax-btn').click(function() {
                    window.location.href = 'excel_export_sales_tax.php?itinerary_fromdate_format=' + encodeURIComponent(fromDate) + '&itinerary_todate_format=' + encodeURIComponent(toDate) + '&selectedQuoteId=' + encodeURIComponent(selectedQuoteId);
                });

                function updateExportBtn() {
                    var from_date = $('#itinerary_from_date').val();
                    var to_date = $('#itinerary_to_date').val();
                    var quote_id = $('#quote_id').val();

                    $.ajax({
                        url: 'engine/ajax/__ajax_accountsmanagerall_filter.php?type=show_form', // Replace with the actual PHP file handling the request
                        type: 'POST',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            quote_id: quote_id
                        },
                        dataType: 'json', // Expect a JSON response
                        success: function(response) {
                            if (response.show) {
                                document.getElementById('export-sales-tax-btn').classList.remove('d-none');
                            } else {
                                document.getElementById('export-sales-tax-btn').classList.add('d-none');
                            }
                        }
                    });
                }

                function updateExportPURCHASEBtn() {
                    var from_date = $('#itinerary_from_date').val();
                    var to_date = $('#itinerary_to_date').val();
                    var quote_id = $('#quote_id').val();

                    $.ajax({
                        url: 'engine/ajax/__ajax_accountsmanagerall_filter.php?type=show_form_purchase', // Replace with the actual PHP file handling the request
                        type: 'POST',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            quote_id: quote_id
                        },
                        dataType: 'json', // Expect a JSON response
                        success: function(response) {
                            if (response.show) {
                                document.getElementById('export-purchase-tax-btn').classList.remove('d-none');
                            } else {
                                document.getElementById('export-purchase-tax-btn').classList.add('d-none');
                            }
                        }
                    });
                }


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

                    // Show the corresponding list based on selected component type
                    switch (componentType) {
                        case '0':
                            show_ACCOUNTSMANAGER_ALL_SUMMARY(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERALLSUMMARY').show(); // Show the ALL list
                            show_ACCOUNTSMANAGER_ALL_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERALLLIST').show(); // Show the ALL list
                            break;
                        case '1':
                            show_ACCOUNTSMANAGER_GUIDE_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERGUIDELIST').show(); // Show the guide list
                            break;
                        case '2':
                            show_ACCOUNTSMANAGER_HOTSPOT_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').show(); // Show the hotspot list
                            break;
                        case '3':
                            show_ACCOUNTSMANAGER_ACTIVITY_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERACTIVITYLIST').show(); // Show the activity list
                            break;
                        case '4':
                            show_ACCOUNTSMANAGER_HOTEL_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERHOTELLIST').show(); // Show the hotel list
                            break;
                        case '5':
                            show_ACCOUNTSMANAGER_VEHICLE_DATA(1, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERVEHICLELIST').show(); // Show the vehicle list
                            break;
                        default:
                            console.log('Invalid component');
                    }
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_SUMMARY(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_summary.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLSUMMARY').html(response).show();
                        }
                    });
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_data.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLLIST').html(response).show();
                        }
                    });
                }

                // Function to show guide data
                function show_ACCOUNTSMANAGER_GUIDE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerguide_data.php?type=show_form_guide&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERGUIDELIST').html(response).show();
                        }
                    });
                }

                // Function to show hotspot data
                function show_ACCOUNTSMANAGER_HOTSPOT_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotspot_data.php?type=show_form_hotspot&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').html(response).show();
                        }
                    });
                }

                // Function to show activity data
                function show_ACCOUNTSMANAGER_ACTIVITY_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanageractivity_data.php?type=show_form_activity&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERACTIVITYLIST').html(response).show();
                        }
                    });
                }

                // Function to show hotel data
                function show_ACCOUNTSMANAGER_HOTEL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotel_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTELLIST').html(response).show();
                        }
                    });
                }

                // Function to show vehicle data
                function show_ACCOUNTSMANAGER_VEHICLE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagervehicle_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERVEHICLELIST').html(response).show();
                        }
                    });
                }
            });
        </script>

    <?php
    elseif ($_GET['type'] == 'show_form_paid') :

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
                            <label for="components_type" class="form-label">Component Type</label>
                            <select class="form-select" id="components_type" name="components_type" autocomplete="off" aria-label="Default select example">
                                <?= getCOMPONENTS('', 'select'); ?>
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
                        <div class="col-3">
                            <label for="agent_name" class="form-label">Agent</label>
                            <div class="form-group">
                                <select name="agent_name" id="agent_name" class="form-select form-control location">
                                    <?= getAGENT_details('', '', 'select') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <a type="button" href="accountsmanager.php" class="btn btn-primary text-white">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 px-0">
                <span id="showACCOUNTSMANAGERALLSUMMARY"></span>
                <span id="showACCOUNTSMANAGERALLLIST"></span>
                <span id="showACCOUNTSMANAGERGUIDELIST"></span>
                <span id="showACCOUNTSMANAGERHOTSPOTLIST"></span>
                <span id="showACCOUNTSMANAGERACTIVITYLIST"></span>
                <span id="showACCOUNTSMANAGERHOTELLIST"></span>
                <span id="showACCOUNTSMANAGERVEHICLELIST"></span>
            </div>
        </div>

        <!-- Global Center Loader Overlay -->
        <div id="globalLoaderpaid" style="
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
                    $('#globalLoaderpaid').fadeIn(100);
                }

                function hideGlobalLoader() {
                    $('#globalLoaderpaid').fadeOut(100);
                }

                // Auto show loader on ANY AJAX request globally
                $(document).ajaxStart(function() {
                    showGlobalLoader();
                });

                $(document).ajaxStop(function() {
                    hideGlobalLoader();
                });

                // ? Place this at the top
                function convertDateToDMY(dateStr) {
                    if (!dateStr) return '';
                    const parts = dateStr.split('-'); // [YYYY, MM, DD]
                    if (parts.length !== 3) return '';
                    return parts[2] + '/' + parts[1] + '/' + parts[0];
                }

                const dashboardFrom = "<?= $dashboard_from ?>";
                const dashboardTo = "<?= $dashboard_to ?>";

                let fromDate, toDate, agentName;
                let formattedCurrentDate, formattedThirtyDaysBefore;

                if (dashboardFrom && dashboardTo) {
                    formattedThirtyDaysBefore = convertDateToDMY(dashboardFrom);
                    formattedCurrentDate = convertDateToDMY(dashboardTo);

                    // Fallback if conversion fails
                    if (!formattedCurrentDate || formattedCurrentDate.trim() === '') {
                        const currentDate = new Date();
                        formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                    }
                } else {
                    const currentDate = new Date();
                    formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                    const thirtyDaysBefore = new Date();
                    thirtyDaysBefore.setDate(currentDate.getDate() - 30);
                    formattedThirtyDaysBefore = flatpickr.formatDate(thirtyDaysBefore, "d/m/Y");
                }

                // ? Continue with your picker initialization
                let fromDatePicker = flatpickr("#itinerary_from_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedThirtyDaysBefore,
                    onChange: function(selectedDates, dateStr, instance) {
                        fromDate = dateStr;
                        toDatePicker.set("minDate", selectedDates[0]);
                        updateComponentData();
                        updateExportBtn();
                        updateExportPURCHASEBtn();
                    }
                });

                let toDatePicker = flatpickr("#itinerary_to_date", {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: formattedCurrentDate,
                    onChange: function(selectedDates, dateStr, instance) {
                        toDate = dateStr;
                        updateComponentData();
                        updateExportBtn();
                        updateExportPURCHASEBtn();
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

                            if (selectedQuoteId) {
                                $('#export-accounts-quoteid-btn').removeClass('d-none');
                            } else {
                                $('#export-accounts-quoteid-btn').addClass('d-none');
                            }
                            updateComponentData();
                            updateExportBtn();
                            updateExportPURCHASEBtn();
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


                $('#agent_name').on('change', function() {
                    // Update the agentName when it changes
                    agentName = $(this).val();
                    // Call the function with updated values for the selected component
                    updateComponentData();
                });

                // Default component type value (Guide)
                const defaultComponentType = '0';

                // Set the default component type to 'Guide' and call the function to show guide data
                $('#components_type').val(defaultComponentType);
                updateComponentData();

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

                    // Show the corresponding list based on selected component type
                    switch (componentType) {
                        case '0':
                            show_ACCOUNTSMANAGER_ALL_SUMMARY(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERALLSUMMARY').show(); // Show the ALL list
                            show_ACCOUNTSMANAGER_ALL_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERALLLIST').show(); // Show the ALL list
                            break;
                        case '1':
                            show_ACCOUNTSMANAGER_GUIDE_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERGUIDELIST').show(); // Show the guide list
                            break;
                        case '2':
                            show_ACCOUNTSMANAGER_HOTSPOT_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').show(); // Show the hotspot list
                            break;
                        case '3':
                            show_ACCOUNTSMANAGER_ACTIVITY_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERACTIVITYLIST').show(); // Show the activity list
                            break;
                        case '4':
                            show_ACCOUNTSMANAGER_HOTEL_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERHOTELLIST').show(); // Show the hotel list
                            break;
                        case '5':
                            show_ACCOUNTSMANAGER_VEHICLE_DATA(2, fromDate, toDate, agentName, selectedQuoteId);
                            $('#showACCOUNTSMANAGERVEHICLELIST').show(); // Show the vehicle list
                            break;
                        default:
                            console.log('Invalid component');
                    }
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_SUMMARY(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_summary.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLSUMMARY').html(response).show();
                        }
                    });
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_data.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLLIST').html(response).show();
                        }
                    });
                }

                // Function to show guide data
                function show_ACCOUNTSMANAGER_GUIDE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerguide_data.php?type=show_form_guide&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERGUIDELIST').html(response).show();
                        }
                    });
                }

                // Function to show hotspot data
                function show_ACCOUNTSMANAGER_HOTSPOT_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotspot_data.php?type=show_form_hotspot&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').html(response).show();
                        }
                    });
                }

                // Function to show activity data
                function show_ACCOUNTSMANAGER_ACTIVITY_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanageractivity_data.php?type=show_form_activity&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERACTIVITYLIST').html(response).show();
                        }
                    });
                }

                // Function to show hotel data
                function show_ACCOUNTSMANAGER_HOTEL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotel_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTELLIST').html(response).show();
                        }
                    });
                }

                // Function to show vehicle data
                function show_ACCOUNTSMANAGER_VEHICLE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagervehicle_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERVEHICLELIST').html(response).show();
                        }
                    });
                }
            });
        </script>
    <?php
    elseif ($_GET['type'] == 'show_form_due') :

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
                            <label for="components_type" class="form-label">Component Type</label>
                            <select class="form-select" id="components_type" name="components_type" autocomplete="off" aria-label="Default select example">
                                <?= getCOMPONENTS('', 'select'); ?>
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
                        <div class="col-3">
                            <label for="agent_name" class="form-label">Agent</label>
                            <div class="form-group">
                                <select name="agent_name" id="agent_name" class="form-select form-control location">
                                    <?= getAGENT_details('', '', 'select') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <a type="button" href="accountsmanager.php" class="btn btn-primary text-white">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 px-0">
                <span id="showACCOUNTSMANAGERALLSUMMARY"></span>
                <span id="showACCOUNTSMANAGERALLLIST"></span>
                <span id="showACCOUNTSMANAGERGUIDELIST"></span>
                <span id="showACCOUNTSMANAGERHOTSPOTLIST"></span>
                <span id="showACCOUNTSMANAGERACTIVITYLIST"></span>
                <span id="showACCOUNTSMANAGERHOTELLIST"></span>
                <span id="showACCOUNTSMANAGERVEHICLELIST"></span>
            </div>
        </div>

        <!-- Global Center Loader Overlay -->
        <div id="globalLoaderbalance" style="
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

            <!--/ Account Manager Payout Pay Now Modal -->
            <script src="assets/js/parsley.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Functions to show/hide loader
                    function showGlobaldueLoader() {
                        $('#globalLoaderbalance').fadeIn(100);
                    }

                    function hideGlobaldueLoader() {
                        $('#globalLoaderbalance').fadeOut(100);
                    }

                    // Auto show loader on ANY AJAX request globally
                    $(document).ajaxStart(function() {
                        showGlobaldueLoader();
                    });

                    $(document).ajaxStop(function() {
                        hideGlobaldueLoader();
                    });

                    // ? Place this at the top
                    function convertDateToDMY(dateStr) {
                        if (!dateStr) return '';
                        const parts = dateStr.split('-'); // [YYYY, MM, DD]
                        if (parts.length !== 3) return '';
                        return parts[2] + '/' + parts[1] + '/' + parts[0];
                    }

                    const dashboardFrom = "<?= $dashboard_from ?>";
                    const dashboardTo = "<?= $dashboard_to ?>";

                    let fromDate, toDate, agentName;
                    let formattedCurrentDate, formattedThirtyDaysBefore;

                    if (dashboardFrom && dashboardTo) {
                        formattedThirtyDaysBefore = convertDateToDMY(dashboardFrom);
                        formattedCurrentDate = convertDateToDMY(dashboardTo);

                        // Fallback if conversion fails
                        if (!formattedCurrentDate || formattedCurrentDate.trim() === '') {
                            const currentDate = new Date();
                            formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                        }
                    } else {
                        const currentDate = new Date();
                        formattedCurrentDate = flatpickr.formatDate(currentDate, "d/m/Y");
                        const thirtyDaysBefore = new Date();
                        thirtyDaysBefore.setDate(currentDate.getDate() - 30);
                        formattedThirtyDaysBefore = flatpickr.formatDate(thirtyDaysBefore, "d/m/Y");
                    }

                    // ? Continue with your picker initialization
                    let fromDatePicker = flatpickr("#itinerary_from_date", {
                        dateFormat: "d/m/Y",
                        allowInput: true,
                        defaultDate: formattedThirtyDaysBefore,
                        onChange: function(selectedDates, dateStr, instance) {
                            fromDate = dateStr;
                            toDatePicker.set("minDate", selectedDates[0]);
                            updateComponentData();
                            updateExportBtn();
                            updateExportPURCHASEBtn();
                        }
                    });

                    let toDatePicker = flatpickr("#itinerary_to_date", {
                        dateFormat: "d/m/Y",
                        allowInput: true,
                        defaultDate: formattedCurrentDate,
                        onChange: function(selectedDates, dateStr, instance) {
                            toDate = dateStr;
                            updateComponentData();
                            updateExportBtn();
                            updateExportPURCHASEBtn();
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

                                if (selectedQuoteId) {
                                    $('#export-accounts-quoteid-btn').removeClass('d-none');
                                } else {
                                    $('#export-accounts-quoteid-btn').addClass('d-none');
                                }
                                updateComponentData();
                                updateExportBtn();
                                updateExportPURCHASEBtn();
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

                    $('#agent_name').on('change', function() {
                        // Update the agentName when it changes
                        agentName = $(this).val();
                        // Call the function with updated values for the selected component
                        updateComponentData();
                    });

                    // Default component type value (Guide)
                    const defaultComponentType = '0';

                    // Set the default component type to 'Guide' and call the function to show guide data
                    $('#components_type').val(defaultComponentType);
                    updateComponentData();

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

                        // Show the corresponding list based on selected component type
                        switch (componentType) {
                            case '0':
                                show_ACCOUNTSMANAGER_ALL_SUMMARY(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERALLSUMMARY').show(); // Show the ALL list
                                show_ACCOUNTSMANAGER_ALL_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERALLLIST').show(); // Show the ALL list
                                break;
                            case '1':
                                show_ACCOUNTSMANAGER_GUIDE_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERGUIDELIST').show(); // Show the guide list
                                break;
                            case '2':
                                show_ACCOUNTSMANAGER_HOTSPOT_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERHOTSPOTLIST').show(); // Show the hotspot list
                                break;
                            case '3':
                                show_ACCOUNTSMANAGER_ACTIVITY_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERACTIVITYLIST').show(); // Show the activity list
                                break;
                            case '4':
                                show_ACCOUNTSMANAGER_HOTEL_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERHOTELLIST').show(); // Show the hotel list
                                break;
                            case '5':
                                show_ACCOUNTSMANAGER_VEHICLE_DATA(3, fromDate, toDate, agentName, selectedQuoteId);
                                $('#showACCOUNTSMANAGERVEHICLELIST').show(); // Show the vehicle list
                                break;
                            default:
                                console.log('Invalid component');
                        }
                    }

                    // Function to show all data
                    function show_ACCOUNTSMANAGER_ALL_SUMMARY(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagerall_summary.php?type=show_form_all&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERALLSUMMARY').html(response).show();
                            }
                        });
                    }

                    // Function to show all data
                    function show_ACCOUNTSMANAGER_ALL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagerall_data.php?type=show_form_all&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERALLLIST').html(response).show();
                            }
                        });
                    }

                    // Function to show guide data
                    function show_ACCOUNTSMANAGER_GUIDE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagerguide_data.php?type=show_form_guide&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERGUIDELIST').html(response).show();
                            }
                        });
                    }

                    // Function to show hotspot data
                    function show_ACCOUNTSMANAGER_HOTSPOT_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagerhotspot_data.php?type=show_form_hotspot&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERHOTSPOTLIST').html(response).show();
                            }
                        });
                    }

                    // Function to show activity data
                    function show_ACCOUNTSMANAGER_ACTIVITY_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanageractivity_data.php?type=show_form_activity&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERACTIVITYLIST').html(response).show();
                            }
                        });
                    }

                    // Function to show hotel data
                    function show_ACCOUNTSMANAGER_HOTEL_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagerhotel_data.php?type=show_form_hotel&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERHOTELLIST').html(response).show();
                            }
                        });
                    }

                    // Function to show vehicle data
                    function show_ACCOUNTSMANAGER_VEHICLE_DATA(ID, fromDate, toDate, agentName, selectedQuoteId) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_accountsmanagervehicle_data.php?type=show_form_hotel&id=' + ID,
                            data: {
                                from_date: fromDate,
                                to_date: toDate,
                                agent_name: agentName,
                                quote_id: selectedQuoteId
                            },
                            success: function(response) {
                                $('#showACCOUNTSMANAGERVEHICLELIST').html(response).show();
                            }
                        });
                    }
                });
            </script>
    <?php
    endif;
endif;
    ?>