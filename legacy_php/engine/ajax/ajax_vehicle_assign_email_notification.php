
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

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

        $itinerary_plan_ID = $_SESSION['global_itinerary_plan_ID'];
        $vendor_id = $_SESSION['global_vendor_id'];
        $vendor_vehicle_type_id = $_SESSION['global_vendor_vehicle_type_id'];
        $vehicle_id = $_SESSION['global_vehicle_id'];
        $driver_id = $_SESSION['global_driver_id'];
        $trip_start_date_and_time = $_SESSION['global_trip_start_date_and_time'];
        $trip_start_date_formatted = date("M d Y, h:i A", strtotime($trip_start_date_and_time));
        $trip_end_date_and_time = $_SESSION['global_trip_end_date_and_time'];
        $trip_end_date_formatted = date("M d Y, h:i A", strtotime($trip_end_date_and_time));
        $assigned_vehicle_status = $_SESSION['global_assigned_vehicle_status'];
        $vendor_state = getVENDORANDVEHICLEDETAILS($vendor_id, 'vendor_state', '');
        $vehicle_escalation_call_number = getVENDORANDVEHICLEDETAILS(101, $vendor_state, 'vehicle_escalation_call_number');
        $vehicle_onground_support_number = getVENDORANDVEHICLEDETAILS(101, $vendor_state, 'vehicle_onground_support_number');
        $assigned_on = $_SESSION['global_assigned_on'];

        $no_of_days = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');

        $vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'get_vehicle_type_id');
        $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $agent_ID = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');

        $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');

        $get_guest_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $get_guest_name_contact_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
        $get_salutation = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'customer_salutation');

        $get_driver_name = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
        $get_driver_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
        $vehicle_count = getCONFIRMED_ITINERARY_VEHICLE_COUNT($itinerary_plan_ID, $vehicle_type_id);
        $get_registration_number = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_registration_number', "");

        //Get Deafult Vehicle Email ID
        $global_default_vehicle_email = getGLOBALSETTING('default_vehicle_voucher_email_id');

        // Convert the comma-separated email IDs into an array
        $global_default_vehicle_email_array = explode(',', $global_default_vehicle_email);

        // Trim any whitespace from each email ID
        $global_default_vehicle_email_array = array_map('trim', $global_default_vehicle_email_array);

        if ($vehicle_id) :
            $get_vendor_email = getVENDORNAMEDETAIL($vehicle_id, 'get_vendor_email');
            $get_agent_email = getAGENT_details($agent_ID, '', 'get_agent_email_address');
            $travel_expert_id = getAGENT_details($agent_ID, '', 'travel_expert_id');
            $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
            $travel_expert_staff_mobile = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
            $email_to = [$travel_expert_staff_email, $admin_emailid, $get_vendor_email, $get_agent_email, $global_default_vehicle_email_array];
        else :
            $email_to = [$admin_emailid, $global_default_vehicle_email_array];
        endif;

        $title                       = 'Your vehicle assigned to a specific Itinerary!';
        $sub_title                   = 'Dear [' . $get_driver_name . '],';
        $site_title                  = getGLOBALSETTING('site_title');
        $company_name                = getGLOBALSETTING('company_name');
        $company_email_id            = getGLOBALSETTING('company_email_id');
        $company_address             = getGLOBALSETTING('company_address');
        $company_contact_no          = getGLOBALSETTING('company_contact_no');
        $current_YEAR                = date('Y');
        $description                 = "We're pleased to inform you that your vehicle has been <b> successfully assigned </b> to the itinerary for your upcoming trip. Our team has ensured that all arrangements are in place for a smooth and comfortable travel experience. Should you need any further assistance or have specific preferences, please feel free to reach out to us.";
        $sub_description             = "<b>Thank you for choosing us. We wish you a pleasant journey!</b>";
        $site_logo                   = BASEPATH . 'assets/img/' . getGLOBALSETTING('company_logo');
        $footer_content              = "Copyright &copy; $current_YEAR | $company_name";

        $message_template =
            '<!DOCTYPE html>
        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
        <head>
          <meta http-equiv="X-UA-Compatible" content="IE=edge" />
          <meta name="viewport" content="width=device-width, initial-scale=1" />
          <meta name="format-detection" content="telephone=no" />
          <!--[if mso]>
            <style type="text/css">
              .es-content-body, .es-footer-body {width:600px !important;}
            </style>
          <![endif]-->
        
          <meta charset="UTF-8" />
          <link href="https://fonts.googleapis.com/css2?family=Imprima&display=swap" rel="stylesheet" />
          <style type="text/css">
            body { font-family: "DM Sans", sans-serif; }
            #outlook a { padding: 0; }
            .es-button { mso-style-priority: 100 !important; text-decoration: none !important; }
            a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
            .es-desk-hidden { display: none; float: left; overflow: hidden; width: 0; max-height: 0; line-height: 0; mso-hide: all; }
         @media only screen and (max-width: 600px) {

         p,
         ul li,
         ol li,
         a {
             line-height: 150% !important;
         }

         h1,
         h2,
         h3,
         h1 a,
         h2 a,
         h3 a {
             line-height: 120%;
         }

         h1 {
             font-size: 30px !important;
             text-align: left;
         }

         h2 {
             font-size: 24px !important;
             text-align: left;
         }

         h3 {
             font-size: 20px !important;
             text-align: left;
         }

         .es-header-body h1 a,
         .es-content-body h1 a,
         .es-footer-body h1 a {
             font-size: 30px !important;
             text-align: left;
         }

         .es-header-body h2 a,
         .es-content-body h2 a,
         .es-footer-body h2 a {
             font-size: 24px !important;
             text-align: left;
         }

         .es-header-body h3 a,
         .es-content-body h3 a,
         .es-footer-body h3 a {
             font-size: 20px !important;
             text-align: left;
         }

         .es-menu td a {
             font-size: 14px !important;
         }

         .es-header-body p,
         .es-header-body ul li,
         .es-header-body ol li,
         .es-header-body a {
             font-size: 14px !important;
         }

         .es-content-body p,
         .es-content-body ul li,
         .es-content-body ol li,
         .es-content-body a {
             font-size: 14px !important;
         }

         .es-footer-body p,
         .es-footer-body ul li,
         .es-footer-body ol li,
         .es-footer-body a {
             font-size: 14px !important;
         }

         .es-infoblock p,
         .es-infoblock ul li,
         .es-infoblock ol li,
         .es-infoblock a {
             font-size: 12px !important;
         }

         *[class="gmail-fix"] {
             display: none !important;
         }

         .es-m-txt-c,
         .es-m-txt-c h1,
         .es-m-txt-c h2,
         .es-m-txt-c h3 {
             text-align: center !important;
         }

         .es-m-txt-r,
         .es-m-txt-r h1,
         .es-m-txt-r h2,
         .es-m-txt-r h3 {
             text-align: right !important;
         }

         .es-m-txt-l,
         .es-m-txt-l h1,
         .es-m-txt-l h2,
         .es-m-txt-l h3 {
             text-align: left !important;
         }

         .es-m-txt-r img,
         .es-m-txt-c img,
         .es-m-txt-l img {
             display: inline !important;
         }

         .es-button-border {
             display: block !important;
         }

         a.es-button,
         button.es-button {
             font-size: 18px !important;
             display: block !important;
             border-right-width: 0px !important;
             border-left-width: 0px !important;
             border-top-width: 15px !important;
             border-bottom-width: 15px !important;
         }

         .es-adaptive table,
         .es-left,
         .es-right {
             width: 100% !important;
         }

         .es-content table,
         .es-header table,
         .es-footer table,
         .es-content,
         .es-footer,
         .es-header {
             width: 100% !important;
             max-width: 600px !important;
         }

         .es-adapt-td {
             display: block !important;
             width: 100% !important;
         }

         .adapt-img {
             width: 100% !important;
             height: auto !important;
         }

         .es-m-p0 {
             padding: 0px !important;
         }

         .es-m-p0r {
             padding-right: 0px !important;
         }

         .es-m-p0l {
             padding-left: 0px !important;
         }

         .es-m-p0t {
             padding-top: 0px !important;
         }

         .es-m-p0b {
             padding-bottom: 0 !important;
         }

         .es-m-p20b {
             padding-bottom: 20px !important;
         }

         .es-mobile-hidden,
         .es-hidden {
             display: none !important;
         }

         tr.es-desk-hidden,
         td.es-desk-hidden,
         table.es-desk-hidden {
             width: auto !important;
             overflow: visible !important;
             float: none !important;
             max-height: inherit !important;
             line-height: inherit !important;
         }

         tr.es-desk-hidden {
             display: table-row !important;
         }

         table.es-desk-hidden {
             display: table !important;
         }

         td.es-desk-menu-hidden {
             display: table-cell !important;
         }

         .es-menu td {
             width: 1% !important;
         }

         table.es-table-not-adapt,
         .esd-block-html table {
             width: auto !important;
         }

         table.es-social {
             display: inline-block !important;
         }

         table.es-social td {
             display: inline-block !important;
         }

         .es-desk-hidden {
             display: table-row !important;
             width: auto !important;
             overflow: visible !important;
             max-height: inherit !important;
         }
     }

     @media screen and (max-width: 384px) {
         .mail-message-content {
             width: 414px !important;
         }
     }

     :root {
         --line-border-fill: #3498db;
         --line-border-empty: #e0e0e0;
     }

     .container {
         text-align: center;
     }

     .progress-container {
         display: flex;
         justify-content: space-between;
         position: relative;
         margin-bottom: 40px;
         max-width: 100%;
         width: 380px;
     }

     .progress-container::before {
         content: "";
         /* Mandatory with ::before */
         background-color: #e0e0e0;
         position: absolute;
         top: 70%;
         left: 0;
         transform: translateY(-50%);
         height: 2px;
         width: 100%;
         z-index: 1;
     }

     .progress {
         background-color: var(--line-border-fill);
         position: absolute;
         top: 50%;
         left: 0;
         transform: translateY(-50%);
         height: 4px;
         width: 0%;
         z-index: -1;
         transition: 0.4s ease;
     }

     .label {
         font-size: 12px;
         color: #999;
         margin-bottom: 5px;
     }

     .circle {
         position: relative;
         /* Ensure proper positioning of the label */
         background-color: #fff;
         color: #999;
         border-radius: 50%;
         height: 45px;
         /* Adjust size as needed */
         width: 45px;
         /* Adjust size as needed */
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         border: 3px solid var(--line-border-empty);
         transition: 0.4s ease;
         z-index: 2;
         margin-top: 30px;
         /* Adjust margin between circles */
     }

     .circle img {
         max-width: calc(100% - 20px);
         /* Adjust the space around the image */
         max-height: calc(100% - 20px);
         /* Adjust the space around the image */
     }

     .circle .label {
         position: absolute;
         top: -28px;
         /* Adjust label position above the circle */
         white-space: nowrap;
     }

     .circle.active {
         border-color: var(--line-border-fill);
     }
          </style>
        </head>
        
        <body style="width:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; padding:0; margin:0;">
          <div dir="ltr" class="es-wrapper-color" lang="en" style="background-color:#ffffff;">
            <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; padding:0; margin:0; width:100%; height:100%; background-repeat:repeat; background-position:center top; background-color:#ffffff;">
              <tr>
                <td align="top" style="padding:0; margin:0;">
        
                  <!-- header -->
                  <table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; table-layout:fixed!important; width:100%; background-color:transparent;">
                    <tr>
                      <td align="center" style="padding:0; margin:0;">
                        <!--[if mso]>
                          <table width="600" cellpadding="0" cellspacing="0"><tr><td>
                        <![endif]-->
                        <table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; background-color:#ffffff; width:600px;">
                          <tr>
                            <td align="center" bgcolor="#ffffff" style="padding:20px; border:5px solid #f6f8fa; margin:0; border-bottom:0px; background-color:#ffffff;">
                              <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px;">
                                <tr>
                                  <td align="center" style="padding:0; margin:0; width:540px;">
                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px;">
                                      <tr>
                                        <td align="center" style="padding:0; margin:0; font-size:0px;">
                                          <a target="_blank" href="#" style="text-decoration:underline; color:#2d3142; font-size:14px;">
                                            <img src="' . BASEPATH . 'assets/img/logo-preview.png" alt="Logo" title="Logo" height="100" style="display:block; border:0; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic;" />
                                          </a>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso]>
                          </td></tr></table>
                        <![endif]-->
                      </td>
                    </tr>
                  </table>
        
                  <!-- main content -->
                  <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; table-layout:fixed!important; width:100%;">
                    <tr>
                      <td align="center" style="padding:0; margin:0;">
                        <!--[if mso]>
                          <table width="600" cellpadding="0" cellspacing="0"><tr><td>
                        <![endif]-->
                        <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; width:600px;">
                          <tr>
                            <td align="left" bgcolor="#ffffff" style="padding:0; margin:0; background-color:#ffffff;">
                              <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px;">
                                <tr>
                                  <td align="center" style="padding:0; margin:0; width:560px;">
                                    <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fff" role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:separate; border-spacing:0px; background-color:#fff; border:5px solid #f6f8fa;">
                                      <tr>
                                        <td align="left" class="es-m-txt-c" style="padding:40px 40px 0 40px; margin:0;">
                                          <h2 style="margin:0; font-size:18px; font-weight:bold; color:#2d3142; line-height:1.2;">' . $title . '</h2>
                                          <h3 style="margin:0; font-size:14px; font-weight:bold; color:#2d3142; padding:20px 0px 10px 0px; line-height:1.2;">' . $sub_title . '</h3>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="left" style="padding:0 40px; margin:0;">
                                          <p style="margin:0; line-height:18px; color:#2d3142; font-size:14px;">' . $description . '</p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="left" style="padding:10px 40px 30px 40px; margin:0;">
                                          <p style="margin:0; line-height:18px; color:#2d3142; font-size:14px;">' . $sub_description . '</p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="center" style="padding:0 40px 40px 40px; margin:0; width:560px;">
                                          <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; width:100%; background-color:#ffffff;">
                                          <thead>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                 Quote Id :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              <a href="' . BASEPATH . 'latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '">' . $itinerary_quote_ID . '</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Guest Name :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                               ' . $get_salutation . ' ' . $get_guest_name . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Guest Mobile No :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px; border-bottom:0;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                               ' . $get_guest_name_contact_no . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Arrival Date & Time:
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                               ' . $trip_start_date_formatted . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Arrival at :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $arrival_location . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                 Departure Date & Time :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $trip_end_date_formatted . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Departure at :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $departure_location . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                  Driver Name :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                                 ' . $get_driver_name . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                  Driver Mobile No :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                                 ' . $get_driver_mobile_no . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                 Vehicle Type :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $get_vehicle_type_title . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                 Quantity :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $vehicle_count . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                 Vehicle Number :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $get_registration_number . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                On ground support Number : ' . $vehicle_onground_support_number . '
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $vehicle_onground_support_number . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
              <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Travel Expert Number :
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $travel_expert_staff_mobile . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
              <tr>
                <th align="left" align="top" style="border:1px solid #000000; font-size:14px; padding:0; border-right:0; width:300px; color:#001255;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:14px; color:#001255; text-align:left;">
                                Escalation Call Number : ' . $vehicle_escalation_call_number . '
                            </td>
                        </tr>
                    </table>
                </th>
                <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:7px; font-size:15px; text-align:left;">
                              ' . $vehicle_escalation_call_number . '
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso]>
                          </td></tr></table>
                        <![endif]-->
                      </td>
                    </tr>
                  </table>
        
                  <!-- footer -->
                  <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; table-layout:fixed!important; width:100%;">
                    <tr>
                      <td align="center" style="padding:0; margin:0;">
                        <!--[if mso]>
                          <table width="600" cellpadding="0" cellspacing="0"><tr><td>
                        <![endif]-->
                        <table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px; background-color:#efefef; width:600px;">
                          <tr>
                            <td align="center" bgcolor="#ffffff" style="margin:0; padding:40px; background-color:#ffffff; border:5px solid #f6f8fa; border-top:0;">
                              <table cellpadding="0" cellspacing="0" width="100%" role="none" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px;">
                                <tr>
                                  <td align="center" style="padding:0; margin:0; width:560px;">
                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse; border-spacing:0px;">
                                      <tr>
                                        <td align="center" style="padding:0; margin:0;">
                                          <p style="margin:0; line-height:18px; color:#2d3142; font-size:12px;">' . getGLOBALSETTING('company_name') . '<br />' . getGLOBALSETTING('company_contact_no') . ', ' . getGLOBALSETTING('company_email_id') . '<br />' . getGLOBALSETTING('company_address') . ' – ' . getGLOBALSETTING('company_pincode') . '.</p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="center" style="padding:20px 0 0 0; margin:0;">
                                          <p style="margin:0; line-height:18px; color:#2d3142; font-size:12px;">' . $footer_content . '</p>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso]>
                          </td></tr></table>
                        <![endif]-->
                      </td>
                    </tr>
                  </table>
        
                </td>
              </tr>
            </table>
          </div>
        </body>
        </html>';
        $subject = "$site_title - Vehicle Assigned for #$itinerary_quote_ID";
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = [$email_to];
        $cc = [$cc_email_id];
        $Bcc = [$bcc_emailid];
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

    else :
        echo "Request Ignored";
    endif;
