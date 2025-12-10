<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

if (isset($_GET['all']) && $_GET['all'] == 'true') {
    $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
    $confirmed_itinerary_plan_ID = $_GET['confirmid'];
    $filter_by_hotel_itinerary = "and HOTEL_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_itinerary = "and `itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_confirmed_itinerary = "and `confirmed_itinerary_plan_ID` = '$confirmed_itinerary_plan_ID'";
    $filter_by_selected_hotels = '';
} elseif (isset($_GET['selectedHotel'])) {
    $selectedHotels = $_GET['selectedHotel'];

    if (strpos($selectedHotels, ',') !== false) {

        $selectedHotelsArray = explode(',', $_GET['selectedHotel']);

        $selectedHotelsList = implode("','", array_map('intval', $selectedHotelsArray)); // Use intval for security
    } else {
        $selectedHotelsList = $_GET['selectedHotel'];
    }

    $filter_by_selected_hotels = "AND HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` IN ('$selectedHotelsList')";

    $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
    $confirmed_itinerary_plan_ID = $_GET['confirmid'];
    $filter_by_hotel_itinerary = "and HOTEL_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_itinerary = "and `itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_confirmed_itinerary = "and `confirmed_itinerary_plan_ID` = '$confirmed_itinerary_plan_ID'";
} else {
    $filter_by_selected_hotels = '';
    $itinerary_plan_ID = $_GET['itinerary_plan_ID'];
    $confirmed_itinerary_plan_ID = $_GET['confirmid'];
    $filter_by_hotel_itinerary = "and HOTEL_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_itinerary = "and `itinerary_plan_ID` = '$itinerary_plan_ID'";
    $filter_by_confirmed_itinerary = "and `confirmed_itinerary_plan_ID` = '$confirmed_itinerary_plan_ID'";
}

class TCPDFCustom extends TCPDF
{

    public function Header()
    {
        $selected_itinerary_plan_ID = $_GET['itinerary_plan_ID'];

        $selected_agent_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($selected_itinerary_plan_ID, 'agent_id');
        $backgroundImage = '' . BASEPATH . 'assets/img/pattern.jpg';
        $get_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($_GET['itinerary_plan_ID'], 'itinerary_quote_ID');
        $this->Image($backgroundImage, 0, 0, 210, 20, '', '', '', false, 300, '', false, false, 0);
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->MultiCell(60, 3, "Hotel Voucher", 0, 'L', false, 1, 10, 6);
        $this->SetFont('helvetica', 'B', 7);
        $this->SetTextColor(255, 255, 255);
        $this->MultiCell(80, 3, 'QUOTE ID', 0, 'R', false, 1, 120, 4);
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(255, 255, 255);
        $this->MultiCell(80, 3, $get_quote_ID, 0, 'R', false, 1, 120, 8);

        if ($selected_agent_id != '' &&  $selected_agent_id != '0'):
            $company_name = get_AGENT_CONFIG_DETAILS($selected_agent_id, 'company_name');
            $agent_site_logo = get_AGENT_CONFIG_DETAILS($selected_agent_id, 'site_logo');
            $company_address = get_AGENT_CONFIG_DETAILS($selected_agent_id, 'site_address');
            $company_contact_no = getAGENT_details($selected_agent_id, '', 'get_agent_mobile_number');
            $company_email_id = getAGENT_details($selected_agent_id, '', 'get_agent_email_address');
            if ($agent_site_logo != '' && $agent_site_logo != 'NULL'):
                $company_logo_format = '' . BASEPATH . 'uploads/agent_gallery/' . $agent_site_logo . '';
            else:
                $company_logo_format = '';
            endif;
        else:
            $company_name = getGLOBALSETTING('company_name');
            $company_contact_no = getGLOBALSETTING('company_contact_no');
            $company_email_id = getGLOBALSETTING('company_email_id');
            $company_address = getGLOBALSETTING('company_address');
            $company_pincode = getGLOBALSETTING('company_pincode');
            $company_logo = getGLOBALSETTING('company_logo');
            $company_logo_format = '' . BASEPATH . './uploads/logo/' . $company_logo . '';
        endif;
        $this->SetY(20);
        $table_company_details = '<table cellspacing="0" cellpadding="8" border="0"> <tr> <td width="70%"> <img src="' . $company_logo_format . '" width="100px"/> </td> <td width="30%"> <table cellspacing="0" cellpadding="0" border="0"> <tr> <td width="100%" style="text-align:right;"> <br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $company_name . '</span> <br/> <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_contact_no . '</span> <br/> <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_email_id . '</span> <br/> <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_address . ' - ' . $company_pincode . '</span> </td> </tr> </table> </td> </tr> </table>';
        $this->writeHTML($table_company_details, true, false, false, false, '');
    }

    public function Footer()
    {
        $backgroundImage = '' . BASEPATH . 'assets/img/pattern.jpg';
        $websiteIcon = '' . BASEPATH . 'assets/img/global.png';
        $emailIcon = '' . BASEPATH . 'assets/img/message.png';
        $this->SetY(-20);
        $this->Image($backgroundImage, 0, $this->GetY(), 210, 20, '', '', '', false, 300, '', false, false, 0);
        $this->SetXY(10, -12.2);
        $this->Image($websiteIcon, 10, $this->GetY(), 5, 5, '', '', '', false, 300, '', false, false, 0);
        $this->SetFont('helvetica', 'N', 12);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(17, -15);
        $this->Cell(0, 10, 'http://www.dvi.co.in/', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(12, -15);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetXY(-50, -11.9);
        $this->Image($emailIcon, 173, $this->GetY(), 5, 5, '', '', '', false, 300, '', false, false, 0);
        $this->SetFont('helvetica', 'N', 12);
        $this->SetXY(170, -15);
        $this->Cell(0, 10, getGLOBALSETTING('company_email_id'), 0, 0, 'R', 0, '', 0, false, 'T', 'M');
    }

    public function drawPageBorders()
    {
        $topBorderY = 32;
        $bottomBorderY = $this->getPageHeight() - 15;
        $this->Line(5, $topBorderY, $this->getPageWidth() - 5, $topBorderY);
        $this->Line(5, $bottomBorderY, $this->getPageWidth() - 5, $bottomBorderY);
        $this->Line(5, $topBorderY, 5, $bottomBorderY);
        $this->Line($this->getPageWidth() - 5, $topBorderY, $this->getPageWidth() - 5, $bottomBorderY);
    }
}

$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('ITINERARY HOTEL VOUCHER');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetMargins(PDF_MARGIN_LEFT, 60, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();

$logged_user_level = 4; // AGENT

$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`,`location_id`, `itinerary_quote_ID`, `total_adult`, `total_children`, `total_infants`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `food_type`, `special_instructions` FROM `dvi_confirmed_itinerary_plan_details` WHERE  `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :

    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $itinerary_booking_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $selected_itinerary_ID = $fetch_itinerary_plan_data['itinerary_plan_ID'];
        $itinerary_quote_ID = get_ITINERARY_PLAN_DETAILS($selected_itinerary_ID, 'itinerary_quote_ID');
        $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
        $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
        $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
        $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
        $total_adult = $fetch_itinerary_plan_data['total_adult'];
        $total_children = $fetch_itinerary_plan_data['total_children'];
        $total_infants = $fetch_itinerary_plan_data['total_infants'];
        $meal_plan_breakfast = $fetch_itinerary_plan_data['meal_plan_breakfast'];
        $meal_plan_lunch = $fetch_itinerary_plan_data['meal_plan_lunch'];
        $meal_plan_dinner = $fetch_itinerary_plan_data['meal_plan_dinner'];
        $food_type = $fetch_itinerary_plan_data['food_type'];
        $special_instructions = $fetch_itinerary_plan_data['special_instructions'];

        if ($meal_plan_breakfast == '1') :
            $meal_breakfast = 'Breakfast, ';
        endif;
        if ($meal_plan_lunch == '1') :
            $meal_lunch = 'Lunch, ';
        endif;
        if ($meal_plan_dinner == '1') :
            $meal_dinner = 'Dinner';
        endif;
        if ($meal_plan_breakfast == '0' &&  $meal_plan_lunch == '0' && $meal_plan_dinner == '0') :
            $meal_plan = 'EP';
        endif;

        if ($food_type == 1) :
            $food_type_format = 'Vegetarian';
        elseif ($food_type == 2) :
            $food_type_format = 'Non-Vegetarian';
        else :
            $food_type_format = 'Non-Vegetarian';
        endif;
    endwhile;
endif;
$hotelDetailsCount = 0;
$consecutive_entries = [];
$previous_hotel_id = null;
$consecutive_dates = [];
$combined_meal_plan_details = "";
$combined_room_details = "";
$combined_room_rate_details = "";

$select_voucher_plan_details = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`itinerary_route_date`, HOTEL_DETAILS.`hotel_id`, HOTEL_DETAILS.`hotel_confirmed_by`, HOTEL_DETAILS.`hotel_confirmed_email_id`, HOTEL_DETAILS.`hotel_confirmed_mobile_no`, HOTEL_DETAILS.`hotel_booking_status` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` AS HOTEL_DETAILS WHERE HOTEL_DETAILS.`deleted` = '0' {$filter_by_hotel_itinerary} {$filter_by_selected_hotels} ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
$current_index = 0;

if ($total_itinerary_plan_details_count > 0) :

    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $confirmed_itinerary_plan_hotel_details_ID = $fetch_itinerary_plan_data['confirmed_itinerary_plan_hotel_details_ID'];
        $itinerary_route_date = date('Y-m-d', strtotime($fetch_itinerary_plan_data['itinerary_route_date']));
        $hotel_id = $fetch_itinerary_plan_data['hotel_id'];

        $select_voucher_hotel_room_plan_details = sqlQUERY_LABEL("SELECT ROOM_DETAILS.`room_id` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` AS HOTEL_DETAILS LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` = HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` WHERE HOTEL_DETAILS.`deleted` = '0' {$filter_by_hotel_itinerary} {$filter_by_selected_hotels} ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_hotel_room_plan_data = sqlFETCHARRAY_LABEL($select_voucher_hotel_room_plan_details)) :
            $selected_room_id = $fetch_hotel_room_plan_data['room_id'];
            $check_in_time = getROOM_DETAILS($selected_room_id, 'check_in_time');
            $check_out_time = getROOM_DETAILS($selected_room_id, 'check_out_time');
            $check_in_time = date('h:i A', strtotime($check_in_time));
            $check_out_time = date('h:i A', strtotime($check_out_time));
        endwhile;

        if ($current_index == 0) {
            $consecutive_dates[] = $itinerary_route_date;
        } else {
            $last_consecutive_date = end($consecutive_dates);
            $next_date = date('Y-m-d', strtotime($last_consecutive_date . ' +1 day'));

            if ($hotel_id !== $previous_hotel_id || $itinerary_route_date !== $next_date) {
                // $check_in_date_time = $consecutive_dates[0]; // First date in the array
                // $check_out_date_time = date('Y-m-d', strtotime(end($consecutive_dates) . ' +1 day')); // Day after the last date

                $consecutive_entries[] = [
                    'dates' => $consecutive_dates,
                    // 'check_in_date_time' => $check_in_date_time,
                    // 'check_out_date_time' => $check_out_date_time,
                    'meal_plan_details' => $combined_meal_plan_details,
                    'room_details' => $combined_room_details,
                    'room_rate_details' => $combined_room_rate_details,
                    'hotel_id' => $previous_hotel_id
                ];

                // Reset for the new hotel
                $consecutive_dates = [$itinerary_route_date];
                $combined_meal_plan_details = "";
                $combined_room_details = "";
                $combined_room_rate_details = "";
            } else {
                $consecutive_dates[] = $itinerary_route_date;
            }
        }

        $previous_hotel_id = $hotel_id;

        if ($logged_user_level != 4):
            $mealplandetails = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_ID, $hotel_id, $itinerary_route_date, '', 'meal_plan_with_cost');
        else:
            $mealplandetails = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_ID, $hotel_id,  $itinerary_route_date, '', 'meal_plan');
        endif;

        $combined_meal_plan_details .= $mealplandetails . "<br>";
        $occupancyDetails = getOccupancyDetails($itinerary_plan_ID, $itinerary_route_date);
        $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);
        $combined_room_details .= "<strong>" . date('M d, Y', strtotime($itinerary_route_date)) . "</strong> - $preferred_room_count Rooms | $formattedoccupancyDetails<br>";
        $roomDetails = getRoomDetails($itinerary_plan_ID, $itinerary_route_date);
        $combined_room_rate_details .= formatRoomDetails($roomDetails) . "<br>";

        $current_index++;

        if ($current_index == $total_itinerary_plan_details_count) {
            // $check_in_date_time = $consecutive_dates[0];
            // $check_out_date_time = date('Y-m-d', strtotime(end($consecutive_dates) . ' +1 day'));

            $consecutive_entries[] = [
                'dates' => $consecutive_dates,
                // 'check_in_date_time' => $check_in_date_time,
                // 'check_out_date_time' => $check_out_date_time,
                'meal_plan_details' => $combined_meal_plan_details,
                'room_details' => $combined_room_details,
                'room_rate_details' => $combined_room_rate_details,
                'hotel_id' => $hotel_id
            ];
        }
    endwhile;

endif;


// Process and print the collected details
foreach ($consecutive_entries as $entry) :
    $hotel_id = $entry['hotel_id'];
    $hotel_category = getHOTELDETAILS($hotel_id, 'hotel_category');
    $hotel_category_label = getHOTEL_CATEGORY_DETAILS($hotel_category, 'label');
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $hotel_address = getHOTEL_DETAIL($hotel_id, '', 'hotel_address');
    $dates = $entry['dates'];
    $room_details = $entry['room_details'];
    if ($logged_vendor_id == '0' || $logged_vendor_id == ''):
        $room_rate_details = $entry['room_rate_details'];
        $meal_plan_details = $entry['meal_plan_details'];
    endif;

    // $check_in_date_time = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_ID, $hotel_id, '', 'check_in_date_and_time');
    // $check_out_date_time = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_ID, $hotel_id, '', 'check_out_date_and_time');

    $check_in_date_time = date('M d, Y', strtotime($dates[0])) . ' ' . $check_in_time;
    $check_out_date_time = date('M d, Y', strtotime(end($dates) . ' +1 day')) . ' ' . $check_out_time;

    // Convert hotel_category_label to star count
    $label = strtolower(trim($hotel_category_label)); // "Budget" becomes "budget"

    if (preg_match('/(\d+)/', $label, $matches)) {
        // If it's something like "3*", "2*", etc., extract the number
        $star_count = (int)$matches[1];
    } elseif (in_array($label, ['budget', 'std', 'standard'])) {
        // Handle special names as 3 stars
        $star_count = 3;
    } else {
        $star_count = 0; // default fallback
    }


    $stars = '';
    for ($i = 0; $i < $star_count; $i++) {
        $stars .= '<span> <img src="' . BASEPATH . 'assets/img/rating.png" width="11px" height="11px"/></span>';
    }


    $table_hotel_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
      <td width="60%">
        <table cellspacing="0" cellpadding="8" border="0">
          <tr>
            <td width="100%" height="111px" style="border:4px solid #d3d3d3;">
              <br/>
              <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Booking ID : </span>
              <span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_booking_ID . '</span>
              <br/>
              <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Quote ID : </span>
              <span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_quote_ID . '</span>
              <br/>
              <div style="font-weight:semi-bold; font-size:16px; color:#232323">' . $hotel_name . '</div>' . $stars . '
              <br/>
              <span style="font-weight:regular; font-size:12px; color:#808080;">' . $hotel_address . '</span>
            </td>
          </tr>
          <tr>';


    if ($logged_user_level != 4):
        $table_hotel_details .= '<td width="25%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Check-In Date:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . ($check_in_date_time) . '</span> </td> <td width="26%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Check-Out Date:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . ($check_out_date_time) . '</span> </td>';
    else:
        $table_hotel_details .= '<td width="51%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Check-In Date:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . ($check_in_date_time) . '</span> </td>';
    endif;

    $table_hotel_details .= '<td rowspan="3" width="49%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Booking Details:</span> <br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $room_details . '</span> <br/>  <span style="color:#232323;font-weight:regular; font-size:12px;">Extra bed - </span><span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_extra_bed . '</span> <br/><span style="color:#232323;font-weight:regular; font-size:12px;">Child With bed - </span><span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_child_with_bed . '</span><br/><span style="color:#232323;font-weight:regular; font-size:12px;">Child Without bed - </span><span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_child_without_bed . '</span>
                            <br/><br/> <span style="color:#8e8a8a;font-weight:regular; font-size:12px;">Meal Plan:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $meal_plan_details . '</span><br/><span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $food_type_format . '</span> </td> </tr>';
    if ($selected_agent_id != '' && $selected_agent_id != '0') :
        $table_hotel_details .= '<tr><td width="51%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Check-Out Date:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $check_out_date_time . '</span> </td></tr>';
    endif;
    if ($logged_user_level != 4 && ($logged_vendor_id == '' || $logged_vendor_id == '0')) :
        $table_hotel_details .= '<tr> <td width="51%" align="left" style="font-size: 12px; border:4px solid #d3d3d3;"><span style="color:#8e8a8a;font-weight:regular;">Number of Guests</span><br/> Adults - ' . $total_adult . ' | Children - ' . $total_children . ' | Infants - ' . $total_infants . '.</td> </tr> <tr> <td width="51%" align="left" style="font-size: 12px; border:4px solid #d3d3d3;"><span style="color:#8e8a8a;font-weight:regular;">Rate</span><br/>' . $room_rate_details . '</td> </tr>';
    else:
        $table_hotel_details .= '<tr> <td width="51%" style="border:4px solid #d3d3d3;"> <br/> <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Check-Out Date:</span><br/> <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . ($check_out_date_time) . '</span> </td> </tr>';
        $table_hotel_details .= '<tr> <td width="51%" align="left" style="font-size: 12px; border:4px solid #d3d3d3;"><span style="color:#8e8a8a;font-weight:regular;">Number of Guests</span><br/>Adults - ' . $total_adult . '<br/>Children - ' . $total_children . '<br/>Infants - ' . $total_infants . '.</td> </tr>';
    endif;
    $table_hotel_details .= '</table> </td>';

    // The SQL query you're debugging
    $select_guest_details = sqlQUERY_LABEL("SELECT `customer_name`, `customer_salutation`,`customer_age`, `primary_contact_no`, `altenative_contact_no`, `email_id` FROM `dvi_confirmed_itinerary_customer_details` WHERE `deleted` = '0' {$filter_by_itinerary} {$filter_by_confirmed_itinerary} and `primary_customer` = '1'") or die("#1-UNABLE_TO_COLLECT_GUEST_DETAILS:" . sqlERROR_LABEL());
    $total_guest_details_count = sqlNUMOFROW_LABEL($select_guest_details);

    if ($total_guest_details_count > 0) :
        while ($fetch_guest_data = sqlFETCHARRAY_LABEL($select_guest_details)) :
            $customer_name = $fetch_guest_data['customer_name'];
            $customer_salutation = $fetch_guest_data['customer_salutation'];
            $customer_age = $fetch_guest_data['customer_age'];
            $primary_contact_no = $fetch_guest_data['primary_contact_no'];
            $altenative_contact_no = $fetch_guest_data['altenative_contact_no'];
            $email_id = $fetch_guest_data['email_id'];
            $primary_contact_data = $primary_contact_no ?: '--';
            $altenative_contact_no_data = $altenative_contact_no ?: '--';
            $email_id_data = $email_id ?: '--';
        endwhile;
    endif;


    $select_voucher_plan_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID`, `itinerary_route_date`, `hotel_id`, `hotel_confirmed_by`, `hotel_confirmed_email_id`, `hotel_confirmed_mobile_no`, `hotel_booking_status` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_id' {$filter_by_itinerary} ORDER BY `itinerary_route_date` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);

    if ($total_itinerary_plan_details_count > 0) :
        while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
            $hotel_confirmed_by = $fetch_itinerary_plan_data['hotel_confirmed_by'];
            $hotel_confirmed_email_id = $fetch_itinerary_plan_data['hotel_confirmed_email_id'];
            $hotel_confirmed_mobile_no = $fetch_itinerary_plan_data['hotel_confirmed_mobile_no'];
            $get_hotel_booking_status = $fetch_itinerary_plan_data['hotel_booking_status'];

            // Determine booking status
            if ($get_hotel_booking_status == 1) :
                $hotel_booking_status = "Awaiting";
                $booking_status_color = "color:#ff9f43;";
            elseif ($get_hotel_booking_status == 2) :
                $hotel_booking_status = "Waitinglist";
                $booking_status_color = "color:#ea5455 ;";
            elseif ($get_hotel_booking_status == 3) :
                $hotel_booking_status = "Block";
                $booking_status_color = "color:gray;";
            elseif ($get_hotel_booking_status == 4) :
                $hotel_booking_status = "Confirmed";
                $booking_status_color = "color:green;";
            else :
                $hotel_booking_status = "N/A";
                $booking_status_color = "color:black;";
            endif;
        endwhile;
    endif;

    $table_hotel_details .= '<td width="40%" style="border:4px solid #d3d3d3;">
    <table cellspacing="0" cellpadding="18.3" border="0"> 
        <br/> 
        <span style="font-size:12px;color:#8e8a8a;font-weight:regular;">Guest Details: </span><br/> 
        <span style="font-weight:bold;font-size:16px;color:#232323;">' . $customer_salutation . ' ' . $customer_name . '</span> <br/>';
        if($customer_age):
            $table_hotel_details .= '<span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $customer_age . '</span> <br/>';
        endif;
        if($primary_contact_data && $primary_contact_data != '--'):
            $table_hotel_details .= '<span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $primary_contact_data . '</span> <br/>';
        endif;
        if($email_id_data && $email_id_data != '--'):
            $table_hotel_details .= '<span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $email_id_data . '</span> <br/>';
        endif;
        $table_hotel_details .= '<br/> ';
        $table_hotel_details .= '<span style="font-weight:bold;font-size:16px;' . $booking_status_color . '">' . $hotel_booking_status . '</span>';

    if ($logged_user_level != 4) {
        // Handle multiple email IDs by splitting on comma and formatting
        $email_ids = array_map('trim', explode(',', $hotel_confirmed_email_id));
        $formatted_emails = implode('<br/>', $email_ids);
        
        $table_hotel_details .= '<br/><br/> 
        <hr style="color: #d3d3d3; height: 4px; width:245px;" /> 
        <span style="font-size:12px;color:#8e8a8a;font-weight:regular;">Confirmed by :</span><br/> 
        <span style="font-weight:bold;font-size:16px;color:#232323;">' . $hotel_confirmed_by . '</span><br/> 
        <span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $formatted_emails . '<br/>' . $hotel_confirmed_mobile_no . '</span>';
    }

    $table_hotel_details .= '</table> </td> </tr> </table>';


    $table_hotel_details .= '<table cellspacing="0" cellpadding="0" border="0"> <tr> <td style="height:150px;"> </td> </tr> </table>';
    // Terms and Conditions Section
    $table_terms_condition_title = '<table cellspacing="0" cellpadding="0" border="0"> <tr> <td> <span style="font-weight:bold;font-size:16px;color:#232323;">Terms and Condition:</span> </td> </tr> </table>';

    $table_terms_condition = '<table cellspacing="0" cellpadding="0" border="0">
    <tr> 
        <td>
            <table cellspacing="0" cellpadding="10" border="1">
                <tr>
                    <td>
                        <span style="font-size: 10pt; line-height: 1.3;">
                            ' . geTERMSANDCONDITION('get_hotel_voucher_terms_n_condtions') . '
                        </span>
                    </td>   
                </tr>
            </table>
        </td>
    </tr></table>';

    $hotelDetailsCount++;  // Increment the counter
    $table_terms_condition++;

    // Check if two hotel details have been processed
    if ($hotelDetailsCount % 1 == 0) {
        // Output the HTML content for two hotels
        $pdf->writeHTML($table_hotel_details, true, false, false, false, '');
        $pdf->writeHTML($table_terms_condition_title, true, false, false, false, '');
        $pdf->writeHTML($table_terms_condition, true, false, false, false, '');

        // Reset the table details string
        $table_hotel_details = '';

        $pdf->AddPage();
    }

    // $pdf->writeHTML($table_hotel_details, true, false, false, false, '');
    // $pdf->writeHTML($table_terms_condition_title, true, false, false, false, '');
    // $pdf->writeHTML($table_terms_condition, true, false, false, false, '');

    // Clear the content variables for the next iteration
    $table_hotel_details = '';
    $table_terms_condition_title = '';
    $table_terms_condition = '';

endforeach;

if (!empty($table_hotel_details)) {
    $pdf->writeHTML($table_hotel_details, true, false, false, false, '');
}
if ($logged_user_level == 2):
    $pdf->Output('itinerary-hotel-voucher-' . $itinerary_booking_ID . '.pdf', 'D');
else:
    $pdf->Output('itinerary-hotel-voucher-' . $itinerary_booking_ID . '_' . $hotel_name . '.pdf', 'D');
endif;
