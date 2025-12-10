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
    <div class="card-body p-2 pt-0 logistics-fleet-sidebar-body pb-4">
      <!-- Menu Accordion -->
      <div class="accordion" id="fleet" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar">
        <?php

        $date = $_POST['date'];
        if (empty($date)) {
          $formattedDate = date('Y-m-d');
        } else {
          $formattedDate = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        }

        if ($logged_agent_id != '' &&  $logged_agent_id != '0'):
          $filter_agent_id = "AND cipd.agent_id = $logged_agent_id";
        endif;

        if ($logged_vendor_id != '' &&  $logged_vendor_id != '0'):
          $join_vendor_query = "LEFT JOIN dvi_confirmed_itinerary_plan_vendor_vehicle_details cipvvd ON cir.itinerary_plan_ID = cipvvd.itinerary_plan_id AND cir.itinerary_route_date = cipvvd.itinerary_route_date ";
          $filter_vendor_id = " AND cipvvd.vendor_id = '$logged_vendor_id'";
        endif;

        $select_dailymoment_query = sqlQUERY_LABEL("SELECT 
                              cipd.confirmed_itinerary_plan_ID, 
                              cipd.itinerary_plan_ID, 
                              cipd.agent_id, 
                              cipd.staff_id, 
                              cipd.location_id, 
                              cipd.arrival_location, 
                              cipd.departure_location, 
                              cipd.itinerary_quote_ID, 
                              cipd.trip_start_date_and_time, 
                              cipd.trip_end_date_and_time, 
                              cir.itinerary_route_ID, 
                              cir.itinerary_route_date, 
                              cir.location_name, 
                              cir.next_visiting_location
                          FROM 
                              dvi_confirmed_itinerary_plan_details cipd
                          LEFT JOIN 
                              dvi_confirmed_itinerary_route_details cir 
                              ON cipd.itinerary_plan_ID = cir.itinerary_plan_ID
                              {$join_vendor_query}
                          WHERE 
                              cipd.deleted = '0' 
                              AND cipd.status = '1'
                              AND cir.deleted = '0' 
                              AND cir.status = '1'
                              {$filter_agent_id}
                              {$filter_vendor_id}
                              AND cir.itinerary_route_date = '$formattedDate' ORDER BY `confirmed_itinerary_plan_ID` DESC LIMIT 5") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_dailymoment_query) > 0) :
          while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_dailymoment_query)) :
            $count++;
            $confirmed_itinerary_plan_ID = $fetch_list_data['confirmed_itinerary_plan_ID'];
            $arrival_location = $fetch_list_data['location_name'];
            $departure_location = $fetch_list_data['next_visiting_location'];
            $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
            $itinerary_route_date = $fetch_list_data['itinerary_route_date'];
            $format_itinerary_route_date = date('d-m-Y', strtotime($fetch_list_data['itinerary_route_date']));
            $trip_start_date_time = $fetch_list_data['trip_start_date_and_time']; // Full date and time
            $trip_start_date = date('Y-m-d', strtotime($trip_start_date_time));

            $trip_end_date_time = $fetch_list_data['trip_end_date_and_time']; // Full date and time
            $trip_end_date = date('Y-m-d', strtotime($trip_end_date_time));


            if ($itinerary_route_date == $trip_start_date):
              $trip_type = 'Arrival';
            elseif ($itinerary_route_date == $trip_end_date):
              $trip_type = 'Departure';
              $trip_type_class = "text-success";
            else:
              $trip_type = 'Ongoing';
            endif;

        ?>
            <!-- Fleet 1 -->
            <div class="accordion-item border-0 mb-0" id="fl-1">
              <div class="accordion-header" id="fleetOne">
                <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#feet<?= $count; ?>" aria-expanded="true" aria-controls="feet<?= $count; ?>">

                  <div class="d-flex align-items-center">
                    <div class="avatar-wrapper">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                      </div>
                    </div>
                    <span class="d-flex flex-column">
                      <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank"><?= $itinerary_quote_ID; ?></a></span>
                      <span class="text-muted <?= $trip_type_class; ?>"><?= $trip_type; ?></span>
                    </span>
                  </div>
                </div>
              </div>
              <div id="feet<?= $count; ?>" class="accordion-collapse collapse" data-bs-parent="#fleet">
                <div class="accordion-body pt-0 pb-0">
                  <ul class="timeline ps-3 mt-1 mb-0">
                    <li class="timeline-item ms-1 ps-4 border-left-dashed">
                      <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                        <i class='ti ti-circle-check'></i>
                      </span>
                      <div class="timeline-event ps-0 pb-0">
                        <div class="timeline-header">
                          <small class="text-success text-uppercase fw-medium"><?= $arrival_location; ?></small>
                        </div>
                        <p class="text-muted mb-0"><?= $format_itinerary_route_date; ?></p>
                      </div>
                    </li>
                    <li class="timeline-item ms-1 ps-4 border-transparent">
                      <span class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                        <i class='ti ti-map-pin mt-1'></i>
                      </span>
                      <div class="timeline-event ps-0 pb-0">
                        <div class="timeline-header">
                          <small class="text-uppercase fw-medium"><?= $departure_location; ?></small>
                        </div>
                        <p class="text-muted mb-0"><?= $format_itinerary_route_date; ?></p>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <h5 class="text-center text-primary mt-5">No Record Found in <?= date('d-m-Y', strtotime($formattedDate)); ?></h5>
        <?php endif;
        ?>
      </div>
      <?php if ($logged_user_level == 1): ?>
        <div class="text-center">
          <a href="dailymoment_tracker.php" class="">View All<span class="ms-2"><i class="ti ti-chevron-right"></i></span></a>
        </div>
      <?php endif; ?>
    </div>
<?php
  endif;
endif;
?>