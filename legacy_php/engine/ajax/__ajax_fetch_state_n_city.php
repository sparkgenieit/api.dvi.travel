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

    if ($_GET['type'] == 'selectize_country' && isset($_GET["COUNTRY_ID"]) && !empty($_GET["COUNTRY_ID"])) :

        $options = [];

        $COUNTRY_ID = $_GET['COUNTRY_ID'];

        if ($COUNTRY_ID == 0 && $COUNTRY_ID == "") :
            $get_country_ID = getCOUNTRYLIST($COUNTRY_ID, 'country_id_from_text');
        else :
            $get_country_ID = $COUNTRY_ID;
        endif;

        $selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_states` WHERE `country_id` = '$get_country_ID' ORDER BY `name` ASC") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
                $name = $fetch_data["name"];

                $options[] = [
                    "value" => addslashes($fetch_data['id']),
                    "text" => addslashes($fetch_data['name'])
                ];
            endwhile;
        else :
            $options[] = [
                "value" => '',
                "text" => "No records found"
            ];
        endif;

        header('Content-Type: application/json');
        echo json_encode($options);

    elseif ($_GET['type'] == 'selectize_state' && isset($_GET["STATE_ID"]) && !empty($_GET["STATE_ID"])) :

        $options = [];

        $STATE_ID = $_GET['STATE_ID'];

        if ($STATE_ID == 0 && $STATE_ID == "") :
            $get_state_ID = getSTATELIST('', $STATE_ID, 'state_id_from_text');
        else :
            $get_state_ID = $STATE_ID;
        endif;

        $selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_cities` WHERE `state_id` = '$get_state_ID' ORDER BY `name` ASC") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
                $name = $fetch_data["name"];

                $options[] = [
                    "value" => $fetch_data['id'],
                    "text" => $fetch_data['name']
                ];
            endwhile;
        else :
            $options[] = [
                "value" => '',
                "text" => "No records found"
            ];
        endif;

        header('Content-Type: application/json');
        echo json_encode($options);

    elseif ($_GET['type'] == 'selectize_state_hotel' && isset($_GET["STATE_ID"]) && !empty($_GET["STATE_ID"])) :

        $options = [];

        $STATE_ID = $_GET['STATE_ID'];

        if ($STATE_ID == 0 && $STATE_ID == "") :
            $get_state_ID = getSTATELIST('', $STATE_ID, 'state_id_from_text');
        else :
            $get_state_ID = $STATE_ID;
        endif;

        $selected_query = sqlQUERY_LABEL("SELECT CITIES.`id`,CITIES.`name` FROM `dvi_hotel` HOTEL LEFT JOIN `dvi_cities` CITIES ON HOTEL.hotel_city=CITIES.id WHERE CITIES.`state_id` = '$get_state_ID' GROUP BY CITIES.`id` ORDER BY CITIES.`name` ASC") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
                $name = $fetch_data["name"];

                $options[] = [
                    "value" => $fetch_data['id'],
                    "text" => $fetch_data['name']
                ];
            endwhile;
        else :
            $options[] = [
                "value" => '',
                "text" => "No records found"
            ];
        endif;

        header('Content-Type: application/json');
        echo json_encode($options);

    endif;

    if ($_GET['type'] == 'selectize_city') :
        $options = [];

        $selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_cities` ORDER BY `name` ASC") or die("#1-getCITY: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
                $name = $fetch_data["name"];
                $options[] = [
                    "value" => $fetch_data['id'],
                    "text" => $fetch_data['name']
                ];
            endwhile;
        else :
            $options[] = [
                "value" => '',
                "text" => "No records found"
            ];
        endif;

        header('Content-Type: application/json');
        echo json_encode($options);

    endif;

else :
    echo "Request Ignored";
endif;
