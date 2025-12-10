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

    if ($_GET['type'] == 'show_form') :

        $hotel_ID = $_GET['hotel_ID'];
        $get_hotel_name = getHOTELDETAILS($hotel_ID, 'HOTEL_NAME');
        $hotel_category = getHOTELDETAILS($hotel_ID, 'hotel_category');
?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center mb-2">
                    <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"><?= $get_hotel_name; ?></h5>
                </div>
                <div id="swiper-gallery">
                    <div class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            <?php
                            $select_hotel_room_gallery_list_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                            $total_hotel_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_room_gallery_list_query);
                            if ($total_hotel_gallery_num_rows_count > 0) :
                                while ($fetch_hotel_room_gallery_data = sqlFETCHARRAY_LABEL($select_hotel_room_gallery_list_query)) :
                                    $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_data['hotel_room_gallery_details_id'];
                                    $room_gallery_name = $fetch_hotel_room_gallery_data['room_gallery_name'];
                                    $hotel_room_photo_url = BASEPATH . 'uploads/room_gallery/' . $room_gallery_name;
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $hotel_room_photo_url; ?>')"></div>
                            <?php
                                endwhile;
                            else :
                                $hotel_room_photo_url = '';
                            endif;
                            ?>
                        </div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php
                            $hotel_room_photo_url = "";
                            $select_hotel_room_gallery_list_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#2-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                            $total_hotel_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_room_gallery_list_query);
                            if ($total_hotel_gallery_num_rows_count > 0) :
                                while ($fetch_hotel_room_gallery_data = sqlFETCHARRAY_LABEL($select_hotel_room_gallery_list_query)) :
                                    $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_data['hotel_room_gallery_details_id'];
                                    $room_gallery_name = $fetch_hotel_room_gallery_data['room_gallery_name'];
                                    $hotel_room_photo_url = BASEPATH . '/uploads/room_gallery/' . $room_gallery_name;
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $hotel_room_photo_url; ?>')"></div>
                            <?php
                                endwhile;
                            else :
                                $hotel_room_photo_url = '';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/vendor/libs/swiper/swiper.js"></script>
        <script src="assets/js/ui-carousel.js"></script>

<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>