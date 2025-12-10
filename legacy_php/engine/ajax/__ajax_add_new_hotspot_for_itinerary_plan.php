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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'show_form') :

        $hidden_location_name = $_POST['hidden_location_name'];
        $hidden_itinerary_route_date = $_POST['hidden_itinerary_route_date'];
        $hidden_itinerary_count = $_POST['hidden_itinerary_count'];
        $itinerary_route_ID = $_POST['itinerary_route_ID'];
        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $next_visiting_location_name = $_POST['next_visiting_location_name'];
        $via_route_location = $_POST['via_route_location'];

        $itinerary_hotspot_type = 'tourist_attraction'; //$itinerary_hotspot_type 
?>
        <style>
            #show_available_hotspot_list_border {
                display: block;
                border: 1px solid #ccc;
                /* Add border style and color */
                max-height: 530px;
                /* Set maximum height to enable scrollbar */
                overflow-y: auto;
                /* Enable vertical scrollbar when content exceeds max-height */
                padding: 20px;
                /* Add padding for better appearance */
                border-radius: 10px;
            }

            #show_available_hotspot_list::-webkit-scrollbar {
                width: 8px;
                /* Adjust scrollbar width */
            }

            #show_available_hotspot_list::-webkit-scrollbar-thumb {
                background-color: #888;
                /* Adjust scrollbar thumb color */
                border-radius: 4px;
                /* Adjust scrollbar thumb border radius */
            }
        </style>

        <!-- Itinerary Customization -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h5 class="text-capitalize mb-0">Itinerary Customization</h5>
                <p class="text-secondary mb-0">Select the hotspots you would like to include for visit.</p>
            </div>
            <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm view_itinerary_<?= $itinerary_plan_ID; ?>_<?= $itinerary_route_ID; ?>"" onclick="show_added_ITINERARY_DETAILS('<?= $hidden_itinerary_count; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $hidden_itinerary_route_date; ?>')"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> View Itinerary </button>
        </div>

        <script>
            $(document).ready(function() {
                show_HOTSPOTS(<?= $hidden_itinerary_count; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, '<?= $hidden_itinerary_route_date; ?>', '<?= $hidden_location_name; ?>', '<?= $next_visiting_location_name ?>', '<?= $via_route_location ?>');
            });

            function show_HOTSPOTS(itinerary_count, itinerary_route_ID, itinerary_plan_ID, hidden_itinerary_route_date, hidden_location_name, next_visiting_location_name, via_route_location_name) {
                var itinerary_hotspot_type = $('#itinerary_hotspot_type.itinerary_hotspot_type_' + itinerary_count).val();
                var itinerary_hotspot_places = $('#itinerary_hotspot_places.itinerary_hotspot_places_' + itinerary_count).val();

                $('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html('');
                $('#show_loader_response.show_loader_response_' + itinerary_count).html('<div class="card p-5"><div class="text-center" role="alert"><img src="assets/img/illustrations/loading_hotspot.gif" width="500" height="300"><h3>Fetching Hotspots Details...</h3></div></div>');

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/_ajax_check_hotspot_list.php?type=show_form',
                    data: {
                        itinerary_count: itinerary_count,
                        itinerary_hotspot_type: itinerary_hotspot_type,
                        itinerary_hotspot_places: itinerary_hotspot_places,
                        hidden_location_name: hidden_location_name,
                        hidden_itinerary_route_date: hidden_itinerary_route_date,
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: itinerary_plan_ID,
                        next_visiting_location_name: next_visiting_location_name,
                        via_route_location_name: via_route_location_name
                    },
                    success: function(response) {
                        $('#show_loader_response.show_loader_response_' + itinerary_count).html('');
                        $('#show_added_hotspot_response.show_added_hotspot_response_' + itinerary_count).html('');
                        $('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html(response);
                    }
                });
            }

            /* function search_HOTSPOTS_CHANGE(itinerary_count, itinerary_route_ID, itinerary_plan_ID, hidden_itinerary_route_date, hidden_location_name, next_visiting_location_name, via_route_location_name, itinerary_hotspot_places) {
                var itinerary_hotspot_type = $('#itinerary_hotspot_type.itinerary_hotspot_type_' + itinerary_count).val();

                $('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html('');
                $('#show_loader_response.show_loader_response_' + itinerary_count).html('<div class="card p-5"><div class="text-center" role="alert"><img src="assets/img/illustrations/loading_hotspot.gif" width="500" height="300"><h3>Fetching Hotspots Details...</h3></div></div>');

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/_ajax_check_hotspot_list.php?type=show_form',
                    data: {
                        itinerary_count: itinerary_count,
                        itinerary_hotspot_type: itinerary_hotspot_type,
                        itinerary_hotspot_places: itinerary_hotspot_places,
                        hidden_location_name: hidden_location_name,
                        hidden_itinerary_route_date: hidden_itinerary_route_date,
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: itinerary_plan_ID,
                        next_visiting_location_name: next_visiting_location_name,
                        via_route_location_name: via_route_location_name
                    },
                    success: function(response) {
                        $('#show_loader_response.show_loader_response_' + itinerary_count).html('');
                        $('#show_added_hotspot_response.show_added_hotspot_response_' + itinerary_count).html('');
                        $('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html(response);
                    }
                });
            } */
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
