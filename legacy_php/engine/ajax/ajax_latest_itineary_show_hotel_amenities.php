<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $hotel_ID = $_GET['hotel_ID'];
        $itinerary_route_date = $_GET['itinerary_route_date'];
        $itinerary_plan_id = $_GET['itinerary_plan_id'];
        $itinerary_route_id = $_GET['itinerary_route_id'];
        $group_type = $_GET['group_type'];
        $year = date('Y', strtotime($itinerary_route_date));
        $month = date('F', strtotime($itinerary_route_date));
        $day = 'day_' . date('j', strtotime($itinerary_route_date));
?>
        <div class="modal-body p-0" style="max-height: 500px; overflow-y: auto;">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="right: 30px; top: 30px;"></button>
            <div class="p-4">
                <div class="col-lg-12 py-3">
                    <div class="col-lg-12">
                        <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Amenities Details</strong></h5>
                    </div>
                </div>
                <?php
                $select_hotel_amenities_details = sqlQUERY_LABEL("SELECT HOTEL_AMENITIES.`hotel_amenities_id`, HOTEL_AMENITIES.`amenities_title`, COALESCE(SUM(ITINEARY_ROOM_AMENITIES.`total_qty`), 0) AS total_qty FROM `dvi_hotel_amenities` HOTEL_AMENITIES LEFT JOIN `dvi_itinerary_plan_hotel_room_amenities` ITINEARY_ROOM_AMENITIES ON ITINEARY_ROOM_AMENITIES.`hotel_amenities_id` = HOTEL_AMENITIES.`hotel_amenities_id` AND ITINEARY_ROOM_AMENITIES.`itinerary_route_id` = '$itinerary_route_id' AND ITINEARY_ROOM_AMENITIES.`itinerary_plan_id` = '$itinerary_plan_id' AND ITINEARY_ROOM_AMENITIES.`group_type` = '$group_type' WHERE HOTEL_AMENITIES.`hotel_id` = '$hotel_ID' AND HOTEL_AMENITIES.`status` = '1' AND HOTEL_AMENITIES.`deleted` = '0' GROUP BY HOTEL_AMENITIES.`hotel_amenities_id`, HOTEL_AMENITIES.`amenities_title`") or die("#1-UNABLE_TO_COLLECT_HOTEL_AMENITIES_DETAILS_LIST:" . sqlERROR_LABEL());
                $total_num_of_amentites_count = sqlNUMOFROW_LABEL($select_hotel_amenities_details);
                if ($total_num_of_amentites_count > 0) :
                ?>
                    <div class="row" style="max-height: 500px; overflow-y: auto;">
                        <?php
                        while ($row = sqlFETCHARRAY_LABEL($select_hotel_amenities_details)) :
                            $hotel_amenities_id = $row['hotel_amenities_id'];
                            $amenities_title = $row['amenities_title'];
                            $total_qty = $row['total_qty'];
                            $amenities_rate_for_the_day = getAMENITIES_PRICEBOOK_DETAILS($hotel_ID, $hotel_amenities_id, $year, $month, $day, 'amenities_rate_for_the_day');
                            if ($total_qty > 0) :
                                $highlight_card_class = 'highlight-card';
                                $total_qty = $total_qty;
                                $add_btn_class = 'd-none';
                                $add_incrementor_class = '';
                            else :
                                $highlight_card_class = '';
                                $total_qty = 0;
                                $add_btn_class = '';
                                $add_incrementor_class = 'd-none';
                            endif;
                        ?>
                            <div class="col-lg-3 d-flex px-2">
                                <div class="card mb-3 w-100 <?= $highlight_card_class; ?>" id="<?= $hotel_amenities_id; ?>">
                                    <div class="row g-0">
                                        <div class="card-body">
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0 cursor-pointer" data-toggle="tooltip" placement="top" title="<?= $amenities_title; ?>"><?= limit_words($amenities_title, 2); ?></h5>
                                                <i class="fa fa-check-circle text-success added-icon <?= $add_incrementor_class; ?>" aria-hidden="true"></i>
                                            </div>
                                            <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                                                <h5 class="card-text text-primary mb-0"><?= general_currency_symbol . ' ' . number_format($amenities_rate_for_the_day, 2); ?></h5>
                                                <?php if ($amenities_rate_for_the_day > 0) : ?>
                                                    <div>
                                                        <button class="btn btn-sm btn-outline-primary add-button <?= $add_btn_class; ?>" type="button" onclick="incrementQuantity('<?= $hotel_amenities_id; ?>','<?= $amenities_rate_for_the_day; ?>')"><span class="tf-icons ti ti-circle-plus ti-xs me-1"></span>Add</button>
                                                        <div class="quantity-controls <?= $add_incrementor_class; ?> btn-group" role="group">
                                                            <button class="btn btn-outline-danger" type="button" onclick="decrementQuantity('<?= $hotel_amenities_id; ?>','<?= $amenities_rate_for_the_day; ?>')"><i class="ti ti-minus ti-xs"></i></button>
                                                            <span readonly class="btn btn-outline-secondary quantity-text"><?= $total_qty; ?></span>
                                                            <button class="btn btn-outline-primary" type="button" onclick="incrementQuantity('<?= $hotel_amenities_id; ?>','<?= $amenities_rate_for_the_day; ?>')"><i class="ti ti-plus ti-xs"></i></button>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <div>
                                                        <button class="btn btn-sm btn-outline-danger add-button" type="button"></span>Sold Out</button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
                <?php else : ?>
                    <div class="col-lg-12 text-center" style="max-height: 600px;">
                        <img src="assets/img/no_amentites_found_1.webp" alt="No Amenities" class="img-fluid mb-3" style="max-height: 300px;">
                        <h5 class="text-muted">No more amenities found.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .quantity-controls .btn {
                padding: 0.2rem 0.5rem;
                /* Reduced padding */
                font-size: 0.875rem;
                /* Smaller font size */
            }

            .quantity-controls .quantity-text {
                width: 2rem;
                /* Reduced width */
                text-align: center;
                border-left: none;
                border-right: none;
                font-size: 0.875rem;
                /* Smaller font size */
            }

            .selected-hotelAmenitiesDetails-card {
                background-color: #d4edda !important;
                /* Green background for selected cards */
            }

            .highlight-card {
                border: 2px solid #28a745 !important;
                /* Green border to highlight the card */
                box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.2) !important;
                /* Slight shadow to emphasize the card */
            }

            .added-icon {
                font-size: 1.25rem;
                display: none;
                /* Initially hidden */
            }

            .card {
                background-clip: padding-box;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                border: none;
                border-radius: 0.25rem;
            }
        </style>

        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <script>
            $(document).ready(function() {
                $('body').tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                });
            });

            function incrementQuantity(hotel_amenities_id, amenitie_rate) {
                const card = document.getElementById(hotel_amenities_id);
                if (card) {
                    const addButton = card.querySelector('.add-button');
                    const quantityControls = card.querySelector('.quantity-controls');
                    const quantityText = card.querySelector('.quantity-text');
                    const addedIcon = card.querySelector('.added-icon');

                    let quantity = parseInt(quantityText.textContent, 10);
                    quantity += 1;
                    quantityText.textContent = quantity;
                    /* console.log(`Incrementing quantity for ${cardId}: ${quantity}`); */

                    addHOTELAMNITIESTOITINEARY('<?= $group_type; ?>', '<?= $hotel_ID; ?>', '<?= $itinerary_plan_id; ?>', '<?= $itinerary_route_id; ?>', '<?= $itinerary_route_date; ?>', 1, hotel_amenities_id, amenitie_rate);

                    if (quantity > 0) {
                        addButton.classList.add('d-none');
                        quantityControls.classList.remove('d-none');
                        card.classList.add('highlight-card'); // Highlight the card
                        addedIcon.classList.remove('d-none'); // Show the added icon
                    }
                }
            }

            function decrementQuantity(hotel_amenities_id, amenitie_rate) {
                const card = document.getElementById(hotel_amenities_id);
                if (card) {
                    const addButton = card.querySelector('.add-button');
                    const quantityControls = card.querySelector('.quantity-controls');
                    const quantityText = card.querySelector('.quantity-text');
                    const addedIcon = card.querySelector('.added-icon');

                    let quantity = parseInt(quantityText.textContent, 10);
                    if (quantity > 0) {
                        quantity -= 1;
                        quantityText.textContent = quantity;
                        /* console.log(`Decrementing quantity for ${cardId}: ${quantity}`); */
                    }

                    addHOTELAMNITIESTOITINEARY('<?= $group_type; ?>', '<?= $hotel_ID; ?>', '<?= $itinerary_plan_id; ?>', '<?= $itinerary_route_id; ?>', '<?= $itinerary_route_date; ?>', 0, hotel_amenities_id, amenitie_rate);

                    if (quantity === 0) {
                        addButton.classList.remove('d-none');
                        quantityControls.classList.add('d-none');
                        card.classList.remove('highlight-card'); // Remove highlight from the card
                        addedIcon.classList.add('d-none'); // Hide the added icon
                    }
                }
            }

            function addHOTELAMNITIESTOITINEARY(group_type, hotel_ID, itinerary_plan_id, itinerary_route_id, itinerary_route_date, quantity, hotel_amenities_id, amenitie_rate) {
                // Perform AJAX call only if the checkbox is checked
                $.ajax({
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=add_amenities_to_itinerary',
                    method: 'POST',
                    data: {
                        hotel_ID: hotel_ID,
                        itinerary_plan_id: itinerary_plan_id,
                        itinerary_route_id: itinerary_route_id,
                        group_type: group_type,
                        itinerary_route_date: itinerary_route_date,
                        quantity: quantity,
                        hotel_amenities_id: hotel_amenities_id,
                        amenitie_rate: amenitie_rate
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Unable to add the Amenities !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            if (response.i_result) {
                                TOAST_NOTIFICATION('success', response.i_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result) {
                                TOAST_NOTIFICATION('success', response.u_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.r_result) {
                                TOAST_NOTIFICATION('success', response.r_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                            showDAYWISE_HOTEL_DETAILS(itinerary_plan_id, group_type);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Error updating database:', error);
                    }
                });
            }

            function showDAYWISE_HOTEL_DETAILS(itinerary_plan_ID, group_type) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_hotel_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                        _group_type: group_type
                    },
                    success: function(response) {
                        $('#showHOTELINFO').html('');
                        $('#showHOTELINFO').html(response);
                    }
                });
            }
        </script>
<?php
    endif;

else :
    echo "Request Ignored";
endif;
