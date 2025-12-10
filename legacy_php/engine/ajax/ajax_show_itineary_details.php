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

  if ($_GET['type'] == 'show_all') :

?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="itineary_LIST">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Quote ID</th>
              <th scope="col">Source</th>
              <th scope="col">Destination</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Guest</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#itineary_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONitinerarydashboard.php?show=showall",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
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
            }, //5
            {
              data: "customer_name"
            } //6
          ],
          columnDefs: [{
            "targets": 1,
            "data": "itinerary_quote_ID",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                '</a>';

            }
          }],
        });
      });
    </script>
  <?php elseif ($_GET['type'] == 'show_upcoming') :

  ?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="itineary_LIST">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Quote ID</th>
              <th scope="col">Source</th>
              <th scope="col">Destination</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Guest</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#itineary_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONitinerarydashboard.php?show=show_upcoming",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
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
            }, //5
            {
              data: "customer_name"
            } //6
          ],
          columnDefs: [{
            "targets": 1,
            "data": "itinerary_quote_ID",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                '</a>';

            }
          }],
        });
      });
    </script>
  <?php elseif ($_GET['type'] == 'show_ongoing') :

  ?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="itineary_LIST">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Quote ID</th>
              <th scope="col">Daily Moment</th>
              <th scope="col">Source</th>
              <th scope="col">Destination</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Guest</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#itineary_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONitinerarydashboard.php?show=show_oncoming",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
          columns: [{
              data: "count"
            }, //0
            {
              data: "itinerary_plan_ID"
            }, //1
            {
              data: "itinerary_plan_ID"
            }, //2
            {
              data: "arrival_location"
            }, //3
            {
              data: "departure_location"
            }, //4
            {
              data: "trip_start_date_and_time"
            }, //5
            {
              data: "trip_end_date_and_time"
            }, //6
            {
              data: "customer_name"
            } //7
          ],
          columnDefs: [{
              "targets": 1,
              "data": "itinerary_quote_ID",
              "render": function(data, type, row, full) {
                return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                  data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                  '</a>';

              }
            },
            {
              "targets": 2,
              "data": "itinerary_plan_ID",
              "render": function(data, type, row, full) {

                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" target="_blank" href="dailymoment.php?formtype=day_list&id=' + data + '" style="margin-right: 3px;"><span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g data-name="13-car"><path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path><path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path></g></g></svg></span></a></div>';

              }
            }
          ],
        });
      });
    </script>
  <?php elseif ($_GET['type'] == 'show_cancellation') :

  ?>
    <div class="card-body dataTable_select text-nowrap">
      <div>
        <h5 class="text-primary">Coming Soon....</h5>
      </div>
    </div>

<?php
  endif;
endif;
?>