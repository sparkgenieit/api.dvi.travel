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

    $agent_id = $_SESSION['global_aid'];
    $agent_name = getAGENT_details($agent_id, '', 'label');
    $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');
    $travel_expert_id = $_SESSION['global_texp_id'];

    if ($travel_expert_id) :
      $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
      $travel_expert_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
      $travel_expert_mobile = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
      $email_to = [$travel_expert_email, $agent_email, $admin_emailid];
    else :
      $travel_expert_name = '--';
      $travel_expert_email = '--';
      $travel_expert_mobile = '--';
      $email_to = [$admin_emailid];
    endif;

    $title = 'You are assigned to a specific Travel Expert!';
    $site_title = getGLOBALSETTING('site_title');
    $description = " This is a notification to confirm that you have been successfully assigned to a specific travel expert.";
    $custom_msg = " If you have any questions about your assignment or would just like to chat, feel free to contact us at any time. We would love to hear from you.";
    $message_template = '<!DOCTYPE html>
 <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

 <head>
     <meta charset="UTF-8" />
     <meta content="width=device-width, initial-scale=1" name="viewport" />
     <meta name="x-apple-disable-message-reformatting" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <title>' . $title  . '</title>
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
    </style>
 </head>

 <body style="
                    width: 100%;
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                    padding: 0;
                    margin: 0;
                    ">
     <div dir="ltr" class="es-wrapper-color" lang="en" style="background-color: #ffffff">
         <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" role="none" style="
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
                        ">
             <tr>
                 <td valign="top" style="padding: 0; margin: 0">
                     <table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                table-layout: fixed !important;
                                width: 100%;
                                background-color: transparent;
                                background-repeat: repeat;
                                background-position: center top;
                            ">
                         <tr>
                             <td align="center" style="padding: 0; margin: 0">
                                 <table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    background-color: #ffffff;
                                    width: 600px;
                                    ">
                                     <tr>
                                         <td align="left" bgcolor="#f6f8fa" style="
                                        padding: 0;
                                        margin: 0;
                                        padding-top: 20px;
                                        padding-left: 20px;
                                        padding-right: 20px;
                                        background-color: #f6f8fa;
                                        ">
                                             <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                        ">
                                                 <tr>
                                                     <td align="center" valign="top" style="padding: 0; margin: 0; width: 540px">
                                                         <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                                ">
                                                             <tr>
                                                                 <td align="center" style="
                                                    padding: 0;
                                                    margin: 0;
                                                    font-size: 0px;
                                                    ">
                                                                     <a target="_blank" href="' . BASEPATH . '" style="
                                                        -webkit-text-size-adjust: none;
                                                        -ms-text-size-adjust: none;
                                                        mso-line-height-rule: exactly;
                                                        text-decoration: underline;
                                                        color: #2d3142;
                                                        font-size: 14px;
                                                    "><img src="' . BASEPATH . 'assets/img/logo-preview.png" alt="Logo" style="
                                                        display: block;
                                                        border: 0;
                                                        outline: none;
                                                        text-decoration: none;
                                                        -ms-interpolation-mode: bicubic;
                                                        " height="70" title="Logo" /></a>
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
                    bgcolor="#f5f6ff"
                    class="es-content-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #f5f6ff;
                      border-radius: 20px 20px 0px 0px;
                      width: 600px;
                    "
                    role="none"
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f5f6ff"
                        style="
                          margin: 0;
                          padding-top: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          padding-bottom: 20px;
                          background-color: #f5f6ff;
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
                                    align="left"
                                    style="
                                      padding-bottom: 40px;
                                      padding-left: 40px;
                                      padding-right: 40px;
                                      margin: 0;
                                      padding-top: 40px;
                                    "
                                  >
                                    <p style="margin-top: 0; font-size: 16px">
                                      <a
                                        dir="auto"
                                        href=""
                                        style="
                                          color: #000;
                                          text-decoration: none;
                                        "
                                        target="_blank"
                                        data-saferedirecturl=""
                                        >Dear Agent</a
                                      >
                                      [' . $agent_name . '],
                                    </p>
                                    <h2
                                      style="
                                        font-size: 20px;
                                        font-weight: normal;
                                        margin: 16px 0;
                                        padding: 0;
                                      "
                                    >
                                      <a
                                        href="javascript:void(0);"
                                        style="
                                          color: #001255;
                                          text-decoration: none;
                                        "
                                      >
                                          ' . $title . '</a
                                      >
                                    </h2>
                                    <div
                                      style="
                                        border-left: 2px solid #2d3142;
                                        margin: 16px 0;
                                        padding: 16px;
                                        font-size: 16px;
                                      "
                                    >
                                      <div>
                                       ' . $description . '
                                        <br /><br />
                                        <table
                                          cellpadding="0"
                                          cellspacing="0"
                                          width="100%"
                                          bgcolor="#fff"
                                          style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: separate;
                                            border-spacing: 0;
                                          "
                                          role="presentation"
                                        >
                                          <thead>
                                            <tr>
                                              <th
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 75px;
                                                "
                                              >
                                                Travel Expert
                                              </th>
                                              <th
                                                style="
                                                  text-align: center;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 10px;
                                                "
                                              >
                                                :
                                              </th>
                                              <td
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  width: 200px;
                                                  padding-left: 6px;
                                                "
                                              >
                                               ' . $travel_expert_name . '
                                              </td>
                                            </tr>
                                            <tr>
                                              <th
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 75px;
                                                "
                                              >
                                                Email Address
                                              </th>
                                              <th
                                                style="
                                                  text-align: center;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 10px;
                                                "
                                              >
                                                :
                                              </th>
                                              <td
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  width: 200px;
                                                  padding-left: 6px;
                                                "
                                              >
                                              ' . $travel_expert_email . '
                                              </td>
                                            </tr>
                                            <tr>
                                              <th
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 75px;
                                                "
                                              >
                                                Mobile Number
                                              </th>
                                              <th
                                                style="
                                                  text-align: center;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  font-weight: lighter;
                                                  color: #5f6267;
                                                  width: 10px;
                                                "
                                              >
                                                :
                                              </th>
                                              <td
                                                style="
                                                  text-align: left;
                                                  font-size: 15px;
                                                  padding: 2px;
                                                  width: 200px;
                                                  padding-left: 6px;
                                                "
                                              >
                                                ' . $travel_expert_mobile . '
                                              </td>
                                            </tr>
                                          </thead>
                                        </table>
                                      </div>
                                    </div>
                                    <table
                                      cellpadding="10"
                                      cellspacing="0"
                                      border="0"
                                      width="100%"
                                      style="
                                        border-spacing: 0;
                                        border-collapse: collapse;
                                        padding: 12px;
                                        background-color: #fff;
                                        border-top: 1px solid #e0e0e0;
                                      "
                                    >
                                      <tbody>
                                        <tr>
                                          <td style="border-collapse: collapse">
                              <!--[if mso]>
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding: 0;">
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" 
                         href="' . BASEPATH . '" 
                         style="height:32px;v-text-anchor:middle;width:70px;" 
                         arcsize="10%" strokecolor="#001255" fillcolor="#001255">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:sans-serif;font-size:12px;white-space:nowrap;">
                   Login
                </center>
            </v:roundrect>
        </td>
    </tr>
</table>
<![endif]-->
<![if !mso]>
<a href="' . BASEPATH . '" 
   target="_blank" 
   style="background:#001255;border-radius:7px;color:#ffffff;display:inline-block;font-size:12px;padding:5px 15px;text-decoration:none;text-align:center;white-space:nowrap;">
    Login
</a>
<![endif]>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p
                                      style="
                                        font-size: 14px;
                                        color: #7c9292;
                                        margin-bottom: 0px;
                                      "
                                    >
                                      ' . $custom_msg . '
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

                     <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                table-layout: fixed !important;
                                width: 100%;
                            ">
                         <tr>
                             <td align="center" style="padding: 0; margin: 0">
                                 <table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    background-color: #efefef;
                                    width: 600px;
                                    ">
                                     <tr>
                                         <td align="left" bgcolor="#f6f8fa" style="
                                        margin: 0;
                                        padding: 20px;
                                        background-color: #f6f8fa;
                                        ">
                                             <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                        ">
                                                 <tr>
                                                     <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                         <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                                ">
                                                             <tr>
                                                                 <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                        mso-table-lspace: 0pt;
                                                        mso-table-rspace: 0pt;
                                                        border-collapse: collapse;
                                                        border-spacing: 0px;
                                                    ">
                                                                         <tr>
                                                                             <td align="center" style="padding: 0; margin: 0">
                                                                                 <p style="
                                                            margin: 0;
                                                            -webkit-text-size-adjust: none;
                                                            -ms-text-size-adjust: none;
                                                            mso-line-height-rule: exactly;
                                                            line-height: 18px;
                                                            color: #2d3142;
                                                            font-size: 12px;
                                                            ">' . getGLOBALSETTING('company_name') . '<br />' . getGLOBALSETTING('company_contact_no') . ', ' . getGLOBALSETTING('company_email_id') . '<br />' . getGLOBALSETTING('company_address') . '– ' . getGLOBALSETTING('company_pincode') . '.
                                                                                 </p>
                                                                             </td>
                                                                         </tr>
                                                                         <tr>
                                                                             <td align="center" style="
                                                            padding: 0;
                                                            margin: 0;
                                                            padding-top: 20px;
                                                        ">
                                                                                 <p style="
                                                            margin: 0;
                                                            -webkit-text-size-adjust: none;
                                                            -ms-text-size-adjust: none;
                                                            mso-line-height-rule: exactly;
                                                            line-height: 18px;
                                                            color: #2d3142;
                                                            font-size: 12px;
                                                            ">
                                                                                     <a target="_blank" href="" style="
                                                                -webkit-text-size-adjust: none;
                                                                -ms-text-size-adjust: none;
                                                                mso-line-height-rule: exactly;
                                                                text-decoration: underline;
                                                                color: #2d3142;
                                                                font-size: 12px;
                                                            "></a>Copyright &copy; ' . date('Y') . ' ' . $site_title . '<a target="_blank" href="" style="
                                                                -webkit-text-size-adjust: none;
                                                                -ms-text-size-adjust: none;
                                                                mso-line-height-rule: exactly;
                                                                text-decoration: underline;
                                                                color: #2d3142;
                                                                font-size: 12px;
                                                            "></a></p>
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

    $subject = "$site_title - $title";
    $send_from = "$SMTP_EMAIL_SEND_FROM";
    $to = [$email_to];
    $Bcc = [$bcc_emailid];
    $cc = [$cc_emailid];
    $sender_name = "$SMTP_EMAIL_SEND_NAME";
    SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

  else :
    echo "Request Ignored";
  endif;
