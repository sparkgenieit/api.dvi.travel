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

        $hotspot_ID = $_GET['hotspot_ID'];
        $get_hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center mb-2">
                    <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"><?= $get_hotspot_name; ?></h5>
                </div>
                <div id="swiper-gallery">
                    <div class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            <?php
                            $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_GALLERY_LIST:" . sqlERROR_LABEL());
                            $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);

                            if ($total_hotspots_gallery_num_rows_count > 0) :
                                while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :
                                    $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                                    $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                                    $hotspot_photo_url = BASEPATH . 'uploads/hotspot_gallery/' . $hotspot_gallery_name;
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $hotspot_photo_url; ?>')"></div>
                            <?php
                                endwhile;
                            else :
                                $hotspot_photo_url = '';
                            endif;
                            ?>
                        </div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php
                            $hotspot_photo_url = "";
                            $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'") or die("#2-UNABLE_TO_COLLECT_HOTSPOT_GALLERY_LIST:" . sqlERROR_LABEL());
                            $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);
                            if ($total_hotspots_gallery_num_rows_count > 0) :
                                while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :
                                    $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                                    $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                                    $hotspot_photo_url = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $hotspot_photo_url; ?>')"></div>
                            <?php
                                endwhile;
                            else :
                                $hotspot_photo_url = '';
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