
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

        $global_hotel_name = $_SESSION['global_hotel_name'];
        $global_g_hotel_booking_status_label = $_SESSION['global_g_hotel_booking_status_label'];
        $global_g_hotel_mobile_no = $_SESSION['global_g_hotel_mobile_no'];
        $global_g_hotel_verified_by = $_SESSION['global_g_hotel_verified_by'];
        $global_g_reservation_no = $_SESSION['global_g_reservation_no'];
        $global_g_hotel_email = $_SESSION['global_g_hotel_email'];
        // Convert the comma-separated email IDs into an array
        $global_hotel_email_array = explode(',', $global_g_hotel_email);

        // Trim any whitespace from each email ID
        $global_hotel_email_array = array_map('trim', $global_hotel_email_array);

        //Get Deafult Hotel Email ID
        $global_default_hotel_email = getGLOBALSETTING('default_hotel_voucher_email_id');

        // Convert the comma-separated email IDs into an array
        $global_default_hotel_email_array = explode(',', $global_default_hotel_email);

        // Trim any whitespace from each email ID
        $global_default_hotel_email_array = array_map('trim', $global_default_hotel_email_array);

        $company_youtube = '#';
        $company_facebook = '#';
        $company_instagram = '#';
        $company_linkedin = '#';

        if ($global_travel_expert_staff_email) :
            $bcc_emailid = $global_travel_expert_staff_email;
        endif;

        $email_to = [$global_hotel_email_array, $global_default_hotel_email_array];

        $cc_emailid = $admin_emailid;

        //$bcc_emailid = $admin_emailid;

        $title = 'Hotel Voucher - ' . $global_hotel_status;
        $site_title = getGLOBALSETTING('site_title');
        $company_name = getGLOBALSETTING('company_name');
        $company_email_id = getGLOBALSETTING('company_email_id');
        $company_contact_no = getGLOBALSETTING('company_contact_no');
        $current_YEAR = date('Y');
        $description = "An itinerary has been confirmed by our agent and requires your approval. Please review the details below and take the necessary action to approve this itinerary.";
        $site_logo = BASEPATH . '/assets/img/' . getGLOBALSETTING('company_logo');
        $footer_content = " Copyright &copy; $current_YEAR | $company_name";

        $message_template = '<!DOCTYPE html>
                    <html
                    dir="ltr"
                    xmlns="http://www.w3.org/1999/xhtml"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    lang="en"
                    >
                    <head>
                        <meta charset="UTF-8" />
                        <meta content="width=device-width, initial-scale=1" name="viewport" />
                        <title>Account Verified</title>
                        <link
                        href="https://fonts.googleapis.com/css2?family=Imprima&display=swap"
                        rel="stylesheet"
                        />
                        <style type="text/css">
                        body {
                            font-family: "DM Sans", sans-serif;
                        }
                        #outlook a {
                            padding: 0;
                        }
                        .es-button {
                            mso-style-priority: 100 !important;
                            text-decoration: none !important;
                        }
                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: none !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }
                        .es-desk-hidden {
                            display: none;
                            float: left;
                            overflow: hidden;
                            width: 0;
                            max-height: 0;
                            line-height: 0;
                            mso-hide: all;
                        }
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
                            content: ""; /* Mandatory with ::before */
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
                            position: relative; /* Ensure proper positioning of the label */
                            background-color: #fff;
                            color: #999;
                            border-radius: 50%;
                            height: 45px; /* Adjust size as needed */
                            width: 45px; /* Adjust size as needed */
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            border: 3px solid var(--line-border-empty);
                            transition: 0.4s ease;
                            z-index: 2;
                            margin-top: 30px; /* Adjust margin between circles */
                        }

                        .circle img {
                            max-width: calc(100% - 20px); /* Adjust the space around the image */
                            max-height: calc(100% - 20px); /* Adjust the space around the image */
                        }

                        .circle .label {
                            position: absolute;
                            top: -28px; /* Adjust label position above the circle */
                            white-space: nowrap;
                        }

                        .circle.active {
                            border-color: var(--line-border-fill);
                        }
                        </style>
                    </head>
                    <body
                        style="
                        width: 100%;
                        -webkit-text-size-adjust: 100%;
                        -ms-text-size-adjust: 100%;
                        padding: 0;
                        margin: 0;
                        "
                    >
                        <div
                        dir="ltr"
                        class="es-wrapper-color"
                        lang="en"
                        style="background-color: #ffffff"
                        >
                        <table
                            class="es-wrapper"
                            width="100%"
                            cellspacing="0"
                            cellpadding="0"
                            role="none"
                            style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            padding: 0;
                            margin: 0;
                            width: 100%;
                            height: 100%;
                            background-repeat: repeat;
                            background-position: center top;
                            background-color: #ffffff;
                            "
                        >
                            <tr>
                            <td valign="top" style="padding: 0; margin: 0">
                                <table
                                cellpadding="0"
                                cellspacing="0"
                                class="es-footer"
                                align="center"
                                role="none"
                                style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    table-layout: fixed !important;
                                    width: 100%;
                                    background-color: transparent;
                                    background-repeat: repeat;
                                    background-position: center top;
                                "
                                >
                                <tr>
                                    <td align="center" style="padding: 0; margin: 0">
                                    <table
                                        bgcolor="#bcb8b1"
                                        class="es-footer-body"
                                        align="center"
                                        cellpadding="0"
                                        cellspacing="0"
                                        role="none"
                                        style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        background-color: #ffffff;
                                        width: 600px;
                                        "
                                    >
                                        <tr>
                                        <td
                                            align="left"
                                            bgcolor="#f6f8fa"
                                            style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 20px;
                                            padding-left: 20px;
                                            padding-right: 20px;
                                            background-color: #f6f8fa;
                                            "
                                        >
                                            <table
                                            cellpadding="0"
                                            cellspacing="0"
                                            width="100%"
                                            role="none"
                                            style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                            "
                                            >
                                            <tr>
                                                <td
                                                align="center"
                                                valign="top"
                                                style="padding: 0; margin: 0; width: 540px"
                                                >
                                                <table
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                    role="presentation"
                                                    style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                    "
                                                >
                                                    <tr>
                                                    <td
                                                        align="center"
                                                        style="
                                                        padding: 0;
                                                        margin: 0;
                                                        font-size: 0px;
                                                        "
                                                    >
                                                        <a
                                                        target="_blank"
                                                        href=""
                                                        style="
                                                            -webkit-text-size-adjust: none;
                                                            -ms-text-size-adjust: none;
                                                            mso-line-height-rule: exactly;
                                                            text-decoration: underline;
                                                            color: #2d3142;
                                                            font-size: 14px;
                                                        "
                                                        ><img
                                                            src="assets/img/logo-preview.png"
                                                            alt="Logo"
                                                            style="
                                                            display: block;
                                                            border: 0;
                                                            outline: none;
                                                            text-decoration: none;
                                                            -ms-interpolation-mode: bicubic;
                                                            "
                                                            height="70"
                                                            title="Logo"
                                                        /></a>
                                                    </td>
                                                    </tr>
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



                                <table
                                cellpadding="0"
                                cellspacing="0"
                                class="es-content"
                                align="center"
                                role="none"
                                style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    table-layout: fixed !important;
                                    width: 100%;
                                "
                                >
                                <tr>
                                    <td align="center" style="padding: 0; margin: 0">
                                    <table
                                        bgcolor="#f6f8fa"
                                        class="es-content-body"
                                        align="center"
                                        cellpadding="0"
                                        cellspacing="0"
                                        style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        background-color: #f6f8fa;
                                        border-radius: 20px 20px 0px 0px;
                                        width: 600px;
                                        "
                                        role="none"
                                    >
                                        <tr>
                                        <td
                                            align="left"
                                            bgcolor="#f6f8fa"
                                            style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 20px;
                                            padding-left: 40px;
                                            padding-right: 40px;
                                            background-color: #f6f8fa;
                                            "
                                        >
                                            <table
                                            cellpadding="0"
                                            cellspacing="0"
                                            width="100%"
                                            role="none"
                                            style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                            "
                                            >
                                            <tr>
                                                <td
                                                align="center"
                                                valign="top"
                                                style="padding: 0; margin: 0; width: 560px"
                                                >
                                                <table
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                    bgcolor="#fff"
                                                    style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: separate;
                                                    border-spacing: 0px;
                                                    background-color: #fff;
                                                    border-radius: 10px;
                                                    border: 1px solid rgba(135, 70, 180, 0.1);
                                                    "
                                                    role="presentation"
                                                >
                                                    <tr>
                                                    <td
                                                        align="center"
                                                        logo
                                                        class="es-m-txt-c"
                                                        style="
                                                        padding: 0;
                                                        margin: 0;
                                                        padding-top: 10px;
                                                        padding-bottom:0px;
                                                        font-size: 0px;
                                                        "
                                                    >
                                                        <img
                                                        src="assets/img/Hotel-confirmed.png"
                                                        alt="Logo"
                                                        style="
                                                            display: block;
                                                            border: 0;
                                                            outline: none;
                                                            text-decoration: none;
                                                            -ms-interpolation-mode: bicubic;
                                                            font-size: 12px;
                                                        "
                                                        title="Logo"
                                                        width="200px"
                                                        />
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td
                                                        align="center"
                                                        logo
                                                        class="es-m-txt-c"
                                                        style="
                                                        padding: 0;
                                                        margin: 0;
                                                        padding-top: 10px;
                                                        "
                                                    >
                                                        <h3
                                                        style="
                                                            margin: 0;
                                                            mso-line-height-rule: exactly;
                                                            font-size: 24px;
                                                            font-style: normal;
                                                            font-weight: bold;
                                                            color: #2d3142;
                                                        "
                                                        >
                                                        Hotel Booking Status !!!
                                                        </h3>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td
                                                        align="center"
                                                        style="
                                                        padding: 20px;
                                                        margin: 0;
                                                        padding-top: 10px;
                                                        "
                                                    >
                                                    <p
                                                        style="
                                                            margin: 0;
                                                            -webkit-text-size-adjust: none;
                                                            -ms-text-size-adjust: none;
                                                            mso-line-height-rule: exactly;
                                                            line-height: 18px;
                                                            color: #2d3142;
                                                            font-size: 14px;
                                                            padding-right: 20px;
                                                            padding-left: 20px;
                                                        "
                                                        >
                                                        We have updated the status of the booking by <b> Mr/Ms. ' . $global_g_hotel_verified_by . '</b>. Please review all details carefully to ensure accuracy and completeness before forwarding them to our client.
                                                        </p>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top" style="padding-bottom: 30px; margin: 0; width: 560px;padding-left: 50px;padding-right: 50px;">
                                                        <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fff" style="
                                                                mso-table-lspace: 0pt;
                                                                mso-table-rspace: 0pt;
                                                                border-collapse: separate;
                                                                border-spacing: 0px;
                                                            
                                                            " role="presentation">
                                                            <thead>
                                                            <tr>
                                                                <th
                                                                style="text-align: left;border: 1px solid #000;font-size: 14px;padding: 7px;border-right: 0;width:300px;color: #001255;border-bottom: 0px;">
                                                                Hotel Name :</th>
                                                                <td
                                                                style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:300px;border-bottom: 0px;">
                                                                ' . html_entity_decode($global_hotel_name) . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th
                                                                style="text-align: left;border: 1px solid #000;font-size: 14px;padding: 7px;border-right: 0;width:300px;color: #001255;border-bottom: 0px;">
                                                                Reservation No :</th>
                                                                <td
                                                                style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:300px;border-bottom: 0px;">
                                                                ' . $global_g_reservation_no . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th
                                                                style="text-align: left; border: 1px solid; font-size: 14px; padding: 7px;border-right: 0;width:300px;color: #001255;border-bottom: 0px;">
                                                                Verified By :</th>
                                                                <td
                                                                style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:300px;border-bottom: 0px;">
                                                                ' . $global_g_hotel_verified_by . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th
                                                                style="text-align: left; border: 1px solid; font-size: 14px; padding: 7px;border-right: 0;width:300px;color: #001255;border-bottom: 0px;">
                                                                Mobile No :</th>
                                                                <td
                                                                style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:300px;border-bottom: 0px;">
                                                                ' . $global_g_hotel_mobile_no . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th
                                                                style="text-align: left; border: 1px solid; font-size: 14px; padding: 7px;border-right: 0;width:300px;color: #001255;">
                                                                Status</th>
                                                                <td
                                                                style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:300px; color: green; font-weight: bold;">
                                                                ' . $global_g_hotel_booking_status_label . '</td>
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
                                    </td>
                                </tr>
                                </table>


                                <table
                                cellpadding="0"
                                cellspacing="0"
                                class="es-content"
                                align="center"
                                role="none"
                                style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    table-layout: fixed !important;
                                    width: 100%;
                                "
                                >
                                <tr>
                                    <td align="center" style="padding: 0; margin: 0">
                                    <table
                                        bgcolor="#efefef"
                                        class="es-content-body"
                                        align="center"
                                        cellpadding="0"
                                        cellspacing="0"
                                        role="none"
                                        style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        background-color: #efefef;
                                        width: 600px;
                                        "
                                    >
                                        <tr>
                                        <td
                                            align="left"
                                            bgcolor="#f6f8fa"
                                            style="
                                            margin: 0;
                                            padding: 20px;
                                            background-color: #f6f8fa;
                                            "
                                        >
                                            <table
                                            cellpadding="0"
                                            cellspacing="0"
                                            width="100%"
                                            role="none"
                                            style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                            "
                                            >
                                            <tr>
                                                <td
                                                align="left"
                                                style="padding: 0; margin: 0; width: 560px"
                                                >
                                                <table
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                    role="none"
                                                    style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                    "
                                                >
                                                    <tr>
                                                    <td
                                                        align="left"
                                                        style="padding: 0; margin: 0; width: 560px"
                                                    >
                                                        <table
                                                        cellpadding="0"
                                                        cellspacing="0"
                                                        width="100%"
                                                        role="presentation"
                                                        style="
                                                            mso-table-lspace: 0pt;
                                                            mso-table-rspace: 0pt;
                                                            border-collapse: collapse;
                                                            border-spacing: 0px;
                                                        "
                                                        >
                                                        <tr>
                                                            <td
                                                            align="center"
                                                            style="padding: 0; margin: 0"
                                                            >
                                                            <p
                                                                style="
                                                                margin: 0;
                                                                -webkit-text-size-adjust: none;
                                                                -ms-text-size-adjust: none;
                                                                mso-line-height-rule: exactly;
                                                                line-height: 18px;
                                                                color: #2d3142;
                                                                font-size: 12px;
                                                                "
                                                            >
                                                                                        ' . $company_name . '<br />+91
                                                                ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
                                                            </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                            align="center"
                                                            style="
                                                                padding: 0;
                                                                margin: 0;
                                                                padding-top: 20px;
                                                            "
                                                            >
                                                            <p
                                                                style="
                                                                margin: 0;
                                                                -webkit-text-size-adjust: none;
                                                                -ms-text-size-adjust: none;
                                                                mso-line-height-rule: exactly;
                                                                line-height: 18px;
                                                                color: #2d3142;
                                                                font-size: 12px;
                                                                "
                                                            >
                                                                <a
                                                                target="_blank"
                                                                href=""
                                                                style="
                                                                    -webkit-text-size-adjust: none;
                                                                    -ms-text-size-adjust: none;
                                                                    mso-line-height-rule: exactly;
                                                                    text-decoration: underline;
                                                                    color: #2d3142;
                                                                    font-size: 12px;
                                                                "
                                                                ></a>' . $footer_content . '<a
                                                                target="_blank"
                                                                href=""
                                                                style="
                                                                    -webkit-text-size-adjust: none;
                                                                    -ms-text-size-adjust: none;
                                                                    mso-line-height-rule: exactly;
                                                                    text-decoration: underline;
                                                                    color: #2d3142;
                                                                    font-size: 12px;
                                                                "
                                                                ></a>
                                                            </p>
                                                            </td>
                                                        </tr>
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
                                    </td>
                                </tr>
                                </table>
                            </td>
                            </tr>
                        </table>
                        </div>
                    </body>
                    </html>';


        $subject = "$site_title - Itinerary Hotel Confirmation #" . html_entity_decode($global_hotel_name);
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = [$email_to];
        $Bcc = [$bcc_emailid];
        $cc = [$cc_emailid];
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

    else :
        echo "Request Ignored";
    endif;
