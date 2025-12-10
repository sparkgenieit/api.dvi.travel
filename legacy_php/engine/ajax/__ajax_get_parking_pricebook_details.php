<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST
    if ($_GET['type'] == 'show_form') :
?>
        <div>
            <div class="row mb-3">
                <div class="col-3">
                    <label class="form-label" for="vehicle_type_id">Vehicle Type <span class="text-danger">*</span></label>
                    <select id="vehicle_type_id" name="vehicle_type_id" class="form-select form-control" data-parsley-trigger="keyup" required>
                        <?= getVEHICLETYPE($vehicle_type, 'pricebook_select') ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="hotspot_location">Hotspot Location <span class="text-danger">*</span></label>
                    <select class="form-control form-select" required id="hotspot_location" name="hotspot_location">
                        <?= getGOOGLE_LOCATION_DETAILS($hotspot_location, 'select'); ?>
                    </select>
                </div>
                <div class="col-5 d-flex align-items-end justify-content-end">
                    <button class="btn btn-sm btn-label-success mb-3" id="parking_export_csv"><i class="ti ti-download me-2"></i>Export</button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div id="table-container" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5>Parking Pricebook Report</h5>
                        </div>
                        <div class="card-body dataTable_select text-nowrap">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table id="parking_pricebook_LIST" class="table table-hover">
                                    <thead class="table-head">
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Hotspot Name</th>
                                            <th scope="col">Vehicle Type</th>
                                            <th scope="col">Parking Charge</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- <script>
            $(document).ready(function() {
                $("select").selectize();

                var table = $('#parking_pricebook_LIST').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthMenu: [
                        [25, 30, 35, -1],
                        [25, 30, 35, "All"]
                    ],
                    language: {
                        emptyTable: "No data available"
                    },
                    data: [], // Empty data initially
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'hotspot_name'
                        },
                        {
                            data: 'vehicle_type_name'
                        },
                        {
                            data: 'parking_charge',
                            render: function(data, type, row) {
                                return '₹' + data; // Prepend ₹ symbol to parking charge
                            }
                        }
                    ]
                });

                function fetchParkingChargesData(filters) {
                    $.ajax({
                        url: "engine/json/__JSONparking_pricebook.php",
                        type: "GET",
                        dataType: 'json',
                        data: filters,
                        success: function(response) {
                            if (response.data.length === 0) {
                                table.clear().draw();
                                $('#parking_export_csv').prop('disabled',
                                    true); // Disable export button when no data
                            } else {
                                table.clear().rows.add(response.data).draw();
                                $('#parking_export_csv').prop('disabled',
                                    false); // Enable export button when data is loaded
                            }
                            $('#table-container').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                            table.clear().draw();
                            $('#parking_pricebook_LIST tbody').html(
                                '<tr><td colspan="4">Error fetching data. Please try again later.</td></tr>'
                            );
                            $('#parking_export_csv').prop('disabled', true); // Disable export button on error
                        }
                    });
                }

                function updateFilters() {
                    var vehicle_type_id = $('#vehicle_type_id').val();
                    var hotspot_location = $('#hotspot_location').val();
                    if (vehicle_type_id && hotspot_location) {
                        var filters = {
                            vehicle_type: vehicle_type_id,
                            hotspot_location: hotspot_location
                        };
                        fetchParkingChargesData(filters);
                    } else {
                        $('#table-container').hide();
                        $('#parking_export_csv').prop('disabled',
                            true); // Hide table and disable export button if no filters selected
                    }
                }

                $('#vehicle_type_id, #hotspot_location').on('change', function() {
                    updateFilters();
                });

                $('#parking_export_csv').on('click', function() {
                    var vehicle_type_id = $('#vehicle_type_id').val();
                    var hotspot_location = $('#hotspot_location').val();
                    var filters = {
                        vehicle_type: vehicle_type_id,
                        hotspot_location: hotspot_location
                    };
                    var queryString = $.param(filters);
                    window.location.href = 'excel_export_parking_pricebook.php?' + queryString;
                });

                $('#parking_export_csv').prop('disabled', true); // Initially disable export button

                // Trigger updateFilters() initially if needed
                updateFilters();
            });
        </script> -->

        <script>
            $(document).ready(function() {
                $("select").selectize();

                var table = $('#parking_pricebook_LIST').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthMenu: [
                        [10, 25, 30, 35, -1],
                        [10, 25, 30, 35, "All"]
                    ],
                    language: {
                        emptyTable: "No data available"
                    },
                    data: [], // Empty data initially
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'hotspot_name'
                        },
                        {
                            data: 'vehicle_type_name'
                        },
                        {
                            data: 'parking_charge',
                            render: function(data, type, row) {
                                return '₹' + data; // Prepend ₹ symbol to parking charge
                            }
                        }
                    ]
                });

                function fetchParkingChargesData(filters) {
                    $.ajax({
                        url: "engine/json/__JSONparking_pricebook.php",
                        type: "GET",
                        dataType: 'json',
                        data: filters,
                        success: function(response) {
                            if (response.data.length === 0) {
                                table.clear().draw();
                                $('#parking_export_csv').prop('disabled',
                                    true); // Disable export button when no data
                            } else {
                                table.clear().rows.add(response.data).draw();
                                $('#parking_export_csv').prop('disabled',
                                    false); // Enable export button when data is loaded
                            }
                            $('#table-container').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                            table.clear().draw();
                            $('#parking_pricebook_LIST tbody').html(
                                '<tr><td colspan="4">Error fetching data. Please try again later.</td></tr>'
                            );
                            $('#parking_export_csv').prop('disabled', true); // Disable export button on error
                        }
                    });
                }

                function updateFilters() {
                    var vehicle_type_id = $('#vehicle_type_id').val();
                    var hotspot_location = $('#hotspot_location').val();
                    if (vehicle_type_id && hotspot_location) {
                        var filters = {
                            vehicle_type: vehicle_type_id,
                            hotspot_location: hotspot_location
                        };
                        fetchParkingChargesData(filters);
                    } else {
                        $('#table-container').hide();
                        $('#parking_export_csv').prop('disabled',
                            true); // Hide table and disable export button if no filters selected
                    }
                }

                $('#vehicle_type_id, #hotspot_location').on('change', function() {
                    updateFilters();
                });

                $('#parking_export_csv').on('click', function() {
                    var vehicle_type_id = $('#vehicle_type_id').val();
                    var hotspot_location = $('#hotspot_location').val();
                    var filters = {
                        vehicle_type: vehicle_type_id,
                        hotspot_location: hotspot_location
                    };
                    var queryString = $.param(filters);
                    window.location.href = 'excel_export_parking_pricebook.php?' + queryString;
                });

                $('#parking_export_csv').prop('disabled', true); // Initially disable export button

                // Trigger updateFilters() initially if needed
                updateFilters();
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>