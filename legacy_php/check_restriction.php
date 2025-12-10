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
include_once('Encryption.php');

$check_page = basename($_SERVER['PHP_SELF']);

if ($check_page != 'restricted.php') :

    $restricted_access = checkmenupage($check_page, $logged_user_level);

    if (checkmenupage($check_page, $logged_user_level) == 0) :
        echo "<script type='text/javascript'>window.location = 'restricted.php'</script>";
    endif;
else :
    echo "<script type='text/javascript'>window.location = 'logout.php'</script>";
endif;
