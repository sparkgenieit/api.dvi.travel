<?php

ob_start();
session_start();
$tollcharge_import_session_id = session_id();
include_once('jackus.php');
require 'vendor/autoload.php'; // Include the Composer autoload file

// use PhpOffice\PhpSpreadsheet\IOFactory;
admin_reguser_protect();

if ($_GET['regen'] == 'y' && $_GET['route'] == 'import') :
  session_regenerate_id(TRUE);
  $tollcharge_import_session_id = session_id();
  echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import'; </script>";
elseif ($_GET['regen'] == 'y') :
  session_regenerate_id(TRUE);
  $tollcharge_import_session_id = session_id();
  echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php'; </script>";
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
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_temp_loc'</script>";
    die;
  endif;

  if (!preg_match("/\.(xlsx|csv)$/i", $file_name)) : // check file extension
    //echo 'Error: your file is not CSV.';
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_valid'</script>";
    @unlink($file_temp_loc); // remove to the temp folder
    die;
  endif;



  if ($file_size > 10485760) : // file check size
    //echo "Error: you file was larger than 5 Megabytes in size.";
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_size'</script>";
    die;
  endif;

  if ($file_error_msg == 1) : // 
    //echo "Error: an error occured while processing the file, try agian.";
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_error_msg'</script>";
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

  if (!empty($locationtollcharge_csv_file_titles)) :

    $sample_col_count = count($locationtollcharge_csv_file_titles);

    // compare both csv file titles count and each titles.
    if ($imported_col_count == $sample_col_count) :

      for ($i = 0; $i < $sample_col_count; $i++) :

        if (strtolower($imported_csv_file[$i]) != strtolower($locationtollcharge_csv_file_titles[$i])) :
          //echo $imported_csv_file[$i];
          //echo "Error: Invalid File Content, try again.";
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_mismatch'</script>";
          die;
        endif;
      endfor;
    else :
      //echo "Error: Invalid File Content, try again.";
      echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=file_mismatch'</script>";
      die;
    endif;
  endif;

  $move_file = move_uploaded_file($file_temp_loc, "uploads/excel_uploads/{$file_name}"); // temp loc, file name
  if ($move_file != true) : // if not move to the temp location
    //echo 'Error: File not uploaded, try again.';
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=move_file'</script>";
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
        $source_location = trim($validation_globalclass->sanitize($data[1]));
        $destination_location = trim($validation_globalclass->sanitize($data[2]));
        //$source_location = htmlentities($source_location);
        //$destination_location = htmlentities($destination_location);
        $vehicle_type_title = trim($validation_globalclass->sanitize($data[3]));
        $toll_charge = trim($validation_globalclass->sanitize($data[4]));

        //CHECK SOURCE LOCATION IS EMPTY
        if ($source_location == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=source_location_required&sno=$count'</script>";
          die;
        endif;

        //CHECK DESTINATION LOCATION IS EMPTY
        if ($destination_location == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=destination_location_required&sno=$count'</script>";
          die;
        endif;

        // CHECK vehicle_type_title IS EMPTY
        if ($vehicle_type_title == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=vehicle_type_title_required&sno=$count'</script>";
          die;
        endif;

        // CHECK TOLL CHARGE IS EMPTY
        if ($toll_charge == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=toll_charge_required&sno=$count'</script>";
          die;
        endif;

        $getLOCATION_ID = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($source_location, $destination_location);


        if ($getLOCATION_ID == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=location_not_exist&source_location=$source_location&destination_location=$destination_location'</script>";
          die;
        endif;

        $LOCATION_ID_des_src = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($destination_location, $source_location);
        if ($LOCATION_ID_des_src == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=location_not_exist&source_location=$source_location&destination_location=$destination_location'</script>";
          die;
        endif;

        $getVEHICLETYPEID = getVEHICLETYPE($vehicle_type_title, 'get_vehicletypeid_from_vehicletypetitle');
        if ($getVEHICLETYPEID == "") :
          session_regenerate_id(TRUE);
          $tollcharge_import_session_id = session_id();
          echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import&switch=E&type=check_vehicle_type_title_not_exist&vehicle_type_title=$vehicle_type_title'</script>";
          die;
        endif;

        $arrFields = array('`csvtype`', '`sessionID`', '`field1`', '`field2`', '`field3`', '`field4`', '`field5`', '`field6`', '`status`');

        $arrValues = array("5", "$tollcharge_import_session_id", "$source_location", "$destination_location", "$vehicle_type_title", "$toll_charge", "$getLOCATION_ID", "$LOCATION_ID_des_src", "1");

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
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=templist&code=1'
		</script>";
    exit();
  else :
    //Templist Shift details			
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?code=2'
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
      $select_temp_data_records = sqlQUERY_LABEL("SELECT `temp_id`,`csvtype`,`sessionID`,`field1`,`field2`,`field3`,`field4`,`field5`,`field6`,`status` FROM `dvi_tempcsv` WHERE `temp_id`='$temp_id' and `status`='1' and `csvtype` = '5'") or die("Unable to get records:" . sqlERROR_LABEL());

      while ($fetch_records = sqlFETCHARRAY_LABEL($select_temp_data_records)) :
        $source_location = htmlentities($fetch_records['field1']);
        $destination_location = htmlentities($fetch_records['field2']);
        $vehicle_type_title = trim($fetch_records['field3']);
        $VEHICLETYPEID = getVEHICLETYPE($vehicle_type_title, 'get_vehicletypeid_from_vehicletypetitle');
        $toll_charge = $fetch_records['field4'];
        //SOURCE TO DESTINATION
        $LOCATION_ID = $fetch_records['field5'];

        //$LOCATION_ID = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($source_location, $destination_location);

        $arrFields = array('`location_id`', '`vehicle_type_id`', '`toll_charge`', '`createdby`', '`status`');
        $arrValues = array("$LOCATION_ID", "$VEHICLETYPEID", "$toll_charge", "$logged_user_id", "1");
        $vehicle_toll_charge_ID = "";
        $selected_vehicle_parking_charge = sqlQUERY_LABEL("SELECT `vehicle_toll_charge_ID` FROM `dvi_vehicle_toll_charges` where  `location_id` =  '$LOCATION_ID' AND `vehicle_type_id` ='$VEHICLETYPEID' AND `status`='1' AND `deleted`='0' ") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
        while ($fetch_vehicle_toll_charge = sqlFETCHARRAY_LABEL($selected_vehicle_parking_charge)) :
          $vehicle_toll_charge_ID = $fetch_vehicle_toll_charge['vehicle_toll_charge_ID'];
        endwhile;

        if ($vehicle_toll_charge_ID != "") :
          //UPDATE TOLL TABLE
          $sqlWhere_toll = " `vehicle_toll_charge_ID` = '$vehicle_toll_charge_ID' ";
          if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields, $arrValues, $sqlWhere_toll)) :
          //success
          else :
            $code = '0';
          endif;
        else :
          //INSERT toll TABLE
          if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields, $arrValues, '')) :
          //success
          else :
            $code = '0';
          endif;
        endif;

        //DESTINATION TO SOURCE
        $LOCATION_ID_des_src = $fetch_records['field6'];
        //$LOCATION_ID_des_src = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($destination_location, $source_location);


        $arrFields_des_src = array('`location_id`', '`vehicle_type_id`', '`toll_charge`', '`createdby`', '`status`');
        $arrValues_des_src = array("$LOCATION_ID_des_src", "$VEHICLETYPEID", "$toll_charge", "$logged_user_id", "1");

        $vehicle_toll_charge_ID_des_src = "";
        $selected_vehicle_parking_charge1 = sqlQUERY_LABEL("SELECT `vehicle_toll_charge_ID` FROM `dvi_vehicle_toll_charges` where  `location_id` =  '$LOCATION_ID_des_src' AND `vehicle_type_id` ='$VEHICLETYPEID' AND `status`='1' AND `deleted`='0' ") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
        while ($fetch_vehicle_toll_charge1 = sqlFETCHARRAY_LABEL($selected_vehicle_parking_charge1)) :
          $vehicle_toll_charge_ID_des_src = $fetch_vehicle_toll_charge1['vehicle_toll_charge_ID'];
        endwhile;

        if ($vehicle_toll_charge_ID_des_src != "") :
          //UPDATE TOLL TABLE
          $sqlWhere_toll_des_src = " `vehicle_toll_charge_ID` = '$vehicle_toll_charge_ID_des_src' ";
          if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, $sqlWhere_toll_des_src)) :
          //success
          else :
            $code = '0';
          endif;
        else :
          //INSERT PARKING TABLE
          if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, '')) :
          //success
          else :
            $code = '0';
          endif;
        endif;

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

      endwhile;
    endfor;
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=import_response'</script>";
  elseif ($rowCount == 0) :
    echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php?route=templist&switch=W&type=import_row'</script>";
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
  <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">

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
                        <div class="row justify-content-end align-items-center ps-4 py-5">
                          <div class="col-md-5 ps-3">
                          </div>
                          <div class="col-md-4">
                            <div class="form-group text-start">
                              <select id="select_source_location" name="select_source_location" class="form-select form-control">
                                <?= getSOURCE_LOCATION_DETAILS($select_source_location, 'select_source'); ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 justify-content-end">
                            <button type="button" class="btn btn-success" style="padding-bottom: 0.6rem !important;
    padding-top: 0.6rem !important;" onclick="downloadSample()">Download Sample CSV</button>
                          </div>
                        </div>
                        <div class="card-body bulk-import-body text-center p-5 pt-0">
                          <img src="assets/img/svg/bulk-upload.svg" class="img-fluid" />
                          <div class="mt-5">
                            <input type="file" class="btn btn-light rounded-pill btn-sm p-0" name="csv">
                          </div>
                          <button type="submit" name="upload_csv" class="btn btn-primary rounded-pill btn-sm mt-3" value="confirm_upload">Upload</button>
                          <div class="mt-3">
                            <div id="icon-container" class="clickable mt-2" onclick="toggleContent()">
                              <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512; cursor:pointer;" xml:space="preserve" class="ms-1">
                                <g>
                                  <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" fill="#3a57e8" data-original="#000000" opacity="1" class=""></path>
                                  <path d="M12 7a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1zM12 15c-.26 0-.52.11-.71.29-.181.19-.29.45-.29.71s.109.52.29.71c.38.37 1.04.37 1.42 0 .18-.19.29-.45.29-.71s-.11-.52-.29-.71c-.19-.18-.45-.29-.71-.29z" fill="#3a57e8" data-original="#000000" opacity="1" class=""></path>
                                </g>
                              </svg>
                            </div>
                            <div id="expanded-content" style="display: none;" class="mt-2">
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
                          <!-- /Download Sample  -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /Basic  -->
                </div>
              </form>

            <?php elseif ($_GET['route'] == 'templist') : ?>
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
                            <a href="location_toll_charge_pricebook.php?regen=y" class="btn btn-sm btn-label-github waves-effect mx-1"> <svg class="icon-20 me-1" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                <th>Source Location</th>
                                <th>Destination Location</th>
                                <th>Vehicle Type</th>
                                <th>Toll Charge</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $select_temp_hotspotpricebook_list = sqlQUERY_LABEL("SELECT `temp_id`, `csvtype`, `sessionID`, `field1`, `field2`, `field3`,`field4`, `status` FROM `dvi_tempcsv` WHERE `csvtype`='5' and `status`='1' and `sessionID` = '$tollcharge_import_session_id'") or die("#1-UNABLE_TO_COLLECT_STUDENTS_LIST:" . sqlERROR_LABEL());
                              $num_row = sqlNUMOFROW_LABEL($select_temp_hotspotpricebook_list);
                              if ($num_row == 0) :
                                echo "<script type='text/javascript'>window.location = 'location_toll_charge_pricebook.php'; </script>";
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
                                  <td class="text-center" colspan='5'>No data Available</td>
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
              $query_imported = "SELECT `temp_id` FROM `dvi_tempcsv` WHERE `csvtype`='5' and `sessionID`= '$tollcharge_import_session_id'";
              $result_query_imported = sqlQUERY_LABEL($query_imported);
              $num_of_row_total = sqlNUMOFROW_LABEL($result_query_imported);

              $total_imported_query = "SELECT `temp_id` FROM `dvi_tempcsv` where `csvtype`='5' and `status`='2' AND `sessionID`='$tollcharge_import_session_id' ";
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
                          <a class="btn btn-light btn-sm shadow-none mt-1" href="location_toll_charge_pricebook.php?route=import">Upload again</a>
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
                                <th>Source Location</th>
                                <th>Destination Location</th>
                                <th>Vehicle Type</th>
                                <th>Toll Charge</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $select_not_imported_query = "SELECT `temp_id`, `csvtype`, `sessionID`, `field1`, `field2`, `field3`,`field4`, `status` FROM `dvi_tempcsv` WHERE `csvtype`='5' and `status` IN ( '1','3') and `sessionID` = '$tollcharge_import_session_id'";
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
                                  <td class="text-center" colspan='5'>No data Available</td>
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
                        <a class="btn btn-light btn-sm shadow-none mt-1" href="location_toll_charge_pricebook.php?route=import">Upload again</a>
                        <h5 class="text-center mt-3">OUT OF <?php echo $num_of_row_total; ?>, <?php echo $num_of_row_imported; ?> IMPORTED</h5>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php
              session_regenerate_id(TRUE);
              $tollcharge_import_session_id = session_id();
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
    elseif ($_GET['type'] == 'source_location_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Source Location on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'destination_location_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty destination location on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'vehicle_type_title_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Vehicle Type on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'toll_charge_required') :
      $sno = $_GET['sno'];
      $customMsg = "Empty Toll Charge on Row - $sno  !!! ";
    elseif ($_GET['type'] == 'location_not_exist') :
      $source_location = $_GET['source_location'];
      $destination_location = $_GET['destination_location'];
      $customMsg = "Invalid data !!! Source location: $source_location or Source location: $source_location does not exist.";
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
  <script src="assets/js/selectize/selectize.min.js"></script>

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>

  <script>
    $(document).ready(function() {
      $('#select_source_location').selectize();
    });

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

    function downloadSample() {
      const select = document.getElementById('select_source_location');
      const selectedOption = select.value;
      if (!selectedOption) {
        TOAST_NOTIFICATION('error', 'Please select an Source Location before downloading.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        return;
      }
      const form = document.createElement('form');
      form.method = 'post';
      form.action = 'excel_export_sampleformat_vehicle_toll_charge.php';
      form.style.display = 'none';

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'select_source_location';
      input.value = selectedOption;
      form.appendChild(input);

      document.body.appendChild(form);
      form.submit();
    }

    function toggleContent() {
      const content = document.getElementById('expanded-content');
      content.style.display = content.style.display === 'none' ? 'block' : 'none';
    }
  </script>
</body>

</html>