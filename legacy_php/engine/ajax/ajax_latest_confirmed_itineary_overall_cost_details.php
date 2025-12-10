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

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
        $total_guest_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_person_count');
        $TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES = getITINEARY_CONFIRMED_TOTAL_GUIDE_CHARGES_DETAILS($itinerary_plan_ID, '', 'TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES');
        $_groupTYPE = getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, '', 'GROUP_TYPE');

        $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');

        $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

        $incident_count = $getguide + $gethotspot + $getactivity;

        $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
        $agent_margin_gst_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_charges');

        if ($incident_count > 0):
            $separate_service_amount = ($agent_margin_charges + $agent_margin_gst_charges) / $incident_count;
        else:
            $separate_service_amount = ($agent_margin_charges + $agent_margin_gst_charges);
        endif;

        // Ensure both variables are available
        if (isset($_POST['_itinerary_plan_ID'])) {
            $itinerary_plan_ID = htmlspecialchars($_POST['_itinerary_plan_ID'], ENT_QUOTES, 'UTF-8'); // Sanitize input
            echo "<script>
                var itineraryPlanID = '{$itinerary_plan_ID}';
                var itineraryQuoteID = '{$itinerary_quote_ID}';
            </script>";
        } else {
            // Default values if not set
            echo "<script>
                var itineraryPlanID = '';
                var itineraryQuoteID = '';
            </script>";
        }


        $total_components_amount = $TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES + getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
?>
        <!-- START OVERALL COST -->
        <div id="contentToCopy">
            <div class="row mt-3">
                <div class=" col-md-12">
                    <div class="card p-4">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="overflow-hidden mb-4" style="height: 300px;">
                                    <h5 class="text-blue-color">Package Includes</h5>
                                    <div class="text-blue-color" id="vertical-example" style="max-height: 250px; overflow-y: auto;">
                                        <p style="line-height: 27px;">
                                            <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                            <?php endif; ?>
                                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                                <?= geTERMSANDCONDITION('get_vehicle_terms_n_condtions'); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php if ($logged_vendor_id != '' && $logged_vendor_id != '0'): ?>
                                <div class="col-md-6">
                                    <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                    <div class="order-calculations">
                                        <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-heading fw-bold">Total for the Vehicle</span>
                                                <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="gross_total_vehicle_package"><?= number_format(round(getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_confirmed_vendor_vehicle_amount')), 2); ?></span>
                                                </h6>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                    <div class="order-calculations">
                                        <?php if ($logged_agent_id == 0 || $logged_agent_id == ''): ?>
                                            <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                                <?php
                                                $total_room_cost = round(getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_ROOM_COST') + getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_MARGIN_RATE') + getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_hotel') + getITINEARYCONFIRMED_GST_COST_DETAILS($itinerary_plan_ID, 'total_gst_cost_hotel'));

                                                $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                                $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                                $total_extra_bed = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed');

                                                $hotel_pax_count = $total_adult  - $total_extra_bed;
                                                $hotel_overall_meal_cost = getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_FOOD_COST');
                                                $pax_meal_cost = $hotel_overall_meal_cost / ($total_adult + $total_children);
                                                $total_room_cost_updated = $total_room_cost + (($total_adult - $total_extra_bed) * $pax_meal_cost);
                                                $hotel_pax_amount  = $total_room_cost_updated /  $hotel_pax_count;

                                                $hotel_pax_amount = number_format($hotel_pax_amount, 2);
                                                if ($total_room_cost > 0 || $hotel_pax_amount > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Room Cost <b>(<?= $hotel_pax_count ?> * <?= $hotel_pax_amount ?>)</b></span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($total_room_cost_updated, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $amenities_cost = round(getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_AMENITIES_COST'));
                                                if ($amenities_cost > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Amenities Cost</span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($amenities_cost, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $extra_bed_cost = round(getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_EXTRABED_COST'));
                                                if ($extra_bed_cost > 0):
                                                    $updated_extra_bed_cost = $extra_bed_cost + ($pax_meal_cost * $total_extra_bed);
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Extra Bed Cost <b>(<?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed'); ?>)</b></span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($updated_extra_bed_cost, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $cwb_cost = round(getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_CWB_COST'));
                                                if ($cwb_cost > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Child With Bed Cost <b>(<?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed'); ?>)</b></span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($cwb_cost + ($pax_meal_cost * get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed')), 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $cnb_cost = round(getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_CNB_COST'));
                                                if ($cnb_cost > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Child Without Bed Cost <b>(<?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed'); ?>)</b></span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($cnb_cost + ($pax_meal_cost * get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')), 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                $hotel_total = round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $_groupTYPE, 'total_hotel_amount', ""));
                                                if ($hotel_total > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading fw-bold">Total Hotel Amount</span>
                                                        <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format($hotel_total, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                                <?php
                                                $vehicle_cost = round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_cost_margin_vehicle', '') + getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_withgst_cost_vehicle'));
                                                if ($vehicle_cost > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Vehicle Cost <b>(<?= getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_qty', ''); ?>)</b></span>
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($vehicle_cost, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>

                                                <!--  <?php
                                                        $vehicle_tax = round(getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_withgst_cost_vehicle'));
                                                        if ($vehicle_tax > 0):
                                                        ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Vehicle Tax</span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($vehicle_tax, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>
 -->
                                                <?php
                                                $vehicle_total = $vehicle_cost;
                                                if ($vehicle_total > 0):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading fw-bold">Total Vehicle Amount</span>
                                                        <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format($vehicle_total, 2); ?></span></h6>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php
                                            $guide_cost = round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount', '')) + $separate_service_amount;
                                            if ($guide_cost > 0 && $getguide == 1):
                                            ?>
                                                <div class="row mb-2">
                                                    <div class="col-8"><span class="text-heading">Total Guide Cost </span></div>
                                                    <div class="col-4 text-end">
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($guide_cost, 2); ?></span></h6>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $hotspot_cost = round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount', '')) + $separate_service_amount;
                                            if ($hotspot_cost > 0 && $gethotspot == 1):
                                            ?>
                                                <div class="row mb-2">
                                                    <div class="col-8"><span class="text-heading">Total Hotspot Cost </span></div>
                                                    <div class="col-4 text-end">
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($hotspot_cost, 2); ?></span></h6>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $activity_cost = round(getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout', '')) + $separate_service_amount;
                                            if ($activity_cost > 0 && $getactivity == 1):
                                            ?>
                                                <div class="row mb-2">
                                                    <div class="col-8"><span class="text-heading">Total Activity Cost </span></div>
                                                    <div class="col-4 text-end">
                                                        <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($activity_cost, 2); ?></span></h6>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <hr>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Total Amount</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="net_total_package"><?= number_format((getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_gross_total_amount', 'cnf_itinerary_summary')), 2); ?></span>
                                            </h6>
                                        </div>
                                        <hr>
                                        <?php
                                        $total_margin_discount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount', 'cnf_itinerary_summary');
                                        if ($total_margin_discount > 0): ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-heading">
                                                    Coupon Discount
                                                </span>
                                                <h6 class="mb-0"><?= general_currency_symbol; ?> <span id="gross_total_vehicle_package"><?= number_format((getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount', 'cnf_itinerary_summary')), 2); ?></span>
                                                </h6>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Net Payable To Doview Holidays India Pvt ltd</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="net_payable_amount"><?= number_format(round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_net_payable_amount', 'cnf_itinerary_summary')), 2); ?></span>
                                            </h6>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Total Paid</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="total_paid"><?= number_format(round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_paid_amount', 'cnf_itinerary_summary')), 2); ?></span>
                                            </h6>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Total Balance</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="total_balance"><?= number_format(round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_balance_amount', 'cnf_itinerary_summary')), 2); ?></span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center d-none">
                        <div class="demo-inline-spacing">
                            <button onclick="fetchAndCopy()" class="btn btn-primary waves-effect waves-light"><i class="fas fa-copy me-1"></i>Copy</button>
                        </div>
                        <div class="demo-inline-spacing">
                            <a href="latestitinerary.php" class="btn btn-primary waves-effect waves-light">
                                <span class="ti-xs ti ti-check me-1"></span>Complete
                            </a>
                        </div>
                        <div class="demo-inline-spacing">
                            <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" onclick="fetch_ITINERARY_CUSTOMER_INFO();">
                                <span class="ti-xs ti ti-check me-1"></span>Confirm Quotation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/vendor/js/jspdf.js"></script>
        <script src="assets/vendor/js/html2canvas.js"></script>
        <script>
            function fetchAndCopy() {
                $.ajax({
                    url: "itineary_latest_clipboard.php",
                    type: "GET",
                    data: {
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>'
                    }, // Replace with dynamic value if needed
                    success: function(response) {
                        var tempElement = $('<div>').html(response).find('#contentToCopy').html();
                        var tempDiv = $('<div>').html(tempElement);
                        $('body').append(tempDiv);

                        var range = document.createRange();
                        range.selectNode(tempDiv[0]);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);

                        try {
                            document.execCommand('copy');
                            TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } catch (err) {
                            console.error('Unable to copy UI to clipboard', err);
                            TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }

                        window.getSelection().removeAllRanges();
                        tempDiv.remove();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching content:', error);
                    }
                });
            }

            function fetch_ITINERARY_CUSTOMER_INFO() {
                var group_type = $('#hid_group_type').val();
                $('.receiving-customer-details-form-data').load('engine/ajax/ajax_latest_itineary_customer_info_view.php?type=show_form&itinerary_plan_id=' + '<?= $itinerary_plan_ID; ?>' + '&group_type=' + group_type, function() {
                    const container = document.getElementById("VIEWCUSTOMERDETAILSMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });

            }

            document.getElementById("download-confirmed-pdf-btn").addEventListener("click", function() {
                const button = this;
                button.disabled = true; // Disable the button temporarily to prevent multiple clicks

                const container = document.getElementById("pdf-container-confirmed");
                const elementToRemove = document.getElementById("remove-this-confirmed");

                // Create loader element with GIF
                // Check if loader already exists, if not, create and append it
                let loader = document.getElementById("pdf-loader");
                if (!loader) {
                    loader = document.createElement("div");
                    loader.id = "pdf-loader";
                    loader.innerHTML = `
                <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                    <img src="assets/img/pdf_download.gif" alt="Loading..." style="width: 300px; height: 200px;" />
                </div>`;
                    document.body.appendChild(loader); // Show loader
                    console.log("Loader added to the DOM.");
                }
                // Temporarily remove the element you want to exclude from the PDF
                let parentElement = elementToRemove.parentNode;
                if (elementToRemove) {
                    parentElement.removeChild(elementToRemove); // Completely remove the element
                }

                // Remove background highlight for gradient text before rendering to PDF
                const textElements = document.querySelectorAll(".text-primary");
                textElements.forEach(element => {
                    element.style.background = 'none'; // Remove any background (highlight)
                });

                // Render the container with html2canvas
                html2canvas(container, {
                    scale: 2
                }).then((canvas) => {
                    // Restore the element after rendering the PDF
                    if (elementToRemove) {
                        parentElement.appendChild(elementToRemove); // Re-append the element
                    }

                    // After rendering, restore background to the text (if needed for other use cases)
                    textElements.forEach(element => {
                        element.style.background = ''; // Reset background to original (if needed)
                    });

                    const pdf = new jspdf.jsPDF("p", "mm", "a4"); // Default A4 size in portrait
                    const filename = itineraryQuoteID && itineraryQuoteID.trim() !== "" ?
                        `${itineraryQuoteID}.pdf` :
                        "output.pdf";
                    const pageWidth = pdf.internal.pageSize.getWidth(); // A4 width in mm
                    const pageHeight = pdf.internal.pageSize.getHeight(); // A4 height in mm

                    const outerMargin = 5; // Outer margin for the page (top, left, right, bottom)
                    const innerBorderMargin = 5; // Spacing between the outer margin and the inner border
                    const contentMargin = 5; // Margin inside the inner border

                    const innerBorderLeft = outerMargin + innerBorderMargin;
                    const innerBorderTop = outerMargin + innerBorderMargin;
                    const innerBorderRight = pageWidth - outerMargin - innerBorderMargin;
                    const innerBorderBottom = pageHeight - outerMargin - innerBorderMargin;

                    const contentLeft = innerBorderLeft + contentMargin;
                    const contentTop = innerBorderTop + contentMargin;
                    const contentWidth = innerBorderRight - innerBorderLeft - 2 * contentMargin;
                    const contentHeight = innerBorderBottom - innerBorderTop - 2 * contentMargin;

                    const imgWidth = contentWidth; // Width adjusted for content margins
                    const imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio
                    const availableHeight = contentHeight; // Adjusted for content height

                    const pageHeightPx = (availableHeight * canvas.height) / imgHeight; // Page height in pixels

                    let heightLeftPx = canvas.height; // Total height of the canvas
                    let positionPx = 0; // Starting position in pixels

                    while (heightLeftPx > 0) {
                        const currentHeight = Math.min(pageHeightPx, heightLeftPx);

                        const pageCanvas = document.createElement("canvas");
                        pageCanvas.width = canvas.width;
                        pageCanvas.height = currentHeight;

                        const pageContext = pageCanvas.getContext("2d");
                        pageContext.drawImage(
                            canvas,
                            0,
                            positionPx, // Start from the current position
                            canvas.width,
                            currentHeight,
                            0,
                            0,
                            pageCanvas.width,
                            pageCanvas.height
                        );

                        const imgData = pageCanvas.toDataURL("image/png");

                        // Add the image to the PDF inside the content area
                        pdf.addImage(
                            imgData,
                            "PNG",
                            contentLeft,
                            contentTop,
                            imgWidth,
                            (currentHeight * imgWidth) / canvas.width
                        );

                        // Draw the inner border
                        pdf.setLineWidth(0.2); // Border thickness
                        pdf.rect(innerBorderLeft, innerBorderTop, innerBorderRight - innerBorderLeft, innerBorderBottom - innerBorderTop);

                        heightLeftPx -= pageHeightPx; // Reduce height left
                        positionPx += pageHeightPx; // Move to the next part of the canvas

                        if (heightLeftPx > 0) {
                            pdf.addPage(); // Add a new page for remaining content
                        }
                    }

                    // Save the PDF with the given name
                    pdf.save(filename); // Save with dynamic or fallback filename

                    // Remove the loader after PDF is generated
                    const loaderElement = document.getElementById("pdf-loader");
                    if (loaderElement) {
                        document.body.removeChild(loaderElement);
                        console.log("Loader removed from the DOM.");
                    } else {
                        console.error("Loader not found in the DOM.");
                    }

                    // Re-enable the button in case of an error
                    button.disabled = false;
                    // Show toast notification
                    TOAST_NOTIFICATION('success', 'Successfully downloaded PDF', 'Success !!!', '', '', '', '', '', '', '', '', '');
                }).catch((error) => {
                    console.error("Error generating PDF:", error);

                    // Remove the loader even if an error occurs
                    const loaderElement = document.getElementById("pdf-loader");
                    if (loaderElement) {
                        document.body.removeChild(loaderElement);
                        console.log("Loader removed from the DOM after error.");
                    } else {
                        console.error("Loader not found in the DOM after error.");
                    }
                    // Re-enable the button in case of an error
                    button.disabled = false;
                    // Show toast notification for error
                    TOAST_NOTIFICATION('error', 'Failed to download PDF', 'Error !!!', '', '', '', '', '', '', '', '', '');
                });
            });
        </script>
        <!-- END OF THE OVERALL COST -->
<?php
    elseif ($_GET['type'] == 'show_grand_itineary_total') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $itinerary_group_TYPE = $_POST['_groupTYPE'];

        $TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES = getITINEARY_CONFIRMED_TOTAL_GUIDE_CHARGES_DETAILS($itinerary_plan_ID, '', 'TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES');
        $itineary_gross_total_amount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, $itinerary_group_TYPE, 'itineary_gross_total_amount');

        echo number_format(round($itineary_gross_total_amount + $TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES), 2);

    endif;
else :
    echo "Request Ignored";
endif;
?>