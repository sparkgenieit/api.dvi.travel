<?php

include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');
require_once('phpqrcode/qrlib.php');

$itinerary_plan_ID = $_GET['id'];


class TCPDFCustom extends TCPDF
{
    public function Header()
    {
        $invoice_type = $_GET['type'];
        $company_logo = getGLOBALSETTING('company_logo');
        $company_logo_format = BASEPATH . './uploads/logo/' . $company_logo;

        if ($invoice_type == 'tax'):
            $invoice_title = 'Tax Invoice';
        elseif ($invoice_type == 'proforma'):
            $invoice_title = 'Proforma Invoice';
        endif;

        // Hotel Voucher Title (Fixed Position)
        $this->SetFont('helvetica', 'B', 16);
        $this->SetXY(75, 10); // Adjust X and Y to keep it in place
        $this->Cell(60, 10, $invoice_title, 0, 0, 'C');
        $this->Image($company_logo_format, 177, 3, 27); // X = 160 (Right Side), Y = 5, Width = 40
    }
}

$agent_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_id');
$travel_expert_id = getAGENT_details($agent_ID, '', 'travel_expert_id');
$travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
$itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
$itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
// Create new PDF document
$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('AGENT INVOICE');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');

// Set default header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(6, 30, 6); // Adjust top margin to avoid overlap with header
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a new page
$pdf->AddPage();

$company_name = getGLOBALSETTING('company_name');
$company_address = getGLOBALSETTING('company_address');
$company_pincode = getGLOBALSETTING('company_pincode');
$company_gstin_no = getGLOBALSETTING('company_gstin_no');
$company_cin = getGLOBALSETTING('company_cin');
$company_email_id = getGLOBALSETTING('company_email_id');
$company_contact_no = getGLOBALSETTING('company_contact_no');
$company_gstin_no_two_digits = substr($company_gstin_no, 0, 2);
$company_gstin_state_name = getStateNameFromGSTCode($company_gstin_no_two_digits);
$agent_gstin_no = get_AGENT_CONFIG_DETAILS($agent_ID, 'invoice_gstin_no');
$agent_gstin_no_two_digits = substr($agent_gstin_no, 0, 2);
$agent_gstin_state_name = getStateNameFromGSTCode($agent_gstin_no_two_digits);
$trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
$arrival_date_and_time =  date('d M, Y h:i A', strtotime(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'arrival_date_and_time')));
$departure_date_and_time =  date('d M, Y h:i A', strtotime(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'departure_date_and_time')));

$arrival_date_and_time = ($arrival_date_and_time == '01 Jan, 1970 05:30 AM') ? '' : ', ' . $arrival_date_and_time;
$departure_date_and_time = ($departure_date_and_time == '01 Jan, 1970 05:30 AM') ? '' : ', ' .  $departure_date_and_time;



$table_agent_details .= '
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
        <td width="53%" valign="top" style="border:2px solid #d3d3d3;">
        <table cellspacing="0" cellpadding="6" border="0" width="100%">
        <tr>
            <td><span style="font-weight:bold;font-size:14px;">Seller:</span><br/>
                <span style="font-weight:bold;font-size:12px;">M/s ' . $company_name . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;color:#232323;">Address: ' . $company_address . ' - ' . $company_pincode . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">GSTIN/UIN: ' . $company_gstin_no . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">State Name : ' . $company_gstin_state_name . ', Code : ' . $company_gstin_no_two_digits . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">CIN: ' . $company_cin . '</span><br/>
                  <span style="font-weight:semi-bold;font-size:12px;">Email Id: ' . $company_email_id . '</span><br/>
                    <span style="font-weight:semi-bold;font-size:12px;">Contact Number: ' . $company_contact_no . '</span>
            </td>
        </tr>
        </table>
        </td>

        <td width="47%" valign="top" style="border:2px solid #d3d3d3;"><table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Invoice No</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID') . '</span>
            </td>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Dated</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . date('d-M-Y', strtotime($trip_start_date_and_time)) . '</span>
            </td>
        </tr>
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Delivery Note</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID') . '</span>
            </td>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Dispatch Doc No</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span>
            </td>
        </tr>
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Travel Expert</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . $travel_expert_name . '</span>
            </td>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Dispatched through</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span>
            </td>
        </tr>
        </table>
        </td>
        </tr>
        <tr>
        <td width="53%" valign="top" style="border:2px solid #d3d3d3;">
        <table cellspacing="0" cellpadding="6" border="0" width="100%">
        <tr>
            <td><span style="font-weight:bold;font-size:14px;">Buyer:</span><br/>
                <span style="font-weight:bold;font-size:12px;color:#232323;">M/s ' . get_AGENT_CONFIG_DETAILS($agent_ID, 'company_name') . '</span>
                <br/>
                <span style="font-weight:semi-bold;font-size:12px;">Address: ' . get_AGENT_CONFIG_DETAILS($agent_ID, 'invoice_address') . '</span>
                <br/>
                <span style="font-weight:semi-bold;font-size:12px;">GSTIN/UIN : ' . get_AGENT_CONFIG_DETAILS($agent_ID, 'invoice_gstin_no') . '</span><br/>
                   <span style="font-weight:semi-bold;font-size:12px;">State Name : ' . $agent_gstin_state_name . ', Code : ' . $agent_gstin_no_two_digits . '</span>
            </td>
        </tr>
        </table>
        </td>
        <td width="47%" valign="top" style="border:2px solid #d3d3d3;"><table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <td width="100%"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Guest Name:</span>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Contact Number:</span>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no') . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Arrival</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'arrival_place') . '' . $arrival_date_and_time  . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Departure</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'departure_place') . '' . $departure_date_and_time  . '</span>
            </td>
        </tr>

        </table>
        </td>
        </tr>
    </table>';
if ($itinerary_preference == 3):
    $table_agent_details .= '<table cellspacing="0" cellpadding="4" border="0" style="border:2px solid #d3d3d3;">
        <tr>
            <th style="font-weight: bold;font-size:13px;width: 6%;border:2px solid #d3d3d3;">SI No.</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 60%;border:2px solid #d3d3d3;">Particulars</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 13%;border:2px solid #d3d3d3;">HSN/SAC</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 21%;border:2px solid #d3d3d3;">Amount</th>
        </tr>
        <tr>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">1</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                <b>HOTEL BOOKING CHARGES ONLY A/C (GST PAID)</b><br>';

    $select_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_route_date`, `itinerary_route_location`, `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_GUEST_DETAILS:" . sqlERROR_LABEL());
    $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details);

    if ($total_hotel_details_count > 0) :
        while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
            $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
            $itinerary_route_date_format = date('M d, Y', strtotime($itinerary_route_date));
            $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
            $hotel_id = $fetch_hotel_data['hotel_id'];
            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
            $table_agent_details .= ' 
                    <span style="font-size:10px;">' . $itinerary_route_date_format . ' - ' . $itinerary_route_location . ' - ' . $hotel_name . '</span><br>';
        endwhile;
    endif;

    $total_payable_cost_hotel = getITINEARYCONFIRMED_WITHOUTMARGIN_COST_DETAILS($itinerary_plan_ID, $select_type, 'total_payable_cost_hotel');
    $total_hotel_margin_rate = getHOTEL_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE');
    $total_hotel_margin_rate_tax = getHOTEL_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT');

    $total_cost_margin_vehicle = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_cost_margin_vehicle', '');
    $total_margingst_cost_vehicle = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_margingst_cost_vehicle', '');
    $total_vehiclegst_cost = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehiclegst_cost', '');

    $total_guide_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount');
    $total_hotspot_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
    $total_activity_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
    $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');

    $guide_amount_lable = ($total_guide_amount != 0) ? 'GUIDE COST +' : '';
    $hotspot_amount_lable = ($total_hotspot_amount != 0) ? 'HOTSPOT COST +' : '';
    $activity_amount_lable = ($total_activity_amout != 0) ? 'ACTIVITY COST  ' : '';

    $total_component_amount = $total_guide_amount + $total_hotspot_amount + $total_activity_amout + $agent_margin_charges;
    $total_margingst_cost_service = getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_service');

    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $GST_label = 'CGST, SGST';
    else:
        $GST_label = 'IGST';
    endif;

    $table_agent_details .= '</td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('hotel_hsn') . '</td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_payable_cost_hotel), 2) . '</td>
                        </tr>
                        <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>' . $GST_label . ' SALES @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') . '% ACCOMMODATION SERVICES</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_hotel_margin_rate), 2) . '</td>
                        </tr>';
    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $max_hotel_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') / 2;
        $hotel_margin_rate_tax = $total_hotel_margin_rate_tax / 2;
        $table_agent_details .= '<tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT CGST @ ' . $max_hotel_margin_gst_percentage . '%</b><br>
                                 <b>OUT PUT SGST @ ' . $max_hotel_margin_gst_percentage . '%</b><br>
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                            ' . number_format(($hotel_margin_rate_tax), 2) . '<br>' . number_format(($hotel_margin_rate_tax), 2) . '<br></td>
                        </tr>';
    else:
        $table_agent_details .= '<tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                            <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') . '%</b><br>
                        </td>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_hotel_margin_rate_tax), 2) . '</td>
                    </tr>';
    endif;
    $table_agent_details .= '<tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">2</td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>' . $GST_label . ' SALES @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') . '% TRANSPORTATION SERVICES</b><br>
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('vehicle_hsn') . '</td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_cost_margin_vehicle), 2) . '</td>
                        </tr>';
    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $max_vehicle_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') / 2;
        $vehicle_margin_rate_tax = ($total_margingst_cost_vehicle + $total_vehiclegst_cost) / 2;
        $table_agent_details .= ' <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT CGST @ ' . $max_vehicle_margin_gst_percentage . '%</b><br>
                                <b>OUT PUT SGST @ ' . $max_vehicle_margin_gst_percentage . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($vehicle_margin_rate_tax), 2) . '<br>' . number_format(($vehicle_margin_rate_tax), 2) . '</td>
                        </tr>';
    else:
        $table_agent_details .= ' <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_margingst_cost_vehicle + $total_vehiclegst_cost), 2) . '</td>
                        </tr>';
    endif;
    if ($total_guide_amount != 0 || $total_hotspot_amount != 0 || $total_activity_amout != 0):
        $table_agent_details .= '<tr>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">3</td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>TOTAL ' . $guide_amount_lable . ' ' . $hotspot_amount_lable . ' ' . $activity_amount_lable . '</b><br>
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('service_component_hsn') . '</td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_component_amount), 2) . '</td>
                            </tr>';
        if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
            $max_component_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') / 2;
            $margingst_rate_tax = $total_margingst_cost_service / 2;
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT CGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                     <b>OUT PUT SGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($margingst_rate_tax), 2) . '<br>' . number_format(($margingst_rate_tax), 2) . '</td>
                            </tr>';
        else:
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_margingst_cost_service), 2) . '</td>
                            </tr>';
        endif;
    endif;

    $coupen_discount_amount = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount');
    if ($coupen_discount_amount != 0):
        $table_agent_details .= '<tr>
                    <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Coupon Discount</strong></td>
                    <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format(($coupen_discount_amount), 2) . '</span></td>
                </tr>';
    endif;
    $table_agent_details .= '<tr>
                    <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Total Amount</strong></td>
                    <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format((get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_net_payable_amount')), 2) . '</span></td>
                </tr>
            </table>';
elseif ($itinerary_preference == 2):
    $total_cost_margin_vehicle = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_cost_margin_vehicle', '');
    $total_margingst_cost_vehicle = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_margingst_cost_vehicle', '');
    $total_vehiclegst_cost = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehiclegst_cost', '');

    $total_guide_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount');
    $total_hotspot_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
    $total_activity_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
    $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');

    $guide_amount_lable = ($total_guide_amount != 0) ? 'GUIDE COST +' : '';
    $hotspot_amount_lable = ($total_hotspot_amount != 0) ? 'HOTSPOT COST' : '';
    $activity_amount_lable = ($total_activity_amout != 0) ? 'ACTIVITY COST  ' : '';

    $total_component_amount = $total_guide_amount + $total_hotspot_amount + $total_activity_amout + $agent_margin_charges;
    $total_margingst_cost_service = getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_service');
    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $GST_label = 'CGST, SGST';
    else:
        $GST_label = 'IGST';
    endif;
    $table_agent_details .= '<table cellspacing="0" cellpadding="4" border="0" style="border:2px solid #d3d3d3;">
        <tr>
            <th style="font-weight: bold;font-size:13px;width: 6%;border:2px solid #d3d3d3;">SI No.</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 60%;border:2px solid #d3d3d3;">Particulars</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 13%;border:2px solid #d3d3d3;">HSN/SAC</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 21%;border:2px solid #d3d3d3;">Amount</th>
        </tr>
        <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">1</td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>' . $GST_label . ' @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') . '% TRANSPORTATION SERVICES</b><br>
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('vehicle_hsn') . '</td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_cost_margin_vehicle), 2) . '</td>
                        </tr>';
    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $max_vehicle_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') / 2;
        $vehicle_margin_rate_tax = ($total_margingst_cost_vehicle + $total_vehiclegst_cost) / 2;
        $table_agent_details .= ' <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT CGST @ ' . $max_vehicle_margin_gst_percentage . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($vehicle_margin_rate_tax), 2) . '</td>
                        </tr> 
                        <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT SGST @ ' . $max_vehicle_margin_gst_percentage . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($vehicle_margin_rate_tax), 2) . '</td>
                        </tr>';
    else:
        $table_agent_details .= ' <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'vehicle_max_gst_percentage') . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_margingst_cost_vehicle + $total_vehiclegst_cost), 2) . '</td>
                        </tr>';
    endif;
    if ($total_guide_amount != 0 || $total_hotspot_amount != 0 || $total_activity_amout != 0):
        $table_agent_details .= '<tr>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">2</td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>TOTAL ' . $guide_amount_lable . ' ' . $hotspot_amount_lable . ' ' . $activity_amount_lable . '</b><br>
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('service_component_hsn') . '</td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_component_amount), 2) . '</td>
                            </tr>';
        if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
            $max_component_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') / 2;
            $margingst_rate_tax = $total_margingst_cost_service / 2;
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT CGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($margingst_rate_tax), 2) . '</td>
                            </tr>
                            <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT SGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($margingst_rate_tax), 2) . '</td>
                            </tr>';
        else:
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_margingst_cost_service), 2) . '</td>
                            </tr>';
        endif;
    endif;

    $coupen_discount_amount = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount');
    if ($coupen_discount_amount != 0):
        $table_agent_details .= '<tr>
                    <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Coupon Discount</strong></td>
                    <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format(($coupen_discount_amount), 2) . '</span></td>
                </tr>';
    endif;
    $table_agent_details .= '<tr>
                    <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Total Amount</strong></td>
                    <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format((get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_net_payable_amount')), 2) . '</span></td>
                </tr>
            </table>';

elseif ($itinerary_preference == 1):
    $table_agent_details .= '<table cellspacing="0" cellpadding="4" border="0" style="border:2px solid #d3d3d3;">
        <tr>
            <th style="font-weight: bold;font-size:13px;width: 6%;border:2px solid #d3d3d3;">SI No.</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 60%;border:2px solid #d3d3d3;">Particulars</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 13%;border:2px solid #d3d3d3;">HSN/SAC</th>
            <th style="font-weight: bold;text-align:center;font-size:13px;width: 21%;border:2px solid #d3d3d3;">Amount</th>
        </tr>
        <tr>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">1</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                <b>HOTEL BOOKING CHARGES ONLY A/C (GST PAID)</b><br>';

    $select_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_route_date`, `itinerary_route_location`, `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_GUEST_DETAILS:" . sqlERROR_LABEL());
    $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details);

    if ($total_hotel_details_count > 0) :
        while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
            $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
            $itinerary_route_date_format = date('M d, Y', strtotime($itinerary_route_date));
            $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
            $hotel_id = $fetch_hotel_data['hotel_id'];
            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
            $table_agent_details .= ' 
                    <span style="font-size:10px;">' . $itinerary_route_date_format . ' - ' . $itinerary_route_location . ' - ' . $hotel_name . '</span><br>';
        endwhile;
    endif;

    $total_payable_cost_hotel = getITINEARYCONFIRMED_WITHOUTMARGIN_COST_DETAILS($itinerary_plan_ID, $select_type, 'total_payable_cost_hotel');
    $total_hotel_margin_rate = getHOTEL_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE');
    $total_hotel_margin_rate_tax = getHOTEL_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT');


    $total_guide_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount');
    $total_hotspot_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
    $total_activity_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
    $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');

    $guide_amount_lable = ($total_guide_amount != 0) ? 'GUIDE COST +' : '';
    $hotspot_amount_lable = ($total_hotspot_amount != 0) ? 'HOTSPOT COST +' : '';
    $activity_amount_lable = ($total_activity_amout != 0) ? 'ACTIVITY COST  ' : '';

    $total_component_amount = $total_guide_amount + $total_hotspot_amount + $total_activity_amout + $agent_margin_charges;
    $total_margingst_cost_service = getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_service');

    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $GST_label = 'CGST, SGST';
    else:
        $GST_label = 'IGST';
    endif;

    $table_agent_details .= '</td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('hotel_hsn') . '</td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_payable_cost_hotel), 2) . '</td>
                        </tr>
                        <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>' . $GST_label . ' SALES @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') . '% ACCOMMODATION SERVICES</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_hotel_margin_rate), 2) . '</td>
                        </tr>';
    if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
        $max_hotel_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') / 2;
        $hotel_margin_rate_tax = $total_hotel_margin_rate_tax / 2;
        $table_agent_details .= '<tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT CGST @ ' . $max_hotel_margin_gst_percentage . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($hotel_margin_rate_tax), 2) . '</td>
                        </tr>
                        <tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                <b>OUT PUT SGST @ ' . $max_hotel_margin_gst_percentage . '%</b><br>
                                
                            </td>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($hotel_margin_rate_tax), 2) . '</td>
                        </tr>';
    else:
        $table_agent_details .= '<tr>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                            <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, 'max_hotel_margin_gst_percentage') . '%</b><br>
                            
                        </td>
                        <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                        <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_hotel_margin_rate_tax), 2) . '</td>
                    </tr>';
    endif;

    if ($total_guide_amount != 0 || $total_hotspot_amount != 0 || $total_activity_amout != 0):
        $table_agent_details .= '<tr>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">2</td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>Total ' . $guide_amount_lable . ' ' . $hotspot_amount_lable . ' ' . $activity_amount_lable . '</b><br>
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . getGLOBALSETTING('service_component_hsn') . '</td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_component_amount), 2) . '</td>
                            </tr>';
        if ($company_gstin_no_two_digits != $agent_gstin_no_two_digits):
            $max_component_margin_gst_percentage = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') / 2;
            $margingst_rate_tax = $total_margingst_cost_service / 2;
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT CGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($margingst_rate_tax), 2) . '</td>
                            </tr>
                            <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT SGST @ ' . $max_component_margin_gst_percentage . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($margingst_rate_tax), 2) . '</td>
                            </tr>';
        else:
            $table_agent_details .= ' <tr>
                            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                            <td style="font-weight:semi-bold; font-size:12px;text-align: right; color:#232323;border-right:2px solid #d3d3d3;">
                                    <b>OUT PUT IGST @ ' . get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_percentage') . '%</b><br>
                                    
                                </td>
                                <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;"></td>
                                <td style="font-weight:semi-bold;text-align: right; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">' . number_format(($total_margingst_cost_service), 2) . '</td>
                            </tr>';
        endif;
    endif;
    $coupen_discount_amount = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount');
    if ($coupen_discount_amount != 0):
        $table_agent_details .= '<tr>
            <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Coupon Discount</strong></td>
            <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format(($coupen_discount_amount), 2) . '</span></td>
        </tr>';
    endif;
    $table_agent_details .= '<tr>
            <td colspan="3" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Total Amount</strong></td>
            <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;text-align: right;font-size:12px;color:#232323;">' . number_format((get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_net_payable_amount')), 2) . '</span></td>
        </tr>
    </table>';

endif;
$table_agent_details .= '<table cellspacing="0" cellpadding="5" border="0" width="100%">
     <tr>
        <td style="font-size: 12px;width: 65%;">
           <div>Amount Chargeable (in words) : <br/><span><b>' . convertToIndianCurrency(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_net_payable_amount')) . '</b></span></div>
            <div>Company\'s PAN : <span><b>' . get_AGENT_CONFIG_DETAILS($agent_ID, 'invoice_pan_no') . '</b></span></div> 
        </td>
        <td style="text-align: left; font-size: 12px;width: 35%;">
          <div><b>Company Bank Details :</b> <br/>Account Name: ' . getGLOBALSETTING('bank_acc_holder_name') . '<br/>Account Number: ' . getGLOBALSETTING('bank_acc_no') . '<br/>Branch & IFSC No: ' . getGLOBALSETTING('branch_name') . ' , ' .  getGLOBALSETTING('bank_ifsc_code') . '<br/>Bank Name: ' . getGLOBALSETTING('bank_name') . '</div>
        </td>
    </tr>
    <tr>
        <td style="font-size: 12px;width: 65%;border:2px solid #d3d3d3;">
            <div><span><b><u>Declaration</u></b></span> <br>
            <div style="margin-top: 5px;">The hotel bill charges are collected on behalf of the hotel hence the GST is payable by the hotel directly to the government.</div></div>
        </td>
        <td style="text-align: right; font-size: 12px;width: 35%;border:2px solid #d3d3d3;">
            for ' . $company_name . '
            <br/><br/><br/><br/>
            Authorized Signatory
        </td>
    </tr>
    </table>
';


$pdf->writeHTML($table_agent_details, true, false, false, false, '');


// Output combined PDF document
$pdf->Output('Agent-Invoice-' . $itinerary_quote_ID . '.pdf', 'I');
