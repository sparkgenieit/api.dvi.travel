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
                <label class="form-label" for="hotel_state">State <span class="text-danger">*</span></label>
                <select class="form-select" name="hotel_state" id="hotel_state" onchange="CHOOSEN_STATE()" data-parsley-trigger="keyup" data-parsley-errors-container="#state_error_container" required>
                    <?php echo getSTATELIST('101', '', 'select_state'); ?>
                </select>
                <div id="hotel_state_error_container"></div>
            </div>
            <div class="col-3">
                <label class="form-label" for="hotel_city">City <span class="text-danger">*</span></label>
                <select class="form-select" name="hotel_city" id="hotel_city" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_city_error_container">
                    <option value="">Please Choose City</option>
                </select>
                <div id="hotel_city_error_container"></div>
            </div>
            <?php /* <div class="col-2">
                <label class="form-label" for="month">Month <span class="text-danger">*</span></label>
                <select id="month" name="month" required class="form-select form-control">
                    <?= getMONTHS_LIST($month_id, 'select_month'); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="hotel_year">Year <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="hotel_year" id="hotel_year" autocomplete="off" required class="form-control" placeholder="Year" />
                </div>
            </div> */ ?>
            <div class="col-md-2">
                <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="start_date" id="start_date" autocomplete="off" required class="form-control" placeholder="DD/MM/YYYY" />
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="end_date">End Date <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input name="end_date" id="end_date" autocomplete="off" required class="form-control" placeholder="DD/MM/YYYY" />
                </div>
            </div>
            <div class="col-2 d-flex align-items-end justify-content-end">
                <button id="export-btn" class="btn btn-sm btn-label-success" disabled><i class="ti ti-download me-2"></i>Export</button>
            </div>
        </div>
        <div class="mt-4 row g-3" id="hotel_pricebook_details"></div>
        <script src="assets/js/selectize/selectize.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize selectize
                $('#hotel_state').selectize();
                $('#hotel_city').selectize();

                // Initialize flatpickr for start and end dates
                const startDatePicker = flatpickr("#start_date", {
                    dateFormat: "Y-m-d",
                    onChange: function(selectedDates, dateStr) {
                        endDatePicker.set("minDate", dateStr); // Set minDate for end date
                    }
                });

                const endDatePicker = flatpickr("#end_date", {
                    dateFormat: "Y-m-d",
                    onChange: function(selectedDates, dateStr) {
                        startDatePicker.set("maxDate", dateStr); // Set maxDate for start date
                    }
                });

                // Listen for changes in state, city, start_date, and end_date
                $('#hotel_state')[0].selectize.on('change', handleFormChange);
                $('#hotel_city')[0].selectize.on('change', handleFormChange);
                $('#start_date').on('change', handleFormChange);
                $('#end_date').on('change', handleFormChange);

                $('#export-btn').click(function() {
                    let state = $('#hotel_state').val();
                    let city = $('#hotel_city').val();
                    let start_date = $('#start_date').val();
                    let end_date = $('#end_date').val();

                    window.location.href = 'excel_export_hotel_room_pricebook.php?state=' + state + '&city=' + city +
                        '&start_date=' + start_date + '&end_date=' + end_date;
                });
            });

            // Handle form changes and validate
            function handleFormChange() {
                let state = $('#hotel_state').val();
                let city = $('#hotel_city').val();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();

                console.log('Form values:', {
                    state,
                    city,
                    start_date,
                    end_date
                });
                if (state && city && start_date && end_date) {
                    $('#export-btn').prop('disabled', false);
                    sendAjaxRequest(state, city, start_date, end_date);
                } else {
                    $('#export-btn').prop('disabled', true);
                }
            }

            // AJAX request function
            function sendAjaxRequest(state, city, start_date, end_date) {
                $('#spinner').show();
                $.ajax({
                    url: 'engine/ajax/__ajax_hotelroom_pricebook_list.php?type=show_form',
                    type: 'POST',
                    data: {
                        state: state,
                        city: city,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function(response) {
                        $('#spinner').hide();
                        $('#hotel_pricebook_details').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                });
            }

            // Fetch city options based on selected state
            function CHOOSEN_STATE() {
                var city_selectize = $('#hotel_city')[0].selectize;
                var STATE_ID = $('#hotel_state').val();
                $('#spinner').show();
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: 'GET',
                    success: function(response) {
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($hotel_city) : ?>
                            city_selectize.setValue('<?= $hotel_city; ?>');
                        <?php endif; ?>
                        $('#spinner').hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                    }
                });
            }

            function searchTable() {
                var input, filter, fixedTable, scrollableTable, fixedTr, scrollableTr, fixedTd, scrollableTd, i, j, txtValue;
                input = document.getElementById("search");
                filter = input.value.toLowerCase();

                // Fixed columns table
                fixedTable = document.querySelector(".fixed-columns table");
                fixedTr = fixedTable.getElementsByTagName("tr");

                // Scrollable columns table
                scrollableTable = document.querySelector(".scrollable-columns table");
                scrollableTr = scrollableTable.getElementsByTagName("tr");

                var matchedRows = [];

                // Filter fixed columns
                for (i = 1; i < fixedTr.length; i++) { // Start at 1 to skip header row
                    fixedTr[i].style.display = "none"; // Hide all rows initially
                    fixedTd = fixedTr[i].getElementsByTagName("td");
                    for (j = 0; j < fixedTd.length; j++) {
                        if (fixedTd[j]) {
                            txtValue = fixedTd[j].textContent || fixedTd[j].innerText;
                            // Check for hotel name and room name
                            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                fixedTr[i].style.display = ""; // Show row if match found
                                matchedRows.push(fixedTr[i].rowIndex); // Store matched row index
                                break; // Stop searching this row's cells
                            }
                        }
                    }
                }

                // Filter scrollable columns based on matched fixed column rows
                for (i = 1; i < scrollableTr.length; i++) { // Start at 1 to skip header row
                    scrollableTr[i].style.display = "none"; // Hide all rows initially
                    scrollableTd = scrollableTr[i].getElementsByTagName("td");

                    // Check if the current scrollable row is in the matched rows
                    if (matchedRows.includes(i)) {
                        scrollableTr[i].style.display = ""; // Show row if matched
                    }
                }
            }
        </script>

<?php
    endif;
endif;

?>