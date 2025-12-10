<?php

ob_start();
session_start();
$hotspotprice_import_session_id = session_id();
include_once('jackus.php');
require 'vendor/autoload.php'; // Include the Composer autoload file

// use PhpOffice\PhpSpreadsheet\IOFactory;
admin_reguser_protect();

if ($_GET['regen'] == 'y' && $_GET['route'] == 'import') :
  session_regenerate_id(TRUE);
  $hotspotprice_import_session_id = session_id();
  echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import'; </script>";
elseif ($_GET['regen'] == 'y') :
  session_regenerate_id(TRUE);
  $hotspotprice_import_session_id = session_id();
  echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php'; </script>";
endif;

//IMPORT CSV UPLOAD
if (isset($_POST['upload_csv']) && $_POST['upload_csv'] == 'confirm_upload') :

  $file_name         = $_FILES['csv']['name'];
  $file_type         = $_FILES['csv']['type'];
  $file_temp_loc     = $_FILES['csv']['tmp_name'];
  $file_error_msg    = $_FILES['csv']['error'];
  $file_size         = $_FILES['csv']['size'];

  /* 1. file upload handling */
  if (!$file_temp_loc) : // if not file selected
    //echo "Error: please browse for a file before clicking the upload button.";
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_temp_loc'</script>";
    die;
  endif;

  if (!preg_match("/\.(xlsx|csv)$/i", $file_name)) : // check file extension
    //echo 'Error: your file is not CSV.';
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_valid'</script>";
    @unlink($file_temp_loc); // remove to the temp folder
    die;
  endif;

  if ($file_size > 5242880) : // file check size
    //echo "Error: you file was larger than 5 Megabytes in size.";
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_size'</script>";
    die;
  endif;

  if ($file_error_msg == 1) : // 
    //echo "Error: an error occured while processing the file, try agian.";
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_error_msg'</script>";
    die;
  endif;

  // opening imported file content
  if (($handle = fopen($file_temp_loc, 'r')) !== FALSE) :
    set_time_limit(0); // necessary if a large csv file
    if (($data = fgetcsv($handle, 1000, ',')) !== FALSE) :
      $imported_col_count = count($data);
      for ($i = 0; $i < $imported_col_count; $i++) :
        //store the title of imported file in an array
        $imported_csv_file[] = $data[$i];
      endfor;
      fclose($handle);
    endif;
  endif;

  if (!empty($hotspotparkingcharge_csv_file_titles)) :

    $sample_col_count = count($hotspotparkingcharge_csv_file_titles);


    // compare both csv file titles count and each titles.
    if ($imported_col_count == $sample_col_count) :

      for ($i = 0; $i < $sample_col_count; $i++) :

        if (strtolower($imported_csv_file[$i]) != strtolower($hotspotparkingcharge_csv_file_titles[$i])) :
          //echo $imported_csv_file[$i];
          //echo "Error: Invalid File Content, try again.";
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_mismatch'</script>";
          die;
        endif;
      endfor;
    else :
      //echo "Error: Invalid File Content, try again.";
      echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=file_mismatch'</script>";
      die;
    endif;
  endif;

  $move_file = move_uploaded_file($file_temp_loc, "uploads/excel_uploads/{$file_name}"); // temp loc, file name
  if ($move_file != true) : // if not move to the temp location
    //echo 'Error: File not uploaded, try again.';
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=move_file'</script>";
    @unlink($file_temp_loc); // remove to the temp folder
    die;
  endif;


  $csvFile  = 'uploads/excel_uploads/' . $file_name;
  $csvFileLength = filesize($csvFile);
  $csvSeparator = ",";
  $handle = fopen($csvFile, 'r');
  $flag = true;
  $count = '';

  while (($data = fgetcsv($handle, $csvFileLength, $csvSeparator)) !== FALSE) : // while for each row
    if (!$flag) :

      /****************************
            Checking if record is empty
       ****************************/
      if ($data[0] != '') :
        foreach ($_POST as $key => $value) :
          $data[$key] = filter($value);
        endforeach;

        $count++;
        // $sno = trim($validation_globalclass->sanitize($data[0]));
        $hotspot_name = trim($validation_globalclass->sanitize($data[1]));
        $hotspot_location = trim($validation_globalclass->sanitize($data[2]));
        $vehicle_type_title = trim($validation_globalclass->sanitize($data[3]));
        $parking_charge = trim($validation_globalclass->sanitize($data[4]));


        //CHECK HOTSPOT NAME IS EMPTY
        if ($hotspot_name == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=hotspot_name_required&sno=$count'</script>";
          die;
        endif;

        //CHECK HOTSPOT LOCATION IS EMPTY
        if ($hotspot_location == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=hotspot_location_required&sno=$count'</script>";
          die;
        endif;


        // CHECK vehicle_type_title IS EMPTY
        if ($vehicle_type_title == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=vehicle_type_title_required&sno=$count'</script>";
          die;
        endif;

        // CHECK parking_charge IS EMPTY
        if ($parking_charge == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=parking_charge_required&sno=$count'</script>";
          die;
        endif;



        $getHOTSPOT_ID = getHOTSPOT_ID($hotspot_name, 'GET_HOTSPOT_ID', '');
        if ($getHOTSPOT_ID == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=check_hotspot_name_not_exist&hotspot_name=$hotspot_name'</script>";
          die;
        endif;

        $getVEHICLETYPEID = getVEHICLETYPE($vehicle_type_title, 'get_vehicletypeid_from_vehicletypetitle');


        if ($getVEHICLETYPEID == "") :
          session_regenerate_id(TRUE);
          $hotspotprice_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import&switch=E&type=check_vehicle_type_title_not_exist&vehicle_type_title=$vehicle_type_title'</script>";
          die;
        endif;

        $arrFields = array('`csvtype`', '`sessionID`', '`field1`', '`field2`', '`field3`', '`field4`', '`status`');

        $arrValues = array("4", "$hotspotprice_import_session_id", "$hotspot_name", "$hotspot_location", "$vehicle_type_title", "$parking_charge", "1");

        if (sqlACTIONS("INSERT", "dvi_tempcsv", $arrFields, $arrValues, '')) :
          //success
          $result = 1;
        else :
          $result = 2;
        endif;
      endif;

    endif; //end of checking data	
    // endif;
    $flag = false;
  endwhile;

  fclose($handle);
  unlink($csvFile); // delete csv after imported
  if ($result == 1) :
    //Templist Shift details			
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=templist&code=1'
		</script>";
    exit();
  else :
    //Templist Shift details			
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?code=2'
		</script>";
    exit();
  endif;
endif;


if (isset($_POST['confirm_import']) && $_POST['confirm_import'] == "Import") :
  if ($_POST["temp_id"] != '') :
    $rowCount = count($_POST["temp_id"]);
  else :
    $rowCount = 0;
  endif;
  if ($rowCount > 0) :
    for ($i = 0; $i <= $rowCount; $i++) :
      $temp_id = $_POST['temp_id'][$i];
      $select_temp_data_records = sqlQUERY_LABEL("SELECT `temp_id`,`csvtype`,`sessionID`,`field1`,`field2`,`field3`, `field4`,`status` FROM `dvi_tempcsv` WHERE `temp_id`='$temp_id' and `status`='1' and `csvtype` = '4'") or die("Unable to get records:" . sqlERROR_LABEL());

      while ($fetch_records = sqlFETCHARRAY_LABEL($select_temp_data_records)) :
        $hotspot_name = $fetch_records['field1'];
        $hotspot_location = $fetch_records['field2'];
        $HOTSPOT_ID = getHOTSPOT_ID($hotspot_name, 'GET_HOTSPOT_ID_FROM_HOTSPOT_NAME_AND_LOCATION', $hotspot_location);

        $vehicle_type_title = $fetch_records['field3'];
        $VEHICLETYPEID = getVEHICLETYPE($vehicle_type_title, 'get_vehicletypeid_from_vehicletypetitle');
        $parking_charge = $fetch_records['field4'];

        $arrFields = array('`hotspot_id`', '`vehicle_type_id`', '`parking_charge`', '`createdby`', '`status`');
        $arrValues = array("$HOTSPOT_ID", "$VEHICLETYPEID", "$parking_charge", "$logged_user_id", "1");

        $selected_vehicle_parking_charge = sqlQUERY_LABEL("SELECT `vehicle_parking_charge_ID` FROM `dvi_hotspot_vehicle_parking_charges` where  `hotspot_id` =  '$HOTSPOT_ID' AND `vehicle_type_id` ='$VEHICLETYPEID' AND `status`='1' AND `deleted`='0' ") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
        while ($fetch_vehicle_parking_charge = sqlFETCHARRAY_LABEL($selected_vehicle_parking_charge)) :
          $vehicle_parking_charge_ID = $fetch_vehicle_parking_charge['vehicle_parking_charge_ID'];
        endwhile;

        if ($vehicle_parking_charge_ID != "") :
          //UPDATE PARKING TABLE
          $sqlWhere_parking = " `vehicle_parking_charge_ID` = '$vehicle_parking_charge_ID' ";
          if (sqlACTIONS("UPDATE", "dvi_hotspot_vehicle_parking_charges", $arrFields, $arrValues, $sqlWhere_parking)) :
            //Update temp csv table
            $arrFields_temp = array('`status`');
            $arrValues_temp = array("2");
            $sqlWhere_temp = " `temp_id` = '$temp_id' ";
            if (sqlACTIONS("UPDATE", "dvi_tempcsv", $arrFields_temp, $arrValues_temp, $sqlWhere_temp)) :
              //success
              $code = '2';
            else :
              $code = '0';
            endif;
          else :
            $code = '0';
          endif;
        else :
          //INSERT PARKING TABLE
          if (sqlACTIONS("INSERT", "dvi_hotspot_vehicle_parking_charges", $arrFields, $arrValues, '')) :
            //Update temp csv table
            $arrFields_temp = array('`status`');
            $arrValues_temp = array("2");
            $sqlWhere_temp = " `temp_id` = '$temp_id' ";
            if (sqlACTIONS("UPDATE", "dvi_tempcsv", $arrFields_temp, $arrValues_temp, $sqlWhere_temp)) :
              //success
              $code = '2';
            else :
              $code = '0';
            endif;
          else :
            $code = '0';
          endif;
        endif;

      endwhile;
    endfor;
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=import_response'</script>";
  elseif ($rowCount == 0) :
    echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php?route=templist&switch=W&type=import_row'</script>";
    exit();
  endif;
endif;

?>


<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>

  <!-- Canonical SEO -->
  <link rel="canonical" href="https://1.envato.market/vuexy_admin">


  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
  <link rel="stylesheet" href="./assets/vendor/libs/flatpickr/flatpickr.css" />
  <!-- Row Group CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
  <!-- Form Validation -->
  <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
  <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
  <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="./assets/css/parsley_validation.css">

  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>
  <script src="./assets/vendor/js/template-customizer.js"></script>
  <script src="./assets/js/config.js"></script>
  <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />

</head>


<body>

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">


      <!-- Layout container -->
      <div class="layout-page">
      <?php include_once('public/__sidebar.php'); ?>


        <!-- Content wrapper -->
        <div class="content-wrapper">

          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">

            <div class=" d-flex justify-content-between align-items-center">
              <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
              <?php include adminpublicpath('__breadcrumb.php'); ?>
            </div>
            <?php if (isset($_GET['route']) && $_GET['route'] == 'import') : ?>

              <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-12">
                    <div class="card mb-4 p-5">
                      <span id="response_alert"></span>
                      <div class="justify-content-center bulk-upload-body">
                        <div class="card-body bulk-import-body text-center p-5">
                          <svg xmlns="http://www.w3.org/2000/svg" height="150" version="1.1" viewBox="-23 0 512 512" width="150">
                            <g id="surface1">
                              <path d="M 337.953125 230.601562 C 404.113281 239.886719 455.015625 296.65625 455.015625 365.378906 C 455.015625 440.503906 394.082031 501.4375 318.957031 501.4375 C 267.3125 501.4375 222.277344 472.625 199.335938 430.152344 C 188.878906 410.839844 182.902344 388.75 182.902344 365.273438 C 182.902344 290.148438 243.835938 229.214844 318.957031 229.214844 C 325.363281 229.320312 331.660156 229.75 337.953125 230.601562 Z M 337.953125 230.601562 " style="stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                              <path d="M 337.953125 230.601562 C 331.765625 229.75 325.363281 229.320312 318.957031 229.320312 C 243.835938 229.320312 182.902344 290.253906 182.902344 365.378906 C 182.902344 388.855469 188.878906 410.945312 199.335938 430.257812 L 199.121094 430.367188 L 57.199219 430.367188 C 31.265625 430.367188 10.242188 409.34375 10.242188 383.414062 L 10.242188 57.730469 C 10.242188 31.800781 31.265625 10.777344 57.199219 10.777344 L 229.429688 10.777344 L 229.429688 88.464844 C 229.429688 108.523438 245.648438 124.746094 265.710938 124.746094 L 337.953125 124.746094 Z M 337.953125 230.601562 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                              <path d="M 229.429688 10.777344 L 337.953125 124.746094 L 265.710938 124.746094 C 245.648438 124.746094 229.429688 108.523438 229.429688 88.464844 Z M 229.429688 10.777344 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                              <path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.96875 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.199219 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.199219 440.71875 L 193.148438 440.71875 C 219.609375 485.535156 267.203125 512 318.960938 512 C 399.847656 512 465.6875 446.265625 465.6875 365.273438 C 465.6875 329.632812 452.988281 295.375 429.511719 268.59375 C 408.277344 244.476562 379.890625 228.042969 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.097656 102.550781 240.097656 88.464844 L 240.097656 37.457031 Z M 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.199219 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.605469 135.523438 L 327.605469 218.863281 C 324.402344 218.757812 321.839844 218.332031 319.066406 218.332031 C 281.824219 218.332031 247.570312 232.628906 221.746094 255.039062 L 86.222656 255.039062 C 80.355469 255.039062 75.550781 259.839844 75.550781 265.710938 C 75.550781 271.582031 80.351562 276.382812 86.222656 276.382812 L 201.898438 276.382812 C 194.320312 287.054688 188.023438 297.726562 183.117188 309.464844 L 86.222656 309.464844 C 80.355469 309.464844 75.550781 314.265625 75.550781 320.132812 C 75.550781 326.003906 80.351562 330.804688 86.222656 330.804688 L 176.179688 330.804688 C 173.511719 341.476562 172.125 353.320312 172.125 365.167969 C 172.125 383.839844 175.644531 402.300781 182.476562 419.375 L 57.199219 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 Z M 318.960938 490.765625 C 272.96875 490.765625 230.601562 465.582031 208.621094 425.136719 C 198.695312 406.890625 193.46875 386.292969 193.46875 365.378906 C 193.46875 296.230469 249.703125 239.992188 318.851562 239.992188 C 324.722656 239.992188 330.589844 240.421875 336.351562 241.167969 C 366.019531 245.328125 393.335938 260.054688 413.183594 282.679688 C 433.246094 305.515625 444.238281 334.859375 444.238281 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625" style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                              <path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.355469 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.355469 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                              <path d="M 326.535156 286.625 C 324.507812 284.492188 321.734375 283.210938 318.746094 283.210938 C 315.757812 283.210938 312.984375 284.492188 310.957031 286.625 L 248.425781 353.746094 C 244.367188 358.015625 244.6875 364.84375 248.957031 368.792969 C 250.984375 370.714844 253.652344 371.675781 256.214844 371.675781 C 259.09375 371.675781 262.082031 370.5 264.21875 368.257812 L 308.394531 320.984375 L 308.394531 437.515625 C 308.394531 443.382812 313.199219 448.1875 319.066406 448.1875 C 324.9375 448.1875 329.738281 443.382812 329.738281 437.515625 L 329.738281 320.988281 L 373.597656 368.261719 C 377.652344 372.527344 384.269531 372.847656 388.644531 368.792969 C 392.910156 364.738281 393.125 358.015625 389.175781 353.746094 Z M 326.535156 286.625 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                            </g>
                          </svg>
                          <div class="mt-5">
                            <input type="file" class="btn btn-light rounded-pill btn-sm p-0" name="csv">
                          </div>
                          <button type="submit" name="upload_csv" class="btn btn-primary rounded-pill btn-sm mt-3" value="confirm_upload">Upload</button>
                          <div class="mt-3">

                            <a href="excel_export_sampleformat_vehicle_parking_charge.php" class="fs-6" download>Download Sample CSV </a>
                            <div id="icon-container" class="clickable mt-2" onclick="toggleContent()">
                              <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512; cursor:pointer;" xml:space="preserve" class="ms-1">
                                <g>
                                  <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" fill="#3a57e8" data-original="#000000" opacity="1" class=""></path>
                                  <path d="M12 7a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zM12 15c-.26 0-.52.11-.71.29-.181.19-.29.45-.29.71s.109.52.29.71c.38.37 1.04.37 1.42 0 .18-.19.29-.45.29-.71s-.11-.52-.29-.71c-.19-.18-.45-.29-.71-.29z" fill="#3a57e8" data-original="#000000" opacity="1" class=""></path>
                                </g>
                              </svg>
                            </div>
                            <div id="expanded-content" style="display: none; " class="mt-2">
                              <div class="row justify-content-center">
                                <div class="col-md-4 text-start bulk-import-container py-4">
                                  <ul class="m-0">
                                    <li>All fields are mandatory.</li>
                                    <li>All the contents should be in same order as the format.</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /Basic  -->
                </div>
              </form>
            <?php elseif ($_GET['route'] == 'templist') :
            ?>
              <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card">
                      <!-- ajax response alert data -->
                      <span id="response_alert"></span>
                      <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                          <h4 class="card-title"></h4>
                        </div>
                        <div class="row">
                          <div class="col">
                            <a href="hotspot_parking_charge_pricebook.php?regen=y" class="btn btn-sm btn-label-github waves-effect mx-1"> <svg class="icon-20 me-1" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                              </svg> Back
                            </a>
                            <button type="submit" name="confirm_import" value="Import" class="btn btn-sm bg-primary text-white mx-1">
                              <svg class="icon-18" style="margin-right: 5px;" width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.38948 8.98403H6.45648C4.42148 8.98403 2.77148 10.634 2.77148 12.669V17.544C2.77148 19.578 4.42148 21.228 6.45648 21.228H17.5865C19.6215 21.228 21.2715 19.578 21.2715 17.544V12.659C21.2715 10.63 19.6265 8.98403 17.5975 8.98403L16.6545 8.98403" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12.0215 2.19044V14.2314" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9.10645 5.1189L12.0214 2.1909L14.9374 5.1189" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                              </svg>Import</button>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive import_table_div">
                          <table id="hotelpricebook_LIST" class="table table-flush-spacing border pb-3" role="grid" width="100%">
                            <thead class="table-head">
                              <tr class="ligth">
                                <th><input type="checkbox" class="select-all" id="select-all" name="select-all"></th>
                                <th>Hotspot Name</th>
                                <th>Hotspot Location</th>
                                <th>Vehicle Type</th>
                                <th>Parking Charge</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $select_temp_hotspotpricebook_list = sqlQUERY_LABEL("SELECT `temp_id`, `csvtype`, `sessionID`, `field1`, `field2`, `field3`, `field4`, `status` FROM `dvi_tempcsv` WHERE `csvtype`='4' and `status`='1' and `sessionID` = '$hotspotprice_import_session_id'") or die("#1-UNABLE_TO_COLLECT_STUDENTS_LIST:" . sqlERROR_LABEL());
                              $num_row = sqlNUMOFROW_LABEL($select_temp_hotspotpricebook_list);
                              if ($num_row == 0) :
                                echo "<script type='text/javascript'>window.location = 'hotspot_parking_charge_pricebook.php'; </script>";
                                die;
                              endif;
                              if ($num_row > 0) :
                                while ($row = sqlFETCHARRAY_LABEL($select_temp_hotspotpricebook_list)) :
                                  $temp_id = $row['temp_id'];
                                  $field1 = $row['field1'];
                                  $field2 = $row['field2'];
                                  $field3 = $row['field3'];
                                  $field4 = $row['field4'];
                              ?>
                                  <tr>
                                    <td><input type="checkbox" class="select-item checkbox" name="temp_id[]" id="customCheck1" value="<?= $temp_id; ?>" /></td>
                                    <td><?= $field1; ?></td>
                                    <td><?= $field2; ?></td>
                                    <td><?= $field3; ?></td>
                                    <td><?= $field4; ?></td>
                                  </tr>
                                <?php
                                endwhile;
                              else :
                                ?>
                                <tr>
                                  <td class="text-center" colspan='4'>No data Available</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            <?php elseif ($_GET['route'] == 'import_response') :

              $query_imported = "SELECT `temp_id` FROM `dvi_tempcsv` WHERE `csvtype`='4' and `sessionID`= '$hotspotprice_import_session_id'";
              $result_query_imported = sqlQUERY_LABEL($query_imported);
              $num_of_row_total = sqlNUMOFROW_LABEL($result_query_imported);

              $total_imported_query = "SELECT `temp_id` FROM `dvi_tempcsv` where `csvtype`='4' and `status`='2' AND `sessionID`='$hotspotprice_import_session_id' ";
              $result_imported_query = sqlQUERY_LABEL($total_imported_query);
              $num_of_row_imported = sqlNUMOFROW_LABEL($result_imported_query);

              $balance_row = $num_of_row_total - $num_of_row_imported;
            ?>
              <div class="row">
                <?php if ($balance_row > '0') : ?>
                  <div class="col-md-12 mt-3 p-0">
                    <div class="card my-3">
                      <div class="card-body">
                        <h5 class="text-center">Few Records Only Imported Successfully</h5>
                        <div class="mt-4 d-flex justify-content-center">
                          <img src="assets/img/caution.gif" style="width: 200px;" alt="Tick GIF">
                        </div>
                        <div class="text-center">
                          <a class="btn btn-light btn-sm shadow-none mt-1" href="hotspot_parking_charge_pricebook.php?route=import">Upload again</a>
                        </div>
                        <h5 class="text-center mt-3">OUT OF <?php echo $num_of_row_total; ?>, <?php echo $num_of_row_imported; ?> IMPORTED</h5>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 p-0">
                    <div class="card mb-4 fullscreen">
                      <div class="card-header d-none">
                        <div class="media">
                          <div class="media-body">
                            <h4 class="content-color-primary mb-0"><?php echo $__list_import; ?></h4>
                          </div>
                        </div>
                      </div>
                      <div class="card-body p-3">
                        <div class="table-responsive import_table_div">
                          <table class="table table-bordered table-responsive" id="import_list" width="100%">
                            <thead>
                              <tr>
                                <th>Reason</th>
                                <th>Hotspot name</th>
                                <th>Hotspot Location</th>
                                <th>Vehicle Type</th>
                                <th>Parking Fee</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $select_not_imported_query = "SELECT `temp_id`, `csvtype`, `sessionID`, `field1`, `field2`, `field3`,`field4`, `status` FROM `dvi_tempcsv` WHERE `csvtype`='4' and `status` IN ( '1','3') and `sessionID` = '$hotspotprice_import_session_id'";
                              $result_not_imported_query = sqlQUERY_LABEL($select_not_imported_query);
                              $not_imported_num_row_count = sqlNUMOFROW_LABEL($result_not_imported_query);
                              if ($not_imported_num_row_count > 0) :
                                while ($row = sqlFETCHARRAY_LABEL($result_not_imported_query)) :
                                  $counter_csv++;
                                  $temp_id = $row['temp_id'];
                                  $field1 = $row['field1'];
                                  $field2 = $row['field2'];
                                  $field3 = $row['field3'];
                                  $field4 = $row['field4'];
                                  $status = $row['status'];
                              ?>
                                  <tr>
                                    <td><?php
                                        if ($field1 != "") :
                                          echo $status == 1 ? '<span class="text-success">Excluded</span>' : '<span class="text-danger">Invalid data</span>';
                                        endif; ?></td>
                                    <td><?= $field1; ?></td>
                                    <td><?= $field2; ?></td>
                                    <td><?= $field3; ?></td>
                                    <td><?= $field4; ?></td>
                                  </tr>
                                <?php endwhile; ?>
                              <?php else : ?>
                                <tr>
                                  <td class="text-center" colspan='4'>No data Available</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php else : ?>
                  <div class="col-md-12 mt-3">
                    <div class="card">
                      <div class="card-body text-center">
                        <h5>All Records Imported Successfully</h5>
                        <div class="mt-4">
                          <img src="assets/img/success.gif" style="width: 120px;" alt="Pending GIF">
                        </div>
                        <a class="btn btn-light btn-sm shadow-none mt-1" href="hotspot_parking_charge_pricebook.php?route=import">Upload again</a>
                        <h5 class="text-center mt-3">OUT OF <?php echo $num_of_row_total; ?>, <?php echo $num_of_row_imported; ?> IMPORTED</h5>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php
              session_regenerate_id(TRUE);
              $hotspotprice_import_session_id = session_id();
            endif; ?>
              </div>

              <?php include('public/__footer.php'); ?>
          </div>
          <!-- / Content -->



          <!-- <div class="content-backdrop fade"></div> -->
        </div>
        <!-- Content wrapper -->

      </div>

      <!-- / Layout page -->
    </div>
  </div>

  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>

  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>

  <?php
  if ($_GET['switch'] != '' && $_GET['type'] != '') :
    if ($_GET['type'] == 'file_temp_loc') :
      $customMsg = "Please browse for a file before clicking the upload button !!!";
    elseif ($_GET['type'] == 'file_valid') :
      $customMsg = "Your file is not CSV !!!";
    elseif ($_GET['type'] == 'file_size') :
      $customMsg = "You file was larger than 5 MB in size !!!";
    elseif ($_GET['type'] == 'file_error_msg') :
      $customMsg = "An error occured while processing the file, Please try agian !!!";
    elseif ($_GET['type'] == 'file_mismatch') :
      $customMsg = "Invalid File Format, Please try again !!!";
    elseif ($_GET['type'] == 'move_file') :
      $customMsg = "File not uploaded, Please try again !!!";
    elseif ($_GET['type'] == 'hotspot_name_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Hotel Name on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'hotspot_location_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Hotel Location on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'vehicle_type_title_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Vehicle Type on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'parking_charge_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Parking Charge on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'check_hotspot_name_not_exist') :
      $hotspot_name = $_GET['hotspot_name'];
      $customMsg = "Invalid data !!! Hotspot Name $hotspot_name is not exist.";
    elseif ($_GET['type'] == 'check_vehicle_type_title_not_exist') :
      $vehicle_type_title = $_GET['vehicle_type_title'];
      $customMsg = "Invalid data !!! Vehicle Type $vehicle_type_title is not exist.";
    endif;
  endif;
  ?> <!-- / Layout wrapper -->
  </div>
  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <script src="./assets/vendor/libs/popper/popper.js"></script>
  <script src="./assets/vendor/js/bootstrap.js"></script>
  <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="./assets/vendor/libs/hammer/hammer.js"></script>
  <script src="./assets/vendor/libs/i18n/i18n.js"></script>
  <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="./assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <!-- Flat Picker -->
  <script src="./assets/vendor/libs/moment/moment.js"></script>
  <script src="./assets/vendor/libs/flatpickr/flatpickr.js"></script>
  <!-- Form Validation -->
  <script src="./assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
  <script src="./assets/js/modal-add-new-cc.js"></script>
  <script src="./assets/js/modal-add-new-address.js"></script>
  <script src="./assets/js/modal-edit-user.js"></script>
  <script src="./assets/js/modal-enable-otp.js"></script>
  <script src="./assets/js/modal-share-project.js"></script>
  <script src="./assets/js/modal-create-app.js"></script>
  <script src="./assets/js/modal-two-factor-auth.js"></script>
  <script src="./assets/js/code.jquery.com_jquery-3.7.0.js"></script>
  <script src="./assets/js/_jquery.dataTables.min.js"></script>
  <script src="./assets/js/_dataTables.buttons.min.js"></script>
  <script src="./assets/js/_jszip_3.10.1_jszip.min.js"></script>
  <script src="./assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
  <script src="./assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
  <script src="./assets/js/_js_buttons.html5.min.js"></script>
  <script src="./assets/js/parsley.min.js"></script>
  <script src="./assets/js/custom-common-script.js"></script>
  <script src="assets/vendor/libs/toastr/toastr.js"></script>
  <script src="assets/js/footerscript.js"></script>


  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>

  <script>
    $("input.select-all").click(function() {

      var checked = this.checked;
      $("input.select-item").each(function(index, item) {
        item.checked = checked;
      });
    });

    // Check selected items
    $("input.select-item").click(function() {
      var checked = this.checked;
      var all = $("input.select-all")[0];
      var total = $("input.select-item").length;
      var len = $("input.select-item:checked:checked").length;
      all.checked = len === total;
    });

    <?php if ($_GET['switch'] != '' && $_GET['type'] != '') : ?>
      TOAST_NOTIFICATION('error', '<?= $customMsg; ?>', 'Error !!!', '', '', '', '', '', '', '', '', '');
    <?php endif; ?>

    function toggleContent() {
      const expandedContent = document.getElementById("expanded-content");
      expandedContent.style.display = expandedContent.style.display === "none" ? "block" : "none";
    }
  </script>
</body>

</html>