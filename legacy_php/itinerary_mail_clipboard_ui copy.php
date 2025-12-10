<?php
include_once('jackus.php');

$itinerary_plan_ID = '1';

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `generated_quote_code`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
        $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
        $departure_location = $fetch_itinerary_plan_data['departure_location'];
        $generated_quote_code = $fetch_itinerary_plan_data['generated_quote_code'];
        $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
        $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
        $trip_start_date_and_time = date('M d, Y | g:i A', strtotime($trip_start_date_and_time));
        $trip_end_date_and_time = date('M d, Y | g:i A', strtotime($trip_end_date_and_time));
        $arrival_type = getTRAVELTYPE($fetch_itinerary_plan_data['arrival_type'], 'label');
        $departure_type = getTRAVELTYPE($fetch_itinerary_plan_data['departure_type'], 'label');
        $entry_ticket_required = get_YES_R_NO($fetch_itinerary_plan_data['entry_ticket_required'], 'label');
        $guide_for_itinerary = get_YES_R_NO($fetch_itinerary_plan_data['guide_for_itinerary'], 'label');
        $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
        $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
        $nationality = getCOUNTRYLIST($fetch_itinerary_plan_data['nationality'], 'country_label');
        $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
        $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
        $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
        $food_type = getFOODTYPE($fetch_itinerary_plan_data['food_type'], 'label');
    endwhile;
endif;

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
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">
    <div id="contentToCopy">
        <div style="padding: 20px; background-color: #fdf7fc; font-family: &quot;Public Sans&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Oxygen&quot;, &quot;Ubuntu&quot;, &quot;Cantarell&quot;, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 0.9375rem; font-weight: 400; color: #5d596c;  width: 800px;">

            <!--Start Header -->
            <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%">
                <tbody>
                    <tr>
                        <td class="column column-1" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align: middle;border-top:0;border-right:0;border-bottom:0;border-left:0">
                            <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                <tbody>
                                    <tr>
                                        <td class="pad" style="padding-left:25px;width:100%;padding-right:0">
                                            <div class="alignment" align="left" style="line-height:10px">
                                                <img src="assets/img/logo-preview.png" style="display:block;height:auto;border:0;width:90px;max-width:100%" width="90" alt="Alternate text" title="Alternate text">
                                                <!-- <img src="assets/img/plane-dash.jpg" style="display:block;height:auto;border:0;width:69px;max-width:100%" width="69" alt="Alternate text" title="Alternate text"> -->
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="column column-2" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                <tbody>
                                    <tr>
                                        <td class="pad">
                                            <div style="color:#555;font-size:12px;line-height:120%;text-align:right;mso-line-height-alt:14.399999999999999px">
                                                <h2 style="margin:0;word-break:break-word">DoView Holidays India
                                                    Pvt. Ltd.</h2>
                                                <!--<p style="margin:0;word-break:break-word">Member Nr. <span
                                                            style="color:#aea5af;"><strong>688969807</strong></span> |
                                                        Level <span style="color:#aea5af;"><strong>Basic</strong></span>
                                                    </p>-->
                                                <h3 style="margin:0;word-break:break-word;margin-top:10px;font-weight:500;font-size: 18px;"><span style="font-size: 14px; color: #afafaf;">Quote_ID </span>
                                                    <strong><span style="background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;"> DVIADMIN00A
                                                        </span></strong>
                                                </h3>
                                                <h3 style="margin:0;word-break:break-word;margin-top: 7px;font-weight:500;">
                                                    <span style="font-size: 14px; color: #afafaf;">Customer Car </span> <strong><span style="font-size: 18px;"> 9047776899</span></strong>
                                                </h3>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;margin-top: 15px;">
                <tbody>
                    <tr>
                        <td>
                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%); color:#fff;width:100%;border-top-left-radius: 10px;border-top-right-radius: 10px;" width="550">
                                <tbody>
                                    <tr>
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding-bottom:25px;padding-left:10px;padding-rights:10px;padding-top:25px">
                                                            <div style="color:#fff;font-size: 22px;line-height:120%;text-align:center;mso-line-height-alt:19.2px">
                                                                <h3 style="margin:0;word-break:break-word;font-weight:600;">
                                                                    <span><strong>Over All Package Cost ₹11,043.00</strong></span>
                                                                </h3>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;vertical-align: middle; padding-bottom: 25px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="35%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;border: 0;">
                                                                            <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding-bottom:10px;padding-top:10px">
                                                                                            <div style="color:#fff;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:50.4px">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span><strong>
                                                                                                            <?= $arrival_location; ?>
                                                                                                        </strong></span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div style="color:#fff;font-size:12px;line-height:120%;text-align:center;mso-line-height-alt:20.4px;margin-top:5px;">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span>Arrival <?= $arrival_type; ?></span>
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td class="column column-2" width="20%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;border: 0;">
                                                                            <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: auto;margin-bottom: auto;mso-table-lspace:0;mso-table-rspace:0;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="width:100%;padding-right:0;padding-left:0">
                                                                                            <div class="alignment" align="center" style="line-height:10px">
                                                                                                <img src="assets/img/Airplane_outline.gif" style="display:block;height:auto;border:0;width:138px;max-width:100%" width="138" alt="Alternate text" title="Alternate text">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td class="column column-3" width="35%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;border: 0;">
                                                                            <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding-bottom:10px;padding-top:10px">
                                                                                            <div style="color:#fff;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:50.4px">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span><strong><?= $departure_location; ?></strong></span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div style="color:#fff;font-size:12px;line-height:120%;text-align:center;mso-line-height-alt:20.4px;;margin-top:5px;">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span>Departure <?= $departure_type; ?></span>
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
            <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                <tbody>
                    <tr>
                        <td>
                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                                <tbody>
                                    <tr>
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Start Date & Time</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $trip_start_date_and_time; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">End Date & Time</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $trip_end_date_and_time; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding: 15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Trip Night &amp; Day</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $no_of_nights; ?> Nights, <?= $no_of_days; ?> Days</span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Entry Ticket Required</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $entry_ticket_required; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Guide for Whole Itineary</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $guide_for_itinerary; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding: 15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Nationality</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $nationality; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Person Count</span>
                                                                </p>
                                                            </div>
                                                            <div style="font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $total_adult; ?> Adult, <?= $total_children; ?> Children, <?= $total_infants; ?> Infant</span></strong>
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

            <!--Start Hotspot Divider -->
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
                                                            <img src="assets/img/plane-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
            <!--End Hotspot Divider -->

            <!-- Start Hotspot Details -->
            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                <tbody>
                    <tr>
                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">

                            <!-- Hotspot Title -->
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
                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:700;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" class="text-uppercase" data-gramm="false" data-lm-text="true">
                                                                                            Tour Itinerary
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td data-resizable-height="" style="font-size:20px;height:20px;line-height:20px;" class="ui-resizable">
                                                                                            &nbsp;
                                                                                            <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">
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
                            <!-- Hotspot Title -->

                            <!-- ********* Start Day 1 Itinerary ********* -->

                            <!-- Day 1 Section -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                            <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 0px 30px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="container-padding" style="position: relative;">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img src="assets/img/calendar.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="15">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size: 18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Day 1 - Mar 27,2024 (Wednesday)</p>
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Chennai, Tamil Nadu to Trichy, Tamil Nadu</p>
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="top" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;position:absolute;right:0px;" data-gramm="false">
                                                                                            <div style="display:flex;"><span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">7AM</span>
                                                                                                <span style="display:flex; align-items: center;margin: 0px 3px"><img src="assets/img/time-period.png" alt="Icon" width="16" border="0" style="width: 16px;border:0px;display:inline-block !important;"></span>
                                                                                                <span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">9PM</span>
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
                            <!-- Day 1 Section -->

                            <!-- Refresh & Relief -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="middle">
                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:40px;max-width:40px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; color: #fff; padding: 4px 6px 5px; font-size: 13.5px; white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                7:00&nbsp;AM
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="middle">
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="position:relative;left:11px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <img src="assets/img/refresh.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="15">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:20px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Refresh / Relief Period</p>
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">7:30 AM - 9:00 PM</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                            <!-- Refresh & Relief 1 -->

                            <!-- Hotspot 1 -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>

                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="margin-bottom: 12px;width: 100%;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">
                                                                                                            Marina Beach
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 545px;padding-right: 15px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom:15px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                            9:30 AM - 10:00 AM
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: middle;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/hour-glass.png" alt="Icon" width="20" style="width:20px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                            30 Min
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="top">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/details.png" alt="Icon" width="18" style="width:18px;margin-top: 4px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                            Marina Beach is a natural urban beach in Chennai, Tamil Nadu, along the Bay of Bengal. It is one of the longest beaches in the world, stretching over 13 kilometers. The beach is a major tourist attraction and a popular spot for locals, offering a scenic view of the sea and a refreshing breeze. The sandy shores are lined with palm trees, and the beach is bustling with activity, from morning walks and yoga sessions to horse rides and kite flying. Marina Beach is not just a place to relax and enjoy the sun; it is a part of Chennai's vibrant culture and a witness to the city's history.
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" valign="top">
                                                                                                            <div><img data-image="Package Image" src="https://dotrip.net/staging/head//uploads/hotspot_gallery/Marina_Beach.jpg" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 120px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <!-- Activity -->
                                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="padding-top: 12px;">
                                                                                                <tbody>
                                                                                                    <!-- Content on the right side -->
                                                                                                    <tr>
                                                                                                        <td align="left" class="container-padding">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                <tbody>

                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle">
                                                                                                                            <!-- <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-46.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;"> -->
                                                                                                                            <img src="assets/img/activity.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                                                        </td>
                                                                                                                        <td width="15">
                                                                                                                            &nbsp;
                                                                                                                        </td>
                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                            <span align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;position:relative;right: 25px;" data-gramm="false">Activity
                                                                                                                            </span>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td data-btn="Button Link" align="center" style="display:block;">
                                                                                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 90px;/* max-width:40px; */">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="/* background-color: #d161b9; */font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto; /* Change 'black' to any color you want */border-left: 2px dashed #d161b9; /* Adjust width and color as needed */ /* Adjust height as needed */">
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="font-weight: 600;color: #ffffff;background-color: #d161b9;padding: 4px 6px 5px;font-size: 13.5px;white-space: nowrap;border-radius:10px;width: 55px;text-align: center;border: 2px solid #d161b9;">
                                                                                                                                                                10:00&nbsp;AM
                                                                                                                                                            </div>
                                                                                                                                                        </td>

                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="/* background-color: #d161b9; */font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #d161b9;">
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
                                                                                                    <tr>
                                                                                                        <td align="center" valign="middle">
                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 97%;max-width: auto;background-color: white;padding: 6px 15px 6px;vertical-align: middle;border-radius: 10px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width: 70px; */">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;max-width: 150px;" data-gramm="false">
                                                                                                                                            Diving Adventure
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: middle;" data-gramm="false">
                                                                                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;display: flex;align-content: center;align-items: center;justify-content: flex-end;"><img data-image="Icon 5" src="assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;">₹1000.00</span></div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle" style="vertical-align: middle;">
                                                                                                                            <table width="60" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 70px;max-width: 70px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="top">
                                                                                                                                            <img data-image="Icon 1" src="https://editor.maool.com/images/travel/package@img-2.png" alt="Icon" width="60" border="0" style="width: 120px;border:0px;display:inline-block !important;height: 120px;border-radius: 10px;" data-lm-image="true">
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                            <table width="10" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 10px;max-width: 10px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" height="20">
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 510px;/* max-width: 100%; */">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:5px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="width: 100%;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="display: flex;margin-bottom: 5px;">
                                                                                                                                                                <div class="col-6" style="width: 50%;display: flex;align-items: center;">
                                                                                                                                                                    <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                    10:00 AM - 10:30 AM
                                                                                                                                                                </div>
                                                                                                                                                                <div class="col-6" style="width: 50%;display: flex;align-items: center;">
                                                                                                                                                                    <img data-image="Clock Icon" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                    30 Min
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:5px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>

                                                                                                                                                    <tr class="">
                                                                                                                                                        <td style="vertical-align: top;">
                                                                                                                                                            <img data-image="Icon 5" src="assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">

                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                                            Explore the ocean's wonders beneath the surface. Dive into a world of vibrant marine life and stunning underwater landscapes. An adventure awaits!
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
                                                                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 90px;/* max-width:40px; */">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td>
                                                                                                                                            <div style="font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #d161b9;">
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td>
                                                                                                                                            <div style="background-color: #d161b9;color: #fff;padding: 4px 6px 5px;font-size: 13.5px;white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                                                                10:30&nbsp;AM
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td>
                                                                                                                                            <div style="font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #d161b9; /* Adjust color and gap size as needed */">
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 97%;max-width: auto;background-color: white;padding: 6px 15px 6px;vertical-align: middle;border-radius: 10px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width: 70px; */">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;max-width: 150px;" data-gramm="false">
                                                                                                                                            Skydive in Marina Beach explore</td>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: middle;" data-gramm="false">
                                                                                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;display: flex;align-content: center;align-items: center;justify-content: flex-end;"><img data-image="Icon 5" src="assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;">₹600.00</span></div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle" style="vertical-align: middle;">
                                                                                                                            <table width="60" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 70px;max-width: 70px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="top">
                                                                                                                                            <img data-image="Icon 1" src="https://editor.maool.com/images/travel/package@img-17.png" alt="Icon" width="60" border="0" style="width: 120px;border:0px;display:inline-block !important;height: 120px;border-radius: 10px;" data-lm-image="true">
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                            <table width="10" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 10px;max-width: 10px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" height="20">
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 510px;/* max-width: 100%; */">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:5px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="" style="width: 100%;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="display: flex;margin-bottom: 5px;">
                                                                                                                                                                <div class="col-6" style="width: 50%;display: flex;align-items: center;">
                                                                                                                                                                    <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                    10:30 AM - 11:00 AM
                                                                                                                                                                </div>
                                                                                                                                                                <div class="col-6" style="width: 50%;display: flex;align-items: center;">
                                                                                                                                                                    <img data-image="Clock Icon" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                    30 Min
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>

                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:5px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>

                                                                                                                                                    <tr class="">
                                                                                                                                                        <td style="vertical-align: top;">
                                                                                                                                                            <img data-image="Icon 5" src="assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">
                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                                            Soar above Marina Beach and experience the thrill of a lifetime. Dive into the sky and enjoy stunning views on this unforgettable adventure!
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
                                                                                            <!-- Activity -->
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
                            <!-- Hotspot 1 -->

                            <!-- Hotspot 2 -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>
                                                    <tr>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" class="container-padding">
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="position:relative;left:11px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td style="display: flex;">
                                                                                                            <img src="assets/img/alert-1.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                                        </td>
                                                                                                        <td width="15">
                                                                                                            &nbsp;
                                                                                                        </td>
                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:20px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">You have deviated from our suggestion and implement your approch.</p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                                        <td>
                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                                                <tbody>

                                                                                                    <tr>
                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">Zoo</td>
                                                                                                        <td>
                                                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;padding-right:6px;display: flex;align-content: center;align-items: center;justify-content: end;"><img data-image="Icon 5" src="assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;">₹400.00</span></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 545px; padding-right: 15px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">11:00 AM - 12:00 PM</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: middle;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">1 Hr</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr class="">
                                                                                                                                        <td style="vertical-align: top;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">

                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                            Experience the wonders of the animal kingdom up close. Visit the zoo and encounter a diverse range of fascinating creatures from around the world. A day filled with discovery and awe awaits!
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" class="">
                                                                                                            <div class=""><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-16.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 145px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
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
                            <!-- Hotspot 2 -->

                            <!-- Hotspot 3 -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>
                                                    <tr>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" class="container-padding">
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="position:relative;left:11px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <img src="assets/img/travel.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                                        </td>
                                                                                                        <td width="15">
                                                                                                            &nbsp;
                                                                                                        </td>
                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:20px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Travel to Trichy.</p>
                                                                                                            <div style="display: flex;margin-top: 3px;">
                                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/distance.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">160 KM</p>
                                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/timer.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">2hrs (This may vary due to traffic conditions)</p>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                                        <td>
                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                                                <tbody>

                                                                                                    <tr>
                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">LA Cinemas</td>
                                                                                                        <td>
                                                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;padding-right:6px;display: flex;align-content: center;align-items: center;justify-content: end;"><img data-image="Icon 5" src="assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;">₹239.00</span></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 545px; padding-right: 15px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">01:30 PM - 05:00 PM</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: middle;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">3 Hr 30 Min</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: top;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                            LA Cinemas offers modern theaters and a wide movie selection for an enjoyable viewing experience.
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" class="">
                                                                                                            <div class=""><img data-image="Package Image" src="assets/img/theatre.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 145px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
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
                            <!-- Hotspot 3 -->

                            <!-- Time -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>
                                                    <tr>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" class="container-padding">
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:40px;max-width:40px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; color: #fff; padding: 4px 6px 5px; font-size: 13.5px; white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                                07:00&nbsp;PM
                                                                                                            </div>
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                                        <td>
                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
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
                            <!--Time -->

                            <!-- Hotspot Hotel -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: auto;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                            <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 0px 30px 15px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="container-padding">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img src="assets/img/hotel.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Garden Hotel</p>
                                                                                            <div style="display: flex;margin-top: 3px;">
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">07:00 PM</p>
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/location.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">No 2, First Street, 3rd Block, Chennai - 600002.</p>
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
                            <!-- Hotspot Hotel -->

                            <!-- ********* End Day 1 Itinerary ********* -->

                            <!-- divider -->
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
                                                                            <img src="assets/img/day-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text" />
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
                            <!-- divider -->

                            <!-- ********* Start Day 2 Itinerary ********* -->

                            <!-- Day 2 Section -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                            <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 0px 30px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="container-padding" style="position: relative;">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img src="assets/img/calendar.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="15">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size: 18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Day 2 - Mar 28,2024 (Thursday)</p>
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Trichy, Tamil Nadu to Palani, Tamil Nadu</p>
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="top" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;position:absolute;right:0px;" data-gramm="false">
                                                                                            <div style="display:flex;"><span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">7AM</span>
                                                                                                <span style="display:flex; align-items: center;margin: 0px 3px"><img src="assets/img/time-period.png" alt="Icon" width="16" border="0" style="width: 16px;border:0px;display:inline-block !important;"></span>
                                                                                                <span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">9PM</span>
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
                            <!-- Day 2 Section -->

                            <!-- Hotspot -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>

                                                    <tr>
                                                        <td align="center" valign="middle">
                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:40px;max-width:40px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; color: #fff; padding: 4px 6px 5px; font-size: 13.5px; white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                9:00&nbsp;AM
                                                                                            </div>
                                                                                        </td>

                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                        <td>
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>

                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="margin-bottom: 12px;width: 100%;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">
                                                                                                            Marina Beach
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 545px;padding-right: 15px;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom:15px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                            9:30 AM - 10:00 AM
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: middle;">
                                                                                                                                            <img data-image="Icon 5" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                            30 Min
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="top">
                                                                                                                                            <img data-image="Clock Icon" src="assets/img/details.png" alt="Icon" width="18" style="width:18px;margin-top: 4px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                            Marina Beach is a natural urban beach in Chennai, Tamil Nadu, along the Bay of Bengal. It is one of the longest beaches in the world, stretching over 13 kilometers. The beach is a major tourist attraction and a popular spot for locals, offering a scenic view of the sea and a refreshing breeze. The sandy shores are lined with palm trees, and the beach is bustling with activity, from morning walks and yoga sessions to horse rides and kite flying. Marina Beach is not just a place to relax and enjoy the sun; it is a part of Chennai's vibrant culture and a witness to the city's history.
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" valign="top">
                                                                                                            <div><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-32.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 120px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
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
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="middle">
                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:40px;max-width:40px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; color: #fff; padding: 4px 6px 5px; font-size: 13.5px; white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                11:00&nbsp;AM
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                        <td align="center" valign="middle">
                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                <tbody>

                                                                    <tr>
                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">Zoo</td>
                                                                        <td>
                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;padding-right:6px;display: flex;align-content: center;align-items: center;justify-content: end;"><img data-image="Icon 5" src="assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;">₹400.00</span></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 545px; padding-right: 15px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" valign="middle">
                                                                                                            <img data-image="Clock Icon" src="assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                        </td>
                                                                                                        <td width="5">
                                                                                                            &nbsp;
                                                                                                        </td>
                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">11:00 AM - 12:00 PM</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td style="vertical-align: middle;">
                                                                                                            <img data-image="Icon 5" src="assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;">
                                                                                                        </td>
                                                                                                        <td width="5">
                                                                                                            &nbsp;
                                                                                                        </td>
                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">1 Hr</td>
                                                                                                    </tr>
                                                                                                    <tr class="">
                                                                                                        <td style="vertical-align: top;">
                                                                                                            <img data-image="Icon 5" src="assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">

                                                                                                        </td>
                                                                                                        <td width="5">
                                                                                                            &nbsp;
                                                                                                        </td>
                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                            Experience the wonders of the animal kingdom up close. Visit the zoo and encounter a diverse range of fascinating creatures from around the world. A day filled with discovery and awe awaits!
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td align="center" class="">
                                                                            <div class=""><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-16.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 145px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
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
                            <!-- Hotspot -->

                            <!-- Timer -->
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                <tbody>
                                                    <tr>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" class="container-padding">
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                <tbody>

                                                                    <tr>
                                                                        <td align="center" valign="middle">
                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:40px;max-width:40px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; color: #fff; padding: 4px 6px 5px; font-size: 13.5px; white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                                12:00&nbsp;PM
                                                                                                            </div>
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
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
                                                                        <td>
                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                <tbody>

                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

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
                            <!-- Timer -->

                            <!-- Direct Destination -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                            <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 0px 30px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="container-padding">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <!-- <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-38.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;"> -->
                                                                                            <img src="assets/img/destination.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>

                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size:18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Travel to Destination(Chennai).</p>
                                                                                            <div style="display: flex;margin-top: 3px;">
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/distance.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">260 KM</p>
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="assets/img/timer.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">5hrs (This may vary due to traffic conditions)</p>
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
                            <!-- Direct Destination -->

                            <!-- ********* Start Day 2 Itinerary ********* -->




                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End Hotspot Details -->

            <!-- Start Hotel Details -->
            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;width:100%" width="550">
                <tbody>
                    <tr>
                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
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
                                                                            <img src="assets/img/hotel-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text" />
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
                                                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                                                                                                            Hotel Details
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

                            <!-- Recommended Hotel - 1 -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                            Recommended Hotel - 1 (₹50,000.00 Approx)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table style="width:100%; border-collapse: collapse;">
                                                <tr>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Day</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:26%;">Hotel Name</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%;">Room Category</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:20%;">Check In & Out</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Persons</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Meal</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Price</th>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-1</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/parkhotel.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                ITC Grand Chola <br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 1(Delux)</br> Room - 1(Non-Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</br>Infant - 3</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹31,000</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/hotel-1.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                The Park Hotel<br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 2(Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</br>Infant - 3</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹31,000</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--/ Recommended Hotel - 1 -->

                            <!-- Recommended Hotel - 2 -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                            Recommended Hotel - 2 (₹45,000.00 Approx)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table style="width:100%; border-collapse: collapse;">
                                                <tr>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Day</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:26%;">Hotel Name</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%;">Room Category</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:20%;">Check In & Out</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Persons</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Meal</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Price</th>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-1</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/parkhotel.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                Le Royal Meridien <br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 1(Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹28,499</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/hotel-1.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                The Taj Hotel <br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 1(Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹29,000</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--/ Recommended Hotel - 2 -->

                            <!-- Recommended Hotel - 3 -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:22px;line-height:40px;font-weight:600;letter-spacing:0px;background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));-webkit-background-clip: text;-webkit-text-fill-color: transparent;" data-gramm="false" data-lm-text="true">
                                            Recommended Hotel - 3 (₹40,000.00 Approx)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table style="width:100%; border-collapse: collapse;">
                                                <tr>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Day</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:26%;">Hotel Name</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:15%;">Room Category</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:20%;">Check In & Out</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Persons</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:8%;">Meal</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Price</th>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-1</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/hotel-2.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                Hablis <br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 1(Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹26,000</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%;">Day-2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:26%;">
                                                        <div style="display:flex;">
                                                            <span><img src="assets/img/itinerary/hotels/hotel-1.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                            <div>
                                                                Turyaa Hotel <br><span style="color:lightgrey; font-size:10px;">5 Star Hotel</span></br>Chennai
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%;">Room - 1(Delux)</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:20%">7 Apr 2024 11:29 AM to 7 Apr 2024 09:30 PM</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">Adult - 1</br>Child - 2</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:8%">All</td>
                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">₹24,500</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--/ Recommended Hotel - 3 -->
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End Hotel Details -->

            <!-- Start Vehicle Details -->
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
                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="assets/img/vehicle-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                                            Vehicle Details
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td data-resizable-height="" style="font-size:20px;height:20px;line-height:20px;" class="ui-resizable">
                                                                                                                            &nbsp;
                                                                                                                            <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">
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
                                                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                            <table style="width:100%; border-collapse: collapse;">
                                                                                <tr>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Day's</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:20%;">Vehicle Details</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:18%; text-align:center;">Travel Place</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Traveling KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Site Seeing KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Total KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:13%;">Total Amount</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 14px; font-weight:600; width:13%;">5 Apr 2024<br>
                                                                                        <span style="font-size: 12px;">Day-1</span>
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 15px; font-weight:600; width:20%;">
                                                                                        <div style="display:flex;">
                                                                                            <span><img src="assets/img/innova.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                                                            <div>
                                                                                                Innova <br><span style="color:grey; font-size:10px;">Occupancy : 5</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%; text-align:center;">
                                                                                        Chennai </br>
                                                                                        <img src="assets/img/down-arrow.png" width="15px" height="15px" /></br>
                                                                                        Pondicherry
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">100</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">50</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">150</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">₹10,000</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 14px; font-weight:600; width:13%;">6 Apr 2024<br>
                                                                                        <span style="font-size: 12px;">Day-2</span>
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 15px; font-weight:600; width:20%;">
                                                                                        <div style="display:flex;">
                                                                                            <span><img src="assets/img/sedan.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                                                            <div>
                                                                                                sedan <br><span style="color:grey; font-size:10px;">Occupancy : 4</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%; text-align:center;">
                                                                                        Pondicherry</br>
                                                                                        <img src="assets/img/down-arrow.png" width="15px" height="15px" /></br>
                                                                                        Trichy
                                                                                    </td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">120</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">60</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">180</td>
                                                                                    <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:13%">₹12,000</td>
                                                                                </tr>
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
                            <!-- Vehicle Details -->
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End Vehicle Details -->

            <!-- Start Summary -->
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
                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="assets/img/vehicle-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>Total for the Hotspot</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹0.00</span>
                                                                                                </h3>
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
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>Total for the Activity</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹2,000.00</span>
                                                                                                </h3>
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
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>Total for the Hotel</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹6,800.00</span>
                                                                                                </h3>
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
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>Total for the Vehicle</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹1,717.00</span>
                                                                                                </h3>
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
                                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="assets/img/vehicle-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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

                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>Gross Total for the Package</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹10,517.00</span>
                                                                                                </h3>
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
                                                        <td>
                                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%;" width="550">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>GST @ 5 % on the Total Package</span>
                                                                                                </h3>
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
                                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pad" style="padding: 0px 10px 15px 10px">
                                                                                            <div style="font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;">
                                                                                                    <span>₹526.00</span>
                                                                                                </h3>
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
            <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%);border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                <tbody>
                    <tr>
                        <td>
                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#fff;width:100%;" width="550">
                                <tbody>
                                    <tr>
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding:15px 10px;">
                                                            <div style="color:#fff;font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px">
                                                                <h3 style="margin:0;word-break:break-word;font-weight:400;">
                                                                    <span><strong>Net Payable to Doview Holidays India Pvt ltd</strong></span>
                                                                </h3>
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
                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#fff;width:100%;" width="550">
                                <tbody>
                                    <tr>
                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding:15px 10px;">
                                                            <div style="color:#fff;font-size:16px;line-height:120%;text-align:right;mso-line-height-alt:19.2px">
                                                                <h3 style="margin:0;word-break:break-word;font-weight:400;">
                                                                    <span><strong>₹11,043.00</strong></span>
                                                                </h3>
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
            <!-- End Summary -->

            <!-- Start Footer -->
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
                                            <div style="color:#555;font-size:12px;line-height:120%;text-align:right;mso-line-height-alt:14.399999999999999px">
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
            <!-- End Footer -->
        </div>
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