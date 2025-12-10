<?php

include_once('jackus.php');
include_once('smtp_functions.php');

        $subject = "$site_title - Itinerary Vehicle Payment #$global_confirmed_itinerary_quote_ID";
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = $email_to;
        $Bcc = $bcc_emailid;
        $cc = $cc_emailid;
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        $reply_to = [$global_travel_expert_staff_email];
        $reply_to = $reply_to;
        $body = 'Test from Microsoft 365';
  
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body);
