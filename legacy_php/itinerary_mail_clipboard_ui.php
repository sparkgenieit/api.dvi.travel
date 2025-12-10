<?php
include_once('jackus.php');

$itinerary_plan_ID = '1';

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `generated_quote_code`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `vehicle_type`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">
    <div id="contentToCopy">
        <div style="padding: 20px; background-color: #fdf7fc; font-family: &quot;Public Sans&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Oxygen&quot;, &quot;Ubuntu&quot;, &quot;Cantarell&quot;, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 0.9375rem; font-weight: 400; color: #5d596c;  width: 800px;">

            <!-- Header -->
            <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;width:100%">
                <tbody>
                    <tr>
                        <td class="column column-1" width="50%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align: middle;border-top:0;border-right:0;border-bottom:0;border-left:0">
                            <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                <tbody>
                                    <tr>
                                        <td class="pad" style="padding-left:25px;width:100%;padding-right:0">
                                            <div class="alignment" align="left" style="line-height:10px">
                                                <img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/logo_airline.png" style="display:block;height:auto;border:0;width:69px;max-width:100%" width="69" alt="Alternate text" title="Alternate text">
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
                                                <h3 style="margin:0;word-break:break-word;margin-top:10px;font-weight:500;font-size: 18px;">Quote_ID
                                                    <strong><span style="color: #ea5357;">DVIADMIN00A
                                                        </span></strong>
                                                </h3>
                                                <h3 style="margin:0;word-break:break-word;margin-top: 7px;font-weight:500;">
                                                    Customer Care <strong><span style="color:#aea5af;">9047776899</span></strong></h3>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Header -->

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
                                                        <td class="pad" style="padding-bottom:25px;padding-left:10px;padding-right:10px;padding-top:25px">
                                                            <div style="color:#fff;font-size: 20px;line-height:120%;text-align:center;mso-line-height-alt:19.2px">
                                                                <h3 style="margin:0;word-break:break-word;font-weight:400;">
                                                                    <span><strong>Over All Package Cost ₹ 11,043</strong></span>
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
                                                                                            <div style="color:#fff;font-size:22px;line-height:120%;text-align:center;mso-line-height-alt:50.4px">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span><strong>
                                                                                                            <?= $arrival_location; ?>
                                                                                                        </strong></span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div style="color:#fff;font-size:17px;line-height:120%;text-align:center;mso-line-height-alt:20.4px">
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
                                                                                                <img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/Airplane_.gif" style="display:block;height:auto;border:0;width:138px;max-width:100%" width="138" alt="Alternate text" title="Alternate text">
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
                                                                                            <div style="color:#fff;font-size:22px;line-height:120%;text-align:center;mso-line-height-alt:50.4px">
                                                                                                <p style="margin:0;word-break:break-word">
                                                                                                    <span><strong><?= $departure_location; ?></strong></span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div style="color:#fff;font-size:17px;line-height:120%;text-align:center;mso-line-height-alt:20.4px">
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
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>Start Date & Time</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $trip_start_date_and_time; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>End Date & Time</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $trip_end_date_and_time; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding: 15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>Trip Night &amp; Day</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
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
                                                                    <span>Entry Ticket Required</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $entry_ticket_required; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>Guide for Whole Itineary</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $guide_for_itinerary; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding: 15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>Nationality</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <strong><span><?= $nationality; ?></span></strong>
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                            <div style="color:#232323;font-size: 16px;line-height:120%;mso-line-height-alt:20.4px">
                                                                <p style="margin:0;word-break:break-word">
                                                                    <span>Person Count - 10</span>
                                                                </p>
                                                            </div>
                                                            <div style="color:#aea5af;font-size: 18px;line-height:120%;mso-line-height-alt:24px;margin-top:5px;">
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

            <!-- Hotspot Details -->
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
                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:20px;line-height:40px;font-weight:600;letter-spacing:0px;" data-gramm="false" data-lm-text="true">
                                                                                            TOUR
                                                                                            ITINERARY
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


                            <!-- Day 1 -->
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
                                                                        <td align="left" class="container-padding">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <!-- Icons and descriptions -->
                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-46.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <span align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;" data-gramm="false">DAY 1 - Mar 29,2024 (Friday)
                                                                                            </span><span align="left" valign="middle" style="color:#191919;font-size:18px;line-height:28px;font-weight:600;letter-spacing:0px;" data-gramm="false"> | Chennai, Tamil Nadu
                                                                                            </span>
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
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row vertical_border" id="vertical_border_1_1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">
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
                                                                                                                9:30&nbsp;AM
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

                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
    margin-bottom: 12px;
">
                                                                                                                <tbody>
                                                                                                                    <tr>

                                                                                                                        <td align="center">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
    width: 545px;
    padding-right: 15px;
">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-bottom:10px;padding-right:10px;">

                                                                                                                                                            <img data-image="Rate Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="22" border="0" style="width:22px;border:0px;display:inline-block !important;">

                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Rate" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:24px;font-weight:600;letter-spacing:0px;padding:0;padding-bottom:10px;" data-gramm="false">
                                                                                                                                                            4.5
                                                                                                                                                            Superb
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>

                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:22px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;padding-bottom: 10px;" data-gramm="false">
                                                                                                                                            Marina
                                                                                                                                            Beach
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="padding:0;padding-bottom:15px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>
                                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                            9:30
                                                                                                                                                            AM
                                                                                                                                                            -
                                                                                                                                                            10:00
                                                                                                                                                            AM
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width:24px;border:0px;display:inline-block !important;">

                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                            Marina
                                                                                                                                                            Beach
                                                                                                                                                            Road,
                                                                                                                                                            Chennai
                                                                                                                                                            (Madras)
                                                                                                                                                            600005
                                                                                                                                                            India
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                        <td align="center" style="
">
                                                                                                                            <div style="
"><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-32.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 145px;max-width: 145px;height: 145px;max-height: 145px;border-radius: 10px;"></div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <!-- Activity -->
                                                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="row" style="
                                                                                                    border-top: 2px dashed #9f5bd0;
                                                                                                    padding-top: 12px;
                                                                                                ">
                                                                                                                <tbody>
                                                                                                                    <!-- Content on the right side -->
                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="">
                                                                                                                                <tbody>

                                                                                                                                    <tr>
                                                                                                                                        <td data-btn="Button Link" align="center" style="border-bottom: 3px solid #9f5bd0;display:block;">
                                                                                                                                            <span href="http://example.com" style="color: #333;font-size:18px;font-weight:600;letter-spacing:0.5px;line-height:24px;display:block;text-decoration:none;white-space:nowrap;padding-bottom: 0px;">ACTIVITY</span>
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
                                                                                                                                                                            <div style="/* background-color: #9f5bd0; */font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto; /* Change 'black' to any color you want */border-left: 2px dashed #9f5bd0; /* Adjust width and color as needed */ /* Adjust height as needed */">
                                                                                                                                                                            </div>
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td>
                                                                                                                                                                            <div style="font-weight: 600;color: #ffffff;background-color: #9f5bd0;padding: 4px 6px 5px;font-size: 13.5px;white-space: nowrap;border-radius:10px;width: 55px;text-align: center;border: 2px solid #9f5bd0;">
                                                                                                                                                                                10:00&nbsp;AM
                                                                                                                                                                            </div>
                                                                                                                                                                        </td>

                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td>
                                                                                                                                                                            <div style="/* background-color: #9f5bd0; */font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #9f5bd0;">
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
                                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 97%;max-width: auto;background-color: white;padding: 10px 15px 6px;vertical-align: middle;border-radius: 10px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="
    vertical-align: middle;
">
                                                                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

                                                                                                                                            <table width="60" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 70px;max-width: 70px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="top">
                                                                                                                                                            <img data-image="Icon 1" src="https://editor.maool.com/images/travel/package@img-2.png" alt="Icon" width="60" border="0" style="width: 110px;border:0px;display:inline-block !important;height: 110px;border-radius: 10px;" data-lm-image="true">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                            <table width="10" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 10px;max-width: 10px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" height="20">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 520px;/* max-width: 100%; */">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width: 70px; */">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;max-width: 150px;" data-gramm="false">
                                                                                                                                                                            Diving
                                                                                                                                                                            Adventure
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: text-top;" data-gramm="false">
                                                                                                                                                                            ₹
                                                                                                                                                                            1,000

                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:10px;">
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="18" style="width: 22px;margin-top: 1px;">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight: 600;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            4.5
                                                                                                                                                                            Superb
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width: 16px;margin-top: 5px;">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            10:00
                                                                                                                                                                            AM
                                                                                                                                                                            -
                                                                                                                                                                            10:30
                                                                                                                                                                            AM
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr style="
">
                                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width: 20px;border:0px;display:inline-block !important;margin-top: 5px;">

                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            Max
                                                                                                                                                                            2
                                                                                                                                                                            Person
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
                                                                                                                                                            <div style="font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #9f5bd0;">
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="background-color: #9f5bd0;color: #fff;padding: 4px 6px 5px;font-size: 13.5px;white-space: nowrap;border-radius:10px;width: 55px;text-align: center;">
                                                                                                                                                                10:30&nbsp;AM
                                                                                                                                                            </div>
                                                                                                                                                        </td>

                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="font-size:2px;height:auto;width:2px;max-width:2px;height:20px;margin-left: auto;margin-right: auto;border-left: 2px dashed #9f5bd0; /* Adjust color and gap size as needed */">
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>

                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>


                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 97%;max-width: auto;background-color: white;padding: 10px 15px 6px;vertical-align: middle;border-radius: 10px;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="
    vertical-align: middle;
">
                                                                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

                                                                                                                                            <table width="60" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 70px;max-width: 70px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="top">
                                                                                                                                                            <img data-image="Icon 1" src="https://editor.maool.com/images/travel/package@img-17.png" alt="Icon" width="60" border="0" style="width: 110px;border:0px;display:inline-block !important;height: 110px;border-radius: 10px;" data-lm-image="true">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                            <table width="10" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 10px;max-width: 10px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" height="20">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 520px;/* max-width: 100%; */">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 100%;/* max-width: 70px; */">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;max-width: 150px;" data-gramm="false">
                                                                                                                                                                            Skydive in Marina Beach explore</td>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: text-top;" data-gramm="false">
                                                                                                                                                                            ₹
                                                                                                                                                                            1,000

                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-right:10px;">
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="18" style="width: 22px;margin-top: 1px;">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight: 600;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            4.5
                                                                                                                                                                            Superb
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width: 16px;margin-top: 5px;">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">10:30 AM - 11:00 AM</td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr style="
">
                                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width: 20px;border:0px;display:inline-block !important;margin-top: 5px;">

                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            Max
                                                                                                                                                                            2
                                                                                                                                                                            Person
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

                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
">
                                                                                                                <tbody>
                                                                                                                    <tr>

                                                                                                                        <td align="center">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
    width: 545px;
    padding-right: 15px;
">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-bottom:10px;padding-right:10px;">

                                                                                                                                                            <img data-image="Rate Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="22" border="0" style="width:22px;border:0px;display:inline-block !important;">

                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Rate" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:24px;font-weight:600;letter-spacing:0px;padding:0;padding-bottom:10px;" data-gramm="false">
                                                                                                                                                            4.5
                                                                                                                                                            Superb
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>

                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:22px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;padding-bottom: 5px;" data-gramm="false">Zoo</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                <tbody>
                                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">11:00 AM - 12:00 PM</td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width:24px;border:0px;display:inline-block !important;">

                                                                                                                                                        </td>
                                                                                                                                                        <td width="5">
                                                                                                                                                            &nbsp;
                                                                                                                                                        </td>
                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">Chennai - 600005, India</td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Price" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:24px;font-weight:700;letter-spacing:0px;" data-gramm="false">
                                                                                                                                            ₹ 400
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                        <td align="center" style="
">
                                                                                                                            <div style="
"><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-16.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 145px;max-width: 145px;height: 145px;max-height: 145px;border-radius: 10px;"></div>
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

                            <table data-group="Other Modules" data-module="Other Module 16" data-thumbnail="thubnails/othModule-16.png" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:100%;margin-bottom: auto;">
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
                                                                                            <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-38.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <span align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:400;letter-spacing:0px;" data-gramm="false">Return To <span style="font-weight:700;">Garden Hotel</span>
                                                                                            </span>
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                <tbody>
                                                                                                    <!-- Icons and descriptions -->
                                                                                                    <tr>


                                                                                                        <td data-gramm="false" style="color:#595959;font-size: 16px;line-height:28px;font-weight:400;letter-spacing:0px;width: fit-content;" valign="middle" align="left" data-font="Primary" data-text="Title"></td>
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









                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: -webkit-fill-available;margin: 20px 26px;border-top: 3px dotted #333;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="container-padding">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                                                                        <td align="left" class="container-padding">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                <tbody>
                                                                                    <!-- Icons and descriptions -->


                                                                                    <tr>
                                                                                        <td align="center" valign="middle">
                                                                                            <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-46.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <span align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:600;letter-spacing:0px;" data-gramm="false">DAY 2 - Mar 30,2024 (Saturday)
                                                                                            </span><span align="left" valign="middle" style="color:#191919;font-size:18px;line-height:28px;font-weight:600;letter-spacing:0px;" data-gramm="false"> | Rameswaram, Tamil Nadu
                                                                                            </span>
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                <tbody>
                                                                                                    <!-- Icons and descriptions -->
                                                                                                    <tr>


                                                                                                        <td data-gramm="false" style="color:#595959;font-size: 16px;line-height:28px;font-weight:400;letter-spacing:0px;width: fit-content;" valign="middle" align="left" data-font="Primary" data-text="Title"></td>
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

                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
">
                                                                                                <tbody>
                                                                                                    <tr>

                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
    width: 545px;
    padding-right: 15px;
">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-bottom:10px;padding-right:10px;">

                                                                                                                                            <img data-image="Rate Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="22" border="0" style="width:22px;border:0px;display:inline-block !important;">

                                                                                                                                        </td>
                                                                                                                                        <td data-text="Rate" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:24px;font-weight:600;letter-spacing:0px;padding:0;padding-bottom:10px;" data-gramm="false">
                                                                                                                                            4.5
                                                                                                                                            Superb
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                    <tr>
                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:22px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;padding-bottom: 5px;" data-gramm="false">Marina Beach</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">9:30 AM - 10:00 AM</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width:24px;border:0px;display:inline-block !important;">

                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">Marina Beach Road, Chennai (Madras) 600005 India</td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" style="
">
                                                                                                            <div style="
"><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-32.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 145px;max-width: 145px;height: 145px;max-height: 145px;border-radius: 10px;"></div>
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

                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
">
                                                                                                <tbody>
                                                                                                    <tr>

                                                                                                        <td align="center">
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="
    width: 545px;
    padding-right: 15px;
">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding:0;padding-bottom:10px;padding-right:10px;">

                                                                                                                                            <img data-image="Rate Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="22" border="0" style="width:22px;border:0px;display:inline-block !important;">

                                                                                                                                        </td>
                                                                                                                                        <td data-text="Rate" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:24px;font-weight:600;letter-spacing:0px;padding:0;padding-bottom:10px;" data-gramm="false">
                                                                                                                                            4.5
                                                                                                                                            Superb
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                    <tr>
                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="br-mobile-none center-text" style="color:#191919;font-size:22px;line-height:32px;font-weight:600;letter-spacing:0px;padding:0px;padding-bottom: 5px;" data-gramm="false">Zoo</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0;padding-bottom: 5px;">
                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                <tbody>
                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width:18px;">
                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">11:00 AM - 12:00 PM</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                            <img data-image="Icon 5" src="https://editor.maool.com/images/travel/icon@img-33.png" alt="Icon" width="24" border="0" style="width:24px;border:0px;display:inline-block !important;">

                                                                                                                                        </td>
                                                                                                                                        <td width="5">
                                                                                                                                            &nbsp;
                                                                                                                                        </td>
                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">Chennai - 600005, India</td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td data-text="Price" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:24px;font-weight:700;letter-spacing:0px;" data-gramm="false">
                                                                                                                            ₹ 400
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                        <td align="center" style="
">
                                                                                                            <div style="
"><img data-image="Package Image" src="https://editor.maool.com/images/travel/package@img-16.png" alt="Package Image" width="239" border="0" style="display:inline-block !important;border:0;width: 145px;max-width: 145px;height: 145px;max-height: 145px;border-radius: 10px;"></div>
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









                            <!-- Day 2 -->
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
                                                                                            <img data-image="Icon" src="https://editor.maool.com/images/travel/icon@img-38.png" alt="Icon" width="40" border="0" style="width: 45px;border:0px;display:inline-block !important;">
                                                                                        </td>
                                                                                        <td width="5">
                                                                                            &nbsp;
                                                                                        </td>
                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size:16px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                            <span align="left" valign="middle" style="color:#191919;font-size: 18px;line-height:28px;font-weight:400;letter-spacing:0px;" data-gramm="false">Return To <span style="font-weight:700;">Garden Hotel</span>
                                                                                            </span>
                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                <tbody>
                                                                                                    <!-- Icons and descriptions -->
                                                                                                    <tr>


                                                                                                        <td data-gramm="false" style="color:#595959;font-size: 16px;line-height:28px;font-weight:400;letter-spacing:0px;width: fit-content;" valign="middle" align="left" data-font="Primary" data-text="Title"></td>
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

                            <!-- Hotspot Details -->

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
                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:20px;line-height:40px;font-weight:600;letter-spacing:0px;" data-gramm="false" data-lm-text="true">
                                                                                                                            Hotel
                                                                                                                            Details
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


                                                            <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size:17px;line-height:120%;mso-line-height-alt:20.4px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span>No. Of Rooms</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="color:#aea5af;font-size:20px;line-height:120%;mso-line-height-alt:24px; margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span>
                                                                                            <?php
                                                                                            $select_itinerary_traveller_details_query = sqlQUERY_LABEL("SELECT `room_id` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `room_id`") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                                                                            $total_itinerary_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_traveller_details_query);
                                                                                            echo $total_itinerary_traveller_details_count;
                                                                                            ?>
                                                                                        </span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size:17px;line-height:120%;mso-line-height-alt:20.4px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span>Child Beds</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="color:#aea5af;font-size:20px;line-height:120%;mso-line-height-alt:24px; margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $total_child_no_bed; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style="padding:15px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size:17px;line-height:120%;mso-line-height-alt:20.4px">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span>Extra Beds</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="color:#aea5af;font-size:20px;line-height:120%;mso-line-height-alt:24px; margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span><?= $total_extra_bed; ?></span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                        <td class="pad" style="padding: 15px 20px; text-align:center;">
                                                                            <div style="color:#232323;font-size:17px;line-height:120%;mso-line-height-alt:20.4px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <span>Food Preference</span>
                                                                                </p>
                                                                            </div>
                                                                            <div style="color:#aea5af;font-size:20px;line-height:120%;mso-line-height-alt:24px; margin-top:5px;">
                                                                                <p style="margin:0;word-break:break-word">
                                                                                    <strong><span>
                                                                                            <?php
                                                                                            if ($food_type == 'Both') :
                                                                                                echo 'Veg & Non- Veg';
                                                                                            else :
                                                                                                echo $food_type;
                                                                                            endif;
                                                                                            ?>
                                                                                        </span></strong>
                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <table width="600" align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 700px;max-width: 700px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td data-bgcolor="Inner Bgcolor" align="center" bgcolor="#FFFFFF" style="background-color:#FFFFFF;">
                                                                            <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 700px;max-width: 700px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" class="container-padding">
                                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td data-resizable-height="" style="font-size:20px;height:20px;line-height:20px;" class="spacer-first ui-resizable">
                                                                                                            &nbsp;<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center" valign="middle">
                                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->

                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 700px;max-width: 700px;" width="420">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td data-shape-border="Bubble Table" align="center" bgcolor="#FFFFFF" class="container-padding" style="background-color: #FFFFFF;border:1px solid #E5E5E5;border-radius:4px;">
                                                                                                                            <table width="380" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 680px;max-width: 680px;" align="center">
                                                                                                                                <tbody>



                                                                                                                                    <tr>
                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:18px;line-height:28px;font-weight:600;letter-spacing:0px;padding:0px;padding: 12px 0px;" data-gramm="false">DAY 1, Mar 29, 2024 (Friday)</td>


                                                                                                                                    </tr>


                                                                                                                                    <tr>
                                                                                                                                        <td align="left" valign="middle" style="">
                                                                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                                                                                            <table width="30" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:30px;max-width:30px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                            <img data-image="Icon 1" src="https://editor.maool.com/images/travel/icon@img-38.png" alt="Icon" width="28" border="0" style="width: 55px;border:0px;display:inline-block !important;">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                            <table width="10" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:10px;max-width:10px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle" height="10">
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->

                                                                                                                                            <table width="340" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 530px;max-width: 530px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                            Check
                                                                                                                                                            in
                                                                                                                                                            to
                                                                                                                                                            <span style="font-weight:700;">Garden
                                                                                                                                                                Hotel
                                                                                                                                                                ,
                                                                                                                                                                7
                                                                                                                                                                Star</span>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <!-- Icons and descriptions -->
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-37.png" alt="Icon" width="18" style="width: 21px;margin-top: 1px;">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td width="5">
                                                                                                                                                                            &nbsp;
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 15px;line-height:26px;font-weight: 600;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                            4.5
                                                                                                                                                                            Superb
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>

                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <table width="40" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 80px;max-width: 80px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size: 18px;line-height:26px;font-weight:600;letter-spacing:0px;white-space: nowrap;text-align: right;" data-gramm="false">
                                                                                                                                                            ₹
                                                                                                                                                            1,000
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                                                                                                                                        </td>
                                                                                                                                    </tr>



                                                                                                                                    <tr>
                                                                                                                                        <td>
                                                                                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; max-width:100%;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td data-resizable-height="" style="font-size:20px;height:20px;line-height:20px;" class="spacer-first ui-resizable">
                                                                                                                                                            &nbsp;
                                                                                                                                                            <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                            <!--[if (gte mso 9)|(IE)]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                                                                                                                            <table width="210" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:210px;max-width:210px;">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle" class="img-responsive">
                                                                                                                                                                            <img data-image="Blog Image" src="https://editor.maool.com/images/travel/blog@img-17.png" alt="Blog Image" width="250" border="0" style="display:inline-block !important;border:0;width: 200px;max-width: 201px;height: 144px;max-height: 200px;border-radius: 10px;">
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                                            <table width="20" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width:20px;max-width:20px;">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="center" valign="middle" height="20">
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td><td><![endif]-->
                                                                                                                                                            <table width="345" align="left" border="0" cellpadding="0" cellspacing="0" class="row" style="width: 445px;max-width: 445px;">
                                                                                                                                                                <tbody>

                                                                                                                                                                    <tr>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:28px;font-weight:600;letter-spacing:0px;max-width: 260px;" data-gramm="false">
                                                                                                                                                                            Room
                                                                                                                                                                            #1
                                                                                                                                                                            -
                                                                                                                                                                            DELUXE
                                                                                                                                                                        </td>
                                                                                                                                                                        <td data-text="Title" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#191919;font-size:16px;line-height:28px;font-weight:600;letter-spacing:0px;text-align: right;vertical-align: text-top;" data-gramm="false">
                                                                                                                                                                            ₹
                                                                                                                                                                            1,000

                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td>
                                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                                                                                                                                                                                <tbody>
                                                                                                                                                                                    <!-- Icons and descriptions -->

                                                                                                                                                                                    <tr>
                                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                                            <img data-image="Clock Icon" src="https://editor.maool.com/images/travel/icon@img-5.png" alt="Icon" width="18" style="width: 15px;margin-top: 5px;">
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td width="5">
                                                                                                                                                                                            &nbsp;
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 14px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                                            Check
                                                                                                                                                                                            In
                                                                                                                                                                                            &amp;
                                                                                                                                                                                            Out
                                                                                                                                                                                            -
                                                                                                                                                                                            2:00
                                                                                                                                                                                            PM
                                                                                                                                                                                            to
                                                                                                                                                                                            11:00
                                                                                                                                                                                            PM
                                                                                                                                                                                        </td>
                                                                                                                                                                                    </tr>
                                                                                                                                                                                    <tr>
                                                                                                                                                                                        <td align="center" valign="middle">
                                                                                                                                                                                            <img style="width: 18px;margin-top: 5px;" width="18" alt="Icon" src="https://editor.maool.com/images/travel/icon@img-25.png" data-image="Clock Icon">
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td width="5">
                                                                                                                                                                                            &nbsp;
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 14px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                                            Max
                                                                                                                                                                                            3
                                                                                                                                                                                            Adults
                                                                                                                                                                                            |
                                                                                                                                                                                            2
                                                                                                                                                                                            Children
                                                                                                                                                                                        </td>
                                                                                                                                                                                    </tr>
                                                                                                                                                                                    <tr>
                                                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                                                            <img data-image="Icon 2" src="https://editor.maool.com/images/travel/icon@img-14.png" alt="Icon" width="15" border="0" style="width: 17px;border:0px;display:inline-block !important;margin-top: 5px;">


                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td width="5">
                                                                                                                                                                                            &nbsp;
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size: 14px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                                            Breakfast
                                                                                                                                                                                        </td>
                                                                                                                                                                                    </tr>
                                                                                                                                                                                    <tr>
                                                                                                                                                                                        <td style="vertical-align: text-top;">

                                                                                                                                                                                            <img data-image="Icon 2" src="https://editor.maool.com/images/travel/icon@img-10.png" alt="Icon" width="15" border="0" style="width: 17px;border:0px;display:inline-block !important;margin-top: 5px;">

                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td width="5">
                                                                                                                                                                                            &nbsp;
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="" class="center-text" style="color:#595959;font-size: 14px;line-height:26px;font-weight:400;letter-spacing:0px;" data-gramm="false">
                                                                                                                                                                                            Ac
                                                                                                                                                                                            Available
                                                                                                                                                                                        </td>
                                                                                                                                                                                    </tr>
                                                                                                                                                                                </tbody>
                                                                                                                                                                            </table>
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                </tbody>
                                                                                                                                                            </table>
                                                                                                                                                            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
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
                                                                                                            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td data-resizable-height="" style="font-size:20px;height:20px;line-height:20px;" class="ui-resizable">
                                                                                                            &nbsp;<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">
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
                            <!-- Hotel Details -->

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
                                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                                        <td data-text="Section Title" data-font="Primary" align="center" valign="middle" style="color:#191919;font-size:20px;line-height:40px;font-weight:600;letter-spacing:0px;" data-gramm="false" data-lm-text="true">
                                                                                                                            Vehicle
                                                                                                                            Details
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
                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 800px; max-width: 100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" valign="middle" bgcolor="#fff">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 400px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" bgcolor="#FFFFFF">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 400px;padding-left: 15px;padding-right: 15px;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="gmail-container-padding">
                                                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle">
                                                                                                                            <table width="160" align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 100%;max-width: 100%;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding: 0px;width: 90px;">
                                                                                                                                            <img data-surl="cid:ii_lulbd7xy0" src="https://editor.maool.com/images/travel/package@img-2.png" alt="Icon" width="110" height="110" style="
    border-radius: 10px;
">
                                                                                                                                        </td>
                                                                                                                                        <td style="width: 260px;">
                                                                                                                                            <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 100%;/* max-width: 140px; */margin-left: 10px;margin-right: 10px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="left" valign="middle" style="font-family: Poppins, sans-serif;color: rgb(25, 25, 25);font-size: 18px;line-height: 28px;font-weight: 600;letter-spacing: 0px;">
                                                                                                                                                            Sedan
                                                                                                                                                        </td>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="font-size: 16px; line-height: 19.2px; text-align: right;">
                                                                                                                                                                <h3 style="margin: 0px; word-break: break-word; font-weight: 500;">
                                                                                                                                                                    ₹ 1,000</h3>
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="left" valign="" class="gmail-center-text" style="font-family: Poppins, sans-serif; color: rgb(89, 89, 89); font-size: 14px; line-height: 26px; letter-spacing: 0px;">
                                                                                                                                                                            Count
                                                                                                                                                                            -
                                                                                                                                                                            1
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="left" valign="" class="gmail-center-text" style="font-family: Poppins, sans-serif; color: rgb(89, 89, 89); font-size: 14px; line-height: 26px; letter-spacing: 0px;">
                                                                                                                                                                            Fuel
                                                                                                                                                                            Type
                                                                                                                                                                            -
                                                                                                                                                                            Petrol
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
                                                                        </td>
                                                                        <td align="center" bgcolor="#FFFFFF">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 400px;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" bgcolor="#FFFFFF">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 400px;padding-right: 30px;padding-left: 15px;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" class="gmail-container-padding">
                                                                                                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="center" valign="middle">
                                                                                                                            <table width="160" align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 100%;max-width: 100%;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="center" valign="middle" style="padding: 0px;width: 90px;">
                                                                                                                                            <img data-surl="cid:ii_lulbd7xy0" src="https://editor.maool.com/images/travel/package@img-2.png" alt="Icon" width="110" height="110" style="
    border-radius: 10px;
">
                                                                                                                                        </td>
                                                                                                                                        <td style="width: 260px;">
                                                                                                                                            <table width="140" align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-row" style="width: 100%;/* max-width: 140px; */margin-left: 10px;margin-right: 10px;">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="left" valign="middle" style="font-family: Poppins, sans-serif;color: rgb(25, 25, 25);font-size: 18px;line-height: 28px;font-weight: 600;letter-spacing: 0px;">
                                                                                                                                                            Sedan
                                                                                                                                                        </td>
                                                                                                                                                        <td>
                                                                                                                                                            <div style="font-size: 16px; line-height: 19.2px; text-align: right;">
                                                                                                                                                                <h3 style="margin: 0px; word-break: break-word; font-weight: 500;">
                                                                                                                                                                    ₹ 1,000</h3>
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>
                                                                                                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="gmail-">
                                                                                                                                                                <tbody>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="left" valign="" class="gmail-center-text" style="font-family: Poppins, sans-serif; color: rgb(89, 89, 89); font-size: 14px; line-height: 26px; letter-spacing: 0px;">
                                                                                                                                                                            Count
                                                                                                                                                                            -
                                                                                                                                                                            1
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td align="left" valign="" class="gmail-center-text" style="font-family: Poppins, sans-serif; color: rgb(89, 89, 89); font-size: 14px; line-height: 26px; letter-spacing: 0px;">
                                                                                                                                                                            Fuel
                                                                                                                                                                            Type
                                                                                                                                                                            -
                                                                                                                                                                            Petrol
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
            <!-- Summary -->
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
                                                            <div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1661/round_corners_2.png" style="display:block;height:auto;border:0;width:100%;" alt="Alternate text" title="Alternate text">
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
                                                                                                    <span>Total for
                                                                                                        The
                                                                                                        Hotspot</span>
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
                                                                                                    <span>₹ 0</span>
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
                                                                                                    <span>Total for
                                                                                                        The
                                                                                                        Activity</span>
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
                                                                                                    <span>₹
                                                                                                        2,000</span>
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
                                                                                                    <span>Total for
                                                                                                        The
                                                                                                        Hotel</span>
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
                                                                                                    <span>₹
                                                                                                        6,800</span>
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
                                                                                                    <span>Total for
                                                                                                        The
                                                                                                        Vehicle</span>
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
                                                                                                    <span>₹
                                                                                                        1,717</span>
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
                                                                                            <div style="width:100%">
                                                                                                <hr style="color: #dbdade; border-style:dashed;">
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
                                                                                                    <span>Gross
                                                                                                        Total for
                                                                                                        The
                                                                                                        Package</span>
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
                                                                                                    <span>₹
                                                                                                        10,517</span>
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
                                                                                                    <span>GST @ 5 %
                                                                                                        On The total
                                                                                                        Package</span>
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
                                                                                                    <span>₹
                                                                                                        526</span>
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
                                                                    <span><strong>Net Payable To Doview Holidays
                                                                            India Pvt ltd</strong></span>
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
                                                                    <span><strong>₹ 11,043</strong></span>
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
            <!-- Summary -->
            <!-- Footer -->
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

            <!-- Footer -->
        </div>
    </div>
    <div>
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