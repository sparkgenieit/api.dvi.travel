<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

$itinerary_plan_ID = $_GET['id'];

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID`, `nationality`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
        $nationality_hotspot = $fetch_itinerary_plan_data['nationality'];
        $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
    endwhile;
endif;



class TCPDFCustom extends TCPDF
{
    public function Header()
    {
        $itinerary_plan_ID = $_GET['id'];
        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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



$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

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
</table>', true, false, false, false, '',);

$itinerary_plan_hotel_group_overall_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
$itinerary_plan_hotel_group_overall_query_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_group_overall_query);
if ($itinerary_plan_hotel_group_overall_query_count > 0) :
    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_overall_query)) :
        $group_type = $row_hotel_group['group_type'];
    endwhile;
endif;

$pdf->writeHTML('<table  cellspacing="0" cellpadding="8" border="1"><tr><td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
<span style="font-size: 14px; vertical-align: middle;color:#fff;">Over All Package Cost: <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount_pdf')), 2) . '</span>
</td></tr></table>', true, false, false, false, '',);

$tbl_route_details .= '
    <table cellspacing="0" cellpadding="8" border="1">
        <thead>
            <tr>
                <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                    <span style="font-size: 14px; vertical-align: middle;color:#fff;">ROUTE DETAILS</span>
                </td>
            </tr>

            <tr style="background-color:#DDEBF6;color:#333333;">
                <td width="8%" align="center" style="font-size:12px;"><br/><br/><b>DAY</b><br/></td>
                <td width="13%" align="center" style="font-size:12px;"><br/><br/><b>DATE</b><br/></td>
                <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>SOURCE</b><br/></td>
                <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>DESTINATION</b><br/></td>
                <td width="30%" align="center" style="font-size:12px;"><br/><br/><b>VIA ROUTE</b><br/></td>
                <td width="11%" align="center" style="font-size:12px;"><br/><br/><b>DIRECT DESTINATION</b><br/></td>
            </tr>
        </thead>';


$select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
$select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
if ($select_itinerary_route_details_count > 0) :
    while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
        $route_day_counter++;
        $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
        $location_name = $fetch_itineary_route_data['location_name'];
        $itinerary_route_date = date('M d, Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
        $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];

        $direct_to_next_visiting_place = $fetch_itineary_route_data['direct_to_next_visiting_place'];
        if ($direct_to_next_visiting_place == '1') :
            $direct_to_next_visiting_place_label = 'Yes';
        else :
            $direct_to_next_visiting_place_label = 'No';
        endif;

        $select_itinerary_via_route_list_query = sqlQUERY_LABEL("SELECT `itinerary_via_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `itinerary_route_date`, `source_location`, `destination_location`, `itinerary_via_location_name` FROM `dvi_confirmed_itinerary_via_route_details` WHERE `deleted` = '0' AND `status` = '1'  AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_ITINEARY_ROUTE_VIA_LOCATION_LIST:" . sqlERROR_LABEL());
        $select_itinerary_via_route_count = sqlNUMOFROW_LABEL($select_itinerary_via_route_list_query);
        $itinerary_via_location_name_all_label = '';
        $via_route_count = 0;
        if ($select_itinerary_via_route_count > 0) :
            while ($fetch_itinerary_via_route_list_data = sqlFETCHARRAY_LABEL($select_itinerary_via_route_list_query)) :
                $via_route_count++;
                $itinerary_via_location_name = $fetch_itinerary_via_route_list_data['itinerary_via_location_name'];

                $itinerary_via_location_name_all_label .= $via_route_count . '. ' . $itinerary_via_location_name . '<br/>';

            /*if($select_itinerary_via_route_count > $via_route_count && $select_itinerary_via_route_count != $via_route_count):
                                            $itinerary_via_location_name_all_label .= ' -> ';
                                        endif;*/
            endwhile;
        endif;

        if ($itinerary_via_location_name_all_label == '') :
            $itinerary_via_location_name_all_label = '--';
        endif;

        $tbl_route_details .= '<tr>
                                <td width="8%" align="left" style="font-size:12px;">DAY ' . $route_day_counter . '</td>
                                <td width="13%" align="left" style="font-size:12px;">' . $itinerary_route_date . '</td>
                                <td width="19%" align="left" style="font-size:12px;">' . $location_name . '</td>
                                <td width="19%" align="left" style="font-size:12px;">' . $next_visiting_location . '</td>
                                <td width="30%" align="left" style="font-size:12px;">' . $itinerary_via_location_name_all_label . '</td>
                                <td width="11%" align="left" style="font-size:12px;">' . $direct_to_next_visiting_place_label . '</td>
                            </tr>';
    endwhile;
endif;
$tbl_route_details .= '</table>';

$pdf->writeHTML($tbl_route_details, true, false, false, false, '');

// -------------------------------------- ROUTE DETAILS --------------------------------------- //







$itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
$itinerary_plan_hotel_group_query_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_group_query);
if ($itinerary_plan_hotel_group_query_count > 0) :
    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
        $group_type = $row_hotel_group['group_type'];

        // START ITINERARY PREFERENCE BOTH //
        if ($itinerary_preference == 3) :

            // -------------------------------------- HOTEL DETAILS --------------------------------------- //
            $tbl_details .=
                '<table cellspacing="0" cellpadding="8" border="1">
            <tr>
            <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                <span style="font-size: 14px; vertical-align: middle;color:#fff;">RECOMMEND HOTEL # ' . $group_type . '</span>
            </th>
            </tr></table>';

            $select_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `itinerary_route_location`, `hotel_required`, `hotel_category_id`, `hotel_id`, `hotel_margin_percentage`, `hotel_margin_gst_type`, `hotel_margin_gst_percentage`, `hotel_margin_rate`, `hotel_margin_rate_tax_amt`, `hotel_breakfast_cost`, `hotel_breakfast_cost_gst_amount`, `hotel_lunch_cost`, `hotel_lunch_cost_gst_amount`, `hotel_dinner_cost`, `hotel_dinner_cost_gst_amount`, `total_no_of_persons`, `total_hotel_meal_plan_cost`, `total_hotel_meal_plan_cost_gst_amount`, `total_extra_bed_cost`, `total_extra_bed_cost_gst_amount`, `total_childwith_bed_cost`, `total_childwith_bed_cost_gst_amount`, `total_childwithout_bed_cost`, `total_childwithout_bed_cost_gst_amount`, `total_no_of_rooms`, `total_room_cost`, `total_room_gst_amount`, `total_hotel_cost`, `total_amenities_cost`, `total_amenities_gst_amount`, `total_hotel_tax_amount` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_ID' and `group_type` = '$group_type' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
            $select_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details);
            if ($select_hotel_details_count > 0) :
                $hotel_day_count = 0;
                while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
                    $hotel_day_count++;
                    $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                    $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                    $total_hotel_cost = $fetch_hotel_data['total_hotel_cost'];
                    $total_hotel_tax_amount = $fetch_hotel_data['total_hotel_tax_amount'];
                    $itinerary_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                    $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                    $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];
                    $hotel_margin_rate_tax_amt = number_format($fetch_hotel_data['hotel_margin_rate_tax_amt'], 2, '.', '');
                    $total_room_gst_amount = number_format($fetch_hotel_data['total_room_gst_amount'], 2, '.', '');
                    $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                    $hotel_name = getHOTEL_DETAIL($fetch_hotel_data['hotel_id'], '', 'label');
                    $hotel_margin_rate = number_format($fetch_hotel_data['hotel_margin_rate'], 2, '.', '');
                    $total_hotel_meal_plan_cost = number_format($fetch_hotel_data['total_hotel_meal_plan_cost'], 2, '.', '');
                    $total_grand_total = $total_hotel_cost + $total_hotel_tax_amount;
                    $total_grand = number_format($total_grand_total, 2, '.', '');
                    $total_amenities_cost = $fetch_hotel_data['total_amenities_cost'];
                    $total_amenities_gst_amount = $fetch_hotel_data['total_amenities_gst_amount'];
                    $total_extra_bed_cost = $fetch_hotel_data['total_extra_bed_cost'];
                    $total_extra_bed_cost_gst_amount = $fetch_hotel_data['total_extra_bed_cost_gst_amount'];
                    $total_childwith_bed_cost = $fetch_hotel_data['total_childwith_bed_cost'];
                    $total_childwith_bed_cost_gst_amount = $fetch_hotel_data['total_childwith_bed_cost_gst_amount'];
                    $total_childwithout_bed_cost = $fetch_hotel_data['total_childwithout_bed_cost'];
                    $total_childwithout_bed_cost_gst_amount = $fetch_hotel_data['total_childwithout_bed_cost_gst_amount'];
                    $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                    $hotel_breakfast_cost_gst_amount = $fetch_hotel_data['hotel_breakfast_cost_gst_amount'];
                    $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                    $hotel_lunch_cost_gst_amount = $fetch_hotel_data['hotel_lunch_cost_gst_amount'];
                    $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                    $hotel_dinner_cost_gst_amount = $fetch_hotel_data['hotel_dinner_cost_gst_amount'];
                    $total_hotel_meal_plan_cost_gst_amount = $fetch_hotel_data['total_hotel_meal_plan_cost_gst_amount'];


                    $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
                        <tr>
                            <td colspan="3" align="left" width="60%" style="font-weight:bold; font-size: 12px;">
                                <span style="font-size: 14px; color:#000;">Day ' . $hotel_day_count . ' - ' . $itinerary_start_date_and_time . ' |  ' . $itinerary_route_location . '</span>
                            </td>
                            <td colspan="3" align="right" width="40%" style="font-weight:bold; font-size: 12px;">
                                <span style="font-size: 14px; color:#000;">' . $hotel_name . '</span>
                            </td>
                        </tr>
                        
                        <tr style="background-color:#DDEBF6; color:#333333;">
                            <th width="40%" align="center" style="font-size:12px;"><br/><br/><b>ROOM DETAILS</b><br/></th>
                            <th width="45%" align="center" style="font-size:12px;"><br/><br/><b>COST DETAILS</b><br/></th>
                            <th width="15%" align="center" style="font-size:12px;"><br/><br/><b>AMOUNT</b><br/></th>
                        </tr>';


                    $select_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_hotel_details_ID` =  '$itinerary_plan_hotel_details_ID' and `itinerary_plan_id` = '$itinerary_plan_ID' and `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                    $select_room_details_count = sqlNUMOFROW_LABEL($select_room_details);
                    $total_room_rate = 0;
                    $total_overall_food_cost = 0;
                    $room_count = 0;
                    $total_room_cost = 0;
                    if ($select_room_details_count > 0) :
                        while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_room_details)) :
                            $room_count++;
                            $hotel_id = $fetch_room_data['hotel_id'];
                            $room_id = $fetch_room_data['room_id'];
                            $itinerary_plan_hotel_details_ID = $fetch_room_data['itinerary_plan_hotel_details_ID'];
                            $itinerary_route_date = $fetch_room_data['itinerary_route_date'];
                            $gst_percentage = $fetch_room_data['gst_percentage'];
                            $room_type = getROOMTYPE_DETAILS($fetch_room_data['room_id'], 'room_type_title');
                            $room_check_in = getROOM_DETAILS($fetch_room_data['room_id'], 'check_in_time');
                            $room_checkin_timeformat = date('h:i A', strtotime($room_check_in));
                            $room_check_in = getROOM_DETAILS($fetch_room_data['room_id'], 'check_out_time');
                            $room_checkout_timeformat = date('h:i A', strtotime($room_check_in));
                            $max_adult = getROOM_DETAILS($fetch_room_data['room_id'], 'max_adult');
                            $max_child = getROOM_DETAILS($fetch_room_data['room_id'], 'max_child');
                            $breakfast = $fetch_room_data['breakfast_required'];
                            $lunch = $fetch_room_data['lunch_required'];
                            $dinner = $fetch_room_data['dinner_required'];
                            $breakfast_cost_per_person = $fetch_room_data['breakfast_cost_per_person'];
                            $lunch_cost_per_person = $fetch_room_data['lunch_cost_per_person'];
                            $dinner_cost_per_person = $fetch_room_data['dinner_cost_per_person'];
                            $room_rate = number_format($fetch_room_data['room_rate'], 2, '.', '');
                            $extra_bed_rate = number_format($fetch_room_data['extra_bed_rate'], 2, '.', '');
                            $child_without_bed_charges = number_format($fetch_room_data['child_without_bed_charges'], 2, '.', '');
                            $child_with_bed_charges = number_format($fetch_room_data['child_with_bed_charges'], 2, '.', '');
                            $total_breafast_cost = $fetch_room_data['total_breafast_cost'];
                            $total_lunch_cost = $fetch_room_data['total_lunch_cost'];
                            $total_dinner_cost = $fetch_room_data['total_dinner_cost'];
                            $total_room_cost += number_format($fetch_room_data['total_room_cost'], 2, '.', '');
                            $total_food_cost = number_format($total_breafast_cost + $total_lunch_cost + $total_dinner_cost, 2, '.', '');

                            $room_year = date('Y', strtotime($itinerary_route_date));
                            $room_month = date('F', strtotime($itinerary_route_date));
                            $room_formatted_day = 'day_' . date('j', strtotime($itinerary_route_date));



                            if ($total_breafast_cost != 0) :
                                $total_breafastcost = ' B (' . $total_personcount_hotel . ') * ' . $breakfast_cost_per_person . ' = ' . $fetch_room_data['total_breafast_cost'] . ',';
                            else :
                                $total_breafastcost = '-';
                            endif;

                            if ($total_lunch_cost != 0) :
                                $total_lunchcost = ' L (' . $total_personcount_hotel . ') * ' . $lunch_cost_per_person . ' = ' . $fetch_room_data['total_lunch_cost'] . ',';
                            else :
                                $total_lunchcost = '-';
                            endif;

                            if ($total_dinner_cost != 0) :
                                $total_dinnercost =  ' D (' . $total_personcount_hotel . ') * ' . $dinner_cost_per_person . ' = ' . $fetch_room_data['total_dinner_cost'] . '';
                            else :
                                $total_dinnercost = '-';
                            endif;

                            $total_room_rate += $fetch_room_data['room_rate'] + $fetch_room_data['extra_bed_rate'] + $fetch_room_data['child_without_bed_charges'] + $fetch_room_data['child_with_bed_charges'];
                            $total_room_rate_format = number_format($total_room_rate, 2, '.', '');

                            $total_gst_amount = number_format($total_room_gst_amount + $total_amenities_gst_amount + $hotel_breakfast_cost_gst_amount + $hotel_lunch_cost_gst_amount + $hotel_dinner_cost_gst_amount + $total_extra_bed_cost_gst_amount + $total_childwith_bed_cost_gst_amount + $total_childwithout_bed_cost_gst_amount, 2);



                            // $total_room_cost = $total_room_rate_format +  $total_overall_food_cost_format;


                            if ($breakfast == 1) :
                                $breakfast_label = 'Breakfast';
                            else :
                                $breakfast_label = '';
                            endif;
                            if ($lunch == 1) :
                                $lunch_label = ', Lunch';
                            else :
                                $lunch_label = '';
                            endif;
                            if ($dinner == 1) :
                                $dinner_label = ', Dinner';
                            else :
                                $dinner_label = '';
                            endif;

                            $itinerary_plan_hotel_room = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `room_type_id`  FROM `dvi_hotel_rooms` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `status` = 1 AND `deleted` = 0 ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                            $itinerary_plan_hotel_room_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_room);

                            if ($itinerary_plan_hotel_room_count > 0) :
                                while ($row_hotel_room_count = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_room)) :
                                    $room_ID = $row_hotel_room_count['room_ID'];
                                    $hotel_ID = $row_hotel_room_count['hotel_id'];
                                    $room_type_id = $row_hotel_room_count['room_type_id'];
                                    $room_type_name = getROOMTYPE($room_type_id, 'label');
                                    $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_ID, $room_ID, '', 'get_room_gallery_1st_IMG');

                                    $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    $image_path = BASEPATH . '/uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    $default_image = BASEPATH . 'uploads/no-photo.png';

                                    if ($get_room_gallery_1st_IMG):
                                        // Check if the image file exists
                                        $room_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                                    else:
                                        $room_gallery_data = $default_image;
                                    endif;

                                    $room_type_rate = number_format(getROOM_PRICEBOOK_DETAILS($hotel_id, $room_ID, $room_year, $room_month, $room_formatted_day, 'room_rate_for_the_day'), 2, '.', '');

                                    if ($room_type_rate == '') :
                                        $room_type_rate = '0.00';
                                    else :
                                        $room_type_rate = number_format(getROOM_PRICEBOOK_DETAILS($hotel_id, $room_ID, $room_year, $room_month, $room_formatted_day, 'room_rate_for_the_day'), 2, '.', '');
                                    endif;

                                    $tbl_details .= '
                                        <tr>
                                        <td width="40%">
                                        <br/><br/>
                                            <div style="color:#191919;font-size:12px;font-weight:400;line-height:0px;">
                                            <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" width="100%"><b> #' . $room_count . '-' . $room_type_name . '</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        
                                            <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">
                                                        <img data-image="Icon 5" src="' . $room_gallery_data . '" alt="Icon" width="110px" border="0" style="width:110px;height:110px;border:1px solid #fff;display:inline-block !important;">
                                                    </td>
                                                    <td width="85%">
                                                        <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                                        Check In & Out - ' . $room_checkin_timeformat . ' to ' . $room_checkout_timeformat . '
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                                        Max ' . $max_adult . ' Adults | ' . $max_child . ' Children
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                                        Food - ' . $breakfast_label . '' . $lunch_label . '' . $dinner_label . '
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        </td>
                                        <td width="25%" align="left" cellpadding="0" style="font-size:12px;">
                                            <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>Rental Charges</td>
                                            </tr>';
                                    endif;
                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= '<tr>
                                                <td>Extra Bed Charges</td>
                                            </tr> ';
                                    endif;
                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                                <td>Child without Bed Charges
                                                </td>
                                            </tr>';
                                    endif;
                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                                <td>Child with Bed Charges
                                                </td>
                                            </tr>';
                                    endif;
                                    if ($total_food_cost != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>Food Charges <span style="font-size:10px; color:#515151;">(Adult & Children Only)</span></td>
                                            </tr>';
                                    endif;
                                    $tbl_details .= '</table>
                                        </td>
                                        <td width="20%" align="right" style="font-size:12px;">
                                            <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>1 * ' . $room_rate . '</td>
                                            </tr>';
                                    endif;

                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>1 * ' .  $extra_bed_rate . '</td>
                                            </tr> ';
                                    endif;

                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>1 * ' .  $child_without_bed_charges . '</td>
                                            </tr>';
                                    endif;

                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>1 * ' . $child_with_bed_charges . '</td>
                                            </tr>';
                                    endif;

                                    if ($total_food_cost != '0'):
                                        $tbl_details .= ' <tr>
                                                <td>' . $total_breafastcost . '' . $total_lunchcost . '' . $total_dinnercost . '</td>
                                            </tr>';
                                    endif;
                                    $tbl_details .= ' </table>
                                        </td>
                                        <td width="15%" align="right" style="font-size:12px;">
                                        <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= '<tr>
                                            <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $room_rate . '</td>
                                            </tr>';
                                    endif;
                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= ' <tr>
                                            <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $extra_bed_rate . '</td>
                                            </tr> ';
                                    endif;
                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                            <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $child_without_bed_charges . '</td>
                                            </tr>';
                                    endif;
                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                            <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $child_with_bed_charges . '</td>
                                            </tr>';
                                    endif;
                                    if ($total_food_cost != '0'):
                                        $tbl_details .= '<tr>
                                            <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $total_food_cost . '</td>
                                            </tr>';
                                    endif;
                                    $tbl_details .= '</table>
                                        </td>
                                        </tr>
                                    ';
                                endwhile;
                            endif;
                        endwhile;
                        if ($total_room_cost != '0'):
                            $tbl_details .= '
                                    <tr>
                                    <td width="65%" align="right"  style="font-size:14px;">
                                    Total Room Cost
                                    </td>
                                    <td width="35%" align="right"  style="font-size:14px;">
                                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  number_format($total_room_cost, 2) . '
                                    </td>
                                </tr>';
                        endif;
                        if ($total_hotel_meal_plan_cost != '0'):
                            $tbl_details .= '<tr>
                                <td width="65%" align="right" style="font-size:14px;">
                                Total Food Cost
                                </td>
                                <td width="35%" align="right" style="font-size:14px;">
                                <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hotel_meal_plan_cost . '
                                </td>
                                </tr>';
                        endif;
                        if ($total_gst_amount != '0'):
                            $tbl_details .= '<tr>
                                    <td width="65%" align="right" style="font-size:14px;">
                                    Total GST (' . $gst_percentage . '%)
                                    </td>
                                    <td width="35%" align="right" style="font-size:14px;">
                                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $total_gst_amount . '
                                    </td>
                                </tr>';
                        endif;
                        if ($hotel_margin_percentage != '0'):
                            $tbl_details .= '<tr>
                                    <td width="65%" align="right" style="font-size:14px;">
                                    Total Margin (' . $hotel_margin_percentage . '%)
                                    </td>
                                    <td width="35%" align="right" style="font-size:14px;">
                                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotel_margin_rate . '
                                    </td>
                                </tr>';
                        endif;
                        if ($hotel_margin_rate_tax_amt != '0'):
                            $tbl_details .= '<tr>
                                <td width="65%" align="right"  style="font-size:14px;">
                                    Service Tax (' . $hotel_margin_gst_percentage . '%)
                                </td>
                                <td width="35%" align="right"  style="font-size:14px;">
                                <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . $hotel_margin_rate_tax_amt . '
                                </td>
                                </tr>';
                        endif;
                        if ($total_grand != '0'):
                            $tbl_details .= '<tr style="background-color:RGB(0, 0, 128);color:#fff;font-weight:bold;">
                                <td width="65%" align="right" style="font-size:14px;">
                                    Gross Total for Day #' . $hotel_day_count . '
                                </td>
                                <td width="35%" align="right" style="font-size:14px;">
                                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  number_format(round($total_grand), 2) . '
                                </td>
                                </tr>';
                        endif;
                        $tbl_details .= '</table>';
                    endif;

                endwhile;
            endif;


            // -------------------------------------- HOTEL DETAILS --------------------------------------- //

            // -------------------------------------- VEHICLE DETAILS --------------------------------------- //


            $select_itinerary_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `vehicle_type_id`, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1' ORDER BY `itinerary_plan_vendor_eligible_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_vehicle_route_query = sqlNUMOFROW_LABEL($select_itinerary_vehicle_route_query);
            if ($total_itinerary_vehicle_route_query > 0) :
                while ($fetch_itinerary = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_route_query)) :
                    $itinerary_plan_vendor_eligible_ID = $fetch_itinerary['itinerary_plan_vendor_eligible_ID'];
                    $vehicle_type_id = $fetch_itinerary['vehicle_type_id'];
                    $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $total_vehicle_qty = $fetch_itinerary['total_vehicle_qty'];

                    $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
                     <td><span>&nbsp;</span></td>
                 </tr></table>';

                    $tbl_details .=  '<table cellspacing="0" cellpadding="8" border="1">
                <tr>
                <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                    <span style="font-size: 14px; vertical-align: middle;color:#fff;">VEHICLE DETAILS - ' . $vehicle_name . '</span>
                </td>
                </tr></table>';

                    $select_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID' and `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_vehicle_route_query = sqlNUMOFROW_LABEL($select_vehicle_route_query);
                    $vehicle_daycount = 0;
                    if ($total_vehicle_route_query > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_route_query)) :
                            $vehicle_daycount++;
                            $itinerary_route_ID = $fetch_vehicle_data['itinerary_route_ID'];
                            $location_name = $fetch_vehicle_data['location_name'];
                            $vendor_vehicle = getVENDOR_DETAILS($fetch_vehicle_data['vendor_id'], 'label');
                            $itinerary_route_location_from = $fetch_vehicle_data['itinerary_route_location_from'];
                            $itinerary_route_location_to = $fetch_vehicle_data['itinerary_route_location_to'];
                            $itinerary_route_date = $fetch_vehicle_data['itinerary_route_date'];
                            $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                            $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
                            $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                            $vehicle_id = $fetch_vehicle_data['vehicle_id'];
                            $vehicle_extra_km = getVENDORANDVEHICLEDETAILS($fetch_vehicle_data['vehicle_id'], 'get_extra_km_charge');
                            $total_siteseeing_km = number_format($fetch_vehicle_data['total_siteseeing_km'], 2);
                            $total_travelled_km = number_format($fetch_vehicle_data['total_travelled_km'], 2);
                            $vehicle_rental_charges = number_format($fetch_vehicle_data['vehicle_rental_charges'], 2, '.', '');
                            $vehicle_toll_charges = number_format($fetch_vehicle_data['vehicle_toll_charges'], 2, '.', '');
                            $vehicle_parking_charges = number_format($fetch_vehicle_data['vehicle_parking_charges'], 2, '.', '');
                            $vehicle_driver_charges = number_format($fetch_vehicle_data['vehicle_driver_charges'], 2, '.', '');
                            $vehicle_permit_charges = number_format($fetch_vehicle_data['vehicle_permit_charges'], 2, '.', '');
                            $before_6_am_charges_for_driver = number_format($fetch_vehicle_data['before_6_am_charges_for_driver'], 2, '.', '');
                            $before_6_am_charges_for_vehicle = number_format($fetch_vehicle_data['before_6_am_charges_for_vehicle'], 2, '.', '');
                            $after_8_pm_charges_for_driver = number_format($fetch_vehicle_data['after_8_pm_charges_for_driver'], 2, '.', '');
                            $after_8_pm_charges_for_vehicle = number_format($fetch_vehicle_data['after_8_pm_charges_for_vehicle'], 2, '.', '');
                            $total_vehicle_amount = number_format($fetch_vehicle_data['total_vehicle_amount'], 2, '.', '');

                            $get_vehicle_gallery_1st_IMG = getVEHICLE_GALLERY_DETAILS($vehicle_id, 'get_vehicle_gallery_1st_IMG');

                            $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/vehicle_gallery/' . $get_vehicle_gallery_1st_IMG;
                            $image_path = BASEPATH . '/uploads/vehicle_gallery/' . $get_vehicle_gallery_1st_IMG;
                            $default_image = BASEPATH . 'uploads/no-photo.png';

                            if ($get_vehicle_gallery_1st_IMG):
                                // Check if the image file exists
                                $vehicle_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                            else:
                                $vehicle_gallery_data = $default_image;
                            endif;

                            $tbl_details .= '
                             <table cellspacing="0" cellpadding="8" border="1">
                                <tr>
                                    <th colspan="3" align="left" style=" font-weight:bold; font-size: 12px;">
                                        <span style="font-size: 14px; color:#000;">Day ' . $vehicle_daycount . ' - ' . $trip_start_date_and_time . ' | ' . $itinerary_route_location_from . ' ==> ' . $itinerary_route_location_to . '</span>
                                    </th>
                                </tr> 
                                <tr style="background-color:#DDEBF6;color:#333333;">
                                    <th width="35%" align="center" style="font-size:12px;"><br/><br/><b>VEHICLE DETAILS</b><br/></th>
                                    <th width="50%" align="center" style="font-size:12px;"><br/><br/><b>COST DETAILS</b><br/></th>
                                    <th width="15%" align="center" style="font-size:12px;"><br/><br/><b>AMOUNT</b><br/></th>
                                </tr>
                    
                                <tbody>
                                <tr>
                                <td width="35%">
                                <br/><br/>
                                    <div style="color:#191919;font-size:12px;font-weight:400;line-height:0px;">
                                    <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td align="left" width="100%"><b>' . $vendor_vehicle . '</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                
                                    <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td width="15%">
                                                <img data-image="Icon 5" src="' . $vehicle_gallery_data . '"alt="Icon" width="110px" border="0" style="width:110px;height:110px;border:1px solid #fff;display:inline-block !important;">
                                            </td>
                                            <td width="85%">
                                                <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                            Extra KM Charge ' . $vehicle_extra_km . '/Per Km
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                            Site Seeing ' . $total_siteseeing_km . ' KM
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                            Travelling ' . $total_travelled_km . ' KM 
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                                <td width="30%" align="left" cellpadding="0" style="font-size:12px;">
                                    <table cellspacing="0" cellpadding="4">';
                            if ($vehicle_rental_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>Rental Charges</td>
                                    </tr>';
                            endif;
                            if ($vehicle_toll_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>Toll Charges</td>
                                    </tr>';
                            endif;
                            if ($vehicle_parking_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>Parking Charge</td>
                                    </tr>';
                            endif;
                            if ($vehicle_driver_charges != '0'):
                                $tbl_details .= ' <tr>
                                        <td>Driver Charges</td>
                                    </tr>';
                            endif;
                            if ($vehicle_permit_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>Permit Charges</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                        <td>Before 6AM Charges for Driver</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                        <td>Before 6AM Charges for Vendor</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                        <td>After 8PM Charges for Driver</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                        <td>After 8PM Charges for Vendor</td>
                                    </tr>';
                            endif;
                            $tbl_details .= '</table>
                                </td>
                                <td width="20%" align="right" style="font-size:12px;">
                                    <table cellspacing="0" cellpadding="4">';
                            if ($vehicle_rental_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $vehicle_rental_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_toll_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $vehicle_toll_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_parking_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $vehicle_parking_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_driver_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $vehicle_driver_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_permit_charges != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $vehicle_permit_charges . '</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $before_6_am_charges_for_driver . '</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $before_6_am_charges_for_vehicle . '</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $after_8_pm_charges_for_driver . '</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                        <td>1 * ' . $after_8_pm_charges_for_vehicle . '</td>
                                    </tr>';
                            endif;
                            $tbl_details .= '</table>
                                </td>
                                <td width="15%" align="right" style="font-size:12px;">
                                <table cellspacing="0" cellpadding="4">';
                            if ($vehicle_rental_charges != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . $vehicle_rental_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_toll_charges != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_toll_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_parking_charges != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_parking_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_driver_charges != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_driver_charges . '</td>
                                    </tr>';
                            endif;
                            if ($vehicle_permit_charges != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_permit_charges . '</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_driver . '</td>
                                    </tr>';
                            endif;
                            if ($before_6_am_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_vehicle . '</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_driver != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_driver . '</td>
                                    </tr>';
                            endif;
                            if ($after_8_pm_charges_for_vehicle != '0'):
                                $tbl_details .= '<tr>
                                    <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_vehicle . '</td>
                                    </tr>';
                            endif;
                            $tbl_details .= '</table>
                                </td>
                                </tr>
                                <tr style="background-color:RGB(0, 0, 128);color:#fff; font-weight:bold; font-size:14px;">
                                <td width="65%" align="right">
                                Gross Total for the Day #' . $vehicle_daycount . ' [' . $vehicle_name . ']
                                </td>
                                <td width="35%" align="right">
                                <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '
                                </td>
                                </tr>
                                </tbody>
                            </table>';
                        endwhile;
                    endif;


                    $select_itinerary_vehicleoverall_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_itinerary_vehicle_route_query = sqlNUMOFROW_LABEL($select_itinerary_vehicleoverall_route_query);
                    if ($total_itinerary_vehicle_route_query > 0) :
                        while ($fetch_itinerary = sqlFETCHARRAY_LABEL($select_itinerary_vehicleoverall_route_query)) :
                            $itinerary_plan_vendor_eligible_ID = $fetch_itinerary['itinerary_plan_vendor_eligible_ID'];
                            $vehicle_type_id = $fetch_itinerary['vehicle_type_id'];
                            $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                            $total_vehicle_qty = $fetch_itinerary['total_vehicle_qty'];
                            $total_outstation_km = $fetch_itinerary['total_outstation_km'];
                            $outstation_allowed_km_per_day = $fetch_itinerary['outstation_allowed_km_per_day'];
                            $total_allowed_kms = $fetch_itinerary['total_allowed_kms'];
                            $total_extra_kms = $fetch_itinerary['total_extra_kms'];
                            $extra_km_rate = $fetch_itinerary['extra_km_rate'];
                            $total_extra_kms_charge = $fetch_itinerary['total_extra_kms_charge'];
                            $vehicle_total_amount = $fetch_itinerary['vehicle_total_amount'];
                            $vehicle_gst_percentage = $fetch_itinerary['vehicle_gst_percentage'];
                            $vehicle_gst_amount = $fetch_itinerary['vehicle_gst_amount'];
                            $vendor_margin_percentage = $fetch_itinerary['vendor_margin_percentage'];
                            $vendor_margin_gst_type = $fetch_itinerary['vendor_margin_gst_type'];
                            $vendor_margin_gst_percentage = $fetch_itinerary['vendor_margin_gst_percentage'];
                            $vendor_margin_amount = $fetch_itinerary['vendor_margin_amount'];
                            $vendor_margin_gst_amount = $fetch_itinerary['vendor_margin_gst_amount'];
                            $vehicle_grand_total = $fetch_itinerary['vehicle_grand_total'];
                            $grand_total_vehicle = $total_vehicle_qty * $vehicle_grand_total;

                            $tbl_details .= '<table cellspacing="0" cellpadding="10" border="1" style="font-size:12px;">
                                    <tr>
                                        <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                                            <span style="font-size: 14px; vertical-align: middle;color:#fff;">Vehicle Summary Details for Vehicle | ' . $vehicle_name . '</span>
                                        </td>
                                    </tr>
                                    <tr style="background-color:#DDEBF6;color:#333333;">
                                        <td width="14%" align="center" style="font-size:12px;"><br/><br/><b>Date</b><br/></td>
                                        <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>Source</b><br/></td>
                                        <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>Destination</b><br/></td>
                                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Traveling KM</b><br/></td>
                                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Site Seeing KM</b><br/></td>
                                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Total KM</b><br/></td>
                                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Total Amount</b><br/></td>
                                    </tr>';

                            $select_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID' and `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_vehicle_route_query = sqlNUMOFROW_LABEL($select_vehicle_route_query);
                            $vehicle_daycount = 0;
                            if ($total_vehicle_route_query > 0) :
                                while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_route_query)) :
                                    $vehicle_daycount++;
                                    $itinerary_route_ID = $fetch_vehicle_data['itinerary_route_ID'];
                                    $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_data['itinerary_plan_vendor_eligible_ID'];
                                    $location_name = $fetch_vehicle_data['location_name'];
                                    $itinerary_route_location_from = $fetch_vehicle_data['itinerary_route_location_from'];
                                    $itinerary_route_location_to = $fetch_vehicle_data['itinerary_route_location_to'];
                                    $itinerary_route_date = $fetch_vehicle_data['itinerary_route_date'];
                                    $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                                    $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
                                    $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                    $vehicle_extra_km = getVENDORANDVEHICLEDETAILS($fetch_vehicle_data['vehicle_id'], 'get_extra_km_charge');
                                    $total_running_km = number_format($fetch_vehicle_data['total_running_km'], 2);
                                    $total_siteseeing_km = number_format($fetch_vehicle_data['total_siteseeing_km'], 2);
                                    $total_travelled_km = number_format($fetch_vehicle_data['total_travelled_km'], 2);
                                    $total_vehicle_amount = number_format($fetch_vehicle_data['total_vehicle_amount'], 2);
                                    $get_total_outstation_trip = get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip');


                                    $tbl_details .= '<tr style="font-size: 12px;">
                                    <td width="14%">Day ' . $vehicle_daycount . ' - ' . $trip_start_date_and_time . '</td>
                                    <td width="19%">' . $itinerary_route_location_from . '</td>
                                    <td width="19%">' . $itinerary_route_location_to . '</td>
                                    <td width="12%" align="center">' . $total_running_km . ' KM</td>
                                    <td width="12%" align="center">' . $total_siteseeing_km . ' KM</td>
                                    <td width="12%" align="center">' . $total_travelled_km . ' KM</td>
                                    <td width="12%" align="right"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '</td>
                                </tr>';
                                endwhile;
                            endif;

                            $tbl_details .= '<tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Total Used KM </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>' . number_format($total_outstation_km, 0, '.', '') . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                </tr>
                                <tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Total Allowed KM (' . $outstation_allowed_km_per_day . ' * ' . $get_total_outstation_trip . ')</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>' . number_format($total_allowed_kms, 0, '.', '') . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                </tr>
                                <tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Extra KM</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>( ' . number_format($total_extra_kms, 0, '.', '') . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . number_format($extra_km_rate, 2) . ')</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_extra_kms_charge, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Subtotal Vehicle | ' . $vehicle_name . ' </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vehicle_total_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">GST ' . $vehicle_gst_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vehicle_gst_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Vendor Margin ' . $vendor_margin_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vendor_margin_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Vendor Margin Service Tax ' . $vendor_margin_gst_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vendor_margin_gst_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#fff;font-size:14px;background-color:RGB(0, 0, 128);">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Grand Total Vehicle | ' . $vehicle_name . ' </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;">' . $total_vehicle_qty . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . number_format(round($vehicle_grand_total), 2) . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($grand_total_vehicle), 2) . '</span>
                                    </td>
                                </tr>
                        </table>';
                        endwhile;
                    endif;

                endwhile;
            endif;




        // -------------------------------------- VEHICLE DETAILS --------------------------------------- //
        endif;
        // END ITINERARY PREFERENCE BOTH //

        // START ITINERARY PREFERENCE HOTEL //
        if ($itinerary_preference == 1) :

            // -------------------------------------- HOTEL DETAILS --------------------------------------- //
            $tbl_details .=
                '<table cellspacing="0" cellpadding="8" border="1">
                <tr>
                <th colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                    <span style="font-size: 14px; vertical-align: middle;color:#fff;">RECOMMEND HOTEL # ' . $group_type . '</span>
                </th>
                </tr></table>';

            $select_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `itinerary_route_location`, `hotel_required`, `hotel_category_id`, `hotel_id`, `hotel_margin_percentage`, `hotel_margin_gst_type`, `hotel_margin_gst_percentage`, `hotel_margin_rate`, `hotel_margin_rate_tax_amt`, `hotel_breakfast_cost`, `hotel_breakfast_cost_gst_amount`, `hotel_lunch_cost`, `hotel_lunch_cost_gst_amount`, `hotel_dinner_cost`, `hotel_dinner_cost_gst_amount`, `total_no_of_persons`, `total_hotel_meal_plan_cost`, `total_hotel_meal_plan_cost_gst_amount`, `total_extra_bed_cost`, `total_extra_bed_cost_gst_amount`, `total_childwith_bed_cost`, `total_childwith_bed_cost_gst_amount`, `total_childwithout_bed_cost`, `total_childwithout_bed_cost_gst_amount`, `total_no_of_rooms`, `total_room_cost`, `total_room_gst_amount`, `total_hotel_cost`, `total_amenities_cost`, `total_amenities_gst_amount`, `total_hotel_tax_amount` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_ID' and `group_type` = '$group_type' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
            $select_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details);
            if ($select_hotel_details_count > 0) :
                $hotel_day_count = 0;
                while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
                    $hotel_day_count++;
                    $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                    $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                    $total_hotel_cost = $fetch_hotel_data['total_hotel_cost'];
                    $total_hotel_tax_amount = $fetch_hotel_data['total_hotel_tax_amount'];
                    $itinerary_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                    $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                    $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];
                    $hotel_margin_rate_tax_amt = number_format($fetch_hotel_data['hotel_margin_rate_tax_amt'], 2, '.', '');
                    $total_room_gst_amount = number_format($fetch_hotel_data['total_room_gst_amount'], 2, '.', '');
                    $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                    $hotel_name = getHOTEL_DETAIL($fetch_hotel_data['hotel_id'], '', 'label');
                    $hotel_margin_rate = number_format($fetch_hotel_data['hotel_margin_rate'], 2, '.', '');
                    $total_hotel_meal_plan_cost = number_format($fetch_hotel_data['total_hotel_meal_plan_cost'], 2, '.', '');
                    $total_grand_total = $total_hotel_cost + $total_hotel_tax_amount;
                    $total_grand = number_format($total_grand_total, 2, '.', '');
                    $total_amenities_cost = $fetch_hotel_data['total_amenities_cost'];
                    $total_amenities_gst_amount = $fetch_hotel_data['total_amenities_gst_amount'];
                    $total_extra_bed_cost = $fetch_hotel_data['total_extra_bed_cost'];
                    $total_extra_bed_cost_gst_amount = $fetch_hotel_data['total_extra_bed_cost_gst_amount'];
                    $total_childwith_bed_cost = $fetch_hotel_data['total_childwith_bed_cost'];
                    $total_childwith_bed_cost_gst_amount = $fetch_hotel_data['total_childwith_bed_cost_gst_amount'];
                    $total_childwithout_bed_cost = $fetch_hotel_data['total_childwithout_bed_cost'];
                    $total_childwithout_bed_cost_gst_amount = $fetch_hotel_data['total_childwithout_bed_cost_gst_amount'];
                    $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                    $hotel_breakfast_cost_gst_amount = $fetch_hotel_data['hotel_breakfast_cost_gst_amount'];
                    $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                    $hotel_lunch_cost_gst_amount = $fetch_hotel_data['hotel_lunch_cost_gst_amount'];
                    $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                    $hotel_dinner_cost_gst_amount = $fetch_hotel_data['hotel_dinner_cost_gst_amount'];
                    $total_hotel_meal_plan_cost_gst_amount = $fetch_hotel_data['total_hotel_meal_plan_cost_gst_amount'];


                    $tbl_details .= ' <table cellspacing="0" cellpadding="8" border="1">
            <tr>
                <td colspan="3" align="left" width="60%" style="font-weight:bold; font-size: 12px;">
                    <span style="font-size: 14px; color:#000;">Day ' . $hotel_day_count . ' - ' . $itinerary_start_date_and_time . ' |  ' . $itinerary_route_location . '</span>
                </td>
                <td colspan="3" align="right" width="40%" style="font-weight:bold; font-size: 12px;">
                    <span style="font-size: 14px; color:#000;">' . $hotel_name . '</span>
                </td>
            </tr>
            
            <tr style="background-color:#DDEBF6; color:#333333;">
                <th width="40%" align="center" style="font-size:12px;"><br/><br/><b>ROOM DETAILS</b><br/></th>
                <th width="45%" align="center" style="font-size:12px;"><br/><br/><b>COST DETAILS</b><br/></th>
                <th width="15%" align="center" style="font-size:12px;"><br/><br/><b>AMOUNT</b><br/></th>
            </tr>';



                    $select_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_hotel_details_ID` =  '$itinerary_plan_hotel_details_ID' and `itinerary_plan_id` = '$itinerary_plan_ID' and `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                    $select_room_details_count = sqlNUMOFROW_LABEL($select_room_details);
                    $total_room_rate = 0;
                    $total_overall_food_cost = 0;
                    $total_room_cost = 0;
                    if ($select_room_details_count > 0) :
                        while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_room_details)) :
                            $hotel_id = $fetch_room_data['hotel_id'];
                            $room_id = $fetch_room_data['room_id'];
                            $itinerary_plan_hotel_details_ID = $fetch_room_data['itinerary_plan_hotel_details_ID'];
                            $itinerary_route_date = $fetch_room_data['itinerary_route_date'];
                            $gst_percentage = $fetch_room_data['gst_percentage'];
                            $room_type = getROOMTYPE_DETAILS($fetch_room_data['room_id'], 'room_type_title');
                            $room_check_in = getROOM_DETAILS($fetch_room_data['room_id'], 'check_in_time');
                            $room_checkin_timeformat = date('h:i A', strtotime($room_check_in));
                            $room_check_in = getROOM_DETAILS($fetch_room_data['room_id'], 'check_out_time');
                            $room_checkout_timeformat = date('h:i A', strtotime($room_check_in));
                            $max_adult = getROOM_DETAILS($fetch_room_data['room_id'], 'max_adult');
                            $max_child = getROOM_DETAILS($fetch_room_data['room_id'], 'max_child');
                            $breakfast = $fetch_room_data['breakfast_required'];
                            $lunch = $fetch_room_data['lunch_required'];
                            $dinner = $fetch_room_data['dinner_required'];
                            $breakfast_cost_per_person = $fetch_room_data['breakfast_cost_per_person'];
                            $lunch_cost_per_person = $fetch_room_data['lunch_cost_per_person'];
                            $dinner_cost_per_person = $fetch_room_data['dinner_cost_per_person'];
                            $room_rate = number_format($fetch_room_data['room_rate'], 2, '.', '');
                            $extra_bed_rate = number_format($fetch_room_data['extra_bed_rate'], 2, '.', '');
                            $child_without_bed_charges = number_format($fetch_room_data['child_without_bed_charges'], 2, '.', '');
                            $child_with_bed_charges = number_format($fetch_room_data['child_with_bed_charges'], 2, '.', '');
                            $total_breafast_cost = $fetch_room_data['total_breafast_cost'];
                            $total_lunch_cost = $fetch_room_data['total_lunch_cost'];
                            $total_dinner_cost = $fetch_room_data['total_dinner_cost'];
                            $total_room_cost += number_format($fetch_room_data['total_room_cost'], 2, '.', '');
                            $total_food_cost = number_format($total_breafast_cost + $total_lunch_cost + $total_dinner_cost, 2, '.', '');

                            $room_year = date('Y', strtotime($itinerary_route_date));
                            $room_month = date('F', strtotime($itinerary_route_date));
                            $room_formatted_day = 'day_' . date('j', strtotime($itinerary_route_date));



                            if ($total_breafast_cost != 0) :
                                $total_breafastcost = ' B (' . $total_personcount_hotel . ') * ' . $breakfast_cost_per_person . ' = ' . $fetch_room_data['total_breafast_cost'] . ',';
                            else :
                                $total_breafastcost = '-';
                            endif;

                            if ($total_lunch_cost != 0) :
                                $total_lunchcost = ' L (' . $total_personcount_hotel . ') * ' . $lunch_cost_per_person . ' = ' . $fetch_room_data['total_lunch_cost'] . ',';
                            else :
                                $total_lunchcost = '-';
                            endif;

                            if ($total_dinner_cost != 0) :
                                $total_dinnercost =  ' D (' . $total_personcount_hotel . ') * ' . $dinner_cost_per_person . ' = ' . $fetch_room_data['total_dinner_cost'] . '';
                            else :
                                $total_dinnercost = '-';
                            endif;

                            $total_room_rate += $fetch_room_data['room_rate'] + $fetch_room_data['extra_bed_rate'] + $fetch_room_data['child_without_bed_charges'] + $fetch_room_data['child_with_bed_charges'];
                            $total_room_rate_format = number_format($total_room_rate, 2, '.', '');

                            $total_gst_amount = number_format($total_room_gst_amount + $total_amenities_gst_amount + $hotel_breakfast_cost_gst_amount + $hotel_lunch_cost_gst_amount + $hotel_dinner_cost_gst_amount + $total_extra_bed_cost_gst_amount + $total_childwith_bed_cost_gst_amount + $total_childwithout_bed_cost_gst_amount, 2);



                            // $total_room_cost = $total_room_rate_format +  $total_overall_food_cost_format;


                            if ($breakfast == 1) :
                                $breakfast_label = 'Breakfast';
                            else :
                                $breakfast_label = '';
                            endif;
                            if ($lunch == 1) :
                                $lunch_label = ', Lunch';
                            else :
                                $lunch_label = '';
                            endif;
                            if ($dinner == 1) :
                                $dinner_label = ', Dinner';
                            else :
                                $dinner_label = '';
                            endif;


                            $itinerary_plan_hotel_room = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `room_type_id`  FROM `dvi_hotel_rooms` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `status` = 1 AND `deleted` = 0 ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                            $itinerary_plan_hotel_room_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_room);
                            $room_count = 0;
                            if ($itinerary_plan_hotel_room_count > 0) :
                                while ($row_hotel_room_count = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_room)) :
                                    $room_count++;
                                    $room_ID = $row_hotel_room_count['room_ID'];
                                    $hotel_ID = $row_hotel_room_count['hotel_id'];
                                    $room_type_id = $row_hotel_room_count['room_type_id'];
                                    $room_type_name = getROOMTYPE($room_type_id, 'label');
                                    $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_ID, $room_ID, '', 'get_room_gallery_1st_IMG');

                                    $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    $image_path = BASEPATH . '/uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    $default_image = BASEPATH . 'uploads/no-photo.png';

                                    if ($get_room_gallery_1st_IMG):
                                        // Check if the image file exists
                                        $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                                    else:
                                        $image_src = $default_image;
                                    endif;

                                    $room_type_rate = number_format(getROOM_PRICEBOOK_DETAILS($hotel_id, $room_ID, $room_year, $room_month, $room_formatted_day, 'room_rate_for_the_day'), 2, '.', '');

                                    if ($room_type_rate  == '') :
                                        $room_type_rate = '0.00';
                                    else :
                                        $room_type_rate = number_format(getROOM_PRICEBOOK_DETAILS($hotel_id, $room_ID, $room_year, $room_month, $room_formatted_day, 'room_rate_for_the_day'), 2, '.', '');
                                    endif;

                                    $tbl_details .= '
                            <tr>
                            <td width="40%">
                            <br/><br/>
                                <div style="color:#191919;font-size:12px;font-weight:400;line-height:0px;">
                                <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                         <tr>
                                            <td align="left" width="100%"><b> #' . $room_count . '-' . $room_type_name . '</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            
                                <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            <img data-image="Icon 5" src="' . $image_src . '" alt="Icon" width="110px" border="0" style="width:110px;height:110px;border:1px solid #fff;display:inline-block !important;">
                                        </td>
                                        <td width="85%">
                                            <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                            Check In & Out - ' . $room_checkin_timeformat . ' to ' . $room_checkout_timeformat . '
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                            Max ' . $max_adult . ' Adults | ' . $max_child . ' Children
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;" width="100%">
                                                            Food - ' . $breakfast_label . '' . $lunch_label . '' . $dinner_label . '
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>
                            <td width="25%" align="left" cellpadding="0" style="font-size:12px;">
                                <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>Rental Charges</td>
                                </tr>';
                                    endif;
                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= '<tr>
                                    <td>Extra Bed Charges</td>
                                </tr> ';
                                    endif;
                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                    <td>Child without Bed Charges
                                    </td>
                                </tr>';
                                    endif;
                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                    <td>Child with Bed Charges
                                    </td>
                                </tr>';
                                    endif;
                                    if ($total_food_cost != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>Food Charges <span style="font-size:10px; color:#515151;">(Adult & Children Only)</span></td>
                                </tr>';
                                    endif;
                                    $tbl_details .= '</table>
                            </td>
                            <td width="20%" align="right" style="font-size:12px;">
                                <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>1 * ' . $room_rate . '</td>
                                </tr>';
                                    endif;

                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>1 * ' .  $extra_bed_rate . '</td>
                                </tr> ';
                                    endif;

                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>1 * ' .  $child_without_bed_charges . '</td>
                                </tr>';
                                    endif;

                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>1 * ' . $child_with_bed_charges . '</td>
                                </tr>';
                                    endif;

                                    if ($total_food_cost != '0'):
                                        $tbl_details .= ' <tr>
                                    <td>' . $total_breafastcost . '' . $total_lunchcost . '' . $total_dinnercost . '</td>
                                </tr>';
                                    endif;
                                    $tbl_details .= ' </table>
                            </td>
                            <td width="15%" align="right" style="font-size:12px;">
                            <table cellspacing="0" cellpadding="4">';
                                    if ($room_rate != '0'):
                                        $tbl_details .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $room_rate . '</td>
                                </tr>';
                                    endif;
                                    if ($extra_bed_rate != '0'):
                                        $tbl_details .= ' <tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $extra_bed_rate . '</td>
                                </tr> ';
                                    endif;
                                    if ($child_without_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $child_without_bed_charges . '</td>
                                </tr>';
                                    endif;
                                    if ($child_with_bed_charges != '0'):
                                        $tbl_details .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $child_with_bed_charges . '</td>
                                </tr>';
                                    endif;
                                    if ($total_food_cost != '0'):
                                        $tbl_details .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $total_food_cost . '</td>
                                </tr>';
                                    endif;
                                    $tbl_details .= '</table>
                            </td>
                            </tr>
                        ';
                                endwhile;
                            endif;
                        endwhile;
                        if ($total_room_cost != '0'):
                            $tbl_details .= '
                        <tr>
                        <td width="65%" align="right"  style="font-size:14px;">
                        Total Room Cost
                        </td>
                        <td width="35%" align="right"  style="font-size:14px;">
                        <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_room_cost, 2) . '
                        </td>
                    </tr>';
                        endif;
                        if ($total_hotel_meal_plan_cost != '0'):
                            $tbl_details .= '<tr>
                    <td width="65%" align="right" style="font-size:14px;">
                    Total Food Cost
                    </td>
                    <td width="35%" align="right" style="font-size:14px;">
                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hotel_meal_plan_cost . '
                    </td>
                    </tr>';
                        endif;
                        if ($total_gst_amount != '0'):
                            $tbl_details .= '<tr>
                        <td width="65%" align="right" style="font-size:14px;">
                        Total GST (' . $gst_percentage . '%)
                        </td>
                        <td width="35%" align="right" style="font-size:14px;">
                        <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  $total_gst_amount . '
                        </td>
                    </tr>';
                        endif;
                        if ($hotel_margin_percentage != '0'):
                            $tbl_details .= '<tr>
                        <td width="65%" align="right" style="font-size:14px;">
                        Total Margin (' . $hotel_margin_percentage . '%)
                        </td>
                        <td width="35%" align="right" style="font-size:14px;">
                        <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotel_margin_rate . '
                        </td>
                    </tr>';
                        endif;
                        if ($hotel_margin_rate_tax_amt != '0'):
                            $tbl_details .= '<tr>
                    <td width="65%" align="right"  style="font-size:14px;">
                        Service Tax (' . $hotel_margin_gst_percentage . '%)
                    </td>
                    <td width="35%" align="right"  style="font-size:14px;">
                    <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . $hotel_margin_rate_tax_amt . '
                    </td>
                    </tr>';
                        endif;
                        if ($total_grand != '0'):
                            $tbl_details .= '<tr style="background-color:RGB(0, 0, 128);color:#fff;font-weight:bold;">
                    <td width="65%" align="right" style="font-size:14px;">
                        Gross Total for Day #' . $hotel_day_count . '
                    </td>
                    <td width="35%" align="right" style="font-size:14px;">
                        <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' .  number_format(round($total_grand), 2) . '
                    </td>
                    </tr>';
                        endif;
                        $tbl_details .= '</table>';
                    endif;

                endwhile;
            endif;


        // -------------------------------------- HOTEL DETAILS --------------------------------------- //

        endif;
    // END ITINERARY PREFERENCE HOTEL //

    endwhile;
    $pdf->writeHTML($tbl_details, true, false, false, false, '');
endif;

// START ITINERARY PREFERENCE VEHICLE //
if ($itinerary_preference == 2) :


    // -------------------------------------- VEHICLE DETAILS --------------------------------------- //


    $select_itinerary_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `vehicle_type_id`, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $total_itinerary_vehicle_route_query = sqlNUMOFROW_LABEL($select_itinerary_vehicle_route_query);
    if ($total_itinerary_vehicle_route_query > 0) :
        while ($fetch_itinerary = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_route_query)) :
            $itinerary_plan_vendor_eligible_ID = $fetch_itinerary['itinerary_plan_vendor_eligible_ID'];
            $vehicle_type_id = $fetch_itinerary['vehicle_type_id'];
            $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
            $total_vehicle_qty = $fetch_itinerary['total_vehicle_qty'];
            $tbl_vehicledetails .=  '<table cellspacing="0" cellpadding="8" border="1">
        <tr>
        <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
            <span style="font-size: 14px; vertical-align: middle;color:#fff;">VEHICLE DETAILS - ' . $vehicle_name . '</span>
        </td>
        </tr></table>';

            $select_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID' and `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_vehicle_route_query = sqlNUMOFROW_LABEL($select_vehicle_route_query);
            $vehicle_daycount = 0;
            if ($total_vehicle_route_query > 0) :
                while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_route_query)) :
                    $vehicle_daycount++;
                    $itinerary_route_ID = $fetch_vehicle_data['itinerary_route_ID'];
                    $location_name = $fetch_vehicle_data['location_name'];
                    $vendor_vehicle = getVENDOR_DETAILS($fetch_vehicle_data['vendor_id'], 'label');
                    $itinerary_route_location_from = $fetch_vehicle_data['itinerary_route_location_from'];
                    $itinerary_route_location_to = $fetch_vehicle_data['itinerary_route_location_to'];
                    $itinerary_route_date = $fetch_vehicle_data['itinerary_route_date'];
                    $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                    $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
                    $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $vehicle_extra_km = getVENDORANDVEHICLEDETAILS($fetch_vehicle_data['vehicle_id'], 'get_extra_km_charge');
                    $total_siteseeing_km = number_format($fetch_vehicle_data['total_siteseeing_km'], 2);
                    $total_travelled_km = number_format($fetch_vehicle_data['total_travelled_km'], 2);
                    $vehicle_rental_charges = number_format($fetch_vehicle_data['vehicle_rental_charges'], 2, '.', '');
                    $vehicle_toll_charges = number_format($fetch_vehicle_data['vehicle_toll_charges'], 2, '.', '');
                    $vehicle_parking_charges = number_format($fetch_vehicle_data['vehicle_parking_charges'], 2, '.', '');
                    $vehicle_driver_charges = number_format($fetch_vehicle_data['vehicle_driver_charges'], 2, '.', '');
                    $vehicle_permit_charges = number_format($fetch_vehicle_data['vehicle_permit_charges'], 2, '.', '');
                    $before_6_am_charges_for_driver = number_format($fetch_vehicle_data['before_6_am_charges_for_driver'], 2, '.', '');
                    $before_6_am_charges_for_vehicle = number_format($fetch_vehicle_data['before_6_am_charges_for_vehicle'], 2, '.', '');
                    $after_8_pm_charges_for_driver = number_format($fetch_vehicle_data['after_8_pm_charges_for_driver'], 2, '.', '');
                    $after_8_pm_charges_for_vehicle = number_format($fetch_vehicle_data['after_8_pm_charges_for_vehicle'], 2, '.', '');
                    $total_vehicle_amount = number_format($fetch_vehicle_data['total_vehicle_amount'], 2, '.', '');

                    $get_vehicle_gallery_1st_IMG = getVEHICLE_GALLERY_DETAILS($vehicle_id, 'get_vehicle_gallery_1st_IMG');

                    $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/vehicle_gallery/' . $get_vehicle_gallery_1st_IMG;
                    $image_path = BASEPATH . '/uploads/vehicle_gallery/' . $get_vehicle_gallery_1st_IMG;
                    $default_image = BASEPATH . 'uploads/no-photo.png';

                    if ($get_vehicle_gallery_1st_IMG):
                        // Check if the image file exists
                        $vehicle_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                    else:
                        $vehicle_gallery_data = $default_image;
                    endif;


                    $tbl_vehicledetails .= '
                        <table cellspacing="0" cellpadding="8" border="1">
                                <tr>
                                    <th colspan="3" align="left" style=" font-weight:bold; font-size: 12px;">
                                        <span style="font-size: 14px; color:#000;">Day ' . $vehicle_daycount . ' - ' . $trip_start_date_and_time . ' | ' . $itinerary_route_location_from . ' ==> ' . $itinerary_route_location_to . '</span>
                                    </th>
                                </tr> 
                                <tr style="background-color:#DDEBF6;color:#333333;">
                                    <th width="35%" align="center" style="font-size:12px;"><br/><br/><b>VEHICLE DETAILS</b><br/></th>
                                    <th width="50%" align="center" style="font-size:12px;"><br/><br/><b>COST DETAILS</b><br/></th>
                                    <th width="15%" align="center" style="font-size:12px;"><br/><br/><b>AMOUNT</b><br/></th>
                                </tr>
                        
                            <tbody>
                            <tr>
                            <td width="35%">
                            <br/><br/>
                                <div style="color:#191919;font-size:12px;font-weight:400;line-height:0px;">
                                <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td align="left" width="100%"><b>' . $vendor_vehicle . '</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            
                                <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            <img data-image="Icon 5" src="' . $vehicle_gallery_data . '"alt="Icon" width="110px" border="0" style="width:110px;height:110px;border:1px solid #fff;display:inline-block !important;">
                                        </td>
                                        <td width="85%">
                                            <table align="center"  valign="middle" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                        Extra KM Charge ' . $vehicle_extra_km . '/Per Km
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                        Site Seeing ' . $total_siteseeing_km . ' KM
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td data-text="Icon Description" data-font="Primary" align="left" valign="middle" class="center-text" style="color:#595959;font-size: 12px;font-weight: 600;"width="100%">
                                                        Travelling ' . $total_travelled_km . ' KM 
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>
                            <td width="30%" align="left" cellpadding="0" style="font-size:12px;">
                                <table cellspacing="0" cellpadding="4">';
                    if ($vehicle_rental_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Rental Charges</td>
                                </tr>';
                    endif;
                    if ($vehicle_toll_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Toll Charges</td>
                                </tr>';
                    endif;
                    if ($vehicle_parking_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Parking Charge</td>
                                </tr>';
                    endif;
                    if ($vehicle_driver_charges != '0'):
                        $tbl_vehicledetails .= ' <tr>
                                    <td>Driver Charges</td>
                                </tr>';
                    endif;
                    if ($vehicle_permit_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Permit Charges</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Before 6AM Charges for Driver</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>Before 6AM Charges for Vendor</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>After 8PM Charges for Driver</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>After 8PM Charges for Vendor</td>
                                </tr>';
                    endif;
                    $tbl_vehicledetails .= '</table>
                            </td>
                            <td width="20%" align="right" style="font-size:12px;">
                                <table cellspacing="0" cellpadding="4">';
                    if ($vehicle_rental_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $vehicle_rental_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_toll_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $vehicle_toll_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_parking_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $vehicle_parking_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_driver_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $vehicle_driver_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_permit_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $vehicle_permit_charges . '</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $before_6_am_charges_for_driver . '</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $before_6_am_charges_for_vehicle . '</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $after_8_pm_charges_for_driver . '</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                    <td>1 * ' . $after_8_pm_charges_for_vehicle . '</td>
                                </tr>';
                    endif;
                    $tbl_vehicledetails .= '</table>
                            </td>
                            <td width="15%" align="right" style="font-size:12px;">
                            <table cellspacing="0" cellpadding="4">';
                    if ($vehicle_rental_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . $vehicle_rental_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_toll_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_toll_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_parking_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_parking_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_driver_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_driver_charges . '</td>
                                </tr>';
                    endif;
                    if ($vehicle_permit_charges != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_permit_charges . '</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_driver . '</td>
                                </tr>';
                    endif;
                    if ($before_6_am_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_vehicle . '</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_driver != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_driver . '</td>
                                </tr>';
                    endif;
                    if ($after_8_pm_charges_for_vehicle != '0'):
                        $tbl_vehicledetails .= '<tr>
                                <td><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_vehicle . '</td>
                                </tr>';
                    endif;
                    $tbl_vehicledetails .= '</table>
                            </td>
                            </tr>
                            <tr style="background-color:RGB(0, 0, 128);color:#fff; font-weight:bold; font-size:14px;">
                            <td width="65%" align="right">
                            Gross Total for the Day #' . $vehicle_daycount . ' [' . $vehicle_name . ']
                            </td>
                            <td width="35%" align="right">
                            <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '
                            </td>
                            </tr>
                            </tbody>
                        </table>';
                endwhile;
            endif;

            $select_itinerary_vehicleoverall_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_vehicle_route_query = sqlNUMOFROW_LABEL($select_itinerary_vehicleoverall_route_query);
            if ($total_itinerary_vehicle_route_query > 0) :
                while ($fetch_itinerary = sqlFETCHARRAY_LABEL($select_itinerary_vehicleoverall_route_query)) :
                    $itinerary_plan_vendor_eligible_ID = $fetch_itinerary['itinerary_plan_vendor_eligible_ID'];
                    $vehicle_type_id = $fetch_itinerary['vehicle_type_id'];
                    $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $total_vehicle_qty = $fetch_itinerary['total_vehicle_qty'];
                    $total_outstation_km = $fetch_itinerary['total_outstation_km'];
                    $outstation_allowed_km_per_day = $fetch_itinerary['outstation_allowed_km_per_day'];
                    $total_allowed_kms = $fetch_itinerary['total_allowed_kms'];
                    $total_extra_kms = $fetch_itinerary['total_extra_kms'];
                    $extra_km_rate = $fetch_itinerary['extra_km_rate'];
                    $total_extra_kms_charge = $fetch_itinerary['total_extra_kms_charge'];
                    $vehicle_total_amount = $fetch_itinerary['vehicle_total_amount'];
                    $vehicle_gst_percentage = $fetch_itinerary['vehicle_gst_percentage'];
                    $vehicle_gst_amount = $fetch_itinerary['vehicle_gst_amount'];
                    $vendor_margin_percentage = $fetch_itinerary['vendor_margin_percentage'];
                    $vendor_margin_gst_type = $fetch_itinerary['vendor_margin_gst_type'];
                    $vendor_margin_gst_percentage = $fetch_itinerary['vendor_margin_gst_percentage'];
                    $vendor_margin_amount = $fetch_itinerary['vendor_margin_amount'];
                    $vendor_margin_gst_amount = $fetch_itinerary['vendor_margin_gst_amount'];
                    $vehicle_grand_total = $fetch_itinerary['vehicle_grand_total'];
                    $grand_total_vehicle = $total_vehicle_qty * $vehicle_grand_total;

                    $tbl_vehicledetails .= '<table cellspacing="0" cellpadding="10" border="1" style="font-size:12px;">
                    <tr>
                        <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
                            <span style="font-size: 14px; vertical-align: middle;color:#fff;">Vehicle Summary Details for Vehicle | ' . $vehicle_name . '</span>
                        </td>
                    </tr>
                    <tr style="background-color:#DDEBF6;color:#333333;">
                        <td width="14%" align="center" style="font-size:12px;"><br/><br/><b>Date</b><br/></td>
                        <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>Source</b><br/></td>
                        <td width="19%" align="center" style="font-size:12px;"><br/><br/><b>Destination</b><br/></td>
                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Traveling KM</b><br/></td>
                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Site Seeing KM</b><br/></td>
                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Total KM</b><br/></td>
                        <td width="12%" align="center" style="font-size:12px;"><br/><br/><b>Total Amount</b><br/></td>
                    </tr>';

                    $select_vehicle_route_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_plan_vendor_eligible_ID` = ' $itinerary_plan_vendor_eligible_ID' and `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_vehicle_route_query = sqlNUMOFROW_LABEL($select_vehicle_route_query);
                    $vehicle_daycount = 0;
                    if ($total_vehicle_route_query > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_route_query)) :
                            $vehicle_daycount++;
                            $itinerary_route_ID = $fetch_vehicle_data['itinerary_route_ID'];
                            $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_data['itinerary_plan_vendor_eligible_ID'];
                            $location_name = $fetch_vehicle_data['location_name'];
                            $itinerary_route_location_from = $fetch_vehicle_data['itinerary_route_location_from'];
                            $itinerary_route_location_to = $fetch_vehicle_data['itinerary_route_location_to'];
                            $itinerary_route_date = $fetch_vehicle_data['itinerary_route_date'];
                            $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));
                            $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
                            $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                            $vehicle_extra_km = getVENDORANDVEHICLEDETAILS($fetch_vehicle_data['vehicle_id'], 'get_extra_km_charge');
                            $total_running_km = number_format($fetch_vehicle_data['total_running_km'], 2);
                            $total_siteseeing_km = number_format($fetch_vehicle_data['total_siteseeing_km'], 2);
                            $total_travelled_km = number_format($fetch_vehicle_data['total_travelled_km'], 2);
                            $total_vehicle_amount = number_format($fetch_vehicle_data['total_vehicle_amount'], 2);
                            $get_total_outstation_trip = get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip');


                            $tbl_vehicledetails .= '<tr style="font-size: 12px;">
                                    <td width="14%">Day ' . $vehicle_daycount . ' - ' . $trip_start_date_and_time . '</td>
                                    <td width="19%">' . $itinerary_route_location_from . '</td>
                                    <td width="19%">' . $itinerary_route_location_to . '</td>
                                    <td width="12%" align="center">' . $total_running_km . ' KM</td>
                                    <td width="12%" align="center">' . $total_siteseeing_km . ' KM</td>
                                    <td width="12%" align="center">' . $total_travelled_km . ' KM</td>
                                    <td width="12%" align="right"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '</td>
                                </tr>';
                        endwhile;
                    endif;

                    $tbl_vehicledetails .= '<tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Total Used KM </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>' . number_format($total_outstation_km, 0, '.', '') . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                </tr>
                                <tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Total Allowed KM (' . $outstation_allowed_km_per_day . ' * ' . $get_total_outstation_trip . ')</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>' . number_format($total_allowed_kms, 0, '.', '') . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                </tr>
                                <tr style="color:#333; font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Extra KM</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span>( ' . number_format($total_extra_kms, 0, '.', '') . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . number_format($extra_km_rate, 2) . ')</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($total_extra_kms_charge, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span>Subtotal Vehicle | ' . $vehicle_name . ' </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vehicle_total_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">GST ' . $vehicle_gst_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vehicle_gst_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Vendor Margin ' . $vendor_margin_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vendor_margin_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#333;font-size: 14px;">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Vendor Margin Service Tax ' . $vendor_margin_gst_percentage . '% - Vehicle | ' . $vehicle_name . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"></span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($vendor_margin_gst_amount, 2) . '</span>
                                    </td>
                                </tr>
                                <tr style="color:#fff;font-size:14px;background-color:RGB(0, 0, 128);">
                                    <td colspan="4" align="right" width="62%">
                                        <span style="font-weight: bold;">Grand Total Vehicle | ' . $vehicle_name . ' </span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;">' . $total_vehicle_qty . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>' . number_format(round($vehicle_grand_total), 2) . '</span>
                                    </td>
                                    <td align="right" width="19%">
                                        <span style="font-weight: bold;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round($grand_total_vehicle), 2) . '</span>
                                    </td>
                                </tr>
                        </table>';
                endwhile;
            endif;
        endwhile;
    endif;




// -------------------------------------- VEHICLE DETAILS --------------------------------------- //

endif;
// END ITINERARY PREFERENCE VEHICLE //

$pdf->writeHTML($tbl_vehicledetails, true, false, false, false, '');


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


$select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details_query);
if ($total_itinerary_route_details_count > 0) :
    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query)) :
        $hotspot_daycount++;
        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
        $location_name = $fetch_itinerary_route_data['location_name'];
        $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];
        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
        $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));

        $select_itinerary_routehotspot_details_query = sqlQUERY_LABEL("SELECT `hotspot_amout` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '4'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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

            // echo "SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'";
            // exit;
            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
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

        $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_duration`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time` FROM `dvi_confirmed_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_HOTSPOT.`item_type` = '4';") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
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


                // echo $hotspot_gallery;
                // exit;


                if ($nationality_hotspot == 101) :
                    $hotspot_person = 'Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_adult_entry_cost . ', Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_child_entry_cost . ',';
                    $hotspot_person_infant =  'Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_infant_entry_cost . '';
                else :
                    $hotspot_person = 'Foreign Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_adult_entry_cost . ',Foreign Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_child_entry_cost . ',';
                    $hotspot_person_infant =  'Foreign Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_infant_entry_cost . '';
                endif;


                if ($hotspot_gallery) {
                    $hotspot_gallery_data = 'uploads/hotspot_gallery/' . $hotspot_gallery;
                } else {
                    $hotspot_gallery_data = 'assets/img/no-image-found.png';
                }

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


                $select_itinerary_plan_activity = sqlQUERY_LABEL("SELECT `route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `activity_ID`, `activity_order`, `activity_charges_for_foreign_adult`, `activity_charges_for_foreign_children`, `activity_charges_for_foreign_infant`, `activity_charges_for_adult`, `activity_charges_for_children`, `activity_charges_for_infant`, `activity_amout`, `activity_traveling_time`, `activity_start_time`, `activity_end_time` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' and `route_hotspot_ID` = '$route_hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
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

                        if ($activity_gallery  != '') :
                            $activity_gallery_data = 'uploads/activity_gallery/' . $activity_gallery . '';
                        else :
                            $activity_gallery_data = 'assets/img/no-image-found.png';
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
                $tbl_hotspot .= '  </table>
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


// -------------------------------------- COMPLETE DETAILS --------------------------------------- //

$tbl_complete_summary .=
    '<table cellspacing="0" cellpadding="8" border="1">
 <tr>
     <td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:#dc3545;">
         <span style="font-size: 14px; vertical-align: middle;color:#fff;">COMPLETE SUMMARY</span>
     </td>
 </tr></table>';
$total_hotspot_charge = number_format(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount'), 2);
$total_activity_charge = number_format(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout'), 2);
$total_guide_charge = number_format(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount'), 2);
$total_hotel_charge = number_format(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount'), 2);
$total_vehicle_charge = number_format(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount'), 2);

$tbl_complete_summary .=
    '<table cellspacing="0" cellpadding="8" border="1">';
if ($total_hotspot_charge != '0'):
    $tbl_complete_summary .= '<tr style="font-size: 14px; font-weight:bold;">
             <td width="50%">
                 Total Hotspot Charges
             </td>
             <td align="right" width="50%">
                 <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hotspot_charge . '
             </td>
         </tr>';
endif;
if ($total_activity_charge != '0'):
    $tbl_complete_summary .= '<tr style="font-size: 14px; font-weight:bold;">
             <td width="50%">
                 Total Activity Charges
             </td>
             <td align="right" width="50%">
                 <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_activity_charge . '
             </td>
         </tr>';
endif;
if ($total_guide_charge != '0'):
    $tbl_complete_summary .= '<tr style="font-size: 14px; font-weight:bold;">
             <td width="50%">
             Total Guide Charges
             </td>
             <td align="right" width="50%">
                 <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_guide_charge . '
             </td>
         </tr>';
endif;
if ($total_hotel_charge != '0'):
    $tbl_complete_summary .= '<tr style="font-size: 14px; font-weight:bold;">
             <td width="50%">
             Total Hotel Charges
             </td>
             <td align="right" width="50%">
                 <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hotel_charge . '
             </td>
         </tr>';
endif;
if ($total_vehicle_charge != '0'):
    $tbl_complete_summary .= '<tr style="font-size: 14px; font-weight:bold;">
             <td width="50%">
             Total Vehicle
             </td>
             <td align="right" width="50%">
                 <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_charge . '
             </td>
         </tr>';
endif;
$tbl_complete_summary .= '</table>';
$tbl_complete_summary .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
<td><span>&nbsp;</span></td>
</tr></table>';

$tbl_complete_summary .= '<table  cellspacing="0" cellpadding="8" border="1"><tr><td colspan="3" align="center" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
 <span style="font-size: 14px; vertical-align: middle;color:#fff;">Over All Package Cost: <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format(round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount_pdf')), 2) . '</span>
 </td></tr></table>';


// -------------------------------------- COMPLETE DETAILS --------------------------------------- //
$tbl_complete_summary .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
                     <td><span>&nbsp;</span></td>
                 </tr></table>';

$pdf->writeHTML($tbl_complete_summary, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('travel_itinerary - ' . $itinerary_quote_ID . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+