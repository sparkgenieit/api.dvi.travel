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
    <div class="card-body pt-0">
      <div class="accordion accordion-flush accordion-arrow-left" id="ecommerceBillingAccordionPayment">
        <?php
        $select_branches = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$logged_vendor_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_branches)) :
          $vendor_id = $fetch_list_data['vendor_id'];
          $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
          $vendor_branch_name = $fetch_list_data['vendor_branch_name'];
          $total_vehicle_count = getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, $vendor_branch_id, '', 'total_vehicle_count');
          $firstletters = substr($vendor_branch_name, 0, 1);
        ?>
          <div class="accordion-item border-bottom">
            <div class="accordion-header d-flex justify-content-between align-items-center flex-wrap flex-sm-nowrap" id="#branch_<?= $vendor_branch_id ?>">
              <a class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#vehiclesofbranch_<?= $vendor_branch_id ?>" aria-expanded="false" aria-controls="#branch_<?= $vendor_branch_id ?>" role="button">
                <span class="accordion-button-information d-flex align-items-center justify-content-between gap-3 w-100">
                  <div class="d-flex align-items-center">
                    <span class="accordion-button-image">
                      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="me-2">
                        <g>
                          <g data-name="13-car">
                            <path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
                            <path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
                          </g>
                        </g>
                      </svg>
                    </span>
                    <span class="d-flex flex-column">
                      <span class="h6 mb-0"><?= $vendor_branch_name ?></span>
                    </span>
                  </div>
                  <div class="mb-0 text-muted"><?= $total_vehicle_count ?> vehicles</div>
                </span>
              </a>
            </div>
            <div id="vehiclesofbranch_<?= $vendor_branch_id ?>" class="accordion-collapse collapse" data-bs-parent="#ecommerceBillingAccordionPayment">
              <div class="accordion-body d-flex align-items-baseline flex-wrap flex-xl-nowrap flex-sm-nowrap flex-md-wrap">
                <table class="table table-hover mt-3" id="agent_LIST">
                  <thead>
                    <tr>
                      <th scope="col">S.No</th>
                      <th>Vehicle Number</th>
                      <th>Vehicle Type</th>
                      <th>FC Expiry Date</th>
                      <th>Status</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $select_VEHICLELIST_query = sqlQUERY_LABEL("SELECT `vendor_id`,`vehicle_id`, `vehicle_type_id`, `registration_number`, `vehicle_fc_expiry_date`, `insurance_end_date`,`status` FROM `dvi_vehicle` WHERE `deleted` = '0' AND `vendor_branch_id`='$vendor_branch_id' ORDER BY `vehicle_id`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

                    if (sqlNUMOFROW_LABEL($select_VEHICLELIST_query) > 0):
                      $counter = 0;
                      while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_VEHICLELIST_query)) :
                        $counter++;
                        $vendor_id = $fetch_list_data['vendor_id'];
                        $vehicle_id = $fetch_list_data['vehicle_id'];
                        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                        $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
                        $registration_number = $fetch_list_data['registration_number'];
                        $vehicle_fc_expiry_date = date('d/m/Y', strtotime($fetch_list_data['vehicle_fc_expiry_date']));
                        $status = $fetch_list_data['status'];

                        $vehicle_fc_expiry_date_status = '0';
                        $insurance_end_date_status = '0';
                        if ((date('Y-m-d', strtotime($fetch_list_data['vehicle_fc_expiry_date'])) < date("Y-m-d")) && $status == 0) :
                          $vehicle_fc_expiry_date_status = '1';
                          $status_label = 'Vehicle FC Date Expired';
                        elseif ((date('Y-m-d', strtotime($fetch_list_data['insurance_end_date'])) < date("Y-m-d")) && $status == 0) :
                          $insurance_end_date_status = '1';
                          $status_label = 'Insurance End Date Expired';
                        else :
                          $status_label = ($status == '1') ? 'Active' : 'In-Active';
                        endif;
                    ?>
                        <tr>
                          <td><?= $counter ?>.</td>
                          <td><?= $registration_number ?></td>
                          <td><?= $vehicle_type_title ?></td>
                          <td><?= $vehicle_fc_expiry_date ?></td>
                          <td><?= $status_label ?></td>
                        </tr>
                      <?php endwhile;
                    else:
                      ?>
                      <tr>
                        <td colspan="5" class="text-center">No Vehicle found</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
<?php
  endif;
endif;
?>