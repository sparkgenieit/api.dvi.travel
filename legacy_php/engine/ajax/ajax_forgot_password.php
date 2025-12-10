<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');
include_once('../../smtp_functions.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($_GET['type'] == 'forgot_pwd') :

		$errors = [];
		$response = [];

		$_forgot_pwd_email = $_POST['forgot_pwd_email'];

		$sanitize_forgot_pwd_email = trim($_forgot_pwd_email);

		$filter_forgot_pwd_email = filter_var($sanitize_forgot_pwd_email, FILTER_SANITIZE_EMAIL);
		$filter_forgot_pwd_email = filter_var($sanitize_forgot_pwd_email, FILTER_VALIDATE_EMAIL);
		$encoded_forgot_pwd_email = Encryption::Encode($sanitize_forgot_pwd_email, SECRET_KEY);

		if (!$_forgot_pwd_email) :
			$errors['not_valid_email'] = 'Please Enter the Valid Email Address !!! ';
		else :
			$query = "SELECT `userID`, `guide_id`,`vendor_id`,`staff_id`,`agent_id`,`username`, `useremail`,`userapproved`,`roleID`,`userbanned` FROM `dvi_users` WHERE `useremail` = '$sanitize_forgot_pwd_email' and `deleted` = '0'";
			$result = sqlQUERY_LABEL($query);
			$num = sqlNUMOFROW_LABEL($result);
			while ($row_reset_user_data = sqlFETCHARRAY_LABEL($result)) :
				$userID = $row_reset_user_data["userID"];
				$username = $row_reset_user_data["username"];
				$useremail = $row_reset_user_data["useremail"];
				$guide_id = $row_reset_user_data["guide_id"];
				$vendor_id = $row_reset_user_data["vendor_id"];
				$staff_id = $row_reset_user_data["staff_id"];
				$agent_id = $row_reset_user_data["agent_id"];
				$roleID = $row_reset_user_data["roleID"];
				$userapproved = $row_reset_user_data["userapproved"];
				$userbanned = $row_reset_user_data["userbanned"];
			endwhile;

			if ($num == 0) :
				$errors['no_user_found'] = 'No users is registered with this email address !!!';
			elseif (!$userapproved && $num > 0) :
				$errors['account_not_activated'] = 'Account not activated. Please Contact Admin !!!';
			elseif ($userbanned == 1) :
				$errors['account_banned'] = 'Your account was banned by the administrator. Please contact us if you think this is a mistake.';
			endif;
		endif;

		if (!empty($errors)) :
			//error call
			$response['success'] = false;
			$response['errors'] = $errors;
		else :
			//success call
			$get_mktime_string_format = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
			$expiry_date = date("Y-m-d H:i:s", $get_mktime_string_format);
			$string = $forgot_pwd_email;
			$key = md5($string . microtime(true));
			$addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
			$keyhash = $key . $addKey;
			$today_DATE = date('Y-m-d');

			$today_reset_password_generated_count = sqlQUERY_LABEL("SELECT `pwd_reset_ID` FROM `dvi_pwd_reset_log` where DATE(`createdon`) = '$today_DATE' and `deleted` = '0' and `email_ID` = '$sanitize_forgot_pwd_email'") or die("#1-today_reset_password_generated_count" . sqlERROR_LABEL());
			$get_today_reset_pwd_num_rows = sqlNUMOFROW_LABEL($today_reset_password_generated_count);

			if ($get_today_reset_pwd_num_rows < 3) :
				// INSERT PASSWORD RESET LOG
				$check_reset_password_already_generated = sqlQUERY_LABEL("SELECT `pwd_reset_ID` FROM `dvi_pwd_reset_log` where DATE(`createdon`) = '$today_DATE' and `status` = '0' and `deleted` = '0' and `email_ID` = '$sanitize_forgot_pwd_email'") or die("#1-check_reset_password_already_generated" . sqlERROR_LABEL());
				$get_reset_pwd_num_rows = sqlNUMOFROW_LABEL($check_reset_password_already_generated);

				if ($get_reset_pwd_num_rows == 0) :
					$arrFields = array('`email_ID`', '`userID`', '`guide_ID`', '`vendor_ID`', '`staff_ID`', '`agent_ID`', '`reset_key`', '`expiry_date`', '`createdby`', '`status`');
					$arrValues = array("$sanitize_forgot_pwd_email", "$userID", "$guide_id", "$vendor_id", "$staff_id", "$agent_id", "$keyhash", "$expiry_date", "$userID", "0");
					if (sqlACTIONS("INSERT", "dvi_pwd_reset_log", $arrFields, $arrValues, '')) :
						//INSERT RESET PASSWORD LOG
						$response['result'] = true;
						$response['result_success'] = 'If this email address was used to create an account, </br> instructions to reset your password will be sent to you. Please check your email.';
					else :
						$response['result'] = false;
						$response['result_error'] = 'Something went wrong !!!';
					endif;
				else :
					$arrFields = array('`email_ID`', '`userID`', '`guide_ID`', '`vendor_ID`', '`staff_ID`', '`agent_ID`',  '`reset_key`', '`expiry_date`', '`createdby`', '`status`');
					$arrValues = array("$sanitize_forgot_pwd_email", "$userID", "$guide_id", "$vendor_id", "$staff_id", "$agent_id", "$keyhash", "$expiry_date", "$userID", "0");
					$sqlWhere = " `email_ID` = '$sanitize_forgot_pwd_email' and `userID` = '$userID' and `guide_ID` = '$guide_id' and `vendor_ID` = '$vendor_id' and `staff_ID` = '$staff_id' and `agent_ID` = '$agent_id' and `status` = '0' ";
					if (sqlACTIONS("UPDATE", "dvi_pwd_reset_log", $arrFields, $arrValues, $sqlWhere)) :
						//UPDATE RESET PASSWORD LOG
						$response['result'] = true;
						$response['result_success'] = 'If this email address was used to create an account, instructions to reset your password will be sent to you. Please check your email.';
					else :
						$response['result'] = false;
						$response['result_error'] = 'Something went wrong !!!';
					endif;
				endif;
			else :
				$response['result_error'] = 'You have reached the daily Limit. !!!';
			endif;

			$select_global_settings_details = sqlQUERY_LABEL("SELECT `site_title`, `company_logo` FROM `dvi_global_settings` WHERE `status`='1' and `deleted` = '0'") or die("#1-UNABLE_TO_GET_GLOBAL_SETTINGS:" . sqlERROR_LABEL());
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_global_settings_details)) :
				$site_title = $fetch_data['site_title'];
				$logo = $fetch_data['company_logo'];
			endwhile;

			if ($response['result'] == true) :
				$custome_message = "That's okay, it happens! Click on the button below <br> to reset your password";
				$forgot_your_password_title = "Forgot Your Password?";
				$rest_link = BASEPATH . 'resetpassword.php?key=' . $keyhash . '&email=' . $encoded_forgot_pwd_email . '&action=reset';
				$current_YEAR = date('Y');
				$footer_content = "Made by $site_title<br> &copy; $current_YEAR. All Rights Reserved $site_title | Privacy Policy | Terms & Conditions";
				$message_template = '<!DOCTYPE html>
				<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
				<head>
					<title></title>
					<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
					<meta content="width=device-width, initial-scale=1.0" name="viewport" />
					<style>
						* {
						box-sizing: border-box;
						}
						body {
						margin: 0;
						padding: 0;
						}
						a[x-apple-data-detectors] {
						color: inherit !important;
						text-decoration: inherit !important;
						}
						#MessageViewBody a {
						color: inherit;
						text-decoration: none;
						}
						p {
						line-height: inherit
						}
						.desktop_hide,
						.desktop_hide table {
						mso-hide: all;
						display: none;
						max-height: 0px;
						overflow: hidden;
						}
						.menu_block.desktop_hide .menu-links span {
						mso-hide: all;
						}
						@media (max-width:700px) {
						.desktop_hide table.icons-inner,
						.social_block.desktop_hide .social-table {
						display: inline-block !important;
						}
						.icons-inner {
						text-align: center;
						}
						.icons-inner td {
						margin: 0 auto;
						}
						.fullMobileWidth,
						.image_block img.big,
						.row-content {
						width: 100% !important;
						}
						.menu-checkbox[type=checkbox]~.menu-links {
						display: none !important;
						padding: 5px 0;
						}
						.menu-checkbox[type=checkbox]:checked~.menu-trigger .menu-open {
						display: none !important;
						}
						.menu-checkbox[type=checkbox]:checked~.menu-links,
						.menu-checkbox[type=checkbox]~.menu-trigger {
						display: block !important;
						max-width: none !important;
						max-height: none !important;
						font-size: inherit !important;
						}
						.menu-checkbox[type=checkbox]~.menu-links>a,
						.menu-checkbox[type=checkbox]~.menu-links>span.label {
						display: block !important;
						text-align: center;
						}
						.menu-checkbox[type=checkbox]:checked~.menu-trigger .menu-close {
						display: block !important;
						}
						.mobile_hide {
						display: none;
						}
						.stack .column {
						width: 100%;
						display: block;
						}
						.mobile_hide {
						min-height: 0;
						max-height: 0;
						max-width: 0;
						overflow: hidden;
						font-size: 0px;
						}
						.desktop_hide,
						.desktop_hide table {
						display: table !important;
						max-height: none !important;
						}
						}
						#memu-r7c0m2:checked~.menu-links {
						background-color: #000000 !important;
						}
						#memu-r7c0m2:checked~.menu-links a,
						#memu-r7c0m2:checked~.menu-links span {
						color: #ffffff !important;
						}
					</style>
				</head>
				<body style="background-color: #F8F7F2; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
					<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
						style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F8F7F2;" width="100%">
						<tbody>
							<tr>
							<td>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 536px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="100%">
														<div class="spacer_block"
															style="height:30px;line-height:30px;font-size:1px;"></div>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 536px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="33.333333333333336%">
														<div class="spacer_block"
															style="height:10px;line-height:5px;font-size:1px;"> </div>
													</td>
													<td class="column column-2"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="33.333333333333336%">
														<table border="0" cellpadding="0" cellspacing="0"
															class="image_block block-2" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
															width="100%">
															<tr>
																<td class="pad"
																style="width:100%;padding-right:0px;padding-left:0px;padding-top:5px;padding-bottom:5px;">
																<div align="center" class="alignment"
																	style="line-height:10px"><img alt="Company Logo"
																	src="' . BASEPATH . '/uploads/logo/' . $logo . '"
																	style="display: block; height: auto; border: 0; max-width: 100%;"
																	title="Company Logo" /></div>
																</td>
															</tr>
														</table>
													</td>
													<td class="column column-3"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="33.333333333333336%">
														<div class="spacer_block"
															style="height:10px;line-height:5px;font-size:1px;"></div>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 536px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="100%">
														<div class="spacer_block"
															style="height:30px;line-height:10px;font-size:1px;"></div>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 536px; border-top-left-radius: 10px;  border-top-right-radius: 10px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 30px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="100%">
														<table border="0" cellpadding="15" cellspacing="0"
															class="image_block block-1" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
															width="100%">
															<tr>
																<td class="pad">
																<div align="center" class="alignment"
																	style="line-height:10px"><img
																	alt="Lock_IMG" class="fullMobileWidth"
																	src="' . BASEPATH . '/assets/img/forgot_password.png"
																	style="display: block; height: auto; border: 0; width: 125px !important; max-width: 100%;"
																	title="Resetting Password"/></div>
																</td>
															</tr>
														</table>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-6"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 536px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="10%">
														<div class="spacer_block"
															style="height:10px;line-height:5px;font-size:1px;">
														</div>
													</td>
													<td class="column column-2"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="80%">
														<table border="0" cellpadding="0" cellspacing="0"
															class="heading_block block-3" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
															width="100%">
															<tr>
																<td class="pad"
																style="text-align:center;width:100%;padding-top:22px;">
																<h1
																	style="margin: 0; color: #272e62; direction: ltr; font-family: Public Sans, sans-serif; font-size: 24px; font-weight: normal; letter-spacing: normal; line-height: 21px; text-align: center; margin-top: 0; margin-bottom: 0;">
																	<strong>' . $forgot_your_password_title . '</strong>
																</h1>
																</td>
															</tr>
														</table>
														<table border="0" cellpadding="0" cellspacing="0"
															class="text_block block-2" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
															width="100%">
															<tr>
																<td class="pad"
																style="padding-bottom:10px;padding-left:20px;padding-right:10px;padding-top:16px;">
																<div style="font-family: sans-serif">
																	<div class=""
																		style="font-size: 12px; mso-line-height-alt: 21.6px; color: #24172E; line-height: 1.2; font-family: Public Sans, sans-serif;">
																		<p
																			style="margin: 0; font-size: 16px; text-align: center; mso-line-height-alt: 25.2px;">
																			<span style="font-size:16px;">' . $custome_message . '</span>
																		</p>
																	</div>
																</div>
																</td>
															</tr>
														</table>
														<table border="0" cellpadding="0" cellspacing="0"
															class="button_block block-4" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
															width="100%">
															<tr>
																<td class="pad"
																style="padding-bottom:40px;padding-left:10px;padding-right:10px;padding-top:22px;text-align:center;">
																<div align="center" class="alignment">
																   <!--[if mso]>
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding: 0 10px;">
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" 
                         href="' . $rest_link . '" 
                         style="height:40px;v-text-anchor:middle;width:200px;" 
                         arcsize="10%" strokecolor="#001255" fillcolor="#001255">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:sans-serif;font-size:14px;white-space:nowrap;">
                   Reset Password
                </center>
            </v:roundrect>
        </td>
    </tr>
</table>
<![endif]-->
<![if !mso]>
<a href="' . $rest_link . '" 
   target="_blank" 
   style="background:#001255;border-radius:7px;color:#ffffff;display:inline-block;font-size:14px;padding:10px 20px;text-decoration:none;text-align:center;white-space:nowrap;">
   Reset Password
</a>
<![endif]>
																</div>
																</td>
															</tr>
														</table>
													</td>
													<td class="column column-3"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="10%">
														<div class="spacer_block"
															style="height:10px;line-height:5px;font-size:1px;"> </div>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-8"
									role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
									<tbody>
										<tr>
										<td>
											<table align="center" border="0" cellpadding="0" cellspacing="0"
												class="row-content stack" role="presentation"
												style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 536px;"
												width="536">
												<tbody>
													<tr>
													<td class="column column-1"
														style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
														width="100%">
														<table border="0" cellpadding="0" cellspacing="0"
															class="social_block block-2" role="presentation"
															style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
															width="100%">
															<tr>
																<td class="pad"
																style="padding-bottom:0px;padding-left:10px;padding-right:10px;padding-top:30px;text-align:center;">
																<div align="center" class="alignment">
																	<table border="0" cellpadding="0" cellspacing="0"
																		class="social-table" role="presentation"
																		style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;"
																		width="100%">
																		<tr>
																			<td style="padding:0 2px 0 2px;">
																			<p style="word-break: break-word; line-height: 24px; padding-top:0px;padding-bottom:0px;font-family:Public Sans, sans-serif;font-size:14px;text-align:center;font-weight:400; word-break:keep-all;color:#808080">' . $footer_content . '</p>
																			</td>
																		</tr>
																	</table>
																</div>
																</td>
															</tr>
														</table>
													</td>
													</tr>
												</tbody>
											</table>
										</td>
										</tr>
									</tbody>
								</table>
							</td>
							</tr>
						</tbody>
					</table>
				</body>
				</html>';

				$subject = "Reset your password on $site_title";
				$send_from = "$SMTP_EMAIL_SEND_FROM";
				$to = $sanitize_forgot_pwd_email; // $admin_emailid; 
				$Bcc = $bcc_emailid;
				$cc = $cc_emailid;
				$sender_name = "$SMTP_EMAIL_SEND_NAME";
				$reply_to = null;
				SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);
				$response['success'] = true;
				$response['html_result'] = 'If this email address was used to create an account, instructions to reset your password will be sent to you. Please check your email.';
			endif;
		endif;
		echo json_encode($response);
	endif;
else :
	echo "Request Ignored !!!";
endif;
