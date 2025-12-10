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

  if ($_GET['type'] == 'guide_preview') :

    $guide_ID = $_POST['ID'];
    $TYPE = $_POST['TYPE'];


    if ($guide_ID != '' && $guide_ID != 0) :
      $basic_info_url = 'guide.php?route=' . $TYPE . '&formtype=basic_info&id=' . $guide_ID;
      $guide_pricebook_url = 'guide.php?route=' . $TYPE . '&formtype=guide_pricebook&id=' . $guide_ID;
      $guide_feedback_url = 'guide.php?route=' . $TYPE . '&formtype=guide_feedback&id=' . $guide_ID;
      $preview_url = 'guide.php?route=' . $TYPE . '&formtype=guide_preview&id=' . $guide_ID;
    else :
      $basic_info_url = 'javascript:void:;';
      $guide_pricebook_url = 'javascript:void:;';
      $guide_feedback_url = 'javascript:void:;';
      $preview_url = 'javascript:void:;';
    endif;

    $select_guide_info = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name`, `guide_dob`, `guide_bloodgroup`, `guide_gender`, `guide_primary_mobile_number`, `guide_alternative_mobile_number`, `guide_email`, `guide_emergency_mobile_number`, `guide_language_proficiency`, `guide_aadhar_number`, `guide_experience`, `guide_country`, `guide_state`, `guide_city`, `guide_gst`, `guide_available_slot`, `guide_bank_name`, `guide_bank_branch_name`, `guide_ifsc_code`, `guide_account_number`, `guide_preffered_for`, `applicable_hotspot_places`, `applicable_activity_places`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_guide_details` WHERE `deleted` = '0'  and `guide_id` = '$guide_ID'") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
    while ($row = sqlFETCHARRAY_LABEL($select_guide_info)) :
      $guide_id = $row["guide_id"];
      $guide_name = $row["guide_name"];
      if ($row['guide_dob'] != "") :
        $guide_dob = date('d M Y', strtotime($row['guide_dob']));
      else :
        $guide_dob = "--";
      endif;
      $guide_bloodgroup = $row["guide_bloodgroup"];
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

    <div class="row">
      <div class="col-12">
        <div id="wizard-validation" class="bs-stepper mt-2">
          <div class="bs-stepper-header border-0 justify-content-center py-2">
            <div class="step" data-target="#account-details-validation">
              <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle disble-stepper-title">1</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">Guide Basic Info</h5>
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a type="button" href="<?= $guide_pricebook_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle disble-stepper-title ">2</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">Pricebook</h5>
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a type="button" href="<?= $guide_feedback_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle  disble-stepper-title">3</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">FeedBack & Review</h5>
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a type="button" href="<?= $preview_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle  active-stepper">4</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title ">Guide Preview</h5>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

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
              <p class="text-light"><?= getBLOOD_GROUP($guide_bloodgroup, 'label'); ?></p>
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

            <div class="text-center mt-5">
              <a class="btn btn-primary float-end ms-2" href="guide.php" data-bs-dismiss=" modal">Confirm</a>
              <a class="btn btn-secondary float-start" href="javascript:;" data-bs-dismiss=" modal">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / Content -->

    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
    <!-- Page JS -->
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script>
      $(document).ready(function() {

        $('#year').datepicker({
          autoclose: true,
          minViewMode: 2,
          format: 'yyyy'
        });

        flatpickr(".show_datepicker", {
          dateFormat: "d-m-Y"
        });

        $('#month').on('change', function() {

          var month = $(this).val();
          var year = $('#year').val();

          var startDate, endDate;

          var daysInMonth = new Date(year, month, 0).getDate();
          startDate = `01-${month}-${year}`;
          endDate = `${daysInMonth}-${month}-${year}`;

          flatpickr("#selectstartdate", {
            dateFormat: "d-m-Y",
            defaultDate: startDate,
            minDate: startDate,
            maxDate: endDate
          });

          flatpickr("#selectenddate", {
            dateFormat: "d-m-Y",
            defaultDate: endDate,
            minDate: startDate,
            maxDate: endDate
          });

        });

        //AJAX FORM SUBMIT
        $("#form_guide_pricebook").submit(function(event) {
          var form = $('#form_guide_pricebook')[0];
          var data = new FormData(form);
          $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
          $.ajax({
            type: "post",
            url: 'engine/ajax/__ajax_manage_guide.php?type=guide_pricebook',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 80000,
            dataType: 'json',
            encode: true,
          }).done(function(response) {

            if (!response.success) {
              //NOT SUCCESS RESPONSE
              if (response.errors.price_type_required) {
                TOAST_NOTIFICATION('error', 'Price For is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
              } else if (response.errors.year_required) {
                TOAST_NOTIFICATION('error', 'Year is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
              } else if (response.errors.month_required) {
                TOAST_NOTIFICATION('error', 'Month is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
              } else if (response.errors.selectstartdate_required) {
                TOAST_NOTIFICATION('error', 'Start date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
              } else if (response.errors.selectenddate_required) {
                TOAST_NOTIFICATION('error', 'End Date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
              }
            } else {
              //SUCCESS RESPOSNE

              if (response.i_result == true) {
                //TOAST_NOTIFICATION('success', 'Activity Basic Details Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                // window.location.href = response.redirect_URL;
                show_ACTIVITY_PRICE_BOOK(response.activity_id);
                location.reload();
              } else if (response.u_result == true) {
                //RESULT SUCCESS
                //TOAST_NOTIFICATION('success', 'Activity Basic Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                show_ACTIVITY_PRICE_BOOK(response.activity_id);
                location.reload();
              } else if (response.i_result == false) {
                //RESULT FAILED
                TOAST_NOTIFICATION('error', 'Unable to Add Activity  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
              } else if (response.u_result == false) {
                //RESULT FAILED
                TOAST_NOTIFICATION('error', 'Unable to Update Activity  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
              }

            }
            if (response == "OK") {
              return true;
            } else {
              return false;
            }
          });
          event.preventDefault();
        });

      });
    </script>
<?php
  endif;
endif;
?>