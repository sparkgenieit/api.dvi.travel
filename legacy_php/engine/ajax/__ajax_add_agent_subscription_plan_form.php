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

    if ($_GET['type'] == 'basic_info') :

        $hidden_agent_subscription_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hidden_agent_subscription_ID != '' && $hidden_agent_subscription_ID != 0) :
            $select_agent_subscription_plan_list_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `additional_charge_for_per_staff`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `per_itinerary_cost`, `validity_in_days`, `subscription_notes` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' and `agent_subscription_plan_ID` = '$hidden_agent_subscription_ID'") or die("#1-UNABLE_TO_COLLECT_AGENT_SUBSCRIPTION_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_agent_subscription_plan_list_query)) :
                $agent_subscription_plan_title = $fetch_list_data['agent_subscription_plan_title'];
                $itinerary_allowed = $fetch_list_data['itinerary_allowed'];
                $subscription_type = $fetch_list_data['subscription_type'];
                $subscription_amount = $fetch_list_data['subscription_amount'];
                $joining_bonus = $fetch_list_data['joining_bonus'];
                $staff_count = $fetch_list_data["staff_count"];
                $per_itinerary_cost = $fetch_list_data['per_itinerary_cost'];
                $validity_in_days = $fetch_list_data["validity_in_days"];
                $subscription_notes = $fetch_list_data["subscription_notes"];
                $additional_charge_for_per_staff = $fetch_list_data["additional_charge_for_per_staff"];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;

?>
        <!-- STEPPER -->
        <form id="agent_subscription_plan_form" action="" method="POST" data-parsley-validate>
            <input type="hidden" name="hidden_agent_subscription_ID" value="<?= $hidden_agent_subscription_ID ?>" />
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="agent_subscription_plan_title">Subscription Title<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="agent_subscription_plan_title" id="agent_subscription_plan_title" class="form-control" placeholder="Enter the Subscription Title" value="<?= $agent_subscription_plan_title; ?>" data-parsley-check_agent_subscription_plan_title data-parsley-check_agent_subscription_plan_title-message="Subscription Title Already Exists" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required autocomplete="off" />
                                    <input type="hidden" name="old_agent_subscription_plan_title" id="old_agent_subscription_plan_title" value="<?= $agent_subscription_plan_title; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="subscription_type">Type<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="subscription_type" name="subscription_type" class="form-select" required>
                                        <?= getSUBSCRIBE_Details($subscription_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="subscription_amount_container">
                                <label class="form-label" for="subscription_amount">Subscription Amonut<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="subscription_amount" id="subscription_amount" class="form-control" placeholder="Enter the Subscription Cost" value="<?= $subscription_amount; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="itinerary_allowed">No of Itinerary Allowed<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="itinerary_allowed" id="itinerary_allowed" class="form-control" placeholder="Enter the No of Itinerary Allowed" value="<?= $itinerary_allowed; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="per_itinerary_cost">Cost of Per Itinerary<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="per_itinerary_cost" id="per_itinerary_cost" class="form-control" placeholder="Enter the Cost of Per Itinerary" value="<?= $per_itinerary_cost; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="joining_bonus">Joining Bonus<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="joining_bonus" id="joining_bonus" class="form-control" placeholder="Enter the Joining Bonus" value="<?= $joining_bonus; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validity_in_days">Validity in days<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="validity_in_days" id="validity_in_days" class="form-control" value="<?= $validity_in_days; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="admin_count">Admin Count</label>
                                <div class="form-group">
                                    <input type="text" name="admin_count" id="admin_count" class="form-control" value="1" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="staff_count">Staff Count<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="staff_count" id="staff_count" class="form-control" placeholder="Enter the Staff Count" value="<?= $staff_count; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="additional_charge_for_per_staff">Additional Charge for Per Staff<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="additional_charge_for_per_staff" id="additional_charge_for_per_staff" class="form-control" placeholder="Enter the Additional Charge for Per Staff" value="<?= $additional_charge_for_per_staff; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label" for="subscription_notes">Notes<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <textarea rows="10" id="subscription_notes" name="subscription_notes" class="form-control" required><?= $subscription_notes ?> </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class=" mt-5">
                <div class="d-flex justify-content-between py-3">
                    <div>
                        <a href="agent_subscription_plan.php" class="btn btn-secondary">Back</a>
                    </div>
                    <button type="submit" id="submit_agent_subscription_plan_btn" class="btn btn-primary btn-md"><?= $btn_label; ?></button>
                </div>
            </div>
        </form>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            if (typeof CKEDITOR === 'undefined') {
                console.error('CKEditor not found. Please check the script source.');
            } else {
                CKEDITOR.ClassicEditor.create(document.getElementById("subscription_notes"), {
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
                    mention: {
                        feeds: [{
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
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
                    $('#update_submit_global_setting_btn').on('click', function() {
                        editor.updateSourceElement();
                        $('#subscription_notes').parsley().validate();

                        if ($('#subscription_notes').parsley().isValid()) {
                            // Form submission logic
                        } else {
                            // Handle validation errors
                        }
                    });
                }).catch(err => {
                    console.error(err.stack);
                });
            }

            $(document).ready(function() {
                $('#agent_subscription_plan_title').parsley();
                var old_agent_subscription_plan_titleDETAIL = document.getElementById("old_agent_subscription_plan_title").value;
                var agent_subscription_plan_title = $('#agent_subscription_plan_title').val();
                window.ParsleyValidator.addValidator('check_agent_subscription_plan_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_agent_subscription_plan_title.php',
                            method: "POST",
                            data: {
                                agent_subscription_plan_title: value,
                                old_agent_subscription_plan_title: old_agent_subscription_plan_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });
            });

            $(document).ready(function() {
                $(".form-select").selectize();

                function toggleSubscriptionCost() {
                    if ($('#subscription_type').val() == 1) {
                        $('#subscription_amount_container').show();
                        $('#subscription_amount').attr('required', 'required');
                        $('#subscription_amount').attr('min', '1');
                        $('#subscription_amount').attr('data-parsley-min', '1');
                    } else {
                        $('#subscription_amount_container').hide();
                        $('#subscription_amount').removeAttr('required');
                        $('#subscription_amount').removeAttr('min');
                        $('#subscription_amount').removeAttr('data-parsley-min');
                    }
                }

                // Initial check
                toggleSubscriptionCost();

                // Check on change
                $('#subscription_type').change(function() {
                    toggleSubscriptionCost();
                });

                //AJAX FORM SUBMIT
                $("#agent_subscription_plan_form").submit(function(event) {

                    var form = $('#agent_subscription_plan_form')[0];
                    var data = new FormData(form);
                    //  $(this).find("button[id='submit_agent_subscription_plan_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_agent_subscription_plan.php?type=basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.agent_subscription_plan_title) {
                                TOAST_NOTIFICATION('warning', 'Subscription Title Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.subscription_type) {
                                TOAST_NOTIFICATION('warning', 'Type Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_allowed) {
                                TOAST_NOTIFICATION('warning', 'No of Itinerary Allowed Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.per_itinerary_cost) {
                                TOAST_NOTIFICATION('warning', 'Cost of Per Itinerary Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.joining_bonus) {
                                TOAST_NOTIFICATION('warning', 'Joining Bonus Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.validity_in_days) {
                                TOAST_NOTIFICATION('warning', 'Validity in days Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.staff_count) {
                                TOAST_NOTIFICATION('warning', 'Staff Count Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.additional_charge_for_per_staff) {
                                TOAST_NOTIFICATION('warning', 'Additional Charge for Per Staff Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.subscription_notes) {
                                TOAST_NOTIFICATION('warning', 'Subscription Notes Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Subscription Added Successfully', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Subscription Updated Successfully', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Subscription',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Subscription',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });
        </script>
    <?php elseif ($_GET['type'] == 'sub_plan_info') :

        $get_agent_subscription_ID  = $_POST['ID'];

        $select_subscription_plan_details_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `additional_charge_for_per_staff`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `per_itinerary_cost`, `validity_in_days`, `subscription_notes` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' and `agent_subscription_plan_ID` = '$get_agent_subscription_ID'") or die("#1-UNABLE_TO_COLLECT_AGENT_SUBSCRIPTION_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_subscription_plan_details_query)) :
            $agent_subscription_plan_title = $fetch_list_data['agent_subscription_plan_title'];
            $itinerary_allowed = $fetch_list_data['itinerary_allowed'];
            $subscription_type = $fetch_list_data['subscription_type'];
            $subscription_type = getSUBSCRIPTION_REGISTRATION($subscription_type, 'subscription_type');
            $additional_charge_for_per_staff = $fetch_list_data["additional_charge_for_per_staff"];
            $subscription_amount = $fetch_list_data['subscription_amount'];
            $joining_bonus = $fetch_list_data['joining_bonus'];
            $staff_count = $fetch_list_data["staff_count"];
            $per_itinerary_cost = $fetch_list_data['per_itinerary_cost'];
            $validity_in_days = $fetch_list_data["validity_in_days"];
            $subscription_notes = $fetch_list_data["subscription_notes"];
        endwhile;
    ?>

        <div class="card p-4">
            <div class="row">
                <h5 class="text-primary">Subscription Plan Details</h5>
                <div class="col-md-3">
                    <label>Subscription Plan Title</label>
                    <p class="text-light">
                        <?= $agent_subscription_plan_title; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>itinerary Allowed</label>
                    <p class="text-light">
                        <?= $itinerary_allowed; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Subscription Type</label>
                    <p class="text-light">
                        <?= $subscription_type; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Subscription Amount </label>
                    <p class="text-light">
                        <?= general_currency_symbol; ?><?= number_format($subscription_amount, 2); ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Joining Bonus</label>
                    <p class="text-light">
                        <?= general_currency_symbol; ?><?= number_format($joining_bonus, 2); ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Staff Count</label>
                    <p class="text-light">
                        <?= $staff_count; ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Per Itinerary Cost</label>
                    <p class="text-light">
                        <?= general_currency_symbol; ?><?= number_format($per_itinerary_cost, 2); ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Validity in days</label>
                    <p class="text-light">
                        <?= $validity_in_days; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Additional Charge For Per Staff</label>
                    <p class="text-light">
                        <?= general_currency_symbol; ?><?= number_format($additional_charge_for_per_staff, 2); ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <label>Subscription Notes</label>
                    <div class="form-group mt-2">
                        <textarea rows="10" id="subscription_notes" name="subscription_notes" class="form-control"><?= $subscription_notes ?> </textarea>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between py-3">
                    <div>
                        <a href="agent_subscription_plan.php" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
        <script src="assets/js/ui-carousel.js"></script>
        <script>
            if (typeof CKEDITOR === 'undefined') {
                console.error('CKEditor not found. Please check the script source.');
            } else {
                CKEDITOR.ClassicEditor.create(document.getElementById("subscription_notes"), {
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
                    mention: {
                        feeds: [{
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
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
                    editor.enableReadOnlyMode('subscription_notes');

                    const toolbarElement = editor.ui.view.toolbar.element;
                    toolbarElement.style.display = 'none';
                }).catch(err => {
                    console.error(err.stack);
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>