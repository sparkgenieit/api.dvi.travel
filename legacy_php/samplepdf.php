<?php
ob_start();

include_once('jackus.php');
require_once('tcpdf/examples/tcpdf_include.php');

class MYPDF extends TCPDF
{

  //Page header
  public function Header()
  {
    // Logo
    $logoPath = 'http://localhost/olympiad_academy/head/assets/images/rounded_logo.jpg';
    $logoWidth = 25; // Set the desired width for your logo
    $this->Image($logoPath, 30, 8, $logoWidth, '', '', '', 'C');
    // Set font
    $this->SetFont('helvetica', 'B', 20);
    $x_size = $this->GetX();
    $y_size = $this->GetY();
    $this->setX($x_size);
    $this->setY($y_size);
    $this->cell(80, 42, '', 1, 0, 'C');
    $this->cell(120, 42, '', 1, 1, 'C');
    $this->setX($x_size);
    $this->setY($y_size + 32);
    $this->SetFont('helvetica', 'B', 8);
    $this->MultiCell(80, 3, "CPE Registration No:2018552522\nRegistration Period:20/11/2023-13/06/2025", 0, 'C', false, 0);
    $this->SetFont('helvetica', 'B', 10);
    $this->writeHTMLCell(115, 5, $x_size + 80, $y_size + 5, '<p><span>info@olympiad.sg | admissions@olympiad.edu.sg</span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../head/assets/images/email-13728.png" style="width:20px;"/></p>', 0, 1, false, true, 'R', true);
    $this->writeHTMLCell(115, 5, $x_size + 80, $y_size + 12, '<p><span>info@olympiad.sg | admissions@olympiad.edu.sg</span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../head/assets/images/email-13728.png" style="width:20px;"/></p>', 0, 1, false, true, 'R', true);
    $this->writeHTMLCell(115, 5, $x_size + 80, $y_size + 19, '<p><span>info@olympiad.sg | admissions@olympiad.edu.sg</span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../head/assets/images/email-13728.png" style="width:20px;"/></p>', 0, 1, false, true, 'R', true);
    $this->writeHTMLCell(115, 5, $x_size + 80, $y_size + 26, '<p><span>info@olympiad.sg | admissions@olympiad.edu.sg</span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../head/assets/images/email-13728.png" style="width:20px;"/></p>', 0, 1, false, true, 'R', true);
  }

  // Page footer
  public function Footer()
  {
    // Position at 15 mm from bottom
    $this->SetY(-15);
    // Set font
    $this->SetFont('helvetica', 'I', 8);
    // Page number
    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins('5', '10', '5');
$pdf->SetHeaderMargin('5');
$pdf->SetFooterMargin('5');

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 10);

// add a page
$pdf->AddPage();

$x_value = $pdf->getX();
$y_value = $pdf->getY();
$pdf->setX($x_value);
$pdf->setY($y_value + 37);
$pdf->cell(100, 30, '', 'LR', 0, 'C');
$pdf->cell(100, 30, '', 'LR', 1, 'C');
$pdf->setX($x_value);
$pdf->setY($y_value + 40);
$pdf->MultiCell(30, 3, "From:", 0, 'L', false, 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->setY($y_value + 46);
$pdf->MultiCell(100, 3, "Olympiad International School\nCPE Registration No:2018552522\nRegistration Period:20/11/2023-13/06/2025", 0, 'L', false, 0, 10);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->setY($y_value + 40);
$pdf->MultiCell(80, 3, "Bill to: Parents of", 0, 'L', false, 0, 105);
$pdf->SetFont('helvetica', '', 10);
$pdf->setY($y_value + 46);
$pdf->MultiCell(100, 3, "GOTETI PAVAN RAMACHANDRA", 0, 'L', false, 0, 110);

$pdf->MultiCell(100, 13, "", 'T', 'R', false, 0, 105, $pdf->getY() + 7);
$pdf->MultiCell(100, 13, "Invoice Ref: 2023/11/03933\nInvoice Date: 17th October 2023", 0, 'R', false, 1, 105, $pdf->getY() + 2);

$table = '<table border="1" cellpadding="8" width="100%">
            <tr>
                <th width="20%" style="font-weight: bold;">Sl.No.</th>
                <th width="40%" style="font-weight: bold;">Description</th>
                <th width="40%" style="font-weight: bold;">Amount</th>
            </tr>
            <tr>
                <td>1.</td>
                <td>Tution Fees - Instalment #1 11 October 2023</td>
                <td>$450.00 + $36.00 (GST) = $486.00</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Tution Fees - Instalment #1 11 October 2023</td>
                <td>$450.00 + $36.00 (GST) = $486.00</td>
            </tr>
            <tr>
                <th colspan="2" style="font-weight: bold;">Total</th>
                <th style="font-weight: bold;">$1350.00 + $108.00 (GST) = $1458.00</th>
            </tr>
        </table>';
$pdf->setY($pdf->getY() - 1);
$pdf->writeHTML($table, true, false, true, false, '');

$pdf->SetFont('helvetica', 'BU', 10);
$pdf->setX($pdf->getX());
$pdf->setY($pdf->getY() - 9);
$pdf->cell(200, 35, '', 'BLR', 0, 'C');
$pdf->MultiCell(100, 1, "Terms & Condtions", 0, 'L', false, 1, 5, $pdf->getY() + 2);
$pdf->SetFont('helvetica', '', 10);
$pdf->setY($pdf->getY());
$pdf->MultiCell(100, 2, "Payment must be made by 18th October 2023", 0, 'L', false, 1, 5, $pdf->getY() + 2);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->setY($pdf->getY());
$pdf->MultiCell(100, 2, "Mode of Payments:", 0, 'L', false, 1, 5, $pdf->getY() + 2);
$pdf->SetFont('helvetica', '', 10);
$pdf->setY($pdf->getY());
$terms_list = '<ul>
                <li>Cash payment at reception counter</li>
                <li>Crossed cheque to Olympoiad International school Pte.Ltd</li>
                <li>Bank transfer to Olympoiad International school Pte.Ltd, DBS Current Account 070-902714-8</li>
            </ul>';
$pdf->writeHTML($terms_list, true, false, true, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+