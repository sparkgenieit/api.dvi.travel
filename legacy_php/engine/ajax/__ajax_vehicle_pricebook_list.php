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
        $vendor = $_POST['vendor'];
        $branch = $_POST['branch'];
        $month = $_POST['month'];
        $year = $_POST['year'];

?>

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

        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script>
            $(document).ready(function() {
                var filters = {
                    vendor: '<?= $vendor ?>',
                    branch: '<?= $branch ?>',
                    month: '<?= $month ?>',
                    year: '<?= $year ?>'
                };
                fetchPricebookData(filters);
            });

            function fetchPricebookData(filters) {
                $.ajax({
                    url: "engine/json/__JSONvehicle_pricebook.php",
                    type: "GET",
                    dataType: 'json',
                    data: filters,
                    success: function(response) {
                        console.log("Response Data:", response); // Debugging log
                        var tableHtml = '';
                        var rowCounter = 0;

                        if (response.data.length === 0) {
                            tableHtml = '<tr><td colspan="40">No data available</td></tr>';
                            // If no data, disable export button
                            $('#export_csv').prop('disabled', true);
                        } else {
                            var groupedData = {};
                            $('#export_csv').prop('disabled', false);
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
                                        local: [],
                                        outstation: []
                                    };
                                }

                                if (rowData.price_book_type === 'Local') {
                                    groupedData[key].local.push(rowData);
                                } else if (rowData.price_book_type === 'Outstation') {
                                    groupedData[key].outstation.push(rowData);
                                }
                            });

                            /* console.log("Grouped Data:", groupedData); // Debugging log */

                            // Construct table rows
                            $.each(groupedData, function(key, rowGroup) {
                                var localRows = rowGroup.local.length;
                                var outstationRows = rowGroup.outstation.length;
                                var totalRows = localRows + outstationRows;

                                for (var i = 0; i < totalRows; i++) {

                                    tableHtml += '<tr>';

                                    tableHtml += '<td>' + ++rowCounter + '</td>';

                                    tableHtml += '<td>' + rowGroup.vendor_name + '</td>';
                                    tableHtml += '<td>' + rowGroup.branch_name + '</td>';
                                    tableHtml += '<td>' + rowGroup.vehicle_type + '</td>';
                                    tableHtml += '<td>' + rowGroup.month + '</td>';
                                    tableHtml += '<td>' + rowGroup.year + '</td>';

                                    // Local record
                                    if (i < localRows) {
                                        var localRecord = rowGroup.local[i];
                                        tableHtml += '<td>Local</td>';
                                        tableHtml += '<td>' + (localRecord.time_limit || '-') + '</td>';
                                        tableHtml += '<td>-</td>';
                                        for (var j = 0; j < 31; j++) {
                                            tableHtml += '<td>' + (localRecord.days[j] || '-') + '</td>';
                                        }
                                    } else {
                                        // Outstation record
                                        var outstationRecord = rowGroup.outstation[i - localRows];
                                        tableHtml += '<td>Outstation</td>';
                                        tableHtml += '<td>-</td>';
                                        tableHtml += '<td>' + (outstationRecord.km_limit || '-') + '</td>';
                                        for (var j = 0; j < 31; j++) {
                                            tableHtml += '<td>' + (outstationRecord.days[j] || '-') + '</td>';
                                        }
                                    }

                                    tableHtml += '</tr>';
                                }

                                //rowCounter++;
                            });

                            $('#vehicle_pricebook_LIST tbody').html(tableHtml);

                            // Initialize DataTables with pagination
                            if ($.fn.DataTable.isDataTable('#vehicle_pricebook_LIST')) {
                                $('#vehicle_pricebook_LIST').DataTable().destroy();
                            }

                            $('#vehicle_pricebook_LIST').DataTable({
                                "paging": true,
                                "searching": true, // Disable searching if not needed
                                "info": false // Disable table information display
                            });
                        }


                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                        $('#vehicle_pricebook_LIST tbody').html('<tr><td colspan="40">Error fetching data. Please try again later.</td></tr>');
                    }
                });
            }
        </script>

<?php

    endif;
endif;

?>