<?php

include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');


class TCPDFCustom extends TCPDF
{

    public $drawBorders = false;
    public function Header()
    {
        $site_logo = 'http://localhost/dvi_travels/head/assets/img/logo-preview.png';
        $company_contact_no = getGLOBALSETTING('company_contact_no');
        $company_name = getGLOBALSETTING('company_name');
        $logoPath =  $site_logo;
        $logoWidth = 26;
        $this->Image($logoPath, 6, 5, $logoWidth, '', '', '', 'C');

        $this->SetFont('helvetica', 'B', 20);
        $this->SetFont('helvetica', 'BI', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(80, 3, $company_name, 0, 'c', false, 1, 74, 15);

        $this->SetFont('helvetica', 'BI', 10);
        $this->SetTextColor(0, 0, 128);
        $this->MultiCell(60, 3, "Hotel Voucher", 0, 'R', false, 1, 144, 13);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 8);
        $this->MultiCell(60, 3, "Customer Care " . $company_contact_no, 0, 'R', false, 1, 144, 18);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 8);
        $this->MultiCell(60, 3, "Customer Care " . $company_contact_no, 0, 'R', false, 1, 144, 20);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        // Draw page borders only if the flag is set
        if ($this->drawBorders) {
            $this->drawPageBorders();
        }
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

// Create new PDF document
$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('ITINERARY PDF');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');

// Set default header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 5, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$confirmed_itinerary_plan_ID = $_GET['confirmid'];
$itinerary_plan_ID = $_GET['id'];


$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `location_id`, `itinerary_quote_ID`, `total_adult`, `total_children`, `total_infants`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `food_type`, `special_instructions` FROM `dvi_confirmed_itinerary_plan_details` WHERE  `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
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

// Query to get the records from the database
$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `itinerary_route_date`, `hotel_id`, `hotel_confirmed_by`, `hotel_confirmed_email_id`, `hotel_confirmed_mobile_no`, `hotel_booking_status` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `cnf_itinerary_plan_hotel_voucher_details_ID` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);


if ($total_itinerary_plan_details_count > 0) :

    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $itinerary_plan_hotel_details_ID = $fetch_itinerary_plan_data['itinerary_plan_hotel_details_ID'];
        $itinerary_route_date =  date('d M Y', strtotime($fetch_itinerary_plan_data['itinerary_route_date']));
        $hotel_id = $fetch_itinerary_plan_data['hotel_id'];
        $hotel_confirmed_by = $fetch_itinerary_plan_data['hotel_confirmed_by'];
        $hotel_confirmed_email_id = $fetch_itinerary_plan_data['hotel_confirmed_email_id'];
        $hotel_confirmed_mobile_no = $fetch_itinerary_plan_data['hotel_confirmed_mobile_no'];
        $get_hotel_booking_status = $fetch_itinerary_plan_data['hotel_booking_status'];
        $hotel_location = getHOTELDETAILS($hotel_id, 'hotel_place');

        $roomDetails = getRoomDetails($itinerary_plan_ID, $fetch_itinerary_plan_data['itinerary_route_date']);
        $formatRoomDetails = formatRoomDetails_withoutdate($roomDetails);

        $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
        $occupancyDetails = getOccupancyDetails($itinerary_plan_ID, $fetch_itinerary_plan_data['itinerary_route_date']);
        $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);

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


        // Add a new page for each record
        $pdf->AddPage();


        // Route Details
        $table_data = '<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
        <table cellspacing="5" cellpadding="6" border="0">
            <tr>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                   <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Quote ID</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_quote_ID . '</span>
                </td>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                    <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Date</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_route_date . '</span>
                </td>
            </tr>
            <tr>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                   <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Room Count</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $preferred_room_count . '</span>
                </td>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                    <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Extra Bed</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $total_extra_bed . '</span>
                </td>
            </tr>
            <tr>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                   <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Child With bed</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $total_child_with_bed . '</span>
                </td>
                <td width="50%" style="border:1px solid #000; padding:5px;">
                    <br/>
                    <span style="font-weight:bold; font-size:12px; color:#8e8a8a;">Child Without bed</span><br/>
                    <span style="font-weight:bold; font-size:12px; color:#232323;">' . $total_child_without_bed . '</span>
                </td>
            </tr>
        </table>
    </td>';

        $select_guest_details = sqlQUERY_LABEL("SELECT `customer_name`, `customer_age`, `primary_contact_no`, `altenative_contact_no`, `email_id` FROM `dvi_confirmed_itinerary_customer_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_itinerary_plan_ID` = '$confirmed_itinerary_plan_ID' and `primary_customer` = '1'") or die("#1-UNABLE_TO_COLLECT_GUEST_DETAILS:" . sqlERROR_LABEL());
        $total_guest_details_count = sqlNUMOFROW_LABEL($select_guest_details);

        if ($total_guest_details_count > 0) :
            while ($fetch_guest_data = sqlFETCHARRAY_LABEL($select_guest_details)) :
                $customer_name = $fetch_guest_data['customer_name'];
                $customer_age = $fetch_guest_data['customer_age'];
                $primary_contact_no = $fetch_guest_data['primary_contact_no'];
                $altenative_contact_no = $fetch_guest_data['altenative_contact_no'];
                $email_id = $fetch_guest_data['email_id'];

                $primary_contact_data = $primary_contact_no ?: '--';
                $altenative_contact_no_data = $altenative_contact_no ?: '--';
                $email_id_data = $email_id ?: '--';
            endwhile;
        endif;

        $table_data .= '<td width="50%">
<table cellspacing="5" cellpadding="8" border="0">
    <tr>
        <td width="100%" style="border:1px solid #000; padding:5px;">
            <br/>
                <span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Customer Name: </span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $customer_name . '</span>
                   <br/>
                     <span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Customer Age: </span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $customer_age . '</span>
                   <br/>
                     <span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Primary Contact No: </span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $primary_contact_data . '</span>
                  <br/>
                     <span style="font-weight:400; font-size:12px;color:#8e8a8a; font-weight:bold;">Email Id: </span><br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $email_id_data . '</span>
        </td>
    </tr>
</table>
</td>
</tr>
</table>';

        // Output the HTML content
        $pdf->writeHTML($table_data, true, false, false, false, '');

        // Hotel Details
        $select_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id`, `room_type_id`, `room_id` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_DETAILS:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_hotel_details) > 0) :
            while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
                $hotel_id = $fetch_hotel_data['hotel_id'];
                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                $hotel_address = getHOTEL_DETAIL($hotel_id, '', 'hotel_address');
                $room_type = getROOMTYPE_DETAILS($fetch_hotel_data['room_id'], 'room_type_title');
                $check_in_time = getROOM_DETAILS($fetch_hotel_data['room_id'], 'check_in_time');
                $check_out_time = getROOM_DETAILS($fetch_hotel_data['room_id'], 'check_out_time');
            endwhile;
        endif;

        $tbl_hotel_details = '
        <table cellspacing="5" cellpadding="0" border="0">
            <tr><td>
                <table cellspacing="0" cellpadding="8" border="1">
                    <thead>
                        <tr><td colspan="2" align="center" style="font-weight:bold; font-size: 12px; background-color: RGB(0, 0, 128);"><span style="font-size: 14px; vertical-align: middle; color: #fff;">Hotel Details</span></td></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Booking ID</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $itinerary_quote_ID . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Hotel Name</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $hotel_name . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Hotel Address</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $hotel_address . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Check-In Date</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . dateformat_datepicker($itinerary_route_date) . ' ' . date('h:i A', strtotime($check_in_time)) . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Check-Out Date</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . dateformat_datepicker(date('Y-m-d', strtotime($itinerary_route_date . ' +1 day'))) . ' ' . date('h:i A', strtotime($check_out_time)) . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Room Type</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $room_type . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Room Count</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $preferred_room_count . ' Rooms | ' . $formattedoccupancyDetails . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Number of Guests</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $total_adult . ' Adults | Childrens (Above 5 & Below 10) - ' . $total_children . ' | Infants Below 5 Years - ' . $total_infants . '.</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Meal plan</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $meal_plan . '' . $meal_breakfast . '' . $meal_lunch . '' . $meal_dinner . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Food Type</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $food_type_format . '</td>
                        </tr>
                         <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Rate</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $formatRoomDetails . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Special Requests</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">( here any special request mentioned while raising the booking )</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Confirm By</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $hotel_confirmed_by . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Email Id</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $hotel_confirmed_email_id . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Mobile Number</b></td>
                            <td width="70%" align="left" style="font-size: 12px;">' . $hotel_confirmed_mobile_no . '</td>
                        </tr>
                        <tr>
                            <td width="30%" align="left" style="font-size: 12px;"><b>Status</b></td>
                            <td width="70%" align="left" style="font-size: 12px;' . $booking_status_color . '"><b>' . $hotel_booking_status . '</b></td>
                        </tr>
                    </tbody>
                </table>
            </td></tr>
        </table>';


        // Render the hotel details
        $pdf->writeHTML($tbl_hotel_details, true, false, false, false, '');



    endwhile;


    $pdf->AddPage();
    $pdf->drawBorders = true; // Enable border drawing for the first page of terms and conditions

    // Initialize the table for terms and conditions content
    $table_terms_condition = '<table cellspacing="5" cellpadding="8" border="0">
        <tr>
            <td style="margin: 0; padding: 0;">
                <h4 style="margin: 0; padding: 0;"><b>Terms and Condition:</b></h4>
                <p style="margin: 0; padding: 0;font-size: 10pt; line-height: 1.3;">
                    ' . geTERMSANDCONDITION('get_hotel_terms_n_condtions') . '
                </p>
            </td>   
        </tr>  </table>';

    // Check if a new page has been started
    if ($pdf->getPage() < 1) {
        // If a new page has been started, disable border drawing
        $pdf->drawBorders = false;
    }


    // Write the HTML content to the PDF
    $pdf->writeHTML($table_terms_condition, true, false, false, false, '');


    // Output combined PDF document
    $pdf->Output('itinerary-voucher - ' . $itinerary_quote_ID . '.pdf', 'I');
endif;
