<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
include_once('../../smtp_functions.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'verify_cancel') :

        //print_r($_POST['hotel_details']);
        //print_r($_POST['cancellation_percentage']);
        //print_r($_POST['cancellation_charge']);
        //print_r($_POST['amenities_details']);
        //print_r($_POST['cnf_itinerary_plan_hotel_voucher_details_ID']);
        $cnf_itinerary_plan_hotel_voucher_details_ID = $_POST['cnf_itinerary_plan_hotel_voucher_details_ID'];
        $data = $_POST;
        $total_cancellation_service = 0;
        $total_aminity_cancellation_amount = 0;
        foreach ($data as $key => $value) :
            if ($key === "hotel_details"):
                //echo "Hotel Details:\n";
                foreach ($value as $date => $details):
                    //echo "  Date: $date\n";
                    //Hotel Room Details
                    foreach ($details["room_details"] as $itinerary_plan_hotel_room_details_ID => $roomDetails):
                        //echo "itinerary_plan_hotel_room_details_ID: $itinerary_plan_hotel_room_details_ID\n";
                        $selected_room_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_route_date` = '$date' AND `itinerary_plan_hotel_room_details_ID`='$itinerary_plan_hotel_room_details_ID'") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($selected_room_query) > 0) :
                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_room_query)) :
                                $room_type_id = $fetch_room_data['room_type_id'];
                                $room_id = $fetch_room_data['room_id'];
                                $room_title = getROOM_DETAILS($room_id, 'room_title');
                                $room_qty = $fetch_room_data['room_qty'];
                                $hotel_id = $fetch_room_data['room_qty'];
                                $itinerary_plan_hotel_details_id = $fetch_room_data['itinerary_plan_hotel_details_id'];
                                foreach ($roomDetails as $meal => $status):
                                    //echo "      $meal: $status\n";
                                    if ($meal == 'room'):
                                        $total_room_cost = $fetch_room_data['total_room_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_room_cost;
                                    endif;
                                    if ($meal == 'child_with_bed'):
                                        $child_with_bed_charges = $fetch_room_data['child_with_bed_charges'];
                                        $total_cancellation_service = $total_cancellation_service + $child_with_bed_charges;
                                    endif;
                                    if ($meal == 'child_without_bed'):
                                        $child_without_bed_charges = $fetch_room_data['child_without_bed_charges'];
                                        $total_cancellation_service = $total_cancellation_service + $child_without_bed_charges;
                                    endif;
                                    if ($meal == 'extra_bed'):
                                        $extra_bed_rate = $fetch_room_data['extra_bed_rate'];
                                        $total_cancellation_service = $total_cancellation_service + $extra_bed_rate;
                                    endif;
                                    if ($meal == 'breakfast'):
                                        $total_breafast_cost = $fetch_room_data['total_breafast_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_breafast_cost;
                                    endif;
                                    if ($meal == 'lunch'):
                                        $total_lunch_cost = $fetch_room_data['total_lunch_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_lunch_cost;
                                    endif;
                                    if ($meal == 'dinner'):
                                        $total_dinner_cost = $fetch_room_data['total_dinner_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_dinner_cost;
                                    endif;

                                endforeach;
                            endwhile;
                        endif;
                    endforeach;

                    //Hotel Amenities details
                    if (isset($details['amenities_details'])):
                        foreach ($details['amenities_details'] as $itinerary_plan_hotel_details_id => $amenities):
                            foreach ($amenities as $hotel_amenities_id => $status) :

                                //echo "Itinerary Plan Hotel Details ID: $itinerary_plan_hotel_details_id, Amenity ID: $hotel_amenities_id\n";

                                $selected_amenities_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_amenities_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `hotel_amenities_id`, `total_qty`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `status`='1' AND `deleted`='0' AND `itinerary_route_date` = '$date' AND `itinerary_plan_hotel_details_id`='$itinerary_plan_hotel_details_id' AND `hotel_amenities_id`='$hotel_amenities_id' ") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                                if (sqlNUMOFROW_LABEL($selected_amenities_query) > 0) :
                                    while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($selected_amenities_query)) :
                                        $itinerary_plan_hotel_room_amenities_details_ID = $fetch_amenities_data['itinerary_plan_hotel_room_amenities_details_ID'];
                                        $amenities_title = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                        $total_qty = $fetch_amenities_data['total_qty'];
                                        $total_amenitie_cost = $fetch_amenities_data['total_amenitie_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_amenitie_cost;

                                    endwhile;
                                endif;
                            endforeach;
                        endforeach;
                    endif;

                endforeach;
            elseif ($key === "cancellation_percentage"):
                //echo "Cancellation Percentage: $value%\n";
                $cancellation_percentage = $value;
            elseif ($key === "cancellation_charge"):
                //echo "Cancellation Charge: $value\n";
                $cancellation_charge = $value;
            endif;
        endforeach;
        echo "\ntotal_cancellation_service" . $total_cancellation_service;
        echo "\ncancellation_charge" . $cancellation_charge;
        $overall_cancellation_amount = $total_cancellation_service  - $cancellation_charge;
        echo "\noverall_cancellation_amount" . $overall_cancellation_amount;
    endif;
else:
    echo "Request Ignored";
endif;
