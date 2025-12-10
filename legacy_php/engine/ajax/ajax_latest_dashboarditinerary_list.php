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
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="showALLITINEARY()" aria-selected="true">Overall</button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showUPCOMINGITINEARY()" aria-selected="false">Upcoming</button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showONGOINGITINEARY()" aria-selected="false">Ongoing</button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="showCANCELLATIONITINEARY()" aria-selected="false">Cancellation</button>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="all_itineary" role="tabpanel">
          <span id="show_all_itineary_response"></span>
        </div>

        <div class="tab-pane fade" id="navs-upcoming" role="tabpanel">
          <span id="show_upcoming_itineary_response"></span>
        </div>

        <div class="tab-pane fade" id="navs-ongoing" role="tabpanel">
          <span id="show_ongoing_itineary_response"></span>
        </div>

        <div class="tab-pane fade" id="navs-cancellation" role="tabpanel">
          <span id="show_cancellation_itineary_response"></span>
        </div>
      </div>
    </div>

    <!-- jQuery -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <!-- DataTables -->
    <script src="./assets/js/_jquery.dataTables.min.js"></script>
    <!-- Moment.js -->
    <script src="./assets/vendor/libs/moment/moment.js"></script>
    <!-- DataTables Moment.js plugin -->
    <script src="./assets/vendor/libs/moment/datetime-moment.js"></script>

    <script>
      $(document).ready(function() {
        // 1. Register the date format with DataTables before initializing the table
        $.fn.dataTable.moment('DD-MM-YYYY hh:mm A');

        showALLITINEARY()
        var dataTable = $('#itineary_LIST').DataTable({
          ajax: {
            "url": "engine/json/__JSONitinerarydashboard.php?show=showall",
            "type": "GET"
          },
          columns: [{
              data: "count"
            }, //0
            {
              data: "itinerary_quote_ID"
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
            "data": "quote_id",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.quote_id +
                '</a>';

            }
          }],
        });
      });

      function showALLITINEARY() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_itineary_details.php?type=show_all",
          success: function(response) {
            $('#all_itineary').addClass('show active');
            $('#navs-upcoming').removeClass('show active');
            $('#navs-ongoing').removeClass('show active');
            $('#navs-cancellation').removeClass('show active');
            $('#show_upcoming_itineary_response').html('');
            $('#show_ongoing_itineary_response').html('');
            $('#show_cancellation_itineary_response').html('');
            $('#show_all_itineary_response').html(response);
          }
        });
      }

      function showUPCOMINGITINEARY() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_itineary_details.php?type=show_upcoming",
          success: function(response) {
            $('#all_itineary').removeClass('show active');
            $('#navs-upcoming').addClass('show active');
            $('#navs-ongoing').removeClass('show active');
            $('#navs-cancellation').removeClass('show active');
            $('#show_upcoming_itineary_response').html(response);
            $('#show_ongoing_itineary_response').html('');
            $('#show_cancellation_itineary_response').html('');
            $('#show_all_itineary_response').html('');
          }
        });
      }

      function showONGOINGITINEARY() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_itineary_details.php?type=show_ongoing",
          success: function(response) {
            $('#all_itineary').removeClass('show active');
            $('#navs-upcoming').removeClass('show active');
            $('#navs-ongoing').addClass('show active');
            $('#navs-cancellation').removeClass('show active');
            $('#show_upcoming_itineary_response').html('');
            $('#show_ongoing_itineary_response').html(response);
            $('#show_cancellation_itineary_response').html('');
            $('#show_all_itineary_response').html('');
          }
        });
      }

      function showCANCELLATIONITINEARY() {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_show_itineary_details.php?type=show_cancellation",
          success: function(response) {
            $('#all_itineary').removeClass('show active');
            $('#navs-upcoming').removeClass('show active');
            $('#navs-ongoing').addClass('show active');
            $('#navs-cancellation').removeClass('show active');
            $('#show_upcoming_itineary_response').html('');
            $('#show_ongoing_itineary_response').html(response);
            $('#show_cancellation_itineary_response').html('');
            $('#show_all_itineary_response').html('');
          }
        });
      }
    </script>
<?php
  endif;
endif;
?>