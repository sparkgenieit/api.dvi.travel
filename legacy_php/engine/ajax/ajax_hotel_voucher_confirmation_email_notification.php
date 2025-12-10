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

        $global_confirmed_by_val = $_SESSION['global_confirmed_by_val'];
        $global_hotel_status = $_SESSION['global_hotel_status'];
        $global_status_val = $_SESSION['global_status_val'];
        $global_confirmed_itinerary_quote_ID = $_SESSION['global_confirmed_itinerary_quote_ID'];
        $global_primary_customer_name = $_SESSION['global_primary_customer_name'];
        $global_hotel_name = $_SESSION['global_hotel_name'];
        $global_hotel_address = $_SESSION['global_hotel_address'];
        $global_hotel_email =   $_SESSION['global_hotel_email'];
        // Convert the comma-separated email IDs into an array
        $global_hotel_email_array = explode(',', $global_hotel_email);

        // Trim any whitespace from each email ID
        $global_hotel_email_array = array_map('trim', $global_hotel_email_array);

        //Get Deafult Hotel Email ID
        $global_default_hotel_email = getGLOBALSETTING('default_hotel_voucher_email_id');

        // Convert the comma-separated email IDs into an array
        $global_default_hotel_email_array = explode(',', $global_default_hotel_email);

        // Trim any whitespace from each email ID
        $global_default_hotel_email_array = array_map('trim', $global_default_hotel_email_array);

        //Get Deafult Accounts Email ID
        $global_default_accounts_email = getGLOBALSETTING('default_accounts_email_id');
        $global_default_accounts_email_array = explode(',', $global_default_accounts_email);
        $global_default_accounts_email_array = array_map('trim', $global_default_accounts_email_array);

        $global_check_in_date = $_SESSION['global_check_in_date'];
        $global_check_out_date = $_SESSION['global_check_out_date'];
        $global_room_type_title = $_SESSION['global_room_type_title'];
        $global_total_adult = $_SESSION['global_total_adult'];
        $global_total_children = $_SESSION['global_total_children'];
        $global_total_infants = $_SESSION['global_total_infants'];
        $global_preferred_room_count = $_SESSION['global_preferred_room_count'];
        $global_meal_plan_details = $_SESSION['global_meal_plan_details'];
        $global_formatRoomDetails = $_SESSION['global_formatRoomDetails'];
        /* $global_formatMealPlanDetails = $_SESSION['global_formatMealPlanDetails']; */
        $global_formattedoccupancyDetails = $_SESSION['global_formattedoccupancyDetails'];
        $global_travel_expert_name = $_SESSION['global_travel_expert_name'];
        $global_travel_expert_mobile = $_SESSION['global_travel_expert_mobile'];
        $global_travel_expert_staff_email = $_SESSION['global_travel_expert_staff_email'];
        $global_food_type  = $_SESSION['global_food_type'];
        $global_billing_type = $_SESSION['global_billing_type'];
        $global_agent_company_name = $_SESSION['global_agent_company_name'];
        $global_agent_invoice_address = $_SESSION['global_agent_invoice_address'];
        $global_agent_invoice_gstin_no = $_SESSION['global_agent_invoice_gstin_no'];
        $global_hidden_itinerary_plan_id = $_SESSION['global_hidden_itinerary_plan_id'];
        $global_itinerary_plan_hotel_details_ID_val = $_SESSION['global_itinerary_plan_hotel_details_ID_val'];
        $itinerary_quote_ID = get_ITINERARY_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'itinerary_quote_ID');
        $customer_salutation = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($global_hidden_itinerary_plan_id, 'customer_salutation');
        $occupancy_details = get_ITINEARY_CONFIRMED_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'total_occupancy_details');

        $company_youtube = '#';
        $company_facebook = '#';
        $company_instagram = '#';
        $company_linkedin = '#';

        $admin_emailid = getGLOBALSETTING('cc_email_id');

        $email_to = [$global_default_accounts_email_array, $global_travel_expert_staff_email, $global_hotel_email_array, $global_default_hotel_email_array, $admin_emailid];

        $children = ($global_total_children > 0) ? " | Childrens (Above 5 & Below 10) -" . $global_total_children : "";
        $infant = ($global_total_infants > 0) ? " | Infants (Below 5 Years) -" . $global_total_infants : "";

        if ($global_billing_type == 1) :
            $billing_company_name = getGLOBALSETTING('company_name');
            $billing_company_address = getGLOBALSETTING('company_address');
            $billing_company_gstin_no = getGLOBALSETTING('company_gstin_no');
        elseif ($global_billing_type == 2) :
            $billing_company_name = $global_agent_company_name;
            $billing_company_address =  $global_agent_invoice_address;
            $billing_company_gstin_no = $global_agent_invoice_gstin_no;
        endif;

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
     <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

     <head>
         <meta charset="UTF-8" />
         <meta content="width=device-width, initial-scale=1" name="viewport" />
         <title>Account Verified</title>

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

                         <!-- logo -->
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
                      border-left: 1px solid #d3d3d3;
                      border-right: 1px solid #d3d3d3;
                    ">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                          padding: 0;
                          margin: 0;
                          padding-top: 11px;
                          background-color: #fff ;
                          padding-bottom: 11px;
                        ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                                     <tr>
                                                         <td valign="top" style="padding: 0; margin: 0; width: 540px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                                                                 <tr>
                                                                     <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      font-size: 0px;
                                      padding-left:28px;
                                    ">
                                                                         <img src="' . $site_logo . '" alt="Logo" style="
                                          display: block;
                                          border: 0;
                                          outline: none;
                                          text-decoration: none;
                                          -ms-interpolation-mode: bicubic;
                                        " height="60" title="Logo" />
                                                                         <div>
                                                                             <h3 style="font-size: 24px ; color: #d72323; margin-bottom: 0px;">Hotel Voucher - ' . $global_hotel_status . '</h3>
                                                                         </div>
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


                         <!-- Hotel Details -->
                         <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
                             <tr>
                                 <td align="left" style="padding: 0; margin: 0">
                                     <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #fff ;
                      border-radius: 20px 20px 0px 0px;
                      width: 600px;
                      border-left: 1px solid #d3d3d3;
                      border-right: 1px solid #d3d3d3;
                    " role="none">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                          padding: 0;
                          margin: 0;
                          padding-top: 10px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #fff ;
                        ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" align="left" role="none" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                                     <tr>
                                                         <td align="left" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fff" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                 
                                " role="presentation">
                                                                 <tbody>
                                                                 <tr>
                                                                    <td colspan="2">
                                                                        <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;"> Dear Mr/Ms ' . $global_confirmed_by_val . '</h5>
                                                                        <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                        Greetings from Dvi !!!</h5>
                                                                        <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">Thank you for your all constent support. As per the telecon while ago, May We request you to book and confirm the below mentioned reservation.</h6>
                                                                    </td>
                                                                 </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                             Booking ID</th>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $global_confirmed_itinerary_quote_ID . '</a></th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; border-top: 0;  font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                             Quote ID</th>
                                                                         <th style="text-align: left; border: 1px solid; border-top: 0; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestitinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $itinerary_quote_ID . '</a></th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Guest Name</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $customer_salutation . '. ' . $global_primary_customer_name . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Hotel Name</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . html_entity_decode($global_hotel_name) . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Hotel Address</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_hotel_address . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Check-in Date</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_check_in_date . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Check-out Date</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_check_out_date . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Room Type</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_room_type_title . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Number of Guests</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_total_adult . ' Adults ' . $children . $infant . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Rooms</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_preferred_room_count . ' Rooms | ' . $global_formattedoccupancyDetails . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Occupancy</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $occupancy_details . '</td>
                                                                     </tr>
                                                                    <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Meal plan</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_meal_plan_details . '</td>
                                                                     </tr>
                                                                   
                                                                    
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Rate</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             <div>
                                                                                 ' . $global_formatRoomDetails . '
                                                                                 <br>
                                                                             </div>
                                                                         </td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Payment Status</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank">Click here</a> to re confirm the booking and get payment</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Special Requests</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             Food Preference - ' . $global_food_type . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Contact Number of the Travel Expert</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_name . ' - ' . $global_travel_expert_mobile . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Travel Expert Mail Id</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_staff_email . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Billing Instructions</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                         Room and meal plan to the Company rest all direct by the Guest.<br>' . $billing_company_name . '<br>' . $billing_company_address . '<br> GSTIN No :' . $billing_company_gstin_no . ' <br> Please raise the bill against above GST Details </td>
                                                                     </tr>
                                                                 </tbody>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td style="width: 100%;">
                                                             <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                 May I request you to send us the written confirmation along with bank account details for
                                                                 our records.</h6>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="center">
                                                             <!--[if mso]>
  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" style="height:36px;v-text-anchor:middle;width:140px;" arcsize="10%" strokecolor="#ed3c0d" fillcolor="#ed3c0d">
    <w:anchorlock/>
    <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">Confirm Book</center>
  </v:roundrect>
<![endif]-->
<![if !mso]>
  <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank" style="
    padding: 8px 16px;
    background: #ed3c0d;
    color: #fff;
    border: 1px solid #ed3c0d;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  ">Confirm Book</a>
<![endif]-->
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
                                     <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    border-collapse: collapse;
                    border-spacing: 0px;
                    background-color: #fff ;
                    border-radius: 20px 20px 0px 0px;
                    width: 600px;
                    border-left: 1px solid #d3d3d3;
                    border-right: 1px solid #d3d3d3;
                  " role="none">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                        padding: 0;
                        margin: 0;
                        padding-top: 20px;
                        padding-left: 20px;
                        padding-right: 20px;
                        background-color: #f5f6ff;
                      ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                        ">
                                                     <tr>
                                                         <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: separate;
                                border-spacing: 0px;
                              " role="presentation">

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
                                            ">
                                                                                     ' . $company_name . '<br />+91
                                             ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
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
                                             <td align="left" bgcolor="#fff " style="
                          margin: 0;
                          padding: 10px;
                          background-color: #001255 ;
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
                                                                                 <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                          ">
                                                                                     <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #fff;
                                              font-size: 12px;
                                            ">
                                                                                         <a target="_blank" href="" style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "></a>' . $footer_content . '<a target="_blank" href="" style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "></a>
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
        $subject = "$site_title - Hotel Voucher of Itinerary #$global_confirmed_itinerary_quote_ID ($global_primary_customer_name)";
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = [$email_to];
        $Bcc = [$bcc_emailid];
        $cc = [$cc_emailid];
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        $reply_to = [$global_travel_expert_staff_email];
        $body = $message_template;
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body);

    /* echo $to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body; */

    else :
        echo "Request Ignored";
    endif;
