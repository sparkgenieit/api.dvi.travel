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
            <div class="col-md-4">
                <label class="form-label" for="hotspot_location">Hotspot Location <span class="text-danger">*</span></label>
                <select class="form-control form-select" required id="hotspot_location" name="hotspot_location">
                    <?= getGOOGLE_LOCATION_DETAILS($hotspot_location, 'select'); ?>
                </select>
            </div>
            <div class="col-8 d-flex align-items-end justify-content-end">
                <button id="hotspot-export-btn" class="btn btn-sm btn-label-success" disabled><i class="ti ti-download me-2"></i>Export</button>
            </div>
        </div>


        <div id="hotspot_pricebook_details"></div>

        <!-- <script>
$(document).ready(function() {
    // Initialize the hotspot location selectize if needed
    $('#hotspot_location').selectize();

    // Event listener for changes on the hotspot location
    $('#hotspot_location').on('change', function() {
        let hotspotLocation = $(this).val(); // Get the selected value

        // Check if the hotspot location is selected
        if (hotspotLocation) {
            sendLocationAjax(hotspotLocation); // Send AJAX request
        }
    });
});

// Function to handle AJAX request for hotspot location
function sendLocationAjax(location) {
    $.ajax({
        url: 'engine/ajax/__ajax_get_hotspot_pricebook_details.php?type=show_form', // Update this URL to your endpoint
        type: 'POST',
        data: {
            hotspot_location: location
        },
        // success: function(response) {
        //     console.log('AJAX response:', response);
        //     // You can update the DOM or perform actions based on the response
        // },
        success: function(response) {
            console.log('AJAX response:', response);
            $('#hotspot_pricebook_details').html(response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
        }
    });
}
$('#hotspot-export-btn').click(function() {
    let hotspot_location = $('#hotspot_location').val();
    window.location.href = 'excel_export_hotspot_pricebook.php?hotspot_location=' + hotspot_location;
});
</script> -->
        <script>
            $(document).ready(function() {
                // Initialize the hotspot location selectize if needed
                $('#hotspot_location').selectize({
                    onChange: function(value) {
                        if (value) {
                            $('#hotspot-export-btn').prop('disabled',
                                false); // Enable the export button if a location is selected
                            sendLocationAjax(value); // Send AJAX request
                        } else {
                            $('#hotspot-export-btn').prop('disabled',
                                true); // Keep the export button disabled if no location is selected
                        }
                    }
                });

                $('#hotspot-export-btn').click(function() {
                    let hotspot_location = $('#hotspot_location').val();
                    if (hotspot_location) {
                        window.location.href = 'excel_export_hotspot_pricebook.php?hotspot_location=' +
                            hotspot_location;
                    } else {
                        console.log(
                            'No location selected'); // You can replace this with a user-friendly message if needed
                    }
                });
            });

            // Function to handle AJAX request for hotspot location
            function sendLocationAjax(location) {
                $.ajax({
                    url: 'engine/ajax/__ajax_get_hotspot_pricebook_details.php?type=show_form',
                    type: 'POST',
                    data: {
                        hotspot_location: location
                    },
                    success: function(response) {
                        console.log('AJAX response:', response);
                        $('#hotspot_pricebook_details').html(response);
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