<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/

include_once('jackus.php');
// admin_reguser_protect();
if ($_GET['type'] == 'show_itinerary') :

    $decrypt_itinerary_plan_id = $_GET['itinerary_plan_ID'];

    $itinerary_plan_ID = Encryption::Decode($decrypt_itinerary_plan_id, SECRET_KEY);

    $itinerary_no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
    $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
    $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');
    
    if (isset($_GET['gtype'])):
        $selected_group_type = $_GET['gtype'];
        $selected_group_type = Encryption::Decode($selected_group_type, SECRET_KEY);
    else:
        $selected_group_type = 1;
    endif;

    $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `agent_id`,`itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
    $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
    if ($total_itinerary_plan_details_count > 0) :
        while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
            $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
            $departure_location = $fetch_itinerary_plan_data['departure_location'];
            $agent_id = $fetch_itinerary_plan_data['agent_id'];
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

    $select_agent_details_query = sqlQUERY_LABEL("SELECT `agent_ID`, `itinerary_margin_discount_percentage`, `agent_margin`, `agent_margin_gst_type`, `agent_margin_gst_percentage` FROM `dvi_agent` WHERE `deleted` = '0' and `agent_ID` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
    $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
    if ($total_agent_details_count > 0) :
        while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
            $itinerary_margin_discount_percentage = $fetch_agent_data['itinerary_margin_discount_percentage'];
            $agent_margin = $fetch_agent_data['agent_margin'];
            $agent_margin_gst_type = $fetch_agent_data['agent_margin_gst_type'];
            $agent_margin_gst_percentage = $fetch_agent_data['agent_margin_gst_percentage'];
        endwhile;
    endif;

    $TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
    $total_net_charge = round((getITINEARY_COST_DETAILS($itinerary_plan_ID, $selected_group_type, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES));

    if ($agent_margin_gst_type == 1):
        $agent_margin_gst_label = 'Inclusive';
        $get_agent_margin = ($total_net_charge * $agent_margin) / 100;
        $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
        $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
        $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
    else:
        $agent_margin_gst_label = 'Exclusive';
        $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
        $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
        $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
    endif;

    $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$selected_group_type' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
    $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
    if ($total_hotel_details_count > 0) :
        while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
            $hotel_margin_rate += $fetch_agent_data['hotel_margin_rate'];
        endwhile;
    endif;

    $select_agent_details_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
    $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
    if ($total_agent_details_count > 0) :
        while ($fetch_agent_details_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
            $agent_logo = $fetch_agent_details_data['site_logo'];
        endwhile;
    endif;

    $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount`, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
    $total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
    if ($total_vehicle_details_count > 0) :
        while ($fetch_vendor_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
            $total_vehicle_qty = $fetch_vendor_data['total_vehicle_qty'];
            $vendor_margin_amount = $fetch_vendor_data['vendor_margin_amount'];
            $total_vehicle_margin += $vendor_margin_amount * $total_vehicle_qty;
        endwhile;
    endif;

    $total_margin_without_percentage = $total_agent_margin + $hotel_margin_rate + $total_vehicle_margin;
    $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;
    $total_discount_amount = $total_net_amount;

    $agent_margin_value = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin');
?>
    <!DOCTYPE html>
    <html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact layout-menu-collapsed " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>View Itinerary - <?= $_SITETITLE; ?></title>

        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

        <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

        <!-- Icons -->
        <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
        <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
        <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="assets/css/demo.css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
        <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
        <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

        <!-- Page CSS -->
        <link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

        <!-- Helpers -->
        <script src="assets/vendor/js/helpers.js"></script>
        <script src="assets/js/config.js"></script>
        <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
        <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
        <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
        <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
        <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

        <!-- Row Group CSS -->
        <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
        <!-- Form Validation -->
        <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
        <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
        <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="assets/css/itineary_custom_style.css" />
        <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
        <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
        <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
        <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
        <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
        <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
        <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
        <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
        <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

        <style>
            /* Hide the "All Day" text in Calendar list view */
            .sticky-accordion-element {
                position: sticky;
                top: 211px;
                /* z-index: 999; */
                z-index: 860;
            }

            .itinerary-header-sticky-element {
                position: sticky;
                top: 117px;
                background-color: #ffffff;
                z-index: 990 !important;
                box-shadow: 0px 0px 4px 0px rgba(135, 70, 180, 0.2) !important;
            }

            .itinerary-header-title-sticky {
                position: sticky;
                top: 2px;
                background-color: #ffffff;
                z-index: 1001;
            }

            .Via-route-title {
                white-space: nowrap;
                width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .location-description-container {
                background-color: #ebedff !important;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>
        <div class="layout-wrapper layout-content-navbar ">
            <div class="layout-container">

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Menu -->

                    <!-- / Menu -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y pt-3">
                            <div class="row mt-2" id="pdf-container">
                                <div class="col-md-12">
                                    <div class="card itinerary-header-title-sticky p-3 py-2">
                                        <div class="d-flex justify-content-between align-items-center position-relative">
                                            <div class="me-2 mb-2">
                                                <?php if ($agent_logo): ?>
                                                    <img src='<?= BASEPATH ?>uploads/agent_gallery/<?= $agent_logo ?>' width="100px" />
                                                <?php else: ?>
                                                    <img src='<?= PUBLICPATH ?>assets/img/logo-preview.png' width="100px" />
                                                <?php endif ?>
                                            </div>
                                            <h5 class="m-0 position-absolute top-50 start-50 translate-middle">Tour Itinerary Plan</h5>
                                            <div class="ms-auto d-flex align-items-center">
                                                <!-- <a href="<?= BASEPATH ?>" type="button" class="btn btn-sm btn-label-github waves-effect ps-3 me-2">
                                                    <i class="ti ti-sm ti-logout me-1"></i> Login
                                                </a> -->
                                                <div class="btn-group" id="hover-dropdown-demo">
                                                    <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" data-trigger="hover">Share</button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="CopyShareLink()">
                                                                <i class="ti ti-circles-relation me-1"></i> Copy Link
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="ShareWhatsApp()">
                                                                <i class="ti ti-brand-whatsapp me-1"></i> on WhatsApp
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="ShareEmail()">
                                                                <i class="ti ti-mail me-1"></i> Share via Email
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if ($guide_for_itinerary == 1) :
                                        $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `guide_type`, `guide_language`, `guide_cost` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                        $total_itinerary_guide_route_count_for_whole_itineary = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                        while ($fetch_itinerary_route_guide_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                            $route_guide_ID = $fetch_itinerary_route_guide_data['route_guide_ID'];
                                            $guide_type = $fetch_itinerary_route_guide_data['guide_type'];
                                            $guide_language = $fetch_itinerary_route_guide_data['guide_language'];
                                            $guide_cost = $fetch_itinerary_route_guide_data['guide_cost'];
                                        endwhile;
                                        $total_guide_charges = $guide_cost;
                                    endif;
                                    ?>

                                    <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary p-3 mt-3">
                                        <div class="d-flex align-items-center">

                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
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
                                                    </div>
                                                </div>

                                                <!-- Lower row: Room details -->
                                                <div>
                                                    <span class="mb-0 fs-6 text-gray">Room Count<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $preferred_room_count; ?></span></span>
                                                    <span class="mb-0 fs-6 text-gray">Extra Bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_extra_bed; ?></span></span>
                                                    <span class="mb-0 fs-6 text-gray">Child with bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_child_with_bed; ?></span></span>
                                                    <span class="mb-0 fs-6 text-gray">Child without bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_child_without_bed; ?></span></span>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <div class="mb-2">
                                                    <span class="mb-0 fs-6 text-gray">Adults<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_adult; ?></span></span>
                                                    <span class="mb-0 fs-6 text-gray">Child<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_children; ?></span></span>
                                                    <span class="mb-0 fs-6 text-gray">Infants<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_infants; ?></span></span>
                                                </div>
                                                <h5 class="card-title mb-sm-0 overall_trip_costs">Overall Trip Cost : <b class="text-primary fs-4"><span id="overall_trip_cost"><?= general_currency_symbol . ' ' . number_format(round($total_discount_amount), 2); ?></span></b>
                                                </h5>
                                            </div>
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
                                                                    <?php if ($guide_for_itinerary == 1 && $total_itinerary_guide_route_count_for_whole_itineary > 0) : ?>
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
                                                                                        <h5 class="text-primary m-0">
                                                                                            <?= general_currency_symbol . ' ' . number_format($total_guide_charges, 2); ?>
                                                                                        </h5>
                                                                                    </div>
                                                                                    <span class="cursor-pointer" onclick="addGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','','<?= $selected_group_type; ?>')"><i class="ti-sm ti ti-edit mb-1 ms-2"></i></span>
                                                                                    <span class="cursor-pointer" onclick="deleteGUIDE('<?= $route_guide_ID; ?>', '1', '<?= $itinerary_plan_ID; ?>', '','','<?= $selected_group_type; ?>')"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <!-- Menu Accordion -->
                                                                    <div id="accordionIcon" class="accordion accordion-without-arrow">
                                                                        <?php
                                                                        $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                                                        $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                                                        $last_destination_city = NULL;
                                                                        $show_day_trip_available = false;

                                                                        if ($total_itinerary_plan_route_details_count > 0) :
                                                                            // $itineary_route_count = 0;
                                                                            while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                                                $itineary_route_count++;
                                                                                $item_type_check = 0;
                                                                                $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                                                $location_id = $fetch_itinerary_plan_route_data['location_id'];
                                                                                $location_name = $fetch_itinerary_plan_route_data['location_name'];
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

                                                                                $start_day_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                                $start_day_time_add_attr = "readonly";

                                                                                $day_end_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                                                $day_end_time_add_attr = "readonly";
                                                                        ?>
                                                                                <!-- DAY WISE ACCORDION -->
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                                                        <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-<?= $itineary_route_count; ?>" aria-controls="accordionIcon-<?= $itineary_route_count; ?>">
                                                                                            <div class="w-100 itinerary_daywise_list_tab bg-white">
                                                                                                <div class="row">
                                                                                                    <div class="col-sm-3 col-md-3 col-xxl-3 d-flex align-items-center">
                                                                                                        <div class="avatar-wrapper">
                                                                                                            <div class="avatar me-2">
                                                                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <h6 class="mb-0"> <b>DAY <?= $itineary_route_count; ?></b> -
                                                                                                            <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                                                    <div class="col-sm-7 col-md-7 col-xxl-7 text-start d-flex align-items-center">
                                                                                                        <h6 class="mb-0 d-inline-block text-truncate d-flex align-items-center" data-toggle="tooltip" placement="top" title="<?= $location_name; ?>"><?= $location_name; ?></h6>
                                                                                                        <?php if ($get_via_route_details_without_format) : ?>
                                                                                                            <div class="Via-route-title" data-bs-html="true" data-toggle="tooltip" placement="top" title="<?= $get_via_route_details_with_format; ?>">&nbsp;<i class="ti ti-arrow-big-right-lines"></i>&nbsp;<?= ($get_via_route_details_without_format); ?>&nbsp;</div>
                                                                                                            <i class="ti ti-arrow-big-right-lines"></i>
                                                                                                        <?php else : ?>
                                                                                                            <span>&nbsp;<i class="ti ti-arrow-big-right-lines"></i>&nbsp;</span>
                                                                                                        <?php endif; ?>
                                                                                                        <!-- SHOW VIA ROUTE INFO -->
                                                                                                        <div class="bg-primary btn-sm text-white py-1 fs-6 mx-3 rounded-1 tooltip-container d-none" id="via_route_tooltip_container" data-bs-html="true" data-toggle="tooltip" placement="top" aria-label="<?= $get_via_route_details_without_format ?>" title="<?= $get_via_route_details_with_format; ?>">
                                                                                                            <i class="ti ti-route ti-tada-hover mx-3" style="font-size: 18px;"></i>
                                                                                                        </div>
                                                                                                        <h6 class="m-0 d-inline-block text-truncate" data-toggle="tooltip" placement="top" title="<?= $next_visiting_location; ?>"><?= $next_visiting_location; ?></h6>
                                                                                                    </div>
                                                                                                    <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                                                    <div class="d-flex align-items-center col-md-2 justify-content-end">
                                                                                                        <?php /* <h6 class="m-0 text-blue-color" id="start_time_<?= $itinerary_route_ID; ?>"><?= date('h:i A', strtotime($route_start_time)); ?> </h6> <i class="ti ti-arrows-diff text-blue-color mx-2"></i> <h6 class="m-0 text-blue-color" id="end_time_<?= $itinerary_route_ID; ?>"> <?= date('h:i A', strtotime($route_end_time)); ?></h6> */ ?>
                                                                                                        <?php if ($itinerary_preference != 1) : ?>
                                                                                                            <img src="assets/img/kilometer.png" />
                                                                                                            <h6 class="m-0 text-blue-color">
                                                                                                                <?= number_format(get_ASSIGNED_VEHICLE_ITINEARY_PLAN_DAYWISE_KM_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_total_kms'), 2); ?>
                                                                                                                KM</h6>
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

                                                                                                            <div> <img class="me-1" src="assets/img/svg/location.svg" width="28px" /></div>

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
                                                                                                                <span class="<?= $start_day_time_add_class; ?>"><?= date('h:i A', strtotime($route_start_time)); ?></span>
                                                                                                            </div>
                                                                                                            <div class="px-2">
                                                                                                                <i class="ti ti-arrows-diff"></i>
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <span class="<?= $day_end_time_add_class; ?>"><?= date('h:i A', strtotime($route_end_time)); ?></span>
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
                                                                                                <?php
                                                                                                $pricebook_true = check_guide_pricebook($itinerary_route_date, $total_pax_count);

                                                                                                if ($guide_for_itinerary == 0 && $pricebook_true) :
                                                                                                    $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
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
                                                                                                ?>
                                                                                                        <div class="col-12 mt-2" id="itinerary-guidecontainer">
                                                                                                            <div class="itineray-guide-container d-flex justify-content-between align-items-center py-2 px-4">
                                                                                                                <div>
                                                                                                                    <div class="my-2">
                                                                                                                        <h6 class="m-0" style="color:#4d287b;">Guide
                                                                                                                            Language - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                                                                        </h6>
                                                                                                                    </div>
                                                                                                                    <div class="my-2">
                                                                                                                        <h6 class="m-0" style="color:#4d287b;">Slot Timing -
                                                                                                                            <span class="text-primary"><?= 'Slot Timing - ' . getSLOTTYPE($guide_slot, 'label'); ?></span>
                                                                                                                        </h6>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="d-flex">
                                                                                                                    <div>
                                                                                                                        <h5 class="text-primary m-0">
                                                                                                                            <?= general_currency_symbol . ' ' . number_format($guide_cost, 2); ?>
                                                                                                                        </h5>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                <?php endif;
                                                                                                endif; ?>

                                                                                                <?php
                                                                                                $select_itinerary_plan_route_hotspot_availability_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `status` = '1' AND `item_type` IN ('6','7') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                                $total_itinerary_plan_route_hotspot_availability_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                                                $fetch_hotspot_availability = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                                                $get_route_last_hotspot_ID = $fetch_hotspot_availability['route_hotspot_ID'];

                                                                                                $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time`, ROUTE_HOTSPOT.`allow_break_hours`, ROUTE_HOTSPOT.`allow_via_route`, ROUTE_HOTSPOT.`via_location_name` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
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
                                                                                                                $item_type_check++;
                                                                                                                if ($last_day_ending_location == NULL) :
                                                                                                                    $last_day_ending_location =  $next_visiting_location;
                                                                                                                endif;


                                                                                                                if ($show_day_trip_available) : ?>
                                                                                                                    <li class="mb-3">
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
                                                                                                                    </li>
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
                                                                                                                    <li class="mb-3">
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
                                                                                                                    </li>
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
                                                                                                                                        to <b class="text-primary"><?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></b>.
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

                                                                                                                            </div>
                                                                                                                        </div>

                                                                                                                        <?php
                                                                                                                        $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
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
                                                                                                                                                        <h5 class="mb-2">
                                                                                                                                                            <?= $activity_title; ?></h5>
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
                                                                                                                                                    <div class="text-end"><i onclick="show_REMOVE_ITINEARY_ROUTE_HOTSPOT_ACTIVITY_MODAL('<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $hotspot_ID; ?>','<?= $route_hotspot_ID; ?>','<?= $activity_ID; ?>')" class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i>
                                                                                                                                                    </div>
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
                                                                                                            <!--  <?php if (($get_route_last_hotspot_ID == $route_hotspot_ID) && ($total_itinerary_plan_route_details_count != $itineary_route_count)) :
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
                                                                        <?php endif; ?> -->


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
                                                                                                                                        to <b class="text-primary"><?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></b>.
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
                                                                                                                                        <?= getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label'); ?>.
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
                                                                                                                $get_hotel_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
                                                                                                                $get_hotel_address = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'hotel_address');
                                                                                                                if ($get_hotel_title) :
                                                                                                                    $get_hotel_name = $get_hotel_title;
                                                                                                                else :
                                                                                                                    $get_hotel_name = 'N/A';
                                                                                                                endif;
                                                                                                                $get_hotel_name = getGLOBALSETTING('itinerary_hotel_return');
                                                                                                                $get_hotel_address = 'N/A';
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
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i><?= date('h:i A', strtotime($hotspot_start_time)); ?>
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
                                                                                                                                        <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>.
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

                                                        <span id="showOVERALLCOSTINFO"></span>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- hi -->
                                </div>
                            </div>
                            <!-- / Content -->
                            <!-- Footer -->
                            <?php include_once('public/__footer.php'); ?>
                            <!-- / Footer -->
                        </div>
                        <!-- Content wrapper -->
                    </div>
                    <!-- / Layout page -->
                </div>
            </div>
            <!-- / Layout wrapper -->

            <div class="buy-now">
                <a class="btn btn-primary btn-buy-now text-white" id="scrollToTopButton">
                    <span class="align-middle"> <i class="ti ti-arrow-up"></i></span>
                </a>
            </div>

            <script src="assets/vendor/libs/jquery/jquery.js"></script>
            <script src="assets/vendor/libs/popper/popper.js"></script>
            <script src="assets/vendor/js/bootstrap.js"></script>
            <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
            <script src="assets/js/extended-ui-perfect-scrollbar.js"></script>
            <script src="assets/vendor/libs/i18n/i18n.js"></script>
            <script src="assets/vendor/js/menu.js"></script>

            <!-- endbuild -->
            <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
            <script src="assets/vendor/libs/tagify/tagify.js"></script>

            <!-- Form Validation -->
            <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
            <!-- Vendors JS -->
            <script src="assets/js/_jquery.dataTables.min.js"></script>
            <script src="assets/js/_dataTables.buttons.min.js"></script>
            <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
            <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
            <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
            <script src="assets/js/_js_buttons.html5.min.js"></script>
            <script src="assets/js/selectize/selectize.min.js"></script>
            <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
            <script src="assets/vendor/libs/toastr/toastr.js"></script>
            <script src="assets/js/footerscript.js"></script>
            <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
            <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
            <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
            <script src="assets/js/lottie.min.js"></script>
            <script src="./assets/vendor/js/dropdown-hover.js"></script>

            <!-- Main JS -->
            <script src="assets/js/main.js"></script>

            <script>
                var scrollToTopButton = document.getElementById("scrollToTopButton");
                // Add click event listener to the button
                scrollToTopButton.addEventListener("click", function() {
                    // Scroll the page to the top smoothly
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                });

                // Initialize flatpickr for all elements with class name .flatpickr-input
                flatpickr(".flatpickr-input", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K" // This format represents hour:minutes AM/PM
                });

                // Function to copy the share link
                function CopyShareLink() {
                    var encryptedItineraryPlanId = "<?= Encryption::Encode($itinerary_plan_ID, SECRET_KEY); ?>";
                    var selected_gtype = "<?= Encryption::Encode($selected_group_type, SECRET_KEY); ?>";
                    var shareLink = "<?= BASEPATH ?>view_itinerary.php?type=show_itinerary&itinerary_plan_ID=" + encryptedItineraryPlanId + "&gtype=" + selected_gtype;
                    var tempInput = document.createElement("input");

                    document.body.appendChild(tempInput);
                    tempInput.value = shareLink;
                    tempInput.select();

                    try {
                        var successful = document.execCommand("copy");
                        if (successful) {
                            TOAST_NOTIFICATION('success', 'Successfully Copied the Share Link !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Copy Share Link!!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    } catch (error) {
                        TOAST_NOTIFICATION('error', 'Unable to Copy Share Link!!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    } finally {
                        document.body.removeChild(tempInput);
                    }
                }

                // Function to share the link via WhatsApp
                function ShareWhatsApp() {
                    var encryptedItineraryPlanId = "<?= Encryption::Encode($itinerary_plan_ID, SECRET_KEY); ?>";
                    var selected_gtype = "<?= Encryption::Encode($selected_group_type, SECRET_KEY); ?>";
                    var shareLink = "<?= BASEPATH ?>view_itinerary.php?type=show_itinerary&itinerary_plan_ID=" + encryptedItineraryPlanId + "&gtype=" + selected_gtype;
                    var whatsappUrl = "https://wa.me/?text=" + encodeURIComponent("Check out this itinerary: " + shareLink);

                    window.open(whatsappUrl, '_blank');
                }


                // Function to share the link via Email
                function ShareEmail() {
                    var encryptedItineraryPlanId = "<?= Encryption::Encode($itinerary_plan_ID, SECRET_KEY); ?>";
                    var selected_gtype = "<?= Encryption::Encode($selected_group_type, SECRET_KEY); ?>";
                    var shareLink = "<?= BASEPATH ?>view_itinerary.php?type=show_itinerary&itinerary_plan_ID=" + encryptedItineraryPlanId + "&gtype=" + selected_gtype;
                    var emailSubject = "Check out this itinerary!";
                    var emailBody = "Here's the itinerary link: " + shareLink;
                    var mailtoUrl = "mailto:?subject=" + encodeURIComponent(emailSubject) + "&body=" + encodeURIComponent(emailBody);

                    window.location.href = mailtoUrl;
                }


                function updateTRIPSTARTANDENDTIME(routeID, planID, routeINFO, startTime, endTime, group_type) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_manage_itineary.php?type=update_timing",
                        data: {
                            routeID: routeID,
                            planID: planID,
                            startTime: startTime,
                            endTime: endTime
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (!response.success) {
                                if (response.errors.endtime_should_be_greater_than_start_time) {
                                    TOAST_NOTIFICATION('error', 'End Time should be Grater than Start Time !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.errors.minimum_required_hours_failed) {
                                    TOAST_NOTIFICATION('error', response.errors.minimum_required_hours_failed, 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            } else {
                                if (response.u_result == true) {
                                    TOAST_NOTIFICATION('success', 'Successfully Trip Timing Updated for ' + routeINFO + ' !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    showDAYWISEHOTSPOT_DETAILS(routeID, planID, group_type);
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to updating time ' + routeINFO + ' !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            TOAST_NOTIFICATION('error', 'Unable to updating time:' + error, 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    });
                }

                // Event listener for start time input
                function startTIME(element, routeID, planID, routeINFO) {
                    var group_type = '<?= $selected_group_type; ?>';
                    var startTime = element.value;
                    var endTime = $('#hotspot_end_time_' + routeID).val(); // Get the corresponding end time
                    updateTRIPSTARTANDENDTIME(routeID, planID, routeINFO, startTime, endTime, group_type);
                    $('#hotspot_start_time_' + routeID).val(startTime); // Update the value directly
                    $('#start_time_' + routeID).text(endTime);
                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                        setTimeout(function() {
                            getVEHICLEPLANDETAILS(planID);
                        }, 1000); // 30 seconds in milliseconds
                    <?php endif; ?>
                }

                // Event listener for end time input
                function endTIME(element, routeID, planID, routeINFO) {
                    var group_type = '<?= $selected_group_type; ?>';
                    var endTime = element.value;
                    var startTime = $('#hotspot_start_time_' + routeID).val(); // Get the corresponding start time
                    updateTRIPSTARTANDENDTIME(routeID, planID, routeINFO, startTime, endTime, group_type);
                    $('#hotspot_end_time_' + routeID).val(endTime); // Update the value directly
                    $('#end_time_' + routeID).text(endTime);
                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                        setTimeout(function() {
                            getVEHICLEPLANDETAILS(planID);
                        }, 1000); // 30 seconds in milliseconds
                    <?php endif; ?>
                }

                function showDAYWISEHOTSPOT_DETAILS(routeID, planID, group_type) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_itineary_step2_form.php?type=show_form&selected_group_type=" + group_type,
                        data: {
                            _ID: planID,
                            routeID: routeID,
                        },
                        success: function(response) {
                            $('#showITINEARYSTEP1').html('');
                            $('#showITINEARYSTEP2').html(response);
                        }
                    });
                }

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
                        showDAYWISE_HOTEL_DETAILS('<?= $itinerary_plan_ID; ?>', '<?= $selected_group_type; ?>');
                        showOVERALL_COST_DETAILS('<?= $itinerary_plan_ID; ?>', '<?= $selected_group_type; ?>');
                        showOVERALLCOSTAMOUNT('<?= $itinerary_plan_ID; ?>', '<?= $selected_group_type; ?>');
                    <?php endif; ?>
                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                        showDAYWISE_VEHICLE_DETAILS('<?= $itinerary_plan_ID; ?>');
                        showOVERALL_COST_DETAILS('<?= $itinerary_plan_ID; ?>', '<?= $selected_group_type; ?>');
                        showOVERALLCOSTAMOUNT('<?= $itinerary_plan_ID; ?>', '<?= $selected_group_type; ?>');
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

                function addGUIDE(ROUTE_GUIDE_ID, GUIDE_TYPE, PLAN_ID, ROUTE_ID, ROUTE_DAY) {
                    var group_type = '<?= $selected_group_type; ?>';
                    $('.receiving-modal-info-form-data').load(
                        'engine/ajax/ajax_latest_itineary_guide_form.php?type=add_guide_form&ROUTE_GUIDE_ID=' + ROUTE_GUIDE_ID +
                        '&GUIDE_TYPE=' + GUIDE_TYPE + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&ROUTE_DAY=' + ROUTE_DAY + '&GROUP_TYPE=' + group_type,
                        function() {
                            const container = document.getElementById("MODALINFODATA");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function showHOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID, itinerary_route_date) {
                    var group_type = '<?= $selected_group_type; ?>';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_itineary_show_hotspot_list.php?type=show_form",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID,
                            itinerary_route_ID: itinerary_route_ID,
                            itinerary_route_date: itinerary_route_date,
                            group_type: group_type
                        },
                        success: function(response) {
                            $('#modalHotspotContent').html(response);
                            $('#hotspotModal').modal('show');
                        }
                    });
                }

                function filterHotspots() {
                    var searchValue = $('#hotspotSearch').val().toLowerCase();
                    $('#hotspotContainer .col-12').each(function() {
                        var hotspotName = $(this).find('.hotspot-card-title').text().toLowerCase();
                        if (hotspotName.includes(searchValue)) {
                            $(this).removeClass('d-none');
                        } else {
                            $(this).addClass('d-none');
                        }
                    });
                }

                function closeHOTSPOTLIST() {
                    $('#hotspotModal').modal('hide');
                }

                /* function showHOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID) {
                    $('#addhotspot_' + itinerary_route_ID).addClass('d-none');
                    $('#closehotspot_' + itinerary_route_ID).removeClass('d-none');
                    showITINEARY_ROUTE_HOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID);
                } */

                function closeHOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID) {
                    $('#closehotspot_' + itinerary_route_ID).addClass('d-none');
                    $('#addhotspot_' + itinerary_route_ID).removeClass('d-none');
                    $('#show_list_of_hotspots_' + itinerary_route_ID).html('');
                }

                function deleteGUIDE(ROUTE_GUIDE_ID, GUIDE_TYPE, PLAN_ID, ROUTE_ID, ROUTE_DAY, group_type) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_manage_itineary.php?type=delete_guide_for_itinerary",
                        data: {
                            ROUTE_GUIDE_ID: ROUTE_GUIDE_ID,
                            GUIDE_TYPE: GUIDE_TYPE,
                            PLAN_ID: PLAN_ID,
                            ROUTE_ID: ROUTE_ID,
                            ROUTE_DAY: ROUTE_DAY
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.result == true) {
                                showDAYWISEHOTSPOT_DETAILS(ROUTE_ID, PLAN_ID, group_type);
                            }
                        }
                    });
                }

                function showITINEARY_ROUTE_HOTSPOTLIST(itinerary_plan_ID, itinerary_route_ID) {
                    var group_type = '<?= $selected_group_type; ?>';
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_itineary_show_hotspot_list.php?type=show_form",
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID,
                            itinerary_route_ID: itinerary_route_ID,
                            group_type: group_type
                        },
                        success: function(response) {
                            $('#show_list_of_hotspots_' + itinerary_route_ID).html(response);
                        }
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

                /*  function showHOTSPOTACTIVITYLIST(route_hotspot_ID, hotspot_ID, itinerary_plan_ID, itinerary_route_ID) {
                        $('#addactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).addClass('d-none');
                        $('#closeactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).removeClass('d-none');
                        showITINEARY_ROUTE_HOTSPOT_ACTIVITY_LIST(route_hotspot_ID, hotspot_ID, itinerary_plan_ID, itinerary_route_ID);
                    } */

                function closeHOTSPOTACTIVITYLIST(hotspot_ID, itinerary_route_ID) {
                    $('#closeactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).addClass('d-none');
                    $('#addactivitybtn_' + hotspot_ID + '_' + itinerary_route_ID).removeClass('d-none');
                    $('#show_list_of_hotspots_activity_' + hotspot_ID + '_' + itinerary_route_ID).html('');
                }

                /* function showITINEARY_ROUTE_HOTSPOT_ACTIVITY_LIST(route_hotspot_ID, hotspot_ID, itinerary_plan_ID, itinerary_route_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_itineary_show_hotspot_activity_list.php?type=show_form",
                        data: {
                            route_hotspot_ID: route_hotspot_ID,
                            hotspot_ID: hotspot_ID,
                            itinerary_route_ID: itinerary_route_ID,
                            itinerary_plan_ID: itinerary_plan_ID
                        },
                        success: function(response) {
                            $('#show_list_of_hotspots_activity_' + hotspot_ID + '_' + itinerary_route_ID).html(response);
                        }
                    });
                } */

                function filterActivity() {
                    var searchValue = $('#activitySearch').val().toLowerCase();
                    $('#vertical-example .col-12').each(function() {
                        var activityName = $(this).find('.custom-option-title').text().toLowerCase();
                        if (activityName.includes(searchValue)) {
                            $(this).removeClass('d-none');
                        } else {
                            $(this).addClass('d-none');
                        }
                    });
                }

                function show_REMOVE_ITINEARY_ROUTE_HOTSPOT_MODAL(itinerary_plan_ID, itinerary_route_ID, hotspot_ID) {
                    var group_type = '<?= $selected_group_type; ?>';
                    $('.receiving-modal-info-form-data').load(
                        'engine/ajax/ajax_latest_manage_itineary.php?type=delete_itineary_hotspot&itinerary_plan_ID=' +
                        itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_ID=' + hotspot_ID + '&group_type=' + group_type,
                        function() {
                            const container = document.getElementById("MODALINFODATA");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function showHOTSPOTACTIVITYGALLERY(activity_ID) {
                    $('.receiving-gallery-modal-info-form-data').load(
                        'engine/ajax/ajax_latest_itineary_show_hotspot_activity_gallery.php?type=show_form&activity_ID=' +
                        activity_ID,
                        function() {
                            const container = document.getElementById("GALLERYMODALINFODATA");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function show_REMOVE_ITINEARY_ROUTE_HOTSPOT_ACTIVITY_MODAL(itinerary_plan_ID, itinerary_route_ID, hotspot_ID,
                    route_hotspot_ID, activity_ID) {
                    var group_type = '<?= $selected_group_type; ?>';
                    $('.receiving-modal-info-form-data').load(
                        'engine/ajax/ajax_latest_manage_itineary.php?type=delete_itineary_hotspot_activity&itinerary_plan_ID=' +
                        itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_ID=' + hotspot_ID +
                        '&route_hotspot_ID=' + route_hotspot_ID + '&activity_ID=' + activity_ID + '&GROUP_TYPE=' + group_type,
                        function() {
                            const container = document.getElementById("MODALINFODATA");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function showDAYWISE_VEHICLE_DETAILS(itinerary_plan_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_view_itineary_vehicle_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                        },
                        success: function(response) {
                            $('#showVEHICLEINFO').html('');
                            $('#showVEHICLEINFO').html(response);
                        }
                    });
                }

                function showOVERALL_COST_DETAILS(itinerary_plan_ID, group_type) {
                    $('#show_itineary_loader').show();
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_view_itineary_overall_cost_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _groupTYPE: group_type,
                        },
                        success: function(response) {
                            $('#show_itineary_loader').hide();
                            $('#showOVERALLCOSTINFO').html('');
                            $('#showOVERALLCOSTINFO').html(response);
                        }
                    });
                }

                function showOVERALLCOSTAMOUNT(itinerary_plan_ID, group_type) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_view_itineary_overall_cost_details.php?type=show_grand_itineary_total",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _groupTYPE: group_type,
                        },
                        success: function(response) {
                            $('#overall_trip_cost').text('');
                            $('#overall_trip_cost').text(response);
                        }
                    });
                }

                function showDAYWISE_HOTEL_DETAILS(itinerary_plan_ID, group_type) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_view_itineary_hotel_details.php?type=show_form",
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _group_type: group_type
                        },
                        success: function(response) {
                            $('#showHOTELINFO').html('');
                            $('#showHOTELINFO').html(response);
                        }
                    });
                }

                function getVEHICLEPLANDETAILS(itinerary_plan_ID) {
                    return new Promise(function(resolve, reject) {
                        $('#show_itineary_loader').addClass('d-block');
                        $('#show_itineary_loader').removeClass('d-none');
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/ajax_latest_itineary_manage_vehicle_details.php?type=add_vehicle_plan',
                            data: {
                                _ID: itinerary_plan_ID
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#show_itineary_loader').addClass('d-none');
                                $('#show_itineary_loader').removeClass('d-block');
                                if (!response.success) {
                                    // Handle errors here
                                    var errorMessage = "";
                                    $.each(response.errors, function(key, value) {
                                        errorMessage = value;
                                        TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '',
                                            '', '', '', '', '', '');
                                    });
                                } else {
                                    // Handle success here
                                    resolve();
                                    //RESULT SUCCESS
                                    TOAST_NOTIFICATION('success', 'Itinerary Details Updated', 'Success !!!',
                                        '', '', '', '', '', '', '', '', '');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error here
                                reject(error);
                            }
                        });
                    });
                }
            </script>

    </body>

    </html>
<?php
endif;
