<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark De`Science
*
*/
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_hotel_code') :

        $hotel_city = trim($_POST['hotel_city']);
        $hotel_id = $_POST['hotel_id'];

        //$firstThreeLetters = substr($hotel_city, 0, 3);
        $randomNumber = mt_rand(1, 1000000);
        if ($hotel_id) :
            $filter_by_hotel = " and `hotel_id` != '$hotel_id' ";
        endif;

        $collect_hotel_code_count = sqlQUERY_LABEL("SELECT `hotel_code` FROM `dvi_hotel` WHERE `deleted` = '0' and `hotel_city` = '$hotel_city' {$filter_by_hotel} ORDER BY `hotel_id` DESC LIMIT 0,1") or die("#1-collect_hotel_code_count: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($collect_hotel_code_count) > 0) :
            while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_code_count)) :
                $hotel_code = $collect_data['hotel_code'];
            endwhile;
            $hotel_code++;
        else :
            $hotel_code = 'DVIHTL' . $randomNumber;
        endif;

        echo strtoupper($hotel_code);

    elseif ($_GET['type'] == 'show_amenities_code') :

        $amenities_title = trim($_POST['amenities_title']);
        $hotel_id = $_POST['hotel_id'];
        $hotel_amenities_id = $_POST['hotel_amenities_id'];


        $amenities_title_prefix = substr($amenities_title, 0, 3);
        $randomNumber = mt_rand(1, 1000000);

        if ($hotel_id) :
            $filter_by_hotel = " and `hotel_id` != '$hotel_id' ";
        endif;
        if ($hotel_amenities_id) :
            $filter_by_amenities = " and `hotel_amenities_id` != '$hotel_amenities_id' ";
        endif;

        $collect_hotel_amenity_count = sqlQUERY_LABEL("SELECT `amenities_code`FROM `dvi_hotel_amenities` WHERE `deleted` = '0' AND `amenities_title` = '$amenities_title' {$filter_by_hotel} {$filter_by_amenities} ORDER BY `hotel_amenities_id` DESC LIMIT 0,1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($collect_hotel_amenity_count) > 0) :
            while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_amenity_count)) :
                $amenities_code = $collect_data['amenities_code'];
            endwhile;
            $amenities_code++;
        else :
            $amenities_code = 'DVIA' . $amenities_title_prefix . $randomNumber;
        endif;

        echo strtoupper($amenities_code);

    elseif ($_GET['type'] == 'show_branch_code') :

    // $vendor_country = trim($_POST['vendor_country']);
    /* $vendor_id = $_POST['vendor_id'];
        $vendor_branch_id = $_POST['vendor_branch_id'];
        $vendor_branch_counter = $_POST['vendor_branch_counter'];

        // $amenities_title_prefix = substr($amenities_title, 0, 3);
        $randomNumber = mt_rand(1, 1000000);

        if ($vendor_id) :
            $filter_by_vendor = " and `vendor_id` = '$vendor_id' ";
        endif;
        if ($vendor_branch_id) :
            $filter_by_branch = " and `vendor_branch_id` = '$vendor_branch_id' ";
        endif;

        $collect_vendor_branch_count = sqlQUERY_LABEL("SELECT `vendor_branch_code` FROM `dvi_vendor_branches` WHERE `deleted` = '0' {$filter_by_branch} {$filter_by_vendor} ORDER BY `vendor_branch_id` DESC LIMIT 0,1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($collect_vendor_branch_count) > 0) :
            while ($collect_data = sqlFETCHARRAY_LABEL($collect_vendor_branch_count)) :
                $vendor_branch_code = $collect_data['vendor_branch_code'];
            endwhile;
            $vendor_branch_code++;
        else :
            $vendor_branch_code = 'DVIVE'  . $randomNumber;
        endif;

        echo strtoupper($vendor_branch_code);*/

    elseif ($_GET['type'] == 'show_hotel_category_code') :

        $hotel_category_id = $_POST['hotel_category_id'];
        $hotel_category_title = $_POST['hotel_category_title'];

        $hotelcategory_title_prefix = substr($hotel_category_title, 0, 1);
        $randomNumber = mt_rand(1, 1000000);

        if ($hotel_category_id) :
            $filter_by_hotel_category = " and `hotel_category_id` != '$hotel_category_id' ";
        endif;
        if ($hotel_category_title) :
            $filter_by_hotel_category_title = " and `hotel_category_title` != '$hotel_category_title' ";
        endif;

        $collect_hotel_categorycode_count = sqlQUERY_LABEL("SELECT `hotel_category_code` FROM `dvi_hotel_category` WHERE `deleted` = '0' AND `hotel_category_title` = '$hotel_category_title' {$filter_by_hotel_category} {$filter_by_hotel_category_title} ORDER BY `hotel_category_id` DESC LIMIT 0,1") or die("#1-collect_hotel_code_count: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($collect_hotel_categorycode_count) > 0) :
            while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_categorycode_count)) :
                $hotel_category_code = $collect_data['hotel_category_code'];
            endwhile;
            $hotel_category_code++;
        else :
            $hotel_category_code = 'DVI' . $hotelcategory_title_prefix . '-' . $randomNumber;
        endif;

        echo strtoupper($hotel_category_code);
    elseif ($_GET['type'] == 'show_referral_number') :

        $referral_number = $_POST['referral_number'];

        // Perform validation or data fetching logic
        $collect_agent_ref_no = sqlQUERY_LABEL("SELECT `agent_ref_no` FROM `dvi_agent` WHERE `deleted` = '0' AND `agent_ref_no` = '$referral_number'");

        if (sqlNUMOFROW_LABEL($collect_agent_ref_no) > 0) {
            // Referral number exists, handle accordingly
            $response = array('valid' => true);
        } else {
            // Referral number does not exist, handle accordingly
            $response = array('valid' => false);
        }

        // Prepare JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    endif;

else :
    echo "Request Ignored";
endif;
