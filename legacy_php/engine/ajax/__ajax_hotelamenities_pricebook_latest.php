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

$month_id = isset($month_id) ? $month_id : null; // Ensure $month_id is set

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST
    if ($_GET['type'] == 'show_form') :
?>

<div class="row">
    <div class="col-md-3">
        <label class="form-label" for="amenity_state">State <span class="text-danger">*</span></label>
        <select class="form-select" name="amenity_state" id="amenity_state" onchange="CHOOSEN_STATE()"
            data-parsley-trigger="keyup" data-parsley-errors-container="#state_error_container" required>
            <?php echo getSTATELIST('101', '', 'select_state'); ?>
        </select>
        <div id="amenity_state_error_container"></div>
    </div>
    <div class="col-3">
        <label class="form-label" for="amenity_city">City <span class="text-danger">*</span></label>
        <select class="form-select" name="amenity_city" id="amenity_city" data-parsley-trigger="keyup"
            data-parsley-errors-container="#amenity_city_error_container">
            <option value="">Please Choose City</option>
        </select>
        <div id="amenity_city_error_container"></div>
    </div>

    <div class="col-2">
        <label class="form-label" for="month">Month<span class="text-danger"> *</span></label>
        <select id="month_name" name="month_name" required class="form-select form-control">
            <?php echo getMONTHS_LIST($month_name, 'select_month'); ?>
        </select>
    </div>
    <div class="col-2">
        <label class="form-label" for="amenity_year">Year <span class="text-danger">*</span></label>
        <div class="input-group">
            <input name="amenity_year" id="amenity_year" autocomplete="off" required class="form-control"
                placeholder="Year" />
        </div>
    </div>
    <!-- <div class="col-2 d-flex align-items-end justify-content-end">
        <button id="amenity-export-btn" class="btn btn-sm btn-label-success"><i
                class="ti ti-download me-2"></i>Export</button>
    </div> -->
    <div class="col-2 d-flex align-items-end justify-content-end">
        <button id="amenity-export-btn" class="btn btn-sm btn-label-success" disabled><i
                class="ti ti-download me-2"></i>Export</button>
    </div>
</div>


<div id="amenities_pricebook_details"></div>

<script>
$(document).ready(function() {
    $('#amenity_state').selectize();
    $('#amenity_city').selectize();
    $('#month_name').selectize();

    $('#amenity_year').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    }).on('changeDate', handleFormChange);

    $('#amenity_state')[0].selectize.on('change', handleFormChange);
    $('#amenity_city')[0].selectize.on('change', handleFormChange);
    $('#month_name').on('change', handleFormChange);
    $('#amenity_year').on('input', handleFormChange);

    $('#amenity-export-btn').click(function() {
        let state = $('#amenity_state').val();
        let city = $('#amenity_city').val();
        let month_name = $('#month_name').val();
        let year = $('#amenity_year').val();
        window.location.href = 'excel_export_hotel_amenity_pricebook.php?state=' + state + '&city=' +
            city + '&month=' + month_name + '&year=' + year;
    });
});

function handleFormChange() {
    let state = $('#amenity_state').val();
    let city = $('#amenity_city').val();
    let month_name = $('#month_name').val();
    let year = $('#amenity_year').val();

    // Enable the export button only if all selections are made
    if (state && city && month_name && year) {
        $('#amenity-export-btn').prop('disabled', false);
    } else {
        $('#amenity-export-btn').prop('disabled', true);
    }

    console.log('Form values:', {
        state,
        city,
        month_name,
        year
    });

    if (state && city && month_name && year) {
        sendAjaxRequest(state, city, month_name, year);
    }
}

function sendAjaxRequest(state, city, month, year) {
    $.ajax({
        url: 'engine/ajax/__ajax_hotel_amenity_pricebook_list.php?type=show_form',
        type: 'POST',
        data: {
            state: state,
            city: city,
            month: month,
            year: year
        },
        success: function(response) {
            console.log('AJAX response:', response);
            $('#amenities_pricebook_details').html(response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            console.log(xhr.responseText);
        }
    });
}

function CHOOSEN_STATE() {
    var city_selectize = $('#amenity_city')[0].selectize;
    var STATE_ID = $('#amenity_state').val();

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
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX error:', textStatus, errorThrown);
        }
    });
}
</script>


<?php
    endif;
endif;
?>