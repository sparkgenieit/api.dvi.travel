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
    <div class="card">
      <div class="card-body dataTable_select text-nowrap">
        <div class="text-nowrap table-responsive table-bordered">
          <table class="table table-hover" id="AGENT_LIST">
            <thead>
              <tr>
                <th scope="col">S.No</th>
                <th scope="col">Quote Id</th>
                <th scope="col">Agent</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Source</th>
                <th scope="col">Destination</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#AGENT_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONagentdashboard.php",
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
              data: "agent_name"
            }, //2
            {
              data: "trip_start_date_and_time"
            }, //3
            {
              data: "trip_end_date_and_time"
            }, //4
            {
              data: "arrival_location"
            }, //5
            {
              data: "departure_location"
            } //6
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
<?php
  endif;
endif;
?>