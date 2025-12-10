<?php
include_once('jackus.php');

$itinerary_plan_ID = '1';


$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
        <div style="padding: 20px; background-color: #fdf7fc; font-family: Public Sans, -apple-system, BlinkMacSystemFont, Segoe UI, Oxygen, Ubuntu, Cantarell, Fira Sans, Droid Sans, Helvetica Neue, sans-serif; font-size: 0.9375rem; font-weight: 400; color: #5d596c;  width: 800px;">

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
                                                <img src="<?= BASEPATH; ?>assets/img/logo-preview.png" style="display:block;height:auto;border:0;width:90px;max-width:100%" width="90" alt="Alternate text" title="Alternate text">
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
                                                                    <span><strong>Over All Package Cost <?= general_currency_symbol . ' ' . number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'itineary_gross_total_amount') + $TOTAL_ITINEARY_GUIDE_CHARGES, 2); ?></strong></span>
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
                                                                                                <img src="<?= BASEPATH; ?>assets/img/Airplane_outline.gif" style="display:block;height:auto;border:0;width:138px;max-width:100%" width="138" alt="Alternate text" title="Alternate text">
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
                                                                    <strong><span><?= $no_of_nights; ?>Nights, <?= $no_of_days; ?> Days</span></strong>
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
                                                            <img src="<?= BASEPATH; ?>assets/img/plane-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                    <?php
                                                    $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                                    if ($total_itinerary_plan_route_details_count > 0) :
                                                        $last_day_ending_location = NULL;
                                                        while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                            $itineary_route_count++;
                                                            $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                            $location_name = $fetch_itinerary_plan_route_data['location_name'];
                                                            $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                                                            $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                                                            $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
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
                                                            <tr>
                                                                <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                                    <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 20px 30px 0px 30px; background-color:#ffffff; ">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="left" class="container-padding" style="position: relative;">
                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="center" valign="middle">
                                                                                                    <img src="<?= BASEPATH; ?>assets/img/calendar.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                                </td>
                                                                                                <td width="15">
                                                                                                    &nbsp;
                                                                                                </td>
                                                                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                    <p align="left" valign="middle" style="color:#191919;font-size: 18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Day <?= $itineary_route_count; ?></b> - <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?></p>
                                                                                                    <p align="left" valign="middle" style="color:#191919;font-size:18px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false"><?= $location_name; ?> to <?= $next_visiting_location; ?></p>
                                                                                                </td>
                                                                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="top" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;position:absolute;right:0px;" data-gramm="false">
                                                                                                    <div style="display:flex;"><span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false"><?= date('h:i A', strtotime($route_start_time)); ?></span>
                                                                                                        <span style="display:flex; align-items: center;margin: 0px 3px"><img src="<?= BASEPATH; ?>assets/img/time-period.png" alt="Icon" width="16" border="0" style="width: 16px;border:0px;display:inline-block !important;"></span>
                                                                                                        <span align="left" valign="middle" style="color:#191919;font-size: 16px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false"><?= date('h:i A', strtotime($route_end_time)); ?></span>
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
                                                         
                                            </table>

                                                            <!-- Hotspot 1 -->
                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%; background-color:#ffffff;">
                                                                <tbody>
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

                                                                                    if ($item_type == 1) :

                                                                                        if ($last_day_ending_location == NULL) :
                                                                                            $last_day_ending_location =  $next_visiting_location;
                                                                                        endif;

                                                                              ?>
                                                                      <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%; background-color:#ffffff;">
    
    <tbody>
            <tr>
                <td align="center" class="container-padding">
                    <!-- <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0 18px;">
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
                                                                        7:00 AM
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px;margin-left: auto;margin-right: auto;">
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
                    </table> -->
                </td>
            </tr>
            <tr>
                <td align="center" class="container-padding">
                    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0 18px; ">
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
                                                                    <img src="<?= BASEPATH; ?>assets/img/refresh.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                </td>
                                                                <td width="15">
                                                                    &nbsp;
                                                                </td>
                                                             
                                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:20px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                    <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false"><?= getGLOBALSETTING('itinerary_break_time'); ?></p>
                                                                    <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false"><?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?></p>
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
    <?php
                                                                                            $last_day_ending_location =  $next_visiting_location;
                                                                                        endif;
                                                                                        ?>
                                                                                    
                                                                                          <?php if ($item_type == 3) : ?>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;padding: 0 18px; background-color:#ffffff;">
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
                                                    <img src="<?= BASEPATH; ?>assets/img/travel.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                </td>
                                                <td width="15">
                                                    &nbsp;
                                                </td>
                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:20px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                    <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Travelling</p>
                                                    <div style="display: flex;margin-top: 3px;">
                                                        <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/distance.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;"> <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?></p>
                                                        <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/distance.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">  <?= $hotspot_travelling_distance; ?> KM</p>
                                                        <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/timer.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;"> <?= formatTimeDuration($hotspot_traveling_time); ?> (This may vary due to traffic conditions)</p>
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
</tbody>
</table>
<?php endif; ?>
                                                                            <tr>
                                                                                <td align="center" class="container-padding">
                                                                                <?php if ($item_type == 4 && $hotspot_ID != 0) :
                                                                                    ?>
                                                                                    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;padding: 0px 18px;background-color:#ffffff;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                        <tbody>

                                                                                                            <tr>
                                                                                                                <td align="center" valign="middle">
                                                                                                                    <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width:289px; */padding: 20px 35px;background-color: #d161b92e;border-radius: 10px;">
                                                                                                                        <tbody>
                                                                                                                            <tr>
                                                                                                                                <td>
                                                                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="margin-bottom: 12px;width: 100%;">
                                                                                                                                        <tbody>
                                                                                                                                            <tr>
                                                                                                                                                <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:18px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;" data-gramm="false">
                                                                                                                                                    <?= $hotspot_name; ?>
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
                                                                                                                                                                                    <img data-image="Clock Icon" src="<?= BASEPATH; ?>assets/img/time.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                                                                </td>
                                                                                                                                                                                <td width="5">
                                                                                                                                                                                    &nbsp;
                                                                                                                                                                                </td>
                                                                                                                                                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                                    <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td style="vertical-align: middle;">
                                                                                                                                                                                    <img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/hour-glass.png" alt="Icon" width="20" style="width:20px;">
                                                                                                                                                                                </td>
                                                                                                                                                                                <td width="5">
                                                                                                                                                                                    &nbsp;
                                                                                                                                                                                </td>
                                                                                                                                                                                <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                                                                    <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td align="center" valign="top">
                                                                                                                                                                                    <img data-image="Clock Icon" src="<?= BASEPATH; ?>assets/img/details.png" alt="Icon" width="18" style="width:18px;margin-top: 4px;">
                                                                                                                                                                                </td>
                                                                                                                                                                                <td width="5">
                                                                                                                                                                                    &nbsp;
                                                                                                                                                                                </td>
                                                                                                                                                                                <td data-text="Icon Description" data-font="Primary" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                                                                    <?= $hotspot_description; ?>
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
                                                                                                                                                    <div><img data-image="Package Image" src="uploads/hotspot_gallery/<?= $hotspot_gallery_name; ?>" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 120px;max-width: 120px;height: 120px;max-height: 145px;border-radius: 10px;"></div>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                    <!-- Activity -->
                                                                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="padding-top: 12px;">
                                                                                                                                        <tbody>
                                                                                                                                            <!-- Content on the right side -->
                                                                                                                                            <?php
                                                                                                                                                            $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                                                                                                                                                            $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                                                                                                                                                            if ($total_hotspot_activity_num_rows_count > 0) :
                                                                                                                                                            ?>
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
                                                                                                                                                                    $activity_rating = $fetch_hotspot_activity_data['activity_rating'];
                                                                                                                                                                    $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                                                                                                                                                    $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                                                                                                                                            ?>
                                                                                                                                            <tr>
                                                                                                                                                <td align="left" class="container-padding">
                                                                                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                        <tbody>

                                                                                                                                                            <tr>
                                                                                                                                                                <td align="center" valign="middle">
                                                                                                                                                                    <!-- <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-46.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;"> -->
                                                                                                                                                                    <img src="<?= BASEPATH; ?>assets/img/activity.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
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
                                                                                                                                                                                                    <div style="font-weight: 600;color: #ffffff;background-color: #d161b9;padding: 4px 6px 5px;font-size: 13.5px;white-space: nowrap;border-radius:10px;text-align: center;border: 2px solid #d161b9;">
                                                                                                                                                                                                    <?= date('h:i A', strtotime($activity_start_time)); ?> - <?= date('h:i A', strtotime($activity_end_time)); ?>
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

                                                                                                                                                    <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 90px;/* max-width:40px; */">
                                                                                                                                                        <tbody>
                                                                                                                                                            <tr>
                                                                                                                                                                <td data-shape-divider="Vertical Divider" class="hide-mobile">
                                                                                                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                                                                                                                                        <tbody></tbody>
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
                                                                                                                                                                                            <?= $activity_title ?> </td>
                                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: middle;" data-gramm="false">
                                                                                                                                                                                            <div class="center-text" style="color:#191919;font-size:16px;font-weight:600;letter-spacing:0px;margin:5px 0;display: flex;align-content: center;align-items: center;justify-content: flex-end;"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/ticket.png" alt="Icon" width="20" style="width:20px;"> <span style="margin-left: 5px;"><?= general_currency_symbol; ?><?= $activity_amout ?></span></div>
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
                                                                                                                                                                                            <img data-image="Icon 1" src="uploads/activity_gallery/<?= $get_first_activity_image_gallery_name; ?>" alt="Icon" width="60" border="0" style="width: 120px;border:0px;display:inline-block !important;height: 120px;border-radius: 10px;" data-lm-image="true">
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
                                                                                                                                                                                                                    <img data-image="Clock Icon" src="<?= BASEPATH; ?>assets/img/time.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                                                                    <?= date('h:i A', strtotime($activity_start_time)); ?> - <?= date('h:i A', strtotime($activity_end_time)); ?>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                <div class="col-6" style="width: 50%;display: flex;align-items: center;">
                                                                                                                                                                                                                    <img data-image="Clock Icon" src="<?= BASEPATH; ?>assets/img/hour-glass.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;">
                                                                                                                                                                                                                    <?= formatTimeDuration($activity_traveling_time); ?>
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
                                                                                                                                                                                                <tbody>                                                                                                                      <tr class="">
                                                                                                                                                                                                        <td style="vertical-align: top;">
                                                                                                                                                                                                            <img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/details.png" alt="Icon" width="18" style="width:18px; margin-top: 4px;">
                                                                                                                                                                                                        </td>
                                                                                                                                                                                                        <td width="5">
                                                                                                                                                                                                            &nbsp;
                                                                                                                                                                                                        </td>
                                                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;text-align:justify;" data-gramm="false">
                                                                                                                                                                                                            <?= $activity_description ?>
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
                                                                                                                                            <?php endwhile; ?>
                                                                                                                                             <?php endif; ?>
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
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endwhile; ?>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>

                                                            <!-- Hotspot 1 -->
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Day 1 Section -->

                            <!-- Refresh & Relief -->

                            <!-- Refresh & Relief 1 -->

                            <!-- Hotspot Hotel -->
                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: auto;">
                                <tbody>
                                    <tr>
                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                            <table width="520" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;max-width: 100%;padding: 2px 30px 15px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" class="container-padding">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                    <tr>
                                                                                         <td>
                                                                                            <div style="background-color: #d161b9; font-size:2px; height:auto; width:2px; max-width:2px; height:20px;margin-left: auto;margin-right: auto;">
                                                                                                 </div>
                                                                                                      </td>
                                                                                           </tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img src="<?= BASEPATH; ?>assets/img/hotel.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" data-gramm="false">
                                                                                            <p align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;margin:0px;" data-gramm="false">Return to <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></p>
                                                                                            <div style="display: flex;margin-top: 3px;">
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/time.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;"> <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?></p>
                                                                                                <p align="left" valign="middle" style="color:#191919;font-size:14px;font-weight:600;letter-spacing:0px;margin:0px;margin-right:10px;display:flex;align-items: center;" data-gramm="false"><img data-image="Icon 5" src="<?= BASEPATH; ?>assets/img/distance.png" alt="Icon" width="18" style="width:18px;margin-right: 5px;"> <?= $hotspot_travelling_distance; ?>KM</p>
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
                                                                            <img src="<?= BASEPATH; ?>assets/img/hotel-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text" />
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
                                            Recommended Hotel - 1 (<?= general_currency_symbol . ' ' . number_format(getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES'), 2); ?>)
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
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:25%;">Name</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:18%;">Category</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Room type</th>
                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:25%;">Price</th>
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
                                                HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID'
                                            GROUP BY 
                                                HOTEL_DETAILS.`itinerary_route_date`
                                            ORDER BY 
                                                HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` ASC
                                            ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);
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

                                                        $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
                                                        $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_id, '', 'get_room_gallery_1st_IMG');
                                                        if ($get_room_gallery_1st_IMG) :
                                                            $get_room_gallery_1st_IMG = $get_room_gallery_1st_IMG;
                                                        else :
                                                            $get_room_gallery_1st_IMG = 'no_image_for_room.png';
                                                        endif;

                                                ?>
                                                        <tr>

                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:25%;">
                                                                <div style="display:flex;">
                                                                    <span><img src="uploads/room_gallery/<?= $get_room_gallery_1st_IMG; ?>" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                                    <div>
                                                                        Day- <?= $hotel_counter; ?> | <?= dateformat_datepicker($itinerary_route_date); ?> </br>
                                                                        <?php if ($hotel_required == 1) : ?>
                                                                            <span data-toggle="tooltip" placement="top" title="<?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?>"><i class="fa-solid fa-hotel me-1 hotelIcon"></i><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?></span>
                                                                        <?php else : ?>
                                                                            <span>--</span>
                                                                        <?php endif; ?></br> <?php if ($hotel_required == 1) : ?>
                                                                            <span data-toggle="tooltip" placement="top" title="<?= $itinerary_route_location; ?>"><?= $itinerary_route_location; ?></span>
                                                                        <?php else : ?>
                                                                            <span>--</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:18%">
                                                                <span data-toggle="tooltip" placement="top" title="<?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>"><?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>
                                                            </td>
                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%">
                                                                <?php if ($hotel_required == 1) : ?>
                                                                    <span data-toggle="tooltip" placement="top" title="<?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?>"><?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?></span>
                                                                <?php else : ?>
                                                                    <span>--</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:25%">
<p class="col-12 p-0 m-0"><b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b></p>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </table>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <!--/ Recommended Hotel - 1 -->

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
                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="<?= BASEPATH; ?>assets/img/vehicle-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                        <td class="container-padding">
                                                                                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
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
                                                            <!-- <table class="paragraph_block block-1 " width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word; margin-bottom:20px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                                            <div style="font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Vendor Name</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 16px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= getVENDOR_DETAILS($vendor_id, 'label'); ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Branch Name</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 16px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span> <?= getBranchLIST($vendor_branch_id, 'branch_label'); ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style="padding: 16px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span style="color: #afafaf;font-size: 14px;font-weight: 500;">Vehicle Origin</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="font-size: 16px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $vehicle_orign; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table> -->

                                                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: 10px;padding: 0 18px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-bgcolor="Outter Bgcolor" align="center" valign="middle" bgcolor="#fff" style="background-color:#fff;">
                                                                            <table style="width:100%; border-collapse: collapse;">
                                                                                <tr>

                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Vehicle Details</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Traveling KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Site Seeing KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:10%;">Total KM</th>
                                                                                    <th style="background-color: #f2f2f2; text-align: left; padding: 8px; width:35%;">Total Amount</th>
                                                                                </tr>
                                                                                <?php
                                                                                $select_itinerary_plan_vendor_vehicle_summary_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_route_date`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1'  AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
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
                                                                                        $itinerary_route_location_to = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_to'];
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
                                                                                        if ($travel_type == 1) :
                                                                                            $travel_type_label = 'Local';
                                                                                        elseif ($travel_type == 2) :
                                                                                            $travel_type_label = 'Outstation';
                                                                                        else :
                                                                                            $travel_type_label = '--';
                                                                                        endif;

                                                                                        $get_total_outstation_trip = get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip');
                                                                                ?>
                                                                                        <tr>

                                                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%;">
                                                                                                <div style="display:flex;">
                                                                                                    <span><img src="<?= BASEPATH; ?>assets/img/vehi.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span>
                                                                                                    <div>
                                                                                                        Day-<?= $vendor_vehicle_day_count; ?> | <?= dateformat_datepicker($itinerary_route_date); ?> <br><span style="color:grey; font-size:13px;"> <?= $itinerary_route_location_from; ?> </br>
                                                                                                            <img src="<?= BASEPATH; ?>assets/img/down-arrow.png" width="15px" height="15px" /></br>
                                                                                                            <?= $itinerary_route_location_to; ?></span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%"><?= number_format($total_running_km, 2); ?> KM <br>
                                                                                                <?= formatTimeDuration($total_running_time); ?></td>
                                                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%"><?= number_format($total_siteseeing_km, 2); ?> KM <br>
                                                                                                <?= formatTimeDuration($total_siteseeing_time); ?></td>
                                                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:10%"><?= number_format($total_travelled_km, 2); ?> KM <br>
                                                                                                <?= formatTimeDuration($total_travelled_time); ?></td>

                                                                                            <td style="border-bottom: 1px solid #ddd; padding: 8px; font-size: 13px; width:3%">
                                                                                               
                                                                                                        <p class="col-12 p-0 m-0 text-end"><b><?= general_currency_symbol . ' ' . number_format($total_vehicle_amount, 2); ?></b></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>

                                                                                        </tr>
                                                                                    <?php endwhile; ?>
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
                                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="<?= BASEPATH; ?>assets/img/vehicle-details-dash.jpg" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                                                <h3 style="margin:0;word-break:break-word;font-weight:500;"><?= general_currency_symbol; ?>
                                                                                                                                    <span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'total_hotspot_amount'), 2); ?></span>
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
                                                                                                                                    <?= general_currency_symbol; ?><span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'total_activity_amout'), 2); ?></span>
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
                                                                                    <?php
                                                                                    if ($TOTAL_ITINEARY_GUIDE_CHARGES > 0) :
                                                                                    ?>
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
                                                                                                                                        <span>Total Guide Charges</span>
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
                                                                                                                                    <h3 style="margin:0;word-break:break-word;font-weight:500;"><?= general_currency_symbol; ?>
                                                                                                                                        <span><?= number_format($TOTAL_ITINEARY_GUIDE_CHARGES, 2); ?></span>
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
                                                                                    <?php endif; ?>
                                                                                    <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
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
                                                                                                                                    <h3 style="margin:0;word-break:break-word;font-weight:500;"><?= general_currency_symbol; ?>
                                                                                                                                        <span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'total_hotel_amount'), 2); ?></span>
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
                                                                                    <?php endif; ?>
                                                                                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
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
                                                                                                                                    <h3 style="margin:0;word-break:break-word;font-weight:500;"><?= general_currency_symbol; ?>
                                                                                                                                        <span><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'total_vehicle_amount'), 2); ?></span>
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
                                                                                                    <?= general_currency_symbol; ?><span><strong><?= number_format(getITINEARY_COST_DETAILS($itinerary_plan_ID, 'itineary_gross_total_amount') + $TOTAL_ITINEARY_GUIDE_CHARGES, 2); ?></strong></span>
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