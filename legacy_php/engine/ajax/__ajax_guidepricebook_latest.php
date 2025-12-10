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
            <div class="col-md-3">
                <label class="form-label" for="guide_month">Month <span class="text-danger">*</span> </label>
                <select id="guide_month" name="guide_month" required class="form-select form-control">
                    <?= getMONTHS_LIST($month_id, 'select_month'); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="guide_year">Year <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="guide_year" id="guide_year" autocomplete="off" required class="form-control"
                        placeholder="Month" />
                </div>
            </div>
            <!-- <div class="col-md-6 d-flex align-items-end justify-content-end">
                <button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
            </div> -->

            <div class="col-6 d-flex align-items-end justify-content-end">
                <button id="export-guide-btn" class="btn btn-sm btn-label-success" disabled><i
                        class="ti ti-download me-2"></i>Export</button>
            </div>
        </div>
        <div id="guide_pricebook_details"></div>

        <!-- <script>
$(document).ready(function() {
    var $select = $('#guide_month').selectize();
    var selectize = $select[0].selectize;

    $('#guide_year').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true // Enable autoclose after selection
    });

    // Trigger the function when either the month or the year changes
    $('#guide_month, #guide_year').on('change', function() {
        var month = selectize.getValue(); // Correct way to get the value from Selectize
        var year = $('#guide_year').val(); // Get the input year
        // alert(month);
        // Make the AJAX call
        $.ajax({
            url: 'engine/ajax/__ajax_guide_pricebook_details_list.php?type=show_form', // Removed spaces around '='
            type: 'POST', // Ensure it uses POST method
            data: {
                guide_month: month,
                guide_year: year
            },
            success: function(response) {
                $('#guide_pricebook_details').html(response);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error sending data: ' + error);
            }
        });
    });
});
// Handle export button click
$('#export-guide-btn').click(function() {
    let month = $('#guide_month').val(); // Corrected ID for month
    let year = $('#guide_year').val();

    // Check if month and year are selected before proceeding
    if (!month || !year) {
        alert('Please select both month and year before exporting.');
        return; // Stop the function if no month or year is selected
    }

    // Redirect to the export script with parameters
    window.location.href = 'excel_export_guide_pricebook.php?month=' + month + '&year=' + year;
});
</script> -->
        <script>
            $(document).ready(function() {
                var $select = $('#guide_month').selectize();
                var selectize = $select[0].selectize;

                var yearPicker = $('#guide_year').datepicker({
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years",
                    autoclose: true // Enable autoclose after selection
                });

                function loadData() {
                    var month = selectize.getValue();
                    var year = $('#guide_year').val();
                    if (month && year) { // Ensure both month and year are selected
                        $.ajax({
                            url: 'engine/ajax/__ajax_guide_pricebook_details_list.php?type=show_form',
                            type: 'POST',
                            data: {
                                guide_month: month,
                                guide_year: year
                            },
                            success: function(response) {
                                $('#export-guide-btn').prop('disabled',
                                    false); // Enable the export button if a location is selected
                                $('#guide_pricebook_details').html(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error sending data: ' + error);
                            }
                        });
                    }
                }

                // Trigger data load on change events
                $('#guide_month, #guide_year').on('change', loadData);

                $('#export-guide-btn').click(function() {
                    let month = $('#guide_month').val();
                    let year = $('#guide_year').val();
                    if (!month || !year) {
                        alert('Please select both month and year before exporting.');
                        return;
                    }
                    window.location.href = 'excel_export_guide_pricebook.php?month=' + month + '&year=' + year;
                });
            });
        </script>



<?php
    endif;
endif;

?>