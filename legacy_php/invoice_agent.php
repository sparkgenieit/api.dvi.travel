<?php

include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');
require_once('phpqrcode/qrlib.php');

class TCPDFCustom extends TCPDF
{
    public function Header()
    {
        // QR code generation for PDF download link
        $pdfUrl = 'http://b2b.dvi.co.in/head/voucherpdf.php?itinerary_plan_ID=3045&confirmid=82&selectedHotels=8027'; // URL to the PDF file
        $qrCodePath = 'qr_code.png'; // Temporary file path to save QR code
        QRcode::png($pdfUrl, $qrCodePath, QR_ECLEVEL_L, 4); // Generate QR code with the PDF URL

        // Prepare the table for company details with dynamic QR code
        $table_company_details = '<table cellspacing="0" cellpadding="8" border="0">
        <tr>   
            <td width="70%">
                <img src="' . PUBLICPATH . 'assets/img/' . getGLOBALSETTING('company_logo') . '" width="100px"/>
            </td>                        
            <td width="30%" align="right">
                <img src="' . $qrCodePath . '" width="100px"/>
            </td>
        </tr>
        </table>';

        // Write the HTML with the table and QR code
        $this->writeHTML($table_company_details, true, false, false, false, '');

        // Optionally, delete the temporary QR code file after it's used
        if (file_exists($qrCodePath)) {
            unlink($qrCodePath);
        }
    }
}

// Create new PDF document
$pdf = new TCPDFCustom(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('ITINERARY HOTEL VOUCHER');
$pdf->SetSubject('Sample TCF PDF');
$pdf->SetKeywords('TCPDF, PDF, sample, TCF');

// Set default header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(6, 45, 6); // Adjust top margin to avoid overlap with header
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a new page
$pdf->AddPage();

$company_name = getGLOBALSETTING('company_name');
$company_contact_no = getGLOBALSETTING('company_contact_no');
$company_email_id = getGLOBALSETTING('company_email_id');
$company_address = getGLOBALSETTING('company_address');
$company_pincode = getGLOBALSETTING('company_pincode');
$company_gstin_no = getGLOBALSETTING('company_gstin_no');
$company_pan_no = getGLOBALSETTING('company_pan_no');
$amount_in_words = convertToIndianCurrency(2345);
$table_agent_details .= '
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
        <td width="53%" valign="top" style="border:2px solid #d3d3d3;">
        <table cellspacing="0" cellpadding="6" border="0" width="100%">
        <tr>
            <td><br/>
                <span style="font-weight:bold;font-size:12px;">' . $company_name . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;color:#232323;">' . $company_address . ', ' . $company_pincode . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">Email Id: ' . $company_email_id . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">Phone No: ' . $company_contact_no . '</span><br/>
                <span style="font-weight:semi-bold;font-size:12px;">GSTIN/UIN: ' . $company_gstin_no . '</span>
            </td>
        </tr>
        </table>
        </td>

        <td width="47%" valign="top" style="border:2px solid #d3d3d3;"><table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Invoice No</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_ITINEARY_CONFIRMED_PLAN_DETAILS(1756, 'itinerary_quote_ID') . '</span>
            </td>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Dated</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">1-Aug-24</span>
            </td>
        </tr>
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Delivery Note</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span>
            </td>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Dispatch Doc No</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">NL2111394620484</span>
            </td>
        </tr>
        <tr>
            <td width="50%" style="border:2px solid #d3d3d3;"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Delivery Note Date</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;"></span>
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
            <td><br/>
                <span style="font-weight:bold;font-size:12px;color:#232323;">' . getAGENT_details(56, '', 'label') . '</span>
                <br/>
                <span style="font-weight:semi-bold;font-size:12px;">' . get_AGENT_CONFIG_DETAILS(56, 'invoice_address') . '</span>
                <br/>
                <span style="font-weight:semi-bold;font-size:12px;">Phone No:' . getAGENT_details(56, '', 'get_agent_mobile_number') . '</span>
                <br/>   <span style="font-weight:semi-bold;font-size:12px;">Email Id: ' . getAGENT_details(56, '', 'get_agent_email_address') . '</span>
                <br/>
                <span style="font-weight:semi-bold;font-size:12px;">GSTIN/UIN : ' . get_AGENT_CONFIG_DETAILS(56, 'invoice_gstin_no') . '</span>
            </td>
        </tr>
        </table>
        </td>
        <td width="47%" valign="top" style="border:2px solid #d3d3d3;"><table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <td width="100%"><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Guest Name:</span>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS(1756, 'primary_customer_name') . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Contact Number:</span>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS(1756, 'primary_customer_contact_no') . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Arrival</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_ITINEARY_CONFIRMED_PLAN_DETAILS(1756, 'arrival_location') . ',' . date('d M Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS(1756, 'trip_start_date_and_time'))) . '</span><br/>
                <span style="font-weight:regular; font-size:12px; color:#6e6e6e ;">Departure</span><br/>
                <span style="font-weight:semi-bold; font-size:12px; color:#232323;">' . get_ITINEARY_CONFIRMED_PLAN_DETAILS(1756, 'departure_location') . ',' . date('d M Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS(1756, 'trip_end_date_and_time'))) . '</span>
            </td>
        </tr>

        </table>
        </td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="10" border="0" style="border:2px solid #d3d3d3;">
        <tr>
            <th style="font-weight: bold;font-size:13px;width: 6%;border:2px solid #d3d3d3;">SI No.</th>
            <th style="font-weight: bold;font-size:13px;width: 47%;border:2px solid #d3d3d3;">Particulars</th>
            <th style="font-weight: bold;font-size:13px;width: 13%;border:2px solid #d3d3d3;">HSN/SAC</th>
            <th style="font-weight: bold;font-size:13px;width: 13%;border:2px solid #d3d3d3;">Rate</th>
            <th style="font-weight: bold;font-size:13px;width: 8%;border:2px solid #d3d3d3;">per</th>
            <th style="font-weight: bold;font-size:13px;width: 13%;border:2px solid #d3d3d3;">Amount</th>
        </tr>
        <tr>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">1</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                <b>IGST SALES @ 5% TRANSPORTATION SERVICES</b><br>
                NL2111394620484 SAMARJEET SINGH<br>
                08-09 AUG - MADURAI<br>
                09-10 AUG - RAMESHWARAM<br>
                10-11 AUG - KANYAKUMARI<br>
                SEDAN
                
            </td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">998551</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">15,704.76</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">5%</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">16,490.00</td>
        </tr>
        <tr>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">2</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                <b>IGST SALES @ 5% TRANSPORTATION SERVICES</b><br>
                NL2111394620484 SAMARJEET SINGH<br>
                08-09 AUG - MADURAI<br>
                09-10 AUG - RAMESHWARAM<br>
                10-11 AUG - KANYAKUMARI<br>
                SEDAN
                
            </td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">998551</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">15,704.76</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">5%</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">16,490.00</td>
        </tr>
        <tr>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">3</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">
                <b>IGST SALES @ 5% TRANSPORTATION SERVICES</b><br>
                NL2111394620484 SAMARJEET SINGH<br>
                08-09 AUG - MADURAI<br>
                09-10 AUG - RAMESHWARAM<br>
                10-11 AUG - KANYAKUMARI<br>
                SEDAN
                
            </td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">998551</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">15,704.76</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">5%</td>
            <td style="font-weight:semi-bold; font-size:12px; color:#232323;border-right:2px solid #d3d3d3;">16,490.00</td>
        </tr>
  
        <tr>
            <td colspan="5" style="text-align: right;font-size:13px;border:2px solid #d3d3d3;"><strong>Total Amount</strong></td>
            <td style="border:2px solid #d3d3d3;"><span style="font-weight:semi-bold;font-size:12px;color:#232323;">88,490.00</span></td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="5" border="0" width="100%">
    <tr>
        <td style="text-align: left; font-size: 12px;border:2px solid #d3d3d3;" colspan="2">
            <div>Amount Chargeable (in words) : <span><b>INR ' . $amount_in_words . '</b></span></div>
            <div>Company\'s PAN : <span><b>' . $company_pan_no . '</b></span></div>                                    
        </td>
    </tr>
    <tr>
        <td style="font-size: 12px;width: 65%;border:2px solid #d3d3d3;">
            <div><span><b><u>Declaration</u></b></span> <br>
            <div style="margin-top: 5px;">The hotel bill charges are collected on behalf of the hotel hence the GST is payable by the hotel directly to the government.</div></div>
        </td>
        <td style="text-align: right; font-size: 12px;width: 35%;border:2px solid #d3d3d3;">
            for ' . $company_name . ',
            <br/><br/><br/><br/>
            Authorized Signatory
        </td>
    </tr>
    </table>
';


$pdf->writeHTML($table_agent_details, true, false, false, false, '');


// Output combined PDF document
$pdf->Output('itinerary-voucher-' . $itinerary_quote_ID . '.pdf', 'I');
