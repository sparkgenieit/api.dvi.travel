<?php
include_once('jackus.php');

$itinerary_plan_ID = $_GET['itinerary_plan_ID'];
$recommended1 = $_GET['recommended1'];
$recommended2 = $_GET['recommended2'];
$recommended3 = $_GET['recommended3'];
$recommended4 = $_GET['recommended4'];

$total_adult = 0;
$total_children = 0;
$total_infants = 0;

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `hotel_rates_visibility`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
        $trip_start_date_and_time = dateformat_datepicker($fetch_itinerary_plan_data['trip_start_date_and_time']);
        $trip_end_date_and_time = dateformat_datepicker($fetch_itinerary_plan_data['trip_end_date_and_time']);
        // $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($trip_start_date_and_time));
        // $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($trip_end_date_and_time));
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
        $hotel_rates_visibility = $fetch_itinerary_plan_data['hotel_rates_visibility'];
        $meal_plan_breakfast = $fetch_itinerary_plan_data['meal_plan_breakfast'];
        $meal_plan_lunch = $fetch_itinerary_plan_data['meal_plan_lunch'];
        $meal_plan_dinner = $fetch_itinerary_plan_data['meal_plan_dinner'];
        $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
        $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
        $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
        $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
        $vehicle_type = $fetch_itinerary_plan_data['vehicle_type'];
        $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
        $special_instructions = $fetch_itinerary_plan_data['special_instructions'];
        $pick_up_date_and_time = $fetch_itinerary_plan_data['pick_up_date_and_time'];
        $pick_up_date_and_time = date('d-m-Y h:i A', strtotime($pick_up_date_and_time));
        $nationality = getCOUNTRYLIST($fetch_itinerary_plan_data['nationality'], 'country_label');
        $food_type = getFOODTYPE($fetch_itinerary_plan_data['food_type'], 'label');
    endwhile;

    $total_pax_count = $total_adult + $total_children + $total_infants;
endif;
$TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');

$itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
$itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');
$no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Clipboard</title>

</head>

<body style="margin: 0; padding: 0; background-color: #f9f9f9;font-family: Calibri;font-size: 11px;color: #302c6e;">
    <div id="contentToCopy">
        <div style="font-family: Calibri; font-size: 11px !important; color: #302c6e; width: 700px;">
            <table class="row-4" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width: 700px; border-collapse: collapse;">

                <!-- START TOUR ITINERARY PLAN TITLE -->
                <tr>
                    <td>
                        <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                            <tr>
                                <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 22px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;text-align:center;">
                                    Tour Itinerary Plan
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- END TOUR ITINERARY PLAN TITLE -->

                <!-- START SUMMARY DETAILS -->
                <tr>
                    <td>
                        <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse; background:#fff; font-size:11px; line-height:1.2;">
                            <tr>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Start Date & Time</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $trip_start_date_and_time; ?></span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">End Date & Time</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $trip_end_date_and_time; ?></span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Quote Id</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $itinerary_quote_ID; ?></span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Trip Night & Day</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $no_of_nights; ?> Nights, <?= $no_of_days; ?> Days</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Entry Ticket Required</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $entry_ticket_required; ?></span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Nationality</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $nationality; ?></span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Total Pax</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $total_adult; ?> Adult, <?= $total_children; ?> Children, <?= $total_infants; ?> Infant</span>
                                </td>
                                <td width="25%" style="text-align:center; padding:3px; border:1px solid #b1b1b1;">
                                    <span style="color:#afafaf; font-weight:500; display:block;">Room Count</span>
                                    <span style="color:#302c6e; font-weight:700; display:block;"><?= $preferred_room_count; ?></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- END SUMMARY DETAILS -->
                <tr>
                    <td>
                        <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">

                            <!--START ITINERARY PREFERENCE BOTH -->
                            <?php
                            if ($itinerary_preference == 3) :
                                $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                                while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
                                    $group_type = $row_hotel_group['group_type'];
                            ?>
                                    <?php
                                    if ($group_type == $recommended1 || $group_type == $recommended2 ||  $group_type == $recommended3 ||  $group_type == $recommended4) : ?>
                                        <!--START HOTEL DETAILS -->
                                        <tr>
                                            <td>
                                                <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 18px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                                            Recommended Hotel - <?= $group_type ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Day
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Destination
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Hotel Name - Category
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Room Type - Count
                                                        </th>
                                                        <?php if ($hotel_rates_visibility == 1): ?>
                                                            <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Price
                                                            </th>
                                                        <?php endif; ?>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Meal Plan
                                                        </th>
                                                    </tr>
                                                    <?php
                                                    $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT 
                                                ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, 
                                                ROOM_DETAILS.`room_id`, 
                                                ROOM_DETAILS.`room_type_id`, 
                                                ROOM_DETAILS.`gst_type`, 
                                                ROOM_DETAILS.`gst_percentage`, 
                                                ROOM_DETAILS.`breakfast_required`,
                                                ROOM_DETAILS.`lunch_required`,
                                                ROOM_DETAILS.`dinner_required`,
                                                ROOM_DETAILS.`breakfast_cost_per_person`, 
                                                ROOM_DETAILS.`lunch_cost_per_person`,
                                                ROOM_DETAILS.`dinner_cost_per_person`,
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
                                                            $breakfast_required = $fetch_hotel_data['breakfast_required'];
                                                            $lunch_required = $fetch_hotel_data['lunch_required'];
                                                            $dinner_required = $fetch_hotel_data['dinner_required'];
                                                            $breakfast_cost_per_person = $fetch_hotel_data['breakfast_cost_per_person'];
                                                            $lunch_cost_per_person = $fetch_hotel_data['lunch_cost_per_person'];
                                                            $dinner_cost_per_person = $fetch_hotel_data['dinner_cost_per_person'];


                                                            $total_kms = totalkms($itinerary_plan_id, 'label');
                                                            $vehicle_gst_amount = totalkms($itinerary_plan_id, 'gst');

                                                            $hotel_charges = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES');
                                                            $vehicle_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
                                                            $vehicle_gst_amount = $vehicle_gst_amount; // Assuming this is already defined
                                                            $hotspot_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
                                                            $activity_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
                                                            $guide_charges = $TOTAL_ITINEARY_GUIDE_CHARGES; // Assuming this is already defined
                                                            $total_amount_perday = ($total_hotel_cost + $total_hotel_tax_amount);

                                                            // Check if additional margin needs to be applied
                                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                                // Calculate additional margin
                                                                $total_amount_perday_additional_margin = ($itinerary_additional_margin_percentage * $total_amount_perday) / 100;
                                                            } else {
                                                                $total_amount_perday_additional_margin = 0;
                                                            }

                                                            // Calculate the total
                                                            $total_amount = $hotel_charges + $vehicle_amount + $hotspot_amount + $activity_amount + $guide_charges;
                                                            $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
                                                            $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_id, '', 'get_room_gallery_1st_IMG');
                                                            if ($get_room_gallery_1st_IMG) :
                                                                $get_room_gallery_1st_IMG = $get_room_gallery_1st_IMG;
                                                            else :
                                                                $get_room_gallery_1st_IMG = 'no_image_for_room.png';
                                                            endif;

                                                            if ($hotel_required != 1) :
                                                                $hotel_meal_label = 'EP';
                                                            elseif ($breakfast_required && $breakfast_cost_per_person > 0) :
                                                                if ($lunch_required && $lunch_cost_per_person > 0 && $dinner_required && $dinner_cost_per_person > 0) :
                                                                    $hotel_meal_label = 'AP';
                                                                elseif (($lunch_required && $lunch_cost_per_person > 0) || ($dinner_required && $dinner_cost_per_person > 0)) :
                                                                    $hotel_meal_label = 'MAP';
                                                                else :
                                                                    $hotel_meal_label = 'CP';
                                                                endif;
                                                            else :
                                                                $hotel_meal_label = 'EP';
                                                            endif;
                                                    ?>

                                                            <tr>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <div>
                                                                        Day- <?= $hotel_counter; ?> |
                                                                        <?= dateformat_datepicker($itinerary_route_date); ?>
                                                                    </div>
                                                                </td>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <div>
                                                                        <?= $itinerary_route_location; ?>
                                                                    </div>
                                                                </td>

                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <?php if ($hotel_required == 1) : ?>
                                                                        <span><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?></span> - <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>
                                                                    <?php else : ?>
                                                                        <span>--</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <span><?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?> -
                                                                        <?php if ($selected_hotel_id != 0 && $selected_hotel_id != '') : ?>
                                                                            <?= $preferred_room_count; ?>
                                                                        <?php else : ?>
                                                                            <span>-</span>
                                                                        <?php endif; ?>
                                                                        <span>
                                                                </td>
                                                                <?php if ($hotel_rates_visibility == 1): ?>
                                                                    <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                        <p style="margin: 2px;">
                                                                            <b><?= general_currency_symbol . ' ' . number_format(($total_amount_perday + $total_amount_perday_additional_margin), 2); ?></b>
                                                                        </p>
                                                                    </td>
                                                                <?php endif; ?>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <span><?= $hotel_meal_label; ?></span>
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
                                        <!--END HOTEL DETAILS -->

                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                        </tr>

                                        <!--START VEHICLE DETAILS -->
                                        <tr>
                                            <td>
                                                <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 18px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                                            Vehicle Details
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Vehicle Details
                                                        </th>

                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Total Amount
                                                        </th>
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

                                                        <?php
                                                        $select_itinerary_plan_vendor_vehicle_summary_data1 = sqlQUERY_LABEL("SELECT 
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
                                                                                                                                                    d2.itinerary_plan_id,
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
                                                                                                                                                        AND d2.status = '1' GROUP BY d2.vehicle_type_id
                                                                                                                                                    ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                                                        if (sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_summary_data1) > 0) :
                                                            while ($fetch_eligible_vendor_vehicle_data1 = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_summary_data1)) :
                                                                $itinerary_plan_id = $fetch_eligible_vendor_vehicle_data1['itinerary_plan_id'];
                                                                $vehicle_type_id = $fetch_eligible_vendor_vehicle_data1['vehicle_type_id'];
                                                                $total_vehicle_qty = getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_id, $vehicle_type_id, 'get_vehicle_type_count');
                                                                $totalextrakmscharge = totalkms($itinerary_plan_ID, 'extra_kms', $vehicle_type_id);
                                                                $vehicle_type_title = totalkms($vehicle_type_id, 'vehicle_type');
                                                                $totalamount = (getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount', $vehicle_type_id));

                                                                // Check if additional margin needs to be applied
                                                                if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                                    // Calculate additional margin
                                                                    $totalamount_additional_margin = ($itinerary_additional_margin_percentage * $totalamount) / 100;
                                                                } else {
                                                                    $totalamount_additional_margin = 0;
                                                                }
                                                        ?>

                                                                <tr>
                                                                    <td style="border: 1px solid #b1b1b1; padding: 3px; font-size: 13px; width:85%; ">
                                                                        <div style="display:flex;">
                                                                            <!-- <span><img src="<?= BASEPATH; ?>assets/img/vehi.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span> -->
                                                                            <div>
                                                                                <?= $vehicle_type_title; ?> (<?= $total_vehicle_qty ?>) - <span> <?= $arrival_value; ?> ==> <?= $departure_value; ?></span> - <span> <?= $trip_start_date_and_time ?> ==>
                                                                                    <?= $trip_end_date_and_time ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td style="border: 1px solid #b1b1b1; padding: 3px; font-size: 13px; width:15%">
                                                                        <p style="margin: 0px;">
                                                                            <b><?= number_format(($totalamount + $totalamount_additional_margin), 2); ?></b>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                        <?php endwhile;
                                                        endif; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="5" style="border: 1px solid #b1b1b1; padding: 3px; text-align: center;">
                                                                No Vehicle available</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--END VEHICLE DETAILS -->

                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                        </tr>

                                        <!--START OVERALL SUMMARY DETAILS -->
                                        <?php

                                        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');

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
                                        $total_net_charge = (getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES);

                                        $getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
                                        $gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
                                        $getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

                                        $incident_count = $getguide + $gethotspot + $getactivity;

                                        if ($agent_margin_gst_type == 1):

                                            $get_agent_margin = ($total_net_charge * $agent_margin) / 100;

                                            if ($incident_count == 0):
                                                $gst_pecentage =  0;
                                                $total_agent_margin =  0;
                                                $agent_margin_gst_percentage = 0;
                                                $agent_margin_gst_label = '--';
                                            else:
                                                $agent_margin_gst_label = 'Inclusive';
                                                $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
                                                $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
                                            endif;
                                            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                        else:

                                            if ($incident_count == 0):
                                                $total_agent_margin =  0;
                                                $gst_pecentage = 0;
                                                $agent_margin_gst_percentage = 0;
                                                $agent_margin_gst_label = '--';
                                            else:
                                                $agent_margin_gst_label = 'Exclusive';
                                                $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
                                                $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
                                            endif;

                                            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                        endif;

                                        if ($incident_count > 0):
                                            $separate_service_amount = ($total_agent_margin + $gst_pecentage) / $incident_count;
                                        else:
                                            $separate_service_amount = ($total_agent_margin + $gst_pecentage);
                                        endif;

                                        $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$group_type' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                        $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
                                        if ($total_hotel_details_count > 0) :
                                            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
                                                $new_hotel_margin_rate += $fetch_agent_data['hotel_margin_rate'];
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

                                        $total_margin_without_percentage = $total_agent_margin + $new_hotel_margin_rate + $total_vehicle_margin;
                                        $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;

                                        $total_discount_amount = $total_net_amount - $total_margin_discount;

                                        $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                        $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                        $total_infants = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants');
                                        $total_guest_count = $total_adult + $total_children + $total_infants;

                                        $total_components_amount = $TOTAL_ITINEARY_GUIDE_CHARGES + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $total_agent_margin;
                                        ?>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <?php if (in_array($itinerary_preference, array(1, 3))) :
                                                        $total_room_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_ROOM_COST') + getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_MARGIN_RATE') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $group_type, 'total_margingst_cost_hotel') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $group_type, 'total_gst_cost_hotel'));

                                                        $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                                        $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                                        $total_extra_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed');

                                                        $hotel_pax_count = $total_adult  - $total_extra_bed;
                                                        $hotel_overall_meal_cost = getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_FOOD_COST');
                                                        $pax_meal_cost = $hotel_overall_meal_cost / ($total_adult + $total_children);
                                                        $total_room_cost_updated = $total_room_cost + (($total_adult - $total_extra_bed) * $pax_meal_cost);
                                                        $hotel_pax_amount  = $total_room_cost_updated /  $hotel_pax_count;

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $hotel_pax_amount_additional_margin = ($itinerary_additional_margin_percentage * $hotel_pax_amount) / 100;
                                                            $total_room_cost_updated_additional_margin = ($itinerary_additional_margin_percentage * $total_room_cost_updated) / 100;
                                                        } else {
                                                            $hotel_pax_amount_additional_margin = 0;
                                                            $total_room_cost_updated_additional_margin = 0;
                                                        }

                                                    ?>
                                                        <?php if ($total_room_cost > 0 || $hotel_pax_amount > 0) : ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Total Room Cost (<?= $hotel_pax_count ?> * <?= number_format($hotel_pax_amount + $hotel_pax_amount_additional_margin,2); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($total_room_cost_updated + $total_room_cost_updated_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php
                                                        $amenities_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_AMENITIES_COST'));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $amenities_cost_additional_margin = ($itinerary_additional_margin_percentage * $amenities_cost) / 100;
                                                        } else {
                                                            $amenities_cost_additional_margin = 0;
                                                        }

                                                        if ($amenities_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Total Amenities Cost
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($amenities_cost + $amenities_cost_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php
                                                        $extra_bed_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_EXTRABED_COST'));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $extra_bed_cost_additional_margin = ($itinerary_additional_margin_percentage * $extra_bed_cost) / 100;
                                                            $updated_extra_bed_cost_additional_margin = ($itinerary_additional_margin_percentage * $updated_extra_bed_cost) / 100;
                                                        } else {
                                                            $extra_bed_cost_additional_margin = 0;
                                                            $updated_extra_bed_cost_additional_margin = 0;
                                                        }

                                                        if ($extra_bed_cost > 0):
                                                            $updated_extra_bed_cost = $extra_bed_cost + ($pax_meal_cost * $total_extra_bed);
                                                        ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Extra Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($updated_extra_bed_cost + $updated_extra_bed_cost_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php
                                                        $cwb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_CWB_COST'));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $cwb_cost_additional_margin = ($itinerary_additional_margin_percentage * $cwb_cost) / 100;
                                                            $cwb_cost_with_meal_additional_margin = ($itinerary_additional_margin_percentage * ($cwb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed')))) / 100;
                                                        } else {
                                                            $cwb_cost_additional_margin = 0;
                                                            $cwb_cost_with_meal_additional_margin = 0;
                                                        }

                                                        if ($cwb_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Child With Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($cwb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed'))), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>

                                                        <?php
                                                        $cnb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_CNB_COST'));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $cnb_cost_additional_margin = ($itinerary_additional_margin_percentage * $cnb_cost) / 100;
                                                            $cnb_cost_with_meal_additional_margin = ($itinerary_additional_margin_percentage * ($cnb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')))) / 100;
                                                        } else {
                                                            $cnb_cost_additional_margin = 0;
                                                            $cnb_cost_with_meal_additional_margin = 0;
                                                        }
                                                        if ($cnb_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Child Without Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($cnb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')) + $cnb_cost_with_meal_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                                        <?php

                                                        $vehicle_cost = (getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_confirmed_vendor_vehicle_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_margin_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_tax_amount'));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $vehicle_cost_additional_margin = ($itinerary_additional_margin_percentage * $vehicle_cost) / 100;
                                                        } else {
                                                            $vehicle_cost_additional_margin = 0;
                                                        }

                                                        if ($vehicle_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Total Vehicle Cost (<?= getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_qty', ''); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($vehicle_cost + $vehicle_cost_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php

                                                    $guide_cost = $TOTAL_ITINEARY_GUIDE_CHARGES + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $guide_cost_additional_margin = ($itinerary_additional_margin_percentage * $guide_cost) / 100;
                                                    } else {
                                                        $guide_cost_additional_margin = 0;
                                                    }

                                                    if ($guide_cost > 0 && $getguide == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Guide Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= general_currency_symbol . ' ' . number_format(($guide_cost + $guide_cost_additional_margin), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php
                                                    $hotspot_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $hotspot_cost_additional_margin = ($itinerary_additional_margin_percentage * $hotspot_cost) / 100;
                                                    } else {
                                                        $hotspot_cost_additional_margin = 0;
                                                    }

                                                    if ($hotspot_cost > 0 && $gethotspot == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Hotspot Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= general_currency_symbol . ' ' . number_format(($hotspot_cost + $hotspot_cost_additional_margin), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php
                                                    $activity_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $activity_cost_additional_margin = ($itinerary_additional_margin_percentage * $activity_cost) / 100;
                                                    } else {
                                                        $activity_cost_additional_margin = 0;
                                                    }

                                                    if ($activity_cost > 0 && $getactivity == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Activity Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <strong><?= general_currency_symbol . ' ' . number_format(($activity_cost + $activity_cost_additional_margin), 2); ?></strong>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <?php
                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $total_net_amount_additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
                                                    } else {
                                                        $total_net_amount_additional_margin = 0;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Total Amount
                                                        </th>
                                                        <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin), 2); ?></strong>
                                                        </td>
                                                    </tr>

                                                    <?php if ($total_margin_discount > 0) : ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Coupon Discount
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                               - <?= general_currency_symbol . ' ' . number_format(($total_margin_discount), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php
                                                    $value = $total_net_amount + $total_net_amount_additional_margin - $total_margin_discount;

                                                    $roundoff = $value - round($value);

                                                    if ($roundoff != 0) :
                                                        if ($roundoff >= 0.5) {
                                                            $roundoff_value = 1 - $roundoff;
                                                        } else {
                                                            $roundoff_value = -$roundoff;
                                                        }
                                                    else :
                                                        $roundoff_value = 0;
                                                    endif;

                                                    // Prepare roundoff symbol
                                                    $roundoff_symbol = '';
                                                    if ($roundoff_value > 0) {
                                                        $roundoff_symbol = '+';
                                                    } elseif ($roundoff_value < 0) {
                                                        $roundoff_symbol = '-';
                                                    }

                                                    if ($roundoff_value > 0 || $roundoff_value < 0): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Round Off
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= $roundoff_symbol . ' ' . general_currency_symbol . ' ' . number_format(($roundoff_value), 2) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <?php if ($total_net_amount > 0) : ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Net Payable To Doview Holidays India Pvt ltd
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin - $total_margin_discount + $roundoff_value), 2) ?></strong>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--START OVERALL SUMMARY DETAILS -->

                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <!--END ITINERARY PREFERENCE BOTH -->


                            <!--START ITINERARY PREFERENCE HOTEL -->
                            <?php
                            if ($itinerary_preference == 1) :
                                $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                                while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
                                    $group_type = $row_hotel_group['group_type'];
                            ?>
                                    <?php
                                    if ($group_type == $recommended1 || $group_type == $recommended2 ||  $group_type == $recommended3 ||  $group_type == $recommended4) : ?>
                                        <!--START HOTEL DETAILS -->
                                        <tr>
                                            <td>
                                                <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 18px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                                            Recommended Hotel - <?= $group_type ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <tr>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Day
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Destination
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Hotel Name - Category
                                                        </th>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Room Type - Count
                                                        </th>
                                                        <?php if ($hotel_rates_visibility == 1): ?>
                                                            <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Price
                                                            </th>
                                                        <?php endif; ?>
                                                        <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Meal Plan
                                                        </th>
                                                    </tr>
                                                    <?php
                                                    $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT 
                                                    ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, 
                                                    ROOM_DETAILS.`room_id`, 
                                                    ROOM_DETAILS.`room_type_id`, 
                                                    ROOM_DETAILS.`gst_type`, 
                                                    ROOM_DETAILS.`gst_percentage`, 
                                                    ROOM_DETAILS.`breakfast_required`,
                                                    ROOM_DETAILS.`lunch_required`,
                                                    ROOM_DETAILS.`dinner_required`,
                                                    ROOM_DETAILS.`breakfast_cost_per_person`, 
                                                    ROOM_DETAILS.`lunch_cost_per_person`,
                                                    ROOM_DETAILS.`dinner_cost_per_person`,
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
                                                            $breakfast_required = $fetch_hotel_data['breakfast_required'];
                                                            $lunch_required = $fetch_hotel_data['lunch_required'];
                                                            $dinner_required = $fetch_hotel_data['dinner_required'];
                                                            $breakfast_cost_per_person = $fetch_hotel_data['breakfast_cost_per_person'];
                                                            $lunch_cost_per_person = $fetch_hotel_data['lunch_cost_per_person'];
                                                            $dinner_cost_per_person = $fetch_hotel_data['dinner_cost_per_person'];
                                                            $total_amount_perday = ($total_hotel_cost + $total_hotel_tax_amount);

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
                                                            endif;

                                                            if ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $lunch_required == 1 && $lunch_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0):
                                                                $hotel_meal_label = 'AP';
                                                            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $dinner_required == 1 && $dinner_cost_per_person > 0) :
                                                                $hotel_meal_label = 'MAP';
                                                            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $lunch_required == 1 && $lunch_cost_per_person > 0):
                                                                $hotel_meal_label = 'MAP';
                                                            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1):
                                                                $hotel_meal_label = 'CP';
                                                            else:
                                                                $hotel_meal_label = 'EP';
                                                            endif;

                                                            // Check if additional margin needs to be applied
                                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                                // Calculate additional margin
                                                                $total_amount_perday_additional_margin = ($itinerary_additional_margin_percentage * $total_amount_perday) / 100;
                                                            } else {
                                                                $total_amount_perday_additional_margin = 0;
                                                            }
                                                    ?>
                                                            <tr>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <div>
                                                                        Day- <?= $hotel_counter; ?> |
                                                                        <?= dateformat_datepicker($itinerary_route_date); ?>
                                                                    </div>
                                                                </td>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <div>
                                                                        <?= $itinerary_route_location; ?>
                                                                    </div>
                                                                </td>

                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <?php if ($hotel_required == 1) : ?>
                                                                        <span><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?></span> - <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>
                                                                    <?php else : ?>
                                                                        <span>--</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <span><?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?> -
                                                                        <?php if ($selected_hotel_id != 0 && $selected_hotel_id != '') : ?>
                                                                            <?= $preferred_room_count; ?>
                                                                        <?php else : ?>
                                                                            <span>-</span>
                                                                        <?php endif; ?>
                                                                        <span>
                                                                </td>
                                                                <?php if ($hotel_rates_visibility == 1): ?>
                                                                    <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                        <p style="margin: 2px;">
                                                                            <b><?= general_currency_symbol . ' ' . number_format($total_amount_perday + $total_amount_perday_additional_margin, 2); ?></b>
                                                                        </p>
                                                                    </td>
                                                                <?php endif; ?>
                                                                <td style="text-align: left; width:15%; border: 1px solid #b1b1b1; padding:3px;">
                                                                    <span><?= $hotel_meal_label; ?></span>
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
                                        <!--END HOTEL DETAILS -->

                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                        </tr>

                                        <!--START OVERALL SUMMARY DETAILS -->
                                        <?php

                                        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');

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
                                        $total_net_charge = (getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES);

                                        $getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
                                        $gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
                                        $getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

                                        $incident_count = $getguide + $gethotspot + $getactivity;

                                        if ($agent_margin_gst_type == 1):

                                            $get_agent_margin = ($total_net_charge * $agent_margin) / 100;

                                            if ($incident_count == 0):
                                                $gst_pecentage =  0;
                                                $total_agent_margin =  0;
                                                $agent_margin_gst_percentage = 0;
                                                $agent_margin_gst_label = '--';
                                            else:
                                                $agent_margin_gst_label = 'Inclusive';
                                                $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
                                                $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
                                            endif;
                                            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                        else:

                                            if ($incident_count == 0):
                                                $total_agent_margin =  0;
                                                $gst_pecentage = 0;
                                                $agent_margin_gst_percentage = 0;
                                                $agent_margin_gst_label = '--';
                                            else:
                                                $agent_margin_gst_label = 'Exclusive';
                                                $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
                                                $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
                                            endif;

                                            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                        endif;

                                        if ($incident_count > 0):
                                            $separate_service_amount = ($total_agent_margin + $gst_pecentage) / $incident_count;
                                        else:
                                            $separate_service_amount = ($total_agent_margin + $gst_pecentage);
                                        endif;

                                        $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$group_type' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                        $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
                                        if ($total_hotel_details_count > 0) :
                                            $total_hotel_margin = 0;
                                            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
                                                $hotel_margin_rate = $fetch_agent_data['hotel_margin_rate'];
                                                $total_hotel_margin += $hotel_margin_rate;
                                            endwhile;
                                        endif;

                                        $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                        $total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
                                        if ($total_vehicle_details_count > 0) :
                                            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
                                                $vendor_margin_amount += ($fetch_agent_data['vendor_margin_amount']);
                                            endwhile;
                                        endif;

                                        $total_margin_without_percentage = $total_agent_margin + $total_hotel_margin + $vendor_margin_amount;
                                        $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;

                                        $total_discount_amount = $total_net_amount - $total_margin_discount;

                                        $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                        $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                        $total_infants = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants');
                                        $total_guest_count = $total_adult + $total_children + $total_infants;

                                        $total_components_amount = $TOTAL_ITINEARY_GUIDE_CHARGES + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $total_agent_margin;
                                        ?>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                                    <?php if (in_array($itinerary_preference, array(1, 3))) :
                                                        $total_room_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_ROOM_COST') + getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_MARGIN_RATE') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $group_type, 'total_margingst_cost_hotel') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $group_type, 'total_gst_cost_hotel'));

                                                        $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                                        $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                                        $total_extra_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed');

                                                        $hotel_pax_count = $total_adult  - $total_extra_bed;
                                                        $hotel_overall_meal_cost = getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_FOOD_COST');
                                                        $pax_meal_cost = $hotel_overall_meal_cost / ($total_adult + $total_children);
                                                        $total_room_cost_updated = $total_room_cost + (($total_adult - $total_extra_bed) * $pax_meal_cost);
                                                        $hotel_pax_amount  = $total_room_cost_updated /  $hotel_pax_count;

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $hotel_pax_amount_additional_margin = ($itinerary_additional_margin_percentage * $hotel_pax_amount) / 100;
                                                            $total_room_cost_updated_additional_margin = ($itinerary_additional_margin_percentage * $total_room_cost_updated) / 100;
                                                        } else {
                                                            $hotel_pax_amount_additional_margin = 0;
                                                            $total_room_cost_updated_additional_margin = 0;
                                                        }

                                                    ?>
                                                        <?php if ($total_room_cost > 0 || $hotel_pax_amount > 0) : ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Total Room Cost (<?= $hotel_pax_count ?> * <?= number_format($hotel_pax_amount + $hotel_pax_amount_additional_margin,2); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($total_room_cost_updated + $total_room_cost_updated_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php $amenities_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_HOTEL_AMENITIES_COST'));
                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $amenities_cost_additional_margin = ($itinerary_additional_margin_percentage * $amenities_cost) / 100;
                                                        } else {
                                                            $amenities_cost_additional_margin = 0;
                                                        }
                                                        if ($amenities_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Total Amenities Cost
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($amenities_cost + $amenities_cost_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php $extra_bed_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_EXTRABED_COST'));
                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $extra_bed_cost_additional_margin = ($itinerary_additional_margin_percentage * $extra_bed_cost) / 100;
                                                            $updated_extra_bed_cost_additional_margin = ($itinerary_additional_margin_percentage * $updated_extra_bed_cost) / 100;
                                                        } else {
                                                            $extra_bed_cost_additional_margin = 0;
                                                            $updated_extra_bed_cost_additional_margin = 0;
                                                        }

                                                        if ($extra_bed_cost > 0):
                                                            $updated_extra_bed_cost = $extra_bed_cost + ($pax_meal_cost * $total_extra_bed);
                                                        ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Extra Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($updated_extra_bed_cost + $updated_extra_bed_cost_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php
                                                        $cwb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_CWB_COST'));
                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $cwb_cost_additional_margin = ($itinerary_additional_margin_percentage * $cwb_cost) / 100;
                                                            $cwb_cost_with_meal_additional_margin = ($itinerary_additional_margin_percentage * ($cwb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed')))) / 100;
                                                        } else {
                                                            $cwb_cost_additional_margin = 0;
                                                            $cwb_cost_with_meal_additional_margin = 0;
                                                        }
                                                        if ($cwb_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Child With Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($cwb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed')) + $cwb_cost_with_meal_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php
                                                        $cnb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'TOTAL_CNB_COST'));
                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $cnb_cost_additional_margin = ($itinerary_additional_margin_percentage * $cnb_cost) / 100;
                                                            $cnb_cost_with_meal_additional_margin = ($itinerary_additional_margin_percentage * ($cnb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')))) / 100;
                                                        } else {
                                                            $cnb_cost_additional_margin = 0;
                                                            $cnb_cost_with_meal_additional_margin = 0;
                                                        }
                                                        if ($cnb_cost > 0): ?>
                                                            <tr>
                                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    Child Without Bed Cost (<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed'); ?>)
                                                                </th>
                                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                    <?= general_currency_symbol . ' ' . number_format(($cnb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')) + $cnb_cost_with_meal_additional_margin), 2); ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php $guide_cost = $TOTAL_ITINEARY_GUIDE_CHARGES + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $guide_cost_additional_margin = ($itinerary_additional_margin_percentage * $guide_cost) / 100;
                                                    } else {
                                                        $guide_cost_additional_margin = 0;
                                                    }

                                                    if ($guide_cost > 0 && $getguide == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Guide Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= general_currency_symbol . ' ' . number_format(($guide_cost + $guide_cost_additional_margin), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php $hotspot_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $hotspot_cost_additional_margin = ($itinerary_additional_margin_percentage * $hotspot_cost) / 100;
                                                    } else {
                                                        $hotspot_cost_additional_margin = 0;
                                                    }

                                                    if ($hotspot_cost > 0 && $gethotspot == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Hotspot Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= general_currency_symbol . ' ' . number_format(($hotspot_cost + $hotspot_cost_additional_margin), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <?php
                                                    $activity_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $separate_service_amount;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $activity_cost_additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
                                                    } else {
                                                        $activity_cost_additional_margin = 0;
                                                    }

                                                    if ($activity_cost > 0 && $getactivity == 1): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Activity Cost
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <strong><?= general_currency_symbol . ' ' . number_format(($activity_cost + $activity_cost_additional_margin), 2); ?></strong>
                                                            </td>
                                                        </tr>
                                                    <?php endif;

                                                    // Check if additional margin needs to be applied
                                                    if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                        // Calculate additional margin
                                                        $total_net_amount_additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
                                                    } else {
                                                        $total_net_amount_additional_margin = 0;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Total Amount
                                                        </th>
                                                        <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin), 2); ?></strong>
                                                        </td>
                                                    </tr>

                                                    <?php if ($total_margin_discount > 0) : ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Coupon Discount
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                               - <?= general_currency_symbol . ' ' . number_format(($total_margin_discount), 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php
                                                    $value = $total_net_amount + $total_net_amount_additional_margin - $total_margin_discount;

                                                    $roundoff = $value - round($value);

                                                    if ($roundoff != 0) :
                                                        if ($roundoff >= 0.5) {
                                                            $roundoff_value = 1 - $roundoff;
                                                        } else {
                                                            $roundoff_value = -$roundoff;
                                                        }
                                                    else :
                                                        $roundoff_value = 0;
                                                    endif;

                                                    // Prepare roundoff symbol
                                                    $roundoff_symbol = '';
                                                    if ($roundoff_value > 0) {
                                                        $roundoff_symbol = '+';
                                                    } elseif ($roundoff_value < 0) {
                                                        $roundoff_symbol = '-';
                                                    }

                                                    if ($roundoff_value > 0 || $roundoff_value < 0): ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Total Round Off
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <?= $roundoff_symbol . ' ' . general_currency_symbol . ' ' . number_format(($roundoff_value), 2) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php if ($total_net_amount > 0) : ?>
                                                        <tr>
                                                            <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                Net Payable To Doview Holidays India Pvt ltd
                                                            </th>
                                                            <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                                <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin - $total_margin_discount + $roundoff_value), 2) ?></strong>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--START OVERALL SUMMARY DETAILS -->
                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <!--END ITINERARY PREFERENCE HOTEL -->


                            <!--START ITINERARY PREFERENCE VEHICLE -->
                            <?php
                            if ($itinerary_preference == 2) :
                                $vehicle_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
                                $vehicle_gst_amount = $vehicle_gst_amount; // Assuming this is already defined
                                $hotspot_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
                                $activity_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
                                $guide_charges = $TOTAL_ITINEARY_GUIDE_CHARGES; // Assuming this is already defined

                                // Calculate the total
                                $total_amount = $hotel_charges + $vehicle_amount + $hotspot_amount + $activity_amount + $guide_charges;
                            ?>
                                <!--START VEHICLE DETAILS -->
                                <tr>
                                    <td>
                                        <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                            <tr>
                                                <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 18px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                                    Vehicle Details
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                            <tr>
                                                <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                    Vehicle Details
                                                </th>

                                                <th style="background-color: #f2f2f2; text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                    Total Amount
                                                </th>
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

                                                <?php
                                                $select_itinerary_plan_vendor_vehicle_summary_data1 = sqlQUERY_LABEL("SELECT 
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
                                                                                                                                                    d2.itinerary_plan_id,
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
                                                                                                                                                        AND d2.status = '1' GROUP BY d2.vehicle_type_id
                                                                                                                                                    ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                                                if (sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_summary_data1) > 0) :
                                                    while ($fetch_eligible_vendor_vehicle_data1 = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_summary_data1)) :
                                                        $itinerary_plan_id = $fetch_eligible_vendor_vehicle_data1['itinerary_plan_id'];
                                                        $vehicle_type_id = $fetch_eligible_vendor_vehicle_data1['vehicle_type_id'];
                                                        $total_vehicle_qty = getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_id, $vehicle_type_id, 'get_vehicle_type_count');
                                                        $totalextrakmscharge = totalkms($itinerary_plan_ID, 'extra_kms', $vehicle_type_id);
                                                        $vehicle_type_title = totalkms($vehicle_type_id, 'vehicle_type');
                                                        $totalamount = (getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount', $vehicle_type_id));

                                                        // Check if additional margin needs to be applied
                                                        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                            // Calculate additional margin
                                                            $totalamount_additional_margin = ($itinerary_additional_margin_percentage * $totalamount) / 100;
                                                        } else {
                                                            $totalamount_additional_margin = 0;
                                                        }
                                                ?>

                                                        <tr>
                                                            <td style="border: 1px solid #b1b1b1; padding: 3px; font-size: 13px; width:85%; ">
                                                                <div style="display:flex;">
                                                                    <!-- <span><img src="<?= BASEPATH; ?>assets/img/vehi.jpg" width="50px" height="50px" style="border-radius:5px;margin-right:5px;" /></span> -->
                                                                    <div>
                                                                        <?= $vehicle_type_title; ?> (<?= $total_vehicle_qty; ?>) - <span> <?= $arrival_value; ?> ==> <?= $departure_value; ?></span> - <span> <?= $trip_start_date_and_time ?> ==>
                                                                            <?= $trip_end_date_and_time ?></span>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td style="border: 1px solid #b1b1b1; padding: 3px; font-size: 13px; width:15%">
                                                                <p style="margin: 0px;">
                                                                    <b><?= number_format($totalamount + $totalamount_additional_margin, 2); ?></b>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                <?php endwhile;
                                                endif; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="5" style="border: 1px solid #b1b1b1; padding: 3px; text-align: center;">
                                                        No Vehicle available</td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                </tr>
                                <!--END VEHICLE DETAILS -->

                                <tr>
                                    <td><span>&nbsp;</span></td>
                                </tr>

                                <!--START OVERALL SUMMARY DETAILS -->
                                <?php

                                $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');

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
                                $total_net_charge = (getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES);

                                $getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
                                $gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
                                $getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

                                $incident_count = $getguide + $gethotspot + $getactivity;

                                if ($agent_margin_gst_type == 1):

                                    $get_agent_margin = ($total_net_charge * $agent_margin) / 100;

                                    if ($incident_count == 0):
                                        $gst_pecentage =  0;
                                        $total_agent_margin =  0;
                                        $agent_margin_gst_percentage = 0;
                                        $agent_margin_gst_label = '--';
                                    else:
                                        $agent_margin_gst_label = 'Inclusive';
                                        $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
                                        $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
                                    endif;
                                    $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                else:

                                    if ($incident_count == 0):
                                        $total_agent_margin =  0;
                                        $gst_pecentage = 0;
                                        $agent_margin_gst_percentage = 0;
                                        $agent_margin_gst_label = '--';
                                    else:
                                        $agent_margin_gst_label = 'Exclusive';
                                        $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
                                        $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
                                    endif;

                                    $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
                                endif;

                                if ($incident_count > 0):
                                    $separate_service_amount = ($total_agent_margin + $gst_pecentage) / $incident_count;
                                else:
                                    $separate_service_amount = ($total_agent_margin + $gst_pecentage);
                                endif;

                                $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$group_type' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
                                if ($total_hotel_details_count > 0) :
                                    $total_hotel_margin = 0;
                                    while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
                                        $hotel_margin_rate = $fetch_agent_data['hotel_margin_rate'];
                                        $total_hotel_margin += $hotel_margin_rate;
                                    endwhile;
                                endif;

                                $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                $total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
                                if ($total_vehicle_details_count > 0) :
                                    while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
                                        $vendor_margin_amount += ($fetch_agent_data['vendor_margin_amount']);
                                    endwhile;
                                endif;

                                $total_margin_without_percentage = $total_agent_margin + $total_hotel_margin + $vendor_margin_amount;
                                $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;
                                $total_discount_amount = $total_net_amount - $total_margin_discount;
                                $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                $total_infants = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants');
                                $total_guest_count = $total_adult + $total_children + $total_infants;
                                $total_components_amount = $TOTAL_ITINEARY_GUIDE_CHARGES + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $total_agent_margin;
                                ?>
                                <tr>
                                    <td>
                                        <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                                <?php $vehicle_cost = (getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_confirmed_vendor_vehicle_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_margin_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_tax_amount'));

                                                // Check if additional margin needs to be applied
                                                if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                    // Calculate additional margin
                                                    $vehicle_cost_additional_margin = ($itinerary_additional_margin_percentage * $vehicle_cost) / 100;
                                                } else {
                                                    $vehicle_cost_additional_margin = 0;
                                                }

                                                if ($vehicle_cost > 0): ?>
                                                    <tr>
                                                        <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            Total Vehicle Amount Total Vehicle Cost (<?= getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_qty', ''); ?>)
                                                        </th>
                                                        <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                            <?= general_currency_symbol . ' ' . number_format(($vehicle_cost + $vehicle_cost_additional_margin), 2); ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php
                                            $guide_cost = $TOTAL_ITINEARY_GUIDE_CHARGES + $separate_service_amount;

                                            // Check if additional margin needs to be applied
                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                // Calculate additional margin
                                                $guide_cost_additional_margin = ($itinerary_additional_margin_percentage * $guide_cost) / 100;
                                            } else {
                                                $guide_cost_additional_margin = 0;
                                            }
                                            if ($guide_cost > 0 && $getguide == 1): ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Total Guide Cost
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        <?= general_currency_symbol . ' ' . number_format(($guide_cost + $guide_cost_additional_margin), 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php
                                            $hotspot_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + $separate_service_amount;

                                            // Check if additional margin needs to be applied
                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                // Calculate additional margin
                                                $hotspot_cost_additional_margin = ($itinerary_additional_margin_percentage * $hotspot_cost) / 100;
                                            } else {
                                                $hotspot_cost_additional_margin = 0;
                                            }

                                            if ($hotspot_cost > 0 && $gethotspot == 1): ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Total Hotspot Cost
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        <?= general_currency_symbol . ' ' . number_format(($hotspot_cost + $hotspot_cost_additional_margin), 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php
                                            $activity_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $separate_service_amount;

                                            // Check if additional margin needs to be applied
                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                // Calculate additional margin
                                                $activity_cost_additional_margin = ($itinerary_additional_margin_percentage * $activity_cost) / 100;
                                            } else {
                                                $activity_cost_additional_margin = 0;
                                            }
                                            if ($activity_cost > 0 && $getactivity == 1): ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Total Activity Cost
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        <strong><?= general_currency_symbol . ' ' . number_format(($activity_cost + $activity_cost_additional_margin), 2); ?></strong>
                                                    </td>
                                                </tr>
                                            <?php endif;

                                            // Check if additional margin needs to be applied
                                            if ($no_of_days <= $itinerary_additional_margin_day_limit) {
                                                // Calculate additional margin
                                                $total_net_amount_additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
                                                $total_discount_amount_additional_margin = ($itinerary_additional_margin_percentage * $total_discount_amount) / 100;
                                            } else {
                                                $total_net_amount_additional_margin = 0;
                                                $total_discount_amount_additional_margin = 0;
                                            }

                                            ?>
                                            <tr>
                                                <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                    Total Amount
                                                </th>
                                                <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                    <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin), 2); ?></strong>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($total_margin_discount > 0) : ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Coupon Discount
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                       - <?= general_currency_symbol . ' ' . number_format(($total_margin_discount), 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>

                                            <?php
                                            $value = $total_net_amount + $total_net_amount_additional_margin - $total_margin_discount;

                                            $roundoff = $value - round($value);

                                            if ($roundoff != 0) :
                                                if ($roundoff >= 0.5) {
                                                    $roundoff_value = 1 - $roundoff;
                                                } else {
                                                    $roundoff_value = -$roundoff;
                                                }
                                            else :
                                                $roundoff_value = 0;
                                            endif;

                                            // Prepare roundoff symbol
                                            $roundoff_symbol = '';
                                            if ($roundoff_value > 0) {
                                                $roundoff_symbol = '+';
                                            } elseif ($roundoff_value < 0) {
                                                $roundoff_symbol = '-';
                                            }

                                            if ($roundoff_value > 0 || $roundoff_value < 0): ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Total Round Off
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        <?= $roundoff_symbol . ' ' . general_currency_symbol . ' ' . number_format(($roundoff_value), 2) ?>
                                                    </td>
                                                </tr>
                                            <?php endif;

                                            ?>
                                            <?php if ($total_net_amount > 0) : ?>
                                                <tr>
                                                    <th style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        Net Payable To Doview Holidays India Pvt ltd
                                                    </th>
                                                    <td style="text-align: left; padding: 3px; border: 1px solid #b1b1b1;">
                                                        <strong><?= general_currency_symbol . ' ' . number_format(($total_net_amount + $total_net_amount_additional_margin - $total_margin_discount + $roundoff_value), 2) ?></strong>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                </tr>
                                <!--START OVERALL SUMMARY DETAILS -->

                                <tr>
                                    <td><span>&nbsp;</span></td>
                                </tr>
                            <?php endif; ?>
                            <!--END ITINERARY PREFERENCE VEHICLE -->
                            <?php
                            if ($itinerary_preference != 1) : ?>

                                <!-- START HOTDPOT DETAILS -->
                                <tr>
                                    <td>
                                        <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                            <tr>
                                                <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 18px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                                    Hotspot Details
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 22px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;">
                                        <?php
                                        $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                        $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                        if ($total_itinerary_plan_route_details_count > 0) :
                                            $last_day_ending_location = NULL;
                                            while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                $itineary_route_count++;
                                                $item_type_check = 0;
                                                $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                $location_name = $fetch_itinerary_plan_route_data['location_name'];
                                                $location_id = $fetch_itinerary_plan_route_data['location_id'];
                                                $location_parts = explode(",", $location_name);
                                                $location_value = trim($location_parts[0]);
                                                $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                                                $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                                                $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                                                $next_visiting_parts = explode(",", $next_visiting_location);
                                                $next_visiting_value = trim($next_visiting_parts[0]);
                                                $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
                                                // $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];
                                                $route_end_time = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_end_time_as_hotel_end_time');
                                                $source_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');
                                                $destination_city = getSTOREDLOCATIONDETAILS($location_id, 'DESTINATION_CITY');
                                                $location_description = getSTOREDLOCATIONDETAILS($location_id, 'LOCATION_DESCRIPTION');

                                                // Scenario Logic
                                                // if ($itineary_route_count == 1) {
                                                //     $show_day_trip_available = false;
                                                // } elseif ($last_destination_city === $source_city && $source_city === $destination_city) {
                                                //     $show_day_trip_available = true;
                                                // } else {
                                                //     $show_day_trip_available = false;
                                                // }

                                                // Update last day's destination for the next iteration
                                                $last_destination_city = $destination_city;
                                                $get_via_route_details_with_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_with_format');
                                                $get_via_route_details_without_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_details_clipboard_format');


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

                                                if ($get_via_route_details_without_format != 'No Via Route Added') :
                                                    $via_route_details = '- ' . $get_via_route_details_without_format . ' -';
                                                else:
                                                    $via_route_details = 'to';
                                                endif;
                                        ?>
                                <tr>
                                    <td colspan="4" style="border: 1px solid #b1b1b1; padding: 4px; font-size: 11px; background-color: #f2f2f2;">
                                        <div style="display: flex; align-items: center;">
                                            <span style="margin-right: 4px; font-weight: 400;color: #302c6e;">
                                                <strong style="color: #302c6e;">Day <?= $itineary_route_count; ?></b> - <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?> <?php /* (<?= date('h:i A', strtotime($route_start_time)); ?> - <?= date('h:i A', strtotime($route_end_time)); ?>) */ ?> - <span style="color: #302c6e;"><?= $location_value; ?> <?= $via_route_details; ?> <?= $next_visiting_value; ?></strong>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                                                $select_itinerary_plan_route_hotspot_availability_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `status` = '1' AND `item_type` IN ('6','7') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                $total_itinerary_plan_route_hotspot_availability_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                $fetch_hotspot_availability = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_availability_query);
                                                $get_route_last_hotspot_ID = $fetch_hotspot_availability['route_hotspot_ID'];

                                                $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time`, ROUTE_HOTSPOT.`allow_break_hours`, ROUTE_HOTSPOT.`allow_via_route`, ROUTE_HOTSPOT.`via_location_name` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
                                                $itineary_route_hotspot_count = 0;

                                                if ($direct_to_next_visiting_place == 1):
                                                    $previous_hotspot_name = $location_name;
                                                else:
                                                    $previous_hotspot_name = $location_name;
                                                endif;
                                                if ($total_itinerary_plan_route_hotspot_details_count > 0) :
                                ?>
                                    <tr>
                                        <td colspan="4" style="border: 1px solid #b1b1b1; padding: 4px; font-size: 11px;">
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
                                                        if ($hotspot_gallery_name) :
                                                            $hotspot_gallery_name = $hotspot_gallery_name;
                                                        else :
                                                            $hotspot_gallery_name = 'no-preview.png';
                                                        endif;
                                            ?>
                                                <?php if ($item_type == 1) :
                                                            $item_type_check++;

                                                            $previous_hotspot_name = $hotspot_name; // Store the hotspot name
                                                ?>
                                                    <?php if ($location_description) : ?>
                                                        <span>
                                                            <b>About Location: </b> <?= $location_description;
                                                                                    ',' ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($show_day_trip_available) : ?>
                                                        <span>
                                                            Day Trip is Available,
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (trim($arrival_location) == trim($location_name) && $itineary_route_count == 1): ?>
                                                        <span>
                                                            <?= getGLOBALSETTING('itinerary_break_time'); ?>,
                                                        </span>
                                                    <?php else: ?>
                                                        <?= getGLOBALSETTING('itinerary_hotel_start'); ?>
                                                    <?php endif; ?>
                                                <?php elseif ($item_type == 3 && $item_type_check == 0) :
                                                            $previous_hotspot_name = $hotspot_name; // Store the hotspot name
                                                ?>
                                                    <?php if ($location_description) : ?>
                                                        <span>
                                                            <b>About Location: </b> <?= $location_description;
                                                                                    ',' ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($show_day_trip_available) : ?>
                                                        <span>
                                                            Day Trip is Available,
                                                        </span>
                                                    <?php endif; ?>

                                                    <?= getGLOBALSETTING('itinerary_hotel_start'); ?>


                                                <?php endif; ?>
                                                <?php if ($item_type == 2) : ?>
                                                    <span>
                                                        <?= $location_name; ?> to <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>,
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($item_type == 3) :
                                                            $from_hotspot_name = $previous_hotspot_name; // Store the "from" hotspot name
                                                            $to_hotspot_name = $hotspot_name; // Store the "to" hotspot name 
                                                ?>
                                                    <?php if ($allow_break_hours == 1): ?>
                                                        <span>
                                                            Waiting time <b><?= formatTimeDuration($hotspot_traveling_time); ?></b> at this <?= $to_hotspot_name; ?>,
                                                        </span>
                                                    <?php elseif ($allow_via_route == 1):
                                                                $to_hotspot_name = $via_location_name;
                                                    ?>
                                                        <span>
                                                            Proceed <?= $from_hotspot_name; ?> to <?= $to_hotspot_name; ?>,
                                                        </span>
                                                    <?php else: ?>
                                                        <span>
                                                            Proceed <?= $from_hotspot_name; ?> to <?= $to_hotspot_name; ?>,
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if ($item_type == 4 && $hotspot_ID != 0) :
                                                            $previous_hotspot_name = $hotspot_name; // Store the hotspot name
                                                ?>
                                                    <span>
                                                        <span><strong style="font-size: 13px;"><?= $hotspot_name; ?></strong> - <?= $hotspot_description ?></span>,
                                                    </span>

                                                <?php endif; ?>
                                                <?php if ($item_type == 5) : ?>
                                                    <span>
                                                        <?php if ($previous_hotspot_name == ''):
                                                                $update_from_location = $location_name;
                                                            else:
                                                                $update_from_location = $previous_hotspot_name;
                                                            endif;
                                                        ?>
                                                        Proceed <?= $update_from_location; ?> to <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>,
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($item_type == 6) : ?>
                                                    <span>
                                                        Hotel Stay
                                                    </span>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        </td>
                                    </tr>

                                <?php endif; ?>

                                <?php if ($item_type == 7 && $total_itinerary_plan_route_hotspot_details_count == $itineary_route_hotspot_count) : ?>
                                    <tr>
                                        <td colspan="4" align="left" valign="middle" class="center-text" style="color: #595959; font-size: 11px; font-weight: 400; letter-spacing: 0px; border: 1px solid #b1b1b1; border-top: 0px; padding: 3px;color: #302c6e;">
                                            <h3 style="margin:0px;">Return to <?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?>.</h3>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endwhile; ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <!-- END HOTDPOT DETAILS -->
            <?php endif; ?>
            </table>
            </td>
            </tr>
            <tr>
                <td>
                    <table width="700" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                        <tr>
                            <td color="#302c6e" align="center" valign="middle" style="color: #302c6e; font-size: 20px; line-height: 40px; font-weight: 600; letter-spacing: 0px;background-color: #fff;text-align:center;">
                                Terms & Condition
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table width="700" align="center" border="1" cellpadding="5" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                        <tr>
                            <td>
                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; background-color:#fff;">
                                    <tr>
                                        <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                            <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                        <?php endif; ?>
                                        <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                            <?= geTERMSANDCONDITION('get_vehicle_terms_n_condtions'); ?>
                                        <?php endif; ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
        </div>
    </div>

    <div style="margin: 10px 0;display:none;">
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
            var rows = document.querySelectorAll('[id^="vertical_border_"]');
            rows.forEach(function(row) {
                var dividerId = 'vertical-divider-' + row.id.split('_')[2] + '-' + row.id.split('_')[3];
                var divider = document.getElementById(dividerId);
                if (divider) {
                    var leftContentHeight = row.offsetHeight - 20;
                    divider.style.height = leftContentHeight + 'px';
                }
            });
        };
    </script>
</body>

</html>