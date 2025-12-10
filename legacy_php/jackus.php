<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/

ini_set('display_errors', 0);
ini_set('log_errors', 0);
ini_set('error_log', dirname(__FILE__) . 'tmp/logs/error_log.txt');
$error_log_file = __DIR__ . '/logs/error.log';

//Configuration Setup
include_once('config/config.php');

//Database Configuration
include_once('config/database.php');

//Common Functions without DATABASE / TABLES involved
include_once('controller/core/global_SQLifunctions.php');
include_once('controller/core/global_functions.php');

//Custom Page Titles
include_once('controller/lang/pagetitle_lang_' . LANG . '.php');

//Contains all Common Fields
include_once('controller/lang/general_lang_' . LANG . '.php');

//Custom language 
// include_once('controller/lang/custom_lang_'.LANG.'.php');

//SQL codes for quick operations
include_once('controller/core/sql_functions.php');

//validation auto enabler
include_once('controller/validation/validation.class.inc');
include_once('controller/validation/messagepopup.class.inc');

if ($validationCLASS != NULL) :
    //include_once('controller/validation/validationexp.class.inc');
    include_once('controller/validation/validationjs.class.inc');
    include_once('controller/validation/error.class.inc');
    //page-auto-gen-codinator
    include_once('controller/validation/' . $validationCLASS . '.class.inc');
endif;

//Common Breadcrumb
include_once('controller/core/breadcrumb.class.inc');

//echo "<div style='margin: 0 auto; text-align: center; width: 450px; margin-top: 15%'><b>Software Updating in progress</b><br />If you are in the middle of your work, contact IT team.</div>";
//exit();

include_once('config/config.php');
//Contains all Common Fields
include_once('controller/lang/general_lang_' . LANG . '.php');
//Custom language 
include_once('controller/lang/custom_lang_' . LANG . '.php');
//To verify license
//include_once('controller/core/composer.class.inc');
//include_once('controller/core/affirmation.class.inc');
//MENU Titles
include_once('controller/lang/menu_' . LANG . '.php');
//Custom Page Titles
include_once('controller/lang/pagetitle_lang_' . LANG . '.php');
//Database Configuration
include_once('config/database.php');
//Common Functions without DATABASE / TABLES involved
//include_once('controller/core/global_SQLfunctions.php');
include_once('controller/core/global_SQLifunctions.php');
include_once('controller/core/global_functions.php');
include_once('controller/core/custom_function.php');
include_once('controller/core/custom_view_function.php');

//Validate API Calls to 3rd Party Links
//include_once('controller/core/api_functions.php');
//SQL codes for quick operations
include_once('controller/core/sql_functions.php');
//Can be used for generating Reports
include_once('controller/core/report_functions.php');
//validation auto enabler
include_once('controller/validation/validation.class.inc');
include_once('controller/validation/messagepopup.class.inc');
//Common Breadcrumb
include_once('controller/core/breadcrumb.class.inc');

if ($validationCLASS != NULL) {
    //include_once('controller/validation/validationexp.class.inc');
    include_once('controller/validation/validationjs.class.inc');
    include_once('controller/validation/error.class.inc');
    //page-auto-gen-codinator
    include_once('controller/validation/' . $validationCLASS . '.class.inc');
}
