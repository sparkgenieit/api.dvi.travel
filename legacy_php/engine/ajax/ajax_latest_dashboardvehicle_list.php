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
    <div class="nav-align-top mb-4" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="showONROUTEVEHICLE()" aria-selected="true">On Route Vehicle</button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showUPCOMINGVEHICLE()" aria-selected="false">Upcoming Vehicle</button>
        </li>
        <?php if ($logged_user_level != 4): ?>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showIDLEVEHICLE()" aria-selected="false">Idle Vehicle</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showINSERVICEVEHICLE()" aria-selected="false">In Service Vehicle</button>
          </li>
        <?php endif; ?>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="on_route_vehicle" role="tabpanel">
          <span id="show_on_route_vehicle"></span>
        </div>

        <div class="tab-pane fade" id="upcoming_vehicle" role="tabpanel">
          <span id="show_upcoming_vehicle_response"></span>
        </div>

        <div class="tab-pane fade" id="idle_vehicle" role="tabpanel">
          <span id="show_idle_vehicle_response"></span>
        </div>

        <div class="tab-pane fade" id="in_service_vehicle" role="tabpanel">
          <span id="show_in_service_vehicle_response"></span>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        showONROUTEVEHICLE()
        var dataTable = $('#itineary_LIST').DataTable({
          ajax: {
            "url": "engine/json/__JSONvehicledashboard.php?show=showall",
            "type": "GET"
          },
          columns: [{
              data: "count"
            }, //0
            {
              data: "itinerary_plan_ID"
            }, //1
            {
              data: "arrival_location"
            }, //2
            {
              data: "departure_location"
            }, //3
            {
              data: "trip_start_date_and_time"
            }, //4
            {
              data: "trip_end_date_and_time"
            } //5
          ],
          columnDefs: [{
            "targets": 2,
            "data": "itinerary_quote_ID",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                '</a>';

            }
          }],
        });
      });


      function showONROUTEVEHICLE() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_vehicle_details.php?type=show_on_route",
          success: function(response) {
            $('#on_route_vehicle').addClass('show active');
            $('#upcoming_vehicle').removeClass('show active');
            $('#idle_vehicle').removeClass('show active');
            $('#cancellation_trip').removeClass('show active');
            $('#in_service_vehicle').removeClass('show active');
            $('#show_upcoming_vehicle_response').html('');
            $('#show_idle_vehicle_response').html('');
            $('#show_cancellation_trip_response').html('');
            $('#show_in_service_vehicle_response').html('');
            $('#show_on_route_vehicle').html(response);
          }
        });
      }

      function showUPCOMINGVEHICLE() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_vehicle_details.php?type=show_upcoming",
          success: function(response) {
            $('#on_route_vehicle').removeClass('show active');
            $('#upcoming_vehicle').addClass('show active');
            $('#idle_vehicle').removeClass('show active');
            $('#in_service_vehicle').removeClass('show active');
            $('#show_upcoming_vehicle_response').html(response);
            $('#show_idle_vehicle_response').html('');
            $('#show_in_service_vehicle_response').html('');
            $('#show_on_route_vehicle').html('');
          }
        });
      }

      function showIDLEVEHICLE() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_vehicle_details.php?type=show_idle",
          success: function(response) {
            $('#on_route_vehicle').removeClass('show active');
            $('#upcoming_vehicle').removeClass('show active');
            $('#idle_vehicle').addClass('show active');
            $('#in_service_vehicle').removeClass('show active');
            $('#show_upcoming_vehicle_response').html('');
            $('#show_idle_vehicle_response').html(response);
            $('#show_in_service_vehicle_response').html('');
            $('#show_on_route_vehicle').html('');
          }
        });
      }

      function showINSERVICEVEHICLE() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_vehicle_details.php?type=show_service_vehicle",
          success: function(response) {
            $('#on_route_vehicle').removeClass('show active');
            $('#upcoming_vehicle').removeClass('show active');
            $('#idle_vehicle').removeClass('show active');
            $('#in_service_vehicle').addClass('show active');
            $('#show_upcoming_vehicle_response').html('');
            $('#show_idle_vehicle_response').html('');
            $('#show_in_service_vehicle_response').html(response);
            $('#show_on_route_vehicle').html('');
          }
        });
      }
    </script>
<?php
  endif;
endif;
?>