<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

$itinerary_plan_ID = $_GET['id'];
$recommended1 = $_GET['recommended1'];
$recommended2 = $_GET['recommended2'];
$recommended3 = $_GET['recommended3'];
$recommended4 = $_GET['recommended4'];

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `nationality`, `guide_for_itinerary`,`trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
        $nationality_hotspot = $fetch_itinerary_plan_data['nationality'];
        $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
        $trip_start_date_and_time_vehicle = dateformat_datepicker($fetch_itinerary_plan_data['trip_start_date_and_time']);
        $trip_end_date_and_time_vehicle = dateformat_datepicker($fetch_itinerary_plan_data['trip_end_date_and_time']);
    endwhile;
endif;

class TCPDFCustom extends TCPDF
{
    public function Header()
    {
        $itinerary_plan_ID = $_GET['id'];
        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
            endwhile;
        endif;
        $logoPath = DIRECTORY_DOCUMENT_ROOT . '/assets/img/logo-preview.png';
        $logoWidth = 23;
        $this->Image($logoPath, 2, 5, $logoWidth, '', '', '', 'C');

        $company_name = getGLOBALSETTING('company_name');
        $company_contact_no = getGLOBALSETTING('company_contact_no');

        $this->SetFont('helvetica', 'B', 20);
        $this->SetFont('helvetica', 'BI', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(80, 3, "$company_name", 0, 'R', false, 1, 124, 10);

        $this->SetFont('helvetica', 'BI', 10);
        $this->SetTextColor(0, 0, 128);
        $this->MultiCell(60, 3, "Quote_ID - " . $itinerary_quote_ID, 0, 'R', false, 1, 144, 16);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 8);
        $this->MultiCell(60, 3, "Customer Care $company_contact_no", 0, 'R', false, 1, 144, 22);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->drawPageBorders(); // Ensure border is drawn in footer to appear on all pages
    }

    public function drawPageBorders()
    {
        $topBorderY = 32;
        $bottomBorderY = $this->getPageHeight() - 25;
        $this->Line(5, $topBorderY, $this->getPageWidth() - 5, $topBorderY);
        $this->Line(5, $bottomBorderY, $this->getPageWidth() - 5, $bottomBorderY);
        $this->Line(5, $topBorderY, 5, $bottomBorderY);
        $this->Line($this->getPageWidth() - 5, $topBorderY, $this->getPageWidth() - 5, $bottomBorderY);
    }
}

$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('$company_name');
$pdf->SetTitle('ITINERARY PDF - ' . $itinerary_quote_ID . '');
$pdf->SetSubject('$company_name');
$pdf->SetKeywords('$company_name');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' $company_name', PDF_HEADER_STRING);
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 5, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();


$itinerary_plan_ID = $_GET['id'];



$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
        $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
        $departure_location = $fetch_itinerary_plan_data['departure_location'];
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
        $itinerary_preference = $fetch_itinerary_plan_data['itinerary_preference'];
        $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
        $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
        $trip_start_date_and_time = date('M d, Y | g:i A', strtotime($trip_start_date_and_time));
        $trip_end_date_and_time = date('M d, Y | g:i A', strtotime($trip_end_date_and_time));
        $arrival_type = getTRAVELTYPE($fetch_itinerary_plan_data['arrival_type'], 'label');
        $departure_type = getTRAVELTYPE($fetch_itinerary_plan_data['departure_type'], 'label');
        $entry_ticket_required = $fetch_itinerary_plan_data['entry_ticket_required'];
        $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
        $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
        $total_adult = $fetch_itinerary_plan_data['total_adult'];
        $total_children = $fetch_itinerary_plan_data['total_children'];
        $total_infants = $fetch_itinerary_plan_data['total_infants'];
        $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
        $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
        $food_type = $fetch_itinerary_plan_data['food_type'];
        $nationality = getCOUNTRYLIST($fetch_itinerary_plan_data['nationality'], 'country_label');
        $total_personcount_hotel =  $total_adult +  $total_children;
    endwhile;

    if ($entry_ticket_required == '1') :
        $entry_ticket_required_label = 'Yes';
    else :
        $entry_ticket_required_label = 'No';
    endif;

    if ($guide_for_itinerary == '1') :
        $guide_for_itinerary_label = 'Yes';
    else :
        $guide_for_itinerary_label = 'No';
    endif;


    if ($food_type == '1') :
        $food_type_label = 'Veg';
    elseif ($food_type == '2') :
        $food_type_label = 'Non-Veg';
    elseif ($food_type == '3') :
        $food_type_label = 'Veg & Non-Veg';
    else:
        $food_type_label = 'EP';
    endif;


endif;



// -------------------------------------- ROUTE DETAILS --------------------------------------- //

$pdf->writeHTML('<table cellspacing="0" cellpadding="5" border="1">
 <tr>
   <td width="50%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Arrival Location</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $arrival_location . ' (Arrival ' . $arrival_type . ')</span></td>
   <td width="50%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Departure Location</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $departure_location . ' (Departure ' . $departure_type . ')</span></td>
 </tr>
 <tr>
   <td width="21%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Start Date & Time</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $trip_start_date_and_time . '</span></td>
   <td width="21%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">End Date & Time</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $trip_end_date_and_time . '</span></td>
   <td width="13%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Night & Day</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $no_of_nights . ' N, ' . $no_of_days . ' D</span></td>
   <td width="25%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Person Count - 10</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $total_adult . ' Adult, ' . $total_children . ' Children, ' . $total_infants . ' Infant</span></td>
   <td width="20%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Entry Ticket Required</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $entry_ticket_required_label . '</span></td>
 </tr>
 <tr>
    <td width="25%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Guide for Whole Itineary</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $guide_for_itinerary_label . '</span></td>
    <td width="25%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">No of Rooms</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $preferred_room_count . '</span></td>
    <td width="25%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Nationality</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $nationality . '</span></td>
    <td width="25%"><span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Food Preferences</span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $food_type_label . '</span></td>
 </tr>
</table>', false, false, false, false, '',);



$pdf->writeHTML($tbl_route_details, false, false, false, false, '');

// -------------------------------------- ROUTE DETAILS --------------------------------------- //






$itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
$itinerary_plan_hotel_group_query_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_group_query);
if ($itinerary_plan_hotel_group_query_count > 0) :
    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
        $group_type = $row_hotel_group['group_type'];

        // START ITINERARY PREFERENCE BOTH //
        if ($itinerary_preference == 3) :
            if ($group_type == $recommended1 || $group_type == $recommended2 ||  $group_type == $recommended3 ||  $group_type == $recommended4) :

                $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
            <td><span>&nbsp;</span></td>
           </tr></table>';

                // -------------------------------------- HOTEL DETAILS --------------------------------------- //
                $tbl_details .=
                    '<table cellspacing="0" cellpadding="8" border="1">
            <tr>
            <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                <span style="font-size: 14px; vertical-align: middle;color:#fff;">RECOMMEND HOTEL # ' . $group_type . '</span>
            </th>
            </tr></table>';

                $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
            <tr style="background-color:#DDEBF6; color:#333333;">
                <th width="19%" align="center" style="font-size:12px;"><br/><br/><b>DAY</b><br/></th>
                <th width="16%" align="center" style="font-size:12px;"><br/><br/><b>DESTINATION</b><br/></th>
                <th width="21%" align="center" style="font-size:12px;"><br/><br/><b>HOTAL NAME - CATEGORY</b><br/></th>
                <th width="18%" align="center" style="font-size:12px;"><br/><br/><b>ROOM TYPE - COUNT</b><br/></th>
                <th width="14%" align="center" style="font-size:12px;"><br/><br/><b>PRICE</b><br/></th>
                <th width="12%" align="center" style="font-size:12px;"><br/><br/><b>MEAL PLAN</b><br/></th>
            </tr> ';

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

                        $total_amount_perday = round($total_hotel_cost + $total_hotel_tax_amount);

                        // Calculate the total
                        $total_amount = $hotel_charges + $vehicle_amount + $hotspot_amount + $activity_amount + $guide_charges;
                        if ($selected_hotel_id != 0 && $selected_hotel_id != '') :
                            $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
                        endif;
                        if ($breakfast_required == 1 && $breakfast_cost_per_person != '0') :
                            $hotel_breakfast_label = 'B';
                        else :
                            $hotel_breakfast_label = '';
                        endif;
                        if ($lunch_required == 1 && $lunch_cost_per_person != '0') :
                            $hotel_lunch_label = 'L';
                        else :
                            $hotel_lunch_label = '';
                        endif;
                        if ($dinner_required == 1 && $dinner_cost_per_person != '0') :
                            $hotel_dinner_label = 'D';
                        else :
                            $hotel_dinner_label = '';
                        endif;

                        if ($hotel_required == 1) :
                            $hotel_name_category_label =  getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME') . ' - ' . getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label');
                        else :
                            $hotel_name_category_label = '--';
                        endif;

                        if ($hotel_breakfast_label == '' && $hotel_lunch_label == '' && $hotel_dinner_label == '') :
                            $hotel_meal_plan_label = 'EP';
                        else :
                            $hotel_meal_plan_label =  $hotel_breakfast_label;
                            $hotel_lunch_label;
                            $hotel_dinner_label;
                        endif;

                        $tbl_details .= ' <tr>
                                                            <td width="19%" align="left" style="font-size:12px;">DAY-' . $hotel_counter . ' | ' . dateformat_datepicker($itinerary_route_date) . '</td>
                                                            <td width="16%" align="left" style="font-size:12px;">' . $itinerary_route_location . '</td>
                                                            <td width="21%" align="left" style="font-size:12px;">' . $hotel_name_category_label . '</td>
                                                            <td width="18%" align="left" style="font-size:12px;">' . getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title') . ' - ' . $preferred_room_count . '</td>
                                                            <td width="14%" align="left" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_amount_perday, 2) . '</td>
                                                            <td width="12%" align="left" style="font-size:12px;">' . $hotel_meal_plan_label . '</td>
                                                        </tr>';

                    endwhile;
                endif;
                $tbl_details .= '</table>';

                // -------------------------------------- HOTEL DETAILS --------------------------------------- //

                $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
            <td><span>&nbsp;</span></td>
        </tr></table>';

                // -------------------------------------- VEHICLE DETAILS --------------------------------------- //

                $tbl_details .=
                    '<table cellspacing="0" cellpadding="8" border="1">
                <tr>
                <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                    <span style="font-size: 14px; vertical-align: middle;color:#fff;">VEHICLE DETAILS</span>
                </th>
                </tr></table>';

                $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
                <tr style="background-color:#DDEBF6; color:#333333;">
                    <th width="75%" align="center" style="font-size:12px;"><br/><br/><b>VEHICLE DETAILS</b><br/></th>
                    <th width="25%" align="center" style="font-size:12px;"><br/><br/><b>TOTAL AMOUNT</b><br/></th>
                </tr>';

                $select_itinerary_plan_vendor_vehicle_summary_data1 = sqlQUERY_LABEL("SELECT 
                d1.total_vehicle_amount,
                d2.itinerary_plan_vendor_eligible_ID AS eligible_list_id,
                d2.itineary_plan_assigned_status,
                d2.vehicle_type_id,
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
                        $vehicle_type_id = $fetch_eligible_vendor_vehicle_data1['vehicle_type_id'];
                        $totalextrakmscharge = totalkms($itinerary_plan_ID, 'extra_kms', $vehicle_type_id);
                        $vehicle_type_title = totalkms($vehicle_type_id, 'vehicle_type');
                        $totalamount = round(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount', $vehicle_type_id));
                        $tbl_details .= '<tr>
                <td width="75%" align="left" style="font-size:12px;">' . $vehicle_type_title . ' - ' . $arrival_value . ' ==> ' . $departure_value . ' - ' . $trip_start_date_and_time_vehicle . ' ==> ' . $trip_end_date_and_time_vehicle . '</td>
                                                                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($totalamount, 2) . '</td>
                </tr>';

                    endwhile;
                    $tbl_details .= ' </table> ';
                endif;
                // -------------------------------------- VEHICLE DETAILS --------------------------------------- //

                $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
                     <td><span>&nbsp;</span></td>
                 </tr></table>';

                // -------------------------------------- SUMMARY DETAILS --------------------------------------- //

                $tbl_details .=
                    '<table cellspacing="0" cellpadding="8" border="1">
            <tr>
            <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
                <span style="font-size: 14px; vertical-align: middle;color:#fff;">OVERALL DETAILS</span>
            </th>
            </tr></table>';

                $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">';

                if (($hotelTotal = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES')) != 0) :
                    $tbl_details .= ' <tr>
                <td width="75%" align="left" style="font-size:12px;">Total for the Hotel</td>
                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>  ' . number_format(round($hotelTotal), 2) . '</td>
                </tr>';
                endif;
                if ($hotel_rates_visibility == 1):
                    if (($vehicleTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount')) != 0) :
                        $tbl_details .= ' <tr>
                    <td width="75%" align="left" style="font-size:12px;">Total for the Vehicle</td>
                    <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($vehicleTotal), 2) . '</td>
                    </tr>';
                    endif;
                endif;
                if (($hotspotTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount')) != 0) :
                    $tbl_details .= ' <tr>
                <td width="75%" align="left" style="font-size:12px;">Total for the Hotspot</td>
                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($hotspotTotal), 2) . '</td>
                </tr>';
                endif;
                if (($activityTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout')) != 0) :
                    $tbl_details .= ' <tr>
                <td width="75%" align="left" style="font-size:12px;">Total for the Activity</td>
                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($activityTotal), 2) . '</td>
                </tr>';
                endif;
                if ($total_amount != 0) :
                    $tbl_details .= ' <tr>
                <td width="75%" align="left" style="font-size:12px;">Net Payable to Doview Holidays India Pvt ltd</td>
                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($total_amount), 2) . '</td>
                </tr>';
                endif;

                $tbl_details .= '</table>';

            // -------------------------------------- SUMMARY DETAILS --------------------------------------- //
            endif;
        endif;
        // END ITINERARY PREFERENCE BOTH //

        // START ITINERARY PREFERENCE HOTEL //
        if ($itinerary_preference == 1) :

            if ($group_type == $recommended1 || $group_type == $recommended2 ||  $group_type == $recommended3 ||  $group_type == $recommended4) :

                $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
            <td><span>&nbsp;</span></td>
           </tr></table>';

                // -------------------------------------- HOTEL DETAILS --------------------------------------- //
                $tbl_details .=
                    '<table cellspacing="0" cellpadding="8" border="1">
            <tr>
            <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                <span style="font-size: 14px; vertical-align: middle;color:#fff;">RECOMMEND HOTEL # ' . $group_type . '</span>
            </th>
            </tr></table>';

                $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
            <tr style="background-color:#DDEBF6; color:#333333;">
                <th width="19%" align="center" style="font-size:12px;"><br/><br/><b>DAY</b><br/></th>
                <th width="16%" align="center" style="font-size:12px;"><br/><br/><b>DESTINATION</b><br/></th>
                <th width="21%" align="center" style="font-size:12px;"><br/><br/><b>HOTAL NAME - CATEGORY</b><br/></th>
                <th width="18%" align="center" style="font-size:12px;"><br/><br/><b>ROOM TYPE - COUNT</b><br/></th>
                <th width="14%" align="center" style="font-size:12px;"><br/><br/><b>PRICE</b><br/></th>
                <th width="12%" align="center" style="font-size:12px;"><br/><br/><b>MEAL PLAN</b><br/></th>
            </tr> ';

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

                        $total_amount_perday = round($total_hotel_cost + $total_hotel_tax_amount);

                        // Calculate the total
                        $total_amount = $hotel_charges + $vehicle_amount + $hotspot_amount + $activity_amount + $guide_charges;
                        if ($selected_hotel_id != 0 && $selected_hotel_id != '') :
                            $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
                        endif;
                        if ($breakfast_required == 1 && $breakfast_cost_per_person != '0') :
                            $hotel_breakfast_label = 'B';
                        else :
                            $hotel_breakfast_label = '';
                        endif;
                        if ($lunch_required == 1 && $lunch_cost_per_person != '0') :
                            $hotel_lunch_label = 'L';
                        else :
                            $hotel_lunch_label = '';
                        endif;
                        if ($dinner_required == 1 && $dinner_cost_per_person != '0') :
                            $hotel_dinner_label = 'D';
                        else :
                            $hotel_dinner_label = '';
                        endif;

                        if ($hotel_required == 1) :
                            $hotel_name_category_label =  getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME') . ' - ' . getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label');
                        else :
                            $hotel_name_category_label = '--';
                        endif;

                        if ($hotel_breakfast_label == '' && $hotel_lunch_label == '' && $hotel_dinner_label == '') :
                            $hotel_meal_plan_label = 'EP';
                        else :
                            $hotel_meal_plan_label =  $hotel_breakfast_label;
                            $hotel_lunch_label;
                            $hotel_dinner_label;
                        endif;

                        $tbl_details .= ' <tr>
                                                            <td width="19%" align="left" style="font-size:12px;">DAY-' . $hotel_counter . ' | ' . dateformat_datepicker($itinerary_route_date) . '</td>
                                                            <td width="16%" align="left" style="font-size:12px;">' . $itinerary_route_location . '</td>
                                                            <td width="21%" align="left" style="font-size:12px;">' . $hotel_name_category_label . '</td>
                                                            <td width="18%" align="left" style="font-size:12px;">' . getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title') . ' - ' . $preferred_room_count . '</td>
                                                            <td width="14%" align="left" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_amount_perday, 2) . '</td>
                                                            <td width="12%" align="left" style="font-size:12px;">' . $hotel_meal_plan_label . '</td>
                                                        </tr>';

                    endwhile;
                endif;
                $tbl_details .= '</table>';

                // -------------------------------------- HOTEL DETAILS --------------------------------------- //

                // -------------------------------------- SUMMARY DETAILS --------------------------------------- //

                $tbl_details .=
                    '<table cellspacing="0" cellpadding="8" border="1">
        <tr>
        <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
            <span style="font-size: 14px; vertical-align: middle;color:#fff;">OVERALL DETAILS</span>
        </th>
        </tr></table>';

                $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">';

                if (($hotelTotal = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES')) != 0) :
                    $tbl_details .= ' <tr>
            <td width="75%" align="left" style="font-size:12px;">Total for the Hotel</td>
            <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>  ' . number_format(round($hotelTotal), 2) . '</td>
            </tr>';
                endif;
                if ($hotel_rates_visibility == 1):
                    if (($vehicleTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount')) != 0) :
                        $tbl_details .= ' <tr>
                <td width="75%" align="left" style="font-size:12px;">Total for the Vehicle</td>
                <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($vehicleTotal), 2) . '</td>
                </tr>';
                    endif;
                endif;
                if (($hotspotTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount')) != 0) :
                    $tbl_details .= ' <tr>
            <td width="75%" align="left" style="font-size:12px;">Total for the Hotspot</td>
            <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($hotspotTotal), 2) . '</td>
            </tr>';
                endif;
                if (($activityTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout')) != 0) :
                    $tbl_details .= ' <tr>
            <td width="75%" align="left" style="font-size:12px;">Total for the Activity</td>
            <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($activityTotal), 2) . '</td>
            </tr>';
                endif;
                if ($total_amount != 0) :
                    $tbl_details .= ' <tr>
            <td width="75%" align="left" style="font-size:12px;">Net Payable to Doview Holidays India Pvt ltd</td>
            <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($total_amount), 2) . '</td>
            </tr>';
                endif;

                $tbl_details .= '</table>';

            // -------------------------------------- SUMMARY DETAILS --------------------------------------- //
            endif;

        endif;
    // END ITINERARY PREFERENCE HOTEL //

    endwhile;
    $pdf->writeHTML($tbl_details, true, false, false, false, '');
endif;

// START ITINERARY PREFERENCE VEHICLE //
if ($itinerary_preference == 2) :

    $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
    <td><span>&nbsp;</span></td>
</tr></table>';
    // -------------------------------------- VEHICLE DETAILS --------------------------------------- //

    $tbl_details .=
        '<table cellspacing="0" cellpadding="8" border="1">
        <tr>
        <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
            <span style="font-size: 14px; vertical-align: middle;color:#fff;">VEHICLE DETAILS</span>
        </th>
        </tr></table>';

    $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
        <tr style="background-color:#DDEBF6; color:#333333;">
            <th width="75%" align="center" style="font-size:12px;"><br/><br/><b>VEHICLE DETAILS</b><br/></th>
            <th width="25%" align="center" style="font-size:12px;"><br/><br/><b>TOTAL AMOUNT</b><br/></th>
        </tr>';

    $select_itinerary_plan_vendor_vehicle_summary_data1 = sqlQUERY_LABEL("SELECT 
        d1.total_vehicle_amount,
        d2.itinerary_plan_vendor_eligible_ID AS eligible_list_id,
        d2.itineary_plan_assigned_status,
        d2.vehicle_type_id,
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
            $vehicle_type_id = $fetch_eligible_vendor_vehicle_data1['vehicle_type_id'];
            $totalextrakmscharge = totalkms($itinerary_plan_ID, 'extra_kms', $vehicle_type_id);
            $vehicle_type_title = totalkms($vehicle_type_id, 'vehicle_type');
            $totalamount = round(getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount', $vehicle_type_id));
            $tbl_details .= '<tr>
        <td width="75%" align="left" style="font-size:12px;">' . $vehicle_type_title . ' - ' . $arrival_value . ' ==> ' . $departure_value . ' - ' . $trip_start_date_and_time_vehicle . ' ==> ' . $trip_end_date_and_time_vehicle . '</td>
                                                        <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($totalamount, 2) . '</td>
        </tr>';

        endwhile;
        $tbl_details .= ' </table> ';
    endif;
    // -------------------------------------- VEHICLE DETAILS --------------------------------------- //

    $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
             <td><span>&nbsp;</span></td>
         </tr></table>';

    // -------------------------------------- SUMMARY DETAILS --------------------------------------- //

    $tbl_details .=
        '<table cellspacing="0" cellpadding="8" border="1">
    <tr>
    <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
        <span style="font-size: 14px; vertical-align: middle;color:#fff;">OVERALL DETAILS</span>
    </th>
    </tr></table>';

    $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">';

    if (($hotelTotal = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES')) != 0) :
        $tbl_details .= ' <tr>
        <td width="75%" align="left" style="font-size:12px;">Total for the Hotel</td>
        <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>  ' . number_format(round($hotelTotal), 2) . '</td>
        </tr>';
    endif;
    if ($hotel_rates_visibility == 1):
        if (($vehicleTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount')) != 0) :
            $tbl_details .= ' <tr>
            <td width="75%" align="left" style="font-size:12px;">Total for the Vehicle</td>
            <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($vehicleTotal), 2) . '</td>
            </tr>';
        endif;
    endif;
    if (($hotspotTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount')) != 0) :
        $tbl_details .= ' <tr>
        <td width="75%" align="left" style="font-size:12px;">Total for the Hotspot</td>
        <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($hotspotTotal), 2) . '</td>
        </tr>';
    endif;
    if (($activityTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout')) != 0) :
        $tbl_details .= ' <tr>
        <td width="75%" align="left" style="font-size:12px;">Total for the Activity</td>
        <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($activityTotal), 2) . '</td>
        </tr>';
    endif;
    if ($total_amount != 0) :
        $tbl_details .= ' <tr>
        <td width="75%" align="left" style="font-size:12px;">Net Payable to Doview Holidays India Pvt ltd</td>
        <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($total_amount), 2) . '</td>
        </tr>';
    endif;

    $tbl_details .= '</table>';

    // -------------------------------------- SUMMARY DETAILS --------------------------------------- //
    $pdf->writeHTML($tbl_details, true, false, false, false, '');
endif;
// END ITINERARY PREFERENCE VEHICLE //




// -------------------------------------- HOTSPOT DETAILS --------------------------------------- //

$pdf->writeHTML(
    '<table cellspacing="0" cellpadding="8" border="1">
   <tr>
    <td colspan="3" align="center" style="font-weight:bold; font-size: 12px;background-color:#dc3545;">
        <span style="font-size: 14px; vertical-align: middle;color:#fff;">HOTSPOT DETAILS</span>
    </td>
  </tr></table>',
    true,
    false,
    false,
    false,
    ''
);


$select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details_query);
if ($total_itinerary_route_details_count > 0) :
    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query)) :
        $hotspot_daycount++;
        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
        $location_name = $fetch_itinerary_route_data['location_name'];
        $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];
        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
        $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));

        $select_itinerary_routehotspot_details_query = sqlQUERY_LABEL("SELECT `hotspot_amout` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '4'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        $total_itinerary_routehotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_routehotspot_details_query);
        if ($total_itinerary_routehotspot_details_count > 0) :
            $has_non_zero_hotspot_amount = false;

            while ($fetch_itinerary_routehotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_routehotspot_details_query)) :
                $hotspot_amout = $fetch_itinerary_routehotspot_data['hotspot_amout'];
                $hotspot_amout_format = number_format($hotspot_amout, 2, '.', '');

                if ($hotspot_amout_format != 0) {
                    $has_non_zero_hotspot_amount = true;
                }
            endwhile;
        endif;


        $tbl_hotspot .= '
         <table cellspacing="0" cellpadding="8" border="1">
            <tr style="font-size:14px;font-weight:bold;">
              <td colspan="4" align="left">Day ' . $hotspot_daycount . ' - ' . $trip_start_date_and_time . ' | ' . $location_name . ' ==> ' . $next_visiting_location . '</td>
            </tr>
            <tr style="background-color:#DDEBF6;color:#333333;">
                <td width="18%" align="center" style="font-size:12px;"><br/><br/><b>TIMING</b><br/></td>
                <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>DURATION</b><br/></td>';
        if ($has_non_zero_hotspot_amount) :
            $tbl_hotspot .= '<td width="53%" align="center" style="font-size:12px;"><br/><br/><b>HOTSPOT PLACES</b><br/></td>
                    <td width="17%" align="center" style="font-size:12px;"><br/><br/><b>COST</b><br/></td>
                    </tr>';
        else :
            $tbl_hotspot .= '<td width="70%" align="center" style="font-size:12px;"><br/><br/><b>HOTSPOT PLACES</b><br/></td>
                    </tr>';
        endif;

        if ($guide_for_itinerary == 0) :

            // echo "SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'";
            // exit;
            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
            $route_guide_ID = '';
            $guide_type = '';
            $guide_language = '';
            $guide_slot = '';
            // $guide_cost = 0;
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

                $tbl_hotspot .= '
               <tr style="font-size:14px;color: #333333;font-weight:normal;background-color:#ffe7d1;">
               <td width="83%" align="left">
                  <span style="font-size: 13px;font-weight:bold;">Guide</span> - ' . getGUIDE_LANGUAGE_DETAILS($guide_language, 'label') . ' - ' . getSLOTTYPE($guide_slot, 'label') . ' 
               </td>
               <td width="17%" align="right">
                   <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . number_format($guide_cost, 2) . '
               </td>
           </tr>';
            endif;
        endif;

        $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_duration`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_HOTSPOT.`item_type` = '4';") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
        $itineary_route_hotspot_count = 0;
        $total_hostpot_amount = 0;
        $total_hostpot_amount_format = 0;


        if ($total_itinerary_plan_route_hotspot_details_count > 0) :
            while ($fetch_itinerary_plan_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_details_query)) :
                $hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['hotspot_ID'];
                $route_hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['route_hotspot_ID'];
                $hotspot_adult_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_adult_entry_cost'];
                $hotspot_child_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_child_entry_cost'];
                $hotspot_infant_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_infant_entry_cost'];
                $hotspot_foreign_adult_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_foreign_adult_entry_cost'];
                $hotspot_foreign_child_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_foreign_child_entry_cost'];
                $hotspot_foreign_infant_entry_cost = $fetch_itinerary_plan_route_hotspot_data['hotspot_foreign_infant_entry_cost'];
                $hotspot_start_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_start_time'];
                $hotspot_start_time_format = date('h:i A', strtotime($hotspot_start_time));
                $hotspot_end_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_end_time'];
                $hotspot_end_time_format = date('h:i A', strtotime($hotspot_end_time));
                $hotspot_traveling_time = $fetch_itinerary_plan_route_hotspot_data['hotspot_traveling_time'];
                $hotspot_traveling_time_duration = formatTimeDuration($hotspot_traveling_time);
                $hotspot_name = trim($fetch_itinerary_plan_route_hotspot_data['hotspot_name']);
                $hotspot_duration = $fetch_itinerary_plan_route_hotspot_data['hotspot_duration'];
                $hotspot_duration_duration = formatTimeDuration($hotspot_duration);
                $hotspot_amout = $fetch_itinerary_plan_route_hotspot_data['hotspot_amout'];
                $hotspot_amout_format = number_format($hotspot_amout, 2, '.', '');

                $total_hostpot_amount += $hotspot_amout_format;
                $total_hostpot_amount_format = number_format($total_hostpot_amount, 2, '.', '');

                $hotspot_gallery = getHOTSPOT_GALLERY_DETAILS($fetch_itinerary_plan_route_hotspot_data['hotspot_ID'], 'hotspot_gallery_name');

                if ($nationality_hotspot == 101) :
                    $hotspot_person = 'Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_adult_entry_cost . ', Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_child_entry_cost . ',';
                    $hotspot_person_infant =  'Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_infant_entry_cost . '';
                else :
                    $hotspot_person = 'Foreign Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_adult_entry_cost . ',Foreign Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_child_entry_cost . ',';
                    $hotspot_person_infant =  'Foreign Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_infant_entry_cost . '';
                endif;

                $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/hotspot_gallery/' . $hotspot_gallery;
                $image_path = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery;
                $default_image = BASEPATH . 'uploads/no-photo.png';

                if ($hotspot_gallery):
                    // Check if the image file exists
                    $hotspot_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                else:
                    $hotspot_gallery_data = $default_image;
                endif;

                //   Debug output
                //   echo "Value of \$hotspot_gallery: $hotspot_gallery<br>";
                //   echo "Value of \$hotspot_gallery_data: $hotspot_gallery_data <br>";

                $tbl_hotspot .= ' 
           
               <tr style="font-size:12px;">
                 <td width="18%">' . $hotspot_start_time_format . ' - ' . $hotspot_end_time_format . '</td>
                 <td width="12%">' . $hotspot_traveling_time_duration . '</td>
                 <td width="70%">
                 <table cellspacing="0" cellpadding="6" border="0">';

                if ($hotspot_amout_format == 0 || $hotspot_amout_format == '') :
                    $tbl_hotspot .= '<tr>
                           <td width="76%">
                                <table align="left"  border="0" cellpadding="0" cellspacing="0">
                                 <tbody>
                                    <tr>
                                     <td width="15%">
                                        <img src="' . $hotspot_gallery_data . '" alt="Icon" width="50px" border="0">
                                     </td>
                                     <td width="85%">
                                        <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="font-size: 13px;font-weight:semi-bold;" width="100%">' . $hotspot_name . '
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                        ' . $hotspot_person . '
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                     ' . $hotspot_person_infant . '
                                                    </td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                     </td>
                                    </tr>
                                 </tbody>
                                </table>
                            </td>
            
                        </tr>';
                elseif ($hotspot_amout_format != 0 || $hotspot_amout_format != '') :
                    $tbl_hotspot .= '<tr>
                    <td width="76%">
                         <table align="left"  border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                             <tr>
                              <td width="15%">
                                 <img src="' . $hotspot_gallery_data . '" alt="Icon" width="110px" border="0">
                              </td>
                              <td width="85%">
                                 <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                     <tbody>
                                         <tr>
                                             <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="font-size: 13px;font-weight:semi-bold;" width="100%">
                                             ' . $hotspot_name . '
                                             </td>
                                         </tr>
                                         <tr>
                                             <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                 ' . $hotspot_person . '
                                             </td>
                                         </tr>
                                         <tr>
                                             <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                              ' . $hotspot_person_infant . '
                                             </td>
                                         </tr> 
                                     </tbody>
                                 </table>
                              </td>
                             </tr>
                          </tbody>
                         </table>
                     </td>
                     <td width="24%" align="right" style="border-left: 1px solid #333;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_amout_format . '</td>
                 </tr>';
                endif;

                $select_itinerary_plan_activity = sqlQUERY_LABEL("SELECT `route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `activity_ID`, `activity_order`, `activity_charges_for_foreign_adult`, `activity_charges_for_foreign_children`, `activity_charges_for_foreign_infant`, `activity_charges_for_adult`, `activity_charges_for_children`, `activity_charges_for_infant`, `activity_amout`, `activity_traveling_time`, `activity_start_time`, `activity_end_time` FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' and `route_hotspot_ID` = '$route_hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                $total_itinerary_plan_activity_count = sqlNUMOFROW_LABEL($select_itinerary_plan_activity);
                $activitycount = 0;
                $total_activity_amout_format = 0;

                if ($total_itinerary_plan_activity_count > 0) :
                    while ($fetch_activity_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_activity)) :
                        $activitycount++;
                        $activity_ID = $fetch_activity_data['activity_ID'];
                        $activity_name = getACTIVITYDETAILS($fetch_activity_data['activity_ID'], 'label');
                        $activity_start_time = $fetch_activity_data['activity_start_time'];
                        $activity_start_time_format = date('h:i A', strtotime($activity_start_time));
                        $activity_end_time = $fetch_activity_data['activity_end_time'];
                        $activity_end_time_format = date('h:i A', strtotime($activity_end_time));
                        $activity_traveling_time = $fetch_activity_data['activity_traveling_time'];
                        $activity_traveling_time_format = formatTimeDuration($activity_traveling_time);
                        $activity_charges_for_foreign_adult = $fetch_activity_data['activity_charges_for_foreign_adult'];
                        $activity_charges_for_foreign_children = $fetch_activity_data['activity_charges_for_foreign_children'];
                        $activity_charges_for_foreign_infant = $fetch_activity_data['activity_charges_for_foreign_infant'];
                        $activity_charges_for_adult = $fetch_activity_data['activity_charges_for_adult'];
                        $activity_charges_for_children = $fetch_activity_data['activity_charges_for_children'];
                        $activity_charges_for_infant = $fetch_activity_data['activity_charges_for_infant'];
                        $activity_amout = $fetch_activity_data['activity_amout'];
                        $activity_amout_format = number_format($activity_amout, 2, '.', '');
                        $total_activity_amout += $activity_amout_format;
                        $total_activity_amout_format = number_format($total_activity_amout, 2, '.', '');

                        if ($nationality_hotspot == 101) :
                            $activity_person = 'Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_adult . ', Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_children . ',';
                            $activity_person_infant =  'Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_infant . '';
                        else :
                            $activity_person = 'Foreign Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_foreign_adult . ',Foreign Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_foreign_children . ',';
                            $activity_person_infant =  'Foreign Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_charges_for_foreign_infant . '';
                        endif;

                        $activity_gallery = getACTIVITY_IMAGE_GALLERY_DETAILS($fetch_activity_data['activity_ID'], 'get_first_activity_image_gallery_name');

                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/activity_gallery/' . $activity_gallery;
                        $image_path = BASEPATH . '/uploads/activity_gallery/' . $activity_gallery;
                        $default_image = BASEPATH . 'uploads/no-photo.png';

                        if ($activity_gallery):
                            // Check if the image file exists
                            $activity_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                        else:
                            $activity_gallery_data = $default_image;
                        endif;

                        $tbl_hotspot .= '
                        <tr>
                            <td colspan="2" style="border-right: 1px solid #333;" width="76%"><hr style="border-top:1px dashed black;" width="100%"/></td>
                        </tr>
                   
                        <tr>
                        <td width="76%">
                            <div style="color:#191919;font-size:12px;font-weight:400;line-height:0px;">
                            <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td align="left" width="19%"><b>Activity #' . $activitycount . '</b></td>
                                    <td align="left" width="2%"><b>- </b></td>
                                    <td align="left" width="79%"><b>' . $activity_name . '</b></td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                         <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                <td width="13%">
                                    <img data-image="Icon 5" src="' . $activity_gallery_data . '" alt="Icon" width="110px" border="0" style="width:110px;height:110px;border:1px solid #fff;display:inline-block !important;">
                                </td>
                                <td width="87%">
                                    <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                    Timing - ' . $activity_start_time_format . ' - ' . $activity_end_time_format . '
                                                </td>
                                            </tr>
                                            <tr>
                                                    <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                      ' . $activity_person . '
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td data-text="Icon Description" data-font="Primary" align="left" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                      ' . $activity_person_infant . '
                                                    </td>
                                                </tr> 
                                            <tr>
                                                <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                    Duration - ' . $activity_traveling_time_format . '
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                </tr>
                            </tbody>
                         </table>
                        </td>
                        <td width="24%" align="right" style="border-left: 1px solid #333;">
                            <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $activity_amout_format . '
                        </td>
                    </tr>';
                    endwhile;
                endif;
                $tbl_hotspot .= '</table>
                </td>
            </tr>';
            endwhile;

            $total_grand = $total_hostpot_amount_format + $total_activity_amout_format + $guide_cost;
            $total_grand_format = number_format($total_grand, 2, '.', '');
            if ($total_hostpot_amount_format != 0) :
                $tbl_hotspot .= ' <tr style="font-size:14px;">
            <td width="83%" align="right">
            Total Hotspot Charges
            </td>
            <td width="17%" align="right">
            <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hostpot_amount_format . '
            </td>
        </tr>';
            endif;

            if ($guide_cost != 0) :
                $tbl_hotspot .= '<tr style="font-size:14px;">
        <td width="83%" align="right">
            Total Guide Charges
            </td>
        <td width="17%" align="right">
            <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($guide_cost, 2) . '
        </td>
       </tr>';
            endif;

            if ($total_activity_amout_format != 0) :
                $tbl_hotspot .= '<tr style="font-size:14px;">
            <td width="83%" align="right">
                Total Activity Charges
                </td>
            <td width="17%" align="right">
                <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_activity_amout_format, 2) . '
            </td>
        </tr>';
            endif;

            if ($total_grand_format != 0) :
                $tbl_hotspot .= '<tr style="background-color:RGB(0, 0, 128); font-size:14px;">
            <td colspan="3" align="right" width="83%">
                <span style="font-weight: bold; color:#fff;">Gross Total for the Day #' . $hotspot_daycount . '</span>
            </td>
            <td align="right" width="17%">
                <span style="font-weight: bold; color:#fff;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_grand_format . '</span>
            </td>
        </tr>';
                $tbl_hotspot .= ' <tr>
                                            <td width="100%"><span>&nbsp;</span></td>
                                        </tr>';
            endif;
        else:
            $tbl_hotspot .= ' <table cellspacing="0" cellpadding="8" border="1">
                    <tr style="font-size:14px;font-weight:bold;">
                      <td colspan="4" align="center">No Hotspot Found</td>
                    </tr></table>';
        endif;


        $tbl_hotspot .= '</table>';
    endwhile;

endif;

$pdf->writeHTML($tbl_hotspot, true, false, true, false, '');


// -------------------------------------- HOTSPOT DETAILS --------------------------------------- //


$pdf->writeHTML($tbl_complete_summary, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('travel_itinerary - ' . $itinerary_quote_ID . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+