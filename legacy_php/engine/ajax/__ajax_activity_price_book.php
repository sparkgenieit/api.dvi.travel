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

    $ACTIVITY_ID = $_POST['ID'];
    $TYPE = $_POST['TYPE'];
    $HOTSPOT_ID = getACTIVITYDETAILS($ACTIVITY_ID, 'get_activity_hotspot_id');

    if ($ACTIVITY_ID) :
      $activity_basic_info_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_basic_info&id=' . $ACTIVITY_ID;
      $activity_pricebook_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_price_book&id=' . $ACTIVITY_ID;
      $activity_feedback_review_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_feedback_review&id=' . $ACTIVITY_ID;
      $activity_preview_url = 'activitydetails.php?route=' . $TYPE . '&formtype=preview&id=' . $ACTIVITY_ID;
    else :
      $activity_basic_info_url = 'javascript:void:;';
      $activity_pricebook_url = 'javascript:void:;';
      $activity_feedback_review_url = 'javascript:void:;';
      $activity_preview_url = 'javascript:void:;';
    endif;
?>
    <!-- Content -->
    <div class="row">
      <div class="col-12">
        <div id="wizard-validation" class="bs-stepper mt-2">
          <div class="bs-stepper-header border-0 justify-content-center py-2">
            <div class="step" data-target="#account-details-validation">
              <a href="<?= $activity_basic_info_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle disble-stepper-title">1</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">Activity Basic Details</h5>
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a href="<?= $activity_pricebook_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle active-stepper">2</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title">Price Book</h5>
                  <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a href="<?= $activity_feedback_review_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle  disble-stepper-title">3</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">FeedBack & Review</h5>

                  <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                </span>
              </a>
            </div>
            <div class="line">
              <i class="ti ti-chevron-right"></i>
            </div>
            <div class="step" data-target="#account-details-validation">
              <a href="<?= $activity_preview_url; ?>" class="step-trigger">
                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>


    <form class="" id="form_activity_pricebook" method="post" data-parsley-validate>
      <input type="hidden" name="hidden_activity_ID" value="<?= $ACTIVITY_ID ?>">
      <input type="hidden" name="hotspot" value="<?= $HOTSPOT_ID ?>">
      <div class="row mt-3">
        <div class="col-12">
          <div class="card app-calendar-wrapper p-4">
            <div class="row py-2">
              <div class="col-md-8 my-auto">
                <div class="pb-3 d-flex justify-content-between">
                  <h5 class="mb-0">Activity Cost Details</h5>
                </div>
              </div>
              <div class="col-md-4">
                <div class="row">
                  <div class="col-md-8">
                    <div class="input-group">
                      <input type="text" class="form-control show_datepicker" placeholder="Start Date" id="selectstartdate" name="selectstartdate" />
                      <input type="text" class="form-control show_datepicker" placeholder="End date" id="selectenddate" name="selectenddate" />
                    </div>
                    <div id="activity_pricebook_date_error" class="invalid-feedback">This field is required</div>
                  </div>
                  <div class="col-md-3">
                    <button type="submit" id="btn_frm_submit" class="btn btn-primary btn-md">Update<?= $btn_label ?></button>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3" id="guideContainer">
              <div class="col-md-2">
                <div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>">Nationality</span></label>
                  <div class="form-group">
                    <h6 class="m-0"><span class="text-primary">Indian</span></h6>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Adult</span></label>
                  <input type="hidden" name="indian_nationality" value="1">
                  <div class="form-group">
                    <input type="text" id="adult_cost" name="adult_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Children</span></label>
                  <input type="hidden" name="indian_nationality" value="1">
                  <div class="form-group">
                    <input type="text" id="child_cost" name="child_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Infant</span></label>
                  <input type="hidden" name="indian_nationality" value="1">
                  <div class="form-group">
                    <input type="text" id="infant_cost" name="infant_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <hr class="my-3" />

              <div class="col-md-2">
                <div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>">Nationality</span></label>
                  <div class="form-group">
                    <h6 class="m-0"><span class="text-primary">Non-Indian</span></h6>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Foreign Adult</span></label>
                  <input type="hidden" name="nonindian_nationality" value="2">
                  <div class="form-group">
                    <input type="text" id="foreign_adult_cost" name="foreign_adult_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Foreign Children</span></label>
                  <input type="hidden" name="nonindian_nationality" value="2">
                  <div class="form-group">
                    <input type="text" id="foreign_child_cost" name="foreign_child_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $ACTIVITY_ID; ?>"><span class="text-primary">Foreign Infant</span></label>
                  <input type="hidden" name="nonindian_nationality" value="2">
                  <div class="form-group">
                    <input type="text" id="foreign_infant_cost" name="foreign_infant_cost" class="form-control activity_cost py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-3 mt-3" id="show_activity_pricebook_container">
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- / Content -->

    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
    <!-- Page JS -->
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script>
      $(document).ready(function() {
        $('select').selectize();

        var startDatePicker = flatpickr("#selectstartdate", {
          dateFormat: "d-m-Y",
          onChange: function(selectedDates, dateStr, instance) {
            endDatePicker.set("minDate", dateStr);

            // Remove error when a valid start date is selected
            if (dateStr) {
              $("#selectstartdate").removeClass('input-error');
              $("#activity_pricebook_date_error").hide();
            }
          }
        });

        var endDatePicker = flatpickr("#selectenddate", {
          dateFormat: "d-m-Y",
          onChange: function(selectedDates, dateStr, instance) {
            // Remove error when a valid end date is selected
            if (dateStr) {
              $("#selectenddate").removeClass('input-error');
              $("#activity_pricebook_date_error").hide();
            }
          }
        });

        flatpickr("#selectstartdate", {
          dateFormat: "d-m-Y",
          onChange: function(selectedDates, dateStr, instance) {
            // Get the selected outstation pricebook start date
            const startDate = selectedDates[0];

            // Clear the value of the end date input field
            document.getElementById("selectenddate").value = "";

            // Re-initialize the Flatpickr for the outstation pricebook end date with the new minDate
            flatpickr("#selectenddate", {
              dateFormat: "d-m-Y",
              minDate: startDate, // Set the minimum date for the outstation pricebook end date picker
              onChange: function(selectedDates, dateStr, instance) {
                // Get the selected amenities end date
                endDate = selectedDates[0];

                // Trigger AJAX call if both start and end dates are selected
                if (startDate && endDate) {
                  getACTIVITY_PRICEBOOK_DETAILS(startDate, endDate);
                }
              }
            });
          }
        });

        // Function to format a date object as DD/MM/YYYY
        function formatDateString(dateString) {
          // Decode the URL-encoded string
          const decodedString = decodeURIComponent(dateString);

          // Create a Date object from the decoded string
          const date = new Date(decodedString);

          // Extract the day, month, and year
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
          const year = date.getFullYear();

          // Return formatted date as DD/MM/YYYY
          return `${day}/${month}/${year}`;
        }

        function getACTIVITY_PRICEBOOK_DETAILS(startDate, endDate) {
          const formattedStartDate = formatDateString(startDate);
          const formattedEndDate = formatDateString(endDate);
          const activity_ID = '<?= $ACTIVITY_ID; ?>';
          const hotspot_ID = '<?= $HOTSPOT_ID; ?>';
          $.ajax({
            url: 'engine/ajax/ajax_activity_pricebook_details.php?type=show_form',
            type: 'POST',
            data: {
              activity_ID: activity_ID,
              hotspot_ID: hotspot_ID,
              start_date: formattedStartDate,
              end_date: formattedEndDate
            },
            success: function(response) {
              // Handle the response from the server
              // console.log('Response:', response);
              $('#show_activity_pricebook_container').html(response);
            },
            error: function(error) {
              console.log('Error:', error);
            }
          });
        }

        //AJAX FORM SUBMIT
        $("#form_activity_pricebook").submit(function(event) {

          event.preventDefault(); // Prevent default form submission

          // Clear any previous error message
          $("#activity_pricebook_date_error").hide();

          var startDate = $("#selectstartdate").val();
          var endDate = $("#selectenddate").val();
          var hasError = false;

          // Validate if both start and end dates are filled
          if (!startDate) {
            $("#selectstartdate").addClass('input-error'); // Add red border
            hasError = true;
          } else {
            $("#selectstartdate").removeClass('input-error'); // Remove red border
          }

          if (!endDate) {
            $("#selectenddate").addClass('input-error'); // Add red border
            hasError = true;
          } else {
            $("#selectenddate").removeClass('input-error'); // Remove red border
          }

          // Show error message if there is an error
          if (hasError) {
            $("#activity_pricebook_date_error").text('Start date and End date are required').show();
            return; // Stop form submission
          }


          var form = $('#form_activity_pricebook')[0];
          var data = new FormData(form);
          // $(this).find("button[id='btn_frm_submit']").prop('disabled', true);

          var startDate = $("#selectstartdate").val();
          var endDate = $("#selectenddate").val();
          const activity_ID = '<?= $ACTIVITY_ID; ?>';
          const hotspot_ID = '<?= $HOTSPOT_ID; ?>';

          $.ajax({
            type: "post",
            url: 'engine/ajax/__ajax_manage_activity.php?type=activity_pricebook',
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
                $("#selectstartdate").addClass('input-error');
                $("#activity_pricebook_date_error").text('Start date is Required').show();
              } else if (response.errors.selectenddate_required) {
                $("#selectenddate").addClass('input-error');
                $("#activity_pricebook_date_error").text('End date is Required').show();
              }
            } else {

              if (response.u_result == true) {
                $("#btn_frm_submit").prop('disabled', false);
                $.ajax({
                  url: 'engine/ajax/ajax_activity_pricebook_details.php?type=show_form',
                  type: 'POST',
                  data: {
                    activity_ID: activity_ID,
                    hotspot_ID: hotspot_ID,
                    start_date: startDate,
                    end_date: endDate
                  },
                  success: function(response) {
                    // Handle the response from the server
                    // console.log('Response:', response);
                    $('.activity_cost').val('');
                    $('#show_activity_pricebook_container').html(response);
                  },
                  error: function(error) {
                    console.log('Error:', error);
                  }
                });
                TOAST_NOTIFICATION('success', 'Activity Pricebook Created Successfully', 'Success !!!');
              }
              // if (response.i_result == true) {
              //   TOAST_NOTIFICATION('success', 'Activity Pricebook Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
              //   setTimeout(function() {
              //     location.reload();
              //   }, 1000);
              //   show_ACTIVITY_PRICE_BOOK(response.activity_id);
              // }

            }
          }).always(function() {
            $("#btn_frm_submit").prop('disabled', false);
          });
        });

        // Remove error when user types in start date or end date
        $('#selectstartdate, #selectenddate').on('input change', function() {
          if ($("#selectstartdate").val()) {
            $("#selectstartdate").removeClass('input-error');
          }
          if ($("#selectenddate").val()) {
            $("#selectenddate").removeClass('input-error');
          }
          if ($("#selectstartdate").val() && $("#selectenddate").val()) {
            $("#activity_pricebook_date_error").hide();
          }
        });

      });

      /* function toggleActivityPrice() {
        var priceType = document.getElementById('price_type').value;
        var activityPrice = document.getElementById('activity_price');
        var indiaPerson = document.getElementById('india_person');
        var foreignPerson = document.getElementById('foreign_person');

        // Hide all sections
        activityPrice.style.display = 'none';
        indiaPerson.style.display = 'none';
        foreignPerson.style.display = 'none';

        // Show sections based on selected option
        if (priceType == '1') { // Indian
          indiaPerson.style.display = 'block';
        } else if (priceType == '2') { // Non-Indian
          foreignPerson.style.display = 'block';
        } else if (priceType == '3') { // Both
          indiaPerson.style.display = 'block';
          foreignPerson.style.display = 'block';
        }

        // Show activity price section if any option other than default is selected
        if (priceType != '') {
          activityPrice.style.display = 'block';
        }
      }

      function show_ACTIVITY_BASIC_INFO(ACTIVITY_ID = "") {

        $.ajax({
          type: 'post',
          url: 'engine/ajax/__ajax_activity_basicinfo.php?type=show_form',
          data: {
            ID: ACTIVITY_ID,
            //TYPE: TYPE
          },
          success: function(response) {
            $('#showACTIVITYLIST').html('');
            $('#showACTIVITYBASICINFO').html(response);
            $('#showACTIVITYFEEDBACKANDREVIEW').html('');
            $('#showACTIVITYPRICEBOOK').html('');
          }
        });
      }

      function show_ACTIVITY_PRICE_BOOK(ACTIVITY_ID = "") {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/__ajax_activity_price_book.php?type=show_form',
          data: {
            ID: ACTIVITY_ID,
            //TYPE: TYPE
          },
          success: function(response) {
            $('#showACTIVITYLIST').html('');
            $('#showACTIVITYBASICINFO').html('');
            $('#showACTIVITYFEEDBACKANDREVIEW').html('');
            $('#showACTIVITYPRICEBOOK').html(response);
          }
        });
      }

      function getACTIVITY_DETAILS() {

        var activity_selectize = $("#activity")[0].selectize;
        var hotspot_id = $("#hotspot").val();
        $.ajax({
          url: 'engine/ajax/__ajax_get_hotspot_activities.php?type=activity_selectize',
          type: "POST",
          data: {
            hotspot_id: hotspot_id
          },
          success: function(response) {
            // Append the response to the dropdown.

            activity_selectize.clear();
            activity_selectize.clearOptions();
            activity_selectize.addOption(response);
            activity_selectize.setValue(response[0].value);
          }
        });
      } */
    </script>
<?php
  endif;
endif;
?>