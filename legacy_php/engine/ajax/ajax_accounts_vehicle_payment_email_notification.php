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

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

  $global_confirmed_itinerary_quote_ID = $_SESSION['global_confirmed_itinerary_quote_ID'];
  $global_primary_customer_name = $_SESSION['global_primary_customer_name'];
  $global_vehicle_type_title = $_SESSION['global_vehicle_type_title'];
  $global_vendor_name = $_SESSION['global_vendor_name'];
  $global_vendor_branch = $_SESSION['global_vendor_branch'];
  $global_vendor_email = $_SESSION['global_vendor_email'];
  $global_total_adult = $_SESSION['global_total_adult'];
  $global_total_children = $_SESSION['global_total_children'];
  $global_total_infants = $_SESSION['global_total_infants'];
  $global_total_vehicle_qty = $_SESSION['global_total_vehicle_qty'];
  $global_total_purchase = $_SESSION['global_total_purchase'];
  $global_travel_expert_staff_email = $_SESSION['global_travel_expert_staff_email'];
  $global_hidden_itinerary_plan_id = $_SESSION['global_hidden_itinerary_plan_id'];
  $global_accounts_itinerary_vehicle_details_ID = $_SESSION['global_accounts_itinerary_vehicle_details_ID'];
  $global_payment_amount = $_SESSION['global_payment_amount'];
  $global_vehicle_ID = $_SESSION['global_vehicle_ID'];

        $admin_emailid = getGLOBALSETTING('cc_email_id');

        $email_to = [$global_travel_expert_staff_email, $global_vendor_email, $admin_emailid];

        $children = ($global_total_children > 0) ? " | Childrens (Above 5 & Below 10) -" . $global_total_children : "";
        $infant = ($global_total_infants > 0) ? " | Infants (Below 5 Years) -" . $global_total_infants : "";

        $total_paid = getACCOUNTS_MANAGER_DETAILS($global_hidden_itinerary_plan_id, $global_vehicle_ID,'total_paid_vehicle_amount');
        $total_balance = getACCOUNTS_MANAGER_DETAILS($global_hidden_itinerary_plan_id, $global_vehicle_ID, 'total_balance_vehicle');
  
        $site_title = getGLOBALSETTING('site_title');
        $company_name = getGLOBALSETTING('company_name');
        $company_email_id = getGLOBALSETTING('company_email_id');
        $company_contact_no = getGLOBALSETTING('company_contact_no');
        $current_YEAR = date('Y');
        $site_logo = BASEPATH . '/assets/img/' . getGLOBALSETTING('company_logo');
        $footer_content = " Copyright &copy; $current_YEAR | $company_name";

        $payment_history_html = ''; // Initialize before the loop

        $getstatus_query_hotel = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_transaction_ID`,`transaction_amount`,`transaction_date`,`transaction_done_by`,`mode_of_pay`,`transaction_utr_no` FROM `dvi_accounts_itinerary_vehicle_transaction_history` WHERE `deleted` = '0' AND `accounts_itinerary_vehicle_details_ID` =  $global_accounts_itinerary_vehicle_details_ID") or die("#getROOMTYPE_DETAILS: JOIN_QUERY_ERROR: " . sqlERROR_LABEL());
        
        if (sqlNUMOFROW_LABEL($getstatus_query_hotel)) :
            $vehiclecount = 0;
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($getstatus_query_hotel)) :
                $vehiclecount++;
                $transaction_amount = $fetch_list_data['transaction_amount'];
                $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
                $transaction_done_by = $fetch_list_data['transaction_done_by'];
                $mode_of_pay = $fetch_list_data['mode_of_pay'];
                $transaction_utr_no = $fetch_list_data['transaction_utr_no'];

                if ($mode_of_pay ==  1) {
                    $mode_of_pay_label = 'Cash';
                } elseif ($mode_of_pay == 2) {
                    $mode_of_pay_label = 'UPI';
                } elseif ($mode_of_pay == 3) {
                    $mode_of_pay_label = 'Net Banking';
                }
        
                // Append to existing HTML
                $payment_history_html .= '
                <tr>
                    <td align="center" valign="top" style="padding: 0; margin: 0; width: 520px">
                        <table cellpadding="0" cellspacing="0" width="90%" bgcolor="#fff" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: separate; border-spacing: 0px;" role="presentation">
                            <tbody>
                                <tr>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-right: 0; width: 160px; background-color: #00bb1f; color: #FFF;">Payment #' . $vehiclecount . '</th>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; width: 400px; background-color: #00bb1f; color: #FFF;">INR ' . $transaction_amount . '</th>
                                </tr>
                                <tr>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-right: 0; border-top: 0; width: 160px; color: #001255;">Proceed By</th>
                                    <td style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-top: 0; width: 400px;">' . $transaction_done_by . '</td>
                                </tr>
                                <tr>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-right: 0; border-top: 0; width: 160px; color: #001255;">Payment Date</th>
                                    <td style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-top: 0; width: 400px;">' . $transaction_date . '</td>
                                </tr>
                                <tr>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-right: 0; border-top: 0; width: 160px; color: #001255;">Mode of pay</th>
                                    <td style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-top: 0; width: 400px;">' . $mode_of_pay_label . '</td>
                                </tr>
                                <tr>
                                    <th style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-right: 0; border-top: 0; width: 160px; color: #001255;">UTR No</th>
                                    <td style="text-align: left; border: 1px solid #000; font-size: 15px; padding: 7px; border-top: 0; width: 400px;">' . $transaction_utr_no . '</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>';
            endwhile;
        endif;
        


    
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
                                        src="' . $site_logo . '"
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
                          padding-top: 30px;
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
                                  border-bottom: 0;
                                  border-bottom-left-radius: 0px;
                                  border-bottom-right-radius: 0px;
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
                                      padding-top: 30px;
                                      padding-bottom: 5px;
                                      font-size: 0px;
                                    "
                                  >
                                    <img
                                      src="../head/assets/img/success2.png"
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
                                      width="50"
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
                                      padding-bottom: 10px;
                                    "
                                  >
                                    <h3
                                      style="
                                        margin: 0;
                                        mso-line-height-rule: exactly;
                                        font-size: 16px;
                                        font-weight: 500;
                                        color: #2d3142;
                                      "
                                    >
                                    Payment of INR <b>'. $global_payment_amount .'</b> to Vehicle <b>('. $global_vehicle_type_title .')</b> was successful !!!
                                    </h3>
                                    <div
                                      style="
                                        border: 1px dashed #d3d3d3;
                                        margin: 25px 50px 25px 50px;
                                      "
                                    ></div>
                                    <div
                                      style="
                                        padding-left: 30px;
                                        padding-right: 40px;
                                      "
                                    >
                                      <h5
                                        style="
                                          font-size: 16px;
                                          text-align: start;
                                        "
                                      >
                                        Vehicle Details
                                      </h5>
                                    </div>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td
                                    align="center"
                                    valign="top"
                                    style="padding: 0; margin: 0; width: 520px"
                                  >
                                    <table
                                      cellpadding="0"
                                      cellspacing="0"
                                      width="90%"
                                      bgcolor="#fff"
                                      style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: separate;
                                        border-spacing: 0px;
                                      "
                                      role="presentation"
                                    >
                                      <tbody>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Booking ID
                                          </th>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              width: 400px;
                                            "
                                          >
                                            ' . $global_confirmed_itinerary_quote_ID . '
                                          </th>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Guest Name
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                            ' . $global_primary_customer_name .'
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Vendor Name
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                            ' . html_entity_decode($global_vendor_name) . '
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Branch Name
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                            ' . $global_vendor_branch . '
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Vehicle Name
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                           ' . $global_vehicle_type_title . '
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Number of Guests
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                             ' . $global_total_adult . ' Adults ' . $children . $infant . '
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Total Quantity
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                           ' . $global_total_vehicle_qty . ' *  ' . $global_total_purchase . '
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Rate
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                            "
                                          >
                                          
                                            INR ' . $global_total_purchase . '
                                                                       
                                          </td>
                                        </tr>
                                          <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Total Paid
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              font-weight: 600;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                              color: #009c1a;
                                            "
                                          >
                                          INR '. $total_paid .'
                                          </td>
                                        </tr>
                                        <tr>
                                          <th
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              padding: 7px;
                                              border-right: 0;
                                              border-top: 0;
                                              width: 160px;
                                              color: #001255;
                                            "
                                          >
                                            Total Pending
                                          </th>
                                          <td
                                            style="
                                              text-align: left;
                                              border: 1px solid;
                                              font-size: 15px;
                                              font-weight: 600;
                                              padding: 7px;
                                              border-top: 0;
                                              width: 400px;
                                              color: #c00000;
                                            "
                                          >
                                           INR '. $total_balance .'
                                          </td>
                                        </tr>
                                      
                                      
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <div
                                    style="
                                      padding-left: 30px;
                                      padding-right: 40px;
                                    "
                                  >
                                    <h5
                                      style="
                                        font-size: 16px;
                                        text-align: start;
                                      "
                                    >
                                      Payment History
                                    </h5>
                                  </div>
                                  </td>
                                </tr>
                             
                         '. $payment_history_html.'
                              </table>
                    
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                bgcolor="#fff"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  --triangle-size: 12px;
                                  --stop1: calc(var(--triangle-size) * 1.42);
                                  --stop2: calc(var(--triangle-size) * 0.7);
                                  --stop1r: calc(var(--stop1) + 0.01px);
                                  --stop2r: calc(var(--stop2) + 0.01px);
                                  background: linear-gradient(
                                        135deg,
                                        white var(--stop2),
                                        transparent var(--stop2r)
                                      )
                                      bottom left,
                                    linear-gradient(
                                        45deg,
                                        transparent var(--stop1),
                                        white var(--stop1r)
                                      )
                                      bottom left;
                                  background-repeat: repeat-x;
                                  background-size: calc(
                                      var(--triangle-size) * 2
                                    )
                                    var(--triangle-size);
                                  padding: var(--triangle-size) 0;
                                  padding-top: 0px;
                                "
                                role="presentation"
                              ></table>
                              
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
                                            Doview Holidays India Pvt Ltd<br />+91
                                            98432 88844, vsr@dvi.co.in<br />2
                                            No.68/1 Butt Road, St Thomas Mount,
                                            Chennai – 600016.
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
                                            ></a
                                            >Copyright © 2024 Doview Holidays<a
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
</html>
';
        $subject = "$site_title - Itinerary Vehicle Payment #$global_confirmed_itinerary_quote_ID";
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = $email_to;
        $Bcc = $bcc_emailid;
        $cc = $cc_emailid;
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        $reply_to = [$global_travel_expert_staff_email];
        $reply_to = $reply_to;
        $body = $message_template;
  
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body);

    /* echo $to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body; */

    else :
        echo "Request Ignored";
    endif;
