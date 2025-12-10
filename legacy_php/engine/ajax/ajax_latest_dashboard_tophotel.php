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
    $year = $_POST['year'];

?>
    <div class="card-body">
      <ul class="p-0 m-0">
        <?php

        if ($logged_agent_id != '' &&  $logged_agent_id != '0') :


          $total_hotel_count_query = sqlQUERY_LABEL("SELECT COUNT(`hotel_id`) AS total_count FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE YEAR(`itinerary_route_date`) = '$year'") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
          $total_hotel_count_data = sqlFETCHARRAY_LABEL($total_hotel_count_query);
          $total_hotel_count = $total_hotel_count_data['total_count'];

          $select_hotel_list_query = sqlQUERY_LABEL("
    SELECT 
        h.`hotel_id`, 
        h.`itinerary_plan_id`, 
        COUNT(h.`hotel_id`) AS hotel_count, 
        d.`itinerary_plan_ID`
    FROM 
        `dvi_confirmed_itinerary_plan_hotel_details` h
    INNER JOIN 
        `dvi_confirmed_itinerary_plan_details` d 
    ON 
        h.`itinerary_plan_id` = d.`itinerary_plan_ID`
    WHERE 
        YEAR(h.`itinerary_route_date`) = '$year' 
        AND d.`agent_id` = $logged_agent_id 
        AND YEAR(d.`trip_start_date_and_time`) = '$year' 
        AND YEAR(d.`trip_end_date_and_time`) = '$year'
    GROUP BY 
        h.`hotel_id`
    ORDER BY 
        hotel_count DESC
    LIMIT 5
") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
        else:
          $total_hotel_count_query = sqlQUERY_LABEL("SELECT COUNT(`hotel_id`) AS total_count FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE YEAR(`itinerary_route_date`) = '$year'") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
          $total_hotel_count_data = sqlFETCHARRAY_LABEL($total_hotel_count_query);
          $total_hotel_count = $total_hotel_count_data['total_count'];
          $select_hotel_list_query = sqlQUERY_LABEL("SELECT `hotel_id`, COUNT(`hotel_id`) AS hotel_count FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE YEAR(`itinerary_route_date`) = '$year' GROUP BY `hotel_id` ORDER BY hotel_count DESC LIMIT 5") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
        endif;

        if (sqlNUMOFROW_LABEL($select_hotel_list_query) > 0) :
          while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
            $hotel_id = $fetch_list_data['hotel_id'];
            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
            $hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');
            $hotel_count = $fetch_list_data['hotel_count'];

            $hotel_percentage = round(($hotel_count / $total_hotel_count) * 100);

        ?>
            <li class="mb-3 pb-1 d-flex">
              <div class="d-flex w-50 align-items-center me-3">
                <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                <div>
                  <h6 class="mb-0"><?= $hotel_name; ?></h6>
                  <small class="text-muted"><?= $hotel_place; ?></small>
                </div>
              </div>
              <div class="d-flex flex-grow-1 align-items-center">
                <div class="progress w-100 me-3" style="height:8px;">
                  <div class="progress-bar bg-primary" role="progressbar" style="width:<?= $hotel_percentage; ?>%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
                <span class="text-muted"><?= $hotel_percentage; ?>%</span>
              </div>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <h5 class="text-center text-primary mt-5">No Record Found in <?= $year ?></h5>
        <?php endif;
        ?>
      </ul>
    </div>
<?php
  endif;
endif;
?>