<?php
include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

// Create a new PDF document (landscape A4)
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Touchmark Descience');
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

// Add space above the welcome text (Modify the number to adjust space)
$pdf->Ln(30); // This will add 30mm of space

// Add the welcome text with reduced spacing between lines
$pdf->Cell(0, 30, 'WELCOME', 0, 1, 'C'); // 30mm height for first line
$pdf->Cell(0, 30, 'MR. SANDIP MALI', 0, 1, 'C'); // 30mm height for second line

// Set font for the phone number
$pdf->SetFont('helvetica', 'B', 25);

// Position the phone number above the footer
$pdf->SetY(-60); // Adjust this value to move the phone number higher

// Add the phone number (left-aligned)
$pdf->Cell(0, 10, '9890058783', 0, 1, 'L');

// Add space before the footer
$pdf->Ln(2); // Adjust to control space between phone number and footer

// Set font for the footer
$pdf->SetFont('helvetica', '', 10);

// Footer content
$html_footer =
    '<table>
<tr>
    <td width="100%"><hr/></td>
</tr>
<tr>
    <td style="color: #405aaf;" width="100%">
        <b style="color: #405aaf;">Trichy – Head Office</b><br>
        No. 51, Vijaya Nagar, Dheeran Nagar, (Near Siddhartha Trust) Tiruchirappalli, Tamilnadu PIN 620009<br>
        Ph: 0431-2403615 Email: <b style="color: #405aaf;">vsr@dvi.co.in</b>  web:: <b style="color: #405aaf;">www.dvi.co.in</b><br>
         <b style="color: #405aaf;">Branches : Madurai | Cochin | Hyderabad</b>
    </td>
</tr>
</table>';
$pdf->writeHTML($html_footer, true, false, true, false, '');

// Output the PDF
$pdf->Output('welcome.pdf', 'I');
