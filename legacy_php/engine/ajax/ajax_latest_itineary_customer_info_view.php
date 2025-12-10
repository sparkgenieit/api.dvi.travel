<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_GET['itinerary_plan_id'];
        $hotel_group_type = $_GET['group_type'];

        $TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
        $itineary_gross_total_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, $hotel_group_type, 'itineary_gross_total_amount') + $TOTAL_ITINEARY_GUIDE_CHARGES;
        // $payable_amount = $itineary_gross_total_amount * (ITINERARY_AGENT_CONFIRMATION_PAYMENT_PERCENTAGE / 100);
        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `agent_id`,`arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $counter++;
                $agent_id = $fetch_itinerary_plan_data['agent_id'];
                $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                $departure_location = $fetch_itinerary_plan_data['departure_location'];
                $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
                $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
                $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
                $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($trip_start_date_and_time));
                $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($trip_end_date_and_time));
                $arrival_type = $fetch_itinerary_plan_data['arrival_type'];
                $departure_type = $fetch_itinerary_plan_data['departure_type'];
                $expecting_budget = $fetch_itinerary_plan_data['expecting_budget'];
                $itinerary_type = $fetch_itinerary_plan_data['itinerary_type'];
                $entry_ticket_required = $fetch_itinerary_plan_data['entry_ticket_required'];
                $no_of_routes = $fetch_itinerary_plan_data['no_of_routes'];
                $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
                $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
                $total_adult = $fetch_itinerary_plan_data['total_adult'];
                $total_children = $fetch_itinerary_plan_data['total_children'];
                $total_infants = $fetch_itinerary_plan_data['total_infants'];
                $nationality = $fetch_itinerary_plan_data['nationality'];
                $itinerary_preference = $fetch_itinerary_plan_data['itinerary_preference'];
                $meal_plan_breakfast = $fetch_itinerary_plan_data['meal_plan_breakfast'];
                $meal_plan_lunch = $fetch_itinerary_plan_data['meal_plan_lunch'];
                $meal_plan_dinner = $fetch_itinerary_plan_data['meal_plan_dinner'];
                $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
                $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
                $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
                $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
                $vehicle_type = $fetch_itinerary_plan_data['vehicle_type'];
                $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
                $food_type = $fetch_itinerary_plan_data['food_type'];
                $special_instructions = $fetch_itinerary_plan_data['special_instructions'];
                $pick_up_date_and_time = $fetch_itinerary_plan_data['pick_up_date_and_time'];
                $pick_up_date_and_time = date('d-m-Y h:i A', strtotime($pick_up_date_and_time));
            endwhile;

            $total_pax_count = $total_adult + $total_children + $total_infants;
        endif;


        //collect margin discount amount
        $total_discount_amount = round(getITINEARY_COST_DETAILS($itinerary_plan_ID, $hotel_group_type, 'total_discount_amount', $agent_id), 2);
        $selected_itineary_gross_total_amount =  round($itineary_gross_total_amount, 2);
        $net_payable_amount = round($selected_itineary_gross_total_amount - $total_discount_amount);
        $payable_amount = $net_payable_amount * (ITINERARY_AGENT_CONFIRMATION_PAYMENT_PERCENTAGE / 100);

?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="right: 30px; top: 30px;"></button>
        <div class="row">
            <form id="ajax_customer_details_form" enctype="multipart/form-data" action="" autocomplete="off" method="post" data-parsley-validate>
                <input type="hidden" name="hotel_group_type" value="<?= $hotel_group_type ?>" />
                <input type="hidden" name="itinerary_plan_ID" value="<?= $itinerary_plan_ID  ?>" />
                <div class="col-lg-12 d-flex">
                    <div class="col-lg-12 py-3 px-4">
                        <div class="col-lg-12">
                            <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Add Guest Details</strong></h5>
                        </div>

                        <div class="row ">
                            <div class="col-lg-12">
                                <h6 class="card-header p-0 mb-2"><strong>Quotation Details</strong></h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Quotation No.<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <h6 class="card-header p-0 mb-2"><?= $itinerary_quote_ID; ?></h6>

                                </div>
                            </div>
                            <?php if ($itinerary_preference == 1 || $itinerary_preference == 3) : ?>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="source_location">Hotel Selected <span class="text-danger"> *</span></label>
                                    <div class="form-group">
                                        <h6 class="card-header p-0 mb-2">Recommendation #<?= $hotel_group_type; ?></h6>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="row ">
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Agent Name<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <?php if ($logged_agent_id != 0) : ?>
                                        <h6 class="card-header p-0 mb-2"><?= getAGENT_details($logged_agent_id, '', 'label'); ?></h6>
                                        <input type="hidden" name="agent" value="<?= $logged_agent_id ?>" />
                                    <?php else : ?>
                                        <h6 class="card-header p-0 mb-2"><?= getAGENT_details($agent_id, '', 'label'); ?></h6>
                                        <input type="hidden" name="agent" value="<?= $agent_id ?>" />
                                    <?php endif; ?>
                                </div>

                            </div>

                            <div class="col-md-9 mb-3 mt-2">
                                <span class="h6" id="show_cash_available"></span>
                                <?php if ($logged_agent_id != 0) : ?>
                                    <span id="show_top_up_link_msg" class="d-none text-danger" style="font-weight:bold"><i class="ti ti-square-x"></i> Balance Insufficient,</span> <a target="_blank" style="font-weight:bold" href="<?php BASEPATH ?>wallet.php" id="show_top_up_link" class="d-none text-decoration-underline" onclick="$('#VIEWCUSTOMERDETAILSMODAL').modal('hide');"> Click to top-up your wallet.</a>
                                <?php else : ?>
                                    <span id="show_top_up_link_msg" class="d-none text-danger" style="font-weight:bold"><i class="ti ti-square-x"></i> Balance Insufficient,</span>
                                    <a target="_blank" href="<?php BASEPATH ?>agent.php?route=edit&formtype=agent_wallet&ippay=agent_pay&id=<?= $agent_id ?>" id="show_top_up_link" style="font-weight:bold" class="d-none text-decoration-underline" onclick="$('#VIEWCUSTOMERDETAILSMODAL').modal('hide');">Click to top-up agent wallet.</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12">
                                <h6 class="card-header p-0 mb-2"><strong>Primary Guest Details - Adult 1</strong></h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="primary_guest_salutation ">Salutation (Mr, Ms, Mrs)<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="primary_guest_salutation" id="primary_guest_salutation" class="form-select" required>
                                        <?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($selected_type_id, 'customer_salutation_select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Name<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="primary_guest_name" id="primary_guest_name" class="form-control" placeholder="Enter the Name" autocomplete="off" required value="" />
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="primary_guest_contact_no">Primary Contact No.<span class="text-danger"> *</span></label></label>
                                <div class="form-group">
                                    <input type="text" name="primary_guest_contact_no" id="primary_guest_contact_no" class="form-control" placeholder="Enter the Contact No" autocomplete="off" data-parsley-type="number" maxlength="10" value="" data-parsley-whitespace="trim" required />
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="primary_guest_age">Age</label>
                                <div class="form-group">
                                    <input type="text" name="primary_guest_age" id="primary_guest_age" class="form-control" placeholder="Enter the Age" data-parsley-type="number" autocomplete="off" value="" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="primary_guest_alternative_contact_no">Alternative Contact No.</label>
                                <div class="form-group">
                                    <input type="text" name="primary_guest_alternative_contact_no" id="primary_guest_alternative_contact_no" class="form-control" placeholder="Enter the Alternative Contact No" autocomplete="off" value="" data-parsley-type="number" maxlength="10" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="primary_guest_email_id">Email ID</label>
                                <div class="form-group">
                                    <input type="text" name="primary_guest_email_id" id="primary_guest_email_id" class="form-control" placeholder="Enter the Email ID" autocomplete="off" value="" data-parsley-type="email" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                </div>
                            </div>

                        </div>
                        <?php for ($i = 0; $i < ($total_adult - 1); $i++) : ?>
                            <div class="row ">
                                <div class="col-lg-12">
                                    <h6 class="card-header p-0 mb-2"><strong>Adult <?= $i + 2 ?></strong></h6>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="adult_name">Name</label>
                                    <div class="form-group">
                                        <input type="text" name="adult_name[]" id="adult_name" class="form-control" placeholder="Enter the name" autocomplete="off" value="" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="adult_age">Age</label>
                                    <div class="form-group">
                                        <input type="text" name="adult_age[]" id="adult_age" class="form-control" placeholder="Enter the Age" autocomplete="off" value="" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <?php for ($j = 0; $j < $total_children; $j++) : ?>
                            <div class="row ">
                                <div class="col-lg-12">
                                    <h6 class="card-header p-0 mb-2"><strong>Child <?= $j + 1 ?></strong></h6>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="child_name">Name</label>
                                    <div class="form-group">
                                        <input type="text" name="child_name[]" id="child_name" class="form-control" placeholder="Enter the name" autocomplete="off" value="" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="child_age">Age</label>
                                    <div class="form-group">
                                        <input type="text" name="child_age[]" id="child_age" class="form-control" placeholder="Enter the Age" autocomplete="off" value="" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                    </div>
                                </div>

                            </div>
                        <?php endfor; ?>

                        <?php for ($k = 0; $k < $total_infants; $k++) : ?>
                            <div class="row ">
                                <div class="col-lg-12">
                                    <h6 class="card-header p-0 mb-2"><strong>Infant <?= $k + 1 ?></strong></h6>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="infant_name">Name</label>
                                    <div class="form-group">
                                        <input type="text" name="infant_name[]" id="infant_name" class="form-control" placeholder="Enter the name" autocomplete="off" value="" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label source_location" for="infant_age">Age</label>
                                    <div class="form-group">
                                        <input type="text" name="infant_age[]" id="infant_age" class="form-control" placeholder="Enter the Age" autocomplete="off" value="" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h6 class="card-header p-0 mb-2"><strong>Arrival Details</strong></h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Date & Time</label>
                                <div class="form-group">
                                    <input type="text" name="arrival_date_time" id="arrival_date_time" class="form-control" placeholder="dd/mm/yyy" autocomplete="off" value="<?= $trip_start_date_and_time ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Arrival Place</label>
                                <div class="form-group">
                                    <input type="text" name="arrival_place" id="arrival_place" class="form-control" placeholder="Enter the Place" autocomplete="off" value="<?= $arrival_location; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label source_location" for="source_location">Flight Details</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="arrival_flight_details" name="arrival_flight_details" placeholder="Enter the Flight Details"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h6 class="card-header p-0 mb-2"><strong>Departure Details</strong></h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Date & Time</label>
                                <div class="form-group">
                                    <input type="text" name="departure_date_time" id="departure_date_time" class="form-control" placeholder="dd/mm/yyy" autocomplete="off" value="<?= $trip_end_date_and_time ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label source_location" for="source_location">Departure Place</label>
                                <div class="form-group">
                                    <input type="text" name="departure_place" id="departure_place" class="form-control" placeholder="Enter the Place" autocomplete="off" value="<?= $departure_location; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label source_location" for="source_location">Flight Details</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="departure_flight_details" name="departure_flight_details" placeholder="Enter the Flight Details"></textarea>
                                </div>
                            </div>
                        </div>

                        <span id="show_warning_alert_for_price_difference"></span>

                        <div class="col-12 d-flex justify-content-between text-center pt-4" id="btn_section">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btn_customer_details_submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {

                $('.form-select').selectize();

                flatpickr("#arrival_date_time", {
                    enableTime: true,
                    dateFormat: "d-m-Y h:i K",
                });
                flatpickr("#departure_date_time", {
                    enableTime: true,
                    dateFormat: "d-m-Y h:i K",
                });


                var agentId = $('input[name="agent"]').val();

                if (agentId) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/__ajax_check_agent_wallet_balance.php?type=cash_wallet",
                        data: {
                            _agent_ID: agentId,
                            _payable_amount: '<?php echo $payable_amount; ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == true) {
                                $('#show_cash_available').html("Wallet Balance  : <br>" + response.cash_available);
                                $('#show_top_up_link').addClass("d-none");
                                $('#show_top_up_link_msg').addClass("d-none");
                            } else {
                                $('#show_cash_available').html("Wallet Balance  : <br>" + response.cash_available);
                                $('#show_top_up_link').removeClass("d-none");
                                $('#show_top_up_link_msg').removeClass("d-none");
                            }
                        }
                    });
                }

                //AJAX FORM SUBMIT
                $("#ajax_customer_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent default form submission

                    var form = $('#ajax_customer_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");

                    // Show spinner
                    spinner.show();

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_quotation_price_details',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        spinner.hide();

                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.price_changed_details) {
                                $('#show_warning_alert_for_price_difference').html(response.errors.price_changed_details);
                                $('#btn_section').remove();

                                // Add event listeners to the buttons
                                $('#proceed_with_old_price').on('click', function(event) {
                                    handleButtonClick('old');
                                });

                                $('#proceed_with_new_price').on('click', function(event) {
                                    handleButtonClick('new');
                                });
                            }
                            if (response.errors.primary_guest_salutation_required) {
                                TOAST_NOTIFICATION('error', 'Primary Guest Salutation(Mr, Ms, Mrs) is Required!!!', 'Error !!!');
                            }
                            if (response.errors.primary_guest_contact_no_required) {
                                TOAST_NOTIFICATION('error', 'Primary Guest Mobile No is Required!!!', 'Error !!!');
                            }
                            if (response.errors.price_changed_details_alert) {
                                TOAST_NOTIFICATION('warning', ' In this Quotation, the price updated have been changed for Hotel / Vendor / Guide / Activity!!!!!', 'Warning!!!');
                            }
                            if (response.errors.price_not_changed_alert) {
                                TOAST_NOTIFICATION('success', 'No changes in pricing for activities, hotspots, guides, hotels, or vehicles. You can now Proceed with quotation confirmation!!!!!', 'Success !!!');
                            }
                            if (response.errors.insufficient_balance) {
                                TOAST_NOTIFICATION('error', 'Agent has Insufficient Cash balance in the wallet', 'Error !!!');
                            }
                        } else {
                            // SUCCESS RESPONSE
                            if (response.result == true) {
                                var ci_id = response.ci_id;
                                form.reset();
                                $('#VIEWCUSTOMERDETAILSMODAL').modal('hide');
                                TOAST_NOTIFICATION('success', 'Quotation Confirmation has been Successfully sent to travel expert', 'Success !!!');
                                setTimeout(function() {
                                    window.location.href = 'latestconfirmeditinerary_voucherdetails.php?cip_id=' + ci_id;
                                }, 2000);
                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Unable to Confirm the quotation', 'Error !!!');
                                $('#btn_customer_details_submit').removeAttr('disabled');
                            }
                        }
                    });
                });
            });

            function handleButtonClick(type) {
                var spinner = $('#spinner');
                // Show spinner
                spinner.show();
                var form = $('#ajax_customer_details_form')[0];
                // Create a new FormData object to avoid appending the custom field to the original data object
                var newData = new FormData(form);
                // Set a custom field to identify which button was clicked
                newData.append('price_confirmation_type', type);

                $.ajax({
                    type: "post",
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_quotation_price_details&response=true',
                    data: newData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 80000,
                    dataType: 'json',
                    encode: true,
                }).done(function(response) {
                    spinner.hide();

                    if (response.success) {
                        // SUCCESS RESPONSE
                        if (response.result == true) {
                            var ci_id = response.ci_id;
                            if (response.insufficient_balance) {
                                TOAST_NOTIFICATION('error', 'Agent has Insufficient Cash balance in the wallet', 'Error !!!');
                            } else {
                                $('#VIEWCUSTOMERDETAILSMODAL').modal('hide');
                                TOAST_NOTIFICATION('success', 'Quotation Confirmation has been Successfully send to travel expert', 'Success !!!');
                                setTimeout(function() {
                                    window.location.href = 'latestconfirmeditinerary_voucherdetails.php?cip_id=' + ci_id;
                                }, 2000);
                            }
                        } else if (response.result == false) {
                            TOAST_NOTIFICATION('error', 'Unable to Confirm the quotation', 'Error !!!');
                            $('#btn_customer_details_submit').removeAttr('disabled');
                        }
                    } else {
                        if (response.errors.insufficient_balance) {
                            TOAST_NOTIFICATION('error', 'Agent has Insufficient Cash balance in the wallet', 'Error !!!');
                        }
                    }
                });
            }
            <?php
        endif;
    else :
        echo "Request Ignored";
    endif;
