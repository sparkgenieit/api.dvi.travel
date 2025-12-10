<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

class TCPDFCustom extends TCPDF
{
    public function Header()
    {
        // Path to the background image
        $backgroundImage = 'http://localhost/dvi_travels/head/assets/img/pattern.jpg';

        // Set the background image
        $this->Image($backgroundImage, 0, 0, 210, 20, '', '', '', false, 300, '', false, false, 0);

        // Set font for "Hotel Voucher" text
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->MultiCell(60, 3, "Vehicle Voucher", 0, 'L', false, 1, 10, 6);

        $this->SetFont('helvetica', 'B', 7);
        $this->SetTextColor(255, 255, 255); // Set text color to white for visibility
        $this->MultiCell(80, 3, 'QUOTE ID', 0, 'R', false, 1, 120, 4);

        // Set font for company name and position it
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(255, 255, 255); // Set text color to white for visibility
        $this->MultiCell(80, 3, 'CQ-DVI202407-194', 0, 'R', false, 1, 120, 8);
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
        $this->Cell(0, 10, 'vsr@dvi.co.in', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 25, PDF_MARGIN_RIGHT); // Adjust top margin to avoid overlap with header
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a new page for each record
$pdf->AddPage();

$confirmed_itinerary_plan_ID = $_GET['confirmid'];
$itinerary_plan_ID = $_GET['id'];

$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `location_id`, `itinerary_quote_ID`, `total_adult`, `total_children`, `total_infants`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `food_type`, `special_instructions` FROM `dvi_confirmed_itinerary_plan_details` WHERE  `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
    endwhile;
endif;

$select_voucher_plan_details = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vendor_id`, `vehicle_id`, `vendor_branch_id`, `vehicle_confirmed_by`, `vehicle_confirmed_email_id`, `vehicle_confirmed_mobile_no`, `invoice_to`, `vehicle_booking_status`, `vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
        $vehicle_type_id = $fetch_itinerary_plan_data['vehicle_type_id'];
        $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $vendor_id = $fetch_itinerary_plan_data['vendor_id'];
        $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
        $vehicle_id = $fetch_itinerary_plan_data['vehicle_id'];
        $vendor_branch_id = $fetch_itinerary_plan_data['vendor_branch_id'];
        $vendor_branch_name = getBranchLIST($vendor_branch_id, 'branch_label');
        $vehicle_confirmed_by = $fetch_itinerary_plan_data['vehicle_confirmed_by'];
        $vehicle_confirmed_email_id = $fetch_itinerary_plan_data['vehicle_confirmed_email_id'];
        $vehicle_confirmed_mobile_no = $fetch_itinerary_plan_data['vehicle_confirmed_mobile_no'];
        $vehicle_booking_status = $fetch_itinerary_plan_data['vehicle_booking_status'];
        $vehicle_voucher_terms_condition = $fetch_itinerary_plan_data['vehicle_voucher_terms_condition'];
        $get_vehicle_voucher_terms_condition =  htmlspecialchars_decode($vehicle_voucher_terms_condition, ENT_QUOTES);
    endwhile;

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
endif;

// Query to get the records from the database
$select_vehiclevoucher_plan_details = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `vehicle_orign`, `vehicle_count`, `total_kms`, `total_outstation_km`, `total_time`, `total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_extra_time`, `total_after_8_pm_extra_time`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle`, `extra_km_rate`, `total_allowed_kms`, `total_extra_kms`, `total_extra_kms_charge`, `vehicle_gst_type`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `vehicle_grand_total` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itineary_plan_assigned_status` = '1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_vehiclevoucher_plan_details);
if ($total_itinerary_plan_details_count > 0) :
    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_vehiclevoucher_plan_details)) :
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
        $vehicle_grand_total = number_format($fetch_itinerary_plan_data['vehicle_grand_total'], 2);
        $vendor_margin_gst_amount = number_format($fetch_itinerary_plan_data['vendor_margin_gst_amount'], 2);
        $vendor_margin_amount = number_format($fetch_itinerary_plan_data['vendor_margin_amount'], 2);
        $vehicle_gst_amount = number_format($fetch_itinerary_plan_data['vehicle_gst_amount'], 2);
        $total_extra_kms = number_format($fetch_itinerary_plan_data['total_extra_kms'], 0, '.', '');
        $total_kms =  number_format($fetch_itinerary_plan_data['total_kms'], 0, '.', '');
        $total_rental_charges = number_format($fetch_itinerary_plan_data['total_rental_charges'], 2);
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
        $total_extra_kms_charge = number_format($fetch_itinerary_plan_data['total_extra_kms_charge'], 2);

        $total_cost_of_vehicle = number_format($total_rental_charges +  $total_toll_charges +  $total_parking_charges +  $total_driver_charges +  $total_permit_charges + $total_before_6_am_charges_for_driver + $total_before_6_am_charges_for_vehicle +  $total_after_8_pm_charges_for_driver + $total_after_8_pm_charges_for_vehicle, 2);

      

    endwhile;

    $company_name = getGLOBALSETTING('company_name');
    $company_contact_no = getGLOBALSETTING('company_contact_no');
    $company_email_id = getGLOBALSETTING('company_email_id');
    $company_address = getGLOBALSETTING('company_address');
    $company_pincode = getGLOBALSETTING('company_pincode');

    // Company Details
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

    $select_voucher_plan_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_pickup_km`, `total_pickup_duration`, `total_drop_km`, `total_drop_duration`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `vendor_branch_id` = '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_voucher_plan_details);

    $counter = 0;
    $itemsPerPage = 2;

    if ($total_itinerary_plan_details_count > 0) :
        while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_voucher_plan_details)) :
            $daycount++;
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

            if($travel_type == '1'):
                $travel_type_label = 'Local';
            elseif($travel_type == '2'):
                $travel_type_label = 'Outstation';
            endif;

            $vehicle_details = '<table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td width="70%">
                    <table cellspacing="0" cellpadding="8" border="0">
                        <tr>
                            <td width="100%" style="border:4px solid #d3d3d3;">
                                <br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Booking ID : </span><span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_quote_ID . '</span> |
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Day ' . $daycount . ' : </span><span style="font-weight:bold; font-size:12px; color:#232323;">' . $itinerary_route_date . '</span>
                                <div style="display:flex; align-items:center;">
                                    <img src="http://localhost/dvi_travels/head/assets/img/sedan.png" width="24px" height="24px"/>
                                    <span style="font-weight:bold; font-size:16px; color:#232323">' . $vehicle_type_title . ' - </span>
                                    <span style="font-weight:regular; font-size:12px; color:#808080">' . $travel_type_label .' [8Hrs - 80 KM] - Count ['.$total_vehicle_qty.']</span><br/>
                                    <span style="font-weight:regular; font-size:12px; color:#808080;">' . $vehicle_orign . '</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td width="22%" style="border:4px solid #d3d3d3;">
                                <br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Local KM:</span><br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $get_total_running_km . ' KM<br/> ' . $get_total_running_time . '</span>
                            </td>
                            <td width="22%" style="border:4px solid #d3d3d3;">
                                <br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Outstation KM:</span><br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $get_total_siteseeing_km . ' KM<br/> ' . $get_total_siteseeing_time . '</span>
                            </td>
                            <td rowspan="3" width="56%" style="border:4px solid #d3d3d3;">
                                <br/>
                                <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Cost Details:</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Rental Charges : </span> <span style="font-weight:regular; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_rental_charges . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Toll Charges : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_toll_charges . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Parking Charges : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_parking_charges . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Driver Charges : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_driver_charges . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Permit Charges : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_permit_charges . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Before 6AM Charges for Driver : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_driver . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">Before 6AM Charges for Vendor : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $before_6_am_charges_for_vehicle . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">After 8PM Charges for Driver : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_driver . '</span>
                                <br/>
                                <span style="font-weight:semi-bold; font-size:12px; color:#808080;">After 8PM Charges for Vendor : </span> <span style="font-weight:regular; font-size:12px; color:#232323"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $after_8_pm_charges_for_vehicle . '</span>
                                <br/>
                                <span style="font-weight:bold; font-size:12px; color:#232323;">Total Amount : </span> <span style="font-weight:bold; font-size:12px; color:#001255"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_vehicle_amount . '</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="44%" align="left" style="border:4px solid #d3d3d3;"><br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Total KM :</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $get_total_travelled_km . ' KM, ' . $get_total_travelled_time . '</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="44%" align="left" style="border:4px solid #d3d3d3;"><br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Vendor :</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vendor_name . '</span> <br/>
                            <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Branch :</span><br/>
                            <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $vendor_branch_name . '</span>
                            </td>
                        </tr>
                    </table>
                </td>';

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

            $vehicle_details .= '<td width="30%" style="border:4px solid #d3d3d3;">
            <table cellspacing="0" cellpadding="18.3" border="0">
                <br/>
                <span style="font-size:12px;color:#8e8a8a;font-weight:regular;">Guest Details: </span><br/>
                <span style="font-weight:bold;font-size:16px;color:#232323;">' . $customer_name . '</span>
                <br/><span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $customer_age . '</span>
                <br/><span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $primary_contact_data . '</span>
                <br/><span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $email_id_data . '</span>
                <br/><br/>
                <span style="font-weight:bold;font-size:16px;' . $booking_status_color . '">' . $vehicle_booking_status . '</span>
                <br/><br/>
                <hr style="color: #d3d3d3; height: 4px; width=500px;" />
                <span style="font-size:12px;color:#8e8a8a;font-weight:regular;">Confirmed by Details:</span><br/>
                <span style="font-weight:bold;font-size:16px;color:#232323;">' . $vehicle_confirmed_by . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $vehicle_confirmed_email_id . '</span><br/><span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $vehicle_confirmed_mobile_no . '</span>
            </table>
            </td>
            </tr>
            </table>';

            // Add vehicle details to $table_vehicle_details
            $table_vehicle_details .= $vehicle_details;

            // Increment the counter
            $counter++;

            // Check if we have reached the limit of items per page
            if ($counter % $itemsPerPage == 0) {
                // Combine company details and vehicle details
                $page_content = $table_company_details . $table_vehicle_details;

                // Output the HTML content for the current page
                $pdf->writeHTML($page_content, true, false, false, false, '');

                // Reset $table_vehicle_details for the next set of records
                $table_vehicle_details = '';

                // Add a new page
                $pdf->AddPage();
            } else {
                // Add a spacer row if it's not the last item on the page
                $table_vehicle_details .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td height="20"></td>
                    </tr>
                </table>';
            }
        endwhile;

        // Output remaining items if any
        if (!empty($table_vehicle_details)) {
            // Combine company details and vehicle details for the last page
            $page_content = $table_company_details . $table_vehicle_details;
            $pdf->writeHTML($page_content, true, false, false, false, '');
        }


        $overallsummary .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
              <td>
               <table cellspacing="0" cellpadding="8" border="0">
                 <tr>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Total Days</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $daycount . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Rental Charges</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_rental_charges . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Toll Charges</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_rental_charges . '</span> 
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
                </tr>
                 <tr>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">Permit Charges</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_permit_charges . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">6AM Charges(D)</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_before_6_am_charges_for_driver . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">6AM Charges(V)</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_before_6_am_charges_for_vehicle . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">8PM Charges(D)</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_after_8_pm_charges_for_driver . '</span> 
                   </td>
                    <td width="20%" style="border:4px solid #d3d3d3;">
                      <br/>
                        <span style="font-weight:regular; font-size:12px; color:#8e8a8a;">8PM Charges(V)</span><br/>
                        <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_after_8_pm_charges_for_vehicle . '</span> 
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
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_cost_of_vehicle  . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Pickup KM</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_pickup_km . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Pickup Duration</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_pickup_duration . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Drop KM</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' .  $total_drop_km  . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Drop Duration</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_drop_duration . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Used KM</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_kms . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Allowed KM (Outstaion KM Only)</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $outstation_allowed_km_per_day . ' * ' . $get_total_outstation_trip  . '</span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_allowed_kms . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Total Extra KM</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $total_extra_kms . ' * <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $extra_km_rate . '</span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $total_extra_kms_charge . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Subtotal</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_total_amount . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">GST ('.$vehicle_gst_percentage.'%)</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_gst_amount . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Vendor Margin ('.$vendor_margin_percentage.'%)</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vendor_margin_amount . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:semi-bold; font-size:12px; color:#8e8a8a;">Vendor Margin Service Tax ('.$vendor_margin_gst_percentage.'%)</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vendor_margin_gst_amount . '</span> 
                  </td>
                </tr>
                <tr>
                  <td width="60%"  style="border:4px solid #d3d3d3;">
                     <br/>
                     <span style="font-weight:bold; font-size:12px; color:#232323;">GRAND TOTAL ('. $total_vehicle_qty .' x <span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_grand_total . ')</span>
                  </td>
                     <td width="20%"  style="border:4px solid #d3d3d3;">
                        <br/>
                      <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span> 
                  </td>
                  <td width="20%"  style="border:4px solid #d3d3d3; text-align:right;">
                     <br/>
                      <span style="font-weight:bold; font-size:12px; color:#232323;"><span style="font-family:DejaVuSans;">' . general_currency_symbol . '</span> ' . $vehicle_grand_total . '</span> 
                  </td>
                </tr>
                </table>
              </td>
            </tr>
            </table>';

        $pdf->writeHTML($overallsummary, true, false, false, false, '');

        $table_terms_condition = '<table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
               <span style="font-weight:bold;font-size:16px;color:#232323;">Terms and Condition:</span>
            </td>
        </tr>
        </table>';
        

        // Initialize the table for terms and conditions content
        $table_terms_condition = '<table cellspacing="0" cellpadding="0" border="0">
                <tr> 
                    <td>
                        <table cellspacing="0" cellpadding="10" border="1">
                            <tr>
                                <td>
                                    <span style="font-size: 10pt; line-height: 1.3;">
                                        ' . $get_vehicle_voucher_terms_condition . '
                                    </span>
                                </td>   
                                </tr>
                            </table>
                    </td>
                </tr></table>';


        $pdf->writeHTML($table_terms_condition, true, false, false, false, '');

        // Output combined PDF document
        $pdf->Output('itinerary-voucher - ' . $itinerary_quote_ID . '.pdf', 'I');

    endif;
endif;
