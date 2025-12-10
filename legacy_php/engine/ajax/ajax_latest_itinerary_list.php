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

if ($logged_user_level == 4) :
  $select_subscribed_query = sqlQUERY_LABEL("SELECT `itinerary_allowed` FROM `dvi_agent_subscribed_plans` WHERE `deleted` = '0'  AND `agent_ID` = '$logged_agent_id'") or die("#1-UNABLE_TO_COLLECT_SUBSCRIBED_PLAN:" . sqlERROR_LABEL());
  while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_query)) :
    $itinerary_allowed = $fetch_data['itinerary_allowed'];
  endwhile;

  // Get current staff count
  $current_itinerary_count_query = sqlQUERY_LABEL("SELECT COUNT(*) as count FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' AND `agent_id` = '$logged_agent_id'")
    or die("#2-UNABLE_TO_COLLECT_STAFF_COUNT:" . sqlERROR_LABEL());
  $current_itinerary_count = sqlFETCHARRAY_LABEL($current_itinerary_count_query)['count'];

  if ($current_itinerary_count >= $itinerary_allowed) :
    $add_onclick = "onclick='EXCEEDED_THE_ITINERARY_LIMIT()'";
  else :
    $add_onclick = "onclick='show_itinerary_add_FORM()'";
  endif;
else :
  $add_onclick = "onclick='show_itinerary_add_FORM()'";
endif;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST
  if ($_GET['type'] == 'show_form') :
?>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-body p-3">
            <h5 class="card-title text-uppercase">Filter</h5>
            <div class="row align-items-end">
              <div class="col-md-3 mb-2">
                <label class="form-label" for="start_date">Start Date</label>
                <input type="text" id="start_date" name="start_date" class="form-control" placeholder="DD/MM/YYYY">
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label" for="end_date">End Date</label>
                <input type="text" id="end_date" name="end_date" class="form-control" placeholder="DD/MM/YYYY">
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label" for="source_location">Orgin</label>
                <div class="form-group">
                  <select name="source_location" id="source_location" class="form-select form-control location" required>
                    <?= getSOURCE_LOCATION_DETAILS($selected_value, 'select_source'); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label" for="destination_location">Destination</label>
                <div class="form-group">
                  <select name="destination_location" id="destination_location" class="form-select form-control location" required>
                    <option value=""> Choose Location</option>
                    <?php // getSOURCE_LOCATION_DETAILS($selected_value, 'select_destination'); 
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label" for="hotel_state">Agent Name</label>
                <div class="form-group">
                  <select name="agent_select" id="agent_select" class="form-select form-control" required>
                    <?= getAGENT_details($agent_id, $logged_staff_id, 'select') ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-2">
                <label class="form-label" for="hotel_state">Agent Staff</label>
                <div class="form-group">
                  <select name="agent_staff_id" id="agent_staff_id" class="form-select form-control" required>
                    <?= getAGENT_STAFF_DETAILS($staff_ID, 'select') ?>
                  </select>
                </div>
              </div>

              <div class="col-md-4 mb-2">
                <a href="latestitinerary.php" id="filter_clear" class="btn btn-secondary">Clear </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    $select_itineary_list_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_ID`) AS itinerary_count FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_list_query)) :
      $itinerary_count = $fetch_list_data['itinerary_count'];
    endwhile;
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-3">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h5 class="card-title mb-3 mt-2">List of Itinerary <span class="text-blue-color">[Total Itinerary Count : <?= $itinerary_count; ?>]</span></h5>
              </div>
              <div class="col-md-6 text-end ps-0">
                <button type="button" <?= $add_onclick; ?> id="add_hotel" class="btn btn-label-primary waves-effect">+ Add Itinerary</button>
              </div>
            </div>
          </div>


          <div class="card-body dataTable_select text-nowrap">
            <div class="text-nowrap table-responsive table-bordered">
              <table id="itinerary_LIST" class="table table-hovera">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Quote ID</th>
                    <th>Arrival</th>
                    <th>Departure</th>
                    <th>Created By</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Created On</th>
                    <th>Nights & Days</th>
                    <th>No of Person</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      var itinerary_LIST;

      $("select").selectize();
      $(document).ready(function() {



        flatpickr("#start_date", {
          dateFormat: "d-m-Y",
        });

        flatpickr("#end_date", {
          dateFormat: "d-m-Y",
        });

        $('#source_location').change(function() {
          // console.log("a");
          var source_location = $(this).val();
          var destination_location = $('#destination_location').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          var agent_select = $('#agent_select').val();
          var agent_staff_id = $('#agent_staff_id').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
          get_destination_location_details();
        });

        $('#destination_location').change(function() {
          var destination_location = $(this).val();
          var source_location = $('#source_location').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          var agent_select = $('#agent_select').val();
          var agent_staff_id = $('#agent_staff_id').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
        });


        $('#start_date').change(function() {
          var start_date = $(this).val();
          var destination_location = $('#destination_location').val();
          var end_date = $('#end_date').val();
          var source_location = $('#source_location').val();
          var agent_select = $('#agent_select').val();
          var agent_staff_id = $('#agent_staff_id').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
        });

        $('#end_date').change(function() {
          var end_date = $(this).val();
          var source_location = $('#source_location').val();
          var destination_location = $('#destination_location').val();
          var start_date = $('#start_date').val();
          var agent_select = $('#agent_select').val();
          var agent_staff_id = $('#agent_staff_id').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
        });


        $('#agent_select').change(function() {
          var agent_select = $(this).val();
          var source_location = $('#source_location').val();
          var destination_location = $('#destination_location').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          var agent_staff_id = $('#agent_staff_id').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
        });

        $('#agent_staff_id').change(function() {
          var agent_staff_id = $(this).val();
          var source_location = $('#source_location').val();
          var destination_location = $('#destination_location').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          var agent_select = $('#agent_select').val();
          itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php?source_location=' + source_location +
            '&destination_location=' + destination_location + '&start_date=' + start_date + '&end_date=' + end_date + '&agent_id=' + agent_select + '&staff_id=' + agent_staff_id).load();
        });



        itinerary_LIST = $('#itinerary_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          buttons: [{
              extend: 'copy',
              text: window.copyButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Only name, email and role
              }
            },
            {
              extend: 'excel',
              text: window.excelButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Only name, email and role
              }
            },
            {
              extend: 'csv',
              text: window.csvButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Only name, email and role
              }
            }
          ],
          initComplete: function() {
            $('.buttons-copy').html(
              '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>'
            );

            $('.buttons-csv').html(
              '<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>'
            );

            $('.buttons-excel').html(
              '<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
            );
          },
          "processing": true,
          "serverSide": true,
          ajax: {
            "url": "engine/json/__JSONlastest_itinerary.php",
            "type": "GET",
            "data": function(d) {
              d.source_location = $('#source_location').val().trim();
              d.destination_location = $('#destination_location').val().trim();
            }
          },
          columns: [{
              data: "counter"
            }, //0
            {
              data: "modify"
            }, //1
            {
              data: "arrival_location"
            }, //2
            {
              data: "departure_location"
            }, //3
            {
              data: "username"
            }, //4
            {
              data: "trip_start_date_and_time"
            }, //5
            {
              data: "trip_end_date_and_time"
            }, //6
            {
              data: "createdon"
            }, //7
            {
              data: "no_of_days_and_nights"
            }, //8
            {
              data: "no_of_person"
            } //9

          ],
          columnDefs: [{
            targets: [2, 3], // Targets the "arrival_location" and "departure_location" columns
            render: function(data, type, row) {
              var truncatedText = data.split(' ').slice(0, 3).join(
                ' '); // Truncate to first three words
              return '<span data-toggle="tooltip" placement="top" title="' + data + '">' +
                truncatedText +
                '</span>'; // Display truncated text with full name in tooltip
            }
          }, {
            "targets": 1,
            "data": "modify",
            "render": function(data, type, row, full) {
              // Preference Icon
              let PreferenceIcon = '';
              if (row.itinerary_preference == 1) {
                PreferenceIcon = '<span class="badge bg-primary me-2" data-bs-toggle="tooltip" title="Hotel">H</span>';
              } else if (row.itinerary_preference == 2) {
                PreferenceIcon = '<span class="badge bg-primary me-2" data-bs-toggle="tooltip" title="Vehicle">V</span>';
              } else if (row.itinerary_preference == 3) {
                PreferenceIcon = '<span class="badge bg-primary me-2" data-bs-toggle="tooltip" title="Both Hotel and Vehicle">B</span>';
              }

              // Booking and Quote Links
              let bookingQuoteLinks = `
    <div class="d-flex flex-column">
      <a target="_blank" class="fw-bold text-dark" data-bs-toggle="tooltip" title="Edit Booking ID"
         href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=${data}">
        ${row.itinerary_booking_ID}
      </a>
      <a target="_blank" class="text-primary" data-bs-toggle="tooltip" title="Edit Quote ID"
         href="latestitinerary.php?route=add&formtype=generate_itinerary&id=${data}">
        ${row.itinerary_quote_ID}
      </a>
    </div>`;

              // Edit button
              let basicInfoLink = `
    <a class="btn btn-sm btn-icon text-primary" data-bs-toggle="tooltip" title="Edit"
       href="latestitinerary.php?route=add&formtype=basic_info&id=${data}">
      <img class="img-fluid" src="assets/img/svg/edit.svg" />
    </a>`;

              // Export button
              let exportLink = '';
              <?php if ($logged_user_level != 4) : ?>
                exportLink = `
      <a class="btn btn-sm btn-icon text-primary" data-bs-toggle="tooltip" title="Export" target="_blank"
         href="excel_export_itinerary.php?id=${data}">
        <img class="img-fluid" src="assets/img/svg/downloads.svg" />
      </a>`;
              <?php endif; ?>

              // Combine all
              return `
    <div class="d-flex align-items-center gap-2">
      ${PreferenceIcon}
      ${bookingQuoteLinks}
      <div class="ms-auto d-flex gap-2">
        ${basicInfoLink}
        ${exportLink}
      </div>
    </div>`;
            }
          }],
        });
      });

      function show_itinerary_add_FORM() {
        location.assign('latestitinerary.php?route=add&formtype=basic_info&regen=y');
      }

      function EXCEEDED_THE_ITINERARY_LIMIT() {
        TOAST_NOTIFICATION('warning', 'Itinerary Limit Exceeded. Please contact admin.', 'Warning !!!', '', '', '', '', '', '', '', '', '');
      }

      function clearItinerary() {
        // Assuming itinerary_LIST is a global DataTable instance
        $('#agent_select').prop('selectedIndex', 0).selectize()[0].selectize.clear();
        itinerary_LIST.ajax.url('engine/json/__JSONlastest_itinerary.php').load();
      }

      function get_destination_location_details() {
        var source_location = $("#source_location").val();
        var destination_selectize = $("#destination_location")[0].selectize;
        $.ajax({
          type: "POST",
          url: "engine/ajax/__ajax_get_location_dropdown.php?type=selectize_destination_location",
          data: {
            source_location: source_location
          },
          dataType: 'json',
          success: function(response) {
            // Append the response to the dropdown.
            destination_selectize.clear();
            destination_selectize.clearOptions();
            destination_selectize.addOption(response);
          }
        });
      }
    </script>
<?php
  endif;
endif;
?>