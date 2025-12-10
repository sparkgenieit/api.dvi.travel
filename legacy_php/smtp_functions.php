<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
require_once 'phpmailer_vendor/autoload.php';

// Ensure the function is defined only once
if (!function_exists('flattenAndValidateEmails')) {
	function flattenAndValidateEmails($emails)
	{
		$valid_emails = [];
		if (is_array($emails)) {
			foreach ($emails as $email) {
				if (is_array($email)) {
					$valid_emails = array_merge($valid_emails, flattenAndValidateEmails($email));
				} elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$valid_emails[] = $email;
				}
			}
		} elseif (filter_var($emails, FILTER_VALIDATE_EMAIL)) {
			$valid_emails[] = $emails;
		}
		return $valid_emails;
	}
}

function SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body, $attachment = NULL)
{

	// Replace smtp_username with your Amazon SES SMTP user name.
	$AWS_SMTP_UN = '908cde001@smtp-brevo.com';
	// Replace smtp_password with your Amazon SES SMTP password.
	$AWS_SMTP_PWD = '9pXAzBPN7rKCxgMj';
	$SMTP_HOST = 'smtp-relay.brevo.com';
	$SMTP_PORT = 2525;

	$default_bcc = 'vsr@dvi.co.in';
	$mail = new PHPMailer(true);
	try {
		// Server settings
		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->SetFrom($send_from, $sender_name);
		$mail->Username = $AWS_SMTP_UN; // SMTP username
		$mail->Password = $AWS_SMTP_PWD; // SMTP password
		$mail->Host = $SMTP_HOST; // Specify main and backup SMTP servers
		$mail->Port = $SMTP_PORT; // 465 - https | 587 - http | 2465 - AWS CLOUD
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted

		// Collect and validate TO addresses
		$to_addresses = flattenAndValidateEmails($to);

		// Collect and validate BCC addresses
		$bcc_addresses = flattenAndValidateEmails($Bcc);

		// Collect and validate CC addresses
		$cc_addresses = flattenAndValidateEmails($cc);

		// Collect and validate Replyto addresses
		$replyto_addresses = flattenAndValidateEmails($reply_to);

		// Always ensure the default BCC is included
		if (!in_array($default_bcc, $bcc_addresses)) {
			$bcc_addresses[] = $default_bcc;
		}

		// Add addresses to the mail object
		foreach ($to_addresses as $email) {
			$mail->addAddress($email);
		}
		foreach ($bcc_addresses as $email) {
			$mail->addBCC($email);
		}
		foreach ($cc_addresses as $email) {
			$mail->addCC($email);
		}
		foreach ($replyto_addresses as $email) {
			$mail->addReplyTo($email);
		}

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $body;

		if ($attachment) {
			$mail->addAttachment($attachment);
		}

		$send = $mail->send();
		return $send;
	} catch (Exception $e) {
		// Handle PHPMailer exceptions
		// error_log("PHPMailer error: {$e->getMessage()}");
		// return false;
	} catch (\Exception $e) {
		// Handle other exceptions
		// error_log("General error: {$e->getMessage()}");
		// return false;
	}
}
