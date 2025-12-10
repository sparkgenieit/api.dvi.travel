<?php

include_once('jackus.php');

$title                       = 'Your vehicle assigned to a specific Itinerary!';
$sub_title                   = 'Dear [Uma],';
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
    /* …all your original media-queries and CSS exactly as before… */
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
                        On ground support Number : 
                    </td>
                </tr>
            </table>
        </th>
        <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:7px; font-size:15px; text-align:left;">
                      ' . $arrival_location . ' -  ' . $departure_location . '
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
                      ' . $arrival_location . ' -  ' . $departure_location . '
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
                        Escalation Call Number :
                    </td>
                </tr>
            </table>
        </th>
        <td align="left" align="top" style="border:1px solid #000000; font-size:15px; padding:0; width:300px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:7px; font-size:15px; text-align:left;">
                      ' . $arrival_location . ' -  ' . $departure_location . '
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

echo $message_template;
