<?php
include_once('jackus.php');

$itinerary_plan_ID = $_GET['itinerary_plan_ID'];
/* $_groupTYPE = $_GET['_groupTYPE'];
   $groupTYPE = $_GET['groupTYPE']; */

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
        $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
        // Split the string by comma
        $arrival_parts = explode(",", $arrival_location);

        // Get the first value before the comma
        $arrival_value = trim($arrival_parts[0]); // Trim to remove any leading/trailing whitespace

        $departure_location = $fetch_itinerary_plan_data['departure_location'];
        // Split the string by comma
        $departure_parts = explode(",", $departure_location);

        // Get the first value before the comma
        $departure_value = trim($departure_parts[0]); // Trim to remove any leading/trailing whitespace
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
        $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
        $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
        $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($trip_start_date_and_time));
        $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($trip_end_date_and_time));
        $arrival_type = getTRAVELTYPE($fetch_itinerary_plan_data['arrival_type'], 'label');
        $departure_type = getTRAVELTYPE($fetch_itinerary_plan_data['departure_type'], 'label');
        $expecting_budget = $fetch_itinerary_plan_data['expecting_budget'];
        $itinerary_type = $fetch_itinerary_plan_data['itinerary_type'];
        $entry_ticket_required = get_YES_R_NO($fetch_itinerary_plan_data['entry_ticket_required'], 'label');
        $no_of_routes = $fetch_itinerary_plan_data['no_of_routes'];
        $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
        $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
        $total_adult = $fetch_itinerary_plan_data['total_adult'];
        $total_children = $fetch_itinerary_plan_data['total_children'];
        $total_infants = $fetch_itinerary_plan_data['total_infants'];
        $itinerary_preference = $fetch_itinerary_plan_data['itinerary_preference'];
        $meal_plan_breakfast = $fetch_itinerary_plan_data['meal_plan_breakfast'];
        $meal_plan_lunch = $fetch_itinerary_plan_data['meal_plan_lunch'];
        $meal_plan_dinner = $fetch_itinerary_plan_data['meal_plan_dinner'];
        $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
        $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
        $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
        $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
        $vehicle_type = $fetch_itinerary_plan_data['vehicle_type'];
        $guide_for_itinerary = get_YES_R_NO($fetch_itinerary_plan_data['guide_for_itinerary'], 'label');
        $special_instructions = $fetch_itinerary_plan_data['special_instructions'];
        $pick_up_date_and_time = $fetch_itinerary_plan_data['pick_up_date_and_time'];
        $pick_up_date_and_time = date('d-m-Y h:i A', strtotime($pick_up_date_and_time));
        $nationality = getCOUNTRYLIST($fetch_itinerary_plan_data['nationality'], 'country_label');
        $food_type = getFOODTYPE($fetch_itinerary_plan_data['food_type'], 'label');
    endwhile;

    $total_pax_count = $total_adult + $total_children + $total_infants;
endif;
$TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');


$select_itinerary_traveller_details_query = sqlQUERY_LABEL("SELECT `room_id` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `room_id`") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
$total_itinerary_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_traveller_details_query);

$total_adult = 0;
$total_children = 0;
$total_infants = 0;

if ($total_itinerary_traveller_details_count > 0) :
    while ($fetch_itinerary_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_traveller_details_query)) :
        $traveller_room_id = $fetch_itinerary_traveller_data['room_id'];
        $select_itinerary_adult_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_ADULT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_itinerary_adult_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_adult_traveller_details_query);
        if ($total_itinerary_adult_traveller_details_count > 0) :
            while ($fetch_itinerary_adult_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_adult_traveller_details_query)) :
                $total_adult += $fetch_itinerary_adult_traveller_data['TOTAL_ADULT'];
            endwhile;
        else :
            $total_adult += 0;
        endif;

        $select_itinerary_children_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_CHILDREN FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_itinerary_children_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_children_traveller_details_query);
        if ($total_itinerary_children_traveller_details_count > 0) :
            while ($fetch_itinerary_children_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_children_traveller_details_query)) :
                $total_children += $fetch_itinerary_children_traveller_data['TOTAL_CHILDREN'];
            endwhile;
        else :
            $total_children += 0;
        endif;
        $select_itinerary_infant_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_INFANT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_itinerary_infant_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_infant_traveller_details_query);
        if ($total_itinerary_infant_traveller_details_count > 0) :
            while ($fetch_itinerary_infant_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_infant_traveller_details_query)) :
                $total_infants += $fetch_itinerary_infant_traveller_data['TOTAL_INFANT'];
            endwhile;
        else :
            $total_infants += 0;
        endif;
    endwhile;
endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Clipboard</title>
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />
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
    <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">
    <div id="contentToCopy">
        <div style=" font-family: Calibri; font-size: 11px; color: #302c6e
            ;  width: 700px; ">
            <!--Start Header -->
            <!-- <div style="border:1px solid #b1b1b1;"> -->
            <table class="row row-4" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style=" font-family: Calibri; font-size: 11px; color: #302c6e
               ;  width: 700px; ">
                <tbody>
                    <tr>
                        <td>
                            <table class="row row-4" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                            <table data-group="Titles" data-module="Title 1" data-thumbnail="thubnails/title-1.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;" class="selected-table">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                                                            <table width="520" align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:520px;max-width:520px;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="container-padding">
                                                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                                                                                                            Tour
                                                                                                                            Itinerary
                                                                                                                            plan
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="row row-3" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width:100%; max-width:100%;background-color:#fff;color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;border-bottom:0px;">
                                <!-- Table content -->
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%; color:#302c6e;" width="550">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border: 1px solid #b1b1b1;">
                                                            <table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;vertical-align: middle; ">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="vertical-align: middle;">
                                                                            <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%" width="550">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="column column-1" width="35%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;border: 0px;">
                                                                                            <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td class="pad" style="">
                                                                                                            <div style="font-size:10px;text-align:center;mso-line-height-alt:20.4px">
                                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                                    <span>Arrival
                                                                                                                        <?= $arrival_type; ?>
                                                                                                                        at</span>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                            <div style="font-size:15px;text-align:center;mso-line-height-alt:50.4px">
                                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                                    <span><strong>
                                                                                                                            <?= $arrival_value; ?>
                                                                                                                        </strong></span>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="column column-2" width="20%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;border: 0;">
                                                                                            <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: auto;margin-bottom: auto;mso-table-lspace:0;mso-table-rspace:0;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                                                            <div class="alignment" align="center" style="line-height:10px">
                                                                                                                <!-- <img src="<?= BASEPATH; ?>assets/img/Airplane_outline.gif" style="display:block;height:auto;border:0;width:138px;max-width:100%" width="138" alt="Alternate text" title="Alternate text"> -->
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="column column-3" width="35%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;border: 0;">
                                                                                            <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td class="pad" style="">
                                                                                                            <div style="font-size:10px;text-align:center;mso-line-height-alt:20.4px;">
                                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                                    <span>Departure
                                                                                                                        <?= $departure_type; ?>
                                                                                                                        at</span>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                            <div style="font-size:15px;text-align:center;mso-line-height-alt:50.4px">
                                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                                    <span><strong><?= $departure_value; ?></strong></span>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--End Header -->
                            <!--Start Basic Details -->
                            <table class="row row-4" align="center" width="100%" border="1" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0; border-collapse: collapse; ">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class="row-content stack" align="center" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%; ">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left; border-collapse: collapse; ">
                                                            <table class="paragraph_block block-1" width="100%" border="1" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word; border-collapse: collapse;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Start
                                                                                        Date & Time</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $trip_start_date_and_time; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">End
                                                                                        Date & Time</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $trip_end_date_and_time; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Quote
                                                                                        Id</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $itinerary_quote_ID; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Trip
                                                                                        Night &amp; Day</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $no_of_nights; ?>
                                                                                            Nights, <?= $no_of_days; ?>
                                                                                            Days</span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="paragraph_block block-1" width="100%" border="1" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word; border-collapse: collapse;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Entry
                                                                                        Ticket Required</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $entry_ticket_required; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Guide
                                                                                        for Whole Itineary</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $guide_for_itinerary; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Nationality</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $nationality; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style=" text-align:center; border: 1px solid #b1b1b1;">
                                                                            <div style="color:#232323;font-size: 11px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 11px;font-weight: 500;">Total
                                                                                        Pax</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 11px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $total_adult; ?>
                                                                                            Adult,
                                                                                            <?= $total_children; ?>
                                                                                            Children,
                                                                                            <?= $total_infants; ?>
                                                                                            Infant</span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--End Basic Details -->
                            <!-- Start Hotspot Details -->
                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                                <tbody>
                                    <tr>
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <!-- Hotel Details -->
                                            <table class="row row-19" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                                            <!-- <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text"> -->
                                                                                            <!-- <img src="<?= BASEPATH; ?>assets/img/hotel-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text" /> -->
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- Hotel Details -->
                                            <!-- Recommended Hotel - 1 -->
                                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:18%; border: 1px solid #b1b1b1;">
                                                            Start and end time
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%; border: 1px solid #b1b1b1;">
                                                            Duration
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:20%; border: 1px solid #b1b1b1;">
                                                            Places to visit
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:30%; border: 1px solid #b1b1b1;">
                                                            Description
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                            $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                            $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                            if ($total_itinerary_plan_route_details_count > 0) :
                                                $last_day_ending_location = NULL;
                                                while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                    $itineary_route_count++;
                                                    $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                    $location_name = $fetch_itinerary_plan_route_data['location_name'];
                                                    // Split the string by comma
                                                    $location_parts = explode(",", $location_name);

                                                    // Get the first value before the comma
                                                    $location_value = trim($location_parts[0]); // Trim to remove any leading/trailing whitespace
                                                    $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                                                    $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                                                    $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                                                    // Split the string by comma
                                                    $next_visiting_parts = explode(",", $next_visiting_location);

                                                    // Get the first value before the comma
                                                    $next_visiting_value = trim($next_visiting_parts[0]); // Trim to remove any leading/trailing whitespace
                                                    $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
                                                    $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];

                                                    $get_via_route_details_with_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_with_format');
                                                    $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_without_format');

                                                    if ($arrival_location == $location_name) :
                                                        $start_day_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                        $start_day_time_add_attr = "readonly";
                                                    else :
                                                        $start_day_time_add_class = "form-control w-px-100 start-time-input text-center flatpickr-input";
                                                        $start_day_time_add_attr = "";
                                                    endif;

                                                    if ($departure_location == $next_visiting_location) :
                                                        $day_end_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
                                                        $day_time_add_attr = "readonly";
                                                    else :
                                                        $day_end_time_add_class = "form-control w-px-100 end-time-input text-center flatpickr-input";
                                                        $day_end_time_add_attr = "";
                                                    endif;
                                            ?>
                                                    <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%; background-color:#fff;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4" style="border: 1px solid #b1b1b1; padding: 8px; font-size: 11px;background-color: #f2f2f2;">
                                                                    <div style="display:flex; align-items: center;">
                                                                        <span style="margin-right:4px; font-weight:400;">
                                                                            <strong>
                                                                                Day
                                                                                <?= $itineary_route_count; ?></b> -
                                                                                <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>
                                                                                (<?= date('h:i A', strtotime($route_start_time)); ?>
                                                                                -
                                                                                <?= date('h:i A', strtotime($route_end_time)); ?>)
                                                                                -
                                                                                <span style="color:#302c6e">
                                                                                    <?= $location_value; ?>
                                                                                    to <?= $next_visiting_value; ?>
                                                                            </strong>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            if ($guide_for_itinerary == 0) :
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
                                                                    <tr>
                                                                        <td colspan='4' style="border: 1px solid #b1b1b1; padding: 4px; font-size: 11px;background-color: #f2f2f2;">
                                                                            <div style="display:flex; align-items: center;">
                                                                                <h4 style="margin:3px;">Guide Language:
                                                                                    <?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                                    <?= 'Slot Timing - ' . getSLOTTYPE($guide_slot, 'label'); ?>
                                                                                </h4>
                                                                            </div>
                                                                        </td>
                                                                    <tr>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php
                                                                $select_itinerary_plan_route_hotspot_availability_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `status` = '1' AND `item_type` IN ('5','6') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                $total_itinerary_plan_route_hotspot_availability_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                $fetch_hotspot_availability = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                                $get_route_last_hotspot_ID = $fetch_hotspot_availability['route_hotspot_ID'];

                                                                $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID'  ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
                                                                $itineary_route_hotspot_count = 0;
                                                                if ($total_itinerary_plan_route_hotspot_details_count > 0) :
                                                                ?>
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
                                                                        $hotspot_gallery_name = getHOTSPOT_GALLERY_DETAILS($hotspot_ID, 'hotspot_gallery_name');
                                                                        if ($hotspot_gallery_name) :
                                                                            $hotspot_gallery_name = $hotspot_gallery_name;
                                                                        else :
                                                                            $hotspot_gallery_name = 'no-preview.png';
                                                                        endif;

                                                                        if ($item_type == 3) : ?>
                                                                    <tr>
                                                                        <td colspan="4" style="border: 1px solid #b1b1b1; padding: 8px; font-size: 11px;">
                                                                            <div style="display:flex; align-items: center;">
                                                                                <span style="margin-right:4px;">Travelling:
                                                                                    <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                    -
                                                                                    <?= date('h:i A', strtotime($hotspot_end_time)); ?></span>[<span style="color: #7e7d88;margin-right: 5px;">Distance:</span>
                                                                                <?= $hotspot_travelling_distance; ?> KM,
                                                                                <span style="color: #7e7d88;margin: 0px 5px;">
                                                                                    Duration:</span>
                                                                                <?= formatTimeDuration($hotspot_traveling_time); ?> ]
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                <?php if ($item_type == 4 && $hotspot_ID != 0) : ?>
                                                                    <tr>
                                                                        <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width: 20%">
                                                                            <div style="display:flex;">
                                                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?> -
                                                                                <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                            </div>
                                                                        </td>
                                                                        <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width: 10%">
                                                                            <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                        </td>
                                                                        <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width: 30%">
                                                                            <?= $hotspot_name; ?>
                                                                            <?php
                                                                            $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                                                                            $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                                                                            if ($total_hotspot_activity_num_rows_count > 0) :
                                                                            ?>
                                                                                <?php
                                                                                $activitycount = 0;
                                                                                while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_activity_details)) :
                                                                                    $activitycount++;
                                                                                    $route_activity_ID = $fetch_hotspot_activity_data['route_activity_ID'];
                                                                                    $activity_order = $fetch_hotspot_activity_data['activity_order'];
                                                                                    $activity_ID = $fetch_hotspot_activity_data['activity_ID'];
                                                                                    $activity_amout = $fetch_hotspot_activity_data['activity_amout'];
                                                                                    $activity_traveling_time = $fetch_hotspot_activity_data['activity_traveling_time'];
                                                                                    $activity_start_time = $fetch_hotspot_activity_data['activity_start_time'];
                                                                                    $activity_end_time = $fetch_hotspot_activity_data['activity_end_time'];
                                                                                    $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                                                                    $activity_rating = $fetch_hotspot_activity_data['activity_rating'];
                                                                                    $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                                                                    $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                                                                ?>
                                                                                    <div>
                                                                                        <p style="margin:0"> <strong>Activity
                                                                                                #<?= $activitycount ?></strong>:
                                                                                            <?= $activity_title ?>
                                                                                            <span><?= date('h:i A', strtotime($activity_start_time)); ?>
                                                                                                -
                                                                                                <?= date('h:i A', strtotime($activity_end_time)); ?></span>
                                                                                        </p>
                                                                                    </div>
                                                                                <?php endwhile; ?>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <td colspan="4" style="border: 1px solid #b1b1b1; padding: 8px; font-size: 11px;">
                                                                        <div style="display:flex; align-items: center;">
                                                                            <?= $hotspot_description ?>
                                                                        </div>
                                                                    </td>
                                    </tr>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
            </table>
            </td>
            </tr>
            </tbody>
            </table>
            <?php if ($item_type == 7 && $total_itinerary_plan_route_hotspot_details_count == $itineary_route_hotspot_count) : ?>
                <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%; background-color: #fff; ">
                    <tbody>
                        <tr>
                            <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:11px;font-weight:400;letter-spacing:0px;border:1px solid #b1b1b1;border-top:0px; padding-left: 8px;" data-gramm="false">
                                <h3 class="m-0">Return to
                                    <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>.
                                </h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
    <!--/ Recommended Hotel - 1 -->
    </td>
    </tr>
    </tbody>
    </table>
    <!-- End Hotspot Details -->
    <!-- Start Hotel Details -->
    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
        <tbody>
            <tr>
                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                    <!-- Hotel Details -->
                    <table class="row row-19" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:100%">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                    <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                    <!-- <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text"> -->
                                                                    <!-- <img src="<?= BASEPATH; ?>assets/img/hotel-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text" /> -->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                    <table data-group="Titles" data-module="Title 1" data-thumbnail="thubnails/title-1.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;" class="selected-table">
                                                        <tbody>
                                                            <tr>
                                                                <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                                                    <table width="520" align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:520px;max-width:520px;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="center" class="container-padding">
                                                                                                    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                                                                                                    Hotel
                                                                                                                    Details
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Hotel Details -->
                    <?php
                    $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
                        $group_type = $row_hotel_group['group_type'];
                    ?>
                        <!-- Recommended Hotel - 1 -->
                        <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;">
                            <tbody>
                                <tr>
                                    <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                        Recommended Hotel - <?= $group_type ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;">
                            <tbody>
                                <tr>
                                    <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                        <table style="width:100%; border-collapse: collapse;">
                                            <tr>
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                    Day
                                                </th>
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                    Hotel Category
                                                </th>
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                    Name
                                                </th>
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                    Room Category
                                                </th>
                                                <!-- <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">Meal Plan</th> -->
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                    Price
                                                </th>
                                            </tr>
                                            <?php
                                            $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT 
                                                   ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, 
                                                   ROOM_DETAILS.`room_id`, 
                                                   ROOM_DETAILS.`room_type_id`, 
                                                   ROOM_DETAILS.`gst_type`, 
                                                   ROOM_DETAILS.`gst_percentage`, 
                                                   HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, 
                                                   HOTEL_DETAILS.`itinerary_plan_id`, 
                                                   HOTEL_DETAILS.`itinerary_route_id`, 
                                                   HOTEL_DETAILS.`itinerary_route_date`, 
                                                   HOTEL_DETAILS.`itinerary_route_location`, 
                                                   HOTEL_DETAILS.`hotel_required`, 
                                                   HOTEL_DETAILS.`hotel_category_id`, 
                                                   HOTEL_DETAILS.`hotel_id`, 
                                                   HOTEL_DETAILS.`hotel_margin_percentage`, 
                                                   HOTEL_DETAILS.`hotel_margin_gst_type`, 
                                                   HOTEL_DETAILS.`hotel_margin_rate`, 
                                                   HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, 
                                                   HOTEL_DETAILS.`hotel_breakfast_cost`, 
                                                   HOTEL_DETAILS.`hotel_lunch_cost`, 
                                                   HOTEL_DETAILS.`hotel_dinner_cost`, 
                                                   HOTEL_DETAILS.`total_no_of_persons`, 
                                                   HOTEL_DETAILS.`total_hotel_meal_plan_cost`, 
                                                   HOTEL_DETAILS.`total_no_of_rooms`, 
                                                   HOTEL_DETAILS.`total_room_cost`, 
                                                   HOTEL_DETAILS.`total_room_gst_amount`, 
                                                   HOTEL_DETAILS.`total_hotel_cost`, 
                                                   HOTEL_DETAILS.`total_hotel_tax_amount`,
                                                   ROOM_GALLERY_DETAILS.`hotel_room_gallery_details_id`,
                                                   ROOM_GALLERY_DETAILS.`room_gallery_name`
                                               FROM 
                                                   `dvi_itinerary_plan_hotel_details` HOTEL_DETAILS 
                                               LEFT JOIN 
                                                   `dvi_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`
                                               LEFT JOIN 
                                                   (SELECT 
                                                       `hotel_room_gallery_details_id`, 
                                                       `hotel_id`, 
                                                       `room_id`, 
                                                       `room_gallery_name`
                                                   FROM 
                                                       `dvi_hotel_room_gallery_details`
                                                   WHERE 
                                                       `deleted` = '0' AND `status` = '1'
                                                   ) ROOM_GALLERY_DETAILS ON ROOM_GALLERY_DETAILS.`room_id` = ROOM_DETAILS.`room_id`
                                               WHERE 
                                                   HOTEL_DETAILS.`deleted` = '0' AND 
                                                   HOTEL_DETAILS.`status` = '1' AND 
                                                   HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND
                                                   HOTEL_DETAILS.`group_type` = '$group_type'
                                               GROUP BY 
                                                   HOTEL_DETAILS.`itinerary_route_date`
                                               ORDER BY 
                                                   HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` ASC
                                               ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);
                                            $hotel_counter = 0;
                                            if ($select_itinerary_plan_hotel_count > 0) :
                                                while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_data)) :
                                                    $hotel_counter++;
                                                    $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                                                    $itinerary_plan_hotel_room_details_ID = $fetch_hotel_data['itinerary_plan_hotel_room_details_ID'];
                                                    $itinerary_plan_id = $fetch_hotel_data['itinerary_plan_id'];
                                                    $itinerary_route_id = $fetch_hotel_data['itinerary_route_id'];
                                                    $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                                                    $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                                                    $hotel_required = $fetch_hotel_data['hotel_required'];
                                                    $gst_type = $fetch_hotel_data['gst_type'];
                                                    $gst_percentage = $fetch_hotel_data['gst_percentage'];
                                                    $hotel_category_id = $fetch_hotel_data['hotel_category_id'];
                                                    $selected_hotel_id = $fetch_hotel_data['hotel_id'];
                                                    $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                                                    $hotel_margin_gst_type = $fetch_hotel_data['hotel_margin_gst_type'];
                                                    $hotel_margin_rate = $fetch_hotel_data['hotel_margin_rate'];
                                                    $hotel_margin_rate_tax_amt = $fetch_hotel_data['hotel_margin_rate_tax_amt'];
                                                    $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                                                    $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                                                    $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                                                    $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];
                                                    $total_hotel_meal_plan_cost = $fetch_hotel_data['total_hotel_meal_plan_cost'];
                                                    $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];
                                                    $total_room_cost = $fetch_hotel_data['total_room_cost'];
                                                    $total_room_gst_amount = $fetch_hotel_data['total_room_gst_amount'];
                                                    $total_hotel_cost = $fetch_hotel_data['total_hotel_cost'];
                                                    $total_hotel_tax_amount = $fetch_hotel_data['total_hotel_tax_amount'];
                                                    $selected_room_id = $fetch_hotel_data['room_id'];
                                                    $selected_room_type_id = $fetch_hotel_data['room_type_id'];
                                                    $check_in_time = getROOM_DETAILS($selected_room_id, 'check_in_time');
                                                    $check_out_time = getROOM_DETAILS($selected_room_id, 'check_out_time');

                                                    $total_kms = totalkms($itinerary_plan_id, 'label');
                                                    $vehicle_gst_amount = totalkms($itinerary_plan_id, 'gst');

                                                    $hotel_charges = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES');
                                                    $vehicle_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
                                                    $vehicle_gst_amount = $vehicle_gst_amount; // Assuming this is already defined
                                                    $hotspot_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
                                                    $activity_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
                                                    $guide_charges = $TOTAL_ITINEARY_GUIDE_CHARGES; // Assuming this is already defined

                                                    // Calculate the total
                                                    $total_amount = $hotel_charges + $vehicle_amount + $hotspot_amount + $activity_amount + $guide_charges;
                                                    $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
                                                    $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_id, '', 'get_room_gallery_1st_IMG');
                                                    if ($get_room_gallery_1st_IMG) :
                                                        $get_room_gallery_1st_IMG = $get_room_gallery_1st_IMG;
                                                    else :
                                                        $get_room_gallery_1st_IMG = 'no_image_for_room.png';
                                                    endif; ?>
                                                    <tr>
                                                        <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
                                                            <div>
                                                                Day- <?= $hotel_counter; ?> |
                                                                <?= dateformat_datepicker($itinerary_route_date); ?>
                                                            </div>
        </div>
        </td>
        <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
            <span data-toggle="tooltip" placement="top" title="<?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>"><?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>
        </td>
        <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
            <?php if ($hotel_required == 1) : ?>
                <span data-toggle="tooltip" placement="top" title="<?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?>"><i class="fa-solid fa-hotel me-1 hotelIcon"></i><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?></span>
            <?php else : ?>
                <span>--</span>
            <?php endif; ?>
        </td>
        <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
            <span data-toggle="tooltip" placement="top" title="<?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?>"><?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?>
        </td>
        <!-- <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
                                                                    <?php if ($meal_plan_breakfast == 1) :
                                                                        $breakfast_label = 'Breakfast';
                                                                    else :
                                                                        $breakfast_label = '';
                                                                    endif;
                                                                    if ($meal_plan_lunch == 1) :
                                                                        $lunch_label = ', Lunch';
                                                                    else :
                                                                        $lunch_label = '';
                                                                    endif;
                                                                    if ($meal_plan_dinner == 1) :
                                                                        $dinner_label = ', Dinner';
                                                                    else :
                                                                        $dinner_label = '';
                                                                    endif; ?>
                                                                    <?= $breakfast_label ?><span><?= $lunch_label ?><span><?= $dinner_label ?>
                                                                    
                                                                    </td> -->
        <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding-left: 8px;">
            <p class="col-12 m-0">
                <b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b>
            </p>
        </td>
        </tr>
    <?php endwhile; ?>
<?php else : ?>
    <tr>
        <td colspan="5" style="border: 1px solid #b1b1b1;text-align: center;">No hotel available</td>
    </tr>
<?php endif; ?>
</table>
</td>
</tr>
</tbody>
</table>
<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
    <tbody>
        <tr>
            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                <!-- Vehicle Details -->
                <table class="row row-19" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                    <tbody>
                        <tr>
                            <td>
                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:100%">
                                    <tbody>
                                        <tr>
                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                <!-- <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
                                    </div> -->
                                                                <div class="alignment" align="center" style="line-height:10px">
                                                                    <!-- <img class="big" src="<?= BASEPATH; ?>assets/img/vehicle-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text"> -->
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                    <tbody>
                        <tr>
                            <td>
                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                                    <tbody>
                                        <tr>
                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                <table data-group="Titles" data-module="Title 1" data-thumbnail="thubnails/title-1.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;" class="selected-table">
                                                    <tbody>
                                                        <tr>
                                                            <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                                                <table width="520" align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:520px;max-width:520px;">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class="container-padding">
                                                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                                                                                                Vehicle
                                                                                                                Details
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;">
                                                    <tbody>
                                                        <tr>
                                                            <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                <table style="width:100%; border-collapse: collapse;">
                                                                    <tr>
                                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                                            Vehicle Details</th>
                                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                                            Route</th>
                                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                                            Days</th>
                                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                                            Extra km cost</th>
                                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%; border: 1px solid #b1b1b1;">
                                                                            Total Amount</th>
                                                                    </tr>
                                                                    <?php
                                                                    $select_itinerary_plan_vendor_vehicle_summary_data = sqlQUERY_LABEL("SELECT 
                                                                                    d1.itinerary_plan_vendor_vehicle_details_ID,
                                                                                    d1.itinerary_plan_vendor_eligible_ID,
                                                                                    d1.itinerary_route_date,
                                                                                    d1.travel_type,
                                                                                    d1.itinerary_route_location_from,
                                                                                    d1.itinerary_route_location_to,
                                                                                    d1.total_running_km,
                                                                                    d1.total_running_time,
                                                                                    d1.total_siteseeing_km,
                                                                                    d1.total_siteseeing_time,
                                                                                    d1.total_travelled_km,
                                                                                    d1.total_travelled_time,
                                                                                    d1.vehicle_rental_charges,
                                                                                    d1.vehicle_toll_charges,
                                                                                    d1.vehicle_parking_charges,
                                                                                    d1.vehicle_driver_charges,
                                                                                    d1.vehicle_permit_charges,
                                                                                    d1.before_6_am_extra_time,
                                                                                    d1.after_8_pm_extra_time,
                                                                                    d1.before_6_am_charges_for_driver,
                                                                                    d1.before_6_am_charges_for_vehicle,
                                                                                    d1.after_8_pm_charges_for_driver,
                                                                                    d1.after_8_pm_charges_for_vehicle,
                                                                                    d1.total_vehicle_amount,
                                                                                    d2.itinerary_plan_vendor_eligible_ID AS eligible_list_id,
                                                                                    d2.itineary_plan_assigned_status,
                                                                                    d2.vehicle_type_id,
                                                                                    d2.total_vehicle_qty,
                                                                                    d2.vendor_id,
                                                                                    d2.outstation_allowed_km_per_day,
                                                                                    d2.extra_km_rate,
                                                                                    d2.vehicle_orign,
                                                                                    d2.vehicle_id,
                                                                                    d2.total_kms,
                                                                                    d2.vendor_branch_id,
                                                                                    d2.vehicle_gst_percentage,
                                                                                    d2.vehicle_gst_amount,
                                                                                    d2.vehicle_total_amount,
                                                                                    d2.vendor_margin_percentage,
                                                                                    d2.vendor_margin_gst_type,
                                                                                    d2.vendor_margin_gst_percentage,
                                                                                    d2.vendor_margin_amount,
                                                                                    d2.vendor_margin_gst_amount,
                                                                                    d2.total_extra_kms_charge,
                                                                                    d2.vehicle_grand_total,
                                                                                    d2.total_outstation_km,
                                                                                    d2.total_allowed_kms,
                                                                                    d2.total_extra_kms
                                                                                    FROM 
                                                                                        dvi_itinerary_plan_vendor_vehicle_details d1
                                                                                    LEFT JOIN 
                                                                                        dvi_itinerary_plan_vendor_eligible_list d2
                                                                                    ON 
                                                                                        d1.itinerary_plan_vendor_eligible_ID = d2.itinerary_plan_vendor_eligible_ID
                                                                                        WHERE 
                                                                                        d1.deleted = '0' 
                                                                                        AND d1.status = '1'
                                                                                        AND d1.itinerary_plan_id = '$itinerary_plan_ID'
                                                                                        AND d2.`itineary_plan_assigned_status` = '1'
                                                                                        AND d2.deleted = '0' 
                                                                                        AND d2.status = '1';
                                                                                    ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                                    $select_itinerary_plan_vendor_vehicle_summary_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_summary_data);
                                                                    if ($select_itinerary_plan_vendor_vehicle_summary_count > 0) :
                                                                        $vendor_vehicle_day_count = 0;
                                                                        while ($fetch_eligible_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_summary_data)) :
                                                                            $vendor_vehicle_day_count++;
                                                                            $itinerary_plan_vendor_vehicle_details_ID = $fetch_eligible_vendor_vehicle_data['itinerary_plan_vendor_vehicle_details_ID'];
                                                                            $itinerary_plan_vendor_eligible_ID = $fetch_eligible_vendor_vehicle_data['itinerary_plan_vendor_eligible_ID'];
                                                                            $itinerary_route_date = $fetch_eligible_vendor_vehicle_data['itinerary_route_date'];
                                                                            $travel_type = $fetch_eligible_vendor_vehicle_data['travel_type'];
                                                                            $itinerary_route_location_from = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_from'];
                                                                            // Split the string by comma
                                                                            $location_from = explode(",", $itinerary_route_location_from);

                                                                            // Get the first value before the comma
                                                                            $location_from_value = trim($location_from[0]); // Trim to remove any leading/trailing whitespace

                                                                            $itinerary_route_location_to = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_to'];
                                                                            // Split the string by comma
                                                                            $location_to = explode(",", $itinerary_route_location_to);

                                                                            // Get the first value before the comma
                                                                            $location_to_value = trim($location_to[0]); // Trim to remove any leading/trailing whitespace

                                                                            $total_running_km = $fetch_eligible_vendor_vehicle_data['total_running_km'];
                                                                            $total_running_time = $fetch_eligible_vendor_vehicle_data['total_running_time'];
                                                                            $total_siteseeing_km = $fetch_eligible_vendor_vehicle_data['total_siteseeing_km'];
                                                                            $total_siteseeing_time = $fetch_eligible_vendor_vehicle_data['total_siteseeing_time'];
                                                                            $total_travelled_km = $fetch_eligible_vendor_vehicle_data['total_travelled_km'];
                                                                            $total_travelled_time = $fetch_eligible_vendor_vehicle_data['total_travelled_time'];
                                                                            $vehicle_rental_charges = $fetch_eligible_vendor_vehicle_data['vehicle_rental_charges'];
                                                                            $vehicle_toll_charges = $fetch_eligible_vendor_vehicle_data['vehicle_toll_charges'];
                                                                            $vehicle_parking_charges = $fetch_eligible_vendor_vehicle_data['vehicle_parking_charges'];
                                                                            $vehicle_driver_charges = $fetch_eligible_vendor_vehicle_data['vehicle_driver_charges'];
                                                                            $vehicle_permit_charges = $fetch_eligible_vendor_vehicle_data['vehicle_permit_charges'];
                                                                            $before_6_am_extra_time = $fetch_eligible_vendor_vehicle_data['before_6_am_extra_time'];
                                                                            $after_8_pm_extra_time = $fetch_eligible_vendor_vehicle_data['after_8_pm_extra_time'];
                                                                            $before_6_am_charges_for_driver = $fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_driver'];
                                                                            $before_6_am_charges_for_vehicle = $fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_vehicle'];
                                                                            $after_8_pm_charges_for_driver = $fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_driver'];
                                                                            $after_8_pm_charges_for_vehicle = $fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_vehicle'];
                                                                            $total_vehicle_amount = $fetch_eligible_vendor_vehicle_data['total_vehicle_amount'];
                                                                            $vehicle_type_id = $fetch_eligible_vendor_vehicle_data['vehicle_type_id'];
                                                                            $total_extra_kms_charge = totalkms($itinerary_plan_ID, 'extra_kms');
                                                                            $vehicle_type_title = totalkms($vehicle_type_id, 'vehicle_type');




                                                                            if ($travel_type == 1) :
                                                                                $travel_type_label = 'Local';
                                                                            elseif ($travel_type == 2) :
                                                                                $travel_type_label = 'Outstation';
                                                                            else :
                                                                                $travel_type_label = '--';
                                                                            endif;
                                                                            $get_total_outstation_trip = get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip');
                                                                    ?>
                                                                        <?php endwhile; ?>
                                                                        <tr>
                                                                            <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width:15%; ">
                                                                                <div style="display:flex;">
                                                                                    <!-- <span><img src="<?= BASEPATH; ?>assets/img/vehi.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span> -->
                                                                                    <div>
                                                                                        <?= $vehicle_type_title; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width:15%">
                                                                                <?= $arrival_value; ?> ==>
                                                                                <?= $departure_value; ?></td>
                                                                            <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width:15%">
                                                                                <?= $trip_start_date_and_time ?> ==>
                                                                                <?= $trip_end_date_and_time ?></td>
                                                                            <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width:15%">
                                                                                <?= general_currency_symbol . ' ' . number_format($total_extra_kms_charge, 2); ?>
                                                                            </td>
                                                                            <td style="border: 1px solid #b1b1b1; padding: 8px; font-size: 13px; width:15%">
                                                                                <p class="col-12 p-0 m-0 text-end">
                                                                                    <b><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount'), 2); ?></b>
                                                                                </p>
    </div>
    </div>
    </td>
    </tr>
<?php else : ?>
    <tr>
        <td colspan="5" style="border: 1px solid #b1b1b1; padding: 8px; text-align: center;">
            No Vehicle available</td>
    </tr>
<?php endif; ?>
</table>
</td>
</tr>
</tbody>
</table>
<!-- Vehicle Details -->
</td>
</tr>
</tbody>
</table>
<!-- End Vehicle Details -->
<!-- Start Summary -->
<table class="row row-19" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;">
    <tbody>
        <tr>
            <td>
                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:100%">
                    <tbody>
                        <tr>
                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                    <tbody>
                                        <tr>
                                            <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                <!-- <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
                                    </div> -->
                                                <div class="alignment" align="center" style="line-height:10px">
                                                    <!-- <img class="big" src="<?= BASEPATH; ?>assets/img/vehicle-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text"> -->
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0; ">
    <tbody>
        <tr>
            <td>
                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                    <tbody>
                        <tr>
                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:1px solid #b1b1b1;border-right:1px solid #b1b1b1;border-bottom:0;border-left:1px solid #b1b1b1;">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                    <tbody>
                                        <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #b1b1b1;  border-right: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 0px 0px 10px;">
                                                                                    <div style="font-size:16px;text-align:left;">
                                                                                        <span><strong>Total
                                                                                                for
                                                                                                the
                                                                                                Hotel</strong></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td style="border-bottom: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                    <div style="font-size:16px;text-align:right;">
                                                                                        <span><?= general_currency_symbol . ' ' . number_format(getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES'), 2); ?></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #b1b1b1;  border-right: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 0px 0px 10px">
                                                                                    <div style="font-size:16px;text-align:left;">
                                                                                        <span><strong>Total
                                                                                                for
                                                                                                the
                                                                                                Vehicle</strong></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td style="border-bottom: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                    <div style="font-size:16px;text-align:right;">
                                                                                        <?= general_currency_symbol; ?>
                                                                                        <span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount'), 2); ?></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid #b1b1b1;  border-right: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 0px 0px 10px">
                                                                                <div style="font-size:16px;text-align:left;">
                                                                                    <span><strong>Total
                                                                                            vehicle
                                                                                            GST
                                                                                            (<?= $vehicle_gst_percentage . '%'; ?>)</strong></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="border-bottom: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                <div style="font-size:16px;text-align:right;">
                                                                                    <span><?= general_currency_symbol . ' ' . number_format($vehicle_gst_amount, 2); ?></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #b1b1b1;  border-right: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 0px 0px 10px">
                                                                                <div style="font-size:16px;text-align:left;">
                                                                                    <span><strong>Total
                                                                                            for
                                                                                            the
                                                                                            Hotspot</strong></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="border-bottom: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                <div style="font-size:16px;text-align:right;">
                                                                                    <?= general_currency_symbol; ?>
                                                                                    <span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount'), 2); ?></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #b1b1b1; border-right: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 0px 0px 10px">
                                                                                <div style="font-size:16px;text-align:left;">
                                                                                    <span><strong>Total
                                                                                            for
                                                                                            the
                                                                                            Activity</strong></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="border-bottom: 1px solid #b1b1b1;">
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                <div style="font-size:16px;text-align:right;">
                                                                                    <?= general_currency_symbol; ?><span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout'), 2); ?></span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                        if ($TOTAL_ITINEARY_GUIDE_CHARGES > 0) :
                                        ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #b1b1b1; border-right: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 0px 0px 10px">
                                                                                    <div style="font-size:16px;text-align:left;">
                                                                                        <span><strong>Total
                                                                                                Guide
                                                                                                Charges</strong></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td style="border-bottom: 1px solid #b1b1b1;">
                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                        <tbody>
                                                            <tr>
                                                                <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                    <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="pad" style="padding: 0px 10px 0px 0px">
                                                                                    <div style="font-size:16px;text-align:right;">
                                                                                        <?= general_currency_symbol; ?>
                                                                                        <span><?= number_format($TOTAL_ITINEARY_GUIDE_CHARGES, 2); ?></span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <table class="row row-19" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:100%">
                                                    <tbody>
                                                        <tr>
                                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                                <!-- <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
                                    </div> -->
                                                                                <div class="alignment" align="center" style="line-height:10px">
                                                                                    <!-- <img class="big" src="assets/img/vehicle-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text"> -->
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px; ">
    <tbody>
        <tr>
            <td>
                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                    <tbody>
                        <tr>
                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:1px solid #b1b1b1;border-left:1px solid #b1b1b1;">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                    <tbody>
                                        <tr>
                                            <td class="pad" style="padding:0px 0px 0px 10px;">
                                                <div style="font-size:16px;text-align:left;">
                                                    <span><strong>Net Payable to
                                                            Doview Holidays India
                                                            Pvt ltd</strong></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                    <tbody>
                        <tr>
                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:1px solid #b1b1b1;;border-bottom:1px solid #b1b1b1;;border-left:0">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                    <tbody>
                                        <tr>
                                            <td class="pad" style="padding:0px 10px 0px 0px;">
                                                <div style="font-size:16px;text-align:right;">
                                                    <strong>
                                                        <?= general_currency_symbol . ' ' . number_format($total_amount, 2); ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- End Hotel Details -->
</td>
</tr>
</tbody>
</table>
<?php endwhile; ?>
<!-- End Summary -->
<!-- Start Footer -->
</td>
</tr>
<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;margin-top:10px;">
    <tbody>
        <tr>
            <td class="column column-1" width="8%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:middle;border-top:0;border-right:0;border-bottom:0;border-left:0">
                <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                    <tbody>
                        <tr>
                            <td style="padding:0 2px 0 2px"><a href="https://www.facebook.com" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/t-only-logo-dark-gray/facebook@2x.png" width="32" height="32" alt="Facebook" title="facebook" style="display:block;height:auto;border:0"></a>
                            </td>
                            <td style="padding:0 2px 0 2px"><a href="https://www.twitter.com" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/t-only-logo-dark-gray/twitter@2x.png" width="32" height="32" alt="Twitter" title="twitter" style="display:block;height:auto;border:0"></a>
                            </td>
                            <td style="padding:0 2px 0 2px"><a href="https://www.linkedin.com" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/t-only-logo-dark-gray/linkedin@2x.png" width="32" height="32" alt="Linkedin" title="linkedin" style="display:block;height:auto;border:0"></a>
                            </td>
                            <td style="padding:0 2px 0 2px">
                                <a href="https://www.instagram.com" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/t-only-logo-dark-gray/instagram@2x.png" width="32" height="32" alt="Instagram" title="instagram" style="display:block;height:auto;border:0"></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="column column-2" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:middle;border-top:0;border-right:0;border-bottom:0;border-left:0">
                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                    <tbody>
                        <tr>
                            <td style="padding-top: 0;">
                                <div style="color:#555;font-size:12px;text-align:right;mso-line-height-alt:14.399999999999999px">
                                    <h3 style="margin:0;word-break:break-word;margin-top:10px;font-weight:500;">
                                        © All Rights Reserved @ DVI
                                        Holidays | 2024</h3>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</div>
<!-- End Footer -->
</div>
<div style="margin: 10px 0px;">
    <button onclick="copyToClipboard()">Copy UI</button>
</div>
<script>
    function copyToClipboard() {
        var contentToCopy = document.getElementById('contentToCopy');
        var range = document.createRange();
        range.selectNode(contentToCopy);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);

        try {
            document.execCommand('copy');
            alert('UI copied to clipboard');
        } catch (err) {
            console.error('Unable to copy UI to clipboard', err);
        }

        window.getSelection().removeAllRanges();
    }


    //    // Define a function to copy content to clipboard
    //    function copyToClipboard() {
    //         var contentToCopy = document.getElementById('contentToCopy').innerHTML;

    //         // Create a temporary textarea to hold the content
    //         var tempTextarea = document.createElement('textarea');
    //         tempTextarea.value = contentToCopy;

    //         // Append the textarea to the body
    //         document.body.appendChild(tempTextarea);

    //         // Select the content in the textarea
    //         tempTextarea.select();

    //         // Copy the selected content
    //         document.execCommand('copy');

    //         // Remove the temporary textarea
    //         document.body.removeChild(tempTextarea);

    //         // Show an alert indicating successful copy
    //         alert('Content copied to clipboard');
    //     }



    window.onload = function() {
        // Get all elements with class 'row' that have IDs starting with 'vertical_border_'
        var rows = document.querySelectorAll('[id^="vertical_border_"]');

        // Loop through each row
        rows.forEach(function(row) {
            // Get the ID of the corresponding vertical-divider element
            var dividerId = 'vertical-divider-' + row.id.split('_')[2] + '-' + row.id.split('_')[3];

            // Find the corresponding vertical-divider element
            var divider = document.getElementById(dividerId);

            // Set the height of the vertical divider to match the height of the row
            if (divider) {
                var leftContentHeight = row.offsetHeight - 20;
                divider.style.height = leftContentHeight + 'px';
            }
        });
    };
</script>
</body>

</html>