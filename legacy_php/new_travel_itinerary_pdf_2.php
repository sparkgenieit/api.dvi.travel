<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

$itinerary_plan_ID = $_GET['id'];

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID`, `nationality` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
        $nationality_hotspot = $fetch_itinerary_plan_data['nationality'];
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
        $logoPath = 'http://localhost/dvi_travels/head/assets/img/logo.png';
        $logoWidth = 23;
        $this->Image($logoPath, 2, 5, $logoWidth, '', '', '', 'C');

        $this->SetFont('helvetica', 'B', 20);
        $this->SetFont('helvetica', 'BI', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(80, 3, "DoView Holidays India Pvt. Ltd.", 0, 'R', false, 1, 124, 10);

        $this->SetFont('helvetica', 'BI', 10);
        $this->SetTextColor(0, 0, 128);
        $this->MultiCell(60, 3, "Quote_ID - " . $itinerary_quote_ID, 0, 'R', false, 1, 144, 16);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 8);
        $this->MultiCell(60, 3, "Customer Care 9047776899", 0, 'R', false, 1, 144, 22);
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
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('ITINERARY PDF - ' . $itinerary_quote_ID . '');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' Sample TCF PDF', PDF_HEADER_STRING);
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
    endif;

endif;


// -------------------------------------- HOTSPOT DETAILS --------------------------------------- //

$pdf->writeHTML(
    '<table cellspacing="0" cellpadding="6" border="1">
        <tr>
            <td colspan="3" align="center" style="font-weight:bold; font-size: 12px;background-color:#dc3545;">
                <span style="font-size: 14px; vertical-align: middle;color:#fff;">HOTSPOT DETAILS</span>
            </td>
        </tr>
    </table>',
    true,
    false,
    false,
    false,
    ''
);

$select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `next_visiting_location`, `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

$total_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details_query);
if ($total_itinerary_route_details_count > 0) :
    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query)) :
        $hotspot_daycount++;
        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
        $location_name = $fetch_itinerary_route_data['location_name'];
        $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];
        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
        $trip_start_date_and_time = date('M d, Y (l)', strtotime($itinerary_route_date));

        $tbl_hotspot .= '
            <table cellspacing="0" cellpadding="6" border="1">
                <tr style="font-size:12px;font-weight:bold;">
                    <td colspan="4" align="left">Day ' . $hotspot_daycount . ' - ' . $trip_start_date_and_time . ' | ' . $location_name . ' ==> ' . $next_visiting_location . '</td>
                </tr>
                <tr style="background-color:#DDEBF6;color:#333333;">
                    <td width="18%" align="center" style="font-size:12px;"><b>TIMING</b></td>
                    <td width="12%" align="center" style="font-size:12px;"><b>DURATION</b></td>
                    <td width="53%" align="center" style="font-size:12px;"><b>HOTSPOT PLACES</b></td>
                    <td width="17%" align="center" style="font-size:12px;"><b>COST</b></td>
                </tr>
                <tr style="font-size:12px;color: #333333;font-weight:normal;background-color:#ffe7d1;">
                    <td colspan="4" align="left">
                        Guide Slot - 9:00 AM to 1:00 PM - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span>200
                    </td>
                </tr>';

        $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_adult_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_child_entry_cost`, ROUTE_HOTSPOT.`hotspot_foreign_infant_entry_cost`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_duration` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_HOTSPOT.`item_type` = '4'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

        $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);

        if ($total_itinerary_plan_route_hotspot_details_count > 0) :
            while ($fetch_itinerary_plan_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_details_query)) :
                $hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['hotspot_ID'];
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
                $hotspot_name = $fetch_itinerary_plan_route_hotspot_data['hotspot_name'];
                $hotspot_duration = $fetch_itinerary_plan_route_hotspot_data['hotspot_duration'];
                $hotspot_duration_duration = formatTimeDuration($hotspot_duration);
                $hotspot_amout = $fetch_itinerary_plan_route_hotspot_data['hotspot_amout'];
                $hotspot_amout_format = number_format($hotspot_amout, 2, '.', '');

                $total_hostpot_amount += $hotspot_amout_format;
                $total_hostpot_amount_format = number_format($total_hostpot_amount, 2, '.', '');

                $hotspot_gallery = getHOTSPOT_GALLERY_DETAILS($fetch_itinerary_plan_route_hotspot_data['hotspot_ID'], 'hotspot_gallery_name');

                if ($nationality_hotspot == 101) :
                    $hotspot_person = 'Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_adult_entry_cost . ', Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_child_entry_cost . ',';
                    $hotspot_person_infant = 'Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_infant_entry_cost . '';
                else :
                    $hotspot_person = 'Foreign Adult (' . $total_adult . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_adult_entry_cost . ',Foreign Child (' . $total_children . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_child_entry_cost . ',';
                    $hotspot_person_infant = 'Foreign Infant (' . $total_infants . ') - <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_foreign_infant_entry_cost . '';
                endif;

                if ($hotspot_gallery != '') {
                    $hotspot_gallery_data = 'uploads/hotspot_gallery/' . $hotspot_gallery . '';
                } else {
                    $hotspot_gallery_data = 'head/assets/img/no-image-found.png';
                }

                $tbl_hotspot .= '
                    <tr style="font-size:12px;">
                        <td width="18%">' . $hotspot_start_time_format . ' - ' . $hotspot_end_time_format . '</td>
                        <td width="12%">' . $hotspot_traveling_time_duration . '</td>
                        <td width="53%">
                            <table cellspacing="0" cellpadding="4" border="0">
                                <tr>
                                    <td width="30%"><img src="' . $hotspot_gallery_data . '" alt="" border="0" width="50" height="40" /></td>
                                    <td width="70%" align="left">
                                        <b>' . $hotspot_name . '</b><br>
                                        <span style="font-size:10px;">' . $hotspot_duration_duration . '</span><br>
                                        <span style="font-size:10px;color:#595959;">' . $hotspot_person . '</span><br>
                                        <span style="font-size:10px;color:#595959;">' . $hotspot_person_infant . '</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="17%" align="right"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $hotspot_amout_format . '</td>
                    </tr>';
            endwhile;
        endif;

        $tbl_hotspot .= '
            <tr style="font-size:12px; font-weight:bold;">
                <td colspan="3" align="right">Total Cost</td>
                <td align="right"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_hostpot_amount_format . '</td>
            </tr>
        </table>';
    endwhile;

    $pdf->writeHTML($tbl_hotspot, true, false, false, false, '');
endif;

$pdf->Output('itinerary_' . $itinerary_plan_ID . '.pdf', 'I');
?>

