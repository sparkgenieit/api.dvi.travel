<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');
$confirmed_itinerary_plan_ID = $_GET['id']; //config values from itinerary plan for language

$select_guest_detail = sqlQUERY_LABEL("SELECT `confirmed_itinerary_customer_ID`, `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `primary_customer`, `customer_type`, `customer_salutation`, `customer_name`, `customer_age`, `primary_contact_no`, `altenative_contact_no`, `email_id`, `arrival_date_and_time`, `arrival_place`, `arrival_flight_details`, `departure_date_and_time`, `departure_place`, `departure_flight_details` FROM `dvi_confirmed_itinerary_customer_details` WHERE `status` = '1' and `deleted` = '0' and `confirmed_itinerary_plan_ID` = '$confirmed_itinerary_plan_ID' and `primary_customer`= 1") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
while ($fetch_guest_data = sqlFETCHARRAY_LABEL($select_guest_detail)) :
    $customer_salutation = $fetch_guest_data['customer_salutation'];
    $customer_name = $fetch_guest_data['customer_name'];
    $primary_contact_no = $fetch_guest_data['primary_contact_no'];
    $arrival_date_and_time = $fetch_guest_data['arrival_date_and_time'];
    if ($arrival_date_and_time):
        $arrival_date_and_time = ', ' . date('d-m-Y h:i A', strtotime($arrival_date_and_time));
    else:
        $arrival_date_and_time = '';
    endif;
    $arrival_place = $fetch_guest_data['arrival_place'];
    $arrival_flight_details = $fetch_guest_data['arrival_flight_details'];
endwhile;

//'. $trip_start_date_and_time . '

// Create a new PDF document (landscape A4)
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(getGLOBALSETTING('site_title'));
$pdf->SetTitle('Welcome PDF');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(10, 10, 10);

// Add a page
$pdf->AddPage();

// Set font for the welcome text
$pdf->SetFont('helvetica', 'B', 60);

// First section (logo)
$pdf->Image('uploads/logo/logo.png', 15, 15, 45, 40, '', '', 'T', false, 300, '', false, false, 0, false, false, false);


// Add space above the welcome text (Modify the number to adjust space)
$pdf->Ln(30); // This will add 30mm of space

// Add the welcome text with reduced spacing between lines
$pdf->Cell(0, 30, 'WELCOME', 0, 1, 'C'); // 30mm height for first line
$pdf->Cell(0, 30, ucwords($customer_salutation) . ' ' . ucwords($customer_name), 0, 1, 'C'); // 30mm height for second line

// Set font for the phone number
$pdf->SetFont('helvetica', 'B', 25);

// Position the phone number above the footer
$pdf->SetY(-60); // Adjust this value to move the phone number higher

// Add the phone number (left-aligned)
$pdf->Cell(0, 10, $primary_contact_no, 0, 1, 'L');

// Add space before the footer
$pdf->Ln(2); // Adjust to control space between phone number and footer

// Set font for the footer
$pdf->SetFont('helvetica', '', 10);

// Footer content
$html_footer = '
<table>
<tr>
    <td width="100%"><hr/></td>
</tr>
<tr>
    <td style="color: #405aaf; font-size: 14px;" width="100%">
        <b style="color: #405aaf;">' . $arrival_place . '' . $arrival_date_and_time . ' </b><br>
        ' . $arrival_flight_details . '<br>
    </td>
</tr>
</table>';
$pdf->writeHTML($html_footer, true, false, true, false, '');

// Output the PDF
$pdf->Output('welcome.pdf', 'I');
