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
			$filter_by_guide_id = "AND `guide_id` = '$id'";
		else:
			$filter_by_guide_id = '';
		endif;
?>
    <div class="card-body pb-0">
                      <!-- Guides List Section -->
                      <ul class="p-0 m-0" style="max-height: 200px; overflow-y: auto;">
                        <?php $selected_query = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name` FROM `dvi_guide_details` WHERE `deleted` = '0' AND `status` = '1'{$filter_by_guide_id} ORDER BY `guide_id` DESC LIMIT 1") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                        $guide_id = $fetch_data['guide_id'];
                        $guide_name = $fetch_data['guide_name'];
                        ?>

                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class='ti ti-compass'></i></span>
                          </div>
                          <div class="d-flex w-100 align-items-center justify-content-between">
                            <div class="me-2">
                              <h6 class="mb-0"><?=$guide_name?></h6>
                              <small class="text-muted">Total Itinerary: <?= getGUIDE_DASHBOARD_DETAILS($guide_id,'','','','total_itinerary');
                              ?> | Total Payout: <?= getGUIDE_DASHBOARD_DETAILS($guide_id,'','','','total_payout');
                              ?></small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-3">
                              <small>Vistings: <?= getGUIDE_DASHBOARD_DETAILS($guide_id,'','','','total_visiting');
                              ?></small>
                              <div class="badge bg-warning rounded-pill">Upcoming: <?= getGUIDE_DASHBOARD_DETAILS($guide_id,'','','','upcoming_visiting');
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
                         <small class="text-muted">Guide Overview</small>
                      </div>

                      <div id="reportBarChart1"></div> <!-- Unique ID for the Guide chart -->
                    </div>
<!-- <script>
"use strict";

var r = document.querySelector("#reportBarChart1"),
    n = {
      chart: { 
        height: 230, 
        type: "bar", 
        stacked: true,  // Enable stacking for grouped data
        toolbar: { show: !1 } 
      },
      plotOptions: {
        bar: {
          barHeight: "60%",
          columnWidth: "60%",
          startingShape: "rounded",
          endingShape: "rounded",
          borderRadius: 4,
        },
      },
      grid: {
        show: !1,
        padding: { top: -20, bottom: 0, left: -10, right: -10 },
      },
      colors: [
          "#836AF9",
          "#a65dcc",
          "#e365cb",
        // config.colors_label.primary,
        // config.colors.primary,
        // config.colors.secondary
      ],
      dataLabels: { enabled: !1 },
      series: [
       {
         name: "Itineraries",
          data: [
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_itinerary') ?>
          ],
        },
        {
          name: "Visitings",
          data: [
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_visiting') ?>
          ],
        },
        {
          name: "Payouts",
          data: [
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_payout') ?>
          ],
        }
      ],
      legend: { show: !0 },
      xaxis: {
        categories: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
        axisBorder: { show: !1 },
        axisTicks: { show: !1 },
        labels: { style: { colors: config.colors.textMuted, fontSize: "13px" } },
      },
      yaxis: { labels: { show: !1 } },
      tooltip: {
        enabled: !0,
        shared: !0, // Ensure multiple series data is displayed on hover
        intersect: !1, // Set this to false
        y: {
          formatter: function (value) {
            return value; // You can format the tooltip value here
          },
        },
      },
      responsive: [
        { breakpoint: 1025, options: { chart: { height: 190 } } },
        { breakpoint: 769, options: { chart: { height: 250 } } },
      ],
    };

    // Log the data to the console
    console.log("Chart Data:", n.series);

null !== r && new ApexCharts(r, n).render();
</script> -->

<script>
"use strict";

var r = document.querySelector("#reportBarChart1"),
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
        "#cc8cda",  // Color for Visitings
        "#e365cb",  // Color for Payouts
      ],
      dataLabels: {
        enabled: false,  // Hide data labels by default
      },
      series: [
        {
          name: "Itineraries",
          data: [ <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_itinerary') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_itinerary') ?>], // Example data for Itineraries
          yAxisIndex: 0, // Use the first Y-axis for Itineraries
        },
        {
          name: "Visitings",
          data: [ <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_visiting') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_visiting') ?>], // Example data for Visitings
          yAxisIndex: 1, // Use the second Y-axis for Visitings
        },
        {
          name: "Payouts",
          data: [<?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '01', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '02', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '03', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '04', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '05', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '06', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '07', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '08', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '09', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '10', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '11', date('Y'), 'total_payout') ?>,
            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '12', date('Y'), 'total_payout') ?>], // Example data for Payouts
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
          title: { text: "Itineraries & Visitings" },
          min: 0,  // Adjust this as necessary
        },
        {
          opposite: true,  // Position the second Y-axis on the opposite side
          show: false,  // Hide the second Y-axis labels
          title: { text: "Visitings" },
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