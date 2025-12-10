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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    $data = [];
    $counter = 0;

    $select_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_type`, `hotspot_priority`, `hotspot_name`, `hotspot_location`, `hotspot_description`, `hotspot_address`, `hotspot_landmark`, `hotspot_rating`, `hotspot_latitude`, `hotspot_longitude`,  `hotspot_adult_entry_cost`,  `hotspot_child_entry_cost`,  `hotspot_infant_entry_cost`,  `hotspot_foreign_adult_entry_cost`,  `hotspot_foreign_child_entry_cost`,  `hotspot_foreign_infant_entry_cost`, `status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' ORDER BY `hotspot_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
        $counter++;
        $hotspot_ID = $fetch_list_data['hotspot_ID'];
        $hotspot_type = $fetch_list_data['hotspot_type'];
        $hotspot_priority = $fetch_list_data['hotspot_priority'];
        $hotspot_name = $fetch_list_data['hotspot_name'];
        $hotspot_location = $fetch_list_data['hotspot_location'];
        $hotspot_address = $fetch_list_data['hotspot_address'];
        $hotspot_adult_entry_cost = number_format($fetch_list_data['hotspot_adult_entry_cost']);
        $hotspot_child_entry_cost = number_format($fetch_list_data['hotspot_child_entry_cost']);
        $hotspot_infant_entry_cost = number_format($fetch_list_data['hotspot_infant_entry_cost']);
        $hotspot_foreign_adult_entry_cost = number_format($fetch_list_data['hotspot_foreign_adult_entry_cost']);
        $hotspot_foreign_child_entry_cost = number_format($fetch_list_data['hotspot_foreign_child_entry_cost']);
        $hotspot_foreign_infant_entry_cost = number_format($fetch_list_data['hotspot_foreign_infant_entry_cost']);
        $local_members = 'Adult-' . general_currency_symbol . $hotspot_adult_entry_cost . "</br>" . "Children-" . general_currency_symbol . $hotspot_child_entry_cost . "</br>" . "Infants-" . general_currency_symbol . $hotspot_infant_entry_cost;
        $foreign_members = 'Adult-' . general_currency_symbol . $hotspot_foreign_adult_entry_cost . "</br>" . "Children-" . general_currency_symbol . $hotspot_foreign_child_entry_cost . "</br>" . "Infants-" . general_currency_symbol . $hotspot_foreign_infant_entry_cost;

        $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID' LIMIT 1") or die("#1-UNABLE_TO_COLLECT_hotspot_GALLERY_LIST:" . sqlERROR_LABEL());
        $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);
        if ($total_hotspots_gallery_num_rows_count > 0) :
            $fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query);
            $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
            $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
            $hotspot_photo_url = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;
        else :
            $hotspot_photo_url = "";
        endif;

        $status = $fetch_list_data['status'];

        // Split hotspot_location by '|'
        $hotspot_locations = explode('|', $hotspot_location);
        // Join locations with <br> for new line display
        $formatted_hotspot_locations = implode('<br>', $hotspot_locations);

        $data[] = [
            "counter" => $counter,
            "hotspot_type" => $hotspot_type,
            "hotspot_priority" => $hotspot_priority,
            "hotspot_name" => $hotspot_name,
            "hotspot_locations" => $formatted_hotspot_locations, // Locations with new lines
            "hotspot_address" => $hotspot_address,
            "hotspot_photo_url" => $hotspot_photo_url,
            "local_members" => $local_members,
            "foreign_members" => $foreign_members,
            "modify" => $hotspot_ID
        ];

    endwhile; //end of while loop

    echo json_encode(["data" => $data]);

else :
    echo "Request Ignored !!!";
endif;
