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
  if ($_GET['type'] == 'guide_pricebook') :

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
                <span class="bs-stepper-circle  active-stepper">2</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title">Pricebook</h5>
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
                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                <span class="bs-stepper-label mt-3 ">
                  <h5 class="bs-stepper-title disble-stepper-title">Guide Preview</h5>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <form class="" id="form_guide_pricebook" method="post" data-parsley-validate>
      <input type="hidden" name="hidden_guide_ID" value="<?= $guide_ID ?>" />
      <div class="row mt-3">
        <div class="col-12">
          <div class="card app-calendar-wrapper p-4">

            <div class="row py-2">
              <div class="col-md-8 my-auto">
                <div class="pb-3 d-flex justify-content-between">
                  <h5 class="mb-0">Guide Cost Details</h5>
                </div>
              </div>
              <div class="col-md-4">
                <div class="row">
                  <div class="col-md-8">
                    <div class="input-group">
                      <input type="text" class="form-control show_datepicker" placeholder="Start Date" id="selectstartdate" name="selectstartdate" />
                      <input type="text" class="form-control show_datepicker" placeholder="End date" id="selectenddate" name="selectenddate" />
                    </div>
                    <div id="guide_pricebook_date_error" class="invalid-feedback">This field is required</div>
                  </div>
                  <div class="col-md-3 ">
                    <button type="submit" id="btn_guide_submit" class="btn btn-primary btn-md">Update</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3" id="guideContainer">
              <div class="col-md-2">
                <div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Pax Count</span></label>
                  <div class="form-group">
                    <h6 class="m-0"><span class="text-primary">1-5</span> Pax </h6>
                    <input type="hidden" name="pax_type[]" value="1">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 1: <span class="text-primary">9 AM to 1 PM</span></label>
                  <input type="hidden" name="pax_slot_type[]" value="1">
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 2: <span class="text-primary">9 AM to 4 PM</span></label>
                  <input type="hidden" name="pax_slot_type[]" value="2">
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 3: <span class="text-primary">6 PM to 9 PM</span></label>
                  <input type="hidden" name="pax_slot_type[]" value="3">
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <hr class="my-3" />

              <div class="col-md-2">
                <div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Pax Count</span></label>
                  <div class="form-group">
                    <h6 class="m-0"><span class="text-primary">6-14</span> Pax </h6>
                    <input type="hidden" name="pax_type[]" value="2">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 1: <span class="text-primary">9 AM to 1 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 2: <span class="text-primary">9 AM to 4 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" class="amount form-control  py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 3: <span class="text-primary">6 PM to 9 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>

              <hr class="my-3" />

              <div class="col-md-2">
                <div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Pax Count</span></label>
                  <div class="form-group">
                    <h6 class="m-0"><span class="text-primary">15-40</span> Pax </h6>
                    <input type="hidden" name="pax_type[]" value="3">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 1: <span class="text-primary">9 AM to 1 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 2: <span class="text-primary">9 AM to 4 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="extra_hour_charge_<?= $guide_ID; ?>">Slot 3: <span class="text-primary">6 PM to 9 PM</span></label>
                  <div class="form-group">
                    <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" class="amount form-control py-1 mb-1" placeholder="Enter Price" autocomplete="off" value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-3" id="show_guide_pricebook_container">
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
    <script>
      $(document).ready(function() {
        $("select").selectize();

        var startDatePicker = flatpickr("#selectstartdate", {
          dateFormat: "d-m-Y",
          onChange: function(selectedDates, dateStr, instance) {
            endDatePicker.set("minDate", dateStr);

            // Remove error when a valid start date is selected
            if (dateStr) {
              $("#selectstartdate").removeClass('input-error');
              $("#guide_pricebook_date_error").hide();
            }
          }
        });

        var endDatePicker = flatpickr("#selectenddate", {
          dateFormat: "d-m-Y",
          onChange: function(selectedDates, dateStr, instance) {
            // Remove error when a valid end date is selected
            if (dateStr) {
              $("#selectenddate").removeClass('input-error');
              $("#guide_pricebook_date_error").hide();
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
                  getGUIDE_PRICEBOOK_DETAILS(startDate, endDate);
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

        function getGUIDE_PRICEBOOK_DETAILS(startDate, endDate) {
          const formattedStartDate = formatDateString(startDate);
          const formattedEndDate = formatDateString(endDate);
          const guide_ID = '<?= $guide_ID; ?>';
          $.ajax({
            url: 'engine/ajax/ajax_guide_pricebook_details.php?type=show_form',
            type: 'POST',
            data: {
              guide_ID: guide_ID,
              start_date: formattedStartDate,
              end_date: formattedEndDate
            },
            success: function(response) {
              // Handle the response from the server
              // console.log('Response:', response);
              $('#show_guide_pricebook_container').html(response);
            },
            error: function(error) {
              console.log('Error:', error);
            }
          });
        }

        //AJAX FORM SUBMIT
        $("#form_guide_pricebook").submit(function(event) {
          event.preventDefault(); // Prevent default form submission

          // Clear any previous error message
          $("#guide_pricebook_date_error").hide();

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
            $("#guide_pricebook_date_error").text('Start date and End date are required').show();
            return; // Stop form submission
          }

          var form = $('#form_guide_pricebook')[0];
          var data = new FormData(form);
          $("#btn_guide_submit").prop('disabled', true);
          var startDate = $("#selectstartdate").val();
          var endDate = $("#selectenddate").val();
          const guide_ID = '<?= $guide_ID; ?>';

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
              if (response.errors.guide_required) {
                TOAST_NOTIFICATION('error', 'Guide is Required', 'Error !!!');
              } else if (response.errors.selectstartdate_required) {
                $("#selectstartdate").addClass('input-error');
                $("#guide_pricebook_date_error").text('Start date is Required').show();
              } else if (response.errors.selectenddate_required) {
                $("#selectenddate").addClass('input-error');
                $("#guide_pricebook_date_error").text('End date is Required').show();
              }
            } else {
              if (response.u_result == true) {
                $("#btn_guide_submit").prop('disabled', false);
                $.ajax({
                  url: 'engine/ajax/ajax_guide_pricebook_details.php?type=show_form',
                  type: 'POST',
                  data: {
                    guide_ID: guide_ID,
                    start_date: startDate,
                    end_date: endDate
                  },
                  success: function(response) {
                    // Handle the response from the server
                    // console.log('Response:', response);
                    $('.amount').val('');
                    $('#show_guide_pricebook_container').html(response);
                  },
                  error: function(error) {
                    console.log('Error:', error);
                  }
                });
                TOAST_NOTIFICATION('success', 'Guide Price Book Details Updated Successfully', 'Success !!!');
              }
            }
          }).always(function() {
            $("#btn_guide_submit").prop('disabled', false);
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
            $("#guide_pricebook_date_error").hide();
          }
        });
      });
    </script>
<?php
  endif;
endif;
?>