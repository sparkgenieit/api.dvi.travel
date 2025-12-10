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

        $get_selected_DATE = $_GET['DT'];
        $get_selected_HOTEL_ID = $_GET['ID'];
        $get_selected_ROOM_TYPE_ID = $_GET['ROOM_TYPE_ID'];

        $formatter_date = trim(date('j', strtotime($get_selected_DATE)));
        $formatter_year = trim(date('Y', strtotime($get_selected_DATE)));
        $formatter_month = trim(date('F', strtotime($get_selected_DATE)));
        $formatted_day = trim('day_' . $formatter_date);

        $formatted_date = date("d-m-Y", strtotime($get_selected_DATE));
?>
        <div class="row">
            <div class="row d-flex justify-content-between">
                <h4 class="col-md-6">Update Pricebook for <?= dateformat_datepicker($get_selected_DATE); ?></h4>
                <button type="button" class="btn-close mt-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#hotel_pricebook" id="hotel_pricebook_btn" onclick="showHOTEL_PRICEBOOK_ROOMS()" aria-controls="hotel_pricebook" aria-selected="true" tabindex="-1">Rooms</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#amenities_pricebook" aria-controls="amenities_pricebook" id="amenities_pricebook_btn" onclick="showHOTEL_PRICEBOOK_AMENITIES()" aria-selected="false">Amenities</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="hotel_pricebook" role="tabpanel">
                        <div class="row">
                            <form method="post" action="" data-parsley-validate>
                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <span class="alert-icon bg-transparent text-primary me-2">
                                        <i class="ti ti-exclamation-circle ti-xs"></i>
                                    </span>
                                    Choosen the filter option to find the rooms.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="hotel_name">Hotel Name</label>
                                        <h5 class="text-black mb-0"><?= getHOTEL_DETAIL($get_selected_HOTEL_ID, '', 'label'); ?></h5>
                                        <h6 class="text-muted mb-0"><?= getHOTEL_PLACE($get_selected_HOTEL_ID, 'hotel_place'); ?></h6>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="choosen_room_type">Choose Room Type </label>
                                        <select id="choosen_room_type" name="choosen_room_type" class="form-control">
                                            <?= getHOTEL_ROOM_TYPE_DETAIL($get_selected_HOTEL_ID, $get_selected_ROOM_TYPE_ID, '', 'select') ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end mb-1" style="padding-bottom: 3px;">
                                        <button type="button" onclick="showHOTELPRICEBOOKROOMS('<?= $get_selected_HOTEL_ID; ?>','<?= $get_selected_DATE; ?>')" class="btn btn-primary btn-md">Apply Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <span id="showHOTELROOMS"></span>
                    </div>
                    <div class="tab-pane fade" id="amenities_pricebook" role="tabpanel">
                        <div class="row">
                            <form method="post" action="" data-parsley-validate>
                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <span class="alert-icon bg-transparent text-primary me-2">
                                        <i class="ti ti-exclamation-circle ti-xs"></i>
                                    </span>
                                    Choosen the filter option to find the amenities quickly.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="hotel_name">Hotel Name</label>
                                        <h5 class="text-black mb-0"><?= getHOTEL_DETAIL($get_selected_HOTEL_ID, '', 'label'); ?></h5>
                                        <h6 class="text-muted mb-0"><?= getHOTEL_PLACE($get_selected_HOTEL_ID, 'hotel_place'); ?></h6>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="choosen_room_amenities">Choose Amenities </label>
                                        <select id="choosen_room_amenities" name="choosen_room_amenities" class="form-control">
                                            <?= getHOTEL_ROOM_AMENITIES_DETAIL($get_selected_HOTEL_ID, '', 'select') ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end mb-1" style="padding-bottom: 3px;">
                                        <button type="button" onclick="showHOTELPRICEBOOKAMENITIES('<?= $get_selected_HOTEL_ID; ?>','<?= $get_selected_DATE; ?>')" class="btn btn-primary btn-md">Apply Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <span id="showHOTELAMENITIES"></span>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            <?php if ($get_selected_ROOM_TYPE_ID != '' && $get_selected_ROOM_TYPE_ID != '0') : ?>
                showHOTELPRICEBOOKROOMS('<?= $get_selected_HOTEL_ID; ?>', '<?= $get_selected_DATE; ?>');
            <?php endif; ?>
            $('#choosen_room_type').selectize();
            $('#choosen_room_amenities').selectize();

            $(document).ready(function() {
                var e = document.getElementById("hotel_pricebook_rooms_list");

                if (e && e.scrollHeight > e.clientHeight) {
                    new PerfectScrollbar(e, {
                        wheelPropagation: false,
                        suppressScrollX: true, // Disable horizontal scrollbar
                    });
                }
            });

            function showHOTEL_PRICEBOOK_ROOMS() {
                $('#amenities_pricebook').removeClass('show active');
                $('#amenities_pricebook_btn').removeClass('active');
                $('#hotel_pricebook').addClass('show active');
                $('#hotel_pricebook_btn').addClass('active');
                /* $('#showHOTELROOMS').empty();
                $('#choosen_room_type')[0].selectize.setValue(''); */
            }

            function showHOTEL_PRICEBOOK_AMENITIES() {
                $('#hotel_pricebook').removeClass('show active');
                $('#hotel_pricebook_btn').removeClass('active');
                $('#amenities_pricebook').addClass('show active');
                $('#amenities_pricebook_btn').addClass('active');
                /* $('#showHOTELROOMS').empty();
                $('#choosen_room_type')[0].selectize.setValue(''); */
            }

            function showHOTELPRICEBOOKROOMS(HOT_ID, DATE) {
                var CHOSEN_ROOM_TYPE = $('#choosen_room_type').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_show_hotel_pricebook_form.php?type=show_available_rooms',
                    data: {
                        ID: HOT_ID,
                        ROOM_TYPE: CHOSEN_ROOM_TYPE,
                        DT: DATE
                    },
                    success: function(response) {
                        $('#showHOTELROOMS').html('');
                        $('#showHOTELROOMS').html(response);
                    }
                });
            }

            function showHOTELPRICEBOOKAMENITIES(HOT_ID, DATE) {
                var CHOSEN_AMENITIES = $('#choosen_room_amenities').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_show_hotel_pricebook_form.php?type=show_available_amenities',
                    data: {
                        ID: HOT_ID,
                        CHOSEN_AMENITIES: CHOSEN_AMENITIES,
                        DT: DATE,
                    },
                    success: function(response) {
                        $('#showHOTELAMENITIES').html('');
                        $('#showHOTELAMENITIES').html(response);
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'show_available_rooms') :

        $get_selected_DATE = $_POST['DT'];
        $get_selected_HOTEL_ID = $_POST['ID'];

        $formatter_date = trim(date('j', strtotime($get_selected_DATE)));
        $formatter_year = trim(date('Y', strtotime($get_selected_DATE)));
        $formatter_month = trim(date('F', strtotime($get_selected_DATE)));
        $formatted_day = trim('day_' . $formatter_date);

        $formatted_date = date("d-m-Y", strtotime($get_selected_DATE));

        $get_room_type = $_POST['ROOM_TYPE'];
    ?>
        <form method="post" action="" data-parsley-validate>
            <div class="row">
                <?php
                $select_room_details = sqlQUERY_LABEL("SELECT `room_ID`, `room_title`, `total_max_adults`, `total_max_childrens`,`preferred_for`, `breakfast_included`, `lunch_included`,`dinner_included` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' AND `hotel_id` = '$get_selected_HOTEL_ID' and `room_type_id` = '$get_room_type'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_rooms_num_rows_count = sqlNUMOFROW_LABEL($select_room_details);
                if ($total_rooms_num_rows_count > 0) :
                ?>
                    <div class="demo-inline-spacing mt-4 overflow-hidden" id="hotel_pricebook_rooms_list" style="height: 300px;">
                        <div class="list-group">
                            <?php
                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_room_details)) :
                                $counter++;
                                $room_ID = $fetch_data['room_ID'];
                                $room_title = $fetch_data['room_title'];
                                $total_max_adults = $fetch_data['total_max_adults'];
                                $total_max_childrens = $fetch_data['total_max_childrens'];
                                $preferred_for = $fetch_data['preferred_for'];
                                $breakfast_included = $fetch_data['breakfast_included'];
                                $lunch_included = $fetch_data['lunch_included'];
                                $dinner_included = $fetch_data['dinner_included'];
                                $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($get_selected_HOTEL_ID, $room_ID, '', 'get_room_gallery_1st_IMG');
                                $room_rate_for_the_day = getROOM_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $room_ID, $formatter_year, $formatter_month, $formatted_day, 'room_rate_for_the_day');
                                $room_rate_for_the_day_num_rows = getROOM_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $room_ID, $formatter_year, $formatter_month, $formatted_day, 'room_rate_for_the_day_num_rows');

                                if ($room_rate_for_the_day_num_rows) :
                                    $room_rate_for_the_day = $room_rate_for_the_day;
                                else :
                                    $room_rate_for_the_day = 0;
                                endif;

                                if ($get_room_gallery_1st_IMG) :
                                    $image_url = BASEPATH . '/uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                else :
                                    $image_url = BASEPATH . 'assets/img/dummy/no-preview.png';
                                endif;
                                $food_applicable = NULL;
                                if ($breakfast_included == 0 && $lunch_included == 0 && $dinner_included == 0) :
                                    $food_applicable = 'N/A ';
                                else :
                                    if ($breakfast_included == 1) :
                                        $food_applicable .= 'Breakfast,';
                                    endif;
                                    if ($lunch_included == 1) :
                                        $food_applicable .= ' Lunch,';
                                    endif;
                                    if ($dinner_included == 1) :
                                        $food_applicable .= ' Dinner,';
                                    endif;
                                endif;
                                $selected_preferred_for = explode(',', $preferred_for);
                                $show_preferred_for = NULL;

                                foreach ($selected_preferred_for as $preferred_for_arrays) :
                                    $show_preferred_for .= get_HOTEL_PREFERRED_FOR($preferred_for_arrays, 'label') . ', ';
                                endforeach;

                                $show_preferred_for_formatted = substr($show_preferred_for, 0, -2);
                            ?>
                                <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer" id="room_details_<?= $get_selected_HOTEL_ID; ?>_<?= $room_ID; ?>">
                                    <img src=" <?= $image_url; ?>" onclick="showHOTELROOMGALLERY('<?= $room_ID; ?>','<?= $get_selected_HOTEL_ID; ?>')" alt="ROOM_GALLERY" class="rounded me-3 w-px-50">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <div class="user-info">
                                                <h6 class="mb-1 fw-bold"><?= $room_title; ?> <span class="badge bg-label-primary"><?= $show_preferred_for_formatted; ?></span></h6>
                                                <div class="d-flex align-items-center mb-2"><svg version="1.1" id="Capa_1" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M418.203,366.793c-4.819-24.142-22.392-34.034-32.436-37.783c-0.165-0.06-0.331-0.116-0.5-0.166 c-1.947-0.574-56.794-16.752-63.253-18.657l-19.724-13.338v-12.318c31.807-16.629,53.296-49.923,53.296-88.169v-1.385 c16.327-3.017,28.616-15.669,28.616-30.778c0-8.204-3.627-15.683-9.553-21.289V78.208c0-35.92-32.518-63.402-68.113-57.113C258.184-15.147,188.617-3.684,154.88,47.743c-2.272,3.465-1.306,8.115,2.158,10.389c3.464,2.272,8.114,1.305,10.389-2.158c16.828-25.655,45.162-40.97,75.79-40.97c20.885,0,40.51,6.912,56.754,19.987c1.816,1.462,4.212,1.997,6.476,1.446c27.213-6.613,53.2,14.078,53.2,41.771v56.189c-1.501-0.443-3.045-0.806-4.627-1.075c-1.121-4.945-4.053-9.231-6.262-10.956l-31.189-24.283c-7.676-5.975-17.573-6.848-24.162-3.853c-75.566,29.211-120.855,29.211-123.103,29.533c-5.641,0.54-11.2,3.869-12.996,9.552c-1.596,0.27-3.155,0.634-4.669,1.081c0.418-24.19-1.199-33.424,2.163-48.553c0.898-4.044-1.65-8.052-5.695-8.95c-4.045-0.901-8.053,1.65-8.95,5.695c-3.951,17.779-2.007,28.31-2.521,60.319c-5.925,5.606-9.551,13.084-9.551,21.288c0,15.109,12.289,27.761,28.615,30.778v0.078c0,36.642,20.083,71.261,53.028,89.042v12.869l-19.418,13.131c-1.534,0.453-63.58,18.739-64.06,18.915c-10.045,3.748-27.616,13.641-32.437,37.786L71.951,476.572c-0.027,0.136-0.051,0.272-0.07,0.409C68.989,497.375,79.932,512,102.294,512h79.922c4.144,0,7.502-3.358,7.502-7.502s-3.358-7.502-7.502-7.502h-27.03v-70.707c0-4.144-3.358-7.502-7.502-7.502c-4.144,0-7.502,3.358-7.502,7.502v70.707h-37.89c-10.237,0-15.769-3.44-15.847-13.677c-0.042-5.103-0.265-0.021,22.076-113.589c3.359-16.824,15.576-23.852,22.733-26.573c2.554-0.753,56.346-16.621,57.46-16.949l13.753,38.542c2.7,7.563,10.392,11.146,17.275,9.716l23.541,20.653c7.22,6.336,18.161,6.389,25.445,0.031l23.697-20.687c7.074,1.473,14.65-2.316,17.289-9.714l13.737-38.495c7.056,2.081,41.878,12.353,57.305,16.903c7.156,2.722,19.373,9.748,22.732,26.57c22.716,115.472,22.28,108.913,22.059,114.381c-0.359,9.033-5.093,12.887-15.828,12.887h-37.621v-70.706c0-4.144-3.358-7.502-7.502-7.502s-7.502,3.358-7.502,7.502v70.707H217.226c-4.144,0-7.502,3.358-7.502,7.502c0,4.144,3.358,7.502,7.502,7.502H409.72c18.533,0,30.681-10.197,30.851-28.565C440.592,480.52,441.168,483.445,418.203,366.793z M355.586,148.812c18.162,5.398,18.074,25.403,0,30.774V148.812z M156.697,179.586c-18.109-5.382-18.124-25.386,0-30.774V179.586z M171.701,195.054c0-6.718,0-49.168,0-56.334c0.1-0.018,54.944-1.625,127.134-30.501l0.581-0.226c1.456-0.473,5.011-1.124,8.935,1.93l30.755,23.945c2.131,2.605,1.477,0.824,1.477,62.494c0,33.362-19.265,62.267-47.489,75.97c-1.807,0.343-31.623,16.087-66.435,3.058C193.873,262.9,171.701,230.656,171.701,195.054z M287.286,290.858v8.468l-19.134,45.729l-24.054,0.001c-4.687-11.158-20.363-48.49-19.37-46.138v-8.275C245.435,297.655,267.538,297.328,287.286,290.858z M216.713,359.774c-0.21-0.01,0.492,1.429-14.281-39.766l11.58-7.83c4.149,9.918,13.851,33.104,15.838,37.854L216.713,359.774z M258.863,383.847c-1.62,1.416-4.065,1.414-5.682-0.006l-20.014-17.558l8.437-6.225h29.064l8.35,6.195L258.863,383.847z M295.571,359.743c-0.134,0.134,0.103,0.106-13.235-9.711c5.905-14.114,12.642-30.216,15.838-37.854l11.579,7.83L295.571,359.743z" />
                                                                <g></g>
                                                    </svg>
                                                    <small class="ms-3">Total Max Adult - <?= $total_max_adults; ?></small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <svg version="1.1" id="Capa_1" width="18" height="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M494.603,317.884c-1.891-15.498-7.108-28.575-21.387-38.894c-11.315-8.883-26.753-11.327-68.88-21.813v-19.244c0.255-0.127,0.081-0.037,0.353-0.176l0.001,0.001c0.754-0.361,2.882-1.514,4.024-2.206l0.125-0.074c19.038-10.961,32.894-29.918,38.018-48.873c8.823,16.291,6.687,31.552,5.962,44.043c-0.615,10.585,8.93,18.868,19.308,16.718l18.472-3.831c5.512-1.144,10.299-4.591,13.133-9.458c2.829-4.857,3.464-10.707,1.741-16.05c-10.96-34.002-25.162-50.895-32.556-57.879c0.423-4.906-0.553-9.624-2.705-13.865c1.821-3.065,2.999-6.467,3.372-10.072c0.273-0.777,0.428-1.608,0.428-2.479c-1.333-14.029,5.805-48.789-16.431-83.321l-0.014,0.009c-4.243-5.944-13.638-2.939-13.638,4.359c0,2.767,0.932,3.274,3.532,7.947c13.704,24.651,11.254,46.217,11.556,47.856c-3.427-1.872-7.295-3.177-11.437-3.763c-2.79-3.449-7.081-4.824-11.091-4.011c-38.088,7.696-53.302-9.941-58.076-17.882c-2.481-4.128-6.837-6.593-11.649-6.594c-0.001,0-0.001,0-0.002,0c-4.812,0-9.168,2.465-11.65,6.593c-0.001,0-0.001,0.001-0.001,0.001c-4.773,7.939-19.987,25.574-58.076,17.882c-4.065-0.823-8.338,0.608-11.092,4.012c-4.141,0.586-8.009,1.891-11.435,3.763v-3.113c0-76.101,87.016-119.581,148.566-73.062c3.262,2.552,7.956,1.963,10.506-1.298c2.552-3.261,1.959-7.987-1.302-10.539c-71.68-54.698-169.077-2.988-172.663,80.208c-0.174,4.067-0.07,4.642-0.101,30.953c0,0.874,0.157,1.709,0.432,2.489c0.36,3.465,1.462,6.742,3.167,9.713c-2.265,4.297-3.322,9.142-2.885,14.213c-7.394,6.984-21.598,23.878-32.556,57.879c-3.618,11.229,3.345,23.117,14.874,25.508c18.491,3.731,18.913,4.172,21.778,4.172c3.807,0,7.488-1.354,10.445-3.907c6.483-5.598,5.638-12.281,5.295-17.006c-0.797-11.001-1.846-25.612,6.477-40.643c6.813,20.396,16.074,35.174,35.035,47.411c4.241,2.734,7.219,4.17,7.319,4.225v19.389c-47.588,11.846-59.011,12.808-72.93,25.053c-13.817-12.129-24.75-13.067-72.903-25.053v-19.406c0.117-0.069,4.601-2.233,10.097-6.107c37.683-26.584,35.192-67.629,35.35-68.208c20.506-4.468,30.035-25.243,18.887-40.871c0.207-32.639-0.049-59.397-25.915-88.137C159.146-35.456,42.926,8.88,42.926,102.469v20.058c0,0.019,0.003,0.038,0.003,0.057c-2.986,4.184-4.718,9.123-4.718,14.411c0,12.769,10.067,23.511,23.608,26.46c0.221,6.924,0.333,11.85,2.478,20.32c5.933,23.878,22.212,43.524,43.33,54.173v19.227c-43.846,10.915-57.579,12.947-68.879,21.812c-10.951,7.914-17.589,18.806-20.06,30.759c-1.337,6.462-18.355,160.3-18.364,160.403c-2.055,23.748,11.544,41.849,35.018,41.849h65.587c4.14,0,7.497-3.356,7.497-7.497c0-4.141-3.357-7.497-7.497-7.497H72.711l7.434-140.616c0.218-4.134-2.957-7.663-7.091-7.882c-4.144-0.259-7.664,2.956-7.883,7.091l-1.477,27.955H25.099c6.988-60.676,6.927-66.868,9.102-74.046c6.987-23.06,33.171-26.612,33.837-27.024c16.802,26.264,45.911,42.356,77.308,42.356s60.505-16.093,77.308-42.356c1.625,0.47,9.326,1.779,17.377,6.604c0.326,0.195,0.666,0.365,1.016,0.509c1.005,0.667,3.702,2.698,5.235,4.058c-0.894,2.092-5.569,7.204-7.657,24.303l-7.34,65.595h-3.978l-1.477-27.955c-0.219-4.135-3.739-7.337-7.883-7.091c-4.134,0.219-7.309,3.747-7.091,7.882l7.434,140.616h-82.374c-4.14,0-7.497,3.356-7.497,7.497c0,4.141,3.357,7.497,7.497,7.497c10.682,0,171.867,0,182.771,0c4.141,0,7.497-3.356,7.497-7.497c0-4.141-3.356-7.497-7.497-7.497h-24.708l7.434-140.616c0.219-4.134-2.956-7.663-7.091-7.882c-4.116-0.232-7.664,2.956-7.882,7.091l-0.43,8.125l-36.493-8.257c5.306-34.986,0.291-53.09,22.417-66.379c9.096-5.45,17.577-6.472,20.785-7.452c4.172,39.187,36.22,55.984,36.415,56.163l0.104,0.062c3.597,1.82,7.453,4.556,18.395,7.656c10.655,2.831,19.73,2.478,19.535,2.478c14.363,0,28.67-4.497,39.731-11.729l0.104-0.067c0.985-0.718,8.538-5.157,16.297-14.182c10.095-11.767,15.94-25.843,17.495-40.312c3.04,0.931,11.566,2.025,20.511,7.384c22.309,13.399,16.825,31.003,22.418,66.387l-36.201,8.238l-0.429-8.114c-0.211-4-3.52-7.101-7.479-7.101c-0.134,0-0.269,0.003-0.402,0.011c-4.135,0.219-7.31,3.747-7.091,7.882l7.433,140.616h-85.882c-4.141,0-7.497,3.356-7.497,7.497c0,4.141,3.356,7.497,7.497,7.497h122.953c21.992,0,35.463-16.535,35.235-35.796C511.956,469.751,512.348,477.86,494.603,317.884z M466.53,175.05c7.966,9.09,17.351,24.857,24.674,47.576c0.573,1.775-0.021,3.209-0.428,3.904c-0.408,0.703-1.37,1.937-3.22,2.321l-18.472,3.831c-0.725,0.148-1.338-0.439-1.295-1.166c0.066-1.143,0.155-2.359,0.248-3.644c0.819-11.303,2.115-29.221-7.651-47.764C462.687,178.755,464.759,177.048,466.53,175.05z M450.112,122.921c11.749,4.844,11.176,17.011,0,21.62V122.921z M450.112,160.2c2.411-0.521,5.419-1.522,7.905-2.762c0.22,5.16-3.249,9.634-8.042,10.887C449.988,168.103,450.096,161.172,450.112,160.2z M264.064,232.683l-18.471-3.831c-2.829-0.587-4.527-3.496-3.648-6.227c7.322-22.719,16.707-38.485,24.674-47.576c1.77,1.998,3.844,3.705,6.144,5.06C257.814,208.489,269.086,233.721,264.064,232.683z M283.417,122.924v21.617C272.124,139.879,271.807,127.712,283.417,122.924z M275.43,157.254c2.624,1.352,5.82,2.419,8.275,2.948c0.082,7.669,0.05,6.663,0.141,8.21C283.494,168.399,274.815,162.569,275.143,157.254z M62.592,398.547l-5.205,98.457c-17.7-0.958-28.508,2.742-36.446-5.305c-3.366-3.412-5.764-8.633-5.844-15.619c-0.092-5.293,0.218-3.714,8.322-77.534H62.592z M228.512,147.805v-21.617C239.963,130.911,239.964,143.08,228.512,147.805z M61.817,147.806c-11.456-4.724-11.458-16.894,0-21.62V147.806z M64.671,110.037c-2.347,0.309-4.605,0.855-6.751,1.598c0.015-6.963-0.098-11.268,0.256-15.893c4.428-49.342,45.292-81.749,89.521-80.478l0.813,0.032c44.245,1.614,81.846,37.045,83.811,83.41c0.144,8.76,0.06,0.926,0.086,12.928c-1.913-0.663-3.917-1.164-5.993-1.483c-1.438-2.283-3.57-4.118-6.266-5.288c-0.994-0.433-1.248-0.348-3.02-0.92c-5.604-1.813-16.217-7.363-24.984-24.186c-2.254-4.326-6.687-7.004-11.571-6.869c-4.879,0.089-9.209,2.879-11.303,7.28c-7.106,14.948-39.173,41.056-92.928,26.351C72.189,105.386,67.699,106.601,64.671,110.037z M93.946,208.779c-21.033-24.487-17.134-43.531-17.134-86.658c31.074,7.476,54.456,2.219,68.828-3.695c15.509-6.382,28.35-16.71,35.193-28.132c9.357,16.105,21.536,24.919,32.682,28.227c0.001,8.284-0.002,37.721,0.002,38.292c-0.519,2.55,3.991,31.229-20.171,55.185c-21.656,21.023-42.882,19.004-51.43,19.806c-1.121-0.052-2.245-0.132-3.365-0.239c-1.12-0.107-2.236-0.242-3.34-0.402C118.836,228.766,104.153,220.423,93.946,208.779z M176.555,271.008l2.276,0.567c-19.066,17.217-47.905,17.218-66.973,0l2.276-0.567c0.439-0.11,0.868-0.259,1.281-0.445c4.376-1.981,7.205-7.025,7.205-12.851v-13.937c13.715,4,29.844,4.284,45.451-0.101v14.037C168.071,264.821,172.199,269.914,176.555,271.008z M83.56,278.619l11.579-2.883c25.945,31.564,74.315,31.751,100.413,0l11.579,2.883C176.488,320.152,114.304,320.29,83.56,278.619z M285.205,378.914l-6.243,118.091c-22.169-0.124-31.785,1.936-38.515-7.415c-3.768-5.265-4.462-11.625-3.919-18.059c1.206-10.773,10.102-90.271,11.311-101.071L285.205,378.914z M404.59,237.808l-0.252-0.518v-0.232l0.353,0.697L404.59,237.808z M314.882,208.026c-20.598-23.973-16.47-46.49-16.468-89.753c15.497,2.388,50.112,4.043,68.35-23.716c18.24,27.76,52.858,26.1,68.353,23.716c0,9.226,0,35.986,0,45.255C435.118,225.87,357.396,256.175,314.882,208.026z M366.606,308.38c-13.829-0.277-20.547-7.428-21.275-7.743c-3.21-2.84-4.051-3.991-3.961-3.891c-4.177-4.907-6.444-9.997-7.559-15.701c-0.686-3.407-0.568-6.558-0.576-9.496c1.855-0.488,2.481-0.546,3.453-0.987c4.375-1.98,7.203-7.025,7.203-12.85v-14.028c14.609,4.143,30.416,4.323,45.451,0.083v13.945c0,7.109,4.129,12.205,8.486,13.297l2.463,0.613c-0.267,1.094,1.887,14.533-8.974,26.064c-2.445,2.609-4.686,4.326-7.671,6.139c-8.785,5.124-17.312,4.574-16.484,4.554C367.081,308.379,366.68,308.38,366.606,308.38z M392.011,327.4c-0.498,0.065-15.814,8.586-36.763,4.496c-26.347-5.226-45.953-27.459-47.805-53.927c2.329-0.58,8.255-2.055,10.803-2.689c0.496,47.763,63.02,66.227,89.203,26.031c2.133-3.964,7.654-12.022,7.836-25.959l10.799,2.688C424.934,296.149,413.773,317.161,392.011,327.4z M491.022,491.7c-7.949,8.056-18.751,4.346-36.454,5.305l-6.244-118.099l37.075-8.437c12.252,111.011,11.316,97.894,11.464,105.925C496.916,480.891,495.407,487.256,491.022,491.7z" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                    <small class="ms-3">Total Max Children - <?= $total_max_childrens; ?></small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <svg version="1.1" id="Capa_1" width="18" height="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M256,114c-78.299,0-142,63.701-142,142s63.701,142,142,142s142-63.701,142-142S334.299,114,256,114z M256,378
			c-67.271,0-122-54.729-122-122s54.729-122,122-122s122,54.729,122,122S323.271,378,256,378z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M506.691,1.168c-3.267-1.734-7.225-1.525-10.29,0.545C447.309,34.884,418,90.06,418,149.309v129.752
			c0,13.917,5.42,27.001,15.26,36.842l18.74,18.74V482c0,16.542,13.458,30,30,30s30-13.458,30-30V10
			C512,6.301,509.958,2.904,506.691,1.168z M492,482c0,5.514-4.486,10-10,10c-5.514,0-10-4.486-10-10V330.5
			c0-2.652-1.054-5.195-2.929-7.071l-21.669-21.669c-6.063-6.063-9.402-14.124-9.402-22.699V149.309
			c0-45.98,19.881-89.194,54-119.012V482z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M233.058,176.693c-2.168-5.079-8.046-7.438-13.124-5.271c-33.933,14.486-55.859,47.685-55.859,84.578
			c0,5.522,4.477,10,10,10s10-4.478,10-10c0-28.869,17.158-54.848,43.712-66.183C232.866,187.649,235.226,181.773,233.058,176.693z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M256,164.075l-0.505,0.001c-5.523,0.037-9.97,4.544-9.933,10.067c0.037,5.5,4.507,9.933,9.999,9.933
			c0.022,0,0.046,0,0.068,0l0.371-0.001c5.523,0,10-4.477,10-10C266,168.552,261.523,164.075,256,164.075z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M394.623,124.479c-17.668-18.616-38.494-33.264-61.899-43.536C308.488,70.307,282.674,64.914,256,64.914
			c-30.48,0-59.616,6.966-86.598,20.706c-4.921,2.506-6.88,8.527-4.374,13.449c2.507,4.92,8.528,6.878,13.45,4.373
			C202.622,91.148,228.704,84.914,256,84.914c47.396,0,91.475,18.94,124.116,53.333c1.966,2.072,4.608,3.116,7.255,3.116
			c2.472,0,4.948-0.911,6.882-2.747C398.259,134.814,398.424,128.485,394.623,124.479z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M404.331,360.379c-4.333-3.424-10.621-2.689-14.046,1.643c-32.693,41.35-81.638,65.064-134.285,65.064
			c-32.95,0-64.937-9.384-92.503-27.138c-4.642-2.991-10.832-1.65-13.822,2.992c-2.99,4.644-1.65,10.832,2.993,13.822
			C183.47,436.6,219.202,447.085,256,447.085c58.8,0,113.463-26.483,149.973-72.66C409.398,370.094,408.664,363.805,404.331,360.379
			z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M135.958,377.924l-0.147-0.169c-0.053-0.065-0.106-0.131-0.161-0.195c-3.557-4.226-9.865-4.766-14.09-1.21
			c-4.225,3.558-4.767,9.865-1.209,14.091l0.097,0.115l0.005-0.004c0.106,0.134,0.216,0.268,0.33,0.4
			c1.978,2.304,4.776,3.486,7.592,3.486c2.306,0,4.623-0.793,6.509-2.412C139.074,388.428,139.555,382.115,135.958,377.924z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M124,0c-5.523,0-10,4.477-10,10v108H95.992c0.001-0.056,0.008-0.11,0.008-0.167V10c0-5.523-4.477-10-10-10S76,4.477,76,10
			v107.833c0,0.056,0.008,0.111,0.008,0.167H57.992c0.001-0.056,0.008-0.11,0.008-0.167V10c0-5.523-4.477-10-10-10S38,4.477,38,10
			v107.833c0,0.056,0.008,0.111,0.008,0.167H20V10c0-5.523-4.477-10-10-10S0,4.477,0,10v122c0,24.53,14.884,46.669,37,56.239V482
			c0,16.542,13.458,30,30,30s30-13.458,30-30V188.239c22.116-9.57,37-31.709,37-56.239V10C134,4.477,129.523,0,124,0z
			 M84.224,171.62c-4.278,1.236-7.224,5.153-7.224,9.607V482c0,5.514-4.486,10-10,10s-10-4.486-10-10V181.227
			c0-4.454-2.945-8.371-7.224-9.607c-15.583-4.503-26.981-17.879-29.324-33.62h93.095C111.205,153.741,99.807,167.117,84.224,171.62
			z" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                    <small class="ms-3">Food Applicable? - <?= substr($food_applicable, 0, -1); ?></small>
                                                </div>
                                            </div>
                                            <div class="add-btn text-end">
                                                <h5 class="fw-bold text-end"><?= $global_currency_format . ' ' . number_format($room_rate_for_the_day, 2); ?></h5>
                                                <button type="button" id="edit_room_btn_<?= $get_selected_HOTEL_ID; ?>_<?= $room_ID; ?>" onclick="edit_ROOM_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $room_ID; ?>')" class="btn btn-primary btn-sm waves-effect waves-light"> <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg> Edit</button>

                                                <div class="row d-none" id="update_room_btn_<?= $get_selected_HOTEL_ID; ?>_<?= $room_ID; ?>">
                                                    <div class="col-md-3">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" id="room_price_rate_<?= $get_selected_HOTEL_ID; ?>_<?= $room_ID; ?>" data-parsley-trigger="keyup" name="room_price_rate" data-parsley-type="number" placeholder="Price" value="<?= $room_rate_for_the_day; ?>" required>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-center">
                                                        <button type="button" onclick="update_ROOM_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $room_ID; ?>','<?= $formatter_year; ?>', '<?= $formatter_month; ?>','<?= $formatted_day; ?>')" class="btn btn-success btn-sm">Update</button>
                                                    </div>
                                                    <div class="col-md-2 ms-3 d-flex align-items-center">
                                                        <button type="button" onclick="cancel_ROOM_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $room_ID; ?>')" class="btn btn-secondary btn-sm">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="d-block justify-content-center d-xl-flex d-lg-flex d-md-flex d-sm-flex">
                        <div class="row p-5 mt-4">
                            <svg id="no_questions_found" xmlns="http://www.w3.org/2000/svg" width="67.116" height="80" viewBox="0 0 67.116 80">
                                <path id="Path_210" data-name="Path 210" d="M31.194,70.338a.987.987,0,0,1-.645-.239c-.6-.512-1.182-1.054-1.739-1.61a30.8,30.8,0,0,1-2.3-2.569.992.992,0,0,1,1.55-1.237,28.8,28.8,0,0,0,2.148,2.4c.521.521,1.069,1.028,1.627,1.507a.992.992,0,0,1-.646,1.745Z" transform="translate(-26.297 -9.612)" fill="#ADB5BD"></path>
                                <path id="Path_211" data-name="Path 211" d="M50.19,77.288A30.458,30.458,0,0,1,36.6,74.1a.992.992,0,0,1,.886-1.774,28.536,28.536,0,0,0,18.77,2.327.992.992,0,0,1,.423,1.938,30.455,30.455,0,0,1-6.491.7Z" transform="translate(-26.175 -9.426)" fill="#ADB5BD"></path>
                                <g id="Group_271" data-name="Group 271" transform="translate(23.27 0)">
                                    <g id="Group_270" data-name="Group 270">
                                        <g id="Group_269" data-name="Group 269">
                                            <path id="Path_212" data-name="Path 212" d="M50.012,19.463a.992.992,0,0,1-.992-.992V11.918a.992.992,0,1,1,1.983,0v6.554A.992.992,0,0,1,50.012,19.463Z" transform="translate(-49.021 -10.926)" fill="#ADB5BD"></path>
                                        </g>
                                    </g>
                                </g>
                                <g id="Group_274" data-name="Group 274" transform="translate(6.661 3.955)">
                                    <g id="Group_273" data-name="Group 273">
                                        <g id="Group_272" data-name="Group 272">
                                            <path id="Path_213" data-name="Path 213" d="M36.865,22.645a.991.991,0,0,1-.883-.538l-3-5.829a.991.991,0,0,1,1.763-.906l3,5.829a.992.992,0,0,1-.881,1.445Z" transform="translate(-32.876 -14.833)" fill="#ADB5BD"></path>
                                        </g>
                                    </g>
                                </g>
                                <g id="Group_277" data-name="Group 277" transform="translate(36.792 3.956)">
                                    <g id="Group_276" data-name="Group 276">
                                        <g id="Group_275" data-name="Group 275">
                                            <path id="Path_214" data-name="Path 214" d="M63.2,22.645a.992.992,0,0,1-.881-1.445l3-5.829a.991.991,0,0,1,1.763.906l-3,5.829a.991.991,0,0,1-.883.538Z" transform="translate(-62.205 -14.833)" fill="#ADB5BD"></path>
                                        </g>
                                    </g>
                                </g>
                                <path id="Path_215" data-name="Path 215" d="M50.092,54.2A1.487,1.487,0,0,1,48.6,52.711V50.837a5.291,5.291,0,0,1,3.235-4.851,4.419,4.419,0,0,0,2.693-4.262,4.513,4.513,0,0,0-4.266-4.266A4.443,4.443,0,0,0,45.648,41.9a1.487,1.487,0,0,1-2.975,0,7.419,7.419,0,0,1,7.707-7.414A7.42,7.42,0,0,1,53.01,48.72a2.319,2.319,0,0,0-1.431,2.116v1.874A1.487,1.487,0,0,1,50.092,54.2Z" transform="translate(-25.856 -10.28)" fill="#ADB5BD"></path>
                                <g id="Group_279" data-name="Group 279" transform="translate(22.006 45.664)">
                                    <g id="Group_278" data-name="Group 278">
                                        <path id="Path_216" data-name="Path 216" d="M52.279,57.754A2.251,2.251,0,1,1,50.028,55.5,2.256,2.256,0,0,1,52.279,57.754Z" transform="translate(-47.777 -55.503)" fill="#ADB5BD"></path>
                                    </g>
                                </g>
                                <path id="Path_225" data-name="Path 225" d="M92.227,79.344,81.758,69,75.632,75.07a1.033,1.033,0,0,1-.712.288,1.051,1.051,0,0,1-.712-.288.992.992,0,0,1,0-1.408L80.345,67.6l-.983-.972a4.051,4.051,0,0,0-5.676,0l-.13.129-4.883-4.829A22.836,22.836,0,0,0,67,31.229a23.462,23.462,0,0,0-32.911,0,22.838,22.838,0,0,0,0,32.546,23.476,23.476,0,0,0,31.045,1.656l4.884,4.829-.13.129a3.943,3.943,0,0,0,0,5.613L82.751,88.715a4.02,4.02,0,0,0,5.666,0l3.81-3.758a3.943,3.943,0,0,0,0-5.613ZM37.807,60.1a17.672,17.672,0,0,1,0-25.2,18.161,18.161,0,0,1,25.47,0,17.672,17.672,0,0,1,0,25.2A18.142,18.142,0,0,1,37.807,60.1Z" transform="translate(-26.285 -9.882)" fill="#ADB5BD"></path>
                            </svg>
                            <span class="col-12 text-center mt-4" style="color:#232D42;">No Rooms found</span>
                            <span class=" text-center mt-1" style="color:#8A92A6;">Modify the filters to get possible results.</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                var e = document.getElementById("hotel_pricebook_rooms_list");

                if (e && e.scrollHeight > e.clientHeight) {
                    new PerfectScrollbar(e, {
                        wheelPropagation: false,
                        suppressScrollX: true, // Disable horizontal scrollbar
                    });
                }
            });

            function showHOTELROOMGALLERY(ROOM_ID, HOT_ID) {
                $('.receiving-swiper-room-form-data').load('engine/ajax/__ajax_add_hotel_form.php?type=show_hotel_room_gallery&ID=' + ROOM_ID + '&HOT_ID=' + HOT_ID, function() {
                    const container = document.getElementById("showSWIPERGALLERYMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function edit_ROOM_PRICE(HOTEL_ID, ROOM_ID) {
                $('#edit_room_btn_' + HOTEL_ID + '_' + ROOM_ID).addClass('d-none');
                $('#update_room_btn_' + HOTEL_ID + '_' + ROOM_ID).removeClass('d-none');
                $('#room_price_rate_' + HOTEL_ID + '_' + ROOM_ID).focus();
                $('#room_price_rate_' + HOTEL_ID + '_' + ROOM_ID).select();
            }

            function cancel_ROOM_PRICE(HOTEL_ID, ROOM_ID) {
                $('#edit_room_btn_' + HOTEL_ID + '_' + ROOM_ID).removeClass('d-none');
                $('#update_room_btn_' + HOTEL_ID + '_' + ROOM_ID).addClass('d-none');
            }

            function update_ROOM_PRICE(HOT_ID, ROOM_ID, RATE_Y, RATE_M, RATE_D) {
                var CHOSEN_ROOM_PRICE = $('#room_price_rate_' + HOT_ID + '_' + ROOM_ID).val();
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotel.php?type=update_room_price",
                    data: {
                        _HOT_ID: HOT_ID,
                        _ROOM_ID: ROOM_ID,
                        _RATE_Y: RATE_Y,
                        _RATE_M: RATE_M,
                        _RATE_D: RATE_D,
                        _ROOM_RATE: CHOSEN_ROOM_PRICE
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result_success == true) {
                            $('#edit_room_btn_' + HOT_ID + '_' + ROOM_ID).removeClass('d-none');
                            $('#update_room_btn_' + HOT_ID + '_' + ROOM_ID).addClass('d-none');
                            TOAST_NOTIFICATION('success', 'Price Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            showHOTELPRICEBOOKROOMS('<?= $get_selected_HOTEL_ID; ?>', '<?= $get_selected_DATE; ?>');

                            filter_calendar('<?= $get_selected_HOTEL_ID; ?>', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update the price', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            function showHOTELPRICEBOOKROOMS(HOT_ID, DATE) {
                var CHOSE_ROOM_TYPE = $('#choosen_room_type').val();
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_show_hotel_pricebook_form.php?type=show_available_rooms',
                    data: {
                        ID: HOT_ID,
                        ROOM_TYPE: CHOSE_ROOM_TYPE,
                        DT: DATE,
                    },
                    success: function(response) {
                        $('#showHOTELROOMS').html('');
                        $('#showHOTELROOMS').html(response);
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'show_available_amenities') :

        $get_selected_DATE = $_POST['DT'];
        $get_selected_HOTEL_ID = $_POST['ID'];

        $formatter_date = trim(date('j', strtotime($get_selected_DATE)));
        $formatter_year = trim(date('Y', strtotime($get_selected_DATE)));
        $formatter_month = trim(date('F', strtotime($get_selected_DATE)));
        $formatted_day = trim('day_' . $formatter_date);

        $formatted_date = date("d-m-Y", strtotime($get_selected_DATE));

        $get_amenities_ID = $_POST['CHOSEN_AMENITIES'];
    ?>
        <form method="post" action="" data-parsley-validate>
            <div class="row mt-5">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50%">Amenities Details</th>
                                <th width="15%">Day Price</th>
                                <th width="15%">Hour Price</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php
                            $select_room_amenities_details = sqlQUERY_LABEL("SELECT `hotel_amenities_id`, `amenities_title`, `amenities_code`, `quantity`, `availability_type`, `start_time`, `end_time`, `status` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' AND `hotel_id` = '$get_selected_HOTEL_ID' and `hotel_amenities_id` = '$get_amenities_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_rooms_amenities_num_rows_count = sqlNUMOFROW_LABEL($select_room_amenities_details);
                            if ($total_rooms_amenities_num_rows_count > 0) :
                                while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($select_room_amenities_details)) :
                                    $counter++;
                                    $hotel_amenities_id = $fetch_amenities_data['hotel_amenities_id'];
                                    $amenities_title = $fetch_amenities_data['amenities_title'];
                                    $amenities_code = $fetch_amenities_data['amenities_code'];
                                    $quantity = $fetch_amenities_data['quantity'];
                                    $availability_type = $fetch_amenities_data['availability_type'];
                                    $start_time = $fetch_amenities_data['start_time'];
                                    $end_time = $fetch_amenities_data['end_time'];
                                    $status = $fetch_amenities_data['status'];
                                    $availability_type_label = get_AMENITIES_AVILABILITY_TYPE($availability_type, 'label');
                                    if ($status == 1) :
                                        $status_label = 'Active';
                                        $status_label_class = 'success';
                                    else :
                                        $status_label = 'In-Active';
                                        $status_label_class = 'danger';
                                    endif;
                                    if ($availability_type == 1) :
                                        $start_time = '--';
                                        $end_time = '--';
                                    else :
                                        $start_time = date('h:i A', strtotime($start_time));
                                        $end_time = date('h:i A', strtotime($end_time));
                                    endif;

                                    $amenities_rate_for_the_day = getAMENITIES_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $hotel_amenities_id, $formatter_year, $formatter_month, $formatted_day, 'amenities_rate_for_the_day');
                                    $amenities_rate_for_the_hour = getAMENITIES_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $hotel_amenities_id, $formatter_year, $formatter_month, $formatted_day, 'amenities_rate_for_the_hour');
                                    $amenities_rate_for_the_day_num_rows = getAMENITIES_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $hotel_amenities_id, $formatter_year, $formatter_month, $formatted_day, 'amenities_rate_for_the_day_num_rows');
                                    $amenities_rate_for_the_hour_num_rows = getAMENITIES_PRICEBOOK_DETAILS($get_selected_HOTEL_ID, $hotel_amenities_id, $formatter_year, $formatter_month, $formatted_day, 'amenities_rate_for_the_hour_num_rows');
                                    if ($amenities_rate_for_the_day_num_rows) :
                                        $amenities_rate_for_the_day = $amenities_rate_for_the_day;
                                    else :
                                        $amenities_rate_for_the_day = 0;
                                    endif;
                                    if ($amenities_rate_for_the_hour_num_rows) :
                                        $amenities_rate_for_the_hour = $amenities_rate_for_the_hour;
                                    else :
                                        $amenities_rate_for_the_hour = 0;
                                    endif;
                            ?>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span>Name</span>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <span>:</span>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <span class="fw-bold"><small><?= $amenities_code; ?></small> - <?= $amenities_title; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span>Availability Type</span>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <span>:</span>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <span class="fw-bold"><?= $availability_type_label; ?></span> | <span class="fw-bold text-<?= $status_label_class; ?> me-1"><?= $status_label; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span>Start Time</span>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <span>:</span>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <span class="fw-bold"><?= $start_time; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span>End Time</span>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <span>:</span>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <span class="fw-bold"><?= $end_time; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold"><span id="day_price_label"><?= $global_currency_format . ' ' . number_format($amenities_rate_for_the_day, 2); ?></span>
                                            <div class="d-none" id="amenities_rate_for_the_day_div_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>"><input type="text" class="form-control" id="amenities_rate_for_the_day_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>" data-parsley-trigger="keyup" data-parsley-type="number" value="<?= $amenities_rate_for_the_day; ?>"></div>
                                        </td>
                                        <td class="fw-bold"><span id="hour_price_label"><?= $global_currency_format . ' ' . number_format($amenities_rate_for_the_hour, 2); ?></span>
                                            <div class="d-none" id="amenities_rate_for_the_hour_div_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>"><input type="text" class="form-control" id="amenities_rate_for_the_hour_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>" data-parsley-trigger="keyup" data-parsley-type="number" value="<?= $amenities_rate_for_the_hour; ?>"></div>
                                        </td>
                                        <td><button type="button" id="edit_amenities_btn_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>" onclick="edit_AMENITIES_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $hotel_amenities_id; ?>')" class="btn btn-primary btn-sm waves-effect waves-light"> <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg> Edit</button>
                                            <div class="row d-none" id="update_amenities_btn_<?= $get_selected_HOTEL_ID; ?>_<?= $hotel_amenities_id; ?>">
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <button type="button" onclick="update_AMENITIES_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $hotel_amenities_id; ?>','<?= $formatter_year; ?>', '<?= $formatter_month; ?>','<?= $formatted_day; ?>')" class="btn btn-success btn-sm">Update</button>
                                                </div>
                                                <div class="col-md-4 ms-4 d-flex align-items-center">
                                                    <button type="button" onclick="cancel_AMENITIES_PRICE('<?= $get_selected_HOTEL_ID; ?>','<?= $hotel_amenities_id; ?>')" class="btn btn-secondary btn-sm">Cancel</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else :
                                ?>
                                <tr>
                                    <td class="text-center" colspan="6">No more Amenities found !!!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            function edit_AMENITIES_PRICE(HOTEL_ID, AMENITIES_ID) {
                $('#day_price_label').addClass('d-none');
                $('#hour_price_label').addClass('d-none');
                $('#edit_amenities_btn_' + HOTEL_ID + '_' + AMENITIES_ID).addClass('d-none');
                $('#update_amenities_btn_' + HOTEL_ID + '_' + AMENITIES_ID).removeClass('d-none');
                $('#amenities_rate_for_the_day_div_' + HOTEL_ID + '_' + AMENITIES_ID).removeClass('d-none');
                $('#amenities_rate_for_the_hour_div_' + HOTEL_ID + '_' + AMENITIES_ID).removeClass('d-none');
                $('#amenities_rate_for_the_day_' + HOTEL_ID + '_' + AMENITIES_ID).focus();
                $('#amenities_rate_for_the_day_' + HOTEL_ID + '_' + AMENITIES_ID).select();
            }

            function cancel_AMENITIES_PRICE(HOTEL_ID, AMENITIES_ID) {
                $('#day_price_label').removeClass('d-none');
                $('#hour_price_label').removeClass('d-none');
                $('#edit_amenities_btn_' + HOTEL_ID + '_' + AMENITIES_ID).removeClass('d-none');
                $('#update_amenities_btn_' + HOTEL_ID + '_' + AMENITIES_ID).addClass('d-none');
                $('#amenities_rate_for_the_day_div_' + HOTEL_ID + '_' + AMENITIES_ID).addClass('d-none');
                $('#amenities_rate_for_the_hour_div_' + HOTEL_ID + '_' + AMENITIES_ID).addClass('d-none');
            }

            function update_AMENITIES_PRICE(HOT_ID, AMENITIES_ID, RATE_Y, RATE_M, RATE_D, ) {
                var CHOSEN_AMENITIES_DAY_PRICE = $('#amenities_rate_for_the_day_' + HOT_ID + '_' + AMENITIES_ID).val();
                var CHOSEN_AMENITIES_HOUR_PRICE = $('#amenities_rate_for_the_hour_' + HOT_ID + '_' + AMENITIES_ID).val();
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotel.php?type=update_amenities_price",
                    data: {
                        _HOT_ID: HOT_ID,
                        _AMENITIES_ID: AMENITIES_ID,
                        _RATE_Y: RATE_Y,
                        _RATE_M: RATE_M,
                        _RATE_D: RATE_D,
                        _AMENITIE_DAY_RATE: CHOSEN_AMENITIES_DAY_PRICE,
                        _AMENITIE_HOUR_RATE: CHOSEN_AMENITIES_HOUR_PRICE
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result_success == true) {
                            $('#edit_amenities_btn_' + HOT_ID + '_' + AMENITIES_ID).removeClass('d-none');
                            $('#update_amenities_btn_' + HOT_ID + '_' + AMENITIES_ID).addClass('d-none');
                            TOAST_NOTIFICATION('success', 'Price Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            showHOTELPRICEBOOKAMENITIES('<?= $get_selected_HOTEL_ID; ?>', '<?= $get_selected_DATE; ?>');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update the price', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>