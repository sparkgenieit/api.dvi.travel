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

    $subscriptionPlanID = $_SESSION['global_sid'];
    $agentID = $_SESSION['global_agent_id'];
    $plan = $_SESSION['plan'];
    $agent_emailid = getAGENT_details($agentID, '', 'get_agent_email_address');
    if ($plan == 'renewal_subscription') :
      $custom_label = 'Renewed On';
    elseif ($plan == 'free_subscription' || $plan == 'paid_subscription') :
      $custom_label = 'Registered On';
    endif;

    $select_subscribed_details_query = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID`, `agent_ID`, `subscription_plan_ID`, `subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost`, `validity_start`, `validity_end`, `subscription_notes`, `subscription_payment_status`, `transaction_id`, `subscription_status` FROM `dvi_agent_subscribed_plans` WHERE `status` = '1' AND `deleted` = '0' AND `subscription_plan_ID` = $subscriptionPlanID AND `agent_ID` = $agentID AND `validity_end` > CURDATE() ORDER BY `validity_end` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_SUBSCRIBED_DETAILS:" . sqlERROR_LABEL());
    while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_details_query)) :
      $agent_ID = $fetch_data['agent_ID'];
      $subscription_plan_ID = $fetch_data['subscription_plan_ID'];
      $subscription_plan_title = !empty($fetch_data['subscription_plan_title']) ? $fetch_data['subscription_plan_title'] : '--';
      $itinerary_allowed = !empty($fetch_data['itinerary_allowed']) ? $fetch_data['itinerary_allowed'] : '--';
      $subscription_amount = !empty($fetch_data['subscription_amount']) ? $fetch_data['subscription_amount'] : '0';
      $joining_bonus = !empty($fetch_data['joining_bonus']) ? $fetch_data['joining_bonus'] : '--';
      $validity_start = !empty($fetch_data['validity_start']) ? date('M d, Y', strtotime($fetch_data['validity_start'])) : '--';
      $agent_name = !empty(getAGENT_details($agent_ID, '', 'label')) ? getAGENT_details($agent_ID, '', 'label') : '--';
      $validity_days = !empty(getSUBSCRIPTION_REGISTRATION($subscription_plan_ID, 'validity_days')) ? getSUBSCRIPTION_REGISTRATION($subscription_plan_ID, 'validity_days') : '--';
    endwhile; //end of while loop
    if ($plan == 'renewal_subscription') :
      $subject_title = 'Agent Renewal Subscription Confirmation';
    elseif ($plan == 'free_subscription' || $plan == 'paid_subscription') :
      $subject_title = 'Agent Subscription Confirmation';
    endif;

    $site_title = getGLOBALSETTING('site_title');
    $company_name = getGLOBALSETTING('company_name');
    $company_email_id = getGLOBALSETTING('company_email_id');
    $company_contact_no = getGLOBALSETTING('company_contact_no');
    $current_YEAR = date('Y');
    if ($plan == 'paid_subscription') :
      $custom_message = "Your Subscription Payment Success !!!";
    elseif ($plan == 'free_subscription') :
      $custom_message = "Your Free Subscription is Active!";
    elseif ($plan == 'renewal_subscription') :
      $custom_message = "Your Subscription has been Successfully Renewed!";
    endif;
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
    <title>' . $subject_title . '</title>
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
                                      href="' . BASEPATH . '"
                                      style="
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        text-decoration: underline;
                                        color: #2d3142;
                                        font-size: 14px;
                                      "
                                      ><img
                                           src=' . $site_logo . '
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
                                    src="' . BASEPATH . 'assets/img/success2.png"
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
                                   ' . $custom_message . '
                                    </h3>
                                   <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="padding: 15px;">
            ' . ($subscription_amount > 0 ? '<h5 style="margin: 0; font-size: 18px; font-weight: 600;">' . general_currency_symbol . ' ' . number_format($subscription_amount, 2) . ' </h5>' : '') . '
        </td>
    </tr>
</table>
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top: 0px; margin-bottom: 25px;">
                                
  <tr>
    <td style="border-top: 1px dashed #d3d3d3; height: 0; line-height: 0;">
    </td>
  </tr>
</table>
                                    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; padding-left: 40px; padding-right: 40px;">
  <tr>
    <td>
      <h5 style="font-size:16px;text-align:start;margin-top:15px;margin-bottom: 15px;">Subscription Plan</h5>
      <table width="100%" style="margin-bottom: 10px;">
        <tr>
          <td style="width: 50%; color: #999; font-size: 14px;">Plan</td>
          <td style="width: 50%; text-align: right; font-size: 14px;">' . $subscription_plan_title . '</td>
        </tr>
      </table>
      <table width="100%" style="margin-bottom: 10px;">
        <tr>
          <td style="width: 50%; color: #999; font-size: 14px;">Itinerary allowed</td>
          <td style="width: 50%; text-align: right; font-size: 14px;">' . $itinerary_allowed . '</td>
        </tr>
      </table>
      <table width="100%" style="margin-bottom: 10px;">
        <tr>
          <td style="width: 50%; color: #999; font-size: 14px;">Subscription Duration</td>
          <td style="width: 50%; text-align: right; font-size: 14px;">' . $validity_days . ' Days</td>
        </tr>
      </table>
      ' . ($joining_bonus > 0 ? '<table width="100%" style="margin-bottom: 10px;">
        <tr>
          <td style="width: 50%; color: #999; font-size: 14px;">Joining Bonus</td>
          <td style="width: 50%; text-align: right; font-size: 14px;">' . general_currency_symbol . ' ' . number_format($joining_bonus, 2) . '</td>
        </tr>
      </table>' : '') . '
      <table width="100%" style="margin-bottom: 10px;">
        <tr>
          <td style="width: 50%; color: #999; font-size: 14px;">' . $custom_label . '</td>
          <td style="width: 50%; text-align: right; font-size: 14px;">' . $validity_start . '</td>
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
                                background:
        linear-gradient(135deg, white var(--stop2), transparent var(--stop2r)) bottom left,
        linear-gradient(45deg, transparent var(--stop1), white var(--stop1r)) bottom left;
                                background-repeat: repeat-x;
                                background-size: calc(var(--triangle-size) * 2) var(--triangle-size);
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
                                            ></a
                                            >' . $footer_content . '<a
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

    $subject = "$site_title - $subject_title";
    $send_from = "$SMTP_EMAIL_SEND_FROM";
    $to = [$agent_emailid];
    $cc_email_id = getGLOBALSETTING('cc_email_id');
    $Bcc = [$bcc_emailid];
    $cc = [$cc_email_id];
    $sender_name = "$SMTP_EMAIL_SEND_NAME";
    SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

  else :
    echo "Request Ignored";
  endif;
