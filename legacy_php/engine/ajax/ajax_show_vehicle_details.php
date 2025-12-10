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

  if ($_GET['type'] == 'show_on_route') :

?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="VEHICLE_LIST">
          <thead>
            <tr>
            <th scope="col">S.No</th>
              <th scope="col">Booking ID</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Vendor</th>
              <th scope="col">Branch</th>
              <th scope="col">Vehicle</th>
              <th scope="col">Driver</th>
              <th scope="col">Driver No</th>
              <th scope="col">Guest</th>
              <th scope="col">Arrival</th>
              <th scope="col">Departure</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#VEHICLE_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONvehicledashboard.php?show=show_oncoming",
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
              data: "start_date_and_time"
            }, //2
            {
              data: "end_date_and_time"
            }, //3
            {
              data: "vendor_name"
            }, //4
            {
              data: "branch_name"
            }, //5
            {
              data: "vehicle_type"
            }, //6
            {
              data: "driver_name"
            }, //7
            {
              data: "driver_mobile_no"
            }, //8
            {
              data: "customer_name"
            }, //9
            {
              data: "get_arrival_location"
            }, //10
            {
              data: "get_departure_location"
            } //11  
      
          ],
          columnDefs: [{
            "targets": 1,
            "data": "itinerary_plan_ID",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                '</a>';

            }
          }],
        });
      });
    </script>
  <?php  elseif ($_GET['type'] == 'show_upcoming') :

?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="VEHICLE_LIST_UPCOMING">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Booking ID</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
              <th scope="col">Vendor</th>
              <th scope="col">Branch</th>
              <th scope="col">Vehicle</th>
              <th scope="col">Driver</th>
              <th scope="col">Driver No</th>
              <th scope="col">Guest</th>
              <th scope="col">Arrival</th>
              <th scope="col">Departure</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#VEHICLE_LIST_UPCOMING').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONvehicledashboard.php?show=show_upcoming",
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
              data: "start_date_and_time"
            }, //2
            {
              data: "end_date_and_time"
            }, //3
            {
              data: "vendor_name"
            }, //4
            {
              data: "branch_name"
            }, //5
            {
              data: "vehicle_type"
            }, //6
            {
              data: "driver_name"
            }, //7
            {
              data: "driver_mobile_no"
            }, //8
            {
              data: "customer_name"
            }, //9
            {
              data: "get_arrival_location"
            }, //10
            {
              data: "get_departure_location"
            } //11
          ],
          columnDefs: [{
            "targets": 1,
            "data": "itinerary_plan_ID",
            "render": function(data, type, row, full) {
              return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                '</a>';

            }
          }],
        });
      });
    </script>
  <?php  elseif ($_GET['type'] == 'show_idle') :

?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="VEHICLE_LIST">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Vendor Name</th>
              <th scope="col">Branch Name</th>
              <th scope="col">Vehicle Number</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#VEHICLE_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONvehicledashboard.php?show=show_idle",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
          columns: [{
              data: "count"
            }, //0
            {
              data: "vendor_name"
            }, //1
            {
              data: "branch_name"
            }, //2
            {
              data: "registration_number"
            } //3
            
          ]
        });
      });
    </script>

  <?php  elseif ($_GET['type'] == 'show_service_vehicle') :

?>
    <div class="card-body dataTable_select text-nowrap">
      <div class="text-nowrap table-responsive table-bordered">
        <table class="table table-hover" id="VEHICLE_LIST">
          <thead>
            <tr>
              <th scope="col">S.No</th>
              <th scope="col">Vendor Name</th>
              <th scope="col">Branch Name</th>
              <th scope="col">Vehicle Number</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#VEHICLE_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5,
          ajax: {
            "url": "engine/json/__JSONvehicledashboard.php?show=show_service_vehicle",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100],
          columns: [{
              data: "count"
            }, //0
            {
              data: "vendor_name"
            }, //1
            {
              data: "branch_name"
            }, //2
            {
              data: "registration_number"
            } //3
            
          ]
        });
      });
    </script>


<?php
  endif;
endif;
?>