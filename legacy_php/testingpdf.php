<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/examples/tcpdf_include.php');
include_once('jackus.php');
reguser_protect();
extract($_REQUEST);
ob_start();

if ($month_n_year != '') :
	$month_n_year = dateformat_database($month_n_year);
	$month = date('m', strtotime($month_n_year));
	$month_name = date('F', strtotime($month_n_year));
	$year = date('Y', strtotime($month_n_year));
else : 
	$month = date('m', strtotime(date("d-m-Y") . " -1 month"));
	$month_name = date('F', strtotime(date("d-m-Y") . " -1 month"));
	$year =  ($year != "" && $year != 0) ? $year : date('Y');
endif;

$selected_employee_ID = [];

if ($empid != "" && $empid != "0") :
	$filter_employee_id = " AND P.`employee_id`='$empid' ";
elseif (count($selected_employee_ID) > 0) :
	$employeeid = implode(',', $selected_employee_ID);
	$filter_employee_id = " AND P.`employee_id` IN('$employeeid') ";
else :
	$filter_employee_id = "";
endif;

//config values from basic settings for language
$select_basic_settings_detail = sqlQUERY_LABEL("SELECT `basic_setting_ID`,`payslip_primary_language`, `payslip_secondary_language`, `payslip_language_setup_status` FROM `gs_basic_settings` WHERE `status` = '1' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_BASIC_SETTING_DETAILS:" . sqlERROR_LABEL());
while ($fetch_basic_setting_data = sqlFETCHARRAY_LABEL($select_basic_settings_detail)) :
	$payslip_primary_language = $fetch_basic_setting_data['payslip_primary_language'];
	$payslip_secondary_language = $fetch_basic_setting_data['payslip_secondary_language'];
	$payslip_language_setup_status = $fetch_basic_setting_data['payslip_language_setup_status'];
endwhile;

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{
	//Page header
	public function Header()
	{
		// Logo
		/* $image_file = 'assets/images/logo/GS_Pay_logo.png';
		$this->Image($image_file, 10, 8, 30, '', 'png', '', 'M', false, 300, '', false, false, 0, false, false, false); */
		// Set font
		/* $this->setFont('helvetica', '', 8); */
		// Title
		/* $this->SetFillColor(230, 230, 230);
		$this->setCellPaddings(3, 0, 3, 0);
		$this->setY($this->getY() - 5);
		$this->MultiCell(60, 10, "Unit Name: Gokaldas Exports \nPlot No. 119, KIADB Growth Center, SH ", 0, 'L', 1, 0, 44, '', true, 0, false, true, 10, 'M');
		$this->setFont('helvetica', 'B', 8);
		$this->MultiCell(96, 10, "Pay Slip for the Month of Dec-2022", 0, 'R', 1, 0, '', '', true, 0, false, true, 10, 'M'); */
	}

	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->setY(-15);
		// Set font
		$this->setFont('helvetica', 'I', 8);
		// Page number
		/* $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M'); */
	}
}
//COMPANY DATA
$select_company_name = sqlQUERY_LABEL("SELECT `gensetting_sitetitle`,`gensetting_address` FROM `gs_generalsettings`");
while($row = sqlFETCHARRAY_LABEL($select_company_name)):
    $company_name =  $row['gensetting_sitetitle'];
    $address      =$row['gensetting_address'];
endwhile;

//PAYSLIP DATA FROM EMPLOYEE TABLE AND PAYSLIP_ACTUAL TABLE
$select_paysilp = sqlQUERY_LABEL("SELECT E.`emp_code`, E.`emp_name`, E.`emp_department`, E.`emp_designation`, E.`emp_category`,E.`emp_doj`,E.`emp_bank_id`, E.`emp_bank_acc_no`,E.`emp_esi_num`,E.`emp_uan_num`, E.`emp_pf_no`, E.`total_el_available`, E.`total_cl_available`, P.`actual_payslip_id`, P.`employee_id`,
P.`total_no_of_days`, P.`total_no_week_off_days`, P.`total_no_of_holidays`, P.`total_no_of_working_days`, P.`total_present_days`, P.`total_no_of_leave_approved`, P.`total_paid_days`, P.`total_no_of_absent_days`, P.`total_lop_days`, P.`total_overtime_hours`,
 P.`employee_basic_rate_monthly`, P.`employee_basic_rate_per_day`, P.`employee_dearness_allowence_allowance_monthly`, P.`employee_dearness_allowence_allowance_per_day`, P.`employee_conveyance_allowance_rate_monthly`, P.`employee_conveyance_allowance_rate_per_day`,P.`employee_house_rent_allowance_rate_monthly`, P.`employee_house_rent_allowance_rate_per_day`, P.`employee_special_allowance_rate_monthly`, P.`employee_special_allowance_rate_per_day`, P.`employee_incentive_monthly`, P.`employee_incentive_per_day`, P.`employee_overtime_rate_monthly`, P.`employee_overtime_rate_per_day`, P.`employee_attendance_bonus_monthly`, P.`employee_attendance_bonus_per_day`, P.`employee_children_education_allowance_rate_monthly`, P.`employee_children_education_allowance_rate_per_day`, P.`employee_arrears_monthly`, P.`employee_arrears_per_day`, P.`cml_encashment_amount_monthly`, P.`cml_encashment_amount_per_day`, P.`total_gross_monthly`, P.`total_gross_per_day`, P.`employee_esi_rate_monthly`, P.`employee_pf_rate_monthly`, P.`employee_prof_tax_rate`, P.`employee_lwf_rate`, P.`employee_advance_salary_rate`, P.`employee_loan_deduction_rate`, P.`employee_income_tax_rate`, P.`employee_festival_advance_rate`, P.`employee_other_deductions`, P.`total_deductions`, P.`net_payable` FROM `gs_payslip_actual` P LEFT JOIN `gs_employee` E ON E.`employee_id` = P.`employee_id` WHERE P.`status`='1' AND P.`deleted`='0' AND MONTH(P.`payslip_month`) ='$month' AND YEAR(P.`payslip_month`) ='$year' {$filter_employee_id}") or die("#1_UNABLE_TO_FETCH_RECORDS:" . sqlERROR_LABEL());
$paysilp_count = sqlNUMOFROW_LABEL($select_paysilp);

if ($paysilp_count > 0) :

	if ($payslip_language_setup_status == 1) :
		if ($payslip_primary_language == "EN") :
			// create new PDF document
			$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set margins
			$pdf->setMargins(5, 20, 5);
			/* $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->setFooterMargin(PDF_MARGIN_FOOTER); */

			// set auto page breaks
			$pdf->setAutoPageBreak(TRUE, 10);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
				require_once(dirname(__FILE__) . '/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			
			while ($paysilp_row = sqlFETCHARRAY_LABEL($select_paysilp)) :
				$count++;
				$emp_pf_no = ($paysilp_row['emp_pf_no']== "")? 'N/A':$paysilp_row['emp_pf_no'];
				$emp_uan_num = ($paysilp_row['emp_uan_num']== "")? 'N/A':$paysilp_row['emp_uan_num'];
				$emp_esi_num = ($paysilp_row['emp_esi_num']== "")? 'N/A':$paysilp_row['emp_esi_num'];
				$emp_bank_acc_no = ($paysilp_row['emp_bank_acc_no']== "")? 'N/A':$paysilp_row['emp_bank_acc_no'];
				
				if($paysilp_row['emp_doj'] != ""):
					$emp_doj = dateformat_datepicker($paysilp_row['emp_doj']);
				else:
					$emp_doj = "N/A";
				endif;
				
				if($paysilp_row['emp_bank_id'] != "" && $paysilp_row['emp_bank_id'] != 0):
					$emp_bank = get_BANK_DETAILS($paysilp_row['emp_bank_id'], 'label');
				else:
					$emp_bank = "N/A";
				endif;
				
				if($paysilp_row['net_payable'] != 0):
					$net_payable_words = convertToIndianCurrency($paysilp_row['net_payable']);
				else:
					$net_payable_words = "";
				endif;
				
				if ($count == 1) :

					// set font
					$pdf->SetMargins(10, 12, 10, true);
					// add a page
					$pdf->AddPage();
					$image_file = 'assets/images/logo/GS_Pay_empty_logo_size.png';
					$pdf->Image($image_file, 10, 10, 30, '', 'png', '', 'M', false, 300, '', false, false, 0, false, false, false);
					/* $pdf->writeHTMLCell(30, 10, 10, 10, '<img src="'.$image_file.'"  />'); */
					// Set font
					$pdf->setFont('helvetica', '', 8);
					// Title
					$pdf->SetFillColor(230, 230, 230);
					$pdf->setCellPaddings(3, 0, 3, 0);
					$pdf->setY($pdf->getY() - 5);
					$pdf->setCellHeightRatio(1.3);
					$pdf->MultiCell(90, 10, "Unit Name:$company_name\n$address", 0, 'L', 1, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(100, 10, "Pay Slip for the Month of " . $month_name . " - " . $year, 0, 'R', 1, 0, '', '', true, 0, false, true, 10, 'M');
					$pdf->setY($pdf->getY() + 8);
					$pdf->setFont('helvetica', '', 8);
					$pdf->setCellHeightRatio(0.6);
					$pdf->MultiCell(50, 2, "Employee No.", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $paysilp_row['emp_code'], 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "PF No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_pf_no, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Employee Name", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $paysilp_row['emp_name'], 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "PF UAN", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_uan_num, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Designation", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, get_DESIGNATIONLIST($paysilp_row['emp_designation'], 'label'), 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "ESI No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_esi_num, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Department", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, get_DEPARTMENT_DETAILS($paysilp_row['emp_department'], 'label'), 0, 'L', 0, 0, 50, '', true, 0, false, true, 10,'M');
					$pdf->MultiCell(50, 2, "Bank A/c No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_bank_acc_no, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Date of Joining", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_doj, 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->setCellHeightRatio(1.3);
					$pdf->MultiCell(50, 2, "Bank Name", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_bank, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					/* BEGIN: Card Section */

					$pdf->setY($pdf->getY() + 2);
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 33.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 57.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 81.25, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 105, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 128.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 152.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 176.25, '', true, 0, false, true, 10, 'M');

					$pdf->setY($pdf->getY());
					$pdf->setCellHeightRatio(1.3);
					$pdf->setCellPaddings(2, 1, 2, 1);
					$pdf->MultiCell(23.75, 10, "No.Of Days\n" . $paysilp_row['total_no_of_days'], 0, 'L', 0, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Holidays\n" . $paysilp_row['total_no_of_holidays'], 0, 'L', 0, 0, 33.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Worked Days\n" . $paysilp_row['total_present_days'], 0, 'L', 0, 0, 57.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Paid Days\n" . $paysilp_row['total_paid_days'], 0, 'L', 0, 0, 81.25, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "OT Hrs\n" . $paysilp_row['total_overtime_hours'], 0, 'L', 0, 0, 105, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "LOP Days\n" . $paysilp_row['total_lop_days'], 0, 'L', 0, 0, 128.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "EL\n" . $paysilp_row['total_el_available'], 0, 'L', 0, 0, 152.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "CL\n" . $paysilp_row['total_cl_available'], 0, 'L', 0, 1, 176.25, '', true, 0, false, true, 10, 'M');

					/* END: Card Section */

					/* BEGIN: Table Content */
					$tbl_one = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60%;" cellspacing="1">
						<thead>
							<tr style="line-height: 1;">
								<th colspan="2" style="background-color: #e6e6e6; border-right: 1px solid #000; font-weight: bold;">Rate Of Wages</th>
								<th style="background-color: #e6e6e6; font-weight: bold;">Earnings</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>BASIC</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_basic_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_basic_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>V.D.A</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_dearness_allowence_allowance_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_dearness_allowence_allowance_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Conveyance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_conveyance_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_conveyance_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>HRA</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_house_rent_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_house_rent_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Special Allowance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_special_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_special_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Incentive</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_incentive_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_incentive_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>OT Rate/Amount</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_overtime_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_overtime_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Attendance Bonus</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_attendance_bonus_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_attendance_bonus_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Education Allowance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_children_education_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_children_education_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
												<td>Arrears</td>
												<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_arrears_per_day'], 2, '.', '') . '</td>
												<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_arrears_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>COMP-L Cash Earnings</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$payslip_row['cml_encashment_amount_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['cml_encashment_amount_monthly'], 2, '.', '') . '</td>
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<td style="border-top: 1px solid #000; font-weight: bold;">Total</td>
												<td style="border-top: 1px solid #000; border-right: 1px solid #000; text-align: right; font-weight: bold;">' . number_format((float)$paysilp_row['total_gross_per_day'], 2, '.', '') . '</td>
												<td style="border-top: 1px solid #000; text-align: right; font-weight: bold;">' . number_format((float)$paysilp_row['total_gross_monthly'], 2, '.', '') . '</td>
							</tr>
						</tfoot>
					</table>';

					$tbl_two = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 100%;" cellspacing="1">
							<thead>
								<tr style="line-height: 1;">
									<th colspan="2" style="font-weight: bold; background-color: #e6e6e6;">Deductions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td >ESI</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_esi_rate_monthly'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>PF</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_pf_rate_monthly'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Prof Tax</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_prof_tax_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>LWF</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_lwf_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Salary Adv</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_advance_salary_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Loan Ded</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_loan_deduction_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Income Tax</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_income_tax_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Other Ded</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_other_deductions'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: right;"></td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: right;"></td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: right;"></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td style="font-weight: bold; border-top: 1px solid #000;">Total Deductions</td>
									<td style="font-weight: bold; text-align: right; border-top: 1px solid #000;">' . number_format((float)$paysilp_row['total_deductions'], 2, '.', '') . '</td>
								</tr>
							</tfoot>
						</table>';

					$tbl_three = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60%;">
							<thead>
								<tr>
									<th style="font-weight: bold;">Net Payable</th>
									<th style="font-weight: bold; text-align: right;">' . number_format((float)$paysilp_row['net_payable'], 2, '.', '') . '</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td style="width: 10%;"></td>
								<td style="text-align: right; font-size: 9px; width: 90%;">' . $net_payable_words . '</td>
								</tr>
							</tbody>
						</table>';
					$pdf->setFont('helvetica', '', 8);
					$pdf->setY($pdf->getY() + 2);
					$table_y_value = $pdf->getY();
					$table_x_value = $pdf->getX();
					$pdf->setCellHeightRatio(0.8);
					$pdf->writeHTML($tbl_one, true, false, false, false, '');
					$pdf->setXY($table_x_value + 116, $table_y_value);
					$pdf->writeHTML($tbl_two, true, false, false, false, '');
					$pdf->setXY($pdf->getX(), $pdf->getY() - 2);
					$table_end_y_value = $pdf->getY();
					$table_end_x_value = $pdf->getX();
					$pdf->writeHTML($tbl_three, false, false, false, false, '');

					/* END: Table Content */
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(32.1, 10, "Employee Signature", 0, 'L', 0, 1, $table_end_x_value + 115, $table_end_y_value - 3, true, 0, false, true, 10, 'M');
					$pdf->setFont('helvetica', '', 8);
					$pdf->MultiCell(190, 5, "*** This is Computer generated Payslip (No Authorized Signature Required) ***", 0, 'C', 0, 1,$pdf->getX(), $pdf->getY(), true, 0, false, true, 10, 'M');
					/* BEGIN: Draw Line Horizontal */

					$pdf->SetLineStyle(array('width' => 0.2, 'dash' => '3,3,3,3', 'phase' => 0));
					$pdf->Line(10, $pdf->getY() + 5, 200, $pdf->getY() + 5);
					$pdf->SetLineStyle(array('width' => 0.2, 'dash' => '0', 'phase' => 0));

				/* END: Draw Line Horizontal */
				else :
					/* BEGIN: Second Section */
 
					$second_image_file = 'assets/images/logo/GS_Pay_empty_logo_size.png';
					$pdf->Image($second_image_file, 10, $pdf->getY() + 15, 30, '', 'png', '', 'M', false, 300, '', false, false, 0, false, false, false);
					/* $pdf->writeHTMLCell(30, 10, 10, 10, '<img src="'.$image_file.'"  />'); */
					// Set font
					$pdf->setFont('helvetica', '', 8);
					$pdf->SetFillColor(230, 230, 230);
					$pdf->setCellPaddings(3, 0, 3, 0);
					$pdf->setY($pdf->getY() - 5);
					$pdf->setCellHeightRatio(1.3);
					$pdf->MultiCell(90, 10, "Unit Name: $company_name\n$address", 0, 'L', 1, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(100, 10, "Pay Slip for the Month of " . $month_name . " - " . $year, 0, 'R', 1, 0, '', '', true, 0, false, true, 10, 'M');
					$pdf->setY($pdf->getY() + 8);
					$pdf->setFont('helvetica', '', 8);
					$pdf->setCellHeightRatio(0.6);
					$pdf->MultiCell(50, 2, "Employee No.", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $paysilp_row['emp_code'], 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "PF No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_pf_no, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Employee Name", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $paysilp_row['emp_name'], 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "PF UAN", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_uan_num, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Designation", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, get_DESIGNATIONLIST($paysilp_row['emp_designation'], 'label'), 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "ESI No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_esi_num, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Department", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, get_DEPARTMENT_DETAILS($paysilp_row['emp_department'], 'label'), 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, "Bank A/c No", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_bank_acc_no, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');

					$pdf->MultiCell(50, 2, "Date of Joining", 0, 'L', 0, 0, 8, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_doj, 0, 'L', 0, 0, 50, '', true, 0, false, true, 10, 'M');
					$pdf->setCellHeightRatio(1.3);
					$pdf->MultiCell(50, 2, "Bank Name", 0, 'L', 0, 0, 130, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(50, 2, $emp_bank, 0, 'L', 0, 1, 160, '', true, 0, false, true, 10, 'M');
					/* BEGIN: Card Section */

					$pdf->setY($pdf->getY() + 2);
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 33.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 57.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 81.25, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 105, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 128.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 152.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "", 1, 'L', 0, 0, 176.25, '', true, 0, false, true, 10, 'M');

					$pdf->setY($pdf->getY());
					$pdf->setCellHeightRatio(1.3);
					$pdf->setCellPaddings(2, 1, 2, 1);
					$pdf->MultiCell(23.75, 10, "No.Of Days\n" . $paysilp_row['total_no_of_days'], 0, 'L', 0, 0, 10, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Holidays\n" . $paysilp_row['total_no_of_holidays'], 0, 'L', 0, 0, 33.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Worked Days\n" . $paysilp_row['total_present_days'], 0, 'L', 0, 0, 57.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "Paid Days\n" . $paysilp_row['total_paid_days'], 0, 'L', 0, 0, 81.25, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "OT Hrs\n" . $paysilp_row['total_overtime_hours'], 0, 'L', 0, 0, 105, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "LOP Days\n" . $paysilp_row['total_lop_days'], 0, 'L', 0, 0, 128.75, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "EL\n" . $paysilp_row['total_el_available'], 0, 'L', 0, 0, 152.5, '', true, 0, false, true, 10, 'M');
					$pdf->MultiCell(23.75, 10, "CL\n" . $paysilp_row['total_cl_available'], 0, 'L', 0, 1, 176.25, '', true, 0, false, true, 10, 'M');

					/* END: Card Section */

					/* BEGIN: Table Content */
					$second_tbl_one = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60%;" cellspacing="1">
						<thead>
							<tr style="line-height: 1;">
								<th colspan="2" style="background-color: #e6e6e6; border-right: 1px solid #000; font-weight: bold;">Rate Of Wages</th>
								<th style="background-color: #e6e6e6; font-weight: bold;">Earnings</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>BASIC</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_basic_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_basic_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>V.D.A</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_dearness_allowence_allowance_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_dearness_allowence_allowance_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Conveyance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_conveyance_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_conveyance_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>HRA</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_house_rent_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_house_rent_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Special Allowance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_special_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_special_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Incentive</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_incentive_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_incentive_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>OT Rate/Amount</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_overtime_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_overtime_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Attendance Bonus</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_attendance_bonus_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_attendance_bonus_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>Education Allowance</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_children_education_allowance_rate_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_children_education_allowance_rate_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
												<td>Arrears</td>
												<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$paysilp_row['employee_arrears_per_day'], 2, '.', '') . '</td>
												<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_arrears_monthly'], 2, '.', '') . '</td>
							</tr>
							<tr>
								<td>COMP-L Cash Earnings</td>
								<td style="text-align: right; border-right: 1px solid #000;">' . number_format((float)$payslip_row['cml_encashment_amount_per_day'], 2, '.', '') . '</td>
								<td style="text-align: right;">' . number_format((float)$paysilp_row['cml_encashment_amount_monthly'], 2, '.', '') . '</td>
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<td style="border-top: 1px solid #000; font-weight: bold;">Total</td>
												<td style="border-top: 1px solid #000; border-right: 1px solid #000; text-align: right; font-weight: bold;">' . number_format((float)$paysilp_row['total_gross_per_day'], 2, '.', '') . '</td>
												<td style="border-top: 1px solid #000; text-align: right; font-weight: bold;">' . number_format((float)$paysilp_row['total_gross_monthly'], 2, '.', '') . '</td>
							</tr>
						</tfoot>
					</table>';

					$second_tbl_two = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 100%;" cellspacing="1">
							<thead>
								<tr style="line-height: 1;">
									<th colspan="2" style="font-weight: bold; background-color: #e6e6e6;">Deductions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td >ESI</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_esi_rate_monthly'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>PF</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_pf_rate_monthly'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Prof Tax</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_prof_tax_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>LWF</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_lwf_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Salary Adv</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_advance_salary_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Loan Ded</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_loan_deduction_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Income Tax</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_income_tax_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Fest. Adv</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_festival_advance_rate'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td>Other Ded</td>
									<td style="text-align: right;">' . number_format((float)$paysilp_row['employee_other_deductions'], 2, '.', '') . '</td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: right;"></td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: right;"></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td style="font-weight: bold; border-top: 1px solid #000;">Total Deductions</td>
									<td style="font-weight: bold; text-align: right; border-top: 1px solid #000;">' . number_format((float)$paysilp_row['total_deductions'], 2, '.', '') . '</td>
								</tr>
							</tfoot>
						</table>';

					$second_tbl_three = '<table style="border-left:1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60%;">
							<thead>
								<tr>
									<th style="font-weight: bold;">Net Payable</th>
									<th style="font-weight: bold; text-align: right;">' . number_format((float)$paysilp_row['net_payable'], 2, '.', '') . '</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td style="width: 10%;"></td>
								<td style="text-align: right; font-size: 9px; width: 90%;">' . $net_payable_words . '</td>
								</tr>
							</tbody>
						</table>';
					$pdf->setFont('helvetica', '', 8);
					$pdf->setY($pdf->getY() + 2);
					$second_table_y_value = $pdf->getY();
					$second_table_x_value = $pdf->getX();
					$pdf->setCellHeightRatio(0.8);
					$pdf->writeHTML($second_tbl_one, true, false, false, false, '');
					$pdf->setXY($second_table_x_value + 116, $second_table_y_value);
					$pdf->writeHTML($second_tbl_two, true, false, false, false, '');
					$pdf->setXY($pdf->getX(), $pdf->getY() - 2);
					$second_table_end_y_value = $pdf->getY();
					$second_table_end_x_value = $pdf->getX();
					$pdf->writeHTML($second_tbl_three, false, false, false, false, '');

					/* END: Table Content */
					$pdf->setFont('helvetica', 'B', 8);
					$pdf->MultiCell(32.1, 10, "Employee Signature", 0, 'L', 0, 1, $second_table_end_x_value + 115, $second_table_end_y_value - 3, true, 0, false, true, 10, 'M');
					$pdf->setFont('helvetica', '', 8);
					$pdf->MultiCell(190, 5, "*** This is Computer generated Payslip (No Authorized Signature Required) ***", 0, 'C', 0, 1,$pdf->getX(), $pdf->getY(), true, 0, false, true, 10, 'M');

					$pdf->SetLineStyle(array('width' => 0.2, 'dash' => '3,3,3,3', 'phase' => 0));
					$pdf->Line(10, $pdf->getY() + 5, 200, $pdf->getY() + 5);
					$pdf->SetLineStyle(array('width' => 0.2, 'dash' => '0', 'phase' => 0));

				/* END: Second Section */
				endif;
			endwhile;

			// --------------------------------------------

			//Close and output PDF document
			$pdf_label_name = 'PAYSLIP_FOR_THE_MONTH_OF_' . $month_name . '_' . $year;
			if ($_GET['type'] == 'print') :
				$pdf->Output($pdf_label_name . '.pdf', 'I');
			elseif ($_GET['type'] == 'download') :
				$pdf->Output($pdf_label_name . '.pdf', 'D');
			endif;
		else :
			echo "Please choose the primary language in the basic settings !!!";
		endif;
	else :
		echo "Language setup status for payslip is not active !!!";
		exit();
	endif;
else :
	echo "No more configuration setup found !!!";
endif;
//============================================================+
// END OF FILE
//============================================================+