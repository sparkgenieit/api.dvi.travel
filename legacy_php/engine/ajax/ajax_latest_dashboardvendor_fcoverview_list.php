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
          <table class="table table-hover" id="VENDOR_VEHICLE_LIST">
            <thead>
              <tr>
                <th scope="col">S.No</th>
                <th scope="col">Vehicle Number</th>
                <th scope="col">Vehicle Type</th>
                <th scope="col">FC Date</th>
                <th scope="col">FC Status</th>
                <th scope="col">Insurance Date</th>
                <th scope="col">Insurance Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var dataTable = $('#VENDOR_VEHICLE_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          "pageLength": 5, // Set default number of entries to show
          ajax: {
            "url": "engine/json/__JSONvendor_vehicle_details.php",
            "type": "GET"
          },
          "lengthMenu": [5, 10, 25, 50, 100], // Options for number of entries to show
          columns: [{
              data: "count"
            }, //0
            {
              data: "vehicle_number"
            }, //1
            {
              data: "vehicle_type"
            }, //2
            {
              data: "fc_date"
            }, //3
            {
              data: "fc_status"
            }, //4
            {
              data: "insurance_end_date"
            }, //5
            {
              data: "insurance_status"
            }, //6
            {
              data: "modify"
            } //7
          ],
          columnDefs: [{
            "targets": 7,
            "data": "modify",
            "render": function(data, type, row, full) {
              var route = 'edit';
              var vendor_id = '<?= $logged_vendor_id; ?>';
              var branch_id = '<?= getVENDORBRANCHDETAIL('', $logged_vendor_id, 'get_vendor_branch_id'); ?>';
              return '<a class="text-white btn btn-primary" onclick="addVEHICLEDETAILS(' + vendor_id + ', ' + branch_id + ', ' + data + ', \'' + route + '\');" target="_blank" style="margin-right: 10px;">View</a>';
            }
          }, {
            "targets": 4,
            "data": "fc_status",
            "render": function(data, type, row, full) {
              switch (row.vehicle_fc_expiry_date_status) {
                case '1':
                  return '<div class="media-body text-start"><span class="badge bg-label-success me-1">' + row.fc_status + '</span></div>';
                  break;
                case '0':
                  return '<div class="media-body text-start"><span class="badge bg-label-danger me-1">' + row.fc_status + '</span></div>';
                  break;
              }
            }
          }, {
            "targets": 6,
            "data": "insurance_status",
            "render": function(data, type, row, full) {
              switch (row.insurance_end_date_status) {
                case '1':
                  return '<div class="media-body text-start"><span class="badge bg-label-success me-1">' + row.insurance_status + '</span></div>';
                  break;
                case '0':
                  return '<div class="media-body text-start"><span class="badge bg-label-danger me-1">' + row.insurance_status + '</span></div>';
                  break;
              }
            }
          }],
        });
      });

      function addVEHICLEDETAILS(VENDOR_ID, BRANCH_ID, VEHICLE_ID, ROUTE) {
        const baseURL = "newvendor.php";
        const route = ROUTE;
        const formtype = "vehicle_info";
        const id = VENDOR_ID;
        const vbranch = BRANCH_ID;
        const v_id = VEHICLE_ID;

        // First, redirect the user to the new page
        const url =  baseURL + `?route=${route}&formtype=${formtype}&id=${id}&vbranch=${vbranch}&v_id=${v_id}`;

        console.log("Redirecting to:", url); // Debugging the redirect
        window.open(url, '_blank'); // Open the URL in a new tab

      }
    </script>
<?php
  endif;
endif;
?>