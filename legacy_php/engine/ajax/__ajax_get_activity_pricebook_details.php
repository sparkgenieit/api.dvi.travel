<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST
    if ($_GET['type'] == 'show_form') :
?>
        <div class="row">
            <div class="col-3">
                <label class="form-label" for="activity_month">Month <span class="text-danger">*</span></label>
                <select class="form-select" name="activity_month" id="activity_month">
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
                <label class="form-label" for="activity_year">Year <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="activity_year" id="activity_year" autocomplete="off" required class="form-control" placeholder="Year" />
                </div>
            </div>
            <div class="col-md-7 d-flex justify-content-end align-items-end">
                <button class="btn btn-sm btn-label-success mb-3" id="activity_export_csv"><i class="ti ti-download me-2"></i>Export</button>
            </div>
        </div>
        <div class="row mt-4" id="activity_table_container" style="display: none;">
            <div class="col-md-12">
                <div class="card-body dataTable_select text-nowrap">
                    <div class="table-responsive table-bordered">
                        <table class="table table-hover" id="activity_pricebook_LIST">
                            <thead>
                                <th scope="col">S.No</th>
                                <th scope="col">Activity Name</th>
                                <th scope="col">Hotspot</th>
                                <th scope="col">Month</th>
                                <th scope="col">Year</th>
                                <th scope="col">Nationality</th>
                                <th scope="col">Price Type</th>
                                <?php for ($day = 1; $day <= 31; $day++) : ?>
                                    <th scope="col">Day <?= $day; ?></th>
                                <?php endfor; ?>
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
                $("select").selectize();
                $('#activity_year').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years"
                });

                var table = $('#activity_pricebook_LIST').DataTable({
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
                            data: 'activity_name'
                        },
                        {
                            data: 'hotspot_name'
                        },
                        {
                            data: 'month'
                        },
                        {
                            data: 'year'
                        },
                        {
                            data: 'nationality_name'
                        },
                        {
                            data: 'price_type'
                        },
                        <?php for ($day = 1; $day <= 31; $day++) : ?> {
                                data: 'day_<?= $day; ?>',
                                render: function(data, type, row) {
                                    return data ? '₹' + data : '';
                                }
                            },
                        <?php endfor; ?>
                    ]
                });

                function fetchPricebookData(filters) {
                    $.ajax({
                        url: "engine/json/__JSONactivity_pricebook.php",
                        type: "GET",
                        dataType: 'json',
                        data: filters,
                        success: function(response) {
                            if (response.data.length === 0) {
                                table.clear().draw();
                                $('#activity_export_csv').prop('disabled', true);
                            } else {
                                table.clear().rows.add(response.data).draw();
                                $('#activity_export_csv').prop('disabled', false);
                            }
                            $('#activity_table_container').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                            table.clear().draw();
                            $('#activity_pricebook_LIST tbody').html('<tr><td colspan="40">Error fetching data. Please try again later.</td></tr>');
                            $('#activity_export_csv').prop('disabled', true);
                        }
                    });
                }

                function updateFilters() {
                    var month = $('#activity_month').val();
                    var year = $('#activity_year').val();
                    if (month && year) {
                        var filters = {
                            month: month,
                            year: year
                        };
                        fetchPricebookData(filters);
                    } else {
                        $('#activity_table_container').hide();
                        $('#activity_export_csv').prop('disabled', true);
                    }
                }

                $('#activity_month, #activity_year').on('change', function() {
                    updateFilters();
                    $('#activity_year').datepicker('hide'); // Close the year picker after selection
                });

                $('#activity_export_csv').on('click', function() {
                    var month = $('#activity_month').val();
                    var year = $('#activity_year').val();
                    var filters = {
                        month: month,
                        year: year
                    };
                    var queryString = $.param(filters);
                    window.location.href = 'excel_export_activity_pricebook.php?' + queryString;
                });

                $('#activity_export_csv').prop('disabled', true);
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>