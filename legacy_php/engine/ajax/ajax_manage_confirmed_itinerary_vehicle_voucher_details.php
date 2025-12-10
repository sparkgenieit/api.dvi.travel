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
        $hidden_itinerary_plan_id = $_POST['hidden_itinerary_plan_id'];
        $hidden_itinerary_plan_vendor_eligible_ID = $_POST['itinerary_plan_vendor_eligible_ID'];
        $hidden_confirmed_itinerary_plan_vendor_eligible_ID = $_POST['hidden_confirmed_itinerary_plan_vendor_eligible_ID'];
        $vehicle_voucher_terms_condition = getGLOBALSETTING('vehicle_voucher_terms_condition');

        ob_start(); // Start output buffering
?>
        <h5 class="modal-title text-center mb-3">Create Vendor Voucher</h5>
        <form action="" method="post" id="confirm_vendor_voucher_creation_form" data-parsley-validate>
            <div class="border-bottom text-dark mb-4"></div>
            <div class="row">
                <?php
                for ($voucher_count = 0; $voucher_count < count($hidden_itinerary_plan_vendor_eligible_ID); $voucher_count++) :
                    $itinerary_plan_vendor_eligible_ID = $hidden_itinerary_plan_vendor_eligible_ID[$voucher_count];
                    $confirmed_itinerary_plan_vendor_eligible_ID = $hidden_confirmed_itinerary_plan_vendor_eligible_ID[$voucher_count];

                    if($itinerary_plan_vendor_eligible_ID != 0){
                        $filter_by = " AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'";
                    } else {
                        $filter_by = " AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$confirmed_itinerary_plan_vendor_eligible_ID' ";
                    }
                    
                    $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms`,`total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$hidden_itinerary_plan_id'  AND `cancellation_status`='0' AND `itineary_plan_assigned_status`='1' {$filter_by}") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                    $select_itinerary_plan_vendor_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_data);

                    if ($select_itinerary_plan_vendor_count > 0) :
                        while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_data)) :
                            $vehicle_type_id = $fetch_eligible_vendor_data['vehicle_type_id'];
                            $vendor_vehicle_type_id = $fetch_eligible_vendor_data['vendor_vehicle_type_id'];
                            $vendor_id = $fetch_eligible_vendor_data['vendor_id'];
                            $vehicle_orign = $fetch_eligible_vendor_data['vehicle_orign'];
                            $total_vehicle_qty = $fetch_eligible_vendor_data['total_vehicle_qty'];
                            $vehicle_id = $fetch_eligible_vendor_data['vehicle_id'];
                            $vendor_branch_id = $fetch_eligible_vendor_data['vendor_branch_id'];
                            $total_vehicle_qty = $fetch_eligible_vendor_data['total_vehicle_qty'];
                            $vehicle_grand_total = $fetch_eligible_vendor_data['vehicle_total_amount'];
                            $GRAND_TOTAL = $total_vehicle_qty * $vehicle_grand_total;

                            $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                            $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
                            $vendor_branch = getBranchLIST($vendor_branch_id, 'branch_label');
                            $vendor_email = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_email');

                        endwhile;
                    endif;

                    // Check if a record already exists
                    $existing_record_query = "SELECT `cnf_itinerary_plan_vehicle_voucher_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_plan_id`, `vehicle_type_id`, `vendor_id`, `vehicle_id`,`vendor_branch_id`,`vehicle_confirmed_by`, `vehicle_confirmed_email_id`, `vehicle_confirmed_mobile_no`, `vehicle_booking_status`,`invoice_to`,`vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE  itinerary_plan_id = '$hidden_itinerary_plan_id' {$filter_by} ";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;

                ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary m-0">Vehicle <?= $voucher_count + 1; ?> | [<?= $vehicle_type_title; ?> - <?= $vendor_name . " , " . $vendor_branch; ?>]</h5>
                        <button class="btn btn-label-primary" id="add_cancellation_btn" type="button" onclick="showVEHICLECANCELLATIONPOLICYFORM('<?= $hidden_itinerary_plan_id; ?>','<?= $vendor_id ?>','<?= $vendor_vehicle_type_id ?>')">+ Add Cancellation Policy</button>
                    </div>
                    <div class="col-12">
                        <div class="row  mt-3">
                            <input type="hidden" name="hidden_itinerary_plan_id" id="hidden_itinerary_plan_id" value="<?= $hidden_itinerary_plan_id; ?>" hidden>
                            <input type="hidden" name="itinerary_plan_vendor_eligible_ID[]" value="<?= $itinerary_plan_vendor_eligible_ID; ?>" hidden>
                            <input type="hidden" name="confirmed_itinerary_plan_vendor_eligible_ID[]" value="<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" hidden>
                            <input type="hidden" name="vehicle_type_id[]" value="<?= $vendor_vehicle_type_id; ?>" hidden>
                            <input type="hidden" name="vendor_id[]" value="<?= $vendor_id; ?>" hidden>
                            <input type="hidden" name="vendor_branch_id[]" value="<?= $vendor_branch_id; ?>" hidden>
                            <input type="hidden" name="total_vehicle_qty[]" value="<?= $total_vehicle_qty; ?>" hidden>
                            <input type="hidden" name="vehicle_grand_total[]" value="<?= $vehicle_grand_total; ?>" hidden>
                            <input type="hidden" name="GRAND_TOTAL[]" value="<?= $GRAND_TOTAL; ?>" hidden>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="confirmed_by_<?= $voucher_count; ?>">Confirmed By<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" data-parsley-trigger="keyup" required name="confirmed_by[]" id="confirmed_by_<?= $voucher_count; ?>" class="form-control required-field" placeholder="Confirmed By" autocomplete="off" value="<?= $existing_record ? $existing_record['vehicle_confirmed_by'] : ''; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="email_id_<?= $voucher_count; ?>">Email Id<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" required data-parsley-trigger="keyup" name="email_id[]" id="email_id_<?= $voucher_count; ?>" class="form-control required-field" placeholder="Email Id" autocomplete="off" value="<?= $existing_record ? $existing_record['vehicle_confirmed_email_id'] : $vendor_email; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="mobile_number_<?= $voucher_count; ?>">Mobile Number<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" required data-parsley-trigger="keyup" data-parsley-type="number" name="mobile_number[]" id="mobile_number_<?= $voucher_count; ?>" class="form-control required-field" placeholder="Mobile Number" autocomplete="off" value="<?= $existing_record ? $existing_record['vehicle_confirmed_mobile_no'] : ''; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="status_<?= $voucher_count; ?>">Status<span class="text-danger">*</span></label>
                                <select class="form-select" data-parsley-trigger="keyup" required name="status[]" id="status_<?= $voucher_count; ?>">
                                    <?= getHOTEL_CONFIRM_STATUS($existing_record ? $existing_record['vehicle_booking_status'] : '', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="invoice_to_<?= $voucher_count; ?>">Invoice To<span class="text-danger">*</span></label>
                                <select class="form-select" data-parsley-trigger="keyup" required name="invoice_to[]" id="invoice_to_<?= $voucher_count; ?>">
                                    <?= getHOTEL_INVOICE_TO($existing_record ? $existing_record['invoice_to'] : '', 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-9 mb-2">
                                <label class="form-label" for="vehicle_voucher_terms_condition">Vehicle Voucher Terms and Condition
                                    <span class="text-danger"> *</span>
                                </label>
                                <div class="form-group">
                                    <textarea rows="10" id="vehicle_voucher_terms_condition_<?= $voucher_count; ?>" name="vehicle_voucher_terms_condition[]" class="form-control vehicle_voucher_terms_condition" required>
                                        <?php
                                        echo  $existing_record ? html_entity_decode($existing_record['vehicle_voucher_terms_condition'], ENT_QUOTES, 'UTF-8') : $vehicle_voucher_terms_condition; ?>
                                    </textarea>
                                </div>
                            </div>

                        </div>
                        <div class="border-bottom border-bottom-dashed my-4"></div>
                    </div>
                <?php endfor; ?>
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
                                    <th>Vendor</th>
                                    <th>Vehicle Type</th>
                                    <th>Cancellation Date</th>
                                    <th>Cancellation (%)</th>
                                    <th>Description</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="load_ajax_response">
                                <?php
                                $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_cancellation_policy_ID`,`vendor_id`,`vendor_vehicle_type_id`, `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
                                if ($total_numrows_count > 0) :
                                    while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                                        $counter++;
                                        $cnf_itinerary_plan_vehicle_cancellation_policy_ID  = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_vehicle_cancellation_policy_ID'];
                                        $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                                        $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                                        $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                                        $vendor_id = $fetch_confirmed_itineary_cancellation_data['vendor_id'];
                                        $vendor_vehicle_type_id = $fetch_confirmed_itineary_cancellation_data['vendor_vehicle_type_id'];
                                        $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'label');
                                        $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
                                ?>
                                        <tr>
                                            <td><?= $counter; ?></td>
                                            <td><?= $vendor_name; ?></td>
                                            <td><?= $vehicle_type_title; ?></td>
                                            <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                                            <td><?= $cancellation_percentage . '%'; ?></td>
                                            <td><?= $cancellation_descrption; ?></td>
                                            <td>
                                                <div><span class="cursor-pointer" onclick="deleteCANCELLATIONPOLICY('<?= $cnf_itinerary_plan_vehicle_cancellation_policy_ID; ?>','<?= $hidden_itinerary_plan_id; ?>');"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span></div>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No more Cancellation Policy found !!!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="button" id="voucher_cancel_btn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" id="vehicle_voucher_submit_btn" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/ckeditor5.js"></script>

        <script>
            $(document).ready(function() {
                $('.form-select').selectize();

                $('.vehicle_voucher_terms_condition').each(function() {
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
                $("#confirm_vendor_voucher_creation_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var hidden_itinerary_plan_id = $('#hidden_itinerary_plan_id').val();
                    var spinner = $('#spinner');
                    var form = $(this)[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=create_voucher',
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
                                $('#showVENDORVOUCHERFORMDATA').modal('hide');
                                $('.modal-backdrop').remove();
                                TOAST_NOTIFICATION('success', 'Vehicle voucher Successfully Created and sent to Respective Vendor!!!', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);
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

            function showVEHICLECANCELLATIONPOLICYFORM(plan_ID, vendor_ID, vendor_vehicle_type_ID) {
                $('.receiving-confirm-vehiclecancellation-policy-form-data').load(
                    'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=show_cancellation_policy_form&plan_ID=' + plan_ID + '&vendor_ID=' + vendor_ID + '&vendor_vehicle_type_ID=' + vendor_vehicle_type_ID,
                    function() {
                        const container = document.getElementById("showVENDORCANCELLATIONPOLICYFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function deleteCANCELLATIONPOLICY(ID, plan_ID) {
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=show_delete_cancellation_policy_form&ID=' + ID + '&plan_ID=' + plan_ID,
                    function() {
                        const container = document.getElementById("VENDORMODALINFODATA");
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

        $itinerary_plan_vendor_eligible_ID = $_POST['itinerary_plan_vendor_eligible_ID'];
        $confirmed_itinerary_plan_vendor_eligible_ID = $_POST['confirmed_itinerary_plan_vendor_eligible_ID'];
        $vehicle_type_id = $_POST['vehicle_type_id'];
        $vendor_id = $_POST['vendor_id'];
        $vendor_branch_id = $_POST['vendor_branch_id'];
        $confirmed_by = $_POST['confirmed_by'];
        $email_id = $_POST['email_id'];
        $mobile_number = $_POST['mobile_number'];
        $status = $_POST['status'];
        $invoice_to = $_POST['invoice_to'];
        $vehicle_voucher_terms_condition = $_POST['vehicle_voucher_terms_condition'];
        $total_vehicle_qty = $_POST['total_vehicle_qty'];
        $vehicle_grand_total = $_POST['vehicle_grand_total'];
        $GRAND_TOTAL = $_POST['GRAND_TOTAL'];

        $primary_customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'primary_customer_name');
        $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_id, 'agent_id');
        $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
        $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
        $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');
        $agent_company_name = get_AGENT_CONFIG_DETAILS($agent_id, 'company_name');
        $agent_invoice_address = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_address');
        $agent_invoice_gstin_no = get_AGENT_CONFIG_DETAILS($agent_id, 'invoice_gstin_no');

        // Check if at least one cancellation policy exists
        // for ($i = 0; $i < count($vehicle_type_id); $i++) :
        //     $vehicle_type_id_val = $vehicle_type_id[$i];
        //     $vendor_id_val = $vendor_id[$i];

        //     $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_cancellation_policy_ID` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$hidden_itinerary_plan_id' AND `status` = '1' AND `deleted` = '0' AND `vendor_id` = '$vendor_id_val' AND `vendor_vehicle_type_id` = '$vehicle_type_id_val' ") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        //     $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);

        //     if ($total_numrows_count == 0):
        //         $cancellation_policy_should_be_required[] = [
        //             'vehicle_type_title' => getVENDOR_VEHICLE_TYPES($vendor_id_val, $vehicle_type_id_val, 'label'),
        //             'vendor_name' => getVENDOR_DETAILS($vendor_id_val, 'label')
        //         ];

        //     endif;

        // endfor;

        // Check if any true exists in the array and generate error messages
        // if (!empty($cancellation_policy_should_be_required)) {
        //     $errorMessages = array_map(function ($policy) {
        //         return "Vehicle Type: <b>{$policy['vehicle_type_title']}</b>, Vendor: <b>{$policy['vendor_name']}</b>";
        //     }, $cancellation_policy_should_be_required);

        //     $errors['cancellation_policy_should_be_required'] = 'Please add at least one more cancellation policy !!! ' . '<br> ' . implode('<br> ', $errorMessages);
        // }

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            for ($i = 0; $i < count($vehicle_type_id); $i++) :
                $itinerary_plan_vendor_eligible_ID_val = $itinerary_plan_vendor_eligible_ID[$i];
                $confirmed_itinerary_plan_vendor_eligible_ID_val = $confirmed_itinerary_plan_vendor_eligible_ID[$i];
                $vehicle_type_id_val = $vehicle_type_id[$i];
                $vendor_id_val = $vendor_id[$i];
                $vendor_branch_id_val = $vendor_branch_id[$i];

                $confirmed_by_val = $confirmed_by[$i];
                $email_id_val = $email_id[$i];
                $mobile_number_val = $mobile_number[$i];
                $status_val = $status[$i];
                $invoice_to_val = $invoice_to[$i];
                $vehicle_voucher_terms_condition_val = htmlspecialchars($vehicle_voucher_terms_condition[$i], ENT_QUOTES, 'UTF-8');

                $total_vehicle_qty_val = $total_vehicle_qty[$i];
                $vehicle_grand_total_val = $vehicle_grand_total[$i];
                $GRAND_TOTAL_val = $GRAND_TOTAL[$i];

                //$vehicle_type_title = getVEHICLETYPE($vehicle_type_id_val, 'get_vehicle_type_title');
                $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id_val, $vehicle_type_id_val, 'label');
                $vendor_name = getVENDOR_DETAILS($vendor_id_val, 'label');
                $vendor_branch = getBranchLIST($vendor_branch_id_val, 'branch_label');
                $vendor_address = getVENDORNAMEDETAIL($vendor_id_val, 'get_vendor_address');
                $vendor_email = $email_id_val;

                // Check if a record already exists
                $existing_record_query = "SELECT `cnf_itinerary_plan_vehicle_voucher_details_ID` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `confirmed_itinerary_plan_vendor_eligible_ID` = '$confirmed_itinerary_plan_vendor_eligible_ID_val' AND `itinerary_plan_id` = '$hidden_itinerary_plan_id'";
                $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;

                if ($existing_record) :
                    // Update existing record
                    $updateFields = [
                        '`vehicle_confirmed_by`',
                        '`vehicle_confirmed_email_id`',
                        '`vehicle_confirmed_mobile_no`',
                        '`vehicle_booking_status`',
                        '`invoice_to`',
                        '`vehicle_voucher_terms_condition`'
                    ];

                    $updateValues = [
                        "$confirmed_by_val",
                        "$email_id_val",
                        "$mobile_number_val",
                        "$status_val",
                        "$invoice_to_val",
                        "$vehicle_voucher_terms_condition_val"
                    ];

                    $sqlWhere = "cnf_itinerary_plan_vehicle_voucher_details_ID = '" . $existing_record['cnf_itinerary_plan_vehicle_voucher_details_ID'] . "'";

                    if (!sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vehicle_voucher_details", $updateFields, $updateValues, $sqlWhere)) :
                        die("#UPDATE_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                    endif;
                else :
                    // Insert new record
                    $arrFields = [
                        '`confirmed_itinerary_plan_vendor_eligible_ID`',
                        '`itinerary_plan_vendor_eligible_ID`',
                        '`itinerary_plan_id`',
                        '`vehicle_type_id`',
                        '`vendor_id`',
                        '`vendor_branch_id`',
                        '`vehicle_confirmed_by`',
                        '`vehicle_confirmed_email_id`',
                        '`vehicle_confirmed_mobile_no`',
                        '`vehicle_booking_status`',
                        '`invoice_to`',
                        '`vehicle_voucher_terms_condition`',
                        '`createdby`',
                        '`status`'
                    ];

                    $arrValues = [
                        "$confirmed_itinerary_plan_vendor_eligible_ID_val",
                        "$itinerary_plan_vendor_eligible_ID_val",
                        "$hidden_itinerary_plan_id",
                        "$vehicle_type_id_val",
                        "$vendor_id_val",
                        "$vendor_branch_id_val",
                        "$confirmed_by_val",
                        "$email_id_val",
                        "$mobile_number_val",
                        "$status_val",
                        "$invoice_to_val",
                        "$vehicle_voucher_terms_condition_val",
                        "$logged_user_id",
                        '1'
                    ];

                    if (!sqlACTIONS("INSERT", "dvi_confirmed_itinerary_plan_vehicle_voucher_details", $arrFields, $arrValues, '')) :
                        die("#INSERT_VOUCHER_DETAILS:" . sqlERROR_LABEL());
                    endif;
                endif;

                $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_adult');
                $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_children');
                $total_infants = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'total_infants');
                $vehicle_status = getHOTEL_CONFIRM_STATUS($status_val, 'label');
                $confirmed_itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_plan_id, 'itinerary_quote_ID');
                $billing_type = $invoice_to_val;

                //if ($status_val == 4) :

                // Set global variables      
                global $confirmed_by_val, $confirmed_itinerary_quote_ID, $primary_customer_name, $vendor_name, $vendor_address, $vehicle_type_title, $vendor_branch, $total_adult, $total_children, $total_infants, $travel_expert_name, $travel_expert_staff_email,  $billing_type, $vehicle_status, $agent_company_name, $agent_invoice_address, $agent_invoice_gstin_no, $hidden_itinerary_plan_id, $itinerary_plan_vendor_eligible_ID_val, $vendor_email, $agent_email, $status_val, $total_vehicle_qty_val, $vehicle_grand_total_val, $GRAND_TOTAL_val;

                // Assign values to global variables
                $_SESSION['global_total_vehicle_qty_val'] = $total_vehicle_qty_val;
                $_SESSION['global_vehicle_grand_total_val'] = $total_vehicle_qty_val . ' X ' . general_currency_symbol . ' ' . number_format($vehicle_grand_total_val, 2);
                $_SESSION['global_GRAND_TOTAL_val'] = general_currency_symbol . ' ' . number_format($GRAND_TOTAL_val, 2);
                $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_plan_id;
                $_SESSION['global_confirmed_by_val'] = $confirmed_by_val;
                $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                $_SESSION['global_vendor_name'] = $vendor_name;
                $_SESSION['global_vehicle_status'] = $vehicle_status;
                $_SESSION['global_status_val'] = $status_val;
                $_SESSION['global_vendor_address'] = $vendor_address;
                $_SESSION['global_vendor_email'] = $vendor_email;
                $_SESSION['global_vehicle_type_title'] = $vehicle_type_title;
                $_SESSION['global_vendor_branch'] = $vendor_branch;
                $_SESSION['global_total_adult'] = $total_adult;
                $_SESSION['global_total_children'] = $total_children;
                $_SESSION['global_total_infants'] = $total_infants;
                $_SESSION['global_travel_expert_name'] = $travel_expert_name;
                $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;
                $_SESSION['global_billing_type'] = $billing_type;
                $_SESSION['global_agent_company_name'] = $agent_company_name;
                $_SESSION['global_agent_invoice_address'] = $agent_invoice_address;
                $_SESSION['global_agent_invoice_gstin_no'] = $agent_invoice_gstin_no;
                $_SESSION['global_itinerary_plan_vendor_eligible_ID_val'] = $itinerary_plan_vendor_eligible_ID_val;
                $_SESSION['global_agent_email'] = $agent_email;

                // Include the email notification script
                include('ajax_vehicle_voucher_confirmation_email_notification.php');

                // Assign values to global variables
                unset($_SESSION['global_vehicle_status']);
                unset($_SESSION['global_confirmed_by_val']);
                unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                unset($_SESSION['global_primary_customer_name']);
                unset($_SESSION['global_vendor_name']);
                unset($_SESSION['global_vendor_address']);
                unset($_SESSION['global_total_adult']);
                unset($_SESSION['global_total_children']);
                unset($_SESSION['global_total_infants']);
                unset($_SESSION['global_travel_expert_name']);
                unset($_SESSION['global_travel_expert_staff_email']);
                unset($_SESSION['global_billing_type']);
                unset($_SESSION['global_hidden_itinerary_plan_id']);
                unset($_SESSION['global_itinerary_plan_vendor_eligible_ID_val']);
                unset($_SESSION['global_vendor_email']);
                unset($_SESSION['global_status_val']);
                unset($_SESSION['global_total_vehicle_qty_val']);
                unset($_SESSION['global_GRAND_TOTAL_val']);
                unset($_SESSION['global_total_vehicle_qty_val']);
                unset($_SESSION['global_vehicle_status']);
                unset($_SESSION['global_status_val']);
                unset($_SESSION['global_vehicle_type_title']);
                unset($_SESSION['global_vendor_branch']);
                unset($_SESSION['global_agent_email']);

            // endif;
            endfor;

            $response['success'] = true;
        endif;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'show_cancellation_policy_form') :

        $plan_ID = $_GET['plan_ID'];
        $vendor_ID = $_GET['vendor_ID'];
        $vendor_vehicle_type_ID = $_GET['vendor_vehicle_type_ID'];
        $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_ID, $vendor_vehicle_type_ID, 'label');
        $vendor_name = getVENDOR_DETAILS($vendor_ID, 'label');
        $trip_start_date_and_time = (get_ITINEARY_CONFIRMED_PLAN_DETAILS($plan_ID, 'trip_start_date_and_time'));

    ?>
        <h5 class="modal-title text-center mb-3" id="cancellation_policyLabel">Add Cancellation Policy</h5>
        <form action="" method="post" id="add_cancellation_form" data-parsley-validate>
            <input type="hidden" name="plan_ID" id="plan_ID" value="<?= $plan_ID; ?>" hidden>
            <input type="hidden" name="vendor_ID" id="vendor_ID" value="<?= $vendor_ID; ?>" hidden>
            <input type="hidden" name="vendor_vehicle_type_ID" id="vendor_vehicle_type_ID" value="<?= $vendor_vehicle_type_ID; ?>" hidden>

            <div class="col-md-12 mb-2">
                <label class="form-label" for="vendor">Vendor</label>
                <div class="form-group">
                    <input type="text" name="vendor_name" id="vendor_name" class="form-control" readonly value="<?= $vendor_name ?>" />
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label" for="cancellation_percentage">Vehicle Type</label>
                <div class="form-group">
                    <input type="text" name="vehicle_type_title" id="vehicle_type_title" class="form-control" readonly value="<?= $vehicle_type_title; ?>" required />
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
                <button type="vehicle_save_submit" class="btn btn-success">Save & Add New</button>
                <button type="vehicle_save_and_close_submit" class="btn btn-warning">Save & Close</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                var tripStartDate = '<?= date('d/m/Y',  strtotime($trip_start_date_and_time)); ?>';

                // Initialize flatpickr for the cancellation date field
                flatpickr("#cancellation_date", {
                    enableTime: false,
                    dateFormat: "d/m/Y",
                    minDate: 'today',
                    //maxDate: tripStartDate
                });

                // AJAX form submission for adding a cancellation policy
                $("#add_cancellation_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var plan_ID = $('#plan_ID').val();

                    var vendor_ID = $('#vendor_ID').val();
                    var vendor_vehicle_type_ID = $('#vendor_vehicle_type_ID').val();

                    var spinner = $('#spinner'); // Spinner element
                    var form = $(this)[0]; // Get the form element
                    var data = new FormData(form); // Create FormData object with form data

                    // Determine which button was clicked
                    var submitType = $(document.activeElement).attr('type');

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=add_cancellation_policy',
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
                                showADDEDCANCELLATIONPOLICY(plan_ID);
                                TOAST_NOTIFICATION(
                                    'success',
                                    'Cancellation Policy Added Successfully.',
                                    'Success !!!',
                                    '', '', '', '', '', '', '', '', ''
                                );

                                if (submitType === "vehicle_save_submit") {
                                    form.reset();
                                } else if (submitType === "vehicle_save_and_close_submit") {
                                    $("#showVENDORCANCELLATIONPOLICYFORMDATA").hide();
                                }


                            } else {
                                if (response.errors.cancellation_policy_should_be_required) {
                                    TOAST_NOTIFICATION(
                                        'error',
                                        'Please add at least one more cancellation policy.',
                                        'Error !!!',
                                        '', '', '', '', '', '', '', '', ''
                                    );
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
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=get_added_cancellation_policy_response",
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
        $vendor_ID = $_POST['vendor_ID'];
        $vendor_vehicle_type_ID = $_POST['vendor_vehicle_type_ID'];

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
            'vendor_id',
            'vendor_vehicle_type_id',
            'cancellation_descrption',
            'cancellation_date',
            'cancellation_percentage',
            'createdby',
            'status',
        ];

        $arrValues = [
            "$itinerary_plan_id",
            "$vendor_ID",
            "$vendor_vehicle_type_ID",
            "$cancellation_description",
            "$cancellation_date",
            "$cancellation_percentage",
            "$logged_user_id",
            '1',
        ];

        // Check if a record with the same itinerary_plan_id and cancellation_date already exists
        $checkQuery = "SELECT COUNT(`cnf_itinerary_plan_vehicle_cancellation_policy_ID`) AS count FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `cancellation_date` = '$cancellation_date' AND `vendor_id` = '$vendor_ID' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID'";
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

            $updateQuery = "UPDATE `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` SET " . implode(", ", $updateValues) . " WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `cancellation_date` = '$cancellation_date' AND `vendor_id` = '$vendor_ID' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID'";

            if (sqlQUERY_LABEL($updateQuery)) :
                $response['success'] = true;
            else :
                $response['success'] = false;
                $response['message'] = "Error updating cancellation policy.";
            endif;
        else :
            // Insert the new record
            if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_plan_vehicle_cancellation_policy", $arrFields, $arrValues, '')) :
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

        $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_cancellation_policy_ID`, `cancellation_descrption`,`vendor_id`,`vendor_vehicle_type_id`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$_itinerary_plan_ID' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
        if ($total_numrows_count > 0) :
            while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                $counter++;
                $cnf_itinerary_plan_vehicle_cancellation_policy_ID = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_vehicle_cancellation_policy_ID'];
                $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                $vendor_id = $fetch_confirmed_itineary_cancellation_data['vendor_id'];
                $vendor_vehicle_type_id = $fetch_confirmed_itineary_cancellation_data['vendor_vehicle_type_id'];
                $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'label');
                $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
        ?>
                <tr>
                    <td><?= $counter; ?></td>
                    <td><?= $vendor_name; ?></td>
                    <td><?= $vehicle_type_title; ?></td>
                    <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                    <td><?= $cancellation_percentage . '%'; ?></td>
                    <td><?= $cancellation_descrption; ?></td>
                    <td>
                        <div><span class="cursor-pointer" onclick="deleteCANCELLATIONPOLICY('<?= $cnf_itinerary_plan_vehicle_cancellation_policy_ID; ?>','<?= $_itinerary_plan_ID; ?>');"><i class="ti-sm ti ti-trash mb-1 ms-2"></i></span></div>
                    </td>
                </tr>
            <?php
            endwhile;
        else : ?>
            <tr>
                <td colspan="4" class="text-center">No more Cancellation Policy found !!!</td>
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
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=confirm_delete_cancellation_policy",
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
                    url: "engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=get_added_cancellation_policy_response",
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

        $sqlwhere = " `cnf_itinerary_plan_vehicle_cancellation_policy_ID` = '$ID' ";

        //UPDATE ITINEARY VIA ROUTE DETAILS
        if (sqlACTIONS("DELETE", "dvi_confirmed_itinerary_plan_vehicle_cancellation_policy", '', '', $sqlwhere)) :
            //SUCCESS
            $response['success'] = true;
        else :
            $response['success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vehicle_confirm') :

        $errors = [];
        $response = [];

        $reservation_no = trim($_POST['reservation_no']);
        $vehicle_verified_by = trim($_POST['vehicle_verified_by']);
        $vehicle_mobile_no = trim($_POST['vehicle_mobile_no']);
        $vehicle_email = trim($_POST['vehicle_email']);
        $vehicle_booking_status = trim($_POST['vehicle_booking_status']);
        $voucher_status_remarks = trim($_POST['voucher_status_remarks']);
        $itinerary_plan_vehicle_details_ID  = trim($_POST['itinerary_plan_vehicle_details_ID']);
        $hidden_itinerary_plan_ID  = trim($_POST['hidden_itinerary_plan_id']);
        $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_plan_ID, 'agent_id');
        $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');

        if (empty($_POST['reservation_no'])) :
            $errors['vehicle_reservation_no_required'] = true;
        elseif (empty($_POST['vehicle_verified_by'])) :
            $errors['vehicle_verified_by_required'] = true;
        elseif (empty($_POST['vehicle_mobile_no'])) :
            $errors['vehicle_mobile_no'] = true;
        elseif (empty($_POST['vehicle_booking_status'])) :
            $errors['vehicle_voucher_status_required'] = true;
        endif;

        if (!empty($errors)) :
            // error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`vehicle_confirmed_reservation`', '`vehicle_confirmation_verified_by`', '`vehicle_confirmation_verified_mobile_no`', '`vehicle_confirmation_verified_email_id`', '`vehicle_booking_status`', '`vehicle_confirmation_status_remarks`');

            $arrValues = array("$reservation_no", "$vehicle_verified_by", " $vehicle_mobile_no", "$vehicle_email", "$vehicle_booking_status", "$voucher_status_remarks");

            $sqlWhere = " `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vehicle_details_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vehicle_voucher_details", $arrFields, $arrValues, $sqlWhere)) {
                // UPDATE SUCCESSFUL
                $response['result'] = true;
                $response['redirect_URL'] = 'vehicleconfirmationsuccess.php?itinerary_plan_vehicle_details_ID=' . $itinerary_plan_vehicle_details_ID;
                $response['result_success'] = true;

                //Email send from  DVI to vehicle regarding Booking details

                $vehicle_booking_status_label = getHOTEL_CONFIRM_STATUS($vehicle_booking_status, 'label');
                $vendor_id = get_ITINERARY_VEHICLE_VOUCHER_DETAILS($itinerary_plan_vehicle_details_ID, 'vendor_id');
                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid'); //vendor name
                $vehicle_type_id = get_ITINERARY_VEHICLE_VOUCHER_DETAILS($itinerary_plan_vehicle_details_ID, 'vehicle_type_id');
                $vehicle_type = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); //vehicle type
                $vendor_email = get_ITINERARY_VEHICLE_VOUCHER_DETAILS($itinerary_plan_vehicle_details_ID, 'vehicle_confirmed_email_id');

                // Set global variables      
                global $g_reservation_no, $g_vehicle_verified_by, $g_vehicle_mobile_no, $g_vendor_name, $g_vehicle_type, $g_vehicle_booking_status_label,  $g_vendor_email,  $g_travel_expert_staff_email;

                $g_reservation_no = $reservation_no;
                $g_vehicle_verified_by = $vehicle_verified_by;
                $g_vehicle_mobile_no = $vehicle_mobile_no;
                $g_vendor_name = $vendor_name;
                $g_vehicle_type = $vehicle_type;
                $g_vehicle_booking_status_label = $vehicle_booking_status_label;
                $g_vendor_email = $vendor_email;
                $g_travel_expert_staff_email = $travel_expert_staff_email;

                // Assign values to global variables
                $_SESSION['global_vendor_name'] = $vendor_name;
                $_SESSION['global_vehicle_type'] = $vehicle_type;
                $_SESSION['global_g_vehicle_booking_status_label'] = $g_vehicle_booking_status_label;
                $_SESSION['global_g_vehicle_mobile_no'] = $g_vehicle_mobile_no;
                $_SESSION['global_g_vehicle_verified_by'] = $g_vehicle_verified_by;
                $_SESSION['global_g_reservation_no'] = $g_reservation_no;
                $_SESSION['global_g_vendor_email'] = $g_vendor_email;
                $_SESSION['global_g_travel_expert_staff_email'] = $g_travel_expert_staff_email;

                // Include the email notification script
                // include('ajax_dvi_to_vehicle_voucher_status_email_notification.php');

                // Assign values to global variables
                unset($_SESSION['global_vendor_name']);
                unset($_SESSION['global_vehicle_type']);
                unset($_SESSION['global_g_vehicle_booking_status_label']);
                unset($_SESSION['global_g_vehicle_mobile_no']);
                unset($_SESSION['global_g_vehicle_verified_by']);
                unset($_SESSION['global_g_reservation_no']);
                unset($_SESSION['global_g_vendor_email']);
                unset($_SESSION['global_g_travel_expert_staff_email']);


                //Email send from vehicle to DVI regarding Booking details
                // Set global variables      
                global $g_reservation_no, $g_vehicle_verified_by, $g_vehicle_mobile_no, $g_vendor_name, $g_vehicle_type, $g_vehicle_booking_status_label, $g_vendor_email,  $g_travel_expert_staff_email;

                $g_reservation_no = $reservation_no;
                $g_vehicle_verified_by = $vehicle_verified_by;
                $g_vehicle_mobile_no = $vehicle_mobile_no;
                $g_vendor_name = $vendor_name;
                $g_vehicle_type = $vehicle_type;
                $g_vehicle_booking_status_label = $vehicle_booking_status_label;
                $g_vendor_email = $vendor_email;
                $g_travel_expert_staff_email = $travel_expert_staff_email;

                // Assign values to global variables
                $_SESSION['global_vendor_name'] = $vendor_name;
                $_SESSION['global_vehicle_type'] = $vehicle_type;
                $_SESSION['global_g_vehicle_booking_status_label'] = $g_vehicle_booking_status_label;
                $_SESSION['global_g_vehicle_mobile_no'] = $g_vehicle_mobile_no;
                $_SESSION['global_g_vehicle_verified_by'] = $g_vehicle_verified_by;
                $_SESSION['global_g_reservation_no'] = $g_reservation_no;
                $_SESSION['global_g_vendor_email'] = $g_vendor_email;
                $_SESSION['global_g_travel_expert_staff_email'] = $g_travel_expert_staff_email;

                // Include the email notification script
                include('ajax_vehicle_to_dvi_voucher_status_email_notification.php');

                // Assign values to global variables
                unset($_SESSION['global_vendor_name']);
                unset($_SESSION['global_vehicle_type']);
                unset($_SESSION['global_g_vehicle_booking_status_label']);
                unset($_SESSION['global_g_vehicle_mobile_no']);
                unset($_SESSION['global_g_vehicle_verified_by']);
                unset($_SESSION['global_g_reservation_no']);
                unset($_SESSION['global_g_vendor_email']);
                unset($_SESSION['global_g_travel_expert_staff_email']);
            } else {
                $response['result'] = false;
                $response['result_success'] = false;
            }

        endif;
        echo json_encode($response);
    endif;

else :
    echo json_encode(['success' => false, 'message' => 'Request Ignored']);
    exit;
endif;
?>