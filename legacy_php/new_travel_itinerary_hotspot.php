<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

$itinerary_plan_ID = $_GET['id'];
$recommended1 = $_GET['recommended1'];
$recommended2 = $_GET['recommended2'];
$recommended3 = $_GET['recommended3'];
$recommended4 = $_GET['recommended4'];

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID`, `nationality`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
        $total_pax_count = $total_adult + $total_children + $total_infants;
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


// -------------------------------------- HOTSPOT DETAILS --------------------------------------- //

$pdf->writeHTML(
    '<table cellspacing="0" cellpadding="8" border="1">
   <tr>
    <td colspan="3" align="center" style="font-weight:bold; font-size: 12px;background-color:#dc3545;">
        <span style="font-size: 14px; vertical-align: middle;color:#fff;">Tour Itinerary Plan</span>
    </td>
  </tr></table>',
    true,
    false,
    false,
    false,
    ''
);

$tbl_hotspot .= '
         <table cellspacing="0" cellpadding="10" border="1">
            <tr style="font-size:14px;font-weight:bold; border-right:0px;">
              <td colspan="8" align="left" style="border-right:none;"><span style="color:#4d287b;">#DVI20241114</span> <span>&nbsp;</span>   Nov 26, 2024 to Nov 30, 2024 (4 N, 5 D)</td>
              <td colspan="4" align="right"  style="font-weight:normal;">Adult - 1, Infant - 2, Child - 2</td>
            </tr>
            <tr style="font-size:14px;font-weight:bold;">
              <td colspan="8" align="left" style="font-weight:normal;">Room Count - 1, Extra Bed - 0, Child withbed - 0, Child withoutbed - 0</td>
              <td colspan="4" align="right">Overall Trip Cost : <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> 55,923.00</td>
            </tr>
           </table>';
$tbl_hotspot .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
                     <td><span>&nbsp;</span></td>
                 </tr></table>';


$select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
$last_destination_city = NULL;
$show_day_trip_available = false;

if ($total_itinerary_plan_route_details_count > 0) :
    // $itineary_route_count = 0;
    while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
        $itineary_route_count++;
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

        if (($arrival_location == $location_name && $itineary_route_count == 1) || ($total_itinerary_plan_route_details_count == $itineary_route_count)) :
            $start_day_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
            $start_day_time_add_attr = "readonly";
        else :
            $start_day_time_add_class = "form-control w-px-100 py-1 start-time-input text-center flatpickr-input";
            $start_day_time_add_attr = "";
        endif;

        if ($departure_location == $next_visiting_location && $no_of_days == $itineary_route_count) :
            $day_end_time_add_class = "form-control-plaintext text-primary fw-bolder w-px-75 text-center";
            $day_end_time_add_attr = "readonly";
        else :
            $day_end_time_add_class = "form-control w-px-100 py-1 end-time-input text-center flatpickr-input";
            $day_end_time_add_attr = "";
        endif;


        $tbl_hotspot .=
            '<table cellspacing="0" cellpadding="8" border="1" style="line-height:1.5;">
 <tr>
     <td colspan="4" align="left" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
         <span style="font-size: 14px; vertical-align: middle;color:#fff;">DAY ' . $itineary_route_count . ' - ' . date('D, M d, Y', strtotime($itinerary_route_date)) . ' </span>
     </td>
     <td colspan="8" align="left" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
         <span style="font-size: 14px; vertical-align: middle;color:#fff;">' . $location_name . ' => ' . $next_visiting_location . ' </span>
     </td>
     <td colspan="2" align="left" style=" font-weight:bold; font-size: 12px;background-color:RGB(0, 0, 128);">
         <span style="font-size: 14px; vertical-align: middle;color:#fff;">' . number_format(get_ASSIGNED_VEHICLE_ITINEARY_PLAN_DAYWISE_KM_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_total_kms'), 2) . ' KM</span>
     </td>
 </tr>';
        if ($location_description):
            $tbl_hotspot .=
                '<tr style="font-size:13px;background-color:#f1f9ff; color:#333333;">
     <td colspan="14" align="left"><b>About Location</b> - <span>' . $location_description . '</span></td>
</tr>';
        endif;
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
                $tbl_hotspot .= '<tr style="font-size:13px; background-color:#ffeded; color:#333333;">
                                                                                                <td colspan="11" align="left"><b>Guide Language</b> - <span>' . getGUIDE_LANGUAGE_DETAILS($guide_language, 'label') . '</span>,<span>&nbsp;</span> <b>Slot Timing</b> - ' . getSLOTTYPE($guide_slot, 'label') . '</td>
                                                                                                <td colspan="3" align="right"> <b><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> 1,500.00</b></td></tr>';
            endif;
        endif;

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
        // Initialize a variable to store the previous hotspot name

        if ($total_itinerary_plan_route_hotspot_details_count > 0) :


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
                $hotspot_gallery = getHOTSPOT_GALLERY_DETAILS($fetch_itinerary_plan_route_hotspot_data['hotspot_ID'], 'hotspot_gallery_name');

                $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/hotspot_gallery/' . $hotspot_gallery;
                $image_path = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery;
                $default_image = BASEPATH . 'uploads/no-photo.png';

                if ($hotspot_gallery):
                    // Check if the image file exists
                    $hotspot_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                else:
                    $hotspot_gallery_data = $default_image;
                endif;

                if ($item_type == 1) :

                    if ($last_day_ending_location == NULL) :
                        $last_day_ending_location =  $next_visiting_location;
                    endif;
                    if ($show_day_trip_available) :
                        $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;">
                                                                                                   <td colspan="14" align="left">Day Trip is available</td>
                                                                                            </tr>';
                    endif;
                    if ($arrival_location == $location_name && $itineary_route_count == 1):
                        $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;">
                                                                                            <td colspan="14" align="left">' . getGLOBALSETTING('itinerary_break_time') . ' <span>&nbsp;</span> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . '</td>
                                                                                             </tr>';
                    endif;
                endif;

                if ($item_type == 2) :
                    $tbl_hotspot .= '<tr style="font-size:13px;background-color:#f1f9ff; color:#333333; ">
                                                                                        <td colspan="14" align="left">Travelling from <b>' . $location_name . '</b> to <b>' . getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location') . '</b> <br><b><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . $hotspot_travelling_distance . 'KM - ' . formatTimeDuration($hotspot_traveling_time) . ' (This may vary due to traffic conditions)</span></b></td>
                                                                                       </tr>';
                endif;

                if ($item_type == 3) :
                    $from_hotspot_name = $previous_hotspot_name; // Store the "from" hotspot name
                    $to_hotspot_name = $hotspot_name; // Store the "to" hotspot name

                    if ($allow_break_hours == 1):
                        $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;background-color:#f1f9ff; color:#333333; ">
                                                                                            <td colspan="14" align="left">Expect a waiting time of approximately <b> ' . formatTimeDuration($hotspot_traveling_time) . '</b> at this location (<b> ' . $to_hotspot_name . ' </b>) <br><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . formatTimeDuration($hotspot_traveling_time) . ' </span></td>
                                                                                           </tr>';
                    elseif ($allow_via_route == 1):
                        $to_hotspot_name = $via_location_name;

                        $tbl_hotspot .= '<tr style="font-size:13px;background-color:#f1f9ff; color:#333333; ">
                                                                                            <td colspan="14" align="left">Travelling from <b>' . $from_hotspot_name . '</b> to <b>' . $to_hotspot_name . '</b> <br><b><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . $hotspot_travelling_distance . 'KM - ' . formatTimeDuration($hotspot_traveling_time) . ' (This may vary due to traffic conditions)</span></b></td>
                                                                                           </tr>';
                        $previous_hotspot_name = $via_location_name;
                    else:
                        $tbl_hotspot .= '<tr style="font-size:13px;background-color:#f1f9ff; color:#333333; ">
                                                                                            <td colspan="14" align="left">Travelling from <b>' . $from_hotspot_name . '</b> to <b>' . $to_hotspot_name . '</b> <br><b><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . $hotspot_travelling_distance . 'KM - ' . formatTimeDuration($hotspot_traveling_time) . ' (This may vary due to traffic conditions)</span></b></td>
                                                                                           </tr>';
                    endif;
                endif;

                if ($item_type == 4 && $hotspot_ID != 0) :
                    $previous_hotspot_name = $hotspot_name;
                    if ($hotspot_amout > 0):
                        $get_hotspot_amount = '<span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($hotspot_amout, 2) . '';
                    endif;

                    $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;background-color: #fcf6ff; color:#333333; ">
                                                                                        <td colspan="12" align="left">' . $hotspot_name . '<br><span style="font-size:12px;  font-weight:normal;text-align:justify;">' . $hotspot_description . '</span> <br><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . formatTimeDuration($hotspot_traveling_time) . ' <span>&nbsp;</span> <span style="color:#333333;">' . $get_hotspot_amount . '</span></span>
                                                                                            </td>
                                                                                         <td colspan="2" align="center"><img src="' . $hotspot_gallery_data . '" alt="Icon" width="80px" border="0"></td></tr>';

                    $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                    $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                    if ($total_hotspot_activity_num_rows_count > 0) :
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
                            $activity_description = $fetch_hotspot_activity_data['activity_description'];
                            $activity_gallery = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');

                            $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/activity_gallery/' . $activity_gallery;
                            $image_path = BASEPATH . '/uploads/activity_gallery/' . $activity_gallery;
                            $default_image = BASEPATH . 'uploads/no-photo.png';

                            if ($activity_gallery):
                                // Check if the image file exists
                                $activity_gallery_data = file_exists($image_already_exist) ? $image_path : $default_image;
                            else:
                                $activity_gallery_data = $default_image;
                            endif;


                            if ($activity_amout > 0):
                                $get_activity_amount = '<span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . number_format($activity_amout, 2) . '';
                            endif;

                            $tbl_hotspot .= ' <tr style="font-size:13px;font-weight:bold; color:#333333;">
                                                                                                        <td colspan="12" align="left">Activity #' . $activitycount . ' - ' . $activity_title . ' <br><span style="font-size:12px; font-weight:normal;text-align:justify;">' . $activity_description . '</span> <br><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($activity_start_time)) . ' - ' . date('h:i A', strtotime($activity_end_time)) . ' - ' . formatTimeDuration($activity_traveling_time) . ' <span>&nbsp;</span> <span style="color:#333333;">' . $get_activity_amount . '</span></span></td>
                                                                                                         <td colspan="2" align="center"><img src="' . $activity_gallery_data . '" alt="Activity Img" width="80px" border="0"></td></tr>';
                        endwhile;
                    endif;
                endif;
                if ($item_type == 5) :
                    $tbl_hotspot .= '<tr style="font-size:13px;background-color:#f1f9ff; color:#333333; ">
                                                                                        <td colspan="14" align="left">Travelling from <b>' . $previous_hotspot_name . '</b> to <b>' . getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location') . '</b> <br><b><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' - ' . $hotspot_travelling_distance . 'KM - ' . formatTimeDuration($hotspot_traveling_time) . ' (This may vary due to traffic conditions)</span></b></td>
                                                                                       </tr>';
                endif;
                if ($item_type == 6) :
                    $get_hotel_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
                    $get_hotel_address = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'hotel_address');
                    if ($get_hotel_title) :
                        $get_hotel_name = $get_hotel_title;
                    else :
                        $get_hotel_name = 'N/A';
                    endif;
                    $get_hotel_name = getGLOBALSETTING('itinerary_hotel_return');
                    $get_hotel_address = 'N/A';
                    $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;">
                                                                                        <td colspan="14" align="left">' . $get_hotel_name . ' <span>&nbsp;</span><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' </span><span>&nbsp;</span><b>Address: <span style="font-size:12px; color:#616161;"></b>' . $get_hotel_address . '</span> </td>
                                                                                        </tr>';
                endif;
                if ($item_type == 7 && $total_itinerary_plan_route_hotspot_details_count == $itineary_route_hotspot_count) :
                    $tbl_hotspot .= '<tr style="font-size:13px;font-weight:bold;">
                                                                                        <td colspan="14" align="left">Return to ' . getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location') . ' <span>&nbsp;</span><span style="font-size:12px; color:#616161;"> ' . date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)) . ' -  ' . $hotspot_travelling_distance . 'KM - ' . formatTimeDuration($hotspot_traveling_time) . ' - Including Depature Type Buffer Time of ' . formatTimeDuration($itinerary_travel_type_buffer_time) . '</span></td>
                                                                                        </tr>';
                endif;




            endwhile;
        endif;
        $tbl_hotspot .=
            '</table>';


    endwhile;
endif;


$pdf->writeHTML($tbl_hotspot, true, false, true, false, '');


// -------------------------------------- HOTSPOT DETAILS --------------------------------------- //


$itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
$itinerary_plan_hotel_group_query_count = sqlNUMOFROW_LABEL($itinerary_plan_hotel_group_query);
if ($itinerary_plan_hotel_group_query_count > 0) :
    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
        $group_type = $row_hotel_group['group_type'];

        // START ITINERARY PREFERENCE BOTH //
        if ($itinerary_preference == 3) :
            if ($group_type == $recommended1 || $group_type == $recommended2 ||  $group_type == $recommended3 ||  $group_type == $recommended4) :


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
                    <th width="20%" align="center" style="font-size:12px;"><br/><br/><b>VENDOR NAME</b><br/></th>
                    <th width="20%" align="center" style="font-size:12px;"><br/><br/><b>BRANCH NAME</b><br/></th>
                    <th width="20%" align="center" style="font-size:12px;"><br/><br/><b>VEHICLE ORIGIN</b><br/></th>
                    <th width="20%" align="center" style="font-size:12px;"><br/><br/><b>TOTAL QTY</b><br/></th>
                    <th width="20%" align="center" style="font-size:12px;"><br/><br/><b>TOTAL AMOUNT</b><br/></th>
                </tr>';


                $tbl_details .= '<tr>
                <td width="20%" align="left" style="font-size:12px;">DVI-CHENNAI</td>
                    <td width="20%" align="right" style="font-size:12px;">DVI-CHENNAI</td>
                    <td width="20%" align="right" style="font-size:12px;">Chennai</td>
                    <td width="20%" align="right" style="font-size:12px;">2 x <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> 21,400.00</td>
                    <td width="20%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> 42,800.00</td>
                </tr>';


                $tbl_details .= ' </table> ';

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

                if (($vehicleTotal = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount')) != 0) :
                    $tbl_details .= ' <tr>
                    <td width="75%" align="left" style="font-size:12px;">Total for the Vehicle</td>
                    <td width="25%" align="right" style="font-size:12px;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> 42,800.00</td>
                    </tr>';
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
        $tbl_details .= '<table  cellspacing="0" cellpadding="8" border="0">  <tr>
    <td><span>&nbsp;</span></td>
   </tr></table>';

    endwhile;
    $pdf->writeHTML($tbl_details, true, false, false, false, '');
endif;



//Close and output PDF document
$pdf->Output('travel_itinerary - ' . $itinerary_quote_ID . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+