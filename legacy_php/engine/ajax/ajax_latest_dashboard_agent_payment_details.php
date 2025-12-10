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

    if ($id != '' && $id != 0):
      $agent_id = $id;
    else:
      $agent_id = '';
    endif;
?>
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-sm-4 col-md-12 col-lg-12">
          <div class="d-flex  align-items-center justify-content-between mb-lg-4 pt-1">

           <div class="mt-lg-4 mt-lg-2">
                              <h3 class="mb-0"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_payout_of_agent')), 2); ?></h3>
                              <p class="mb-0">Total Payout</p>
                            </div>
  <?php if ($agent_id != '' && $agent_id != 0):?>
               <div>   <h3 class="mb-0"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'get_agent_profit')), 2); ?></h3>
            <p class="mb-0">Agent Profit</p>
              </div>
              <?php endif;?>
          </div>
          </div>
          <div class="col-12 col-sm-4 col-md-12 col-lg-4">
          <ul class="p-0 m-0">
             <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
              <div class="badge rounded bg-label-primary p-1"><i class="ti ti-receipt  ti-sm"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Total Billed</h6>
                <small class="text-muted"> <?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'amt_billed_of_agent')), 2); ?></small>
              </div>
            </li>
            <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Total Received</h6>
                <small class="text-muted"> <?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'amt_received_from_agent')), 2); ?></small>
              </div>
            </li>
            <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
              <div class="badge rounded bg-label-success p-1"><i class="ti ti-clock ti-sm"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Total Receivable</h6>
                <small class="text-muted"> <?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'amt_receivable_from_agent')), 2); ?></small>
              </div>
            </li>
          
            <li class="d-flex gap-3 align-items-center pb-1">
              <div class="badge rounded bg-label-warning p-1"><i class="ti ti-help-square-rounded ti-sm"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Total Payable</h6>
                <small class="text-muted"> <?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'amt_payable_of_agent')), 2); ?></small>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-12 col-sm-8 col-md-12 col-lg-8 d-flex align-items-center">
          <div id="agentTracker"></div>
        </div>
      </div>
    </div>
    <script>
      "use strict";

      (function() {
        // Theme-based configurations
        let e, t, a, r, o;
        if (isDarkStyle) {
          e = config.colors_dark.cardColor;
          a = config.colors_dark.textMuted;
          t = config.colors_dark.headingColor;
          r = "dark";
          o = "#5E6692";
        } else {
          e = config.colors.cardColor;
          a = config.colors.textMuted;
          t = config.colors.headingColor;
          r = "";
          o = "#817D8D";
        }

        // Support Tracker Chart
        var agentTrackerElement = document.querySelector("#agentTracker");
        if (agentTrackerElement) {
          var agentTrackerOptions = {
            series: ['<?= getAC_MNRG_DASHBOARD_DETAILS($agent_id, '', 'agent_payout_percentage') ?>'],
            labels: ["Completed Payouts"],
            chart: {
              height: 360,
              type: "radialBar"
            },
            plotOptions: {
              radialBar: {
                offsetY: 10,
                startAngle: -140,
                endAngle: 130,
                hollow: {
                  size: "65%"
                },
                track: {
                  background: e,
                  strokeWidth: "100%"
                },
                dataLabels: {
                  name: {
                    offsetY: -20,
                    color: a,
                    fontSize: "13px",
                    fontWeight: "400"
                  },
                  value: {
                    offsetY: 10,
                    color: t,
                    fontSize: "38px",
                    fontWeight: "500"
                  }
                }
              }
            },
            colors: [config.colors.primary],
            fill: {
              type: "gradient",
              gradient: {
                shade: "dark",
                shadeIntensity: 0.5,
                gradientToColors: [config.colors.primary],
                opacityFrom: 1,
                opacityTo: 0.6
              }
            },
            stroke: {
              dashArray: 10
            },
            grid: {
              padding: {
                top: -20,
                bottom: 5
              }
            },
            states: {
              hover: {
                filter: {
                  type: "none"
                }
              },
              active: {
                filter: {
                  type: "none"
                }
              }
            },
            responsive: [{
                breakpoint: 1025,
                options: {
                  chart: {
                    height: 330
                  }
                }
              },
              {
                breakpoint: 769,
                options: {
                  chart: {
                    height: 280
                  }
                }
              }
            ]
          };
          new ApexCharts(agentTrackerElement, agentTrackerOptions).render();
        }

      })();
    </script>
<?php
  endif;
endif;
?>