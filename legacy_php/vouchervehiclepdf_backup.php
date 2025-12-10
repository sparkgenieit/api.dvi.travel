<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

class TCPDFCustom extends TCPDF
{
  public function Header()
  {
    // Path to the background image
    $backgroundImage = 'http://localhost/dvi_travels/head/assets/img/pattern.jpg';

    $get_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($_GET['id'], 'itinerary_quote_ID');

    // Set the background image
    $this->Image($backgroundImage, 0, 0, 210, 20, '', '', '', false, 300, '', false, false, 0);

    // Set font for "Vehicle Voucher" text
    $this->SetFont('helvetica', 'B', 16);
    $this->SetTextColor(255, 255, 255);
    $this->MultiCell(60, 3, "Vehicle Voucher", 0, 'L', false, 1, 10, 6);

    // Set font for Booking ID
    $this->SetFont('helvetica', 'B', 7);
    $this->SetTextColor(255, 255, 255); // Set text color to white for visibility
    $this->MultiCell(80, 3, 'Booking ID', 0, 'R', false, 1, 120, 4);

    // Set font for company name and position it
    $this->SetFont('helvetica', 'B', 14);
    $this->SetTextColor(255, 255, 255); // Set text color to white for visibility
    $this->MultiCell(80, 3, $get_quote_ID, 0, 'R', false, 1, 120, 8);

    // Add company details
    $company_name = getGLOBALSETTING('company_name');
    $company_contact_no = getGLOBALSETTING('company_contact_no');
    $company_email_id = getGLOBALSETTING('company_email_id');
    $company_address = getGLOBALSETTING('company_address');
    $company_pincode = getGLOBALSETTING('company_pincode');

    $this->SetY(20); // Adjust Y position to avoid overlap

    $table_company_details = '<table cellspacing="0" cellpadding="8" border="0">
        <tr>
            <td width="70%">
                <img src="http://localhost/dvi_travels/head/assets/img/logo-preview.png" width="100px"/>
            </td>
            <td width="30%">
                <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td width="100%" style="text-align:right;">
                            <br/><span style="font-weight:bold;font-size:12px;color:#232323;">' . $company_name . '</span>
                            <br/>
                            <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_contact_no . '</span>
                            <br/>
                            <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_email_id . '</span>
                            <br/>
                            <span style="font-weight:bold;font-size:12px;color:#A0A0A0;">' . $company_address . ' - ' . $company_pincode . '</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>';

    $this->writeHTML($table_company_details, true, false, false, false, '');
  }

  public function Footer()
  {
    // Path to the background image for the footer
    $backgroundImage = 'http://localhost/dvi_travels/head/assets/img/pattern.jpg';

    // Path to the website icon image
    $websiteIcon = 'http://localhost/dvi_travels/head/assets/img/global.png'; // Replace with the correct path to your icon

    // Path to the email icon image
    $emailIcon = 'http://localhost/dvi_travels/head/assets/img/message.png'; // Replace with the correct path to your email icon

    // Set position for the footer
    $this->SetY(-20);

    // Draw the background image
    $this->Image($backgroundImage, 0, $this->GetY(), 210, 20, '', '', '', false, 300, '', false, false, 0);

    // Set position for website icon and text
    $this->SetXY(10, -12.2);
    // Display website icon
    $this->Image($websiteIcon, 10, $this->GetY(), 5, 5, '', '', '', false, 300, '', false, false, 0);
    // Set font for website text
    $this->SetFont('helvetica', 'N', 12);
    $this->SetTextColor(255, 255, 255); // Set text color if needed
    $this->SetXY(17, -15); // Position text next to the icon
    $this->Cell(0, 10, 'http://www.dvi.co.in/', 0, 0, 'L', 0, '', 0, false, 'T', 'M');

    // Set font for page number
    $this->SetFont('helvetica', 'I', 8);
    $this->SetTextColor(255, 255, 255);
    $this->SetXY(12, -15); // Center page number
    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    // Set position for email icon and text
    $this->SetXY(-50, -11.9); // Adjust X to position on the right
    // Display email icon
    $this->Image($emailIcon, 173, $this->GetY(), 5, 5, '', '', '', false, 300, '', false, false, 0); // Adjust the position as needed
    // Display email text
    $this->SetFont('helvetica', 'N', 12);
    $this->SetXY(170, -15); // Position text next to the icon, adjust X coordinate
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

// Create new PDF document
$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('ITINERARY VEHICLE VOUCHER');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');

// Set default header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 60, PDF_MARGIN_RIGHT); // Adjust top margin to avoid overlap with header
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a new page
$pdf->AddPage();

$itinerary_plan_ID = $_GET['id'];

$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE  `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
  while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
    $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
    $trip_start_date_and_time = dateformat_datepicker($fetch_itinerary_plan_data['trip_start_date_and_time']);
    $trip_end_date_and_time = dateformat_datepicker($fetch_itinerary_plan_data['trip_end_date_and_time']);
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

    $total_pax_count = $total_adult + $total_children + $total_infants;
  endwhile;
endif;

$table_itinerary_plan_details = '<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
      <span style="font-weight:bold;font-size:16px;color:#232323;">Tour Plan :</span>
    </td>
</tr>
</table>';

$pdf->writeHTML($table_itinerary_plan_details, true, false, false, false, '');
$table_itinerary_plan_details = '';

$table_itinerary_plan_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td>
  <table cellspacing="0" cellpadding="8" border="0">
    <tr>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Start Date & Time:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $trip_start_date_and_time . '</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">End Date & Time:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .  $trip_end_date_and_time . '</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Booking ID:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .   $itinerary_quote_ID . '</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Trip Night & Day:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $no_of_nights . ' Nights / ' . $no_of_days . ' Days </span> 
      </td>
    </tr>
    <tr>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Entry Ticket Required:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $entry_ticket_required . '</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Nationality:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .  $nationality . '</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Total Pax:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .   $total_adult . ' Adult, ' .   $total_children . ' Child, ' .   $total_infants . ' Infant</span> 
      </td>
        <td width="25%" style="border:4px solid #d3d3d3;">
          <br/>
            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Room Count:</span><br/>
            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $preferred_room_count . '</span> 
      </td>
    </tr>
    
    </table>
    </td>
</tr></table>';

$pdf->writeHTML($table_itinerary_plan_details, true, false, false, false, '');



$select_guest_details = sqlQUERY_LABEL("SELECT `customer_name`, `customer_age`, `primary_contact_no`, `altenative_contact_no`, `email_id` FROM `dvi_confirmed_itinerary_customer_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1'") or die("#1-UNABLE_TO_COLLECT_GUEST_DETAILS:" . sqlERROR_LABEL());
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


$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vendor_id`, `vehicle_id`, `vendor_branch_id`, `vehicle_confirmed_by`, `vehicle_confirmed_email_id`, `vehicle_confirmed_mobile_no`, `invoice_to`, `vehicle_booking_status`, `vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
  while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
    $vehicle_type_id = $fetch_itinerary_plan_data['vehicle_type_id'];
    //$vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $vendor_id = $fetch_itinerary_plan_data['vendor_id'];
    $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
    $vehicle_id = $fetch_itinerary_plan_data['vehicle_id'];
    $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
    $vendor_branch_id = $fetch_itinerary_plan_data['vendor_branch_id'];
    $vendor_branch_name = getBranchLIST($vendor_branch_id, 'branch_label');
    $vehicle_confirmed_by = $fetch_itinerary_plan_data['vehicle_confirmed_by'];
    $vehicle_confirmed_email_id = $fetch_itinerary_plan_data['vehicle_confirmed_email_id'];
    $vehicle_confirmed_mobile_no = $fetch_itinerary_plan_data['vehicle_confirmed_mobile_no'];
    $vehicle_booking_status = $fetch_itinerary_plan_data['vehicle_booking_status'];
    $vehicle_voucher_terms_condition = $fetch_itinerary_plan_data['vehicle_voucher_terms_condition'];
    $get_vehicle_voucher_terms_condition =  htmlspecialchars_decode($vehicle_voucher_terms_condition, ENT_QUOTES);

    // Determine booking status
    if ($vehicle_booking_status == 1) :
      $vehicle_booking_status = "Awaiting";
      $booking_status_color = "color:#ff9f43;";
    elseif ($vehicle_booking_status == 2) :
      $vehicle_booking_status = "Waitinglist";
      $booking_status_color = "color:#ea5455 ;";
    elseif ($vehicle_booking_status == 3) :
      $vehicle_booking_status = "Block";
      $booking_status_color = "color:gray;";
    elseif ($vehicle_booking_status == 4) :
      $vehicle_booking_status = "Confirmed";
      $booking_status_color = "color:green;";
    else :
      $vehicle_booking_status = "N/A";
      $booking_status_color = "color:black;";
    endif;


    // Query to get the records from the database
    $select_vehiclevoucher_eligible_plan_details = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `vehicle_orign`, `vehicle_count`, `total_kms`, `total_outstation_km`, `total_time`, `total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_extra_time`, `total_after_8_pm_extra_time`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle`, `extra_km_rate`, `total_allowed_kms`, `total_extra_kms`, `total_extra_kms_charge`, `vehicle_gst_type`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `vehicle_grand_total` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1' and `vendor_vehicle_type_id` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_vehiclevoucher_eligible_plan_details);
    if ($total_itinerary_plan_details_count > 0) :
      while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_vehiclevoucher_eligible_plan_details)) :
        $vehicle_type_id = $fetch_itinerary_plan_data['vehicle_type_id'];
        $total_vehicle_qty = $fetch_itinerary_plan_data['total_vehicle_qty'];
        $vendor_vehicle_type_id = $fetch_itinerary_plan_data['vendor_vehicle_type_id'];
        $vehicle_id = $fetch_itinerary_plan_data['vehicle_id'];
        $vendor_branch_id = $fetch_itinerary_plan_data['vendor_branch_id'];
        $vehicle_orign = $fetch_itinerary_plan_data['vehicle_orign'];
        $vehicle_count = $fetch_itinerary_plan_data['vehicle_count'];
        $total_allowed_kms = $fetch_itinerary_plan_data['total_allowed_kms'];
        $extra_km_rate = number_format($fetch_itinerary_plan_data['extra_km_rate'], 2);
        $vehicle_gst_percentage = $fetch_itinerary_plan_data['vehicle_gst_percentage'];
        $vendor_margin_percentage = $fetch_itinerary_plan_data['vendor_margin_percentage'];
        $vendor_margin_gst_percentage = $fetch_itinerary_plan_data['vendor_margin_gst_percentage'];
        $outstation_allowed_km_per_day = $fetch_itinerary_plan_data['outstation_allowed_km_per_day'];
        $vehicle_total_amount = number_format($fetch_itinerary_plan_data['vehicle_total_amount'], 2);
        $vehicle_grand_total = round($fetch_itinerary_plan_data['vehicle_grand_total']);
        $get_vehicle_grand_total = number_format($vehicle_grand_total, 2);
        $vendor_margin_gst_amount = number_format($fetch_itinerary_plan_data['vendor_margin_gst_amount'], 2);
        $vendor_margin_amount = number_format($fetch_itinerary_plan_data['vendor_margin_amount'], 2);
        $vehicle_gst_amount = number_format($fetch_itinerary_plan_data['vehicle_gst_amount'], 2);
        $total_extra_kms = number_format($fetch_itinerary_plan_data['total_extra_kms'], 0, '.', '');
        $total_kms =  number_format($fetch_itinerary_plan_data['total_kms'], 0, '.', '');
        $total_rental_charges = round($fetch_itinerary_plan_data['total_rental_charges']);
        $get_total_rental_charges = number_format($total_rental_charges, 2);
        $total_toll_charges = number_format($fetch_itinerary_plan_data['total_toll_charges'], 2);
        $total_parking_charges = number_format($fetch_itinerary_plan_data['total_parking_charges'], 2);
        $total_driver_charges = number_format($fetch_itinerary_plan_data['total_driver_charges'], 2);
        $total_permit_charges = number_format($fetch_itinerary_plan_data['total_permit_charges'], 2);
        $total_before_6_am_extra_time = $fetch_itinerary_plan_data['total_before_6_am_extra_time'];
        $total_after_8_pm_extra_time = $fetch_itinerary_plan_data['total_after_8_pm_extra_time'];
        $total_before_6_am_charges_for_driver = number_format($fetch_itinerary_plan_data['total_before_6_am_charges_for_driver'], 2);
        $total_before_6_am_charges_for_vehicle = number_format($fetch_itinerary_plan_data['total_before_6_am_charges_for_vehicle'], 2);
        $total_after_8_pm_charges_for_driver = number_format($fetch_itinerary_plan_data['total_after_8_pm_charges_for_driver'], 2);
        $total_after_8_pm_charges_for_vehicle = number_format($fetch_itinerary_plan_data['total_after_8_pm_charges_for_vehicle'], 2);
        $total_extra_kms_charge = round($fetch_itinerary_plan_data['total_extra_kms_charge']);
        $get_total_extra_kms_charge = number_format($total_extra_kms_charge, 2);

        $total_cost_of_vehicle =  $total_rental_charges +  $total_toll_charges +  $total_parking_charges +  $total_driver_charges +  $total_permit_charges + $total_before_6_am_charges_for_driver + $total_before_6_am_charges_for_vehicle +  $total_after_8_pm_charges_for_driver + $total_after_8_pm_charges_for_vehicle;
        $get_total_cost_of_vehicle = number_format($total_cost_of_vehicle, 2);


      endwhile;
    endif;




    $table_guest = '<table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                      <span style="font-weight:bold;font-size:16px;color:#232323;">Guest Details :</span>
                    </td>
                </tr>
                </table>';

    $pdf->writeHTML($table_guest, true, false, false, false, '');
    $table_guest = '';
    $guest_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                  <td>
                  <table cellspacing="0" cellpadding="8" border="0">
                    <tr>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Name:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $customer_name . '</span> 
                      </td>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Guest Age:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .  $customer_age . '</span> 
                      </td>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Email Id:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .   $email_id_data . '</span> 
                      </td>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Mobile No:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $primary_contact_data . '</span> 
                      </td>
                    </tr></table>
                    </td>
                </tr></table>';

    $pdf->writeHTML($guest_details, true, false, false, false, '');
    $guest_details = '';

    $table_confirmed = '<table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                       <span style="font-weight:bold;font-size:16px;color:#232323;">Confirmed By :</span>
                    </td>
                </tr>
                </table>';

    $pdf->writeHTML($table_confirmed, true, false, false, false, '');
    $table_confirmed = '';

    $confirm_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                  <td>
                  <table cellspacing="0" cellpadding="8" border="0">
                    <tr>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Name:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vehicle_confirmed_by . '</span> 
                      </td>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Email Id:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .   $vehicle_confirmed_email_id . '</span> 
                      </td>
                        <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Mobile No:</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vehicle_confirmed_mobile_no . '</span> 
                      </td>
                                  <td width="25%" style="border:4px solid #d3d3d3;">
                          <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Status</span><br/>
                            <span style="font-weight:bold; font-size:14px; ' . $booking_status_color . '">' . $vehicle_booking_status . '</span> 
                      </td>
                    </tr></table>
                  </td>
                </tr></table>';

    $pdf->writeHTML($confirm_details, true, false, false, false, '');
    $confirm_details = '';


    $vehicle_details_title = '<table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                       <span style="font-weight:bold;font-size:16px;color:#232323;">Vehicle Details :</span>
                    </td>
                </tr>
                </table>';

    $pdf->writeHTML($vehicle_details_title, true, false, false, false, '');
    $vehicle_details_title = '';

    $vehicle_details_basic .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="100%">
                        <table cellspacing="0" cellpadding="8" border="0">
                            <tr>
                                <td width="65%" style="border:4px solid #d3d3d3;">
                                    <br/>
                                    <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Booking ID : </span><span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_quote_ID . '</span>
                                    <div style="display:flex; align-items:center;">
                                        <img src="http://localhost/dvi_travels/head/assets/img/sedan.png" width="24px" height="24px"/>
                                        <span style="font-weight:bold; font-size:16px; color:#232323">' . $vehicle_type_title . ' - </span>
                                        <span style="font-weight:regular; font-size:12px; color:#808080">Count [' . $total_vehicle_qty . ']</span><br/>
                                        <span style="font-weight:regular; font-size:12px; color:#808080;">' . $vehicle_orign . '</span></div>
                                </td>
                                 <td width="35%" align="left" style="border:4px solid #d3d3d3;"><br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Vendor :</span><br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vendor_name . '</span> <br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Branch :</span><br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vendor_branch_name . '</span>
                                </td>
                            </tr></table>
                    </td>
                </tr></table>';

    $pdf->writeHTML($vehicle_details_basic, true, false, false, false, '');
    $vehicle_details_basic = '';


    $select_vehicle_daywise = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `time_limit_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_pickup_km`, `total_pickup_duration`, `total_drop_km`, `total_drop_duration`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `vendor_branch_id` = '$vendor_branch_id' and `vehicle_type_id` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_vehicle_daywise);

    if ($total_itinerary_plan_details_count > 0) :
      $daycount = 0;
      while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_vehicle_daywise)) :
        $daycount++;
        $itinerary_route_id = $fetch_itinerary_plan_data['itinerary_route_id'];
        $time_limit_id = $fetch_itinerary_plan_data['time_limit_id'];
        $time_limit_label = getTIMELIMIT($time_limit_id, 'get_title', '', '', '');
        $itinerary_plan_vendor_eligible_ID = $fetch_itinerary_plan_data['itinerary_plan_vendor_eligible_ID'];
        $itinerary_route_date = date('d M Y', strtotime($fetch_itinerary_plan_data['itinerary_route_date']));
        $total_running_km = $fetch_itinerary_plan_data['total_running_km'];
        $total_running_time = $fetch_itinerary_plan_data['total_running_time'];
        $total_siteseeing_km = $fetch_itinerary_plan_data['total_siteseeing_km'];
        $total_siteseeing_time = $fetch_itinerary_plan_data['total_siteseeing_time'];
        $total_travelled_km = $fetch_itinerary_plan_data['total_travelled_km'];
        $total_travelled_time = $fetch_itinerary_plan_data['total_travelled_time'];
        $travel_type = $fetch_itinerary_plan_data['travel_type'];
        $total_pickup_km = number_format($fetch_itinerary_plan_data['total_pickup_km'], 2);
        $total_pickup_duration =  formatTimeDuration($fetch_itinerary_plan_data['total_pickup_duration']);
        $total_drop_km = number_format($fetch_itinerary_plan_data['total_drop_km'], 2);
        $total_drop_duration =  formatTimeDuration($fetch_itinerary_plan_data['total_drop_duration']);
        $vehicle_rental_charges = number_format($fetch_itinerary_plan_data['vehicle_rental_charges'], 2);
        $vehicle_toll_charges = number_format($fetch_itinerary_plan_data['vehicle_toll_charges'], 2);
        $vehicle_parking_charges = number_format($fetch_itinerary_plan_data['vehicle_parking_charges'], 2);
        $vehicle_driver_charges = number_format($fetch_itinerary_plan_data['vehicle_driver_charges'], 2);
        $vehicle_permit_charges = number_format($fetch_itinerary_plan_data['vehicle_permit_charges'], 2);
        $before_6_am_extra_time = number_format($fetch_itinerary_plan_data['before_6_am_extra_time'], 2);
        $after_8_pm_extra_time = number_format($fetch_itinerary_plan_data['after_8_pm_extra_time'], 2);
        $before_6_am_charges_for_driver = number_format($fetch_itinerary_plan_data['before_6_am_charges_for_driver'], 2);
        $before_6_am_charges_for_vehicle = number_format($fetch_itinerary_plan_data['before_6_am_charges_for_vehicle'], 2);
        $after_8_pm_charges_for_driver = number_format($fetch_itinerary_plan_data['after_8_pm_charges_for_driver'], 2);
        $after_8_pm_charges_for_vehicle = number_format($fetch_itinerary_plan_data['after_8_pm_charges_for_vehicle'], 2);
        $total_vehicle_amount = number_format($fetch_itinerary_plan_data['total_vehicle_amount'], 2);

        $get_total_outstation_trip = get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip');

        $get_total_running_km = number_format($total_running_km, 2);
        $get_total_running_time = formatTimeDuration($total_running_time);
        $get_total_siteseeing_km = number_format($total_siteseeing_km, 2);
        $get_total_siteseeing_time = formatTimeDuration($total_siteseeing_time);
        $get_total_travelled_km = number_format($total_travelled_km, 2);
        $get_total_travelled_time = formatTimeDuration($total_travelled_time);

        if ($travel_type == 1) :
          $travel_type_label = 'Local';
        elseif ($travel_type == 2) :
          $travel_type_label = 'Outstation';
        else :
          $travel_type_label = '--';
        endif;

        $outstation_KM_LIMIT = getKMLIMIT($vendor_vehicle_type_id, 'get_kms_limit', $vendor_id);

        if ($outstation_KM_LIMIT != '') :
          $outstation_KM_LIMIT_TITLE  = $outstation_KM_LIMIT . 'KM';
        else :
          $outstation_KM_LIMIT_TITLE  = 'NA';
        endif;


        if ($travel_type == 1) :
          $get_travel_type =  getTIMELIMIT($vendor_vehicle_type_id, 'get_local_kms_limit', $vendor_id);
        elseif ($travel_type == 2) :
          $get_travel_type = $outstation_KM_LIMIT_TITLE;
        endif;
        if ($logged_user_level == 1) :
          $vehicle_details .= ' <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td width="100%">
                                <table cellspacing="0" cellpadding="8" border="0">
                                    <tr>
                                        <td width="23%" style="border:4px solid #d3d3d3;">
                                            <br/>
                                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Day ' . $daycount . ' : </span> <br/>
                                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $itinerary_route_date . '</span>
                                        </td>
                                        <td width="27%" style="border:4px solid #d3d3d3;">
                                            <br/>
                                          <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Trip Type : </span> <br/>
                                          <span style="font-weight:semi-bold; font-size:12px; color:#232323">' . $travel_type_label . ' [' . $get_travel_type . ']</span>
                                        </td>
                                         <td width="25%" align="left" style="border:4px solid #d3d3d3;"><br/>
                                       <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Total KM : </span> <br/>
                                       <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $get_total_travelled_km . ' KM</span>
                                        </td>
                                         <td width="25%" align="left" style="border:4px solid #d3d3d3;"><br/>
                                       <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Perday Amount: </span> <br/>
                                       <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '</span>
                                        </td>
                                    </tr>
                                    </table>
                                  </td>
                                  </tr>
                              </table> ';
        elseif ($logged_agent_id != '' &&  $logged_agent_id != '0'):
          $vehicle_details .= ' <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                <td width="100%">
                                        <table cellspacing="0" cellpadding="8" border="0">
                                            <tr>
                                                <td width="33%" style="border:4px solid #d3d3d3;">
                                                    <br/>
                                                    <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Day ' . $daycount . ' : </span> <br/>
                                                    <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $itinerary_route_date . '</span>
                                                </td>
                                                <td width="34%" style="border:4px solid #d3d3d3;">
                                                    <br/>
                                                  <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Trip Type : </span> <br/>
                                                  <span style="font-weight:semi-bold; font-size:12px; color:#232323">' . $travel_type_label . ' [' . $get_travel_type . ']</span>
                                                </td>
                                                 <td width="33%" align="left" style="border:4px solid #d3d3d3;"><br/>
                                               <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Total KM : </span> <br/>
                                               <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $get_total_travelled_km . ' KM</span>
                                                </td>
                                            </tr>
                                            </table>
                                          </td>
                                          </tr>
                                      </table> ';
        endif;

      endwhile;
      $pdf->writeHTML($vehicle_details, true, false, false, false, '');
      $vehicle_details = '';

    endif;

    if ($logged_user_level == 1) :
      $overallsummary .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
      <td>
       <table cellspacing="0" cellpadding="8" border="0">
         <tr>
            <td width="20%" style="border:4px solid #d3d3d3;">
              <br/>
                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Rental Charges</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $get_total_rental_charges . '</span> 
           </td>
            <td width="20%" style="border:4px solid #d3d3d3;">
              <br/>
                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Toll Charges</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_toll_charges . '</span> 
           </td>
            <td width="20%" style="border:4px solid #d3d3d3;">
              <br/>
                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Parking Charges</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_parking_charges . '</span> 
           </td>
            <td width="20%" style="border:4px solid #d3d3d3;">
              <br/>
                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Driver Charges</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_driver_charges . '</span> 
           </td>
            <td width="20%" style="border:4px solid #d3d3d3;">
              <br/>
                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Permit Charges</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_permit_charges . '</span> 
           </td>
        </tr>
        <tr>
          <td width="60%"  style="border:4px solid #d3d3d3;">
             <br/>
             <span style="font-weight:bold; font-size:12px; color:#232323;">TOTAL COST OF VEHICLE</span>
          </td>
           <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
          </td>
          <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
             <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $get_total_cost_of_vehicle  . '</span> 
          </td>
        </tr>
        <tr>
          <td width="60%"  style="border:4px solid #d3d3d3;">
             <br/>
             <span style="font-weight:bold; font-size:12px; color:#232323;">TOTAL EXTRA KM</span>
          </td>
             <td width="20%"  style="border:4px solid #d3d3d3;">
                <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_extra_kms . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $extra_km_rate . '</span> 
          </td>
          <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
             <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $get_total_extra_kms_charge . '</span> 
          </td>
        </tr>
        <tr>
          <td width="60%"  style="border:4px solid #d3d3d3;">
             <br/>
             <span style="font-weight:bold; font-size:12px; color:#232323;">GRAND TOTAL (' . $total_vehicle_qty . ' x <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $get_vehicle_grand_total . ')</span>
          </td>
             <td width="20%"  style="border:4px solid #d3d3d3;">
                <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
          </td>
          <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
             <br/>
              <span style="font-weight:bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $get_vehicle_grand_total . '</span> 
          </td>
        </tr>
        </table>
      </td>
    </tr>
    </table>';
    endif;

    $pdf->writeHTML($overallsummary, true, false, false, false, '');

    $overallsummary = '';



  endwhile;


  $table_hotspot_details_title = '<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td>
       <span style="font-weight:bold;font-size:16px;color:#232323;">Hotspot Details:</span>
    </td>
  </tr>
  </table>';

  $pdf->writeHTML($table_hotspot_details_title, true, false, false, false, '');

  $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
  $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
  if ($total_itinerary_plan_route_details_count > 0) :
    $last_day_ending_location = NULL;
    while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
      $itineary_route_count++;
      $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
      $location_name = $fetch_itinerary_plan_route_data['location_name'];
      $location_parts = explode(",", $location_name);
      $location_value = trim($location_parts[0]);
      $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
      $itinerary_route_date_format = date('D, M d, Y', strtotime($itinerary_route_date));
      $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
      $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
      $next_visiting_parts = explode(",", $next_visiting_location);
      $next_visiting_value = trim($next_visiting_parts[0]);
      $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
      $route_start_time_format = date('h:i A', strtotime($route_start_time));
      $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];
      $route_end_time_format = date('h:i A', strtotime($route_end_time));
      $day_wise_kms = number_format(get_ASSIGNED_VEHICLE_ITINEARY_PLAN_DAYWISE_KM_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_total_kms'), 2);
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



      // Initialize the table for terms and conditions content
      $table_hotspot_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                  <td>
                  <table cellspacing="0" cellpadding="8" border="0">
                    <tr>
                        <td width="100%" style="border:4px solid #d3d3d3;">
                        <br/>
                          <span style="font-weight:bold; font-size:12px; color:#232323;">Day ' . $itineary_route_count . ' - ' . $itinerary_route_date_format . ' (' . $route_start_time_format . ' - ' . $route_end_time_format . ') - ' . $location_value . ' to ' . $next_visiting_value . ' - (' . $day_wise_kms . ' KM) </span>
                      </td>
                       
                    </tr>';

      if ($get_via_route_details_without_format != 'No Via Route Added') :
        $table_hotspot_details .= '<tr>
                        <td width="100%" style="border:4px solid #d3d3d3;">
                        <br/>
                          <span style="font-weight:semi-bold; font-size:12px; color:#232323;">Via Route: ' . $get_via_route_details_without_format . ' </span>
                      </td>
                       
                    </tr>';
      endif;

      $pricebook_true = check_guide_pricebook($itinerary_route_date, $total_pax_count);

      if ($guide_for_itinerary == 0 &&  $pricebook_true) :
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
            $guide_language_details = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
            $guide_slot_details = getSLOTTYPE($guide_slot, 'label');
          endwhile;
          $table_hotspot_details .= '<tr>
                            <td width="100%" style="border:4px solid #d3d3d3;">
                            <br/>
                              <span style="font-weight:semi-bold; font-size:12px; color:#232323;">Guide Language: ' . $guide_language_details . ' - Slot Timing - ' . $guide_slot_details . ' </span>
                          </td>
                           
                        </tr>';
        endif;
      endif;

      $select_itinerary_plan_route_hotspot_availability_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `status` = '1' AND `item_type` IN ('5','6') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
      $total_itinerary_plan_route_hotspot_availability_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_availability_query);
      $fetch_hotspot_availability = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_availability_query);
      $get_route_last_hotspot_ID = $fetch_hotspot_availability['route_hotspot_ID'];
      $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
      $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
      $itineary_route_hotspot_count = 0;
      $previous_hotspot_name = $location_name;
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
          $hotspot_gallery_name = getHOTSPOT_GALLERY_DETAILS($hotspot_ID, 'hotspot_gallery_name');

          $itinerary_break_time = getGLOBALSETTING('itinerary_break_time');
          $hotspot_start_time_details = date('h:i A', strtotime($hotspot_start_time));
          $hotspot_end_time_details = date('h:i A', strtotime($hotspot_end_time));
          $hotspot_traveling_time_format =  formatTimeDuration($hotspot_traveling_time);
          $next_visiting_location = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location');



          if ($hotspot_gallery_name) :
            $hotspot_gallery_name = $hotspot_gallery_name;
          else :
            $hotspot_gallery_name = 'no-preview.png';
          endif;

          if ($item_type == 1):
            $table_hotspot_details .= '<tr>
                        <td width="100%" style="border:4px solid #d3d3d3;">
                        <br/>
                          <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $itinerary_break_time . ' - ' . $hotspot_start_time_details . ' - ' . $hotspot_end_time_details . ' </span>
                      </td>
                       
                    </tr>';
          endif;

          if ($item_type == 3) :
            $from_hotspot_name = $previous_hotspot_name; // Store the "from" hotspot name
            $to_hotspot_name = $hotspot_name; // Store the "to" hotspot name 
            $table_hotspot_details .= '<tr>
            <td width="100%" style="border:4px solid #d3d3d3;">
            <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;">Travelling from <b>' . $from_hotspot_name . '</b> to <b>' . $to_hotspot_name . '</b> - ' . $hotspot_start_time_details . ' - ' . $hotspot_end_time_details . ' [Distance: ' . $hotspot_travelling_distance . ' KM, Duration: ' . $hotspot_traveling_time_format . '] </span>
              </td>
              
            </tr>';
          endif;

          if ($item_type == 4 && $hotspot_ID != 0) :
            $previous_hotspot_name = $hotspot_name;
            $table_hotspot_details .= '<tr>
            <td width="100%" style="border:4px solid #d3d3d3;">
            <br/>
              <span style="font-weight:semi-bold; font-size:12px; color:#232323;"> ' . $hotspot_start_time_details . ' - ' . $hotspot_end_time_details . ' - ' . $hotspot_traveling_time_format . ' - <b>' . $hotspot_name . '</b> </span>
             </td>
           
             </tr>';

            $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`,ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`,ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
            $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
            if ($total_hotspot_activity_num_rows_count > 0) :
              $activitycount = 0;
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
                $activity_rating = $fetch_hotspot_activity_data['activity_rating'];
                $activity_description = $fetch_hotspot_activity_data['activity_description'];
                $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');

                $get_activity_start_time = date('h:i A', strtotime($activity_start_time));
                $get_activity_end_time = date('h:i A', strtotime($activity_end_time));

                $table_hotspot_details .= '<tr>
                      <td width="100%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;">Activity #' . $activitycount . ' : ' . $activity_title . ' ' . $get_activity_start_time . ' - ' . $get_activity_end_time . '</span>
                       </td>
                       </tr>';
              endwhile;
            endif;

            $table_hotspot_details .= '<tr>
                <td width="100%" style="border:4px solid #d3d3d3;">
                <br/>
                  <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $hotspot_description . '</span>
                 </td>
               
                 </tr>';

          endif;

        endwhile;
      endif;

      if ($item_type == 7 && $total_itinerary_plan_route_hotspot_details_count == $itineary_route_hotspot_count):
        $table_hotspot_details .= '<tr>
                    <td width="100%" style="border:4px solid #d3d3d3;">
                    <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">Return to ' . $next_visiting_location . ' </span>
                  </td>
                   
                </tr>';
      endif;


      $table_hotspot_details .= '</table>
                    </td>
                </tr></table>';

      $pdf->writeHTML($table_hotspot_details, true, false, false, false, '');
      $table_hotspot_details = '';
    endwhile;

  endif;


  $table_terms_condition = '<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
     <span style="font-weight:bold;font-size:16px;color:#232323;">Terms and Condition:</span>
  </td>
</tr>
</table>';

  $pdf->writeHTML($table_terms_condition, true, false, false, false, '');


  // Initialize the table for terms and conditions content
  $table_terms_condition = '<table cellspacing="0" cellpadding="0" border="0">
      <tr> 
          <td>
              <table cellspacing="0" cellpadding="10" border="1">
                  <tr>
                      <td>
                          <span style="font-size: 10pt; line-height: 1.3;">
                              ' . geTERMSANDCONDITION('get_vehicle_voucher_terms_n_condtions') . '
                          </span>
                      </td>   
                      </tr>
                  </table>
          </td>
      </tr></table>';


  $pdf->writeHTML($table_terms_condition, true, false, false, false, '');
  $pdf->Output('itinerary-voucher - ' . $itinerary_quote_ID . '.pdf', 'I');

endif;
