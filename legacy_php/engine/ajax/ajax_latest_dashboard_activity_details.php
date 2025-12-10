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
    $id = $_POST['id'];

 if($id!='' && $id!=0):
			$filter_by_activity_id = "AND `activity_id` = '$id'";
		else:
			$filter_by_activity_id = '';
		endif;
?>
    <div class="card-body pb-0">
                      <!-- Guides List Section -->
                      
                      <ul class="p-0 m-0" style="max-height: 200px; overflow-y: auto;">
                        <?php $selected_query = sqlQUERY_LABEL("SELECT `activity_id`, `activity_title`,`activity_id` FROM `dvi_activity` WHERE `deleted` = '0' AND `status` = '1'{$filter_by_activity_id} ORDER BY `activity_id` DESC LIMIT 1") or die("#getACTIVITYDETAILS:" . sqlERROR_LABEL());while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                        $activity_id = $fetch_data['activity_id'];  
                         $activity_id = $fetch_data['activity_id'];
                        $activity_title = $fetch_data['activity_title'];
                        ?>

                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class='ti ti-building'></i></span>
                          </div>
                          <div class="d-flex w-100 align-items-center justify-content-between">
                            <div class="me-2">
                              <h6 class="mb-0"><?=$activity_title?></h6>
                              <small class="text-muted">Total Itinerary: <?= getACTIVITY_DASHBOARD_DETAILS($activity_id,'','','','total_itinerary');
                              ?> | Total Payout: <?= getACTIVITY_DASHBOARD_DETAILS($activity_id,'','','','total_payout');
                              ?></small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-3">
                              <small>Bookings: <?= getACTIVITY_DASHBOARD_DETAILS($activity_id,'','','','total_booking');
                              ?></small>
                              <div class="badge bg-warning rounded-pill">Upcoming: <?= getACTIVITY_DASHBOARD_DETAILS($activity_id,'','','','upcoming_booking');
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
                         <small class="text-muted">Activity Overview</small>
                      </div>

                      <div id="reportBarChart5"></div> <!-- Unique ID for the Guide chart -->
                    </div>
                    <!-- <script>
"use strict";

var r = document.querySelector("#reportBarChart5"),
    n = {
      chart: { height: 230, type: "bar", toolbar: { show: !1 } },
      plotOptions: {
        bar: {
          barHeight: "60%",
          columnWidth: "60%",
          startingShape: "rounded",
          endingShape: "rounded",
          borderRadius: 4,
          distributed: !0,
        },
      },
      grid: {
        show: !1,
        padding: { top: -20, bottom: 0, left: -10, right: -10 },
      },
      colors: [
        config.colors_label.primary,
        config.colors.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors.primary,
        config.colors_label.primary,
        config.colors.primary
      ],
      dataLabels: { enabled: !1 },
      series: [{
        data: [
             <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '01', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '02', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '03', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '04', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '05', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '06', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '07', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '08', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '09', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '10', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '11', date('Y'), 'month_wise_report') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '12', date('Y'), 'month_wise_report') ?>
        ]
      }],
      legend: { show: !1 },
      xaxis: {
        categories: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
        axisBorder: { show: !1 },
        axisTicks: { show: !1 },
        labels: { style: { colors: config.colors.textMuted, fontSize: "13px" } },
      },
      yaxis: { labels: { show: !1 } },
      responsive: [
        { breakpoint: 1025, options: { chart: { height: 190 } } },
        { breakpoint: 769, options: { chart: { height: 250 } } },
      ],
    };

    // Log the data to the console
    console.log("Chart Data:", n.series[0].data);

null !== r && new ApexCharts(r, n).render();
</script> -->

<script>
"use strict";

var r = document.querySelector("#reportBarChart5"),
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
          data: [<?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '01', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '02', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '03', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '04', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '05', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '06', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '07', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '08', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '09', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '10', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '11', date('Y'), 'total_itinerary') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '12', date('Y'), 'total_itinerary') ?>], // Example data for Itineraries
          yAxisIndex: 0, // Use the first Y-axis for Itineraries
        },
        {
          name: "Bookings",
          data: [ <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '01', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '02', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '03', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '04', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '05', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '06', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '07', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '08', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '09', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '10', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '11', date('Y'), 'total_booking') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '12', date('Y'), 'total_booking') ?>], // Example data for Bookings
          yAxisIndex: 1, // Use the second Y-axis for Bookings
        },
        {
          name: "Payouts",
          data: [<?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '01', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '02', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '03', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '04', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '05', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '06', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '07', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '08', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '09', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '10', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '11', date('Y'), 'total_payout') ?>,
            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '12', date('Y'), 'total_payout') ?>], // Example data for Payouts
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