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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_ID'];

        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`,`arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time`, `itinerary_total_net_payable_amount`,`itinerary_cancellation_status` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $confirmed_itinerary_plan_ID = $fetch_itinerary_plan_data['confirmed_itinerary_plan_ID'];
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
                $itinerary_total_net_payable_amount = $fetch_itinerary_plan_data['itinerary_total_net_payable_amount'];
                $itinerary_cancellation_status = $fetch_itinerary_plan_data['itinerary_cancellation_status'];
            endwhile;

            $total_pax_count = $total_adult + $total_children + $total_infants;
        endif;
        $TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES = getITINEARY_CONFIRMED_TOTAL_GUIDE_CHARGES_DETAILS($itinerary_plan_ID, '', 'TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES');

?>
        <style>
            .sticky-accordion-element {
                position: sticky;
                top: 148px;
                z-index: 999;
            }

            .itinerary-header-sticky-element {
                position: sticky;
                top: 95px;
                background-color: #ffffff;
                z-index: 1000;
                box-shadow: 0px 0px 4px 0px rgba(135, 70, 180, 0.2) !important;
            }

            .itinerary-header-sticky-element-vendor {
                position: sticky;
                top: 50px;
                background-color: #ffffff;
                z-index: 1000;
                box-shadow: 0px 0px 4px 0px rgba(135, 70, 180, 0.2) !important;
            }

            .itinerary-header-title-sticky {
                position: sticky;
                top: 2px;
                background-color: #ffffff;
                z-index: 1001;
            }

            .location-description-container {
                background-color: #ebedff !important;
                border-radius: 5px;
            }
        </style>
        <!-- HOTSPOT ADD INFO START -->
        <?php if ($logged_vendor_id != '0' && $logged_vendor_id != ''):
            $hide_actions = 'd-none';
        else:
            $hide_actions = '';
        endif;
        ?>
        <div class="row mt-2" id="pdf-container-confirmed">
            <div class="col-md-12">
                <div class="card itinerary-header-title-sticky p-3 py-2">
                    <div class="row d-flex align-items-center">
                        <div class="col-3">
                            <h5 class="m-0">Tour Itinerary Plan</b></h5>
                        </div>
                        <?php /* <a href="latestitinerary.php?route=edit&formtype=basic_info&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Route List</a> */ ?>
                        <div class="col-9 text-end" id="remove-this-confirmed">
                            <a href="latestconfirmeditinerary.php" type="button" class="btn btn-sm btn-label-github waves-effect ps-3 me-2 "><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to List</a>
                            <?php if ($itinerary_cancellation_status == 0): ?>
                            <!--  <button type="button" class="btn btn-sm btn-label-success waves-effect ps-3" id="download-confirmed-pdf-btn"><i class="tf-icons ti ti-download ti-xs me-1"></i> Download PDF</button> -->
                            <a target="_blank" href="pluck_card.php?id=<?= $confirmed_itinerary_plan_ID ?>" type="button" class="btn btn-sm btn-label-primary waves-effect ps-3"><i class="tf-icons ti ti-download ti-xs me-1"></i> Download Pluck Card</a>
                            <a href="latestconfirmeditinerary_voucherdetails.php?cip_id=<?= $itinerary_plan_ID; ?>" target="_blank" class="btn btn-sm btn-label-primary waves-effect ps-3"><i class="tf-icons ti ti-notes ti-xs me-1"></i> Voucher Details</a>

                            <button type="button" class="btn btn-sm btn-label-warning waves-effect p-2 <?= $hide_actions ?>" onclick="showINCIDENTALMODAL(<?= $itinerary_plan_ID ?>);" data-bs-dismiss="modal">+ Add Incidental Expenses</button>
                            <a id="btn_cancel_itinerary" href="javascript:void(0);" class="btn btn-sm btn-label-danger waves-effect ps-3 mt-3 <?= $hide_actions ?>" onclick="showCANCEL_ITINERARY_MODAL('<?= $itinerary_plan_ID ?>');"><i class="ti ti-x pe-1 ti-xs me-1"></i> Modify Itinerary </a>
                            <a target="_blank" href="agentinvoicepdf.php?id=<?= $itinerary_plan_ID ?>&type=tax" type="button" class="btn btn-sm btn-label-success waves-effect ps-3 mt-3 <?= $hide_actions ?>"><i class="tf-icons ti ti-download ti-xs me-1"></i> Invoice Tax</a>
                            <a target="_blank" href="agentinvoicepdf.php?id=<?= $itinerary_plan_ID ?>&type=proforma" type="button" class="btn btn-sm btn-label-warning waves-effect ps-3 mt-3 <?= $hide_actions ?>"><i class="tf-icons ti ti-download ti-xs me-1"></i> Invoice Performa</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                if ($guide_for_itinerary == 1) :
                    $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `guide_type`, `guide_language`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_itinerary_guide_route_count_for_whole_itineary = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                    while ($fetch_itinerary_route_guide_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                        $route_guide_ID = $fetch_itinerary_route_guide_data['route_guide_ID'];
                        $guide_type = $fetch_itinerary_route_guide_data['guide_type'];
                        $guide_language = $fetch_itinerary_route_guide_data['guide_language'];
                        $guide_cost = $fetch_itinerary_route_guide_data['guide_cost'];
                    endwhile;
                    $total_guide_charges = round($guide_cost);

                    $select_itinerary_guide_cancelled_count = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `guide_id`, `route_guide_ID`, `guide_type` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `route_cancellation_status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `route_guide_ID` = '$route_guide_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $entire_itinerary_guide_cancelled = sqlNUMOFROW_LABEL($select_itinerary_guide_cancelled_count);
                    while ($fetch_itinerary_route_guide_list_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_cancelled_count)) :
                        $guide_ID = $fetch_itinerary_route_guide_list_data['guide_id'];
                    endwhile;

                    $select_itinerary_guide_cancelled_details = sqlQUERY_LABEL("SELECT GD.`itinerary_plan_ID`, GD.`guide_id`, GD.`route_guide_ID`, GD.`guide_type`, GSD.`slot_cancellation_status`, GSD.`itinerary_route_date` FROM `dvi_cancelled_itinerary_route_guide_details` GD LEFT JOIN `dvi_cancelled_itinerary_route_guide_slot_cost_details` GSD ON GSD.`cancelled_itinerary_ID` = GSD.`cancelled_itinerary_ID` WHERE  GD.`deleted` = '0' and  GD.`status` = '1' and  GD.`route_cancellation_status` = '1' and  GD.`itinerary_plan_ID` = '$itinerary_plan_ID' AND  GD.`route_guide_ID` = '$route_guide_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                    // Initialize an empty array to hold cancelled dates
                    $cancelled_dates = [];

                    // Fetch the cancelled dates
                    while ($fetch_itinerary_route_guide_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_cancelled_details)) :
                        $cancelled_dates[] = $fetch_itinerary_route_guide_data['itinerary_route_date']; // Store cancelled date in array
                    endwhile;

                    // Fetch the start date of the itinerary (includes both date and time)
                    $select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `trip_start_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'")
                        or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());

                    while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
                        $trip_start_date_and_time = $fetch_itineary_plan_data['trip_start_date_and_time']; // Start date with time
                    endwhile;

                    // Convert the start date to DateTime (with time included)
                    $start_date = new DateTime($trip_start_date_and_time);

                    // Initialize the variable to store the output as a string
                    $day_output = '';

                    // Loop through each cancelled date and calculate the day difference
                    foreach ($cancelled_dates as $cancelled_date) {
                        // Convert the cancelled date to DateTime (set time to 00:00:00 for comparison)
                        $cancelled_date_obj = new DateTime($cancelled_date . ' 00:00:00');

                        // Calculate the difference between the start date and the cancelled date
                        $date_difference = $start_date->diff($cancelled_date_obj);

                        // Get the number of days difference (add 1 to make it "Day X" style)
                        $day_count = $date_difference->days + 1;

                        // Append the result to the variable with a comma separator
                        if ($cancelled_date_obj > $start_date) {
                            $day_output .= "Day " . $day_count . ", "; // Append "Day X" with comma
                        }
                    }

                    // Remove the trailing comma and space if there are results
                    $cancelled_days = rtrim($day_output, ', ');
                endif;
                if ($logged_vendor_id != '' && $logged_vendor_id != '0'):
                    $sticky_style = "itinerary-header-sticky-element-vendor";
                else:
                    $sticky_style = "itinerary-header-sticky-element";
                endif
                ?>

                <div class="<?= $sticky_style ?> card-header sticky-element bg-label-primary p-3 mt-3">
                    <div class=" d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <h6 class="m-0 text-blue-color">#<?= $itinerary_quote_ID; ?></h6>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-calendar-event text-body ti-sm me-1"></i>
                                <h6 class="text-capitalize m-0">
                                    <b><?= date('M d, Y', strtotime($trip_start_date_and_time)); ?></b> to
                                    <b><?= date('M d, Y', strtotime($trip_end_date_and_time)); ?></b> (<b><?= $no_of_nights; ?></b> N,
                                    <b><?= $no_of_days; ?></b> D)
                                </h6>
                            </div>
                            <?php /* if ($guide_for_itinerary == 1 && $total_itinerary_guide_route_count_for_whole_itineary == 0) : ?>
                                <a href="javascript:void(0)" class="text-blue-color" onclick="addGUIDE('', '1', '<?= $itinerary_plan_ID; ?>', '','')">
                                    <span class="ti-xs ti ti-circle-plus"></span> Add Guide
                                </a>
                            <?php endif; */ ?>
                        </div>
                        <div>
                            <span class="mb-0 fs-6 text-gray">Adults<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_adult; ?></span></span>
                            <span class="mb-0 fs-6 text-gray">Child<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_children; ?></span></span>
                            <span class="mb-0 fs-6 text-gray">Infants<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_infants; ?></span></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div>
                            <span class="mb-0 fs-6 text-gray">Room Count<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $preferred_room_count; ?></span></span>
                            <span class="mb-0 fs-6 text-gray">Extra Bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_extra_bed; ?></span></span>
                            <span class="mb-0 fs-6 text-gray">Child withbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_child_with_bed; ?></span></span>
                            <span class="mb-0 fs-6 text-gray">Child withoutbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_child_without_bed; ?></span></span>
                        </div>
                        <?php if ($logged_user_level != 2):
                            $total_margin_discount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount', 'cnf_itinerary_summary'); ?>
                            <h5 class="card-title mb-sm-0">Overall Trip Cost : <b class="text-primary fs-4"><span id="overall_trip_cost"><?= general_currency_symbol . ' ' . number_format(round($itinerary_total_net_payable_amount), 2); ?></span></b>
                            </h5>

                        <?php endif; ?>
                    </div>
                </div>

                <div class="nav-align-top p-0">
                    <div class="tab-content p-0  rounded-0" style="background: none;">
                        <div class="tab-pane fade active show" id="navs-top-itinerary<?= $tab_content_route_count; ?>" role="tabpanel">
                            <?php if ($itinerary_preference != 1): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body rounded-0">
                                                <?php if ($special_instructions != '' && $special_instructions != 'NULL') : ?>
                                                    <div class="col-12 mb-3" id="itinerary-guidecontainer-overall">
                                                        <div class="itineray-spl-ins-container d-flex justify-content-between align-items-center p-3">
                                                            <div>
                                                                <div>
                                                                    <h5 class="m-0">Special Instructions
                                                                    </h5>

                                                                    <p class="mt-2 mb-0" style="text-align: justify;">
                                                                        <?= nl2br(html_entity_decode($special_instructions, ENT_QUOTES, 'UTF-8')) ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="d-flex">
                                                                <div>
                                                                    <h5 class="text-primary m-0">
                                                                        <?= general_currency_symbol . ' ' . number_format($total_guide_charges, 2); ?>
                                                                    </h5>
                                                                </div>
                                                                <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','','<?= $selected_group_type; ?>')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                                <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','','<?= $selected_group_type; ?>')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($guide_for_itinerary == 1 && $total_itinerary_guide_route_count_for_whole_itineary > 0 && $entire_itinerary_guide_cancelled == 1) : ?>
                                                    <div class="col-12 mb-3" id="itinerary-guidecontainer-overall">
                                                        <div class="itineray-guide-container py-2 px-4">
                                                            <!-- Row 1 -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="mt-2">
                                                                        <h6 class="m-0" style="color:#4d287b;">Guide for Entire Itinerary
                                                                            Language - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                        </h6>
                                                                        <small class="text-bold">Guide Cancelled: <?= $cancelled_days ?>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div>
                                                                        <?php if ($logged_vendor_id == '' || $logged_vendor_id == '0'): ?>
                                                                            <h5 class="text-primary m-0" style="text-decoration: line-through; text-decoration-color: #ea5455;">
                                                                                <?= general_currency_symbol . ' ' . number_format($total_guide_charges, 2); ?>
                                                                            </h5>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php /* <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                            <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span> */ ?>
                                                                </div>
                                                            </div>

                                                            <!-- Row 2 -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="mb-2">
                                                                        <h6 class="m-0" style="color:#4d287b;">Guide Name
                                                                            - <span class="text-primary"><?= getGUIDEDETAILS($guide_ID, 'label'); ?></span>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div>
                                                                        <span class="badge bg-danger">Cancelled</span>
                                                                    </div>
                                                                    <?php /* <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                            <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span> */ ?>
                                                                </div>
                                                            </div>

                                                            <!-- Repeat similar rows as needed -->
                                                        </div>
                                                    </div>

                                                <?php elseif ($guide_for_itinerary == 1 && $total_itinerary_guide_route_count_for_whole_itineary > 0) : ?>
                                                    <div class="col-12 mb-3" id="itinerary-guidecontainer-overall">
                                                        <div class="itineray-guide-container d-flex justify-content-between align-items-center py-2 px-4">
                                                            <div>
                                                                <div class="my-2">
                                                                    <h6 class="m-0" style="color:#4d287b;">Guide for Entire Itinerary
                                                                        Language - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <?php if ($logged_vendor_id == '' || $logged_vendor_id == '0'): ?>
                                                                        <h5 class="text-primary m-0">
                                                                            <?= general_currency_symbol . ' ' . number_format($total_guide_charges, 2); ?>
                                                                        </h5>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <?php /* <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                            <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span> */ ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Menu Accordion -->
                                                <div id="accordionIcon" class="accordion accordion-without-arrow">
                                                    <?php
                                                    $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                                    $last_destination_city = NULL;
                                                    $show_day_trip_available = false;

                                                    if ($total_itinerary_plan_route_details_count > 0) :
                                                        while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                            $itineary_route_count++;
                                                            $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                            $location_name = $fetch_itinerary_plan_route_data['location_name'];
                                                            $location_id = $fetch_itinerary_plan_route_data['location_id'];
                                                            $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                                                            $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                                                            $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                                                            $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
                                                            $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];
                                                            $source_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');
                                                            $destination_city = getSTOREDLOCATIONDETAILS($location_id, 'DESTINATION_CITY');

                                                            $location_description = getSTOREDLOCATIONDETAILS($location_id, 'LOCATION_DESCRIPTION');

                                                            // Scenario Logic
                                                            if ($itineary_route_count == 1) {
                                                                $show_day_trip_available = false;
                                                            } elseif ($last_destination_city === $source_city && $source_city === $destination_city) {
                                                                $show_day_trip_available = true;
                                                            } else {
                                                                $show_day_trip_available = false;
                                                            }

                                                            // Update last day's destination for the next iteration
                                                            $last_destination_city = $destination_city;

                                                            $get_via_route_details_with_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_with_format');
                                                            $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_without_format');

                                                            if (($arrival_location == $location_name && $itineary_route_count == 1) || ($total_itinerary_plan_route_details_count == $itineary_route_count)) :
                                                                $start_day_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                $start_day_time_add_attr = "readonly";
                                                            else :
                                                                $start_day_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                $start_day_time_add_attr = "";
                                                            endif;

                                                            if ($departure_location == $next_visiting_location && $no_of_days == $itineary_route_count) :
                                                                $day_end_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                $day_end_time_add_attr = "readonly";
                                                            else :
                                                                $day_end_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                $day_end_time_add_attr = "";
                                                            endif;
                                                    ?>
                                                            <!-- DAY WISE ACCORDION -->
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                                    <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-<?= $itineary_route_count; ?>" aria-controls="accordionIcon-<?= $itineary_route_count; ?>">
                                                                        <div class="w-100 itinerary_daywise_list_tab bg-white">
                                                                            <div class="row">
                                                                                <div class="col-sm-3 col-md-4 col-xxl-3 d-flex align-items-center">
                                                                                    <div class="avatar-wrapper">
                                                                                        <div class="avatar me-2">
                                                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h6 class="mb-0"> <b>DAY <?= $itineary_route_count; ?></b> -
                                                                                        <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>
                                                                                    </h6>
                                                                                </div>
                                                                                <?php /* <div class="col-auto d-flex align-items-center"><span> | </span></div> */ ?>
                                                                                <div class="col-sm-3 col-md-5 col-xxl-6 text-start d-flex align-items-center">
                                                                                    <h6 class="mb-0 d-inline-block text-truncate d-flex align-items-center" data-toggle="tooltip" placement="top" title="<?= $location_name; ?>" style="max-width: 300px;"><?= $location_name; ?></h6>
                                                                                    <!-- SHOW VIA ROUTE INFO -->
                                                                                    <div class="bg-primary btn-sm text-white py-1 fs-6 mx-3 rounded-1 tooltip-container" id="via_route_tooltip_container" data-bs-html="true" data-toggle="tooltip" placement="top" aria-label="<?= $get_via_route_details_without_format ?>" title="<?= $get_via_route_details_with_format; ?>">
                                                                                        <i class="ti ti-route ti-tada-hover mx-3" style="font-size: 18px;"></i>
                                                                                    </div>
                                                                                    <h6 class="m-0 d-inline-block text-truncate" data-toggle="tooltip" placement="top" title="<?= $next_visiting_location; ?>" style="max-width: 300px;"><?= $next_visiting_location; ?></h6>
                                                                                </div>
                                                                                <?php /* <div class="col-auto d-flex align-items-center"><span> | </span></div> */ ?>
                                                                                <div class="d-flex align-items-center col-md-3 justify-content-end">
                                                                                    <?php /* <h6 class="m-0 text-blue-color" id="start_time_<?= $itinerary_route_ID; ?>"><?= date('h:i A', strtotime($route_start_time)); ?>
                                                                                </h6> <i class="ti ti-arrows-diff text-blue-color mx-2"></i> <h6 class="m-0 text-blue-color" id="end_time_<?= $itinerary_route_ID; ?>"><?= date('h:i A', strtotime($route_end_time)); ?></h6> */ ?>
                                                                                    <?php if ($itinerary_preference != 1) : ?>
                                                                                        <img src="assets/img/kilometer.png" />
                                                                                        <h6 class="m-0 text-blue-color"><?= number_format(get_ASSIGNED_VEHICLE_ITINEARY_PLAN_DAYWISE_KM_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_total_kms'), 2); ?> KM</h6>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </h2>

                                                                <div class="load_ajax_response_<?= $itinerary_route_ID; ?>" id=" accordionIcon-<?= $itineary_route_count; ?>" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                                    <div class="accordion-body">
                                                                        <div class="row">
                                                                            <?php if ($location_description): ?>
                                                                                <div class="col-12 mt-2 mb-3">
                                                                                    <div class="location-description-container d-flex justify-content-between py-2 px-4 ps-3">
                                                                                        <div><img class="me-1" src="assets/img/svg/location.svg" width="28px" /></div>
                                                                                        <div>
                                                                                            <h6 class="mb-2 fw-bold">About Location</h6>
                                                                                            <h6 class="m-0"><?= $location_description; ?></h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <div class="col-10">
                                                                                <div class="d-flex align-items-center ">
                                                                                    <span class="d-flex align-items-center">
                                                                                        <div class="form-group">
                                                                                            <?php
                                                                                            if (($arrival_location == $location_name && $itineary_route_count == 1) || ($total_itinerary_plan_route_details_count == $itineary_route_count)) :
                                                                                            ?>
                                                                                                <span class="<?= $start_day_time_add_class; ?>"><?= date('h:i A', strtotime($route_start_time)); ?></span>
                                                                                            <?php else : ?>
                                                                                                <span class="<?= $start_day_time_add_class; ?>"><?= date('h:i A', strtotime($route_start_time)); ?></span>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                        <div class="px-2">
                                                                                            <i class="ti ti-arrows-diff"></i>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <?php
                                                                                            if ($departure_location == $next_visiting_location && $no_of_days == $itineary_route_count) :
                                                                                            ?>
                                                                                                <span class="<?= $day_end_time_add_class; ?>"><?= date('h:i A', strtotime($route_end_time)); ?></span>
                                                                                            <?php else : ?>
                                                                                                <span class="<?= $day_end_time_add_class; ?>"><?= date('h:i A', strtotime($route_end_time)); ?></span>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </span>
                                                                                    <?php
                                                                                    if (in_array($itinerary_preference, array(2, 3))) :
                                                                                        #Convert times to 24-hour format for easier comparison
                                                                                        $route_start_time_24h = date('H:i', strtotime($route_start_time));
                                                                                        $route_end_time_24h = date('H:i', strtotime($route_end_time));

                                                                                        #Check if start time is before 6:00 AM or end time is after 8:00 PM
                                                                                        $isBefore6AM = strtotime($route_start_time_24h) < strtotime('06:00');
                                                                                        $isAfter8PM = strtotime($route_end_time_24h) > strtotime('20:00');

                                                                                        // Display the message if either condition is true
                                                                                        if ($isBefore6AM && $isAfter8PM) :
                                                                                    ?>
                                                                                            <p class="mb-0 mt-2">
                                                                                                <i class="ti ti-info-circle-filled mb-1 ms-3 me-1"></i><span class="text-warning">Before 6 AM</span> and <span class="text-warning">after 8 PM</span>, extra
                                                                                                charges for vehicle and driver are applicable.
                                                                                            </p>
                                                                                        <?php
                                                                                        elseif ($isBefore6AM) :
                                                                                        ?>
                                                                                            <p class="mb-0 mt-2">
                                                                                                <i class="ti ti-info-circle-filled mb-1 ms-3 me-1"></i><span class="text-warning">Before 6 AM</span> extra
                                                                                                charges for vehicle and driver are applicable.
                                                                                            </p>
                                                                                        <?php
                                                                                        elseif ($isAfter8PM) :
                                                                                        ?>
                                                                                            <p class="mb-0 mt-2">
                                                                                                <i class="ti ti-info-circle-filled mb-1 ms-3 me-1"></i><span class="text-warning">After 8 PM</span> extra charges
                                                                                                for vehicle and driver are applicable.
                                                                                            </p>
                                                                                    <?php
                                                                                        endif;
                                                                                    endif;
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <!-- check for single day -->
                                                                            <?php
                                                                            if ($guide_for_itinerary == 0) :
                                                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                                                $route_guide_ID = '';
                                                                                $guide_type = '';
                                                                                $guide_language = '';
                                                                                $guide_slot = '';
                                                                                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                                                if ($total_itinerary_guide_route_count > 0) :
                                                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                                                        $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                                                        $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                                                        $guide_cost = $fetch_itinerary_guide_route_data['guide_cost'];
                                                                                    endwhile;

                                                                                    $select_daywise_guide_cancelled_details = sqlQUERY_LABEL("SELECT GD.`itinerary_plan_ID`, GD.`guide_id`, GD.`route_guide_ID`, GD.`guide_type`, GSD.`guide_type`, GSD.`slot_cancellation_status`, GSD.`itinerary_route_date`,  GSD.`guide_slot`FROM `dvi_cancelled_itinerary_route_guide_details` GD LEFT JOIN `dvi_cancelled_itinerary_route_guide_slot_cost_details` GSD ON GSD.`cancelled_itinerary_ID` = GD.`cancelled_itinerary_ID` WHERE GD.`deleted` = '0' AND GD.`status` = '1' AND GSD.`slot_cancellation_status` = '1' AND GD.`itinerary_plan_ID` = '$itinerary_plan_ID' AND GD.`route_guide_ID` = '$route_guide_ID' AND GSD.`itinerary_route_date` = '$itinerary_route_date'") or die("#1-UNABLE_TO_COLLECT_GUIDE_DETAILS: " . sqlERROR_LABEL());

                                                                                    $daywise_guide_cancelled = sqlNUMOFROW_LABEL($select_daywise_guide_cancelled_details);

                                                                                    // while ($fetch_itinerary_daywise_guide_id = sqlFETCHARRAY_LABEL($select_daywise_guide_cancelled_details)) :
                                                                                    //     $day_guide_id = $fetch_itinerary_daywise_guide_id['guide_id'];
                                                                                    // endwhile;
                                                                                    // Initialize an empty array to store cancelled slots for the date
                                                                                    $cancelled_slots = [];

                                                                                    // Fetch the slots for the date
                                                                                    while ($fetch_itinerary_daywise_guide_data = sqlFETCHARRAY_LABEL($select_daywise_guide_cancelled_details)) {
                                                                                        $day_guide_id = $fetch_itinerary_daywise_guide_data['guide_id'];
                                                                                        $cancelled_slots[] = $fetch_itinerary_daywise_guide_data['guide_slot']; // Store cancelled slots in an array
                                                                                    }

                                                                                    // Prepare output
                                                                                    $day_wise_output = '';
                                                                                    foreach ($cancelled_slots as $cancelled_slots_data) {
                                                                                        $slot_output = getSLOTTYPE($cancelled_slots_data, 'label'); // Get slot type label

                                                                                        if ($slot_output) {
                                                                                            $day_wise_output .= $slot_output . ", ";
                                                                                        }
                                                                                    }

                                                                                    // Remove the trailing comma and space
                                                                                    $day_wise_output = rtrim(
                                                                                        $day_wise_output,
                                                                                        ', '
                                                                                    );

                                                                            ?>

                                                                                    <div class="col-12 mt-2" id="itinerary-guidecontainer">

                                                                                        <div class="itineray-guide-container py-2 px-4">
                                                                                            <!-- Row 1 -->
                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                <div>
                                                                                                    <div class="mt-2">
                                                                                                        <h6 class="m-0" style="color:#4d287b;">Guide
                                                                                                            Language - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="d-flex">
                                                                                                    <div>
                                                                                                        <?php if ($logged_vendor_id == '' || $logged_vendor_id == '0'): ?>
                                                                                                            <h5 class="text-primary m-0">
                                                                                                                <?= general_currency_symbol . ' ' . number_format($guide_cost, 2); ?>
                                                                                                            </h5>
                                                                                                        <?php endif; ?>
                                                                                                    </div>
                                                                                                    <?php /* <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                            <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span> */ ?>
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Row 2 -->
                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                <div>
                                                                                                    <div class="m-0">
                                                                                                        <h6 class="m-0" style="color:#4d287b;">Guide Name
                                                                                                            - <span class="text-primary"><?= getGUIDEDETAILS($day_guide_id, 'label'); ?></span>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- Row 3 -->
                                                                                            <div class="d-flex justify-content-start align-items-center">
                                                                                                <div>
                                                                                                    <div class="m-0">
                                                                                                        <h6 class="m-0" style="color:#4d287b;">Booked Slots -
                                                                                                            <span class="text-primary"><?= 'Slot Timing - ' . getSLOTTYPE($guide_slot, 'label'); ?></span>
                                                                                                        </h6><?php if ($daywise_guide_cancelled >= 1): ?>
                                                                                                            <small class="m-0" class="text-bold">Cancelled Slots: <?= $day_wise_output ?>
                                                                                                            </small>
                                                                                                        <?php endif; ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Repeat similar rows as needed -->
                                                                                        </div>
                                                                                    </div>

                                                                                <?php else : ?>
                                                                                    <?php /* <div class="col-2 d-flex justify-content-end">
                                                                                    <div class="day_wise_guide_avilability_">
                                                                                        <a href="javascript:void(0)" class="btn btn-sm btn-label-github" onclick="addGUIDE('', '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>','<?= $itinerary_route_date; ?>')">
                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add
                                                                                            Guide
                                                                                        </a>
                                                                                    </div>
                                                                                </div> */ ?>
                                                                            <?php
                                                                                endif;
                                                                            endif; ?>

                                                                            <?php
                                                                            $select_itinerary_plan_route_hotspot_availability_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `status` = '1' AND `item_type` IN ('6','7') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                            $total_itinerary_plan_route_hotspot_availability_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                            $fetch_hotspot_availability = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                            $get_route_last_hotspot_ID = $fetch_hotspot_availability['route_hotspot_ID'];

                                                                            $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time`, ROUTE_HOTSPOT.`allow_break_hours`, ROUTE_HOTSPOT.`allow_via_route`, ROUTE_HOTSPOT.`via_location_name` FROM `dvi_confirmed_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                            $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
                                                                            $itineary_route_hotspot_count = 0;

                                                                            if ($direct_to_next_visiting_place == 1):
                                                                                $previous_hotspot_name = $next_visiting_location;
                                                                            else:
                                                                                $previous_hotspot_name = $location_name;
                                                                            endif;
                                                                            // Initialize a variable to store the previous hotspot name

                                                                            if ($total_itinerary_plan_route_hotspot_details_count > 0) :
                                                                            ?>
                                                                                <ul class="timeline pt-3 px-3 mb-0">
                                                                                    <?php
                                                                                    while ($fetch_itinerary_plan_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_details_query)) :
                                                                                        $itineary_route_hotspot_count++;
                                                                                        $route_hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['route_hotspot_ID'];
                                                                                        $item_type = $fetch_itinerary_plan_route_hotspot_data['item_type'];
                                                                                        $hotspot_order = $fetch_itinerary_plan_route_hotspot_data['hotspot_order'];
                                                                                        $hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['hotspot_ID'];
                                                                                        $hotspot_amout = $fetch_itinerary_plan_route_hotspot_data['hotspot_amout'];
                                                                                        $hotspot_traveling_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_traveling_time'];
                                                                                        $hotspot_travelling_distance = $fetch_itinerary_plan_route_hotspot_data['hotspot_travelling_distance'];
                                                                                        $hotspot_start_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_start_time'];
                                                                                        $hotspot_end_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_end_time'];
                                                                                        $hotspot_plan_own_way = $fetch_itinerary_plan_route_hotspot_data['hotspot_plan_own_way'];
                                                                                        $hotspot_name = $fetch_itinerary_plan_route_hotspot_data['hotspot_name'];
                                                                                        $hotspot_description = $fetch_itinerary_plan_route_hotspot_data['hotspot_description'];
                                                                                        $hotspot_video_url = $fetch_itinerary_plan_route_hotspot_data['hotspot_video_url'];
                                                                                        $itinerary_travel_type_buffer_time = $fetch_itinerary_plan_route_hotspot_data['itinerary_travel_type_buffer_time'];
                                                                                        $allow_break_hours = $fetch_itinerary_plan_route_hotspot_data['allow_break_hours'];
                                                                                        $allow_via_route = $fetch_itinerary_plan_route_hotspot_data['allow_via_route'];
                                                                                        $via_location_name = $fetch_itinerary_plan_route_hotspot_data['via_location_name'];
                                                                                        $hotspot_gallery_name = getHOTSPOT_GALLERY_DETAILS($hotspot_ID, 'hotspot_gallery_name');

                                                                                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                                                                        $image_path = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                                                                        $default_image = BASEPATH . 'uploads/no-photo.png';

                                                                                        if ($hotspot_gallery_name):
                                                                                            // Check if the image file exists
                                                                                            $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                                                                                        else:
                                                                                            $image_src = $default_image;
                                                                                        endif;

                                                                                        if ($item_type == 1) :

                                                                                            if ($show_day_trip_available) : ?>
                                                                                                <!-- <li class="mb-3">
                                                                                                    <div class="d-flex align-items-center">
                                                                                                        <div class="avatar me-3">
                                                                                                            <span class="avatar-initial rounded-circle bg-label-info"><i class="ti ti-bell text-info ti-sm"></i></span>
                                                                                                        </div>
                                                                                                        <div class="px-4 py-2 w-50 bg-info" style="border-radius:3px;">
                                                                                                            <h6 class="m-0 text-white">Day Trip is available
                                                                                                            </h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li> -->
                                                                                            <?php
                                                                                            endif;
                                                                                            ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-bed text-body ti-sm"></i></span>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <h6 class="m-0">
                                                                                                            <?php if (trim($arrival_location) == trim($location_name) && $itineary_route_count == 1): ?>
                                                                                                                <?= getGLOBALSETTING('itinerary_break_time'); ?><?php else: ?>
                                                                                                                <?= getGLOBALSETTING('itinerary_hotel_start'); ?>
                                                                                                            <?php endif; ?>
                                                                                                            <span class="mb-0 ms-2">
                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                -
                                                                                                                <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                            </span>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="itineray-daywise-border"></div>
                                                                                            </li>
                                                                                            <?php elseif ($item_type == 3 && $item_type_check == 0) :

                                                                                            if ($last_day_ending_location == NULL) :
                                                                                                $last_day_ending_location =  $next_visiting_location;
                                                                                            endif;

                                                                                            if ($show_day_trip_available) : ?>
                                                                                                <!-- <li class="mb-3">
                                                                                                    <div class="d-flex align-items-center">
                                                                                                        <div class="avatar me-3">
                                                                                                            <span class="avatar-initial rounded-circle bg-label-info"><i class="ti ti-bell text-info ti-sm"></i></span>
                                                                                                        </div>
                                                                                                        <div class="px-4 py-2 w-50 bg-info" style="border-radius:3px;">
                                                                                                            <h6 class="m-0 text-white">Day Trip is available
                                                                                                            </h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li> -->
                                                                                            <?php
                                                                                            endif;
                                                                                            ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-bed text-body ti-sm"></i></span>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <h6 class="m-0">

                                                                                                            <?= getGLOBALSETTING('itinerary_hotel_start'); ?>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="itineray-daywise-border"></div>
                                                                                            </li>
                                                                                        <?php endif; ?>
                                                                                        <?php if ($item_type == 2) : ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                                    <div class="row">
                                                                                                        <div class="col-1 pe-0">
                                                                                                            <div class="avatar mt-2 me-3">
                                                                                                                <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-11 ps-0">
                                                                                                            <div class="mt-1">
                                                                                                                <h6 class="m-0">Travel from <b class="text-primary"><?= $location_name; ?></b>
                                                                                                                    to <b class="text-primary"><?= getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></b>.
                                                                                                                </h6>
                                                                                                                <div class="d-flex gap-3">
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                        -
                                                                                                                        <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-route me-1 mb-1"></i>
                                                                                                                        <?= $hotspot_travelling_distance; ?>
                                                                                                                        KM
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                                        <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                        (This may vary due to traffic
                                                                                                                        conditions)
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="itineray-daywise-border"></div>
                                                                                            </li>
                                                                                        <?php endif; ?>
                                                                                        <?php if ($item_type == 3) :
                                                                                            $from_hotspot_name = $previous_hotspot_name; // Store the "from" hotspot name
                                                                                            $to_hotspot_name = $hotspot_name; // Store the "to" hotspot name
                                                                                            if ($allow_break_hours == 1):
                                                                                        ?>
                                                                                                <li class="mb-3">
                                                                                                    <div class="px-3 py-2 rounded-3 bg-label-warning" style="border-radius:3px;">
                                                                                                        <div class="row">
                                                                                                            <div class="col-12 ps-0 d-flex align-items-center">
                                                                                                                <div class="avatar me-3 ms-2">
                                                                                                                    <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-hourglass-high text-blue-color"></i></span>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <h6 class="m-0">Expect a waiting time of approximately <span class="text-primary"><?= formatTimeDuration($hotspot_traveling_time); ?></span> at this location
                                                                                                                        (<b class="text-primary"><?= $to_hotspot_name; ?></b>)
                                                                                                                        <span class="ms-2">
                                                                                                                            <i class="ti ti-clock mx-1 mb-1"></i>
                                                                                                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                            -
                                                                                                                            <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                                            <i class="ti ti-hourglass-high mx-1 fs-5 ti-sm mb-1"></i>
                                                                                                                            <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                        </span>
                                                                                                                    </h6>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li>
                                                                                            <?php elseif ($allow_via_route == 1):
                                                                                                $to_hotspot_name = $via_location_name;
                                                                                            ?>
                                                                                                <li class="mb-3">
                                                                                                    <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                                        <div class="row">
                                                                                                            <div class="col-12 ps-0 d-flex align-items-center">
                                                                                                                <div class="avatar me-3 ms-2">
                                                                                                                    <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <h6 class="m-0">Travelling from <b class="text-primary"><?= $from_hotspot_name; ?></b>
                                                                                                                        to <b class="text-primary"><?= $to_hotspot_name; ?></b>
                                                                                                                        <span class="ms-2">
                                                                                                                            <i class="ti ti-clock mx-1 mb-1"></i>
                                                                                                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                            -
                                                                                                                            <?= date('h:i A', strtotime($hotspot_end_time)); ?>

                                                                                                                            <i class="ti ti-route mx-1 mb-1"></i>
                                                                                                                            <?= $hotspot_travelling_distance; ?>
                                                                                                                            KM

                                                                                                                            <i class="ti ti-hourglass-high mx-1 fs-5 ti-sm mb-1"></i>
                                                                                                                            <?= formatTimeDuration($hotspot_traveling_time); ?> (This may vary due to traffic conditions)


                                                                                                                        </span>
                                                                                                                    </h6>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li>
                                                                                                <?php
                                                                                                $previous_hotspot_name = $via_location_name;
                                                                                                ?>
                                                                                            <?php else: ?>
                                                                                                <li class="mb-3">
                                                                                                    <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                                        <div class="row">
                                                                                                            <div class="col-12 ps-0 d-flex align-items-center">
                                                                                                                <div class="avatar me-3 ms-2">
                                                                                                                    <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <h6 class="m-0">Travelling from <b class="text-primary"><?= $from_hotspot_name; ?></b>
                                                                                                                        to <b class="text-primary"><?= $to_hotspot_name; ?></b>
                                                                                                                        <span class="ms-2">
                                                                                                                            <i class="ti ti-clock mx-1 mb-1"></i>
                                                                                                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                            -
                                                                                                                            <?= date('h:i A', strtotime($hotspot_end_time)); ?>

                                                                                                                            <i class="ti ti-route mx-1 mb-1"></i>
                                                                                                                            <?= $hotspot_travelling_distance; ?>
                                                                                                                            KM

                                                                                                                            <i class="ti ti-hourglass-high mx-1 fs-5 ti-sm mb-1"></i>
                                                                                                                            <?= formatTimeDuration($hotspot_traveling_time); ?> (This may vary due to traffic conditions)
                                                                                                                        </span>
                                                                                                                    </h6>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li>
                                                                                            <?php endif; ?>
                                                                                            <?php if ($hotspot_plan_own_way == 1) : ?>
                                                                                                <li class="mb-3">
                                                                                                    <div class="d-flex align-items-center">
                                                                                                        <div class="avatar me-3">
                                                                                                            <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-bell text-danger ti-sm"></i></span>
                                                                                                        </div>
                                                                                                        <div class="px-4 py-2 w-50" style="background-color: #dc3545 !important; border-radius:3px;">
                                                                                                            <h6 class="m-0 text-white">You have deviated from
                                                                                                                our suggestion and implement your approch.</h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="itineray-daywise-border"></div>
                                                                                                </li>
                                                                                            <?php endif; ?>
                                                                                        <?php endif; ?>
                                                                                        <?php if ($item_type == 4 && $hotspot_ID != 0) :
                                                                                            $previous_hotspot_name = $hotspot_name; // Store the hotspot name
                                                                                        ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="p-4 rounded-3" style="background-color: #f0e0f8;">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-9">
                                                                                                            <div class="d-flex align-items-center">
                                                                                                                <h5 class="mb-0"><?= $hotspot_name; ?></h5>
                                                                                                                <div class="text-end mx-2">|
                                                                                                                </div>
                                                                                                                <div class="text-end">
                                                                                                                    <?php $total_guest_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_person_count');

                                                                                                                    $select_hotspot_cancalled_details = sqlQUERY_LABEL("SELECT HD.`itinerary_plan_ID`, HD.`hotspot_ID`, HD.`route_hotspot_ID`, HECD.`traveller_type`, HECD.`entry_cost_cancellation_status` FROM `dvi_cancelled_itinerary_route_hotspot_details` HD LEFT JOIN `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` HECD ON HECD.`cancelled_itinerary_ID` = HD.`cancelled_itinerary_ID` WHERE HD.`deleted` = '0' AND HD.`status` = '1' AND HECD.`entry_cost_cancellation_status` = '1' AND HD.`itinerary_plan_ID` = '$itinerary_plan_ID' AND HD.`route_hotspot_ID` = '$route_hotspot_ID' AND HD.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_DETAILS: " . sqlERROR_LABEL());

                                                                                                                    $hotspot_cancalled_count = sqlNUMOFROW_LABEL($select_hotspot_cancalled_details);

                                                                                                                    if ($hotspot_cancalled_count <= $total_guest_count):
                                                                                                                        $balance_guest_count = $total_guest_count - $hotspot_cancalled_count;
                                                                                                                    else:
                                                                                                                        $balance_guest_count = 0;

                                                                                                                    endif;

                                                                                                                    ?>
                                                                                                                    <span class="mb-0 fs-6 text-gray">Booked<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_guest_count; ?></span></span>
                                                                                                                    <span class="mb-0 fs-6 text-gray">Cancelled<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $hotspot_cancalled_count; ?></span></span>
                                                                                                                    <span class="mb-0 fs-6 text-gray">Balance<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $balance_guest_count; ?></span></span>
                                                                                                                </div>
                                                                                                                <?php /* <div class="text-end ms-2"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer" onclick="show_REMOVE_ITINEARY_ROUTE_HOTSPOT_MODAL('<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $hotspot_ID; ?>')" data-toggle="tooltip" placement="top" title="Delete Hotspot"></i>
                                                                                                                </div> */ ?>

                                                                                                            </div>
                                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                                <?= $hotspot_description ?>
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div class="col-md-3 d-flex justify-content-end position-relative">
                                                                                                            <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showHOTSPOTGALLERY('<?= $hotspot_ID; ?>');">
                                                                                                                <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                            </div>
                                                                                                            <?php if ($hotspot_video_url) : ?>
                                                                                                                <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                    <a href="<?= $hotspot_video_url; ?>" target="_blank"><img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg"></a>
                                                                                                                </div>
                                                                                                            <?php endif; ?>
                                                                                                            <img src="<?= $image_src; ?>" class="rounded-3" width="185px" height="115px" />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                        <div class="d-flex align-items-center gap-4">
                                                                                                            <p class="mt-2 mb-0">
                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                -
                                                                                                                <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                            </p>
                                                                                                            <?php if ($hotspot_amout > 0) : ?>
                                                                                                                <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i><?= general_currency_symbol . ' ' . number_format($hotspot_amout, 2); ?>
                                                                                                                </p>
                                                                                                            <?php endif; ?>
                                                                                                            <p class="mt-2 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i><?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                            </p>
                                                                                                            <?php $get_activity_count = getHOTSPOT_ACTIVITY_DETAILS($hotspot_ID, 'get_activity_count');
                                                                                                            if ($get_activity_count > 0) :
                                                                                                            ?>
                                                                                                                <?php /* <button type="button" id="addactivitybtn_<?= $hotspot_ID . '_' . $itinerary_route_ID; ?>" onclick="showHOTSPOTACTIVITYLIST('<?= $route_hotspot_ID; ?>','<?= $hotspot_ID; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>');" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary">
                                                                                    <span class="tf-icons ti ti-circle-plus ti-xs me-1"></span>
                                                                                    Add Activity
                                                                                </button> */ ?>
                                                                                                                <!-- <span id="show_list_of_hotspots_activity_<?= $hotspot_ID . '_' . $itinerary_route_ID; ?>"></span> -->
                                                                                                            <?php endif; ?>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <?php
                                                                                                    $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_confirmed_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                                                                                                    $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                                                                                                    if ($total_hotspot_activity_num_rows_count > 0) :
                                                                                                    ?>
                                                                                                        <div class="ps-5 mt-2 border-top">
                                                                                                            <h5 class="m-2">Activity</h5>
                                                                                                            <ul class="timeline pt-3">
                                                                                                                <?php
                                                                                                                while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_activity_details)) :
                                                                                                                    $route_activity_ID = $fetch_hotspot_activity_data['route_activity_ID'];
                                                                                                                    $activity_order = $fetch_hotspot_activity_data['activity_order'];
                                                                                                                    $activity_ID = $fetch_hotspot_activity_data['activity_ID'];
                                                                                                                    $activity_amout = $fetch_hotspot_activity_data['activity_amout'];
                                                                                                                    $activity_traveling_time = $fetch_hotspot_activity_data['activity_traveling_time'];
                                                                                                                    $activity_start_time = $fetch_hotspot_activity_data['activity_start_time'];
                                                                                                                    $activity_end_time = $fetch_hotspot_activity_data['activity_end_time'];
                                                                                                                    $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                                                                                                    $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                                                                                                    $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                                                                                                    $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/activity_gallery/' . $get_first_activity_image_gallery_name;
                                                                                                                    $image_path = BASEPATH . '/uploads/activity_gallery/' . $get_first_activity_image_gallery_name;
                                                                                                                    $default_image = BASEPATH . 'uploads/no-photo.png';

                                                                                                                    if ($get_first_activity_image_gallery_name):
                                                                                                                        // Check if the image file exists
                                                                                                                        $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                                                                                                                    else:
                                                                                                                        $image_src = $default_image;
                                                                                                                    endif;

                                                                                                                ?>
                                                                                                                    <li class="timeline-item pb-4" style="border-left:1px dashed">
                                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                                            <i class="ti ti-send rounded-circle text-primary"></i>
                                                                                                                        </span>
                                                                                                                        <div class="card p-4" style="box-shadow: none !important;">
                                                                                                                            <div class="row">
                                                                                                                                <div class="col-10">
                                                                                                                                    <div class="d-flex align-items-center">
                                                                                                                                        <h5 class="mb-0"><?= $activity_title; ?></h5>
                                                                                                                                        <div class="text-end mx-2">|
                                                                                                                                        </div>
                                                                                                                                        <div class="text-end">
                                                                                                                                            <?php $total_guest_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_person_count');

                                                                                                                                            $select_hotspot_cancalled_details = sqlQUERY_LABEL("SELECT AD.`itinerary_plan_ID`, AD.`hotspot_ID`, AD.`route_hotspot_ID`, AD.`activity_ID`, AD.`route_activity_ID`, AECD.`traveller_type`, AECD.`entry_cost_cancellation_status` FROM `dvi_cancelled_itinerary_route_activity_details` AD LEFT JOIN `dvi_cancelled_itinerary_route_activity_entry_cost_details` AECD ON AECD.`cancelled_itinerary_ID` = AD.`cancelled_itinerary_ID` WHERE AD.`deleted` = '0' AND AD.`status` = '1' AND AECD.`entry_cost_cancellation_status` = '1' AND AD.`itinerary_plan_ID` = '$itinerary_plan_ID' AND AD.`route_hotspot_ID` = '$route_hotspot_ID' AND AD.`hotspot_ID` = '$hotspot_ID' AND AD.`route_activity_ID` = '$route_activity_ID' AND AD.`activity_ID` = '$activity_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_DETAILS: " . sqlERROR_LABEL());

                                                                                                                                            $hotspot_cancalled_count = sqlNUMOFROW_LABEL($select_hotspot_cancalled_details);

                                                                                                                                            if ($hotspot_cancalled_count <= $total_guest_count):
                                                                                                                                                $balance_guest_count = $total_guest_count - $hotspot_cancalled_count;
                                                                                                                                            else:
                                                                                                                                                $balance_guest_count = 0;

                                                                                                                                            endif; ?>
                                                                                                                                            <span class="mb-0 fs-6 text-gray">Booked<span style="background: #c1c1c1" class="badge badge-center fw-semi-bold text-gray rounded-pill mx-2"><?= $total_guest_count; ?></span></span>
                                                                                                                                            <span class="mb-0 fs-6 text-gray">Cancelled<span style="background: #c1c1c1" class="badge badge-center fw-semi-bold text-gray rounded-pill mx-2"><?= $hotspot_cancalled_count; ?></span></span>
                                                                                                                                            <span class="mb-0 fs-6 text-gray">Balance<span style="background: #c1c1c1" class="badge badge-center fw-semi-bold text-gray rounded-pill ms-2"><?= $balance_guest_count; ?></span></span>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <h5 class="mb-2">
                                                                                                                                    </h5>
                                                                                                                                    <p><?= $activity_description; ?>
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="col-2 position-relative">
                                                                                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showHOTSPOTACTIVITYGALLERY('<?= $activity_ID; ?>');">
                                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                                    </div>
                                                                                                                                    <div class="itinerary-video-icon cursor-pointer d-none" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                                    </div>
                                                                                                                                    <img src="<?= $image_src; ?>" class="rounded-3" alt="Hotspot Img" width="140" height="100px">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                                <div class="d-flex align-items-center gap-3">
                                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                        <?= date('h:i A', strtotime($activity_start_time)); ?>
                                                                                                                                        -
                                                                                                                                        <?= date('h:i A', strtotime($activity_end_time)); ?>
                                                                                                                                    </p>
                                                                                                                                    <?php if ($activity_amout > 0) : ?>
                                                                                                                                        <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i><?= general_currency_symbol . ' ' . number_format($activity_amout, 2); ?>
                                                                                                                                        </p>
                                                                                                                                    <?php endif; ?>
                                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                                        <i class="ti ti-hourglass-high mb-1"></i>
                                                                                                                                        <?= formatTimeDuration($activity_traveling_time); ?>
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <?php /* <div class="text-end"><i onclick="show_REMOVE_ITINEARY_ROUTE_HOTSPOT_ACTIVITY_MODAL('<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $hotspot_ID; ?>','<?= $route_hotspot_ID; ?>','<?= $activity_ID; ?>')" class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div> */ ?>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </li>
                                                                                                                <?php endwhile; ?>
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    endif; ?>
                                                                                                </div>
                                                                                                <div class="itineray-daywise-border"></div>
                                                                                            </li>
                                                                                        <?php endif; ?>
                                                                                        <?php /* if (($get_route_last_hotspot_ID == $route_hotspot_ID) && ($total_itinerary_plan_route_details_count != $itineary_route_count)) :
                    ?>
                    <li class="mb-3">
                        <div class="d-flex align-items-center" id="addhotspot_<?= $itinerary_route_ID; ?>" onclick="showHOTSPOTLIST('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>')">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-plus text-primary ti-sm"></i></span>
                            </div>
                            <div class="m-0 text-primary fs-6 cursor-pointer" id="toggleContainer">Click to Add Hotspot </div>
                        </div>
                        <div class="d-flex align-items-center d-none" id="closehotspot_<?= $itinerary_route_ID; ?>" onclick="closeHOTSPOTLIST('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>')">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-minus text-danger ti-sm"></i></span>
                            </div>
                            <div class="m-0 text-danger fs-6 cursor-pointer" id="toggleContainer">Click to Close Hotspot </div>
                        </div>
                        <div class="itineray-daywise-border"></div>
                    </li>
                    <li class="mb-3" id="add_hotspot_itinerary_<?= $itinerary_route_ID; ?>">
                        <span id="show_list_of_hotspots_<?= $itinerary_route_ID; ?>"></span>
                    </li>
                <?php endif; */ ?>
                                                                                        <?php if (($get_route_last_hotspot_ID == $route_hotspot_ID) && ($total_itinerary_plan_route_details_count != $itineary_route_count)) : ?>
                                                                                            <?php /* <li class="mb-3">
                                    <div class="d-flex align-items-center" id="addhotspot_<?= $itinerary_route_ID; ?>" onclick="showHOTSPOTLIST('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>')">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-plus text-primary ti-sm"></i></span>
                                        </div>
                                        <div class="m-0 text-primary fs-6 cursor-pointer">Click to Add Hotspot </div>
                                    </div>
                                    <div class="itineray-daywise-border"></div>
                                </li>
                                <li class="mb-3 d-none" id="add_hotspot_itinerary_<?= $itinerary_route_ID; ?>">
                                    <span id="show_list_of_hotspots_<?= $itinerary_route_ID; ?>"></span>
                                </li> */ ?>
                                                                                        <?php endif; ?>

                                                                                        <?php if ($item_type == 5) : ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                                    <div class="row">
                                                                                                        <!-- RETURN TO NEXT VISTING PLACE LOCATION -->
                                                                                                        <div class="col-12 ps-0 d-flex align-items-center">
                                                                                                            <div class="avatar ms-2 me-3">
                                                                                                                <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                            </div>
                                                                                                            <div class="mt-1">
                                                                                                                <h6 class="m-0">Travelling from <b class="text-primary"><?= $previous_hotspot_name; ?></b>
                                                                                                                    to <b class="text-primary"><?= getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></b>.
                                                                                                                    <span><i class="ti ti-clock mx-1 mb-1"></i>
                                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                        -
                                                                                                                        <?= date('h:i A', strtotime($hotspot_end_time)); ?><i class="ti ti-route mx-1 mb-1"></i>
                                                                                                                        <?= $hotspot_travelling_distance; ?>
                                                                                                                        KM
                                                                                                                        <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                        (This may vary due to traffic
                                                                                                                        conditions)</span>
                                                                                                                </h6>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- RETURN TO HOTEL DIV -->
                                                                                                        <div class="col-11 ps-0 d-none">
                                                                                                            <div class="mt-1">
                                                                                                                <h6 class="m-0">Travelling to
                                                                                                                    <?= getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label'); ?>.
                                                                                                                </h6>
                                                                                                                <div class="d-flex gap-3">
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                        -
                                                                                                                        <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-route me-1 mb-1"></i>
                                                                                                                        <?= $hotspot_travelling_distance; ?>
                                                                                                                        KM
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                                        <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                        (This may vary due to traffic
                                                                                                                        conditions)
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="itineray-daywise-border"></div>
                                                                                            </li>
                                                                                        <?php endif; ?>

                                                                                        <?php if ($item_type == 6) :
                                                                                            $get_hotel_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
                                                                                            $get_hotel_address = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'hotel_address');
                                                                                            if ($get_hotel_title) :
                                                                                                $get_hotel_name = $get_hotel_title;
                                                                                            else :
                                                                                                $get_hotel_name = 'N/A';
                                                                                                $get_hotel_address = 'N/A';
                                                                                            endif;
                                                                                        ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <h6 class="m-0"><?= $get_hotel_name; ?></h6>
                                                                                                        <div class="d-flex align-items-center gap-3 mt-1">
                                                                                                            <p class="mb-0">
                                                                                                                <i class="ti ti-clock me-1 mb-1"></i><?= date('h:i A', timestamp: strtotime($hotspot_start_time)); ?>
                                                                                                            </p>
                                                                                                            <?php if ($get_hotel_address) : ?>
                                                                                                                <p class="m-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i><?= $get_hotel_address; ?>
                                                                                                                </p>
                                                                                                            <?php endif; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>
                                                                                        <?php endif; ?>

                                                                                        <?php if ($item_type == 7 && $total_itinerary_plan_route_hotspot_details_count == $itineary_route_hotspot_count) : ?>
                                                                                            <li class="mb-3">
                                                                                                <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                                    <div class="row">
                                                                                                        <div class="col-1 pe-0">
                                                                                                            <div class="avatar mt-2 me-3">
                                                                                                                <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-11 ps-0">
                                                                                                            <div class="mt-1">
                                                                                                                <h6 class="m-0">Return to
                                                                                                                    <?= getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>.
                                                                                                                </h6>
                                                                                                                <div class="d-flex gap-3">
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                                                        -
                                                                                                                        <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0">
                                                                                                                        <i class="ti ti-route me-1 mb-1"></i>
                                                                                                                        <?= $hotspot_travelling_distance; ?>
                                                                                                                        KM
                                                                                                                    </p>
                                                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                                        <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                        (This may vary due to traffic
                                                                                                                        conditions)
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div class="d-flex gap-3">
                                                                                                                    <p class="mt-1 mb-0"><i class="ti ti-clock me-1 mb-1"></i>Including
                                                                                                                        Depature Type Buffer Time of
                                                                                                                        <?= formatTimeDuration($itinerary_travel_type_buffer_time); ?>
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>
                                                                                        <?php endif; ?>
                                                                                    <?php endwhile; ?>
                                                                                </ul>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        endwhile;
                                                    endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row mt-3">
                                <!-- START HOTEL AND VEHICLE AND OVERALL COST LIST -->
                                <div class="col-md-12">

                                    <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                        <!-- START HOTEL LIST -->
                                        <span id="showHOTELINFO"></span>
                                        <!-- END OF THE HOTEL LIST -->
                                    <?php endif; ?>

                                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                        <!-- START VEHICLE LIST -->
                                        <span id="showVEHICLEINFO"></span>
                                        <!-- END OF THE VEHICLE LIST -->
                                    <?php endif; ?>

                                    <span id="showINCIDENTALESSPENSES"></span>

                                    <span id="showOVERALLCOSTINFO"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- HOTSPOT ADD INFO START -->

                    <!-- END OF THE HOTEL AND VEHICLE LIST -->

                    <!-- Modal Structure -->
                    <div class="modal fade" id="hotspotModal" tabindex="-1" aria-labelledby="hotspotModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="row w-100 align-items-center mb-3">
                                        <div class="col-md-9">
                                            <h5 class="m-0" id="hotspotModalLabel">Hotspot List</h5>
                                        </div>
                                        <div class="col-md-3"><input type="text" id="hotspotSearch" class="form-control" placeholder="Search Hotspot..." onkeyup="filterHotspots()"></div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div id="modalHotspotContent">
                                    <!-- Hotspot list will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Structure -->
            <div class="modal fade" id="hotspotActivityModal" tabindex="-1" aria-labelledby="hotspotActivityModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row w-100 align-items-center mb-3">
                                <div class="col-md-9">
                                    <h5 class="m-0" id="hotspotActivityModalLabel">Activity List</h5>
                                </div>
                                <div class="col-md-3"><input type="text" id="activitySearch" class="form-control" placeholder="Search Activity..." onkeyup="filterActivity()"></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Dynamic content will be loaded here -->
                        <div id="modalactivityContent"></div>
                    </div>
                </div>
            </div>
            <!-- Modal Structure for Itinerary cancellation -->
            <div class="modal fade" id="showITINERARYCANCELLATIONMODAL" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                    <div class="modal-content p-3 p-md-5">
                        <div class="receiving-cancel-itinerary-form-data">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addINCIDENTALCHARGEFORM" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                    <div class="modal-content p-3 p-md-5">
                        <div class="receiving-incidentialcharge-form-data">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('.accordion-collapse').on('show.bs.collapse', function() {
                        $(this).closest('.accordion-item').find('.accordion-header').addClass(
                            'sticky-accordion-element');
                    });
                    $('.accordion-collapse').on('hide.bs.collapse', function() {
                        $(this).closest('.accordion-item').find('.accordion-header').removeClass(
                            'sticky-accordion-element');
                    });
                });

                $(document).ready(function() {
                    $(".form-select").selectize();
                    $('body').tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                    $(function() {
                        $('[data-toggle="tooltip"]').tooltip()
                    })
                    <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                        showDAYWISE_HOTEL_DETAILS('<?= $itinerary_plan_ID; ?>');
                        showOVERALL_COST_DETAILS('<?= $itinerary_plan_ID; ?>');
                        showINCIDENTAL_EXPENSES_DETAILS('<?= $itinerary_plan_ID; ?>');
                    <?php endif; ?>
                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                        showDAYWISE_VEHICLE_DETAILS('<?= $itinerary_plan_ID; ?>');
                        showOVERALL_COST_DETAILS('<?= $itinerary_plan_ID; ?>');
                        showINCIDENTAL_EXPENSES_DETAILS('<?= $itinerary_plan_ID; ?>');
                    <?php endif; ?>
                });

                $(document).ready(function() {
                    // Add event listener to the tooltip container
                    $('.tooltip-container').tooltip({
                        trigger: 'manual', // Set the trigger to manual
                        animation: false, // Disable animation
                        html: true, // Enable HTML in tooltip
                        delay: {
                            show: 100,
                            hide: 0
                        } // Adjust delay if needed
                    }).on('mouseenter', function() {
                        var $tooltip = $(this);

                        // Get the tooltip content from the title attribute
                        var tooltipContent = $tooltip.attr('title');

                        // Only proceed if the tooltip content is defined
                        if (tooltipContent) {
                            // Split the title content into an array of list items
                            tooltipContent = tooltipContent.split('<br>');

                            // Clear the tooltip content
                            $tooltip.attr('title', '');

                            // Loop through each list item and show it sequentially
                            var i = 0;
                            var interval = setInterval(function() {
                                if (i < tooltipContent.length) {
                                    // Set the tooltip content with the current list item
                                    $tooltip.attr('title', tooltipContent[i]);

                                    // Show the tooltip
                                    $tooltip.tooltip('show');

                                    i++;
                                } else {
                                    // Clear the interval once all list items are shown
                                    clearInterval(interval);
                                }
                            }, 500); // Adjust interval duration if needed
                        }
                    }).on('mouseleave', function() {
                        // Hide the tooltip when mouse leaves the container
                        $(this).tooltip('hide');
                    });
                });

                function showINCIDENTALMODAL(ITINERARY_ID) {
                    $('.receiving-incidentialcharge-form-data').load('engine/ajax/ajax_incidental_expenses.php?type=show_form&ITINERARY_ID=' + ITINERARY_ID, function() {
                        const container = document.getElementById("addINCIDENTALCHARGEFORM");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                }

                function showCANCEL_ITINERARY_MODAL(ITINERARY_ID) {
                    $('.receiving-cancel-itinerary-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_cancellation_modal&ITINERARY_ID=' + ITINERARY_ID, function() {
                        const container = document.getElementById("showITINERARYCANCELLATIONMODAL");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                }

                function showHOTSPOTGALLERY(hotspot_ID) {
                    $('.receiving-gallery-modal-info-form-data').load(
                        'engine/ajax/ajax_latest_itineary_show_hotspot_gallery.php?type=show_form&hotspot_ID=' + hotspot_ID,
                        function() {
                            const container = document.getElementById("GALLERYMODALINFODATA");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_confirmed_itineary_vehicle_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                        },
                        success: function(response) {
                            $('#showVEHICLEINFO').html('');
                            $('#showVEHICLEINFO').html(response);
                        }
                    });
                }

                function showOVERALL_COST_DETAILS(itinerary_plan_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_confirmed_itineary_overall_cost_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                        },
                        success: function(response) {
                            $('#showOVERALLCOSTINFO').html('');
                            $('#showOVERALLCOSTINFO').html(response);
                        }
                    });
                }

                function showINCIDENTAL_EXPENSES_DETAILS(itinerary_plan_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_incidental_expenses_list.php?type=show_form",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID,
                        },
                        success: function(response) {
                            $('#showINCIDENTALESSPENSES').html('');
                            $('#showINCIDENTALESSPENSES').html(response);
                        }
                    });
                }

                function showDAYWISE_HOTEL_DETAILS(itinerary_plan_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_confirmed_itineary_hotel_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                        },
                        success: function(response) {
                            $('#showHOTELINFO').html('');
                            $('#showHOTELINFO').html(response);
                        }
                    });
                }
            </script>
    <?php
    endif;

else :
    echo "Request Ignored";
endif;
