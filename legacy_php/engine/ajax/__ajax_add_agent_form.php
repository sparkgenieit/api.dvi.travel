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

    if ($_GET['type'] == 'agent_info') :

        $agent_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($agent_ID  != '' && $agent_ID  != 0) :

            $select_agent_list_query = sqlQUERY_LABEL("SELECT `agent_ID`, `travel_expert_id`, `agent_name`, `agent_lastname`, `agent_primary_mobile_number`, `agent_alternative_mobile_number`, `agent_email_id`, `agent_country`, `agent_state`, `agent_city`, `agent_gst_number`, `agent_gst_attachment` FROM `dvi_agent` WHERE `status`='1' and  `deleted`='0' and `agent_ID`='$agent_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_agent_list_query)) :
                $agent_ID = $fetch_list_data['agent_ID'];
                $agent_name = $fetch_list_data['agent_name'];
                $agent_lastname = $fetch_list_data['agent_lastname'];
                $agent_primary_mobile_number = $fetch_list_data['agent_primary_mobile_number'];
                $agent_alternative_mobile_number = $fetch_list_data['agent_alternative_mobile_number'];
                $agent_email_id = $fetch_list_data['agent_email_id'];
                $agent_gst_number = $fetch_list_data['agent_gst_number'];
                $travel_expert_id = $fetch_list_data['travel_expert_id'];
                $agent_gst_attachment = $fetch_list_data['agent_gst_attachment'];
                $country_name = $fetch_list_data['agent_country'];
                if ($country_name == '' || $country_name == 0) :
                    $country_name = '101';
                endif;
                $state_name = $fetch_list_data['agent_state'];
                $city_name = $fetch_list_data['agent_city'];
                $city_name = getCITYLIST($state_name, $city_name, 'city_label');
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;

        if ($agent_ID != '' && $agent_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID;
            $agent_staff_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_ID;
            $agent_wallet_url = 'agent.php?route=edit&formtype=agent_wallet&id=' . $agent_ID;
            $agent_invoice_url = 'agent.php?route=edit&formtype=agent_invoice&id=' . $agent_ID;
            $agent_configuration_url = 'agent.php?route=edit&formtype=agent_config&id=' . $agent_ID;
            $preview_url = 'agent.php?route=edit&formtype=agent_preview&id=' . $agent_ID;
        endif;

        if ($agent_ID) :
            $email_readonly = 'readonly';
        else :
            $email_readonly = '';
        endif;
?>
        <style>
            .suggestions-list {
                border: 1px solid #ccc;
                max-height: 125px;
                overflow-y: auto;
                display: none;
                position: absolute;
                /* Ensures it stays under the input field */
                background-color: white;
                z-index: 1000;
                /* Higher z-index to stay above content but below buttons */
                width: 100%;
                top: 100%;
                /* Position below the input field */
                left: 0;
            }

            .suggestions-list ul {
                list-style-type: none;
                padding-left: 0;
                margin: 0;
            }

            .suggestions-list li {
                padding: 10px;
                cursor: pointer;
                /* Show it's not selectable */
            }

            .suggestions-list li:hover {
                background-color: #f0f0f0;
            }

            /* Add some z-index to buttons to stay above the suggestions */
            .form-group .btn {
                z-index: 2000;
                /* Ensure buttons appear above suggestions */
                position: relative;
            }
        </style>
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_staff_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_wallet_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <!-- <div class="step">
                            <a href="<?= $agent_invoice_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div> -->
                        <div class="step">
                            <a href="<?= $agent_configuration_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Configuration</h4>
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
                    <form id="agent_sigup_form" autocomplete="off" action="" method="POST" data-parsley-validate>
                        <input type="hidden" name="hidden_agent_ID" value="<?= $agent_ID ?>" />
                        <div class="row g-3">
                            <h5 class="text-primary mt-3 mb-0">Basic Info</h5>
                            <div class="col-md-4">
                                <label for="agent_first_name">First Name <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" id="agent_first_name" name="agent_first_name" placeholder="Enter the first name" required data-parsley-trigger="keyup" autocomplete="off" value="<?= $agent_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="agent_last_name">Last Name <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" id="agent_last_name" name="agent_last_name" placeholder="Enter the last name" required data-parsley-trigger="keyup" autocomplete="off" value="<?= $agent_lastname; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="agent_email_address">Email Address <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="email" class="form-control" data-parsley-type="email" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-checkemail data-parsley-checkemail-message="Email Address already Exists" required <?= $email_readonly; ?> id="agent_email_address" name="agent_email_address" placeholder="Enter the Email Address" autocomplete="off" value="<?= $agent_email_id; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label w-100" for="country_name">Nationality<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="country_name" name="country_name" class="form-select" required disabled>
                                        <?= getCOUNTRYLIST($country_name, 'select_country'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label w-100" for="state_name">State<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="state_name" name="state_name" class="form-select" required>
                                        <?= getSTATELIST($country_name, $state_name, 'select_state'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label w-100" for="city_name">City<span class=" text-danger"> *</span></label>
                                <div class="form-group position-relative">

                                    <input type="text" id="city_name" name="city_name" class="form-control" placeholder="Enter the City Name" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off" value="<?= $city_name; ?>" />
                                    <div id="city_suggestions" class="suggestions-list"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="agent_mobile_number">Mobile No <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" autocomplete="off" data-parsley-type="number" data-parsley-trigger="keyup" required id="agent_mobile_number" name="agent_mobile_number" maxlength="10" data-parsley-whitespace="trim" data-parsley-check_agent_mobile_number data-parsley-check_agent_mobile_number-message="Mobile Number already Exists" placeholder="Enter the Mobile No" value="<?= $agent_primary_mobile_number; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="agent_alternative_mobile_number">Alternative Mobile No</label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" autocomplete="off" data-parsley-type="number" data-parsley-trigger="keyup" id="agent_alternative_mobile_number" name="agent_alternative_mobile_number" maxlength="10" data-parsley-whitespace="trim" data-parsley-check_agent_alternative_mobile_number data-parsley-check_agent_alternative_mobile_number-message="Mobile Number already Exists" placeholder="Enter the Alternative Mobile No" value="<?= $agent_alternative_mobile_number; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="agent_gst_number">GSTIN Number <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input id="agent_gst_number" class="form-control" name="agent_gst_number" type="text" id="agent_gst_number" placeholder="Enter the GSTIN Number" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_agent_gst_number data-parsley-check_agent_gst_number-message="GSTIN Number already Exists" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[A-Za-z0-9]{1}" autocomplete="off" value="<?= $agent_gst_number; ?>" />
                                </div>
                            </div>
                            <?php if ($logged_staff_id == '' || $logged_staff_id == 0) : ?>

                                <div class="col-md-4">
                                    <label for="travel_expert">Travel Expert<span class="text-danger">*</span></label>
                                    <div class="form-group mt-1">
                                        <select class="form-select form-control" name="travel_expert" id="travel_expert">
                                            <?= getTRAVEL_EXPERT($travel_expert_id, 'select'); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="hidden_travel_expert_ID" id="hidden_travel_expert_ID" value="<?= $travel_expert_id; ?>" hidden>
                            <div class="col-md-4">
                                <label for="agent_gst_file_attachement">GST Attachement <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <div class="gst-attachement-download d-flex align-items-center justify-content-between">
                                        <?php if (!empty($agent_gst_attachment)) : ?>
                                            <h6 class="m-0"><?= $agent_gst_attachment; ?></h6>
                                            <a href="uploads/agent_doc/<?= $agent_gst_attachment; ?>" download>
                                                <img src="assets/img/svg/downloads.svg" alt="Download" />
                                            </a>
                                        <?php else : ?>
                                            <h6 class="m-0">No file uploaded</h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header pb-3 px-0 d-flex justify-content-between">
                            <div class="col-md-12 d-flex justify-content-between">
                                <h5 class="card-title">List of Subscription History</h5>
                                <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showSUBSCRIPTIONMODAL( <?= $agent_ID; ?>);" data-bs-dismiss="modal">+ Add Subscription</a>
                            </div>
                        </div>
                        <div class="card-body dataTable_select text-nowrap px-0">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table id="subscription_history" class="table table-hover">
                                    <thead class="table-head">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Subscription Title</th>
                                            <th>Amount (₹)</th>
                                            <th>Validity Start</th>
                                            <th>Validity End</th>
                                            <th>Transaction Id</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="agent.php" class="btn btn-secondary">Back</a>
                                </div>
                                <button type="submit" id="submit_hotspot_info_btn" class="btn btn-primary btn-md">
                                    <?= $btn_label; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSUBSCRIPTIONFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-language-form-data">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showDELETEGALLERYMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content receiving-delete-gallery-form-data">
                </div>
            </div>
        </div>


        <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
        <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
        <script src="assets/js/parsley.min.js"></script>
        <!-- Selectize JS and Autocomplete JS -->
        <script src="assets/js/selectize/selectize.min.js"></script>
        <script>
            $(document).ready(function() {
                $("select").selectize();
                $('#country_name').selectize();
                $('#state_name').selectize();

                $('#city_name').on('keyup', function() {
                    var cityName = $(this).val().toLowerCase(); // Convert input to lowercase for case-insensitive comparison
                    var stateName = $('#state_name').val(); // Ensure state is selected

                    if (cityName.length >= 2) { // Start showing suggestions after 2 characters
                        $.ajax({
                            url: 'engine/ajax/__ajax_fetch_cities.php',
                            method: 'POST',
                            data: {
                                state_name: stateName,
                                term: cityName
                            },
                            dataType: 'json',
                            success: function(data) {
                                var suggestions = '';

                                if (data.length > 0) {
                                    console.log("Data:", data);

                                    $.each(data, function(index, city) {
                                        // Filter cities that exactly match or where the input is a substring of the city name
                                        if (city.name.toLowerCase() === cityName || city.name.toLowerCase().includes(cityName)) {
                                            suggestions += '<li>' + city.name + '</li>';
                                        }
                                    });
                                }

                                if (suggestions) {
                                    $('#city_suggestions').html('<ul>' + suggestions + '</ul>').show();
                                    $('#city_suggestions').removeClass('d-none');
                                } else {
                                    $('#city_suggestions').hide(); // Hide suggestions if no data returned
                                    $('#city_suggestions').addClass('d-none');
                                }
                            }
                        });
                    } else {
                        $('#city_suggestions').hide();
                        $('#city_suggestions').addClass('d-none');
                    }
                });

                // Hide suggestions when clicking outside
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('#city_name').length) {
                        $('#city_suggestions').hide();
                        $('#city_suggestions').addClass('d-none');

                    }
                });

                $('#subscription_history').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONsubscriptionhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "subscription_plan_title"
                        }, //1
                        {
                            data: "subscription_amount"
                        }, //2
                        {
                            data: "validity_start"
                        }, //3
                        {
                            data: "validity_end"
                        }, //4
                        {
                            data: "transaction_id"
                        }, //5
                        {
                            data: "subscription_payment_status"
                        } //6
                    ],

                });

                $("#agent_sigup_form").submit(function(event) {
                    var form = $('#agent_sigup_form')[0];
                    var data = new FormData(form);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_agent.php?type=edit',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE

                            if (response.errors.agent_first_name_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_first_name').focus();
                            } else if (response.errors.agent_last_name_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Your Last Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_last_name').focus();
                            } else if (response.errors.agent_email_address_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter your Email Address !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_email_address').focus();
                            } else if (response.errors.agent_mobile_number_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Your Mobile No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_mobile_number').focus();
                            } else if (response.errors.agent_gst_number_required) {
                                TOAST_NOTIFICATION('error', 'GST number is required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_gst_number').focus();
                            } else if (response.errors.agent_email_address_already_exist) {
                                TOAST_NOTIFICATION('error', 'Account already exist with email !', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#agent_email_address').focus();
                            }

                        } else {
                            //SUCCESS RESPOSNE
                            if (response.result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Agent Details Successfully Updated !', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Agent Details !', 'Error !!!', '', '', '', '', '', '', '', '', '');

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

            function showSUBSCRIPTIONMODAL(AGENT_ID) {

                $('.receiving-language-form-data').load('engine/ajax/__ajax_add_subscription.php?type=show_form&AGENT_ID=' + AGENT_ID + '', function() {
                    const container = document.getElementById("addSUBSCRIPTIONFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'agent_staff') :

        $agent_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($logged_user_level == '4') :

            $select_subscribed_query = sqlQUERY_LABEL("SELECT `staff_count` FROM `dvi_agent_subscribed_plans` WHERE `deleted` = '0'  AND `agent_ID` = '$logged_agent_id'") or die("#1-UNABLE_TO_COLLECT_SUBSCRIBED_PLAN:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_query)) :
                $staff_count = $fetch_data['staff_count'];
            endwhile;

            // Get current staff count
            $current_staff_count_query = sqlQUERY_LABEL("SELECT COUNT(*) as count FROM `dvi_staff_details` WHERE `deleted` = '0' AND `agent_id` = '$logged_agent_id'")
                or die("#2-UNABLE_TO_COLLECT_STAFF_COUNT:" . sqlERROR_LABEL());
            $current_staff_count = sqlFETCHARRAY_LABEL($current_staff_count_query)['count'];

            if ($current_staff_count >= $staff_count) :
                $add_onclick = "onclick='EXCEEDED_THE_STAFF_LIMIT()'";
            else :
                $add_onclick = "onclick='show_staff_add_FORM()'";
            endif;
        else :
            $add_onclick = "onclick='show_staff_add_FORM()'";
        endif;

        if ($agent_ID != '' && $agent_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID;
            $agent_staff_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_ID;
            $agent_wallet_url = 'agent.php?route=edit&formtype=agent_wallet&id=' . $agent_ID;
            $agent_invoice_url = 'agent.php?route=edit&formtype=agent_invoice&id=' . $agent_ID;
            $agent_configuration_url = 'agent.php?route=edit&formtype=agent_config&id=' . $agent_ID;
            $preview_url = 'agent.php?route=edit&formtype=agent_preview&id=' . $agent_ID;
        endif;
    ?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_staff_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_wallet_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <!-- <div class="step">
                            <a href="<?= $agent_invoice_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div> -->
                        <div class="step">
                            <a href="<?= $agent_configuration_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Configuration</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-0">
                    <div class="card-header pb-3 d-flex justify-content-between">
                        <h5 class="card-title mb-3">List of Staff</h5>
                        <button type="button" <?= $add_onclick; ?> id="add_staff" class="btn btn-label-primary waves-effect me-2">+ Add staff</button>
                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="staff_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Mobile No</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#staff_LIST').DataTable({

                    dom: 'Blfrtip',

                    "bFilter": true,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary ms-2"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                        $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                        $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');

                        $('.buttons-pdf').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-danger"><svg version="1.1" fill="currentColor" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" class="me-2" width="16" height="16" xml:space="preserve"><g><g><path d="M494.479,138.557L364.04,3.018C362.183,1.09,359.621,0,356.945,0h-194.41c-21.757,0-39.458,17.694-39.458,39.442v137.789H44.29c-16.278,0-29.521,13.239-29.521,29.513v147.744C14.769,370.761,28.012,384,44.29,384h78.787v88.627c0,21.71,17.701,39.373,39.458,39.373h295.238c21.757,0,39.458-17.653,39.458-39.351V145.385 C497.231,142.839,496.244,140.392,494.479,138.557zM359.385,26.581l107.079,111.265H359.385V26.581z M44.29,364.308c-5.42,0-9.828-4.405-9.828-9.82V206.744c0-5.415,4.409-9.821,9.828-9.821h265.882c5.42,0,9.828,4.406,9.828,9.821v147.744c0,5.415-4.409,9.82-9.828,9.82H44.29zM477.538,472.649c0,10.84-8.867,19.659-19.766,19.659H162.535c-10.899,0-19.766-8.828-19.766-19.68V384h167.403c16.278,0,29.521-13.239,29.521-29.512V206.744c0-16.274-13.243-29.513-29.521-29.513H142.769V39.442c0-10.891,8.867-19.75,19.766-19.75h177.157v128c0,5.438,4.409,9.846,9.846,9.846h128V472.649z"/></g></g><g><g><path d="M132.481,249.894c-3.269-4.25-7.327-7.01-12.173-8.279c-3.154-0.846-9.923-1.269-20.308-1.269H72.596v84.577h17.077v-31.904h11.135c7.731,0,13.635-0.404,17.712-1.212c3-0.654,5.952-1.99,8.856-4.01c2.904-2.019,5.298-4.798,7.183-8.336c1.885-3.538,2.827-7.904,2.827-13.096C137.385,259.634,135.75,254.144,132.481,249.894z M117.856,273.173c-1.288,1.885-3.067,3.269-5.337,4.154s-6.769,1.327-13.5,1.327h-9.346v-24h8.25c6.154,0,10.25,0.192,12.288,0.577c2.769,0.5,5.058,1.75,6.865,3.75c1.808,2,2.712,4.539,2.712,7.615C119.789,269.096,119.144,271.288,117.856,273.173z"/></g></g><g><g><path d="M219.481,263.452c-1.846-5.404-4.539-9.971-8.077-13.702s-7.789-6.327-12.75-7.789c-3.692-1.077-9.058-1.615-16.096-1.61h-31.212v84.577h32.135c6.308,0,11.346-0.596,15.115-1.789c5.039-1.615,9.039-3.865,12-6.75c3.923-3.808,6.942-8.788,9.058-14.942c1.731-5.039,2.596-11.039,2.596-18C222.25,275.519,221.327,268.856,219.481,263.452z M202.865,298.183c-1.154,3.789-2.644,6.51-4.471,8.163c-1.827,1.654-4.125,2.827-6.894,3.519c-2.115,0.539-5.558,0.808-10.327,0.808h-12.75v0v-56.019h7.673c6.961,0,11.635,0.269,14.019,0.808c3.192,0.692,5.827,2.019,7.904,3.981c2.077,1.962,3.692,4.692,4.846,8.192c1.154,3.5,1.731,8.519,1.731,15.058C204.596,289.231,204.019,294.394,202.865,298.183z"/></g></g><g><g><polygon points="294.827,254.654 294.827,240.346 236.846,240.346 236.846,324.923 253.923,324.923 253.923,288.981 289.231,288.981 289.231,274.673 253.923,274.673 253.923,254.654"/></g></g></svg>PDF</a>');
                    },
                    ajax: {
                        "url": "engine/json/__JSONstaff.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "staff_name"
                        }, //2
                        {
                            data: "staff_mobile"
                        }, //3
                        {
                            data: "staff_email"
                        }, //4
                        {
                            data: "status"
                        }, //5

                    ],

                    columnDefs: [

                        {
                            "targets": 5,
                            "visible": true,
                            "data": "status",
                            "render": function(data, type, row, full) {
                                switch (data) {
                                    case '1':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                        break;
                                    case '0':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input"  onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                        break;
                                }
                            }
                        },
                        {
                            "targets": 1,
                            "data": "modify",
                            "render": function(data, type, full) {


                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Preview" href="newstaff.php?route=preview&formtype=preview&id=' + data + '" style="margin-right: 5px; color:#888686;"><svg style="width: 26px; height: 26px;" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> </span> </a><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="agent.php?route=edit&formtype=agent_add_staff&id=' + <?= $agent_ID; ?> + '&staffid=' + data + '"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETESTAFFMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';


                            }
                        }
                    ],
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                    ],

                });
            });

            function show_staff_add_FORM() {
                location.assign('agent.php?route=add&formtype=agent_add_staff&id=<?= $agent_ID; ?>');
            }
            //SHOW DELETE POPUP
            function showDELETESTAFFMODAL(ID) {
                $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_staff.php?type=delete&ID=' + ID, function() {
                    const container = document.getElementById("confirmDELETEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            //CONFIRM DELETE POPUP
            function confirmSTAFFDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_staff.php?type=confirmdelete",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#staff_LIST').DataTable().ajax.reload();
                            $('#confirmDELETEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            function togglestatusITEM(STATUS_ID, STAFF_ID) {
                if (STAFF_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/__ajax_manage_staff.php?type=updatestatus",
                        data: {
                            STAFF_ID: STAFF_ID,
                            oldstatus: STATUS_ID
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.result_success == true) {
                                $('#staff_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'staff Status Updated Successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                TOAST_NOTIFICATION('success', 'Unable to update staff status.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }
            }
        </script>


    <?php elseif ($_GET['type'] == 'agent_add_staff') :

        $agent_id = $_POST['ID'];
        $staff_ID = $_POST['STAFFID'];
        $ROUTE = $_POST['TYPE'];

        if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
            $select_staff_list_query = sqlQUERY_LABEL("SELECT `staff_name`, `staff_mobile`, `staff_email`, `roleID` FROM `dvi_staff_details` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_LIST:" . sqlERROR_LABEL());
            while ($fetch_staff_list_data = sqlFETCHARRAY_LABEL($select_staff_list_query)) :
                $roleID = $fetch_staff_list_data['roleID'];
                $staff_name = $fetch_staff_list_data['staff_name'];
                $staff_mobile = $fetch_staff_list_data['staff_mobile'];
                $staff_email = $fetch_staff_list_data['staff_email'];
            endwhile;

            $select_staff_credientials = sqlQUERY_LABEL("SELECT `userID`, `staff_id`, `user_profile`, `username`, `password` FROM `dvi_users` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
            while ($fetch_staff_credientials_list_data = sqlFETCHARRAY_LABEL($select_staff_credientials)) :
                $staff_username = $fetch_staff_credientials_list_data['username'];
                $staff_password = $fetch_staff_credientials_list_data['password'];
            endwhile;

            $basic_info_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_id;
            // $disabled_navigate = '';
            // $button_text_disabled = '';

            $button_label = "Update";
        else :
            $basic_info_url = 'javascript:;';
            $disabled_navigate = 'disabled';
            $button_text_disabled = ' text-light';

            $button_label = "Save";
        endif;

        if ($staff_ID) :
            $pwd_required = '';
            $email_readonly = 'readonly';
        else :
            $pwd_required = 'required';
            $email_readonly = '';
        endif;
    ?>
        <!-- Default Wizard -->

        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="javascript:;" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="javascript:;" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="javascript:;" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <!-- <div class="step">
                            <a href="javascript:;" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div> -->
                        <div class="step">
                            <a href="javascript:;" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Configuration</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <div>
                        <form id="form_basic_info" action="" method="POST" autocomplete="off" data-parsley-validate>
                            <!-- Basic Info -->
                            <div id="basic_info" class="content active dstepper-block">
                                <div class="content-header mb-3">
                                    <h5 class="text-primary mb-0">Staff Details</h5>
                                </div>
                                <div class="row g-3">
                                    <div class="col-sm-3">
                                        <label class="form-label" for="staff_name">Staff Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="staff_name" id="staff_name" class="form-control" placeholder="Staff Name" required value="<?= $staff_name ?>" />
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="staff_email">Email ID<span class=" text-danger"> *</span></label>
                                        <input type="email" autocomplete="off" name="staff_email" id="staff_email" class="form-control" placeholder="Email ID" aria-label="Email ID" data-parsley-type="email" required <?= $email_readonly; ?> data-parsley-check_staff_email data-parsley-check_staff_email-message="Entered staff Email Already Exists" value="<?= $staff_email ?>" data-parsley-trigger="keyup" />
                                        <input type="hidden" name="old_staff_email" id="old_staff_email" value="<?= $staff_email; ?>" />
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="staff_mobile">Mobile Number<span class=" text-danger"> *</span></label>
                                        <input type="tel" name="staff_mobile" id="staff_mobile" class="form-control" placeholder="Primary Mobile Number" data-parsley-maxlength="10" aria-label="Mobile Number" required data-parsley-type="number" value="<?= $staff_mobile ?>" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" />
                                    </div>
                                    <!-- 
                                    <?php if (($staff_ID == '' || $staff_ID == 0) && $ROUTE != 'edit') : ?>
                                        <div class="col-md-3">
                                            <label class="form-label" for="staff_username">Username<span class=" text-danger"> *</span></label>
                                            <div class="form-group">
                                                <input type="text" name="staff_username" id="staff_username" class="form-control" required placeholder="Username" />
                                            </div>
                                        </div>
                                    <?php endif; ?> -->

                                    <div class="col-md-3">
                                        <label class="form-label" for="staff_password">Password <span class=" text-danger"> <?= (($staff_ID == '' || $staff_ID == 0) && $ROUTE == 'add') ? "*" : "" ?></span></label>
                                        <div class="form-group">
                                            <input type="password" name="staff_password" id="staff_password" class="form-control" placeholder="Password" <?= $pwd_required; ?> />
                                        </div>
                                    </div>

                                    <?php if ($logged_user_level != '4') : ?>
                                        <div class="col-sm-3">
                                            <label class="form-label" for="staff_select_role">Role<span class=" text-danger"> *</span></label>
                                            <select class="form-select" name="staff_select_role" id="staff_select_role" data-parsley-errors-container="#staff_role_error_container" required>
                                                <?= getRole($roleID, 'select'); ?>
                                            </select>
                                            <div id="staff_role_error_container"></div>
                                        </div>
                                    <?php endif; ?>

                                    <input type="hidden" name="hidden_staff_ID" id="hidden_staff_ID" value="<?= $staff_ID; ?>" hidden>
                                    <input type="hidden" name="hidden_agent_ID" id="hidden_agent_ID" value="<?= $agent_id; ?>" hidden>

                                    <div class="row g-3 mt-2">
                                        <div class="col-12 d-flex justify-content-between">
                                            <div>
                                                <a href="agent.php?route=edit&formtype=agent_staff&id=<?= $agent_id; ?>" class="btn btn-secondary">Back
                                                </a>
                                            </div>
                                            <button type="submit" class="btn btn-primary float-end ms-2" id="permit_cost_form_submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- /Default Wizard -->

        <script src="assets/js/parsley.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script src="assets/js/selectize/selectize.min.js"></script>

        <script>
            document.getElementById('staff_email').addEventListener('input', function() {
                var staffEmail = this.value; // Get the email from the input field

                // Extract the username and password based on the email
                var staffUsername = staffEmail.substring(0, staffEmail.indexOf('@'));
                // var staffPassword = staffEmail.substring(0, staffEmail.indexOf('@'));

                // Set the values of the username and password input fields
                // document.getElementById('staff_username').value = staffUsername;
                // document.getElementById('staff_password').value = staffPassword;
            });

            $(document).ready(function() {
                $("select").selectize();

                //CHECK DUPLICATE staff EMAIL ID
                $('#staff_email').parsley();
                var old_staff_emailDETAIL = document.getElementById("old_staff_email").value;
                var staff_email = $('#staff_email').val();
                window.ParsleyValidator.addValidator('check_staff_email', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_staff_email.php',
                            method: "POST",
                            data: {
                                staff_email: value,
                                old_staff_email: old_staff_emailDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#form_basic_info").submit(function(event) {
                    var form = $('#form_basic_info')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_staff.php?type=staff_basic_info',
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
                            if (response.errors.staff_name_required) {
                                TOAST_NOTIFICATION('warning', 'staff Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.staff_email_required) {
                                TOAST_NOTIFICATION('warning', 'staff Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.staff_mobile_required) {
                                TOAST_NOTIFICATION('warning', 'staff Primary Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.staff_email_address_already_exist) {
                                TOAST_NOTIFICATION('warning', 'staff email id already exists', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'staff basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to create staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'staff basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to update staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.staff_email_address_already_exist) {
                                TOAST_NOTIFICATION('warning', 'staff email id already exists', 'Warning !!!', '', '', '', '', '', '', '', '', '');
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

    <?php elseif ($_GET['type'] == 'agent_wallet') :

        $agent_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($agent_ID != '' && $agent_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID;
            $agent_staff_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_ID;
            $agent_wallet_url = 'agent.php?route=edit&formtype=agent_wallet&id=' . $agent_ID;
            $agent_invoice_url = 'agent.php?route=edit&formtype=agent_invoice&id=' . $agent_ID;
            $agent_configuration_url = 'agent.php?route=edit&formtype=agent_config&id=' . $agent_ID;
            $preview_url = 'agent.php?route=edit&formtype=agent_preview&id=' . $agent_ID;

        endif;
    ?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_staff_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_wallet_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title ">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <!-- <div class="step">
                            <a href="<?= $agent_invoice_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div> -->
                        <div class="step">
                            <a href="<?= $agent_configuration_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Configuration</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= general_currency_symbol; ?> <?= number_format((getAGENT_details($agent_ID, '', 'get_total_agent_coupon_wallet')), 2); ?></h4>
                            <p class="mb-0 disble-stepper-title" style="font-size: 14px;">Coupon Wallet</p>
                        </div>
                        <img src="assets/img/svg/coupon.svg" width="30px" height="30px" />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= general_currency_symbol; ?> <?= number_format((getAGENT_details($agent_ID, '', 'get_total_agent_cash_wallet')), 2); ?></h4>
                            <p class="mb-0 disble-stepper-title" style="font-size: 14px;">Cash Wallet</p>
                        </div>
                        <img src="assets/img/svg/money.svg" width="30px" height="30px" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <div>
                    <?php if ($logged_user_level == 1): ?>
                        <a href="javascript:void(0)" class="btn btn-label-warning waves-effect" onclick="showAGENTCASHWALLETMODAL(<?= $agent_ID ?>);" data-bs-dismiss="modal">+ Add Cash Wallet</a>
                    <?php endif; ?>

                    <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showAGENTCOUPONWALLETMODAL(<?= $agent_ID ?>);" data-bs-dismiss="modal">+ Add Coupon Wallet</a>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card p-0">
                    <div class="card-header pb-0 pt-2 d-flex justify-content-between">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3 mt-2">List of Cash wallet History</h5>
                        </div>
                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="staffCASH_HISTORY_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card p-0">
                    <div class="card-header pb-0 pt-2 d-flex justify-content-between">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3 mt-2">List of Coupon Wallet History</h5>
                        </div>
                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="staffCOUPON_HISTORY_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-0 text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-5">
                        <h5 class="modal-title text-center mb-3" id="imageModalLabel">Add Coupon</h5>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="coupen_amount">Amount<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="coupen_amount" id="coupen_amount" class="form-control required-field" placeholder="Amount" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="coupen_title">Title<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <textarea rows="3" id="coupen_title" name="coupen_title" placeholder="Enter the Title" class="form-control required-field" required><?= $invoice_address; ?></textarea>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Sumbit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Add Coupon Wallet -->
        <div class="modal fade" id="addCOUPONWALLETFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-add-form-data">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Coupon Wallet -->
        <div class="modal fade" id="addCASHWALLETFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-form-data">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#staffCASH_HISTORY_LIST').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONcashhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "transaction_date"
                        }, //1
                        {
                            data: "transaction_amount",
                            render: function(data, type, row) {
                                return data ? '₹' + ' ' + data : '';
                            }
                        }, //2
                        {
                            data: "transaction_type"
                        }, //3
                        {
                            data: "remarks"
                        } //4
                    ],

                });

                $('#staffCOUPON_HISTORY_LIST').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONcoupenhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "transaction_date"
                        }, //1
                        {
                            data: "transaction_amount",
                            render: function(data, type, row) {
                                return data ? '₹' + ' ' + data : '';
                            }
                        }, //2
                        {
                            data: "transaction_type"
                        }, //3
                        {
                            data: "remarks"
                        } //4
                    ]

                });
            });

            function showAGENTCOUPONWALLETMODAL(AGENT_ID) {
                $('.receiving-add-form-data').load('engine/ajax/__ajax_add_coupon_wallet.php?type=show_form&id=' + AGENT_ID + '', function() {
                    const container = document.getElementById("addCOUPONWALLETFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();

                    $('#COUPONWALLETFORMLabel').html('Add Coupon Wallet');

                });
            }

            function showAGENTCASHWALLETMODAL(AGENT_ID) {
                $('.receiving-form-data').load('engine/ajax/__ajax_add_agent_cash_wallet.php?type=show_form&id=' + AGENT_ID + '', function() {
                    const container = document.getElementById("addCASHWALLETFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();

                    $('#CASHWALLETFORMLabel').html('Add Cash Wallet');

                });
            }
        </script>


    <?php elseif ($_GET['type'] == 'agent_invoice') :
        $agent_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($agent_ID != '' && $agent_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID;
            $agent_staff_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_ID;
            $agent_wallet_url = 'agent.php?route=edit&formtype=agent_wallet&id=' . $agent_ID;
            $agent_invoice_url = 'agent.php?route=edit&formtype=agent_invoice&id=' . $agent_ID;
            $agent_configuration_url = 'agent.php?route=edit&formtype=agent_config&id=' . $agent_ID;
            $preview_url = 'agent.php?route=edit&formtype=agent_preview&id=' . $agent_ID;

        endif;
    ?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_staff_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_wallet_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_invoice_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_configuration_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Configuration</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 d-none">
            <div class="customer-invoice-overall-section">
                <h4 class="mt-3">Customer Invoice</h4>
                <div class="col-md-12 mt-3 d-none">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="card-title mb-0">Customer Invoice Overview</h5>
                                <button class="btn btn-primary waves-effect waves-light" type="button">
                                    <span>
                                        <i class="ti ti-plus ti-xs me-md-2"></i>
                                        <span class="d-md-inline-block d-none">Create Invoice</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-primary me-3 p-2"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">230k</h5>
                                            <small>Sales</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-info me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">8.549k</h5>
                                            <small>Customers</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-danger me-3 p-2"><i class="ti ti-shopping-cart ti-sm"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">1.423k</h5>
                                            <small>Products</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-success me-3 p-2"><i class="ti ti-currency-dollar ti-sm"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">$9745</h5>
                                            <small>Revenue</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-0">Customer Invoice Overview</h5>
                                <button class="btn btn-primary waves-effect waves-light" type="button">
                                    <span>
                                        <i class="ti ti-plus ti-xs me-md-2"></i>
                                        <span class="d-md-inline-block d-none">Create Invoice</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex px-4 mb-3">
                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                                <div class="dataTables_length d-flex align-items-center me-3">
                                    <label class="me-3">Show</label>
                                    <select name="DataTables_Table_0_length" class="form-select">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row gap-md-4 mt-n6 mt-md-0">
                                <div class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control" placeholder="Search Invoice">
                                    </label>
                                </div>
                                <div class="invoice_status mb-6 mb-md-0">
                                    <select id="UserRole" class="form-select">
                                        <option value="">Invoice Status</option>
                                        <option value="Downloaded" class="text-capitalize">Downloaded</option>
                                        <option value="Draft" class="text-capitalize">Draft</option>
                                        <option value="Paid" class="text-capitalize">Paid</option>
                                        <option value="Partial Payment" class="text-capitalize">Partial Payment</option>
                                        <option value="Past Due" class="text-capitalize">Past Due</option>
                                        <option value="Sent" class="text-capitalize">Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="invoice-list-table table border-top">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#ID</th>
                                        <th>Status</th>
                                        <th>Client</th>
                                        <th>Total</th>
                                        <th class="text-truncate">Issued Date</th>
                                        <th>Balance</th>
                                        <th class="cell-fit">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Customer Invoice Rows -->
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td><a href="#">#5089</a></td>
                                        <td>
                                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 d-flex align-items-center justify-content-center custom-tooltip" data-tip="Tooltip message here">
                                                <i class="ti ti-circle-check ti-xs"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded-circle bg-label-warning">JK</span></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="pages-profile-user.html" class="text-heading text-truncate"><span class="fw-medium">Jamal Kerrod</span></a>
                                                    <small class="text-truncate">Software Development</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹8492</td>
                                        <td>09 May 2020</td>
                                        <td><span class="badge bg-label-success">Paid</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" data-bs-toggle="tooltip" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" data-bs-placement="top" aria-label="Delete"><i class="ti ti-trash mx-2 ti-md"></i></a>
                                                <a href="#" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" aria-label="Preview Invoice"><i class="ti ti-eye mx-2 ti-md"></i></a>
                                                <div class="dropdown">
                                                    <a href="javascript:;" class="btn dropdown-toggle hide-arrow btn-icon btn-text-secondary waves-effect waves-light rounded-pill p-0" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical ti-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="javascript:;" class="dropdown-item">Download</a>
                                                        <a href="app-invoice-edit.html" class="dropdown-item">Edit</a>
                                                        <a href="javascript:;" class="dropdown-item">Duplicate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td><a href="#">#5077</a></td>
                                        <td>
                                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle-check ti-xs"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded-circle bg-label-dark">AM</span></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="pages-profile-user.html" class="text-heading text-truncate"><span class="fw-medium">Albie Morkel</span></a>
                                                    <small class="text-truncate">Software Testing</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹3077</td>
                                        <td>09 May 2020</td>
                                        <td><span class="badge bg-label-success">Paid</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" data-bs-toggle="tooltip" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" data-bs-placement="top" aria-label="Delete"><i class="ti ti-trash mx-2 ti-md"></i></a>
                                                <a href="#" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" aria-label="Preview Invoice"><i class="ti ti-eye mx-2 ti-md"></i></a>
                                                <div class="dropdown">
                                                    <a href="javascript:;" class="btn dropdown-toggle hide-arrow btn-icon btn-text-secondary waves-effect waves-light rounded-pill p-0" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical ti-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="javascript:;" class="dropdown-item">Download</a>
                                                        <a href="app-invoice-edit.html" class="dropdown-item">Edit</a>
                                                        <a href="javascript:;" class="dropdown-item">Duplicate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td><a href="#">#6002</a></td>
                                        <td>
                                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle-check ti-xs"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded-circle bg-label-info">AJ</span></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="pages-profile-user.html" class="text-heading text-truncate"><span class="fw-medium">Angeline Julie</span></a>
                                                    <small class="text-truncate">Human Resources</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹5075</td>
                                        <td>09 May 2020</td>
                                        <td><span class="badge bg-label-warning">Partial Payment</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" data-bs-toggle="tooltip" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" data-bs-placement="top" aria-label="Delete"><i class="ti ti-trash mx-2 ti-md"></i></a>
                                                <a href="#" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" aria-label="Preview Invoice"><i class="ti ti-eye mx-2 ti-md"></i></a>
                                                <div class="dropdown">
                                                    <a href="javascript:;" class="btn dropdown-toggle hide-arrow btn-icon btn-text-secondary waves-effect waves-light rounded-pill p-0" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical ti-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="javascript:;" class="dropdown-item">Download</a>
                                                        <a href="app-invoice-edit.html" class="dropdown-item">Edit</a>
                                                        <a href="javascript:;" class="dropdown-item">Duplicate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add more customer invoice rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agent-invoice-overall-section mt-5">
                <h4 class="mb-0">Agent Invoice</h4>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between mb-0">
                                <h5 class="card-title mb-0">Agent Invoice Overview</h5>
                            </div>
                        </div>
                        <div class="d-flex px-4 mb-3">
                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                                <div class="dataTables_length d-flex align-items-center me-3">
                                    <label class="me-3">Show</label>
                                    <select name="DataTables_Table_0_length" class="form-select">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row  gap-md-4 mt-n6 mt-md-0">
                                <div class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control" placeholder="Search Invoice">
                                    </label>
                                </div>
                                <div class="invoice_status mb-6 mb-md-0">
                                    <select id="UserRole" class="form-select">
                                        <option value="">Invoice Status</option>
                                        <option value="Downloaded" class="text-capitalize">Downloaded</option>
                                        <option value="Draft" class="text-capitalize">Draft</option>
                                        <option value="Paid" class="text-capitalize">Paid</option>
                                        <option value="Partial Payment" class="text-capitalize">Partial Payment</option>
                                        <option value="Past Due" class="text-capitalize">Past Due</option>
                                        <option value="Sent" class="text-capitalize">Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="invoice-list-table table border-top">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#ID</th>
                                        <th>Status</th>
                                        <th>Agent</th>
                                        <th>Total</th>
                                        <th class="text-truncate">Issued Date</th>
                                        <th>Balance</th>
                                        <th class="cell-fit">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Agent Invoice Rows -->
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td><a href="#">#5012</a></td>
                                        <td>
                                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle-check ti-xs"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded-circle bg-label-danger">AG</span></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="pages-profile-user.html" class="text-heading text-truncate"><span class="fw-medium">Alex Gresham</span></a>
                                                    <small class="text-truncate">Agent</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$4520</td>
                                        <td>15 Jan 2021</td>
                                        <td><span class="badge bg-label-success">Paid</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" data-bs-toggle="tooltip" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" data-bs-placement="top" aria-label="Delete"><i class="ti ti-trash mx-2 ti-md"></i></a>
                                                <a href="#" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" aria-label="Preview Invoice"><i class="ti ti-eye mx-2 ti-md"></i></a>
                                                <div class="dropdown">
                                                    <a href="javascript:;" class="btn dropdown-toggle hide-arrow btn-icon btn-text-secondary waves-effect waves-light rounded-pill p-0" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical ti-md"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="javascript:;" class="dropdown-item">Download</a>
                                                        <a href="app-invoice-edit.html" class="dropdown-item">Edit</a>
                                                        <a href="javascript:;" class="dropdown-item">Duplicate</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add more agent invoice rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row invoice-edit mt-5">
            <!-- Invoice Edit-->

            <div class="col-lg-12 col-12 mb-lg-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div class="row m-sm-4 m-0">
                            <div class="col-md-7 mb-md-0 mb-4 ps-0">
                                <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                                    <img src="assets/img/logo-preview.png" alt="image" style="width: 120px;">
                                </div>
                                <p class="mb-2">No.68/1 Butt Road, St Thomas Mount,</p>
                                <p class="mb-2">Chennai – 600016</p>
                                <p class="mb-3">+91 9843288844</p>
                            </div>
                            <div class="col-md-5">
                                <dl class="row mb-2">
                                    <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end ps-0">
                                        <span class="h4 text-capitalize mb-0 text-nowrap">Invoice</span>
                                    </dt>
                                    <dd class="col-sm-6 d-flex justify-content-md-end pe-0 ps-0 ps-sm-2">
                                        <div class="input-group input-group-merge disabled w-px-150">
                                            <span class="input-group-text">#</span>
                                            <input type="text" class="form-control" disabled placeholder="74909" value="74909" id="invoiceId" />
                                        </div>
                                    </dd>
                                    <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end ps-0">
                                        <span class="fw-normal">Date:</span>
                                    </dt>
                                    <dd class="col-sm-6 d-flex justify-content-md-end pe-0 ps-0 ps-sm-2">
                                        <input type="text" class="form-control w-px-150 invoice-date" placeholder="YYYY-MM-DD" />
                                    </dd>
                                    <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end ps-0">
                                        <span class="fw-normal">Due Date:</span>
                                    </dt>
                                    <dd class="col-sm-6 d-flex justify-content-md-end pe-0 ps-0 ps-sm-2">
                                        <input type="text" class="form-control w-px-150 due-date" placeholder="YYYY-MM-DD" />
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        <hr class="my-3 mx-n4" />

                        <div class="row p-sm-4 p-0">
                            <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-4">
                                <h6 class="mb-4">Invoice To:</h6>
                                <p class="mb-1">Uma</p>
                                <p class="mb-1">34, Rajaji road, guindy</p>
                                <p class="mb-1">Chennai - 600 032</p>
                                <p class="mb-1">+919837383838</p>
                                <p class="mb-0">uma@gmail.com</p>
                            </div>
                            <!-- <div class="col-md-6 col-sm-7">
                                <h6 class="mb-4">Bill To:</h6>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="pe-4">Total Due:</td>
                                            <td><span class="fw-medium">Rs12,110.55</span></td>
                                        </tr>
                                        <tr>
                                            <td class="pe-4">Bank name:</td>
                                            <td>Indian Bank</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-4">Country:</td>
                                            <td>United States</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-4">IBAN:</td>
                                            <td>ETD95476213874685</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-4">SWIFT code:</td>
                                            <td>BR91905</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> -->
                        </div>

                        <hr class="my-3 mx-n4" />

                        <form class="source-item pt-4 px-0 px-sm-4">
                            <div class="mb-3" data-repeater-list="group-a">
                                <div class="repeater-wrapper pt-0 pt-md-4" data-repeater-item>
                                    <div class="d-flex border rounded position-relative pe-0">
                                        <div class="row w-100 p-3">
                                            <div class="col-md-6 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Item</p>
                                                <select class="form-select item-details mb-3">
                                                    <option value="App Design">App Design</option>
                                                    <option value="App Customization" selected>App Customization</option>
                                                    <option value="ABC Template">ABC Template</option>
                                                    <option value="App Development">App Development</option>
                                                </select>
                                                <textarea class="form-control" rows="2">The most developer friendly & highly customizable HTML5 Admin</textarea>
                                            </div>
                                            <div class="col-md-3 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Cost</p>
                                                <input type="number" class="form-control invoice-item-price mb-3" value="24" placeholder="24" min="12" />
                                                <div>
                                                    <span>Discount:</span>
                                                    <span class="discount me-2">0%</span>
                                                    <span class="tax-1 me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax 1">0%</span>
                                                    <span class="tax-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax 2">0%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12 mb-md-0 mb-3">
                                                <p class="mb-2 repeater-title">Qty</p>
                                                <input type="number" class="form-control invoice-item-qty" value="1" placeholder="1" min="1" max="50" />
                                            </div>
                                            <div class="col-md-1 col-12 pe-0">
                                                <p class="mb-2 repeater-title">Price</p>
                                                <p class="mb-0">$24.00</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                            <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                            <div class="dropdown">
                                                <i class="ti ti-settings ti-xs cursor-pointer more-options-dropdown" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                </i>
                                                <div class="dropdown-menu dropdown-menu-end w-px-300 p-3" aria-labelledby="dropdownMenuButton">

                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label for="discountInput" class="form-label">Discount(%)</label>
                                                            <input type="number" class="form-control" id="discountInput" min="0" max="100" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="taxInput1" class="form-label">Tax 1</label>
                                                            <select name="tax-1-input" id="taxInput1" class="form-select tax-select">
                                                                <option value="0%" selected>0%</option>
                                                                <option value="1%">1%</option>
                                                                <option value="10%">10%</option>
                                                                <option value="18%">18%</option>
                                                                <option value="40%">40%</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="taxInput2" class="form-label">Tax 2</label>
                                                            <select name="tax-2-input" id="taxInput2" class="form-select tax-select">
                                                                <option value="0%" selected>0%</option>
                                                                <option value="1%">1%</option>
                                                                <option value="10%">10%</option>
                                                                <option value="18%">18%</option>
                                                                <option value="40%">40%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-divider my-3"></div>
                                                    <button type="button" class="btn btn-label-primary btn-apply-changes">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" data-repeater-create>Add Item</button>
                                </div>
                            </div>
                        </form>

                        <hr class="my-3 mx-n4" />

                        <div class="row p-0 p-sm-4">
                            <div class="col-md-6 mb-md-0 mb-3">
                                <div class="d-flex align-items-center mb-3">
                                    <label for="salesperson" class="form-label me-4 fw-medium">Salesperson:</label>
                                    <input type="text" class="form-control ms-3" id="salesperson" placeholder="Edward Crowley" value="Edward Crowley" />
                                </div>
                                <input type="text" class="form-control" id="invoiceMsg" placeholder="Thanks for your business" value="Thanks for your business" />
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <div class="invoice-calculations">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">Subtotal:</span>
                                        <span class="fw-medium">$5000.25</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">Discount:</span>
                                        <span class="fw-medium">$00.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">Tax:</span>
                                        <span class="fw-medium">$100.00</span>
                                    </div>
                                    <hr />
                                    <div class="d-flex justify-content-between">
                                        <span class="w-px-100">Total:</span>
                                        <span class="fw-medium">$5100.25</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3 mx-n4" />

                        <div class="row px-0 px-sm-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="note" class="form-label fw-medium">Note:</label>
                                    <textarea class="form-control" rows="2" id="note">It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice Edit-->

            <!-- Invoice Actions -->
            <div class="col-lg-12 d-flex justify-content-center">
                <div class="col-lg-4 mt-3 col-12 invoice-actions">
                    <div class="mb-4">
                        <div class="card-body">
                            <!-- <button class="btn btn-primary d-grid w-100" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">Confirm</span>
                        </button> -->
                            <div class="d-flex my-2">
                                <a href="app-invoice-preview.html" class="btn btn-label-secondary w-100 me-2">Cancel</a>
                                <button type="button" class="btn btn-primary w-100">Save</button>
                            </div>
                            <!-- <button class="btn btn-primary d-grid w-100" data-bs-toggle="offcanvas" data-bs-target="#addPaymentOffcanvas">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-currency-dollar ti-xs me-2"></i>Add Payment</span>
                        </button> -->
                        </div>
                    </div>
                    <div class="d-none">
                        <p class="mb-2">Accept payments via</p>
                        <select class="form-select mb-4">
                            <option value="Bank Account">Bank Account</option>
                            <option value="Paypal">Paypal</option>
                            <option value="Card">Credit/Debit Card</option>
                            <option value="UPI Transfer">UPI Transfer</option>
                        </select>
                        <div class="d-flex justify-content-between mb-2">
                            <label for="payment-terms" class="mb-0">Payment Terms</label>
                            <label class="switch switch-primary me-0">
                                <input type="checkbox" class="switch-input" id="payment-terms" checked />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label"></span>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <label for="client-notes" class="mb-0">Client Notes</label>
                            <label class="switch switch-primary me-0">
                                <input type="checkbox" class="switch-input" id="client-notes" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label"></span>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <label for="payment-stub" class="mb-0">Payment Stub</label>
                            <label class="switch switch-primary me-0">
                                <input type="checkbox" class="switch-input" id="payment-stub" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>


    <?php elseif ($_GET['type'] == 'agent_config') :
        $agent_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($agent_ID  != '' && $agent_ID  != 0) :

            $select_agent_list_query = sqlQUERY_LABEL("SELECT `itinerary_margin_discount_percentage`, `agent_margin`, `agent_margin_gst_type`, `agent_margin_gst_percentage` FROM `dvi_agent` WHERE `status`='1' and  `deleted`='0' and `agent_ID`='$agent_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_agent_list_query)) :
                $itinerary_margin_discount_percentage = $fetch_list_data['itinerary_margin_discount_percentage'];
                $agent_margin = $fetch_list_data['agent_margin'];
                $agent_margin_gst_type = $fetch_list_data['agent_margin_gst_type'];
                $agent_margin_gst_percentage = $fetch_list_data['agent_margin_gst_percentage'];
            endwhile;
        endif;

        $select_agentconfig_query = sqlQUERY_LABEL("SELECT `agent_config_id`, `agent_id`, `site_logo`, `company_name`, `site_address`, `terms_condition`, `invoice_logo`, `invoice_gstin_no`,`invoice_pan_no`, `invoice_address` FROM `dvi_agent_configuration` WHERE `deleted` = '0' AND `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_config_data = sqlFETCHARRAY_LABEL($select_agentconfig_query)) :
            $hidden_agent_ID = $fetch_config_data['agent_id'];
            $site_logo = $fetch_config_data['site_logo'];
            $site_address = $fetch_config_data['site_address'];
            $terms_condition = $fetch_config_data['terms_condition'];
            $company_name = $fetch_config_data['company_name'];
            $invoice_logo = $fetch_config_data['invoice_logo'];
            $invoice_gstin_no = $fetch_config_data['invoice_gstin_no'];
            $invoice_pan_no = $fetch_config_data['invoice_pan_no'];
            $invoice_address = $fetch_config_data['invoice_address'];
        endwhile;

        if ($agent_ID != '' && $agent_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID;
            $agent_staff_url = 'agent.php?route=edit&formtype=agent_staff&id=' . $agent_ID;
            $agent_wallet_url = 'agent.php?route=edit&formtype=agent_wallet&id=' . $agent_ID;
            $agent_invoice_url = 'agent.php?route=edit&formtype=agent_invoice&id=' . $agent_ID;
            $agent_configuration_url = 'agent.php?route=edit&formtype=agent_config&id=' . $agent_ID;
            $preview_url = 'agent.php?route=edit&formtype=agent_preview&id=' . $agent_ID;

        endif;
    ?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_staff_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Staff</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $agent_wallet_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Wallet</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <!-- <div class="step">
                            <a href="<?= $agent_invoice_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Invoice</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div> -->
                        <div class="step">
                            <a href="<?= $agent_configuration_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Configuration</h4>
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
                    <form id="agent_config_form" autocomplete="off" action="" method="POST" data-parsley-validate>
                        <input type="hidden" name="hidden_agent_ID" value="<?= $agent_ID ?>" />
                        <div class="row g-3">
                            <h5 class="text-primary mt-3 mb-0">Basic Info</h5>
                            <div class="col-md-3">
                                <label for="itinerary_margin_discount_percentage">Itinerary Discount Margin Percentage <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" id="itinerary_margin_discount_percentage" name="itinerary_margin_discount_percentage" placeholder="Enter the Agent Itinerary Discount Margin" required data-parsley-type="number" data-parsley-error-message="Please enter a valid number." autocomplete="off" value="<?= $itinerary_margin_discount_percentage; ?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="agent_margin">Service Charge <span class="text-danger">*</span></label>
                                <div class="form-group mt-1">
                                    <input type="text" class="form-control" id="agent_margin" name="agent_margin" placeholder="Enter the Agent Margin" required data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-error-message="Please enter a valid number." autocomplete="off" value="<?= $agent_margin; ?>" />
                                </div>
                            </div>
                            <div class="col-md-3"><label class="form-label" for="agent_margin_gst_type">Agent Margin GST
                                    Type<span class="text-danger">*</span></label>
                                <select id="agent_margin_gst_type" name="agent_margin_gst_type" class="form-control form-select" required><?= getGSTTYPE($agent_margin_gst_type, 'select') ?></select>
                            </div>

                            <div class="col-md-3"><label class="form-label" for="agent_margin_gst_percentage">Agent Margin GST
                                    Percentage<span class="text-danger">*</span></label>
                                <select id="agent_margin_gst_percentage" name="agent_margin_gst_percentage" class="form-control form-select" required><?= getGSTDETAILS($agent_margin_gst_percentage, 'select') ?></select>
                            </div>

                            <div class="col-md-3">
                                <label for="agent_password">Password</label>
                                <div class="form-group mt-1 position-relative">
                                    <input type="password" class="form-control" id="agent_password" name="agent_password" placeholder="Enter the Password" autocomplete="off" />
                                    <span id="toggleAgentPassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">
                                <i class="ti ti-star ti-sm text-primary"></i>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <h5 class="text-primary m-0">General Configuration</h5>
                            <input type="hidden" id="hidden_agent_ID" name="hidden_agent_ID" value="<?= $hidden_agent_ID; ?>" />
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="">Logo Upload</label>
                                    <a href="#" class="fw-bold" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#sitelogo">View</a>
                                </div>
                                <div class="form-group"><?php /* empty($site_logo) ? 'required' : ''; */ ?>
                                    <input type="file" name="site_logo_upload" id="site_logo_upload" autocomplete="off" class="form-control required-field" accept=".jpg,.jpeg,.png" />
                                    <input type="hidden" id="site_logo" name="site_logo" value="<?= $site_logo; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="agent_company_name">Company Name</label>
                                <div class="form-group">
                                    <input type="text" name="agent_company_name" id="agent_company_name" class="form-control required-field" autocomplete="off" placeholder="Company Name" value="<?= $company_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="site_address">Address</label>
                                <div class="form-group">
                                    <textarea rows="1" id="site_address" name="site_address" placeholder="Enter the Address" class="form-control required-field"><?= $site_address; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="terms_condition">Terms and Condition</label>
                                <div class="form-group">
                                    <textarea rows="1" id="terms_condition" name="terms_condition" placeholder="Enter the Terms and condition" class="form-control required-field"><?= $terms_condition; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">
                                <i class="ti ti-star ti-sm text-primary"></i>
                            </div>
                        </div>

                        <div class="row g-3 mt-2 mb-2">
                            <h5 class="text-primary m-0">Invoice Setting</h5>

                            <div class="col-md-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="">Invoice Logo Upload</label>
                                    <a href="#" class="fw-bold" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#invoicelogo">View</a>
                                </div>
                                <div class="form-group"><?php /* empty($invoice_logo) ? 'required' : ''; */ ?>
                                    <input type="file" name="invoice_logo_upload" id="invoice_logo_upload" autocomplete="off" class="form-control required-field" accept=".jpg,.jpeg,.png" />
                                    <input type="hidden" id="invoice_logo" name="invoice_logo" value="<?= $invoice_logo; ?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gst_in_number">GSTIN Number</label>
                                <div class="form-group">
                                    <input type="text" name="gst_in_number" id="gst_in_number" class="form-control required-field" data-parsley-whitespace="trim" data-parsley-trigger="keyup" autocomplete="off" placeholder="GSTIN Number" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[A-Za-z0-9]{1}" value="<?= $invoice_gstin_no; ?>" maxlength="15" />
                                    <small class="text-dark"><b>GSTIN Format: 10AABCU9603R1Z5 </b></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="invoice_pan_no">Pan No</label>
                                <div class="form-group">
                                    <input type="text" name="invoice_pan_no" id="invoice_pan_no" class="form-control required-field" data-parsley-whitespace="trim" data-parsley-trigger="keyup" autocomplete="off" placeholder="PAN Number" data-parsley-pattern="^[A-Z]{5}[0-9]{4}[A-Z]{1}$" value="<?= $invoice_pan_no; ?>" maxlength="10" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="invoice_address">Invoice Address</label>
                                <div class="form-group">
                                    <textarea rows="1" id="invoice_address" name="invoice_address" placeholder="Enter the Address" class="form-control required-field"><?= $invoice_address; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="agent.php?route=edit&formtype=agent_invoice&id= <?= $agent_ID ?>" class="btn btn-secondary">Back</a>
                                </div>
                                <button type="submit" id="submit_hotspot_info_btn" class="btn btn-primary btn-md">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Invoice logo Modal -->
        <div class="modal fade" id="invoicelogo" tabindex="-1" aria-labelledby="invoicelogoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="invoicelogoLabel">Invoice Logo Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="invoice_logo_modal" src="uploads/agent_gallery/<?= $invoice_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150px" height="150px" />
                    </div>
                </div>
            </div>
        </div>


        <!-- Site logo Modal -->
        <div class="modal fade" id="sitelogo" tabindex="-1" aria-labelledby="sitelogoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sitelogoLabel">Site Logo Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="site_logo_modal" src="uploads/agent_gallery/<?= $site_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150px" height="150px" />
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
        <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/selectize/selectize.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script src="assets/js/ckeditor5.js"></script>

        <script>
            const toggleAgentPassword = document.querySelector('#toggleAgentPassword');
            const agentPasswordField = document.querySelector('#agent_password');

            toggleAgentPassword.addEventListener('click', function(e) {
                // Toggle the type attribute
                const type = agentPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                agentPasswordField.setAttribute('type', type);

                // Toggle the eye icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            CKEDITOR.ClassicEditor.create(document.getElementById("terms_condition"), {
                updateSourceElementOnDestroy: true,
                toolbar: {
                    items: [
                        'exportPDF', 'exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing', 'lineHeight'
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                placeholder: '',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                lineHeight: {
                    options: [1, 1.2, 1.5, 2, 2.5, 3],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                mention: {
                    feeds: [{
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                            '@sugar', '@sweet', '@topping', '@wafer'
                        ],
                        minimumCharacters: 1
                    }]
                },
                removePlugins: [
                    'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                    'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
                    'FormatPainter', 'TableOfContents'
                ]
            }).then(editor => {
                $('#update_submit_global_setting_btn').on('click', function() {
                    editor.updateSourceElement();
                    $('#terms_condition').parsley().validate();

                    if ($('#terms_condition').parsley().isValid()) {
                        // Form submission logic
                    } else {
                        // Handle validation errors
                    }
                });
            }).catch(err => {
                console.error(err.stack);
            });

            $(document).ready(function() {
                $('#agent_margin_gst_type').selectize();
                $('#agent_margin_gst_percentage').selectize();
            });

            $("#agent_config_form").on("submit", function(event) {
                // Check if the form is valid
                if ($(this).parsley().isValid()) {
                    event.preventDefault(); // Prevent the default submit action if form is valid

                    var form = $('#agent_config_form')[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_agent.php?type=config&id=<?= $agent_ID; ?>',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(data);
                        if (!response.success) {
                            // Handle different error responses
                            if (response.errors.agent_itinerary_discount_margin_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Itinerary Discount Margin Percentage !!!', 'Error !!!');
                                $('#itinerary_margin_discount_percentage').focus();
                            } else if (response.errors.agent_margin_gst_type_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Margin GST Type !!!', 'Error !!!');
                                $('#agent_margin_gst_type').focus();
                            } else if (response.errors.agent_itinerary_margin_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter the Agent Margin !!!', 'Error !!!');
                                $('#agent_margin_gst_type').focus();
                            } else if (response.errors.agent_margin_gst_percentage_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Margin GST Percentage !!!', 'Error !!!');
                                $('#agent_margin_gst_percentage').focus();
                            } else if (response.errors.site_logo_upload_required) {
                                TOAST_NOTIFICATION('error', 'Site Logo is required !!!', 'Error !!!');
                                $('#site_logo').focus();
                            } else if (response.errors.site_address_required) {
                                TOAST_NOTIFICATION('error', 'Site Address is required !!!', 'Error !!!');
                                $('#site_address').focus();
                            } else if (response.errors.terms_condition_required) {
                                TOAST_NOTIFICATION('error', 'Terms and Conditions are required !!!', 'Error !!!');
                                $('#terms_condition').focus();
                            } else if (response.errors.invoice_logo_upload_required) {
                                TOAST_NOTIFICATION('error', 'Invoice Logo is required !!!', 'Error !!!');
                                $('#invoice_logo_upload').focus();
                            } else if (response.errors.gst_in_number_required) {
                                TOAST_NOTIFICATION('error', 'GSTIN Number is required !!!', 'Error !!!');
                                $('#gst_in_number').focus();
                            } else if (response.errors.invoice_pan_no_required) {
                                TOAST_NOTIFICATION('error', 'Pan No is required !!!', 'Error !!!');
                                $('#invoice_pan_no').focus();
                            } else if (response.errors.invoice_address_required) {
                                TOAST_NOTIFICATION('error', 'Invoice Address is required !!!', 'Error !!!');
                                $('#invoice_address').focus();
                            } else if (response.errors.agent_company_name_required) {
                                TOAST_NOTIFICATION('error', 'IAgent Company Name is required !!!', 'Error !!!');
                                $('#agent_company_name').focus();
                            }
                        } else {
                            // Handle successful response
                            if (response.result == true) {
                                TOAST_NOTIFICATION('success', 'Agent Details Successfully Updated !', 'Success !!!');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Unable to Update Agent Details !', 'Error !!!');
                            }
                        }
                    });
                } else {
                    // Prevent form submission if validation fails
                    event.preventDefault();
                }
            });
        </script>


    <?php elseif ($_GET['type'] == 'preview') :

        $agent_ID  = $_POST['ID'];

        $select_agent_list_query = sqlQUERY_LABEL("SELECT `subscription_plan_id`, `agent_ID`, `travel_expert_id`, `agent_name`, `agent_lastname`, `agent_primary_mobile_number`, `agent_alternative_mobile_number`, `agent_email_id`, `agent_country`, `agent_state`, `agent_city`, `agent_gst_number`, `agent_gst_attachment` FROM `dvi_agent` WHERE `status`='1' and  `deleted`='0' and `agent_ID`='$agent_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_agent_list_query)) :
            $agent_ID = $fetch_list_data['agent_ID'];
            $subscription_plan_id = $fetch_list_data['subscription_plan_id'];
            $agent_name = $fetch_list_data['agent_name'];
            $agent_lastname = $fetch_list_data['agent_lastname'];
            $agent_primary_mobile_number = $fetch_list_data['agent_primary_mobile_number'];
            $agent_alternative_mobile_number = $fetch_list_data['agent_alternative_mobile_number'];
            $agent_email_id = $fetch_list_data['agent_email_id'];
            $agent_gst_number = $fetch_list_data['agent_gst_number'];
            $travel_expert_id = $fetch_list_data['travel_expert_id'];
            $agent_gst_attachment = $fetch_list_data['agent_gst_attachment'];
            $country_name = $fetch_list_data['agent_country'];
            $state_name = $fetch_list_data['agent_state'];
            $city_name = $fetch_list_data['agent_city'];
            $city_name = getCITYLIST($state_name, $city_name, 'city_label');
            $state_name = getSTATELIST($country_name, $state_name, 'state_label');
            $country_name = getCOUNTRYLIST($country_name, 'country_label');

        endwhile;
    ?>

        <div class="card p-4">
            <div class="row">
                <h5 class="text-primary">Agent Details</h5>
                <div class="col-md-3">
                    <label>First Name</label>
                    <p class="text-light">
                        <?= $agent_name; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Last Name</label>
                    <p class="text-light">
                        <?= $agent_lastname; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Email Address</label>
                    <p class="text-light">
                        <?= $agent_email_id; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Nationality</label>
                    <p class="text-light">
                        <?= $country_name; ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>State</label>
                    <p class="text-light">
                        <?= $state_name; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>City</label>
                    <p class="text-light">
                        <?= $city_name; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Mobile No </label>
                    <p class="text-light">
                        <?= $agent_primary_mobile_number; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Alternative Mobile No</label>

                    <?php if (!empty($agent_alternative_mobile_number)) : ?>
                        <p class="text-light"> <?= $agent_alternative_mobile_number; ?> </p>
                    <?php else : ?>
                        <p class="text-light"> -- </p>
                    <?php endif; ?>

                </div>
                <div class="col-md-3">
                    <label>GSTIN Number</label>
                    <p class="text-light">
                        <?= $agent_gst_number; ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Travel Expert</label>
                    <p class="text-light">
                        <?= getTRAVEL_EXPERT($travel_expert_id, 'label'); ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>GST Attachement</label>
                    <div class="gst-attachement-download d-flex align-items-center justify-content-between">
                        <?php if (!empty($agent_gst_attachment)) : ?>
                            <h6 class="m-0"><?= $agent_gst_attachment; ?></h6>
                            <a href="uploads/agent_doc/<?= $agent_gst_attachment; ?>" download>
                                <img src="assets/img/svg/downloads.svg" alt="Download" />
                            </a>
                        <?php else : ?>
                            <h6 class="m-0">No file uploaded</h6>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="divider">
                    <div class="divider-text">
                        <i class="ti ti-star ti-sm text-primary"></i>
                    </div>
                </div>
                <div class="card-header pb-3 px-0 d-flex justify-content-between">
                    <div class="col-md-8">
                        <h5 class="card-title mb-3 mt-2">List of Subscription History</h5>
                    </div>
                </div>
                <div class="card-body dataTable_select text-nowrap px-0">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table id="agent_subscription_history" class="table table-hover">
                            <thead class="table-head">
                                <tr>
                                    <th>S.No</th>
                                    <th>Subscription Title</th>
                                    <th>Amount</th>
                                    <th>Validity Start</th>
                                    <th>Validity End</th>
                                    <th>Transaction Id</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between py-3">
                    <div>
                        <a href="agent.php" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
        <script src="assets/js/ui-carousel.js"></script>
        <script>
            $(document).ready(function() {

                $('#agent_subscription_history').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONsubscriptionhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "subscription_plan_title"
                        }, //1
                        {
                            data: "subscription_amount",
                            render: function(data, type, row) {
                                return data ? '₹' + data : '';
                            }
                        }, //2
                        {
                            data: "validity_start"
                        }, //3
                        {
                            data: "validity_end"
                        }, //4
                        {
                            data: "transaction_id"
                        }, //5
                        {
                            data: "subscription_payment_status"
                        } //6
                    ],

                });
            });
        </script>

<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>