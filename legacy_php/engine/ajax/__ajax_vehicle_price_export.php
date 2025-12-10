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
        <div class="row">
            <div class="col-3">
                <label class="form-label" for="vendor_name">Vendor Name<span class="text-danger"> *</span></label>
                <select id="vendor_name" name="vendor_name" class="form-select form-control" data-parsley-trigger="keyup" required>
                    <?= getVENDOR_DETAILS($vendor_name, 'select'); ?>
                </select>
            </div>
            <div class="col-3" id="vendorbranchDiv">
                <label class="form-label" for="vendor_branch">Vendor Branch <span class="text-danger"> *</span></label>
                <select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" required>
                    <option value=""> Choose Vendor Branch</option>
                </select>
            </div>
            <div class="col-2">
                <label class="form-label" for="vehicle_month">Month <span class="text-danger">*</span></label>
                <select class="form-select" name="vehicle_month" id="vehicle_month">
                    <option value="">Choose Month</option>
                    <?php
                    $months = [
                        1 => "January", 2 => "February", 3 => "March", 4 => "April",
                        5 => "May", 6 => "June", 7 => "July", 8 => "August",
                        9 => "September", 10 => "October", 11 => "November", 12 => "December"
                    ];

                    foreach ($months as $key => $month) {
                        echo "<option value=\"$month\">$month</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="vehicle_year">Year <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="vehicle_year" id="vehicle_year" autocomplete="off" required class="form-control" placeholder="Year" />
                </div>
            </div>
            <div class="col-2 d-flex align-items-end justify-content-end">
                <button class="btn btn-sm btn-label-success" id="export_csv" disabled><i class="ti ti-download me-2"></i>Export</button>
            </div>
        </div>
        <div id="vehicle_pricebook_details"></div>
        <script src="assets/js/selectize/selectize.min.js"></script>

        <script>
            $(document).ready(function() {

                // Initialize Selectize on all select elements
                $("select").selectize();

                $('#vehicle_year').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    autoclose: true // Ensure the picker closes after selection
                }).on('changeDate', handlevehicleChange); // Add event listener for date picker


                $('#vehicle_month').on('change', handlevehicleChange);
                $('#vehicle_year').on('input', handlevehicleChange); // Ensure input changes are handled
                $('#vendor_name').on('change', handlevehicleChange);


                // Trigger branch name through vendor name selection
                var vendorNameSelectize = $('#vendor_name').selectize()[0].selectize;
                var vendorBranchSelectize = $('#vendor_branch').selectize()[0].selectize;

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
                            $('#vendorbranchDiv').html(response);
                            var vendorBranchSelectize = $('#vendor_branch').selectize()[0].selectize;

                            vendorBranchSelectize.on('change', function() {
                                handlevehicleChange();
                            });
                        }
                    });
                }



                $('#export_csv').on('click', function() {
                    var vendor = $('#vendor_name').val();
                    var branch = $('#vendor_branch').val();
                    var month = $('#vehicle_month').val();
                    var year = $('#vehicle_year').val();

                    var filters = {
                        vendor: vendor,
                        branch: branch,
                        month: month,
                        year: year
                    };

                    var queryString = $.param(filters);
                    if (vendor && branch && month && year) {
                        window.location.href = 'excel_export_vehicle_pricebook.php?' + queryString;
                    }
                });
            });

            function handlevehicleChange() {
                var vendor = $('#vendor_name').val();
                var branch = $('#vendor_branch').val();
                var month = $('#vehicle_month').val();
                var year = $('#vehicle_year').val();

                console.log('Form values:', {
                    vendor,
                    branch,
                    month,
                    year
                });

                if (vendor && branch && month && year) {
                    sendAjaxRequest(vendor, branch, month, year);
                }
            }

            function sendAjaxRequest(vendor, branch, month, year) {
                $.ajax({
                    url: 'engine/ajax/__ajax_vehicle_pricebook_list.php?type=show_form',
                    type: 'POST',
                    data: {
                        vendor: vendor,
                        branch: branch,
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        $('#vehicle_pricebook_details').html(response);

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                });
            }
        </script>

<?php
    endif;
endif;

?>