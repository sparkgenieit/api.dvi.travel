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
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

  if ($_GET['type'] == 'show_form') :
    $id = $_POST['id'];

 if($id!='' && $id!=0):
			$filter_by_vendor_id = "AND `vendor_id` = '$id'";
		else:
			$filter_by_vendor_id = '';
		endif;
?>
    <div class="card-body pb-0">
                      <!-- Guides List Section -->
                      
                      <ul class="p-0 m-0" style="max-height: 200px; overflow-y: auto;">
                        <?php $selected_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name` FROM `dvi_vendor_details` WHERE `deleted` = '0' AND `status` = '1'{$filter_by_vendor_id} ORDER BY `vendor_id` DESC LIMIT 1") or die("#getVENDORDETAILS:" . sqlERROR_LABEL());while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                        $vendor_id = $fetch_data['vendor_id'];
                        $vendor_name = $fetch_data['vendor_name'];
                        ?>

                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class='ti ti-building'></i></span>
                          </div>
                          <div class="d-flex w-100 align-items-center justify-content-between">
                            <div class="me-2">
                              <h6 class="mb-0"><?=$vendor_name?></h6> 
                              <small class="text-muted">Total Itinerary: <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id,'','','','total_itinerary');
                              ?> | Total Payout: <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id,'','','','total_payout');
                              ?></small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-3">
                              <small>Bookings: <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id,'','','','total_booking');
                              ?></small>
                              <div class="badge bg-warning rounded-pill">Upcoming: <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id,'','','','upcoming_booking');
                              ?></div>
                            </div>
                          </div>
                        </li>
                        <?php endwhile;?>

                        <!-- Add more guides here -->
                      </ul>
                      <hr>
                      <!-- Key Data Points Section -->
                      <div class="card-title mb-0 mt-3">
                         <h5 class="m-0 me-2">Overall Reports</h5>
                         <small class="text-muted">Vendor Overview</small>
                      </div>

                      <div id="reportBarChart3"></div> <!-- Unique ID for the Guide chart -->
                    </div>
<!-- <script>
"use strict";

var r = document.querySelector("#reportBarChart3"),
    n = {
      chart: {
        height: 230, 
        type: "bar", 
        toolbar: { show: false },
      },
      plotOptions: {
        bar: {
          barHeight: "60%",  // Adjust bar height for visibility
          columnWidth: "25%",  // Set the width of each bar
          startingShape: "rounded",
          endingShape: "rounded",
          borderRadius: 4,
          distributed: false,  // Bars will be side-by-side, not distributed
        },
      },
      grid: {
        show: false,
        padding: { top: -20, bottom: 0, left: -10, right: -10 },
      },
      colors: [
        "#836AF9",  // Color for Itineraries
        "#a65dcc",  // Color for Bookings
        "#e365cb",  // Color for Payouts
      ],
      dataLabels: {
        enabled: false,  // Hide data labels by default
      },
      series: [
        {
          name: "Itineraries",
          data: [
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_itinerary') ?>
          ],
         
        },
        {
          name: "Bookings",
          data: [
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_booking') ?>
          ],
        },
        {
          name: "Payouts",
           data: [
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_payout') ?>
          ],
         
        }
      ],
      legend: { show: true },
      xaxis: {
        categories: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"], // Months
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: { style: { colors: config.colors.textMuted, fontSize: "13px" } },
      },
      yaxis: { labels: { show: true } },  // Show Y-axis labels for clarity
      tooltip: {
        enabled: true,
        shared: true,  // Show data for all series on hover
        intersect: false,  // Show tooltip when hovering over any part of the bar
        y: {
          formatter: function(value, { seriesIndex, dataPointIndex, w }) {
            let seriesName = w.config.series[seriesIndex].name;
            return `${seriesName}: ${value}`; // Display series name and value in the tooltip
          },
        },
      },
      responsive: [
        { breakpoint: 1025, options: { chart: { height: 190 } } },
        { breakpoint: 769, options: { chart: { height: 250 } } },
      ],
    };

null !== r && new ApexCharts(r, n).render();
</script> -->


<script>
"use strict";

var r = document.querySelector("#reportBarChart3"),
    n = {
      chart: {
        height: 230, 
        type: "bar", 
        toolbar: { show: false },
      },
      plotOptions: {
        bar: {
          barHeight: "60%",  // Adjust bar height for visibility
          columnWidth: "50%",  // Set the width of each bar
          startingShape: "rounded",
          endingShape: "rounded",
          borderRadius: 4,
          distributed: false,  // Bars will be side-by-side, not distributed
        },
      },
      grid: {
        show: false,
        padding: { top: -20, bottom: 0, left: -10, right: -10 },
      },
      colors: [
        "#836AF9",  // Color for Itineraries
        "#cc8cda",  // Color for Bookings
        "#e365cb",  // Color for Payouts
      ],
      dataLabels: {
        enabled: false,  // Hide data labels by default
      },
      series: [
        {
          name: "Itineraries",
          data: [<?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_itinerary') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_itinerary') ?>], // Example data for Itineraries
          yAxisIndex: 0, // Use the first Y-axis for Itineraries
        },
        {
          name: "Bookings",
          data: [ <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_booking') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_booking') ?>], // Example data for Bookings
          yAxisIndex: 1, // Use the second Y-axis for Bookings
        },
        {
          name: "Payouts",
          data: [<?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '01', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '02', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '03', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '04', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '05', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '06', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '07', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '08', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '09', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '10', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '11', date('Y'), 'total_payout') ?>,
            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '12', date('Y'), 'total_payout') ?>], // Example data for Payouts
          yAxisIndex: 2, // Use the third Y-axis for Payouts
        }
      ],
      legend: { show: true },
      xaxis: {
        categories: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"], // Months
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: { style: { colors: config.colors.textMuted, fontSize: "13px" } },
      },
      yaxis: [
        {
          show: false,  // Hide the first Y-axis labels
          title: { text: "Itineraries & Bookings" },
          min: 0,  // Adjust this as necessary
        },
        {
          opposite: true,  // Position the second Y-axis on the opposite side
          show: false,  // Hide the second Y-axis labels
          title: { text: "Bookings" },
          min: 0,
        },
        {
          show: false,  // Hide the third Y-axis labels
          title: { text: "Payouts" },
          min: 0,
        }
      ],
      tooltip: {
        enabled: true,
        shared: true,  // Show data for all series on hover
        intersect: false,  // Show tooltip when hovering over any part of the bar
        y: {
          formatter: function(value, { seriesIndex, dataPointIndex, w }) {
            return `${value}`; // Display series name and value in the tooltip
          },
        },
      },
      responsive: [
        { breakpoint: 1025, options: { chart: { height: 190 } } },
        { breakpoint: 769, options: { chart: { height: 250 } } },
      ],
    };

null !== r && new ApexCharts(r, n).render();
</script>
<?php
  endif;
endif;
?>