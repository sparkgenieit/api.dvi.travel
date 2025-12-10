<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
include_once('../../smtp_functions.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $response = [];
        $confirmed_itinerary_plan_hotel_details_ID = $_POST['hidden_confirmed_itinerary_plan_hotel_details_ID'];
        $hidden_itinerary_plan_id = $_POST['hidden_itinerary_plan_id'];
        $itinerary_plan_hotel_details_ID = $_POST['itinerary_plan_hotel_details_ID'];
        $hotel_voucher_terms_condition = getGLOBALSETTING('hotel_voucher_terms_condition');
        ob_start(); // Start output buffering
?>
        <h5 class="modal-title text-center mb-3">Create Hotel Voucher</h5>
        <form action="" method="post" id="confirm_hotel_voucher_creation_form" data-parsley-validate>
            <div class="border-bottom text-dark mb-4"></div>
            <div class="row">
                <?php
                $getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id` = '$hidden_itinerary_plan_id' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
                while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
                    $itinerary_route_dates[] = $getstatus_fetch['itinerary_route_date'];
                endwhile;
                sort($itinerary_route_dates);

                // Create a map: date => day number
                $all_days_map = [];
                foreach ($itinerary_route_dates as $index => $date) {
                    $all_days_map[$date] = $index + 1;
                }

                // Grouping hotels by hotel_id
                $grouped_hotels = [];

                for ($voucher_count = 0; $voucher_count < count($confirmed_itinerary_plan_hotel_details_ID); $voucher_count++):
                    $hotel_id = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($confirmed_itinerary_plan_hotel_details_ID[$voucher_count], 'confirmed_hotel_id');
                    $itinerary_route_date = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($confirmed_itinerary_plan_hotel_details_ID[$voucher_count], 'confirmed_itinerary_route_date');


                    // If the hotel is already in the array, append the date
                    if (isset($grouped_hotels[$hotel_id])) :
                        $grouped_hotels[$hotel_id]['dates'][] = $itinerary_route_date;
                        $grouped_hotels[$hotel_id]['confirmed_itinerary_plan_hotel_details_ID'][] = $confirmed_itinerary_plan_hotel_details_ID[$voucher_count];
                        $grouped_hotels[$hotel_id]['itinerary_plan_hotel_details_ID'][] = $itinerary_plan_hotel_details_ID[$voucher_count];
                    else :
                        // Store hotel details and itinerary dates
                        $grouped_hotels[$hotel_id] = [
                            'hotel_name' => getHOTEL_DETAIL($hotel_id, '', 'label'),
                            'hotel_email' => getHOTEL_DETAIL($hotel_id, '', 'hotel_email'),
                            'hotel_state_city' => getHOTEL_DETAIL($hotel_id, '', requesttype: 'hotel_state_city'),
                            'dates' => [$itinerary_route_date],
                            'confirmed_itinerary_plan_hotel_details_ID' => [$confirmed_itinerary_plan_hotel_details_ID[$voucher_count]],
                            'itinerary_plan_hotel_details_ID' => [$itinerary_plan_hotel_details_ID[$voucher_count]]
                        ];
                    endif;
                ?>
                    <!-- Hidden Fields -->
                    <?php /* <input type="hidden" name="hidden_itinerary_plan_id" id="hidden_itinerary_plan_id" value="<?= $hidden_itinerary_plan_id; ?>" hidden>
                    <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= $itinerary_route_date; ?>" hidden>
                    <input type="hidden" name="itinerary_plan_hotel_details_ID[]" value="<?= $itinerary_plan_hotel_details_ID[$voucher_count]; ?>" hidden> */ ?>

                <?php endfor;

                $voucher_count = 0;
                $day_count = 0;
                foreach ($grouped_hotels as $hotel_id => $hotel_info) :
                    $hotel_name = $hotel_info['hotel_name'];
                    $hotel_email = $hotel_info['hotel_email'];
                    $hotel_state_city = $hotel_info['hotel_state_city'];
                    $dates = $hotel_info['dates'];
                    $confirmed_itinerary_plan_hotel_details_IDs = $hotel_info['confirmed_itinerary_plan_hotel_details_ID'];
                    $itinerary_plan_hotel_details_IDs = $hotel_info['itinerary_plan_hotel_details_ID'];

                  /*  for ($i = 0; $i < count($dates); $i++):
                        $day_count++;
                    endfor;
                    // Combine all dates into a comma-separated string
                    $date_string = implode(', ', array_map(function ($date) {
                        return date('M d, Y', strtotime($date));
                    }, $dates));
                */

                    sort($dates); // make sure hotel’s dates are in order

                    // Find day numbers for this hotel's dates
                    $day_numbers = [];
                    foreach ($dates as $d) {
                        if (isset($all_days_map[$d])) {
                            $day_numbers[] = $all_days_map[$d];
                        }
                    }
                    sort($day_numbers); // ensure ascending

                    // Display
                    $day_label = (count($day_numbers) > 1)
                        ? "Days " . implode(', ', $day_numbers)
                        : "Day " . $day_numbers[0];

                    $date_string = implode(', ', array_map(fn($d) => date('M d, Y', strtotime($d)), $dates));

                    // Fetch existing record for the first itinerary_plan_hotel_details_ID of this hotel
                    $first_itinerary_plan_hotel_details_ID = $confirmed_itinerary_plan_hotel_details_IDs[0];
                    $existing_record_query = "SELECT * FROM dvi_confirmed_itinerary_plan_hotel_voucher_details WHERE confirmed_itinerary_plan_hotel_details_ID = '$first_itinerary_plan_hotel_details_ID' AND itinerary_plan_id = '$hidden_itinerary_plan_id'";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;
                    
                    /*$day_range = range($day_count, count($dates));
                    sort($day_range); */

                ?>
                    <div class="d-flex justify-content-between align-items-center">
                       <?php /* <h6 class="text-primary"> <?= (count($dates) > 1) ? ("Days " . implode(', ', $day_range)) : "Day " . $day_count; ?> | [<?= $hotel_name; ?> - <?= $hotel_state_city; ?>] | <?= $date_string; ?></h6> */ ?>
                        <h6 class="text-primary"> <?= $day_label; ?> | [<?= $hotel_name; ?> - <?= $hotel_state_city; ?>] | <?= $date_string; ?></h6>
                        <button class="btn btn-label-primary" id="add_cancellation_btn" type="button" onclick="showHOTELCANCELLATIONPOLICYFORM('<?= $hidden_itinerary_plan_id; ?>','<?= $hotel_id ?>')">+ Add Cancellation Policy</button>
                    </div>

                    <div class="col-12">
                        <div class="row mt-3">
                            <!-- Hidden Fields -->
                            <input type="hidden" name="hidden_confirmed_itinerary_plan_hotel_details_ID[]" value="<?= implode(',', $confirmed_itinerary_plan_hotel_details_IDs); ?>" hidden>
                            <input type="hidden" name="hidden_itinerary_plan_id" id="hidden_itinerary_plan_id" value="<?= $hidden_itinerary_plan_id; ?>" hidden>
                            <input type="hidden" name="hotel_id[]" value="<?= $hotel_id; ?>" hidden>
                            <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= implode(',', $dates); ?>" hidden>
                            <input type="hidden" name="itinerary_plan_hotel_details_ID[]" value="<?= implode(',', $itinerary_plan_hotel_details_IDs); ?>" hidden>

                            <!-- Confirmation and Email Fields -->
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="confirmed_by_<?= $voucher_count; ?>">Confirmed By<span class="text-danger"> *</span></label>
                                <input type="text" required name="confirmed_by[]" id="confirmed_by_<?= $voucher_count; ?>" placeholder="Confirmed By" class="form-control required-field" value="<?= $existing_record ? $existing_record['hotel_confirmed_by'] : ''; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="email_id_<?= $voucher_count; ?>">Email Id<span class="text-danger"> *</span></label>
                                <input type="text" required name="email_id[]" id="email_id_<?= $voucher_count; ?>" placeholder="Email Id" class="form-control required-field"
                                    value="<?= $existing_record ? $existing_record['hotel_confirmed_email_id'] : $hotel_email; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="mobile_number_<?= $voucher_count; ?>">Mobile Number<span class=" text-danger"> *</span></label>
                                <input type="text" required name="mobile_number[]" id="mobile_number_<?= $voucher_count; ?>" placeholder="Mobile Number" class="form-control required-field" value="<?= $existing_record ? $existing_record['hotel_confirmed_mobile_no'] : ''; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="status_<?= $voucher_count; ?>">Status<span class="text-danger">*</span></label>
                                <select class="form-select" required name="status[]" id="status_<?= $voucher_count; ?>">
                                    <?= getHOTEL_CONFIRM_STATUS($existing_record ? $existing_record['hotel_booking_status'] : '1', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="invoice_to_<?= $voucher_count; ?>">Invoice To<span class="text-danger">*</span></label>
                                <select class="form-select" required name="invoice_to[]" id="invoice_to_<?= $voucher_count; ?>">
                                    <?= getHOTEL_INVOICE_TO($existing_record ? $existing_record['invoice_to'] : '', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-9 mb-2">
                                <label class="form-label" for="hotel_voucher_terms_condition">Hotel Voucher Terms and Condition<span class="text-danger"> *</span></label>
                                <textarea rows="10" id="hotel_voucher_terms_condition<?= $voucher_count; ?>" name="hotel_voucher_terms_condition[]" class="form-control hotel_voucher_terms_condition" required>
                        <?= $existing_record ? html_entity_decode($existing_record['hotel_voucher_terms_condition'], ENT_QUOTES, 'UTF-8') : $hotel_voucher_terms_condition; ?>
                    </textarea>
                            </div>
                        </div>
                        <div class="border-bottom border-bottom-dashed my-4"></div>
                    </div>
                <?php
                    $voucher_count++;
                endforeach; ?>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-primary m-0">Cancellation Policy</h5>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="text-nowrap mb-3 table-responsive">
                        <table class="table table-hover border-top-0">
                            <thead class="table-head">
                                <tr>
                                    <th>S.No</th>
                                    <th>Hotel</th>
                                    <th>Cancellation Date</th>
                                    <th>Cancellation Percentage</th>
                                    <th>Description</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="load_ajax_response">
                                <?php
                                $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID`, `cancellation_descrption`, `cancellation_date`, `cancellation_percentage`,`hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
                                if ($total_numrows_count > 0) :
                                    while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                                        $counter++;
                                        $cnf_itinerary_plan_hotel_cancellation_policy_ID = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_hotel_cancellation_policy_ID'];
                                        $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                                        $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                                        $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                                        $hotel_id = $fetch_confirmed_itineary_cancellation_data['hotel_id'];
                                        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                ?>
                                        <tr>
                                            <td><?= $counter; ?></td>
                                            <td><?= $hotel_name ?></td>
                                            <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                                            <td><?= $cancellation_percentage . '%'; ?></td>
                                            <td><?= $cancellation_descrption; ?></td>
                                            <td>
                                                <div><span class="cursor-pointer" onclick="deleteCANCELLATIONPOLICY('<?= $cnf_itinerary_plan_hotel_cancellation_policy_ID; ?>','<?= $hidden_itinerary_plan_id; ?>');"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span></div>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No more Cancellation Policy found !!!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="button" id="voucher_cancel_btn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/ckeditor5.js"></script>

        <script>
            $(document).ready(function() {
                $('.form-select').selectize();

                $('.hotel_voucher_terms_condition').each(function() {
                    var textarea = this;

                    CKEDITOR.ClassicEditor.create(textarea, {
                        updateSourceElementOnDestroy: true,
                        toolbar: {
                            items: [
                                'exportPDF', 'exportWord', '|',
                                'findAndReplace', 'selectAll', '|',
                                'heading', '|',
                                'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                'bulletedList', 'numberedList', 'todoList', '|',
                                'outdent', 'indent', '|',
                                'undo', 'redo',
                                '-',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'alignment', '|',
                                'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
                                'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                'textPartLanguage', '|',
                                'sourceEditing', 'lineHeight'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        list: {
                            properties: {
                                styles: true,
                                startIndex: true,
                                reversed: true
                            }
                        },
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Paragraph',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Heading 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Heading 2',
                                    class: 'ck-heading_heading2'
                                },
                                {
                                    model: 'heading3',
                                    view: 'h3',
                                    title: 'Heading 3',
                                    class: 'ck-heading_heading3'
                                },
                                {
                                    model: 'heading4',
                                    view: 'h4',
                                    title: 'Heading 4',
                                    class: 'ck-heading_heading4'
                                },
                                {
                                    model: 'heading5',
                                    view: 'h5',
                                    title: 'Heading 5',
                                    class: 'ck-heading_heading5'
                                },
                                {
                                    model: 'heading6',
                                    view: 'h6',
                                    title: 'Heading 6',
                                    class: 'ck-heading_heading6'
                                }
                            ]
                        },
                        placeholder: '',
                        fontFamily: {
                            options: [
                                'default',
                                'Arial, Helvetica, sans-serif',
                                'Courier New, Courier, monospace',
                                'Georgia, serif',
                                'Lucida Sans Unicode, Lucida Grande, sans-serif',
                                'Tahoma, Geneva, sans-serif',
                                'Times New Roman, Times, serif',
                                'Trebuchet MS, Helvetica, sans-serif',
                                'Verdana, Geneva, sans-serif'
                            ],
                            supportAllValues: true
                        },
                        fontSize: {
                            options: [10, 12, 14, 'default', 18, 20, 22],
                            supportAllValues: true
                        },
                        lineHeight: {
                            options: [1, 1.2, 1.5, 2, 2.5, 3],
                            supportAllValues: true
                        },
                        htmlSupport: {
                            allow: [{
                                name: /.*/,
                                attributes: true,
                                classes: true,
                                styles: true
                            }]
                        },
                        htmlEmbed: {
                            showPreviews: true
                        },
                        editorConfig: {
                            height: 200, // Set the height of the editor
                        },
                        mention: {
                            feeds: [{
                                marker: '@',
                                feed: [
                                    '@apple', '@bears', '@brownie', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                    '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                    '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                    '@sugar', '@sweet', '@topping', '@wafer'
                                ],
                                minimumCharacters: 1
                            }]
                        },
                        removePlugins: [
                            'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                            'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                            'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
                            'FormatPainter', 'TableOfContents'
                        ]
                    }).then(editor => {
                        // Apply CSS for scrollable content
                        editor.editing.view.change(writer => {
                            const editableElement = editor.editing.view.document.getRoot();
                            writer.setStyle({
                                'max-height': '200px', // Adjust the max-height as needed
                                'overflow': 'auto', // Enable scrolling
                            }, editableElement);
                        });
                        // Update textarea on content change in CKEditor
                        editor.model.document.on('change:data', () => {
                            editor.updateSourceElement(); // Update the original textarea
                            $(textarea).parsley().validate(); // Re-validate the textarea with Parsley
                        });
                    }).catch(err => {
                        console.error(err.stack);
                    });
                });

                // AJAX form submission
                $("#confirm_hotel_voucher_creation_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var hidden_itinerary_plan_id = $('#hidden_itinerary_plan_id').val();
                    var spinner = $('#spinner');
                    var form = $(this)[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=create_voucher',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                        beforeSend: function() {
                            spinner.show();
                        },
                        complete: function() {
                            spinner.hide();
                        },
                        success: function(response) {
                            if (response.success) {
                                TOAST_NOTIFICATION(
                                    'success',
                                    'Hotel voucher Successfully Created and sent to Respective Hotel.',
                                    'Success !!!',
                                    '', '', '', '', '', '', '', '', ''
                                );
                                $('#showHOTELVOUCHERFORMDATA').modal('hide');

                                $('#showHOTELVOUCHERFORMDATA').on('hidden.bs.modal', function() {
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                });
                                showVOUCHERDETAILS(hidden_itinerary_plan_id);
                                <?php if ($_POST['request_type'] == 'cancellation'): ?>
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // 2000 ms = 2 seconds
                                <?php endif; ?>
                            } else {
                                if (response.errors && response.errors.cancellation_policy_should_be_required) {
                                    let errorMessage = response.errors.cancellation_policy_should_be_required;

                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error occurred: " + textStatus, errorThrown);
                        }
                    });
                });
            });

            function showVOUCHERDETAILS(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_confirmed_itinerary_overall_voucher_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#OVERALL_VOUCHER_DETAILS').html('');
                        $('#OVERALL_VOUCHER_DETAILS').html(response);
                    }
                });
            }

            function showHOTELCANCELLATIONPOLICYFORM(plan_ID, hotel_id) {
                $('.receiving-confirm-hotelcancellation-policy-form-data').load(
                    'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_cancellation_policy_form&plan_ID=' + plan_ID + '&hotel_id=' + hotel_id,
                    function() {
                        const container = document.getElementById("showHOTELCANCELLATIONPOLICYFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function deleteCANCELLATIONPOLICY(ID, plan_ID) {
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_delete_cancellation_policy_form&ID=' + ID + '&plan_ID=' + plan_ID,
                    function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }
        </script>
    <?php
        $html_output = ob_get_clean(); // Get the buffer content and clean the buffer

        $response['success'] = true;
        $response['html'] = $html_output;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'show_amendment_form') :

        $response = [];
        $hidden_itinerary_plan_id = $_POST['hidden_itinerary_plan_id'];
        $itinerary_plan_hotel_details_ID_raw = $_POST['itinerary_plan_hotel_details_ID'];
        $itinerary_plan_hotel_details_ID = [];

        foreach ($itinerary_plan_hotel_details_ID_raw as $item) {
            $split_items = explode(',', $item);
            foreach ($split_items as $value) {
                $trimmed_value = trim($value);
                if ($trimmed_value !== '') {
                    $itinerary_plan_hotel_details_ID[] = $trimmed_value;
                }
            }
        }
        $hotel_voucher_terms_condition = getGLOBALSETTING('hotel_voucher_terms_condition');


        ob_start(); // Start output buffering
    ?>
        <h5 class="modal-title text-center mb-3">Create Hotel Voucher</h5>
        <form action="" method="post" id="confirm_hotel_voucher_creation_form" data-parsley-validate>
            <div class="border-bottom text-dark mb-4"></div>
            <div class="row">
                <?php

                 $getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id` = '$hidden_itinerary_plan_id' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
                while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
                    $itinerary_route_dates[] = $getstatus_fetch['itinerary_route_date'];
                endwhile;
                sort($itinerary_route_dates);

                // Create a map: date => day number
                $all_days_map = [];
                foreach ($itinerary_route_dates as $index => $date) {
                    $all_days_map[$date] = $index + 1;
                }
                // Grouping hotels by hotel_id
                $grouped_hotels = [];

                for ($voucher_count = 0; $voucher_count < count($itinerary_plan_hotel_details_ID); $voucher_count++):
                    $hotel_id = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($itinerary_plan_hotel_details_ID[$voucher_count], 'confirmed_hotel_id');
                    $itinerary_route_date = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($itinerary_plan_hotel_details_ID[$voucher_count], 'confirmed_itinerary_route_date');

                    // If the hotel is already in the array, append the date
                    if (isset($grouped_hotels[$hotel_id])) :
                        $grouped_hotels[$hotel_id]['dates'][] = $itinerary_route_date;
                        $grouped_hotels[$hotel_id]['itinerary_plan_hotel_details_ID'][] = $itinerary_plan_hotel_details_ID[$voucher_count];
                    else :
                        // Store hotel details and itinerary dates
                        $grouped_hotels[$hotel_id] = [
                            'hotel_name' => getHOTEL_DETAIL($hotel_id, '', 'label'),
                            'hotel_email' => getHOTEL_DETAIL($hotel_id, '', 'hotel_email'),
                            'hotel_state_city' => getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city'),
                            'dates' => [$itinerary_route_date],
                            'itinerary_plan_hotel_details_ID' => [$itinerary_plan_hotel_details_ID[$voucher_count]]
                        ];
                    endif;
                ?>
                    <!-- Hidden Fields -->
                    <?php /* <input type="hidden" name="hidden_itinerary_plan_id" id="hidden_itinerary_plan_id" value="<?= $hidden_itinerary_plan_id; ?>" hidden>
                    <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= $itinerary_route_date; ?>" hidden>
                    <input type="hidden" name="itinerary_plan_hotel_details_ID[]" value="<?= $itinerary_plan_hotel_details_ID[$voucher_count]; ?>" hidden> */ ?>

                <?php endfor;

                $voucher_count = 0;
                $day_count = 0;
                foreach ($grouped_hotels as $hotel_id => $hotel_info) :
                    $hotel_name = $hotel_info['hotel_name'];
                    $hotel_email = $hotel_info['hotel_email'];
                    $hotel_state_city = $hotel_info['hotel_state_city'];
                    $dates = $hotel_info['dates'];
                    $itinerary_plan_hotel_details_IDs = $hotel_info['itinerary_plan_hotel_details_ID'];

                     sort($dates); // make sure hotel’s dates are in order

                    // Find day numbers for this hotel's dates
                    $day_numbers = [];
                    foreach ($dates as $d) {
                        if (isset($all_days_map[$d])) {
                            $day_numbers[] = $all_days_map[$d];
                        }
                    }
                    sort($day_numbers); // ensure ascending

                    // Display
                    $day_label = (count($day_numbers) > 1)
                        ? "Days " . implode(', ', $day_numbers)
                        : "Day " . $day_numbers[0];

                    $date_string = implode(', ', array_map(fn($d) => date('M d, Y', strtotime($d)), $dates));

                   /* for ($i = 0; $i < count($dates); $i++):
                        $day_count++;
                    endfor;
                    // Combine all dates into a comma-separated string
                    $date_string = implode(', ', array_map(function ($date) {
                        return date('M d, Y', strtotime($date));
                    }, $dates));*/

                    // Fetch existing record for the first itinerary_plan_hotel_details_ID of this hotel
                    $first_itinerary_plan_hotel_details_ID = $itinerary_plan_hotel_details_IDs[0];

                    $existing_record_query = "SELECT * FROM dvi_confirmed_itinerary_plan_hotel_voucher_details 
                                  WHERE confirmed_itinerary_plan_hotel_details_ID = '$first_itinerary_plan_hotel_details_ID' 
                                  AND itinerary_plan_id = '$hidden_itinerary_plan_id'";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;

                ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php /* <h6 class="text-primary"> <?= (count($dates) > 1) ? ("Days " . implode(', ', range(1, count($dates)))) : "Day " . $day_count; ?> | [<?= $hotel_name; ?> - <?= $hotel_state_city; ?>] | <?= $date_string; ?></h6> */ ?>
                        <h6 class="text-primary"><?= $day_label; ?> | [<?= $hotel_name; ?> - <?= $hotel_state_city; ?>] | <?= $date_string; ?></h6>   
                        <button class="btn btn-label-primary" id="add_cancellation_btn" type="button" onclick="showHOTELCANCELLATIONPOLICYFORM('<?= $hidden_itinerary_plan_id; ?>','<?= $hotel_id ?>')">+ Add Cancellation Policy</button>
                    </div>

                    <div class="col-12">
                        <div class="row mt-3">
                            <!-- Hidden Fields -->
                            <input type="hidden" name="hidden_itinerary_plan_id" id="hidden_itinerary_plan_id" value="<?= $hidden_itinerary_plan_id; ?>" hidden>
                            <input type="hidden" name="hotel_id[]" value="<?= $hotel_id; ?>" hidden>
                            <input type="hidden" name="hidden_itinerary_route_date[]" value="<?= implode(',', $dates); ?>" hidden>
                            <input type="hidden" name="itinerary_plan_hotel_details_ID[]" value="<?= implode(',', $itinerary_plan_hotel_details_IDs); ?>" hidden>

                            <!-- Confirmation and Email Fields -->
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="confirmed_by_<?= $voucher_count; ?>">Confirmed By<span class="text-danger"> *</span></label>
                                <input type="text" required name="confirmed_by[]" id="confirmed_by_<?= $voucher_count; ?>" placeholder="Confirmed By" class="form-control required-field" value="<?= $existing_record ? $existing_record['hotel_confirmed_by'] : ''; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="email_id_<?= $voucher_count; ?>">Email Id<span class="text-danger"> *</span></label>
                                <input type="text" required name="email_id[]" id="email_id_<?= $voucher_count; ?>" placeholder="Email Id" class="form-control required-field" value="<?= $existing_record ? $existing_record['hotel_confirmed_email_id'] : $hotel_email; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="mobile_number_<?= $voucher_count; ?>">Mobile Number<span class=" text-danger"> *</span></label>
                                <input type="text" required name="mobile_number[]" id="mobile_number_<?= $voucher_count; ?>" placeholder="Mobile Number" class="form-control required-field" value="<?= $existing_record ? $existing_record['hotel_confirmed_mobile_no'] : ''; ?>" />
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="status_<?= $voucher_count; ?>">Status<span class="text-danger">*</span></label>
                                <select class="form-select" required name="status[]" id="status_<?= $voucher_count; ?>">
                                    <?= getHOTEL_CONFIRM_STATUS($existing_record ? $existing_record['hotel_booking_status'] : '1', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="invoice_to_<?= $voucher_count; ?>">Invoice To<span class="text-danger">*</span></label>
                                <select class="form-select" required name="invoice_to[]" id="invoice_to_<?= $voucher_count; ?>">
                                    <?= getHOTEL_INVOICE_TO($existing_record ? $existing_record['invoice_to'] : '', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-9 mb-2">
                                <label class="form-label" for="hotel_voucher_terms_condition">Hotel Voucher Terms and Condition<span class="text-danger"> *</span></label>
                                <textarea rows="10" id="hotel_voucher_terms_condition<?= $voucher_count; ?>" name="hotel_voucher_terms_condition[]" class="form-control hotel_voucher_terms_condition" required>
                        <?= $existing_record ? html_entity_decode($existing_record['hotel_voucher_terms_condition'], ENT_QUOTES, 'UTF-8') : $hotel_voucher_terms_condition; ?>
                    </textarea>
                            </div>
                        </div>
                        <div class="border-bottom border-bottom-dashed my-4"></div>
                    </div>
                <?php
                    $voucher_count++;
                endforeach; ?>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-primary m-0">Cancellation Policy</h5>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="text-nowrap mb-3 table-responsive">
                        <table class="table table-hover border-top-0">
                            <thead class="table-head">
                                <tr>
                                    <th>S.No</th>
                                    <th>Hotel</th>
                                    <th>Cancellation Date</th>
                                    <th>Cancellation Percentage</th>
                                    <th>Description</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="load_ajax_response">
                                <?php
                                $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID`, `cancellation_descrption`, `cancellation_date`, `cancellation_percentage`,`hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
                                if ($total_numrows_count > 0) :
                                    while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                                        $counter++;
                                        $cnf_itinerary_plan_hotel_cancellation_policy_ID = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_hotel_cancellation_policy_ID'];
                                        $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                                        $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                                        $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                                        $hotel_id = $fetch_confirmed_itineary_cancellation_data['hotel_id'];
                                        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                ?>
                                        <tr>
                                            <td><?= $counter; ?></td>
                                            <td><?= $hotel_name ?></td>
                                            <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                                            <td><?= $cancellation_percentage . '%'; ?></td>
                                            <td><?= $cancellation_descrption; ?></td>
                                            <td>
                                                <div><span class="cursor-pointer" onclick="deleteCANCELLATIONPOLICY('<?= $cnf_itinerary_plan_hotel_cancellation_policy_ID; ?>','<?= $hidden_itinerary_plan_id; ?>');"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span></div>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No more Cancellation Policy found !!!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button id="voucher_cancel_btn" type="button" class="btn btn-secondary" onclick="handleVoucherCancelClick(event)">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/ckeditor5.js"></script>

        <script>
            $(document).ready(function() {
                $('.form-select').selectize();

                $('.hotel_voucher_terms_condition').each(function() {
                    var textarea = this;

                    CKEDITOR.ClassicEditor.create(textarea, {
                        updateSourceElementOnDestroy: true,
                        toolbar: {
                            items: [
                                'exportPDF', 'exportWord', '|',
                                'findAndReplace', 'selectAll', '|',
                                'heading', '|',
                                'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                'bulletedList', 'numberedList', 'todoList', '|',
                                'outdent', 'indent', '|',
                                'undo', 'redo',
                                '-',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'alignment', '|',
                                'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
                                'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                'textPartLanguage', '|',
                                'sourceEditing', 'lineHeight'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        list: {
                            properties: {
                                styles: true,
                                startIndex: true,
                                reversed: true
                            }
                        },
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Paragraph',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Heading 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Heading 2',
                                    class: 'ck-heading_heading2'
                                },
                                {
                                    model: 'heading3',
                                    view: 'h3',
                                    title: 'Heading 3',
                                    class: 'ck-heading_heading3'
                                },
                                {
                                    model: 'heading4',
                                    view: 'h4',
                                    title: 'Heading 4',
                                    class: 'ck-heading_heading4'
                                },
                                {
                                    model: 'heading5',
                                    view: 'h5',
                                    title: 'Heading 5',
                                    class: 'ck-heading_heading5'
                                },
                                {
                                    model: 'heading6',
                                    view: 'h6',
                                    title: 'Heading 6',
                                    class: 'ck-heading_heading6'
                                }
                            ]
                        },
                        placeholder: '',
                        fontFamily: {
                            options: [
                                'default',
                                'Arial, Helvetica, sans-serif',
                                'Courier New, Courier, monospace',
                                'Georgia, serif',
                                'Lucida Sans Unicode, Lucida Grande, sans-serif',
                                'Tahoma, Geneva, sans-serif',
                                'Times New Roman, Times, serif',
                                'Trebuchet MS, Helvetica, sans-serif',
                                'Verdana, Geneva, sans-serif'
                            ],
                            supportAllValues: true
                        },
                        fontSize: {
                            options: [10, 12, 14, 'default', 18, 20, 22],
                            supportAllValues: true
                        },
                        lineHeight: {
                            options: [1, 1.2, 1.5, 2, 2.5, 3],
                            supportAllValues: true
                        },
                        htmlSupport: {
                            allow: [{
                                name: /.*/,
                                attributes: true,
                                classes: true,
                                styles: true
                            }]
                        },
                        htmlEmbed: {
                            showPreviews: true
                        },
                        editorConfig: {
                            height: 200, // Set the height of the editor
                        },
                        mention: {
                            feeds: [{
                                marker: '@',
                                feed: [
                                    '@apple', '@bears', '@brownie', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                    '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                    '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                    '@sugar', '@sweet', '@topping', '@wafer'
                                ],
                                minimumCharacters: 1
                            }]
                        },
                        removePlugins: [
                            'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                            'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                            'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
                            'FormatPainter', 'TableOfContents'
                        ]
                    }).then(editor => {
                        // Apply CSS for scrollable content
                        editor.editing.view.change(writer => {
                            const editableElement = editor.editing.view.document.getRoot();
                            writer.setStyle({
                                'max-height': '200px', // Adjust the max-height as needed
                                'overflow': 'auto', // Enable scrolling
                            }, editableElement);
                        });
                        // Update textarea on content change in CKEditor
                        editor.model.document.on('change:data', () => {
                            editor.updateSourceElement(); // Update the original textarea
                            $(textarea).parsley().validate(); // Re-validate the textarea with Parsley
                        });
                    }).catch(err => {
                        console.error(err.stack);
                    });
                });

                // AJAX form submission
                $("#confirm_hotel_voucher_creation_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var hidden_itinerary_plan_id = $('#hidden_itinerary_plan_id').val();
                    var spinner = $('#spinner');
                    var form = $(this)[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=create_amendment_voucher',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                        beforeSend: function() {
                            spinner.show();
                        },
                        complete: function() {
                            spinner.hide();
                        },
                        success: function(response) {
                            if (response.success) {
                                TOAST_NOTIFICATION(
                                    'success',
                                    'Hotel voucher Successfully Created and sent to Respective Hotel.',
                                    'Success !!!',
                                    '', '', '', '', '', '', '', '', ''
                                );
                                $('#showHOTELVOUCHERFORMDATA').modal('hide');

                                $('#showHOTELVOUCHERFORMDATA').on('hidden.bs.modal', function() {
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                });
                                showVOUCHERDETAILS(hidden_itinerary_plan_id);
                                <?php if ($_POST['request_type'] == 'cancellation'): ?>
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // 2000 ms = 2 seconds
                                <?php endif; ?>
                            } else {
                                if (response.errors && response.errors.cancellation_policy_should_be_required) {
                                    let errorMessage = response.errors.cancellation_policy_should_be_required;

                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error occurred: " + textStatus, errorThrown);
                        }
                    });
                });
            });

            // Function to handle the cancel button click
            function handleVoucherCancelClick(event) {
                event.preventDefault(); // Prevent any default action

                // Hide the modal
                const modal = document.getElementById('showHOTELVOUCHERFORMDATA');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }

                // Hide the create/update voucher button
                const createVoucherButton = document.getElementById('createHotelVoucherButton');
                createVoucherButton.classList.add('d-none');

                // Hide the download voucher button
                const downloadVoucherButton = document.getElementById('downloadHotelVoucherButton');
                downloadVoucherButton.classList.add('d-none');

                // Uncheck all checked checkboxes
                const checkedHotels = document.querySelectorAll('.hotel-checkbox:checked');
                checkedHotels.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }

            function showVOUCHERDETAILS(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_confirmed_itinerary_overall_voucher_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#OVERALL_VOUCHER_DETAILS').html('');
                        $('#OVERALL_VOUCHER_DETAILS').html(response);
                    }
                });
            }

            function showHOTELCANCELLATIONPOLICYFORM(plan_ID, hotel_id) {
                $('.receiving-confirm-hotelcancellation-policy-form-data').load(
                    'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_cancellation_policy_form&plan_ID=' + plan_ID + '&hotel_id=' + hotel_id,
                    function() {
                        const container = document.getElementById("showHOTELCANCELLATIONPOLICYFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function deleteCANCELLATIONPOLICY(ID, plan_ID) {
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_delete_cancellation_policy_form&ID=' + ID + '&plan_ID=' + plan_ID,
                    function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }
        </script>
    <?php
        $html_output = ob_get_clean(); // Get the buffer content and clean the buffer

        $response['success'] = true;
        $response['html'] = $html_output;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'create_voucher') :

        $response = [];
        $errors = [];
        $cancellation_policy_should_be_required = [];

        $hidden_itinerary_plan_id = $_POST['hidden_itinerary_plan_id'];
        $hotel_id = $_POST['hotel_id'];
        $confirmed_itinerary_plan_hotel_details_ID = $_POST['hidden_confirmed_itinerary_plan_hotel_details_ID'];
        $itinerary_plan_hotel_details_ID = $_POST['itinerary_plan_hotel_details_ID'];
        $hidden_itinerary_route_date = $_POST['hidden_itinerary_route_date'];
        $confirmed_by = $_POST['confirmed_by'];
        $email_id = $_POST['email_id'];
        $mobile_number = $_POST['mobile_number'];
        $status = $_POST['status'];
        $invoice_to = $_POST['invoice_to'];
        $hotel_voucher_terms_condition = $_POST['hotel_voucher_terms_condition'];

        $primary_customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'primary_customer_name');
        $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'agent_id');
        $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
        $travel_expert_staff_mobile = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
        $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
        $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');
        $agent_company_name = get_AGENT_CONFIG_DETAILS($agent_id, 'company_name');
        $agent_invoice_address = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_address');
        $agent_invoice_gstin_no = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_gstin_no');

        // Check if at least one cancellation policy exists
        // for ($i = 0; $i < count($hotel_id); $i++) :
        //     $hotel_id_val = $hotel_id[$i];
        //     $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' AND `status` = '1' AND `deleted` = '0' AND `hotel_id` = '$hotel_id_val' ") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        //     $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
        //     if ($total_numrows_count == 0) :
        //         $cancellation_policy_should_be_required[] = [
        //             'hotel_title' => getHOTEL_DETAIL($hotel_id_val, '', 'label'),
        //             'hotel_state_city' => getHOTEL_DETAIL($hotel_id_val, '', 'hotel_state_city')
        //         ];
        //     endif;
        // endfor;

        // Check if any 'true' exists in the array
        // if (in_array(true, $cancellation_policy_should_be_required)) :
        //     $errors['cancellation_policy_should_be_required'] = true;
        // endif;

        // // Check if any true exists in the array and generate error messages
        // if (!empty($cancellation_policy_should_be_required)) {
        //     $errorMessages = array_map(function ($policy) {
        //         return "Hotel: <b>{$policy['hotel_title']} - {$policy['hotel_state_city']}</b>";
        //     }, $cancellation_policy_should_be_required);

        //     $errors['cancellation_policy_should_be_required'] = 'Please add at least one more cancellation policy !!! ' . '<br> ' . implode('<br> ', $errorMessages);
        // }

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            for ($i = 0; $i < count($hotel_id); $i++) :
                $hotel_id_val = $hotel_id[$i];
                $confirmed_itinerary_plan_hotel_details_ID_array = explode(',', $confirmed_itinerary_plan_hotel_details_ID[$i]);
                $itinerary_plan_hotel_details_ID_array = explode(',', $itinerary_plan_hotel_details_ID[$i]);
                $itinerary_route_date_array = explode(',', $hidden_itinerary_route_date[$i]);

                for ($j = 0; $j < count($itinerary_plan_hotel_details_ID_array); $j++):
                    $confirmed_itinerary_plan_hotel_details_ID_array_val = $confirmed_itinerary_plan_hotel_details_ID_array[$j];
                    $itinerary_plan_hotel_details_ID_val = $itinerary_plan_hotel_details_ID_array[$j];
                    $hidden_itinerary_route_date_val = $itinerary_route_date_array[$j];

                    $confirmed_by_val = $confirmed_by[$i];
                    $email_id_val = $email_id[$i];
                    $mobile_number_val = $mobile_number[$i];
                    $status_val = $status[$i];
                    $invoice_to_val = $invoice_to[$i];
                    $hotel_voucher_terms_condition_val = htmlspecialchars($hotel_voucher_terms_condition[$i], ENT_QUOTES, 'UTF-8');

                    $hotel_name = getHOTEL_DETAIL($hotel_id_val, '', 'label');
                    $hotel_address = getHOTEL_DETAIL($hotel_id_val, '', 'hotel_address');
                    $hotel_email = $email_id_val;

                    // Check if a record already exists
                    $existing_record_query = "SELECT `cnf_itinerary_plan_hotel_voucher_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID_val' AND `itinerary_plan_id` = '$hidden_itinerary_plan_id'";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;

                    if ($existing_record) :
                        // Update existing record
                        $updateFields = [
                            '`hotel_confirmed_by`',
                            '`hotel_confirmed_email_id`',
                            '`hotel_confirmed_mobile_no`',
                            '`hotel_booking_status`',
                            '`invoice_to`',
                            '`hotel_voucher_terms_condition`'
                        ];

                        $updateValues = [
                            "$confirmed_by_val",
                            "$email_id_val",
                            "$mobile_number_val",
                            "$status_val",
                            "$invoice_to_val",
                            "$hotel_voucher_terms_condition_val"
                        ];

                        $sqlWhere = "cnf_itinerary_plan_hotel_voucher_details_ID = '" . $existing_record['cnf_itinerary_plan_hotel_voucher_details_ID'] . "'";

                        if (!sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_hotel_voucher_details", $updateFields, $updateValues, $sqlWhere)) :
                            die("#UPDATE_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                        endif;
                    else :
                        // Insert new record
                        $arrFields = [
                            '`confirmed_itinerary_plan_hotel_details_ID`',
                            '`itinerary_plan_hotel_details_ID`',
                            '`itinerary_plan_id`',
                            '`itinerary_route_date`',
                            '`hotel_id`',
                            '`hotel_confirmed_by`',
                            '`hotel_confirmed_email_id`',
                            '`hotel_confirmed_mobile_no`',
                            '`hotel_booking_status`',
                            '`invoice_to`',
                            '`hotel_voucher_terms_condition`',
                            '`createdby`',
                            '`status`'
                        ];

                        $arrValues = [
                            "$confirmed_itinerary_plan_hotel_details_ID_array_val",
                            "$itinerary_plan_hotel_details_ID_val",
                            "$hidden_itinerary_plan_id",
                            "$hidden_itinerary_route_date_val",
                            "$hotel_id_val",
                            "$confirmed_by_val",
                            "$email_id_val",
                            "$mobile_number_val",
                            "$status_val",
                            "$invoice_to_val",
                            "$hotel_voucher_terms_condition_val",
                            "$logged_user_id",
                            '1'
                        ];

                        if (!sqlACTIONS("INSERT", "dvi_confirmed_itinerary_plan_hotel_voucher_details", $arrFields, $arrValues, '')) :
                            die("#INSERT_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                        endif;
                    endif;

                endfor;

                if ($status_val):
                    //CONFIRMATION EMAIL 
                    $confirmed_itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'itinerary_quote_ID');
                    $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_adult');
                    $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_children');
                    $total_infants = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_infants');
                    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'preferred_room_count');
                    $food_type_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'food_type');
                    $food_type = getFOODTYPE($food_type_id, 'label');
                    $billing_type = $invoice_to_val;
                    $hotel_status = getHOTEL_CONFIRM_STATUS($status_val, 'label');

                    if (count($itinerary_plan_hotel_details_ID_array) == 1):
                        //HOTEL ASSIGNED TO ONE DAY 
                        $get_room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val, 'get_room_type_id');
                        $check_in_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_id_val, $get_room_type_id, 'check_in_time')));
                        $check_out_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_id_val, $get_room_type_id, 'check_out_time')));
                        $check_in_date = date('M d, Y', strtotime($hidden_itinerary_route_date_val)) . ' ' . $check_in_time;
                        $check_out_date = date('M d, Y', strtotime($hidden_itinerary_route_date_val . ' +1 day')) . ' ' . $check_out_time;
                        $room_type_title = getROOMTYPE_DETAILS($get_room_type_id, 'room_type_title');

                        $mealplandetails = getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        /* if ($i == 0) :
                        $meal_plan_details = str_replace("Breakfast, ", "", $mealplandetails);
                    elseif ($i == (count($hotel_id) - 1)) :
                        $meal_plan_details = str_replace(", Dinner", "", $mealplandetails);
                    else :
                        $meal_plan_details = $mealplandetails;
                    endif; */
                        $meal_plan_details = $mealplandetails;

                        $roomDetails = getRoomDetails($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formatRoomDetails = formatRoomDetails(roomDetails: $roomDetails);
                        /* $formatMealPlanDetails = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($hidden_itinerary_plan_id, $hotel_id_val, $hidden_itinerary_route_date_val, '', 'meal_plan_with_cost'); */
                        $occupancyDetails = getOccupancyDetails($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);

                        // Set global variables      
                        global $confirmed_by_val, $confirmed_itinerary_quote_ID, $primary_customer_name, $hotel_name, $hotel_address, $check_in_date, $check_out_date, $room_type_title, $total_adult, $total_children, $total_infants, $preferred_room_count, $meal_plan_details, $formatRoomDetails,
                            $formatMealPlanDetails, $travel_expert_name, $travel_expert_staff_email, $food_type, $billing_type, $hotel_status, $agent_company_name, $agent_invoice_address, $agent_invoice_gstin_no, $hidden_itinerary_plan_id, $itinerary_plan_hotel_details_ID_val, $hotel_email, $agent_email, $status_val;

                        // Assign values to global variables
                       /* $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_plan_id;
                        $_SESSION['global_confirmed_by_val'] = $confirmed_by_val;
                        $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                        $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                        $_SESSION['global_hotel_name'] = $hotel_name;
                        $_SESSION['global_hotel_status'] = $hotel_status;
                        $_SESSION['global_status_val'] = $status_val;
                        $_SESSION['global_hotel_address'] = $hotel_address;
                        $_SESSION['global_hotel_email'] = $hotel_email;
                        $_SESSION['global_check_in_date'] = $check_in_date;
                        $_SESSION['global_check_out_date'] = $check_out_date;
                        $_SESSION['global_room_type_title'] = $room_type_title;
                        $_SESSION['global_total_adult'] = $total_adult;
                        $_SESSION['global_total_children'] = $total_children;
                        $_SESSION['global_total_infants'] = $total_infants;
                        $_SESSION['global_preferred_room_count'] = $preferred_room_count;
                        $_SESSION['global_meal_plan_details'] = $meal_plan_details;
                        $_SESSION['global_formatRoomDetails'] = $formatRoomDetails;
                        // $_SESSION['global_formatMealPlanDetails'] = $formatMealPlanDetails; 
                        $_SESSION['global_formattedoccupancyDetails'] = $formattedoccupancyDetails;
                        $_SESSION['global_travel_expert_name'] = $travel_expert_name;
                        $_SESSION['global_travel_expert_mobile'] = $travel_expert_staff_mobile;
                        $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;
                        $_SESSION['global_food_type'] = $food_type;
                        $_SESSION['global_billing_type'] = $billing_type;
                        $_SESSION['global_agent_company_name'] = $agent_company_name;
                        $_SESSION['global_agent_invoice_address'] = $agent_invoice_address;
                        $_SESSION['global_agent_invoice_gstin_no'] = $agent_invoice_gstin_no;
                        $_SESSION['global_itinerary_plan_hotel_details_ID_val'] = $itinerary_plan_hotel_details_ID_val;
                        $_SESSION['global_agent_email'] = $agent_email;

                        // Include the email notification script
                        include('ajax_hotel_voucher_confirmation_email_notification.php');

                        // Assign values to global variables
                        unset($_SESSION['global_hotel_status']);
                        unset($_SESSION['global_confirmed_by_val']);
                        unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                        unset($_SESSION['global_primary_customer_name']);
                        unset($_SESSION['global_hotel_name']);
                        unset($_SESSION['global_hotel_address']);
                        unset($_SESSION['global_check_in_date']);
                        unset($_SESSION['global_check_out_date']);
                        unset($_SESSION['global_room_type_title']);
                        unset($_SESSION['global_total_adult']);
                        unset($_SESSION['global_total_children']);
                        unset($_SESSION['global_total_infants']);
                        unset($_SESSION['global_preferred_room_count']);
                        unset($_SESSION['global_meal_plan_details']);
                        unset($_SESSION['global_formatRoomDetails']);
                        // unset($_SESSION['global_formatMealPlanDetails']); 
                        unset($_SESSION['global_formattedoccupancyDetails']);
                        unset($_SESSION['global_travel_expert_name']);
                        unset($_SESSION['global_travel_expert_mobile']);
                        unset($_SESSION['global_travel_expert_staff_email']);
                        unset($_SESSION['global_food_type']);
                        unset($_SESSION['global_billing_type']);
                        unset($_SESSION['global_hidden_itinerary_plan_id']);
                        unset($_SESSION['global_itinerary_plan_hotel_details_ID_val']);
                        unset($_SESSION['global_hotel_email']);
                        unset($_SESSION['global_status_val']);
                        unset($_SESSION['global_agent_email']);*/

                        $global_hidden_itinerary_plan_id = $hidden_itinerary_plan_id;
                        $global_confirmed_by_val = $confirmed_by_val;
                        $global_confirmed_itinerary_quote_ID = $confirmed_itinerary_quote_ID;
                        $global_primary_customer_name = $primary_customer_name;
                        $global_hotel_name = $hotel_name;
                        $global_hotel_status = $hotel_status;
                        $global_status_val = $status_val;
                        $global_hotel_address = $hotel_address;
                        $global_hotel_email = $hotel_email;
                        $global_check_in_date = $check_in_date;
                        $global_check_out_date = $check_out_date;
                        $global_room_type_title = $room_type_title;
                        $global_total_adult = $total_adult;
                        $global_total_children = $total_children;
                        $global_total_infants = $total_infants;
                        $global_preferred_room_count = $preferred_room_count;
                        $global_meal_plan_details = $meal_plan_details;
                        $global_formatRoomDetails = $formatRoomDetails;
                        // $global_formatMealPlanDetails = $formatMealPlanDetails; 
                        $global_formattedoccupancyDetails = $formattedoccupancyDetails;
                        $global_travel_expert_name = $travel_expert_name;
                        $global_travel_expert_mobile = $travel_expert_staff_mobile;
                        $global_travel_expert_staff_email = $travel_expert_staff_email;
                        $global_food_type = $food_type;
                        $global_billing_type = $billing_type;
                        $global_agent_company_name = $agent_company_name;
                        $global_agent_invoice_address = $agent_invoice_address;
                        $global_agent_invoice_gstin_no = $agent_invoice_gstin_no;
                        $global_itinerary_plan_hotel_details_ID_val = $itinerary_plan_hotel_details_ID_val;
                        $global_agent_email = $agent_email;

                        
                        // Convert the comma-separated email IDs into an array
                        $global_hotel_email_array = explode(',', $global_hotel_email);

                        // Trim any whitespace from each email ID
                        $global_hotel_email_array = array_map('trim', $global_hotel_email_array);

                        //Get Deafult Hotel Email ID
                        $global_default_hotel_email = getGLOBALSETTING('default_hotel_voucher_email_id');

                        // Convert the comma-separated email IDs into an array
                        $global_default_hotel_email_array = explode(',', $global_default_hotel_email);

                        // Trim any whitespace from each email ID
                        $global_default_hotel_email_array = array_map('trim', $global_default_hotel_email_array);

                        //Get Deafult Accounts Email ID
                        $global_default_accounts_email = getGLOBALSETTING('default_accounts_email_id');
                        $global_default_accounts_email_array = explode(',', $global_default_accounts_email);
                        $global_default_accounts_email_array = array_map('trim', $global_default_accounts_email_array);

                        $itinerary_quote_ID = get_ITINERARY_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'itinerary_quote_ID');
                        $customer_salutation = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($global_hidden_itinerary_plan_id, 'customer_salutation');
                        $occupancy_details = get_ITINEARY_CONFIRMED_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'total_occupancy_details');

                        $company_youtube = '#';
                        $company_facebook = '#';
                        $company_instagram = '#';
                        $company_linkedin = '#';

                        $admin_emailid = getGLOBALSETTING('cc_email_id');

                        $email_to = [$global_default_accounts_email_array, $global_travel_expert_staff_email, $global_hotel_email_array, $global_default_hotel_email_array, $admin_emailid];

                        $children = ($global_total_children > 0) ? " | Childrens (Above 5 & Below 10) -" . $global_total_children : "";
                        $infant = ($global_total_infants > 0) ? " | Infants (Below 5 Years) -" . $global_total_infants : "";

                        if ($global_billing_type == 1) :
                            $billing_company_name = getGLOBALSETTING('company_name');
                            $billing_company_address = getGLOBALSETTING('company_address');
                            $billing_company_gstin_no = getGLOBALSETTING('company_gstin_no');
                        elseif ($global_billing_type == 2) :
                            $billing_company_name = $global_agent_company_name;
                            $billing_company_address =  $global_agent_invoice_address;
                            $billing_company_gstin_no = $global_agent_invoice_gstin_no;
                        endif;

                        $title = 'Hotel Voucher - ' . $global_hotel_status;
                        $site_title = getGLOBALSETTING('site_title');
                        $company_name = getGLOBALSETTING('company_name');
                        $company_email_id = getGLOBALSETTING('company_email_id');
                        $company_contact_no = getGLOBALSETTING('company_contact_no');
                        $current_YEAR = date('Y');
                        $description = "An itinerary has been confirmed by our agent and requires your approval. Please review the details below and take the necessary action to approve this itinerary.";
                        $site_logo = BASEPATH . '/assets/img/' . getGLOBALSETTING('company_logo');
                        $footer_content = " Copyright &copy; $current_YEAR | $company_name";


        $message_template = '<!DOCTYPE html>
     <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

     <head>
         <meta charset="UTF-8" />
         <meta content="width=device-width, initial-scale=1" name="viewport" />
         <title>Account Verified</title>

         <style type="text/css">
             body {
                 font-family: "DM Sans", sans-serif;
             }

             #outlook a {
                 padding: 0;
             }

             .es-button {
                 mso-style-priority: 100 !important;
                 text-decoration: none !important;
             }

             a[x-apple-data-detectors] {
                 color: inherit !important;
                 text-decoration: none !important;
                 font-size: inherit !important;
                 font-family: inherit !important;
                 font-weight: inherit !important;
                 line-height: inherit !important;
             }

             .es-desk-hidden {
                 display: none;
                 float: left;
                 overflow: hidden;
                 width: 0;
                 max-height: 0;
                 line-height: 0;
                 mso-hide: all;
             }

             @media only screen and (max-width: 600px) {

                 p,
                 ul li,
                 ol li,
                 a {
                     line-height: 150% !important;
                 }

                 h1,
                 h2,
                 h3,
                 h1 a,
                 h2 a,
                 h3 a {
                     line-height: 120%;
                 }

                 h1 {
                     font-size: 30px !important;
                     text-align: left;
                 }

                 h2 {
                     font-size: 24px !important;
                     text-align: left;
                 }

                 h3 {
                     font-size: 20px !important;
                     text-align: left;
                 }

                 .es-header-body h1 a,
                 .es-content-body h1 a,
                 .es-footer-body h1 a {
                     font-size: 30px !important;
                     text-align: left;
                 }

                 .es-header-body h2 a,
                 .es-content-body h2 a,
                 .es-footer-body h2 a {
                     font-size: 24px !important;
                     text-align: left;
                 }

                 .es-header-body h3 a,
                 .es-content-body h3 a,
                 .es-footer-body h3 a {
                     font-size: 20px !important;
                     text-align: left;
                 }

                 .es-menu td a {
                     font-size: 14px !important;
                 }

                 .es-header-body p,
                 .es-header-body ul li,
                 .es-header-body ol li,
                 .es-header-body a {
                     font-size: 14px !important;
                 }

                 .es-content-body p,
                 .es-content-body ul li,
                 .es-content-body ol li,
                 .es-content-body a {
                     font-size: 14px !important;
                 }

                 .es-footer-body p,
                 .es-footer-body ul li,
                 .es-footer-body ol li,
                 .es-footer-body a {
                     font-size: 14px !important;
                 }

                 .es-infoblock p,
                 .es-infoblock ul li,
                 .es-infoblock ol li,
                 .es-infoblock a {
                     font-size: 12px !important;
                 }

                 *[class="gmail-fix"] {
                     display: none !important;
                 }

                 .es-m-txt-c,
                 .es-m-txt-c h1,
                 .es-m-txt-c h2,
                 .es-m-txt-c h3 {
                     text-align: center !important;
                 }

                 .es-m-txt-r,
                 .es-m-txt-r h1,
                 .es-m-txt-r h2,
                 .es-m-txt-r h3 {
                     text-align: right !important;
                 }

                 .es-m-txt-l,
                 .es-m-txt-l h1,
                 .es-m-txt-l h2,
                 .es-m-txt-l h3 {
                     text-align: left !important;
                 }

                 .es-m-txt-r img,
                 .es-m-txt-c img,
                 .es-m-txt-l img {
                     display: inline !important;
                 }

                 .es-button-border {
                     display: block !important;
                 }

                 a.es-button,
                 button.es-button {
                     font-size: 18px !important;
                     display: block !important;
                     border-right-width: 0px !important;
                     border-left-width: 0px !important;
                     border-top-width: 15px !important;
                     border-bottom-width: 15px !important;
                 }

                 .es-adaptive table,
                 .es-left,
                 .es-right {
                     width: 100% !important;
                 }

                 .es-content table,
                 .es-header table,
                 .es-footer table,
                 .es-content,
                 .es-footer,
                 .es-header {
                     width: 100% !important;
                     max-width: 600px !important;
                 }

                 .es-adapt-td {
                     display: block !important;
                     width: 100% !important;
                 }

                 .adapt-img {
                     width: 100% !important;
                     height: auto !important;
                 }

                 .es-m-p0 {
                     padding: 0px !important;
                 }

                 .es-m-p0r {
                     padding-right: 0px !important;
                 }

                 .es-m-p0l {
                     padding-left: 0px !important;
                 }

                 .es-m-p0t {
                     padding-top: 0px !important;
                 }

                 .es-m-p0b {
                     padding-bottom: 0 !important;
                 }

                 .es-m-p20b {
                     padding-bottom: 20px !important;
                 }

                 .es-mobile-hidden,
                 .es-hidden {
                     display: none !important;
                 }

                 tr.es-desk-hidden,
                 td.es-desk-hidden,
                 table.es-desk-hidden {
                     width: auto !important;
                     overflow: visible !important;
                     float: none !important;
                     max-height: inherit !important;
                     line-height: inherit !important;
                 }

                 tr.es-desk-hidden {
                     display: table-row !important;
                 }

                 table.es-desk-hidden {
                     display: table !important;
                 }

                 td.es-desk-menu-hidden {
                     display: table-cell !important;
                 }

                 .es-menu td {
                     width: 1% !important;
                 }

                 table.es-table-not-adapt,
                 .esd-block-html table {
                     width: auto !important;
                 }

                 table.es-social {
                     display: inline-block !important;
                 }

                 table.es-social td {
                     display: inline-block !important;
                 }

                 .es-desk-hidden {
                     display: table-row !important;
                     width: auto !important;
                     overflow: visible !important;
                     max-height: inherit !important;
                 }
             }

             @media screen and (max-width: 384px) {
                 .mail-message-content {
                     width: 414px !important;
                 }
             }

             :root {
                 --line-border-fill: #3498db;
                 --line-border-empty: #e0e0e0;
             }

             .container {
                 text-align: center;
             }

             .progress-container {
                 display: flex;
                 justify-content: space-between;
                 position: relative;
                 margin-bottom: 40px;
                 max-width: 100%;
                 width: 380px;
             }

             .progress-container::before {
                 content: "";
                 /* Mandatory with ::before */
                 background-color: #e0e0e0;
                 position: absolute;
                 top: 70%;
                 left: 0;
                 transform: translateY(-50%);
                 height: 2px;
                 width: 100%;
                 z-index: 1;
             }

             .progress {
                 background-color: var(--line-border-fill);
                 position: absolute;
                 top: 50%;
                 left: 0;
                 transform: translateY(-50%);
                 height: 4px;
                 width: 0%;
                 z-index: -1;
                 transition: 0.4s ease;
             }

             .label {
                 font-size: 12px;
                 color: #999;
                 margin-bottom: 5px;
             }

             .circle {
                 position: relative;
                 /* Ensure proper positioning of the label */
                 background-color: #fff;
                 color: #999;
                 border-radius: 50%;
                 height: 45px;
                 /* Adjust size as needed */
                 width: 45px;
                 /* Adjust size as needed */
                 display: flex;
                 flex-direction: column;
                 align-items: center;
                 justify-content: center;
                 border: 3px solid var(--line-border-empty);
                 transition: 0.4s ease;
                 z-index: 2;
                 margin-top: 30px;
                 /* Adjust margin between circles */
             }

             .circle img {
                 max-width: calc(100% - 20px);
                 /* Adjust the space around the image */
                 max-height: calc(100% - 20px);
                 /* Adjust the space around the image */
             }

             .circle .label {
                 position: absolute;
                 top: -28px;
                 /* Adjust label position above the circle */
                 white-space: nowrap;
             }

             .circle.active {
                 border-color: var(--line-border-fill);
             }
         </style>
     </head>

     <body style="
      width: 100%;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
         <div dir="ltr" class="es-wrapper-color" lang="en" style="background-color: #ffffff">
             <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" role="none" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #ffffff;
        ">
                 <tr>
                     <td valign="top" style="padding: 0; margin: 0">

                         <!-- logo -->
                         <table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
                             <tr>
                                 <td align="center" style="padding: 0; margin: 0">
                                     <table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                      border-left: 1px solid #d3d3d3;
                      border-right: 1px solid #d3d3d3;
                    ">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                          padding: 0;
                          margin: 0;
                          padding-top: 11px;
                          background-color: #fff ;
                          padding-bottom: 11px;
                        ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                                     <tr>
                                                         <td valign="top" style="padding: 0; margin: 0; width: 540px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                                                                 <tr>
                                                                     <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      font-size: 0px;
                                      padding-left:28px;
                                    ">
                                                                         <img src="' . $site_logo . '" alt="Logo" style="
                                          display: block;
                                          border: 0;
                                          outline: none;
                                          text-decoration: none;
                                          -ms-interpolation-mode: bicubic;
                                        " height="60" title="Logo" />
                                                                         <div>
                                                                             <h3 style="font-size: 24px ; color: #d72323; margin-bottom: 0px;">Hotel Voucher - ' . $global_hotel_status . '</h3>
                                                                         </div>
                                                                     </td>
                                                                 </tr>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                                 </td>
                             </tr>
                         </table>


                         <!-- Hotel Details -->
                         <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
                             <tr>
                                 <td align="left" style="padding: 0; margin: 0">
                                     <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #fff ;
                      border-radius: 20px 20px 0px 0px;
                      width: 600px;
                      border-left: 1px solid #d3d3d3;
                      border-right: 1px solid #d3d3d3;
                    " role="none">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                          padding: 0;
                          margin: 0;
                          padding-top: 10px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #fff ;
                        ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" align="left" role="none" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                                     <tr>
                                                         <td align="left" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fff" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                 
                                " role="presentation">
                                                                 <tbody>
                                                                 <tr>
                                                                    <td colspan="2">
                                                                        <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;"> Dear Mr/Ms ' . $global_confirmed_by_val . '</h5>
                                                                        <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                        Greetings from Dvi !!!</h5>
                                                                        <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">Thank you for your all constent support. As per the telecon while ago, May We request you to book and confirm the below mentioned reservation.</h6>
                                                                    </td>
                                                                 </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                             Booking ID</th>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $global_confirmed_itinerary_quote_ID . '</a></th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; border-top: 0;  font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                             Quote ID</th>
                                                                         <th style="text-align: left; border: 1px solid; border-top: 0; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestitinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $itinerary_quote_ID . '</a></th>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Guest Name</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $customer_salutation . '. ' . $global_primary_customer_name . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Hotel Name</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . html_entity_decode($global_hotel_name) . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Hotel Address</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_hotel_address . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Check-in Date</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_check_in_date . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Check-out Date</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_check_out_date . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Room Type</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_room_type_title . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Number of Guests</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_total_adult . ' Adults ' . $children . $infant . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Rooms</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_preferred_room_count . ' Rooms | ' . $global_formattedoccupancyDetails . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Occupancy</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $occupancy_details . '</td>
                                                                     </tr>
                                                                    <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Meal plan</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             ' . $global_meal_plan_details . '</td>
                                                                     </tr>
                                                                   
                                                                    
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Rate</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             <div>
                                                                                 ' . $global_formatRoomDetails . '
                                                                                 <br>
                                                                             </div>
                                                                         </td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Payment Status</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank">Click here</a> to re confirm the booking and get payment</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Special Requests</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                             Food Preference - ' . $global_food_type . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Contact Number of the Travel Expert</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_name . ' - ' . $global_travel_expert_mobile . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Travel Expert Mail Id</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_staff_email . '</td>
                                                                     </tr>
                                                                     <tr>
                                                                         <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                             Billing Instructions</th>
                                                                         <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                         Room and meal plan to the Company rest all direct by the Guest.<br>' . $billing_company_name . '<br>' . $billing_company_address . '<br> GSTIN No :' . $billing_company_gstin_no . ' <br> Please raise the bill against above GST Details </td>
                                                                     </tr>
                                                                 </tbody>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td style="width: 100%;">
                                                             <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                 May I request you to send us the written confirmation along with bank account details for
                                                                 our records.</h6>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="center">
                                                             <!--[if mso]>
  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" style="height:36px;v-text-anchor:middle;width:140px;" arcsize="10%" strokecolor="#ed3c0d" fillcolor="#ed3c0d">
    <w:anchorlock/>
    <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">Confirm Book</center>
  </v:roundrect>
<![endif]-->
<![if !mso]>
  <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank" style="
    padding: 8px 16px;
    background: #ed3c0d;
    color: #fff;
    border: 1px solid #ed3c0d;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  ">Confirm Book</a>
<![endif]-->
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                                 </td>
                             </tr>
                         </table>


                         <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
              mso-table-lspace: 0pt;
              mso-table-rspace: 0pt;
              border-collapse: collapse;
              border-spacing: 0px;
              table-layout: fixed !important;
              width: 100%;
            ">
                             <tr>
                                 <td align="center" style="padding: 0; margin: 0">
                                     <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    border-collapse: collapse;
                    border-spacing: 0px;
                    background-color: #fff ;
                    border-radius: 20px 20px 0px 0px;
                    width: 600px;
                    border-left: 1px solid #d3d3d3;
                    border-right: 1px solid #d3d3d3;
                  " role="none">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                        padding: 0;
                        margin: 0;
                        padding-top: 20px;
                        padding-left: 20px;
                        padding-right: 20px;
                        background-color: #f5f6ff;
                      ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                        ">
                                                     <tr>
                                                         <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: separate;
                                border-spacing: 0px;
                              " role="presentation">

                                                                                                                                         <tr>
                                                                            <td align="center" style="padding: 0; margin: 0">
                                                                                <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #2d3142;
                                              font-size: 12px;
                                            ">
                                                                                     ' . $company_name . '<br />+91
                                             ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
                                                                                </p>
                                                                            </td>
                                                                        </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                                 </td>
                             </tr>
                         </table>

                         <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
                             <tr>
                                 <td align="center" style="padding: 0; margin: 0">
                                     <table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #efefef;
                      width: 600px;
                    ">
                                         <tr>
                                             <td align="left" bgcolor="#fff " style="
                          margin: 0;
                          padding: 10px;
                          background-color: #001255 ;
                        ">
                                                 <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                                     <tr>
                                                         <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                             <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                                                                 <tr>
                                                                     <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                                         <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">

                                                                             <tr>
                                                                                 <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                          ">
                                                                                     <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #fff;
                                              font-size: 12px;
                                            ">
                                                                                         <a target="_blank" href="" style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "></a>' . $footer_content . '<a target="_blank" href="" style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "></a>
                                                                                     </p>
                                                                                 </td>
                                                                             </tr>
                                                                         </table>
                                                                     </td>
                                                                 </tr>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                                 </td>
                             </tr>
                         </table>
                     </td>
                 </tr>
             </table>
         </div>
     </body>

     </html>';
        $subject = "$site_title - Hotel Voucher of Itinerary #$global_confirmed_itinerary_quote_ID ($global_primary_customer_name)";
        $send_from = "$SMTP_EMAIL_SEND_FROM";
        $to = [$email_to];
        $Bcc = [$bcc_emailid];
        $cc = [$cc_emailid];
        $sender_name = "$SMTP_EMAIL_SEND_NAME";
        $reply_to = [$global_travel_expert_staff_email];
        $body = $message_template;
        SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body);

                    else:

                        // Set global variables      
                        global $confirmed_by_val, $confirmed_itinerary_quote_ID, $primary_customer_name, $hotel_name, $hotel_address,  $total_adult, $total_children, $total_infants, $preferred_room_count,  $travel_expert_name, $travel_expert_staff_email, $food_type, $billing_type, $hotel_status, $agent_company_name, $agent_invoice_address, $agent_invoice_gstin_no, $hidden_itinerary_plan_id, $itinerary_plan_hotel_details_ID_val, $hotel_email, $agent_email, $status_val, $hidden_itinerary_route_date_val, $hotel_id_val;

                        // Assign values to global variables
                     /*   $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_plan_id;
                        $_SESSION['global_confirmed_by_val'] = $confirmed_by_val;
                        $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                        $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                        $_SESSION['global_hotel_name'] = $hotel_name;
                        $_SESSION['global_hotel_status'] = $hotel_status;
                        $_SESSION['global_status_val'] = $status_val;
                        $_SESSION['global_hotel_address'] = $hotel_address;
                        $_SESSION['global_hotel_email'] = $hotel_email;
                        $_SESSION['global_total_adult'] = $total_adult;
                        $_SESSION['global_total_children'] = $total_children;
                        $_SESSION['global_total_infants'] = $total_infants;
                        $_SESSION['global_preferred_room_count'] = $preferred_room_count;
                        $_SESSION['global_travel_expert_name'] = $travel_expert_name;
                        $_SESSION['global_travel_expert_mobile'] = $travel_expert_staff_mobile;
                        $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;
                        $_SESSION['global_food_type'] = $food_type;
                        $_SESSION['global_billing_type'] = $billing_type;
                        $_SESSION['global_agent_company_name'] = $agent_company_name;
                        $_SESSION['global_agent_invoice_address'] = $agent_invoice_address;
                        $_SESSION['global_agent_invoice_gstin_no'] = $agent_invoice_gstin_no;
                        $_SESSION['global_agent_email'] = $agent_email;
                        $_SESSION['global_itinerary_plan_hotel_details_ID_val'] = $itinerary_plan_hotel_details_ID[$i];
                        $_SESSION['global_hidden_itinerary_route_date_val'] = $hidden_itinerary_route_date[$i];
                        $_SESSION['global_hotel_id_val'] = $hotel_id_val;

                        // Include the email notification script
                        include('ajax_hotel_voucher_confirmation_email_notification_for_more_days.php');

                        // Assign values to global variables
                        unset($_SESSION['global_hotel_status']);
                        unset($_SESSION['global_confirmed_by_val']);
                        unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                        unset($_SESSION['global_primary_customer_name']);
                        unset($_SESSION['global_hotel_name']);
                        unset($_SESSION['global_hotel_address']);
                        unset($_SESSION['global_total_adult']);
                        unset($_SESSION['global_total_children']);
                        unset($_SESSION['global_total_infants']);
                        unset($_SESSION['global_preferred_room_count']);
                        unset($_SESSION['global_travel_expert_name']);
                        unset($_SESSION['global_travel_expert_mobile']);
                        unset($_SESSION['global_travel_expert_staff_email']);
                        unset($_SESSION['global_food_type']);
                        unset($_SESSION['global_billing_type']);
                        unset($_SESSION['global_hidden_itinerary_plan_id']);
                        unset($_SESSION['global_itinerary_plan_hotel_details_ID_val']);
                        unset($_SESSION['global_hotel_email']);
                        unset($_SESSION['global_status_val']);
                        unset($_SESSION['global_agent_email']);
                        unset($_SESSION['global_hidden_itinerary_route_date_val']);
                        unset($_SESSION['global_hotel_id_val']);*/

                        $global_hidden_itinerary_plan_id = $hidden_itinerary_plan_id;
                        $global_confirmed_by_val = $confirmed_by_val;
                        $global_confirmed_itinerary_quote_ID = $confirmed_itinerary_quote_ID;
                        $global_primary_customer_name = $primary_customer_name;
                        $global_hotel_name = $hotel_name;
                        $global_hotel_status = $hotel_status;
                        $global_status_val = $status_val;
                        $global_hotel_address = $hotel_address;
                        $global_hotel_email = $hotel_email;
                        $global_total_adult = $total_adult;
                        $global_total_children = $total_children;
                        $global_total_infants = $total_infants;
                        $global_preferred_room_count = $preferred_room_count;
                        $global_travel_expert_name = $travel_expert_name;
                        $global_travel_expert_mobile = $travel_expert_staff_mobile;
                        $global_travel_expert_staff_email = $travel_expert_staff_email;
                        $global_food_type = $food_type;
                        $global_billing_type = $billing_type;
                        $global_agent_company_name = $agent_company_name;
                        $global_agent_invoice_address = $agent_invoice_address;
                        $global_agent_invoice_gstin_no = $agent_invoice_gstin_no;
                        $global_agent_email = $agent_email;
                        $global_itinerary_plan_hotel_details_ID_val = $itinerary_plan_hotel_details_ID[$i];
                        $global_hidden_itinerary_route_date_val = $hidden_itinerary_route_date[$i];
                        $global_hotel_id_val = $hotel_id_val;

                    // Convert the comma-separated email IDs into an array
                    $global_hotel_email_array = explode(',', $global_hotel_email);

                    // Trim any whitespace from each email ID
                    $global_hotel_email_array = array_map('trim', $global_hotel_email_array);

                    //Get Deafult Hotel Email ID
                    $global_default_hotel_email = getGLOBALSETTING('default_hotel_voucher_email_id');

                    // Convert the comma-separated email IDs into an array
                    $global_default_hotel_email_array = explode(',', $global_default_hotel_email);

                    // Trim any whitespace from each email ID
                    $global_default_hotel_email_array = array_map('trim', $global_default_hotel_email_array);

       
                    $itinerary_quote_ID = get_ITINERARY_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'itinerary_quote_ID');
                    $customer_salutation = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($global_hidden_itinerary_plan_id, 'customer_salutation');
                    $occupancy_details = get_ITINEARY_CONFIRMED_PLAN_DETAILS($global_hidden_itinerary_plan_id, 'total_occupancy_details');

                    $itinerary_plan_hotel_details_ID_array1 = explode(',', $global_itinerary_plan_hotel_details_ID_val);
                    $itinerary_route_date_array1 = explode(',', $global_hidden_itinerary_route_date_val);
                
                    $combined_meal_plan_details = "";
                    $combined_room_details = "";
                    $combined_room_rate_details = "";

                // NEW: accumulators for segmented check-ins / check-outs
                    $check_in_date_time  = "";
                    $check_out_date_time = "";
                    $check_in_check_out_date_time = "";
                    $stay_segments = [];
                    $segment_open = false;
                    $seg_first_date_str = "";
                    $seg_first_checkin_time = "";
                    $seg_last_date_str = "";
                    $seg_last_checkout_time = "";

                    // SAME HOTEL ASSIGNED TO MORE THAN ONE DAY 
                    for ($j = 0; $j < count($itinerary_plan_hotel_details_ID_array1); $j++):
                        $itinerary_plan_hotel_details_ID_val = $itinerary_plan_hotel_details_ID_array1[$j];
                        $hidden_itinerary_route_date_val = $itinerary_route_date_array1[$j];

                        $get_room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($global_hidden_itinerary_plan_id, $hidden_itinerary_route_date_val, 'get_room_type_id');
                        $check_in_time  = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($global_hotel_id_val, $get_room_type_id, 'check_in_time')));
                        $check_out_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($global_hotel_id_val, $get_room_type_id, 'check_out_time')));

                        // For reference (not used to render the segmented list directly)
                        $check_in_date  = date('M d, Y', strtotime($hidden_itinerary_route_date_val)) . ' ' . $check_in_time;
                        $check_out_date = date('M d, Y', strtotime($hidden_itinerary_route_date_val . ' +1 day')) . ' ' . $check_out_time;

                        $room_type_title = getROOMTYPE_DETAILS($get_room_type_id, 'room_type_title');

                        // --- SEGMENTING LOGIC (replaces the old j==0 / last-item logic) ---
                        $this_day_ts  = strtotime(date('Y-m-d', strtotime($hidden_itinerary_route_date_val)));
                        $last_day_ts  = $seg_last_date_str ? strtotime(date('Y-m-d', strtotime($seg_last_date_str))) : null;

                        if (!$segment_open) {
                            // start first segment
                            $segment_open = true;
                            $seg_first_date_str      = $hidden_itinerary_route_date_val;
                            $seg_first_checkin_time  = $check_in_time;
                            $seg_last_date_str       = $hidden_itinerary_route_date_val;
                            $seg_last_checkout_time  = $check_out_time;
                        } else {
                            $expected_next_ts = strtotime('+1 day', $last_day_ts);
                            if ($this_day_ts === $expected_next_ts) {
                                // continue current segment
                                $seg_last_date_str      = $hidden_itinerary_route_date_val;
                                $seg_last_checkout_time = $check_out_time; // last day's checkout time
                            } else {
                                // close previous segment (store raw dates/times)
                                $stay_segments[] = [
                                    'in_date'  => $seg_first_date_str,
                                    'in_time'  => $seg_first_checkin_time,
                                    'out_date' => date('Y-m-d', strtotime($seg_last_date_str . ' +1 day')),
                                    'out_time' => $seg_last_checkout_time,
                                ];
                                // start new segment
                                $seg_first_date_str      = $hidden_itinerary_route_date_val;
                                $seg_first_checkin_time  = $check_in_time;
                                $seg_last_date_str       = $hidden_itinerary_route_date_val;
                                $seg_last_checkout_time  = $check_out_time;
                            }
                        }
                        // --- END SEGMENTING LOGIC ---

                        // MEAL PLAN DETAILS
                        $mealplandetails = getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($global_hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $meal_plan_details = $mealplandetails;
                        $combined_meal_plan_details .=  $meal_plan_details . "<br>";

                        // ROOM DETAILS
                        $occupancyDetails = getOccupancyDetails($global_hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);
                        $combined_room_details .= date('M d, Y', strtotime($hidden_itinerary_route_date_val)) . " - " . $global_preferred_room_count . ' Rooms | ' . $formattedoccupancyDetails . "<br>";

                        // ROOM RATE DETAILS
                        $roomDetails = getRoomDetails($global_hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formatRoomDetails = formatRoomDetails($roomDetails);
                        $combined_room_rate_details .=  $formatRoomDetails . "<br>";

                        /* $room_details .= ' <tr>
                            <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;"> Room Type</th>
                            <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $room_type_title . '</td>
                        </tr> ';*/
                    endfor;

                    // Close the final open segment, if any (store raw dates/times)
                    if ($segment_open) {
                        $stay_segments[] = [
                            'in_date'  => $seg_first_date_str,
                            'in_time'  => $seg_first_checkin_time,
                            'out_date' => date('Y-m-d', strtotime($seg_last_date_str . ' +1 day')),
                            'out_time' => $seg_last_checkout_time,
                        ];
                    }

                    // Helper to format \"20 oct 2025 12:00 PM\" (lowercase month only)
                    $format_range = function ($dateYmd, $timeHia) {
                        // Day without leading zero, 3-letter month, 4-digit year
                        $str = date('j M Y', strtotime($dateYmd)) . ' ' . $timeHia; // e.g., 20 Oct 2025 12:00 PM
                        // Lowercase the English month only
                        $months  = [' Jan ', ' Feb ', ' Mar ', ' Apr ', ' May ', ' Jun ', ' Jul ', ' Aug ', ' Sep ', ' Oct ', ' Nov ', ' Dec '];
                        $monthsL = [' jan ', ' feb ', ' mar ', ' apr ', ' may ', ' jun ', ' jul ', ' aug ', ' sep ', ' oct ', ' nov ', ' dec '];
                        // pad with spaces to catch month boundaries, then trim back
                        $tmp = ' ' . $str . ' ';
                        $tmp = str_replace($months, $monthsL, $tmp);
                        // Ensure AM/PM remain uppercase
                        $tmp = str_replace([' am', ' pm'], [' AM', ' PM'], $tmp);
                        return trim($tmp);
                    };

                    // Build the final single-line variable with comma-separated segments
                    $ranges = [];
                    foreach ($stay_segments as $seg) {
                        $in  = $format_range($seg['in_date'],  $seg['in_time']);
                        $out = $format_range($seg['out_date'], $seg['out_time']);
                        $ranges[] = $in . ' - ' . $out;
                    }
                    $check_in_check_out_date_time = implode(' , ', $ranges);

                    $company_youtube = '#';
                    $company_facebook = '#';
                    $company_instagram = '#';
                    $company_linkedin = '#';

                    $admin_emailid = getGLOBALSETTING('cc_email_id');
                    $bcc_emailid = [$admin_emailid];

                    $email_to = [$global_travel_expert_staff_email, $global_hotel_email_array, $global_default_hotel_email_array];


                    $children = ($global_total_children > 0) ? " | Childrens (Above 5 & Below 10) -" . $global_total_children : "";
                    $infant = ($global_total_infants > 0) ? " | Infants (Below 5 Years) -" . $global_total_infants : "";

                    if ($global_billing_type == 1) :
                        $billing_company_name = getGLOBALSETTING('company_name');
                        $billing_company_address = getGLOBALSETTING('company_address');
                        $billing_company_gstin_no = getGLOBALSETTING('company_gstin_no');
                    elseif ($global_billing_type == 2) :
                        $billing_company_name = $global_agent_company_name;
                        $billing_company_address =  $global_agent_invoice_address;
                        $billing_company_gstin_no = $global_agent_invoice_gstin_no;
                    endif;

                    $title = 'Hotel Voucher - ' . $global_hotel_status;
                    $site_title = getGLOBALSETTING('site_title');
                    $company_name = getGLOBALSETTING('company_name');
                    $company_email_id = getGLOBALSETTING('company_email_id');
                    $company_contact_no = getGLOBALSETTING('company_contact_no');
                    $current_YEAR = date('Y');
                    $description = "An itinerary has been confirmed by our agent and requires your approval. Please review the details below and take the necessary action to approve this itinerary.";
                    $site_logo = BASEPATH . '/assets/img/' . getGLOBALSETTING('company_logo');
                    $footer_content = " Copyright &copy; $current_YEAR | $company_name";


                                $message_template = '<!DOCTYPE html>
                            <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

                            <head>
                                <meta charset="UTF-8" />
                                <meta content="width=device-width, initial-scale=1" name="viewport" />
                                <title>Account Verified</title>

                                <style type="text/css">
                                    body {
                                        font-family: "DM Sans", sans-serif;
                                    }

                                    #outlook a {
                                        padding: 0;
                                    }

                                    .es-button {
                                        mso-style-priority: 100 !important;
                                        text-decoration: none !important;
                                    }

                                    a[x-apple-data-detectors] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                    }

                                    .es-desk-hidden {
                                        display: none;
                                        float: left;
                                        overflow: hidden;
                                        width: 0;
                                        max-height: 0;
                                        line-height: 0;
                                        mso-hide: all;
                                    }

                                    @media only screen and (max-width: 600px) {

                                        p,
                                        ul li,
                                        ol li,
                                        a {
                                            line-height: 150% !important;
                                        }

                                        h1,
                                        h2,
                                        h3,
                                        h1 a,
                                        h2 a,
                                        h3 a {
                                            line-height: 120%;
                                        }

                                        h1 {
                                            font-size: 30px !important;
                                            text-align: left;
                                        }

                                        h2 {
                                            font-size: 24px !important;
                                            text-align: left;
                                        }

                                        h3 {
                                            font-size: 20px !important;
                                            text-align: left;
                                        }

                                        .es-header-body h1 a,
                                        .es-content-body h1 a,
                                        .es-footer-body h1 a {
                                            font-size: 30px !important;
                                            text-align: left;
                                        }

                                        .es-header-body h2 a,
                                        .es-content-body h2 a,
                                        .es-footer-body h2 a {
                                            font-size: 24px !important;
                                            text-align: left;
                                        }

                                        .es-header-body h3 a,
                                        .es-content-body h3 a,
                                        .es-footer-body h3 a {
                                            font-size: 20px !important;
                                            text-align: left;
                                        }

                                        .es-menu td a {
                                            font-size: 14px !important;
                                        }

                                        .es-header-body p,
                                        .es-header-body ul li,
                                        .es-header-body ol li,
                                        .es-header-body a {
                                            font-size: 14px !important;
                                        }

                                        .es-content-body p,
                                        .es-content-body ul li,
                                        .es-content-body ol li,
                                        .es-content-body a {
                                            font-size: 14px !important;
                                        }

                                        .es-footer-body p,
                                        .es-footer-body ul li,
                                        .es-footer-body ol li,
                                        .es-footer-body a {
                                            font-size: 14px !important;
                                        }

                                        .es-infoblock p,
                                        .es-infoblock ul li,
                                        .es-infoblock ol li,
                                        .es-infoblock a {
                                            font-size: 12px !important;
                                        }

                                        *[class="gmail-fix"] {
                                            display: none !important;
                                        }

                                        .es-m-txt-c,
                                        .es-m-txt-c h1,
                                        .es-m-txt-c h2,
                                        .es-m-txt-c h3 {
                                            text-align: center !important;
                                        }

                                        .es-m-txt-r,
                                        .es-m-txt-r h1,
                                        .es-m-txt-r h2,
                                        .es-m-txt-r h3 {
                                            text-align: right !important;
                                        }

                                        .es-m-txt-l,
                                        .es-m-txt-l h1,
                                        .es-m-txt-l h2,
                                        .es-m-txt-l h3 {
                                            text-align: left !important;
                                        }

                                        .es-m-txt-r img,
                                        .es-m-txt-c img,
                                        .es-m-txt-l img {
                                            display: inline !important;
                                        }

                                        .es-button-border {
                                            display: block !important;
                                        }

                                        a.es-button,
                                        button.es-button {
                                            font-size: 18px !important;
                                            display: block !important;
                                            border-right-width: 0px !important;
                                            border-left-width: 0px !important;
                                            border-top-width: 15px !important;
                                            border-bottom-width: 15px !important;
                                        }

                                        .es-adaptive table,
                                        .es-left,
                                        .es-right {
                                            width: 100% !important;
                                        }

                                        .es-content table,
                                        .es-header table,
                                        .es-footer table,
                                        .es-content,
                                        .es-footer,
                                        .es-header {
                                            width: 100% !important;
                                            max-width: 600px !important;
                                        }

                                        .es-adapt-td {
                                            display: block !important;
                                            width: 100% !important;
                                        }

                                        .adapt-img {
                                            width: 100% !important;
                                            height: auto !important;
                                        }

                                        .es-m-p0 {
                                            padding: 0px !important;
                                        }

                                        .es-m-p0r {
                                            padding-right: 0px !important;
                                        }

                                        .es-m-p0l {
                                            padding-left: 0px !important;
                                        }

                                        .es-m-p0t {
                                            padding-top: 0px !important;
                                        }

                                        .es-m-p0b {
                                            padding-bottom: 0 !important;
                                        }

                                        .es-m-p20b {
                                            padding-bottom: 20px !important;
                                        }

                                        .es-mobile-hidden,
                                        .es-hidden {
                                            display: none !important;
                                        }

                                        tr.es-desk-hidden,
                                        td.es-desk-hidden,
                                        table.es-desk-hidden {
                                            width: auto !important;
                                            overflow: visible !important;
                                            float: none !important;
                                            max-height: inherit !important;
                                            line-height: inherit !important;
                                        }

                                        tr.es-desk-hidden {
                                            display: table-row !important;
                                        }

                                        table.es-desk-hidden {
                                            display: table !important;
                                        }

                                        td.es-desk-menu-hidden {
                                            display: table-cell !important;
                                        }

                                        .es-menu td {
                                            width: 1% !important;
                                        }

                                        table.es-table-not-adapt,
                                        .esd-block-html table {
                                            width: auto !important;
                                        }

                                        table.es-social {
                                            display: inline-block !important;
                                        }

                                        table.es-social td {
                                            display: inline-block !important;
                                        }

                                        .es-desk-hidden {
                                            display: table-row !important;
                                            width: auto !important;
                                            overflow: visible !important;
                                            max-height: inherit !important;
                                        }
                                    }

                                    @media screen and (max-width: 384px) {
                                        .mail-message-content {
                                            width: 414px !important;
                                        }
                                    }

                                    :root {
                                        --line-border-fill: #3498db;
                                        --line-border-empty: #e0e0e0;
                                    }

                                    .container {
                                        text-align: center;
                                    }

                                    .progress-container {
                                        display: flex;
                                        justify-content: space-between;
                                        position: relative;
                                        margin-bottom: 40px;
                                        max-width: 100%;
                                        width: 380px;
                                    }

                                    .progress-container::before {
                                        content: "";
                                        /* Mandatory with ::before */
                                        background-color: #e0e0e0;
                                        position: absolute;
                                        top: 70%;
                                        left: 0;
                                        transform: translateY(-50%);
                                        height: 2px;
                                        width: 100%;
                                        z-index: 1;
                                    }

                                    .progress {
                                        background-color: var(--line-border-fill);
                                        position: absolute;
                                        top: 50%;
                                        left: 0;
                                        transform: translateY(-50%);
                                        height: 4px;
                                        width: 0%;
                                        z-index: -1;
                                        transition: 0.4s ease;
                                    }

                                    .label {
                                        font-size: 12px;
                                        color: #999;
                                        margin-bottom: 5px;
                                    }

                                    .circle {
                                        position: relative;
                                        /* Ensure proper positioning of the label */
                                        background-color: #fff;
                                        color: #999;
                                        border-radius: 50%;
                                        height: 45px;
                                        /* Adjust size as needed */
                                        width: 45px;
                                        /* Adjust size as needed */
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        justify-content: center;
                                        border: 3px solid var(--line-border-empty);
                                        transition: 0.4s ease;
                                        z-index: 2;
                                        margin-top: 30px;
                                        /* Adjust margin between circles */
                                    }

                                    .circle img {
                                        max-width: calc(100% - 20px);
                                        /* Adjust the space around the image */
                                        max-height: calc(100% - 20px);
                                        /* Adjust the space around the image */
                                    }

                                    .circle .label {
                                        position: absolute;
                                        top: -28px;
                                        /* Adjust label position above the circle */
                                        white-space: nowrap;
                                    }

                                    .circle.active {
                                        border-color: var(--line-border-fill);
                                    }
                                </style>
                            </head>

                            <body style="
                            width: 100%;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                            padding: 0;
                            margin: 0;
                            ">
                                <div dir="ltr" class="es-wrapper-color" lang="en" style="background-color: #ffffff">
                                    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" role="none" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                padding: 0;
                                margin: 0;
                                width: 100%;
                                height: 100%;
                                background-repeat: repeat;
                                background-position: center top;
                                background-color: #ffffff;
                                ">
                                        <tr>
                                            <td valign="top" style="padding: 0; margin: 0">

                                                <!-- logo -->
                                                <table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        table-layout: fixed !important;
                                        width: 100%;
                                        background-color: transparent;
                                        background-repeat: repeat;
                                        background-position: center top;
                                    ">
                                                    <tr>
                                                        <td align="center" style="padding: 0; margin: 0">
                                                            <table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            background-color: #ffffff;
                                            width: 600px;
                                            border-left: 1px solid #d3d3d3;
                                            border-right: 1px solid #d3d3d3;
                                            ">
                                                                <tr>
                                                                    <td align="left" bgcolor="#fff " style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 11px;
                                                background-color: #fff ;
                                                padding-bottom: 11px;
                                                ">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                ">
                                                                            <tr>
                                                                                <td valign="top" style="padding: 0; margin: 0; width: 540px">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                        mso-table-lspace: 0pt;
                                                        mso-table-rspace: 0pt;
                                                        border-collapse: collapse;
                                                        border-spacing: 0px;
                                                        ">
                                                                                        <tr>
                                                                                            <td align="left" style="
                                                            padding: 0;
                                                            margin: 0;
                                                            font-size: 0px;
                                                            padding-left:28px;
                                                            ">
                                                                                                <img src="' . $site_logo . '" alt="Logo" style="
                                                                display: block;
                                                                border: 0;
                                                                outline: none;
                                                                text-decoration: none;
                                                                -ms-interpolation-mode: bicubic;
                                                                " height="60" title="Logo" />
                                                                                                <div>
                                                                                                    <h3 style="font-size: 24px ; color: #d72323; margin-bottom: 0px;">Hotel Voucher - ' . $global_hotel_status . '</h3>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>


                                                <!-- Hotel Details -->
                                                <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        table-layout: fixed !important;
                                        width: 100%;
                                    ">
                                                    <tr>
                                                        <td align="left" style="padding: 0; margin: 0">
                                                            <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            background-color: #fff ;
                                            border-radius: 20px 20px 0px 0px;
                                            width: 600px;
                                            border-left: 1px solid #d3d3d3;
                                            border-right: 1px solid #d3d3d3;
                                            " role="none">
                                                                <tr>
                                                                    <td align="left" bgcolor="#fff " style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 10px;
                                                padding-bottom: 20px;
                                                padding-left: 20px;
                                                padding-right: 20px;
                                                background-color: #fff ;
                                                ">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" align="left" role="none" style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                ">
                                                                            <tr>
                                                                                <td align="left" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fff" style="
                                                        mso-table-lspace: 0pt;
                                                        mso-table-rspace: 0pt;
                                                        border-collapse: separate;
                                                        border-spacing: 0px;
                                                        
                                                        " role="presentation">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td colspan="2">
                                                                                                <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;"> Dear Mr/Ms ' . $global_confirmed_by_val . '</h5>
                                                                                                <h5 style="font-size: 14px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                                                Greetings from Dvi !!!</h5>
                                                                                                <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">Thank you for your all constent support. As per the telecon while ago, May We request you to book and confirm the below mentioned reservation.</h6>
                                                                                            </td>
                                                                                        </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                                                    Booking ID</th>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $global_confirmed_itinerary_quote_ID . '</a></th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; border-top: 0; font-size: 15px; padding: 7px;border-right: 0;width:160px;color: #001255;">
                                                                                                    Quote ID</th>
                                                                                                <th style="text-align: left; border: 1px solid; border-top: 0; font-size: 15px; padding: 7px;width:400px;"><a href="' . BASEPATH . 'latestitinerary.php?route=add&formtype=generate_itinerary&id=' . $global_hidden_itinerary_plan_id . '">' . $itinerary_quote_ID . '</a></th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Guest Name</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $customer_salutation . '. ' . $global_primary_customer_name . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Hotel Name</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . html_entity_decode($global_hotel_name) . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Hotel Address</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_hotel_address . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Number of Guests</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                                                    ' . $global_total_adult . ' Adults ' . $children . $infant . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Occupancy</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                                                    ' . $occupancy_details . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                            <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;"> Check-in & Check-out Date</th>
                                                                                            <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $check_in_check_out_date_time . '</td>
                                                                                            </tr>
                                                                                            <!--<tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;"> Check-out Date</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $check_out_date_time . '</td>
                                                                                            </tr>-->
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;"> Rooms</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $combined_room_details  . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">  Meal plan</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $combined_meal_plan_details . '</td>
                                                                                            </tr>
                                                                                        
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;"> Rate</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;"><div>' . $combined_room_rate_details . '</div></td>
                                                                                            </tr>
                                                                                        
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Payment Status</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                                                    <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank">Click here</a> to re confirm the booking and get payment</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Special Requests</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                                                    Food Preference - ' . $global_food_type . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Contact Number of the Travel Expert</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_name . ' - ' . $global_travel_expert_mobile . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Travel Expert Mail Id</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">' . $global_travel_expert_staff_email . '</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-right: 0;border-top: 0;width:160px;color: #001255;">
                                                                                                    Billing Instructions</th>
                                                                                                <td style="text-align: left; border: 1px solid; font-size: 15px; padding: 7px;border-top: 0;width:400px;">
                                                                                                Room and meal plan to the Company rest all direct by the Guest.<br>' . $billing_company_name . '<br>' . $billing_company_address . '<br> GSTIN No :' . $billing_company_gstin_no . ' <br> Please raise the bill against above GST Details </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 100%;">
                                                                                    <h6 style="font-size: 13px; font-weight: 500; margin-bottom: 15px; margin-top: 10px;color: #001255;">
                                                                                        May I request you to send us the written confirmation along with bank account details for
                                                                                        our records.</h6>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" style="height:36px;v-text-anchor:middle;width:140px;" arcsize="10%" strokecolor="#ed3c0d" fillcolor="#ed3c0d">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">Confirm Book</center>
                        </v:roundrect>
                        <![endif]-->
                        <![if !mso]>
                        <a href="' . PUBLICPATH . 'hotelconfirmation.php?id=' . $global_hidden_itinerary_plan_id . '&itinerary_plan_hotel_details_ID=' . $global_itinerary_plan_hotel_details_ID_val . '" target="_blank" style="
                            padding: 8px 16px;
                            background: #ed3c0d;
                            color: #fff;
                            border: 1px solid #ed3c0d;
                            font-size: 13px;
                            font-weight: 600;
                            cursor: pointer;
                            text-decoration: none;
                            display: inline-block;
                        ">Confirm Book</a>
                        <![endif]-->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>


                                                <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    border-collapse: collapse;
                                    border-spacing: 0px;
                                    table-layout: fixed !important;
                                    width: 100%;
                                    ">
                                                    <tr>
                                                        <td align="center" style="padding: 0; margin: 0">
                                                            <table bgcolor="#fff " class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            background-color: #fff ;
                                            border-radius: 20px 20px 0px 0px;
                                            width: 600px;
                                            border-left: 1px solid #d3d3d3;
                                            border-right: 1px solid #d3d3d3;
                                        " role="none">
                                                                <tr>
                                                                    <td align="left" bgcolor="#fff " style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 20px;
                                                padding-left: 20px;
                                                padding-right: 20px;
                                                background-color: #f5f6ff;
                                            ">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                                mso-table-lspace: 0pt;
                                                mso-table-rspace: 0pt;
                                                border-collapse: collapse;
                                                border-spacing: 0px;
                                                ">
                                                                            <tr>
                                                                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" style="
                                                        mso-table-lspace: 0pt;
                                                        mso-table-rspace: 0pt;
                                                        border-collapse: separate;
                                                        border-spacing: 0px;
                                                    " role="presentation">

                                                                                                                                                                <tr>
                                                                                                    <td align="center" style="padding: 0; margin: 0">
                                                                                                        <p style="
                                                                    margin: 0;
                                                                    -webkit-text-size-adjust: none;
                                                                    -ms-text-size-adjust: none;
                                                                    mso-line-height-rule: exactly;
                                                                    line-height: 18px;
                                                                    color: #2d3142;
                                                                    font-size: 12px;
                                                                    ">
                                                                                                            ' . $company_name . '<br />+91
                                                                    ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
                                                                                                        </p>
                                                                                                    </td>
                                                                                                </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <table cellpadding="0" cellspacing="0" class="es-content" align="center" role="none" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        table-layout: fixed !important;
                                        width: 100%;
                                    ">
                                                    <tr>
                                                        <td align="center" style="padding: 0; margin: 0">
                                                            <table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" role="none" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            background-color: #efefef;
                                            width: 600px;
                                            ">
                                                                <tr>
                                                                    <td align="left" bgcolor="#fff " style="
                                                margin: 0;
                                                padding: 10px;
                                                background-color: #001255 ;
                                                ">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                ">
                                                                            <tr>
                                                                                <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" role="none" style="
                                                        mso-table-lspace: 0pt;
                                                        mso-table-rspace: 0pt;
                                                        border-collapse: collapse;
                                                        border-spacing: 0px;
                                                        ">
                                                                                        <tr>
                                                                                            <td align="left" style="padding: 0; margin: 0; width: 560px">
                                                                                                <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                                mso-table-lspace: 0pt;
                                                                mso-table-rspace: 0pt;
                                                                border-collapse: collapse;
                                                                border-spacing: 0px;
                                                            ">

                                                                                                    <tr>
                                                                                                        <td align="center" style="
                                                                    padding: 0;
                                                                    margin: 0;
                                                                ">
                                                                                                            <p style="
                                                                    margin: 0;
                                                                    -webkit-text-size-adjust: none;
                                                                    -ms-text-size-adjust: none;
                                                                    mso-line-height-rule: exactly;
                                                                    line-height: 18px;
                                                                    color: #fff;
                                                                    font-size: 12px;
                                                                    ">
                                                                                                                <a target="_blank" href="" style="
                                                                        -webkit-text-size-adjust: none;
                                                                        -ms-text-size-adjust: none;
                                                                        mso-line-height-rule: exactly;
                                                                        text-decoration: underline;
                                                                        color: #2d3142;
                                                                        font-size: 12px;
                                                                    "></a>' . $footer_content . '<a target="_blank" href="" style="
                                                                        -webkit-text-size-adjust: none;
                                                                        -ms-text-size-adjust: none;
                                                                        mso-line-height-rule: exactly;
                                                                        text-decoration: underline;
                                                                        color: #2d3142;
                                                                        font-size: 12px;
                                                                    "></a>
                                                                                                            </p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </body>

                            </html>';
                            
                            $subject = "$site_title - Hotel Voucher of Itinerary #$global_confirmed_itinerary_quote_ID ($global_primary_customer_name)";
                            $send_from = "$SMTP_EMAIL_SEND_FROM";
                            $to = [$email_to];
                            $Bcc = [$bcc_emailid];
                            $cc = [$cc_emailid];
                            $sender_name = "$SMTP_EMAIL_SEND_NAME";
                            $reply_to = [$global_travel_expert_staff_email];
                            $body = $message_template;
                            SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $body);

                    endif;
                endif;
            endfor;

            $response['success'] = true;
        endif;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'create_amendment_voucher') :

        $response = [];
        $errors = [];
        $cancellation_policy_should_be_required = [];

        $hidden_itinerary_plan_id = $_POST['hidden_itinerary_plan_id'];
        $hotel_id = $_POST['hotel_id'];
        $itinerary_plan_hotel_details_ID = $_POST['itinerary_plan_hotel_details_ID'];
        $hidden_itinerary_route_date = $_POST['hidden_itinerary_route_date'];

        $confirmed_by = $_POST['confirmed_by'];
        $email_id = $_POST['email_id'];
        $mobile_number = $_POST['mobile_number'];
        $status = $_POST['status'];
        $invoice_to = $_POST['invoice_to'];
        $hotel_voucher_terms_condition = $_POST['hotel_voucher_terms_condition'];

        $primary_customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'primary_customer_name');
        $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'agent_id');
        $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
        $travel_expert_staff_mobile = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
        $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
        $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');
        $agent_company_name = get_AGENT_CONFIG_DETAILS($agent_id, 'company_name');
        $agent_invoice_address = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_address');
        $agent_invoice_gstin_no = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_gstin_no');

        // Check if at least one cancellation policy exists
        // for ($i = 0; $i < count($hotel_id); $i++) :
        //     $hotel_id_val = $hotel_id[$i];
        //     $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' AND `status` = '1' AND `deleted` = '0' AND `hotel_id` = '$hotel_id_val' ") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        //     $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
        //     if ($total_numrows_count == 0) :
        //         $cancellation_policy_should_be_required[] = [
        //             'hotel_title' => getHOTEL_DETAIL($hotel_id_val, '', 'label'),
        //             'hotel_state_city' => getHOTEL_DETAIL($hotel_id_val, '', 'hotel_state_city')
        //         ];
        //     endif;
        // endfor;

        // // Check if any 'true' exists in the array
        // if (in_array(true, $cancellation_policy_should_be_required)) :
        //     $errors['cancellation_policy_should_be_required'] = true;
        // endif;

        // // Check if any true exists in the array and generate error messages
        // if (!empty($cancellation_policy_should_be_required)) {
        //     $errorMessages = array_map(function ($policy) {
        //         return "Hotel: <b>{$policy['hotel_title']} - {$policy['hotel_state_city']}</b>";
        //     }, $cancellation_policy_should_be_required);

        //     $errors['cancellation_policy_should_be_required'] = 'Please add at least one more cancellation policy !!! ' . '<br> ' . implode('<br> ', $errorMessages);
        // }


        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            for ($i = 0; $i < count($hotel_id); $i++) :
                $hotel_id_val = $hotel_id[$i];
                $itinerary_plan_hotel_details_ID_array = explode(',', $itinerary_plan_hotel_details_ID[$i]);
                $itinerary_route_date_array = explode(',', $hidden_itinerary_route_date[$i]);

                for ($j = 0; $j < count($itinerary_plan_hotel_details_ID_array); $j++):

                    $itinerary_plan_hotel_details_ID_val = $itinerary_plan_hotel_details_ID_array[$j];
                    $hidden_itinerary_route_date_val = $itinerary_route_date_array[$j];

                    $confirmed_by_val = $confirmed_by[$i];
                    $email_id_val = $email_id[$i];
                    $mobile_number_val = $mobile_number[$i];
                    $status_val = $status[$i];
                    $invoice_to_val = $invoice_to[$i];
                    $hotel_voucher_terms_condition_val = htmlspecialchars($hotel_voucher_terms_condition[$i], ENT_QUOTES, 'UTF-8');

                    $hotel_name = getHOTEL_DETAIL($hotel_id_val, '', 'label');
                    $hotel_address = getHOTEL_DETAIL($hotel_id_val, '', 'hotel_address');
                    $hotel_email = $email_id_val;

                    // Check if a record already exists
                    $existing_record_query = "SELECT `cnf_itinerary_plan_hotel_voucher_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `confirmed_itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID_val' AND `itinerary_plan_id` = '$hidden_itinerary_plan_id'";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;

                    if ($existing_record) :
                        // Update existing record
                        $updateFields = [
                            '`hotel_confirmed_by`',
                            '`hotel_confirmed_email_id`',
                            '`hotel_confirmed_mobile_no`',
                            '`hotel_booking_status`',
                            '`invoice_to`',
                            '`hotel_voucher_terms_condition`'
                        ];

                        $updateValues = [
                            "$confirmed_by_val",
                            "$email_id_val",
                            "$mobile_number_val",
                            "$status_val",
                            "$invoice_to_val",
                            "$hotel_voucher_terms_condition_val"
                        ];

                        $sqlWhere = "cnf_itinerary_plan_hotel_voucher_details_ID = '" . $existing_record['cnf_itinerary_plan_hotel_voucher_details_ID'] . "'";

                        if (!sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_hotel_voucher_details", $updateFields, $updateValues, $sqlWhere)) :
                            die("#UPDATE_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                        endif;
                    else :
                        // Insert new record
                        $arrFields = [
                            '`confirmed_itinerary_plan_hotel_details_ID`',
                            '`itinerary_plan_id`',
                            '`itinerary_route_date`',
                            '`hotel_id`',
                            '`hotel_confirmed_by`',
                            '`hotel_confirmed_email_id`',
                            '`hotel_confirmed_mobile_no`',
                            '`hotel_booking_status`',
                            '`invoice_to`',
                            '`hotel_voucher_terms_condition`',
                            '`createdby`',
                            '`status`'
                        ];

                        $arrValues = [
                            "$itinerary_plan_hotel_details_ID_val",
                            "$hidden_itinerary_plan_id",
                            "$hidden_itinerary_route_date_val",
                            "$hotel_id_val",
                            "$confirmed_by_val",
                            "$email_id_val",
                            "$mobile_number_val",
                            "$status_val",
                            "$invoice_to_val",
                            "$hotel_voucher_terms_condition_val",
                            "$logged_user_id",
                            '1'
                        ];

                        if (!sqlACTIONS("INSERT", "dvi_confirmed_itinerary_plan_hotel_voucher_details", $arrFields, $arrValues, '')) :
                            die("#INSERT_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                        endif;
                    endif;

                endfor;
		
                if ($status_val):
                    //CONFIRMATION EMAIL 
                    $confirmed_itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'itinerary_quote_ID');
                    $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_adult');
                    $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_children');
                    $total_infants = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_infants');
                    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'preferred_room_count');
                    $food_type_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'food_type');
                    $food_type = getFOODTYPE($food_type_id, 'label');
                    $billing_type = $invoice_to_val;
                    $hotel_status = getHOTEL_CONFIRM_STATUS($status_val, 'label');

                    if (count($itinerary_plan_hotel_details_ID_array) == 1):
                        //HOTEL ASSIGNED TO ONE DAY 
                        $get_room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val, 'get_room_type_id');
                        $check_in_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_id_val, $get_room_type_id, 'check_in_time')));
                        $check_out_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_id_val, $get_room_type_id, 'check_out_time')));
                        $check_in_date = date('M d, Y', strtotime($hidden_itinerary_route_date_val)) . ' ' . $check_in_time;
                        $check_out_date = date('M d, Y', strtotime($hidden_itinerary_route_date_val . ' +1 day')) . ' ' . $check_out_time;
                        $room_type_title = getROOMTYPE_DETAILS($get_room_type_id, 'room_type_title');

                        $mealplandetails = getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        /* if ($i == 0) :
                        $meal_plan_details = str_replace("Breakfast, ", "", $mealplandetails);
                    elseif ($i == (count($hotel_id) - 1)) :
                        $meal_plan_details = str_replace(", Dinner", "", $mealplandetails);
                    else :
                        $meal_plan_details = $mealplandetails;
                    endif; */
                        $meal_plan_details = $mealplandetails;

                        $roomDetails = getRoomDetails($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formatRoomDetails = formatRoomDetails($roomDetails);
                        $occupancyDetails = getOccupancyDetails($hidden_itinerary_plan_id, $hidden_itinerary_route_date_val);
                        $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);

                        // Set global variables      
                        global $confirmed_by_val, $confirmed_itinerary_quote_ID, $primary_customer_name, $hotel_name, $hotel_address, $check_in_date, $check_out_date, $room_type_title, $total_adult, $total_children, $total_infants, $preferred_room_count, $meal_plan_details, $formatRoomDetails, $travel_expert_name, $travel_expert_staff_email, $food_type, $billing_type, $hotel_status, $agent_company_name, $agent_invoice_address, $agent_invoice_gstin_no, $hidden_itinerary_plan_id, $itinerary_plan_hotel_details_ID_val, $hotel_email, $agent_email, $status_val;

                        // Assign values to global variables
                        $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_plan_id;
                        $_SESSION['global_confirmed_by_val'] = $confirmed_by_val;
                        $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                        $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                        $_SESSION['global_hotel_name'] = $hotel_name;
                        $_SESSION['global_hotel_status'] = $hotel_status;
                        $_SESSION['global_status_val'] = $status_val;
                        $_SESSION['global_hotel_address'] = $hotel_address;
                        $_SESSION['global_hotel_email'] = $hotel_email;
                        $_SESSION['global_check_in_date'] = $check_in_date;
                        $_SESSION['global_check_out_date'] = $check_out_date;
                        $_SESSION['global_room_type_title'] = $room_type_title;
                        $_SESSION['global_total_adult'] = $total_adult;
                        $_SESSION['global_total_children'] = $total_children;
                        $_SESSION['global_total_infants'] = $total_infants;
                        $_SESSION['global_preferred_room_count'] = $preferred_room_count;
                        $_SESSION['global_meal_plan_details'] = $meal_plan_details;
                        $_SESSION['global_formatRoomDetails'] = $formatRoomDetails;
                        $_SESSION['global_formattedoccupancyDetails'] = $formattedoccupancyDetails;
                        $_SESSION['global_travel_expert_name'] = $travel_expert_name;
                        $_SESSION['global_travel_expert_mobile'] = $travel_expert_staff_mobile;
                        $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;
                        $_SESSION['global_food_type'] = $food_type;
                        $_SESSION['global_billing_type'] = $billing_type;
                        $_SESSION['global_agent_company_name'] = $agent_company_name;
                        $_SESSION['global_agent_invoice_address'] = $agent_invoice_address;
                        $_SESSION['global_agent_invoice_gstin_no'] = $agent_invoice_gstin_no;
                        $_SESSION['global_itinerary_plan_hotel_details_ID_val'] = $itinerary_plan_hotel_details_ID_val;
                        $_SESSION['global_agent_email'] = $agent_email;

                        // Include the email notification script
                        include('ajax_hotel_voucher_confirmation_email_notification.php');

                        // Assign values to global variables
                        unset($_SESSION['global_hotel_status']);
                        unset($_SESSION['global_confirmed_by_val']);
                        unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                        unset($_SESSION['global_primary_customer_name']);
                        unset($_SESSION['global_hotel_name']);
                        unset($_SESSION['global_hotel_address']);
                        unset($_SESSION['global_check_in_date']);
                        unset($_SESSION['global_check_out_date']);
                        unset($_SESSION['global_room_type_title']);
                        unset($_SESSION['global_total_adult']);
                        unset($_SESSION['global_total_children']);
                        unset($_SESSION['global_total_infants']);
                        unset($_SESSION['global_preferred_room_count']);
                        unset($_SESSION['global_meal_plan_details']);
                        unset($_SESSION['global_formatRoomDetails']);
                        unset($_SESSION['global_formattedoccupancyDetails']);
                        unset($_SESSION['global_travel_expert_name']);
                        unset($_SESSION['global_travel_expert_mobile']);
                        unset($_SESSION['global_travel_expert_staff_email']);
                        unset($_SESSION['global_food_type']);
                        unset($_SESSION['global_billing_type']);
                        unset($_SESSION['global_hidden_itinerary_plan_id']);
                        unset($_SESSION['global_itinerary_plan_hotel_details_ID_val']);
                        unset($_SESSION['global_hotel_email']);
                        unset($_SESSION['global_status_val']);
                        unset($_SESSION['global_agent_email']);

                    else:

                        // Set global variables      
                        global $confirmed_by_val, $confirmed_itinerary_quote_ID, $primary_customer_name, $hotel_name, $hotel_address,  $total_adult, $total_children, $total_infants, $preferred_room_count,  $travel_expert_name, $travel_expert_staff_email, $food_type, $billing_type, $hotel_status, $agent_company_name, $agent_invoice_address, $agent_invoice_gstin_no, $hidden_itinerary_plan_id, $itinerary_plan_hotel_details_ID_val, $hotel_email, $agent_email, $status_val, $hidden_itinerary_route_date_val, $hotel_id_val;

                        // Assign values to global variables
                        $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_plan_id;
                        $_SESSION['global_confirmed_by_val'] = $confirmed_by_val;
                        $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                        $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                        $_SESSION['global_hotel_name'] = $hotel_name;
                        $_SESSION['global_hotel_status'] = $hotel_status;
                        $_SESSION['global_status_val'] = $status_val;
                        $_SESSION['global_hotel_address'] = $hotel_address;
                        $_SESSION['global_hotel_email'] = $hotel_email;
                        $_SESSION['global_total_adult'] = $total_adult;
                        $_SESSION['global_total_children'] = $total_children;
                        $_SESSION['global_total_infants'] = $total_infants;
                        $_SESSION['global_preferred_room_count'] = $preferred_room_count;
                        $_SESSION['global_travel_expert_name'] = $travel_expert_name;
                        $_SESSION['global_travel_expert_mobile'] = $travel_expert_staff_mobile;
                        $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;
                        $_SESSION['global_food_type'] = $food_type;
                        $_SESSION['global_billing_type'] = $billing_type;
                        $_SESSION['global_agent_company_name'] = $agent_company_name;
                        $_SESSION['global_agent_invoice_address'] = $agent_invoice_address;
                        $_SESSION['global_agent_invoice_gstin_no'] = $agent_invoice_gstin_no;
                        $_SESSION['global_agent_email'] = $agent_email;
                        $_SESSION['global_itinerary_plan_hotel_details_ID_val'] = $itinerary_plan_hotel_details_ID[$i];
                        $_SESSION['global_hidden_itinerary_route_date_val'] = $hidden_itinerary_route_date[$i];
                        $_SESSION['global_hotel_id_val'] = $hotel_id_val;

                        // Include the email notification script
                        include('ajax_hotel_voucher_confirmation_email_notification_for_more_days.php');

                        // Assign values to global variables
                        unset($_SESSION['global_hotel_status']);
                        unset($_SESSION['global_confirmed_by_val']);
                        unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                        unset($_SESSION['global_primary_customer_name']);
                        unset($_SESSION['global_hotel_name']);
                        unset($_SESSION['global_hotel_address']);
                        unset($_SESSION['global_total_adult']);
                        unset($_SESSION['global_total_children']);
                        unset($_SESSION['global_total_infants']);
                        unset($_SESSION['global_preferred_room_count']);
                        unset($_SESSION['global_travel_expert_name']);
                        unset($_SESSION['global_travel_expert_mobile']);
                        unset($_SESSION['global_travel_expert_staff_email']);
                        unset($_SESSION['global_food_type']);
                        unset($_SESSION['global_billing_type']);
                        unset($_SESSION['global_hidden_itinerary_plan_id']);
                        unset($_SESSION['global_itinerary_plan_hotel_details_ID_val']);
                        unset($_SESSION['global_hotel_email']);
                        unset($_SESSION['global_status_val']);
                        unset($_SESSION['global_agent_email']);
                        unset($_SESSION['global_hidden_itinerary_route_date_val']);
                        unset($_SESSION['global_hotel_id_val']);

                    endif;
                endif;

            endfor;

            $response['success'] = true;
        endif;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'show_cancellation_policy_form') :

        $plan_ID = $_GET['plan_ID'];
        $hotel_id = $_GET['hotel_id'];
        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
        $trip_start_date_and_time = (get_ITINEARY_CONFIRMED_PLAN_DETAILS($plan_ID, 'trip_start_date_and_time'));

    ?>
        <h5 class="modal-title text-center mb-3" id="cancellation_policyLabel">Add Cancellation Policy</h5>
        <form action="" method="post" id="add_cancellation_form" data-parsley-validate>
            <input type="hidden" name="hotel_id" id="hotel_id" value="<?= $hotel_id; ?>" hidden>
            <input type="hidden" name="plan_ID" id="plan_ID" value="<?= $plan_ID; ?>" hidden>
            <div class="col-md-12 mb-2">
                <label class="form-label" for="vendor">Hotel</label>
                <div class="form-group">
                    <input type="text" name="hotel_name" id="hotel_name" class="form-control" readonly value="<?= $hotel_name ?>" />
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label" for="cancellation_date">Cancellation Date<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="cancellation_date" id="cancellation_date" class="form-control" placeholder="DD/MM/YYYY" autocomplete="off" required />

                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label" for="cancellation_percentage">Cancellation Percentage<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="cancellation_percentage" data-parsley-trigger="keyup" id="cancellation_percentage" class="form-control" placeholder="Cancellation Percentage" max="100" data-parsley-max="100" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label" for="cancellation_description">Description (Optional)</label>
                <div class="form-group">
                    <textarea rows="3" id="cancellation_description" data-parsley-trigger="keyup" name="cancellation_description" placeholder="Enter the Description" class="form-control"></textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="save_submit" class="btn btn-success">Save & Add New</button>
                <button type="save_and_close_submit" class="btn btn-warning">Save & Close</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                var tripStartDate = '<?= date('d/m/Y', strtotime($trip_start_date_and_time)); ?>';
                // Initialize flatpickr for the cancellation date field
                flatpickr("#cancellation_date", {
                    enableTime: false,
                    dateFormat: "d/m/Y",
                    minDate: 'today',
                    maxDate: tripStartDate
                });

                // AJAX form submission for adding a cancellation policy
                $("#add_cancellation_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var plan_ID = $('#plan_ID').val();

                    var spinner = $('#spinner'); // Spinner element
                    var form = $(this)[0]; // Get the form element
                    var data = new FormData(form); // Create FormData object with form data

                    // Determine which button was clicked
                    var submitType = $(document.activeElement).attr('type');

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=add_cancellation_policy',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                        beforeSend: function() {
                            spinner.show(); // Show spinner before sending the request
                        },
                        complete: function() {
                            spinner.hide(); // Hide spinner after request completion
                        },
                        success: function(response) {
                            if (response.success) {
                                TOAST_NOTIFICATION(
                                    'success',
                                    'Cancellation Policy Added Successfully.',
                                    'Success !!!',
                                    '', '', '', '', '', '', '', '', ''
                                );

                                if (submitType === "save_submit") {
                                    form.reset();
                                } else if (submitType === "save_and_close_submit") {
                                    $('#showHOTELCANCELLATIONPOLICYFORMDATA').modal('hide');
                                }
                                showADDEDCANCELLATIONPOLICY(plan_ID);
                            } else {
                                if (response.errors && response.errors.cancellation_policy_should_be_required) {
                                    let errorMessage = response.errors.cancellation_policy_should_be_required;

                                    TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
                                } else {
                                    TOAST_NOTIFICATION(
                                        'error',
                                        'An error occurred while adding the cancellation policy.',
                                        'Error !!!',
                                        '', '', '', '', '', '', '', '', ''
                                    );
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error occurred: " + textStatus, errorThrown);
                            TOAST_NOTIFICATION(
                                'error',
                                'An unexpected error occurred. Please try again.',
                                'Error !!!',
                                '', '', '', '', '', '', '', '', ''
                            );
                        }
                    });
                });
            });

            function showADDEDCANCELLATIONPOLICY(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=get_added_cancellation_policy_response",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#load_ajax_response').html('');
                        $('#load_ajax_response').html(response);
                    }
                });
            }
        </script>
        <?php
    elseif ($_GET['type'] == 'add_cancellation_policy') :

        $response = [];
        $errors = [];

        $itinerary_plan_id = $_POST['plan_ID'];
        $hotel_id = $_POST['hotel_id'];
        $cancellation_date = dateformat_database($_POST['cancellation_date']);
        $cancellation_percentage = $_POST['cancellation_percentage'];
        $cancellation_description = $_POST['cancellation_description'];

        // Check if cancellation_descrption is blank and generate a default description if needed
        if (empty($cancellation_description)) :
            $cancellation_description = "Cancellation policy as of " . date('M d, Y', strtotime($cancellation_date));
        endif;

        // Prepare the field and value arrays for the insertion
        $arrFields = [
            'itinerary_plan_id',
            'hotel_id',
            'cancellation_descrption',
            'cancellation_date',
            'cancellation_percentage',
            'createdby',
            'status',
        ];

        $arrValues = [
            "$itinerary_plan_id",
            "$hotel_id",
            "$cancellation_description",
            "$cancellation_date",
            "$cancellation_percentage",
            "$logged_user_id",
            '1',
        ];

        // Check if a record with the same itinerary_plan_id and cancellation_date already exists
        $checkQuery = "SELECT COUNT(`cnf_itinerary_plan_hotel_cancellation_policy_ID`) AS count FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `cancellation_date` = '$cancellation_date' AND `hotel_id` = '$hotel_id' ";
        $checkResult = sqlQUERY_LABEL($checkQuery);
        $count = sqlFETCHARRAY_LABEL($checkResult)['count'];

        if ($count > 0) :
            // Update the existing record
            $updateFields = [
                'cancellation_descrption' => $cancellation_description,
                'cancellation_percentage' => $cancellation_percentage,
                'status' => '1',
            ];

            $updateValues = [];
            foreach ($updateFields as $field => $value) :
                $updateValues[] = "$field = '$value'";
            endforeach;

            $updateQuery = "UPDATE `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` SET " . implode(", ", $updateValues) . " WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `cancellation_date` = '$cancellation_date' AND `hotel_id` = '$hotel_id' ";

            if (sqlQUERY_LABEL($updateQuery)) :
                $response['success'] = true;
            else :
                $response['success'] = false;
                $response['message'] = "Error updating cancellation policy.";
            endif;
        else :
            // Insert the new record
            if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_plan_hotel_cancellation_policy", $arrFields, $arrValues, '')) :
                $response['success'] = true;
            else :
                $response['success'] = false;
                $response['message'] = "Error inserting cancellation policy.";
            endif;
        endif;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'get_added_cancellation_policy_response') :

        $_itinerary_plan_ID = trim($_POST['_itinerary_plan_ID']);

        $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID`, `cancellation_descrption`, `cancellation_date`, `cancellation_percentage`,`hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$_itinerary_plan_ID' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
        if ($total_numrows_count > 0) :
            while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                $counter++;
                $cnf_itinerary_plan_hotel_cancellation_policy_ID = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_hotel_cancellation_policy_ID'];
                $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                $hotel_id = $fetch_confirmed_itineary_cancellation_data['hotel_id'];
                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
        ?>
                <tr>
                    <td><?= $counter; ?></td>
                    <td><?= $hotel_name; ?></td>
                    <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                    <td><?= $cancellation_percentage . '%'; ?></td>
                    <td><?= $cancellation_descrption; ?></td>
                    <td>
                        <div><span class="cursor-pointer" onclick="deleteCANCELLATIONPOLICY('<?= $cnf_itinerary_plan_hotel_cancellation_policy_ID; ?>','<?= $_itinerary_plan_ID; ?>');"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span></div>
                    </td>
                </tr>
            <?php
            endwhile;
        else : ?>
            <tr>
                <td colspan="5" class="text-center">No more Cancellation Policy found !!!</td>
            </tr>
        <?php endif;

    elseif ($_GET['type'] == 'show_delete_cancellation_policy_form') :

        $response = [];
        $errors = [];

        $ID = $_GET['ID'];
        $plan_ID = $_GET['plan_ID'];

        ?>
        <div class="row">
            <div class="text-center">
                <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
            <p class="text-center">Do you really want to delete this record? <br /> This process cannot be undone.</p>
            <div class="text-center pb-0">
                <button type="button" class="btn btn-secondary" id="close_cancellation_charge_delete" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="confirmCANCELLATIONPOLICYDELETE('<?= $ID; ?>','<?= $plan_ID; ?>');" class="btn btn-danger">Delete</button>
            </div>
        </div>

        <script>
            function confirmCANCELLATIONPOLICYDELETE(ID, plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=confirm_delete_cancellation_policy",
                    data: {
                        ID: ID,
                        plan_ID: plan_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success == true) {
                            $('#close_cancellation_charge_delete').click();
                            showADDEDCANCELLATIONPOLICY(plan_ID);
                        }
                    }
                });
            }

            function showADDEDCANCELLATIONPOLICY(itinerary_plan_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=get_added_cancellation_policy_response",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                    },
                    success: function(response) {
                        $('#load_ajax_response').html('');
                        $('#load_ajax_response').html(response);
                    }
                });
            }
        </script>
<?php
    elseif ($_GET['type'] == 'confirm_delete_cancellation_policy') :

        $response = [];
        $errors = [];

        $ID = $_POST['ID'];
        $plan_ID = $_POST['plan_ID'];

        $sqlwhere = " `cnf_itinerary_plan_hotel_cancellation_policy_ID` = '$ID' ";

        //UPDATE ITINEARY VIA ROUTE DETAILS
        if (sqlACTIONS("DELETE", "dvi_confirmed_itinerary_plan_hotel_cancellation_policy", '', '', $sqlwhere)) :
            //SUCCESS
            $response['success'] = true;
        else :
            $response['success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotel_confirm') :

        $errors = [];
        $response = [];

        $reservation_no = trim($_POST['reservation_no']);
        $hotel_verified_by = trim($_POST['hotel_verified_by']);
        $hotel_mobile_no = trim($_POST['hotel_mobile_no']);
        $hotel_email = trim($_POST['hotel_email']);
        $hotel_booking_status = trim($_POST['hotel_booking_status']);
        $voucher_status_remarks = trim($_POST['voucher_status_remarks']);
        $itinerary_plan_hotel_details_ID  = trim($_POST['itinerary_plan_hotel_details_ID']);
        $itinerary_plan_hotel_details_ID_array = explode(',', $itinerary_plan_hotel_details_ID);

        if (empty($_POST['reservation_no'])) :
            $errors['hotel_reservation_no_required'] = true;
        elseif (empty($_POST['hotel_verified_by'])) :
            $errors['hotel_verified_by_required'] = true;
        elseif (empty($_POST['hotel_mobile_no'])) :
            $errors['hotel_mobile_no'] = true;
        elseif (empty($_POST['hotel_booking_status'])) :
            $errors['hotel_voucher_status_required'] = true;
        endif;

        if (!empty($errors)) :
            // error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
            for ($i = 0; $i < count($itinerary_plan_hotel_details_ID_array); $i++):
                $itinerary_plan_hotel_detailsID = $itinerary_plan_hotel_details_ID_array[$i];

                $arrFields = array('`hotel_confirmed_reservation`', '`hotel_confirmation_verified_by`', '`hotel_confirmation_verified_mobile_no`', '`hotel_confirmation_verified_email_id`', '`hotel_booking_status`', '`hotel_confirmation_status_remarks`');

                $arrValues = array("$reservation_no", "$hotel_verified_by", " $hotel_mobile_no", "$hotel_email", "$hotel_booking_status", "$voucher_status_remarks");

                $sqlWhere = " `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_detailsID' ";

                if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_hotel_voucher_details", $arrFields, $arrValues, $sqlWhere)) {
                    // UPDATE SUCCESSFUL
                    $response['result'] = true;
                    $response['redirect_URL'] = 'hotelconfirmationsuccess.php?itinerary_plan_hotel_details_ID=' . $itinerary_plan_hotel_detailsID;
                    $response['result_success'] = true;
                } else {
                    $response['result'] = false;
                    $response['result_success'] = false;
                }
            endfor;
            //Email send from  DVI to hotel regarding Booking details
            $hotel_booking_status_label = getHOTEL_CONFIRM_STATUS($hotel_booking_status, 'label');
            $hotel_id = get_ITINERARY_HOTEL_VOUCHER_DETAILS($itinerary_plan_hotel_detailsID, 'hotel_id');
            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
            $hotel_address = getHOTEL_DETAIL($hotel_id, '', 'hotel_address');
            $hotel_email = get_ITINERARY_HOTEL_VOUCHER_DETAILS($itinerary_plan_hotel_detailsID,  'hotel_confirmed_email_id');

            // Set global variables      
            global $g_reservation_no, $g_hotel_verified_by, $g_hotel_mobile_no, $g_hotel_name, $g_hotel_booking_status_label,  $g_hotel_email;

            $g_reservation_no = $reservation_no;
            $g_hotel_verified_by = $hotel_verified_by;
            $g_hotel_mobile_no = $hotel_mobile_no;
            $g_hotel_name = $hotel_name;
            $g_hotel_booking_status_label = $hotel_booking_status_label;
            $g_hotel_email = $hotel_email;

            // Assign values to global variables
            $_SESSION['global_hotel_name'] = $hotel_name;
            $_SESSION['global_g_hotel_booking_status_label'] = $g_hotel_booking_status_label;
            $_SESSION['global_g_hotel_mobile_no'] = $g_hotel_mobile_no;
            $_SESSION['global_g_hotel_verified_by'] = $g_hotel_verified_by;
            $_SESSION['global_g_reservation_no'] = $g_reservation_no;
            $_SESSION['global_g_hotel_email'] = $g_hotel_email;

            // Include the email notification script
            include('ajax_dvi_to_hotel_voucher_status_email_notification.php');

            // Assign values to global variables
            unset($_SESSION['global_hotel_name']);
            unset($_SESSION['global_g_hotel_booking_status_label']);
            unset($_SESSION['global_g_hotel_mobile_no']);
            unset($_SESSION['global_g_hotel_verified_by']);
            unset($_SESSION['global_g_reservation_no']);
            unset($_SESSION['global_g_hotel_email']);


            //Email send from hotel to DVI regarding Booking details
            // Set global variables      
            global $g_reservation_no, $g_hotel_verified_by, $g_hotel_mobile_no, $g_hotel_name, $g_hotel_booking_status_label,  $g_hotel_email;

            $g_reservation_no = $reservation_no;
            $g_hotel_verified_by = $hotel_verified_by;
            $g_hotel_mobile_no = $hotel_mobile_no;
            $g_hotel_name = $hotel_name;
            $g_hotel_booking_status_label = $hotel_booking_status_label;
            $g_hotel_email = $hotel_email;

            // Assign values to global variables
            $_SESSION['global_hotel_name'] = $hotel_name;
            $_SESSION['global_g_hotel_booking_status_label'] = $g_hotel_booking_status_label;
            $_SESSION['global_g_hotel_mobile_no'] = $g_hotel_mobile_no;
            $_SESSION['global_g_hotel_verified_by'] = $g_hotel_verified_by;
            $_SESSION['global_g_reservation_no'] = $g_reservation_no;
            $_SESSION['global_g_hotel_email'] = $g_hotel_email;

            // Include the email notification script
            include('ajax_hotel_to_dvi_voucher_status_email_notification.php');

            // Assign values to global variables
            unset($_SESSION['global_hotel_name']);
            unset($_SESSION['global_g_hotel_booking_status_label']);
            unset($_SESSION['global_g_hotel_mobile_no']);
            unset($_SESSION['global_g_hotel_verified_by']);
            unset($_SESSION['global_g_reservation_no']);
            unset($_SESSION['global_g_hotel_email']);

        endif;
        echo json_encode($response);

    endif;

else :
    echo json_encode(['success' => false, 'message' => 'Request Ignored']);
    exit;
endif;
?>