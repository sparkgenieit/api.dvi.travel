<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST
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
            <div class="col-3">
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
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Vehicle Price List</h5>
                    <button class="btn btn-sm btn-label-success mb-3" id="export_csv"><i class="ti ti-download me-2"></i>Export</button>
                </div>
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
                                    <th scope="col">Time Limit</th>
                                    <th scope="col">KM Limit</th>
                                    <?php for ($day = 1; $day <= 31; $day++) : ?>
                                        <th scope="col">Day <?= $day; ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="40">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Initialize Selectize on all select elements
                $("select").selectize();

                // Initialize datepicker on #vehicle_year input
                $('#vehicle_year').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years"
                });

                $(document).on('change', '#vendor_branch', function() {
                    console.log('Vendor branch changed');
                    updateFilters();
                });

                // Function to fetch and display data based on selected filters

                function fetchPricebookData(filters) {
                    $.ajax({
                        url: "engine/json/__JSONvehicle_pricebook.php",
                        type: "GET",
                        dataType: 'json',
                        data: filters,
                        success: function(response) {
                            console.log("Response Data:", response); // Debugging log
                            var tableHtml = '';
                            var rowCounter = 1;

                            if (response.data.length === 0) {
                                tableHtml = '<tr><td colspan="40">No data available</td></tr>';
                                // If no data, disable export button
                                $('#export_csv').prop('disabled', true);
                            } else {
                                var groupedData = {};

                                // Group data by common keys
                                $.each(response.data, function(index, rowData) {
                                    var key = rowData.vendor_name + '-' + rowData.branch_name + '-' + rowData.vehicle_type + '-' + rowData.month + '-' + rowData.year;
                                    if (!groupedData[key]) {
                                        groupedData[key] = {
                                            vendor_name: rowData.vendor_name,
                                            branch_name: rowData.branch_name,
                                            vehicle_type: rowData.vehicle_type,
                                            month: rowData.month,
                                            year: rowData.year,
                                            records: []
                                        };
                                    }

                                    groupedData[key].records.push(rowData);
                                });

                                console.log("Grouped Data:", groupedData); // Debugging log
                                alert("ggg");
                                // Construct table rows
                                $.each(groupedData, function(key, rowGroup) {
                                    var totalRows = rowGroup.records.length;

                                    for (var i = 0; i < totalRows; i++) {
                                        tableHtml += '<tr>';
                                        // if (i === 0) {
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowCounter + '</td>';
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowGroup.vendor_name + '</td>';
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowGroup.branch_name + '</td>';
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowGroup.vehicle_type + '</td>';
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowGroup.month + '</td>';
                                        tableHtml += '<td rowspan="' + totalRows + '">' + rowGroup.year + '</td>';
                                        // }

                                        var record = rowGroup.records[i];
                                        tableHtml += '<td>' + record.price_book_type + '</td>';
                                        tableHtml += '<td>' + (record.time_limit || '-') + '</td>';
                                        tableHtml += '<td>' + (record.km_limit || '-') + '</td>';
                                        $.each(record.days, function(dayIndex, dayValue) {
                                            tableHtml += '<td>' + dayValue + '</td>';
                                        });

                                        tableHtml += '</tr>';
                                    }

                                    rowCounter++;
                                });
                            }

                            $('#vehicle_pricebook_LIST tbody').html(tableHtml);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                            $('#vehicle_pricebook_LIST tbody').html('<tr><td colspan="40">Error fetching data. Please try again later.</td></tr>');
                        }
                    });

                }

                // Function to update filters and trigger data fetch on filter change
                function updateFilters() {
                    var vendor = $('#vendor_name').val();
                    var branch = $('#vendor_branch').val();
                    var month = $('#vehicle_month').val();
                    var year = $('#vehicle_year').val();
                    console.log('Vendor: ' + vendor + ', Branch: ' + branch + ', Month: ' + month + ', Year: ' + year);
                    // Check if all necessary filters are filled
                    if (vendor && branch && month && year) {
                        var filters = {
                            vendor: vendor,
                            branch: branch,
                            month: month,
                            year: year
                        };

                        // Fetch data from JSON
                        fetchPricebookData(filters);

                        // Enable export button
                        $('#export_csv').prop('disabled', false);
                    } else {
                        // If any filter is not filled, disable export button
                        $('#export_csv').prop('disabled', true);
                    }
                }

                // Event listeners for filter fields change
                $('#vendor_name, #vendor_branch, #vehicle_month, #vehicle_year').on('change', function() {
                    updateFilters();
                });

                // Click event handler for export button
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
                    window.location.href = 'excel_export_vehicle_pricebook.php?' + queryString;
                });

                // Initial state: disable export button
                $('#export_csv').prop('disabled', true);

                // Additional code for selectize and AJAX functionality
            });

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
                        $('#vendor_branch').selectize();
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>