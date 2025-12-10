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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST
    if ($_GET['type'] == 'show_form') :
?>
        <div class="row">
            <div class="col-3">
                <label class="form-label" for="vendor_vehicle">Vendor<span class="text-danger"> *</span></label>
                <select id="vendor_vehicle" name="vendor_vehicle" class="form-select form-control" data-parsley-trigger="keyup" required>
                    <?php
                    if ($logged_vendor_id != '') :
                        $vendor_id = $logged_vendor_id;
                    endif;
                    getVENDOR_DETAILS($vendor_id, 'select'); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="vendor_branch">Vendor Branch <span class="text-danger"> *</span></label>
                <select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
                    <?= getVENDORBRANCHDETAIL($vendor_branch, $logged_vendor_id, 'select'); ?>
                </select>
            </div>
            <div class="col-md-2">
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
                        echo "<option value=\"$key\">$month</option>";
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
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h5>Vehicle Price List</h5>
                <div class="card-body dataTable_select text-nowrap">
                    <div class="table-responsive table-bordered">
                        <table class="table table-hover" id="vehicle_pricebook_LIST">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Vendor Name</th>
                                    <th scope="col">Branch Name</th>
                                    <th scope="col">Vehicle Type</th>
                                    <th scope="col">Month</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Cost Type</th>
                                    <?php for ($day = 1; $day <= 31; $day++) : ?>
                                        <th scope="col">Day <?= $day; ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="38">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                function fetchPricebookData(filters) {
                    $.ajax({
                        url: "engine/json/__JSONvehicle_pricebook.php",
                        type: "GET",
                        dataType: 'json',
                        data: filters,
                        success: function(response) {
                            var tableHtml = '';
                            var rowCounter = 1;

                            if (response.data.length === 0) {
                                tableHtml = '<tr><td colspan="38">No data available</td></tr>';
                            } else {
                                var groupedData = {};

                                // Group data by vendor, branch, vehicle type, month, and year
                                $.each(response.data, function(index, rowData) {
                                    var key = rowData.vendor_name + '-' + rowData.branch_name + '-' + rowData.vehicle_type + '-' + rowData.month + '-' + rowData.year;
                                    if (!groupedData[key]) {
                                        groupedData[key] = {
                                            vendor_name: rowData.vendor_name,
                                            branch_name: rowData.branch_name,
                                            vehicle_type: rowData.vehicle_type,
                                            month: rowData.month,
                                            year: rowData.year,
                                            local_days: Array(31).fill('-'),
                                            outstation_days: Array(31).fill('-')
                                        };
                                    }
                                    if (rowData.price_book_type === 'Local') {
                                        groupedData[key].local_days = rowData.days;
                                    } else if (rowData.price_book_type === 'Outstation') {
                                        groupedData[key].outstation_days = rowData.days;
                                    }
                                });

                                // Build table rows based on grouped data
                                $.each(groupedData, function(key, rowGroup) {
                                    tableHtml += '<tr>';
                                    tableHtml += '<td rowspan="2">' + rowCounter + '</td>';
                                    tableHtml += '<td rowspan="2">' + rowGroup.vendor_name + '</td>';
                                    tableHtml += '<td rowspan="2">' + rowGroup.branch_name + '</td>';
                                    tableHtml += '<td rowspan="2">' + rowGroup.vehicle_type + '</td>';
                                    tableHtml += '<td rowspan="2">' + rowGroup.month + '</td>';
                                    tableHtml += '<td rowspan="2">' + rowGroup.year + '</td>';

                                    // Local cost row
                                    tableHtml += '<td>Local</td>';
                                    $.each(rowGroup.local_days, function(dayIndex, dayValue) {
                                        tableHtml += '<td>' + dayValue + '</td>';
                                    });
                                    tableHtml += '</tr>';

                                    // Outstation cost row
                                    tableHtml += '<tr>';
                                    tableHtml += '<td>Outstation</td>';
                                    $.each(rowGroup.outstation_days, function(dayIndex, dayValue) {
                                        tableHtml += '<td>' + dayValue + '</td>';
                                    });
                                    tableHtml += '</tr>';

                                    rowCounter++;
                                });
                            }

                            // Append generated HTML to table body
                            $('#vehicle_pricebook_LIST tbody').html(tableHtml);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                            $('#vehicle_pricebook_LIST tbody').html('<tr><td colspan="38">Error fetching data. Please try again later.</td></tr>');
                        }
                    });
                }

                function updateFilters() {
                    var vendor = $('#vendor_vehicle').val();
                    var branch = $('#vendor_branch').val();
                    var month = $('#vehicle_month').val();
                    var year = $('#vehicle_year').val();

                    var filters = {
                        vendor: vendor,
                        branch: branch,
                        month: month,
                        year: year
                    };

                    // Call fetchPricebookData function with filters
                    fetchPricebookData(filters);
                }

                // Add change event listeners to all filter inputs
                $('#vendor_vehicle, #vendor_branch, #vehicle_month, #vehicle_year').on('change', updateFilters);

                // Initial fetch to show "No data available"
                $('#vehicle_pricebook_LIST tbody').html('<tr><td colspan="38">No data available</td></tr>');
            });
        </script>

<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>