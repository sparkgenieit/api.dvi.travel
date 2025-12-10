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
  if ($_GET['type'] == 'overallpreview') :

    $guide_ID = $_POST['ID'];
    $TYPE = $_POST['TYPE'];


    $select_guide_info = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name`, `guide_dob`, `guide_bloodgroup`, `guide_gender`, `guide_primary_mobile_number`, `guide_alternative_mobile_number`, `guide_email`, `guide_emergency_mobile_number`, `guide_language_proficiency`, `guide_aadhar_number`, `guide_experience`, `guide_country`, `guide_state`, `guide_city`, `guide_gst`, `guide_available_slot`, `guide_bank_name`, `guide_bank_branch_name`, `guide_ifsc_code`, `guide_account_number`, `guide_preffered_for`, `applicable_hotspot_places`, `applicable_activity_places`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_guide_details` WHERE `deleted` = '0'  and `guide_id` = '$guide_ID'") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
    while ($row = sqlFETCHARRAY_LABEL($select_guide_info)) :
      $guide_id = $row["guide_id"];
      $guide_name = $row["guide_name"];
      $guide_dob = date('d-m-Y', strtotime($row['guide_dob']));
      $guide_bloodgroup = $row["guide_bloodgroup"];
      $get_guide_bloodgroup =  getBLOOD_GROUP($guide_bloodgroup, 'label');
      $guide_gender = $row["guide_gender"];
      $guide_primary_mobile_number = $row["guide_primary_mobile_number"];
      $guide_alternative_mobile_number = $row["guide_alternative_mobile_number"];
      $guide_email = $row["guide_email"];
      $guide_emergency_mobile_number = $row["guide_emergency_mobile_number"];
      $guide_language_proficiency = $row["guide_language_proficiency"];
      $guide_aadhar_number = $row["guide_aadhar_number"];
      $guide_experience = $row["guide_experience"];
      $guide_country = $row["guide_country"];
      $guide_city = $row["guide_city"];
      $guide_state = $row["guide_state"];
      $guide_gst = $row["guide_gst"];
      $guide_available_slot = $row["guide_available_slot"];
      $guide_bank_name = $row["guide_bank_name"];
      $guide_bank_branch_name = $row["guide_bank_branch_name"];
      $guide_ifsc_code = $row["guide_ifsc_code"];
      $guide_account_number = $row["guide_account_number"];
      $guide_preffered_for = $row["guide_preffered_for"];
      $applicable_hotspot_places = $row["applicable_hotspot_places"];
      $applicable_activity_places = $row["applicable_activity_places"];
    endwhile;

?>
    <!-- Content -->


    <div class="row mt-3">
      <div class="col-md-12">
        <div class="card mb-4 p-4">
          <div class="row">
            <h4 class="text-primary">Basic Info</h4>
            <div class="col-md-3">
              <label>Guide Name</label>
              <p class="text-light"><?= $guide_name; ?></p>
            </div>
            <div class="col-md-3">
              <label>Date of Birth</label>
              <p class="text-light"><?= $guide_dob; ?></p>
            </div>
            <div class="col-md-3">
              <label>Blood Group</label>
              <p class="text-light"><?= $get_guide_bloodgroup; ?></p>
            </div>
            <div class="col-md-3">
              <label>Gender</label>
              <p class="text-light"><?= getGENDER($guide_gender, 'label'); ?></p>
            </div>
            <div class="col-md-3">
              <label>Primary Mobile Number</label>
              <p class="text-light"><?= $guide_primary_mobile_number; ?></p>
            </div>
            <div class="col-md-3">
              <label>Alternative Mobile Number</label>
              <p class="text-light"><?= $guide_alternative_mobile_number; ?></p>
            </div>
            <div class="col-md-3">
              <label>Email ID</label>
              <p class="text-light"><?= $guide_email; ?></p>
            </div>
            <div class="col-md-3">
              <label>Emergency Mobile Number </label>
              <p class="text-light"><?= $guide_emergency_mobile_number; ?></p>
            </div>
            <div class="col-md-3">
              <label>Aadhar Card Number</label>
              <p class="text-light"><?= $guide_aadhar_number; ?></p>
            </div>
            <div class="col-md-3">
              <label>Language Preference</label>
              <p class="text-light"><?= getGUIDE_LANGUAGE_DETAILS($guide_language_proficiency, 'multilabel'); ?></p>
            </div>
            <div class="col-md-3">
              <label>Experience</label>
              <p class="text-light"><?= $guide_experience; ?></p>
            </div>
            <div class="col-md-3">
              <label>Country</label>
              <p class="text-light"><?= getCOUNTRYLIST($guide_country, 'country_label'); ?></p>
            </div>
            <div class="col-md-3">
              <label>State</label>
              <p class="text-light"><?= getSTATELIST($guide_country, $guide_state, 'state_label'); ?></p>
            </div>
            <div class="col-md-3">
              <label>City</label>
              <p class="text-light"><?= getCITYLIST($guide_state, $guide_city, 'city_label'); ?></p>
            </div>
            <div class="col-md-3">
              <label>
                GST% </label>
              <p class="text-light"><?= $guide_gst; ?>%</p>
            </div>
            <div class="col-md-3">
              <label>Guide Available Slots</label>
              <p class="text-light"><?= getSLOTTYPE($guide_available_slot, 'label'); ?></p>
            </div>
          </div>
          <div class="divider">
            <div class="divider-text text-muted">
              <i class="ti ti-star"></i>
            </div>
          </div>
          <div class="row">
            <h4 class="text-primary">Bank Details</h4>

            <div class="col-md-3">
              <label>Bank Name</label>
              <p class="text-light"><?= $guide_bank_name; ?></p>
            </div>
            <div class="col-md-3">
              <label>Branch Name</label>
              <p class="text-light"><?= $guide_bank_branch_name; ?></p>
            </div>
            <div class="col-md-3">
              <label>IFSC Code</label>
              <p class="text-light"><?= $guide_ifsc_code; ?></p>
            </div>
            <div class="col-md-3">
              <label>Account Number</label>
              <p class="text-light"><?= $guide_account_number; ?></p>
            </div>
            <div class="col-md-3">
              <label>Confirm Account Number</label>
              <p class="text-light"><?= $guide_account_number; ?></p>
            </div>
          </div>
          <div class="divider">
            <div class="divider-text text-muted">
              <i class="ti ti-star"></i>
            </div>
          </div>
          <div class="row">
            <h4 class="text-primary">Guide Prefered For</h4>
            <div class="col-md-2 mt-2">
              <input class="form-check-input me-1" type="checkbox" value="" id="disabledCheck2" disabled checked>
              <label><?= getGSTPREFERED($guide_preffered_for, 'label'); ?></label>
            </div>
            <div class="col-md-6">
              <?php
              if ($guide_preffered_for == '1') :  ?>
                <label><?= getGSTPREFERED($guide_preffered_for, 'label'); ?></label>
                <p class="text-light"><?= getHOTSPOTDETAILS($applicable_hotspot_places, 'multilabel'); ?></p>
              <?php
              endif;
              ?>

              <?php
              if ($guide_preffered_for == '2') :  ?>
                <label><?= getGSTPREFERED($guide_preffered_for, 'label'); ?></label>
                <p class="text-light"><?= getACTIVITYDETAILS($applicable_activity_places, 'multilabel'); ?></p>
              <?php
              endif;
              ?>

              <?php
              if ($guide_preffered_for == '3') :  ?>
                <h6 class="alert alert-primary">From the beginning to the end of each day, the itinerary and all the hotspots serve as a guide for the entire journey.</h6>
              <?php
              endif;
              ?>
            </div>
          </div>
          <div class="divider">
            <div class="divider-text text-muted">
              <i class="ti ti-star"></i>
            </div>
          </div>
          <div class="row">
            <h4 class="text-primary">Feedback &amp; Review</h4>
            <div class="card-datatable dataTable_select text-nowrap">
              <!-- <h5 class="text-primary">List of Reviews</h5> -->
              <div class="table-responsive">
                <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                  <thead class="table-head">
                    <tr>
                      <th>S.no</th>
                      <th>Rating</th>
                      <th colspan="5">Description</th>
                      <th>Created On</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $select_list = sqlQUERY_LABEL("SELECT `guide_review_id`, `guide_id`, `guide_rating`, `guide_description`, `createdon` FROM `dvi_guide_review_details` WHERE `deleted` = '0' AND `guide_id` = '$guide_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $select_review_count = sqlNUMOFROW_LABEL($select_list);
                    if ($select_review_count > 0) :
                      while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                        $guide_counter++;
                        $guide_review_id = $fetch_data['guide_review_id'];
                        $guide_rating = $fetch_data['guide_rating'];
                        $guide_description = $fetch_data['guide_description'];
                        $createdon = date('d-m-Y h:i A', strtotime($fetch_data['createdon']));
                    ?>
                        <tr>
                          <td><?= $guide_counter; ?></td>
                          <td><?= getSTARRATINGCOUNT($guide_rating, 'label'); ?></td>
                          <td colspan="5"><?= $guide_description; ?></td>
                          <td><?= $createdon; ?></td>
                        </tr>
                      <?php
                      endwhile;
                    else :
                      ?>
                      <tr>
                        <td colspan="5" class="text-center">No Special Time Found !!!</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="text-end mt-5">
            <a class="btn btn-light" href="guide.php" data-bs-dismiss=" modal">Back</a>
          </div>
        </div>
      </div>
    </div>

<?php
  endif;
endif;
?>