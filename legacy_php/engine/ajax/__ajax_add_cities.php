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

    if ($_GET['type'] == 'show_form') :

        $CITY_ID = $_GET['CITY_ID'];

        if ($CITY_ID != '' && $CITY_ID != 0) :
            $select_cities_details = sqlQUERY_LABEL("SELECT `id`, `state_id`, `name`FROM `dvi_cities` WHERE `id` = '$CITY_ID'") or die("#1-UNABLE_TO_COLLECT_CITIES_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_cities_details)) :
                $CITY_ID = $fetch_data['id'];
                $state_id = $fetch_data['state_id'];
                $city_name = $fetch_data['name'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <style>
            .suggestions-list {
                border: 1px solid #ccc;
                max-height: 125px;
                overflow-y: auto;
                display: none;
                position: absolute;
                /* Ensures it stays under the input field */
                background-color: white;
                z-index: 1000;
                /* Higher z-index to stay above content but below buttons */
                width: 100%;
                top: 100%;
                /* Position below the input field */
                left: 0;
            }

            .suggestions-list ul {
                list-style-type: none;
                padding-left: 0;
                margin: 0;
            }

            .suggestions-list li {
                padding: 10px;
                cursor: not-allowed;
                /* Show it's not selectable */
            }

            .suggestions-list li:hover {
                background-color: #f0f0f0;
            }

            /* Add some z-index to buttons to stay above the suggestions */
            .form-group .btn {
                z-index: 2000;
                /* Ensure buttons appear above suggestions */
                position: relative;
            }
        </style>
        <form id="ajax_city_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="CITYFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12">
                <label class="form-label w-100" for="country_name">County Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="country_name" name="country_name" class="form-select" required disabled>
                        <?= getCOUNTRYLIST(101, 'select_country'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="state_name">State Name<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="state_name" name="state_name" class="form-select" required>
                        <?= getSTATELIST(101, $state_id, 'select_state'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="city_name">City Name<span class=" text-danger"> *</span></label>
                <div class="form-group position-relative">

                    <input type="text" id="city_name" name="city_name" class="form-control" placeholder="Enter the City Name" value="<?= $city_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_city_name data-parsley-check_city_name-message="City Name Already Exists" autocomplete="off" data-parsley-trigger="keyup" />
                    <div id="city_suggestions" class="suggestions-list"></div>
                    <input type="hidden" name="old_city_name" id="old_city_name" value="<?= $city_name; ?>" />
                    <input type="hidden" name="hiddenCITY_ID" id="hiddenCITY_ID" value="<?= $CITY_ID; ?>" hidden />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn hotel_category_add_form" id="city_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#city_name, #state_name').bind('keyup', function() {
                if (allFilled()) $('#city_form_submit_btn').removeAttr('disabled');
            });


            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }


            $(document).ready(function() {
                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                $('#country_name').selectize();
                $('#state_name').selectize();
                $('#city_name').on('keyup', function() {
                    var cityName = $(this).val();
                    var stateName = $('#state_name').val(); // Ensure state is selected

                    if (cityName.length >= 2) { // Start showing suggestions after 2 characters
                        $.ajax({
                            url: 'engine/ajax/__ajax_fetch_cities.php',
                            method: 'POST',
                            data: {
                                state_name: stateName,
                                term: cityName
                            },
                            dataType: 'json',
                            success: function(data) {
                                var suggestions = '';
                                if (data.length > 0) {
                                    $.each(data, function(index, city) {
                                        suggestions += '<li>' + city.name + '</li>';
                                    });
                                } else {
                                    $('#city_suggestions').hide(); // Hide suggestions if no data returned
                                }
                                $('#city_suggestions').html('<ul>' + suggestions + '</ul>').show();
                            }
                        });
                    } else {
                        $('#city_suggestions').hide();
                    }
                });

                // Hide suggestions when clicking outside
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('#city_name').length) {
                        $('#city_suggestions').hide();
                    }
                });
                //CHECK DUPLICATE City Name
                $('#city_name').parsley();
                var old_city_nameDETAIL = document.getElementById("old_city_name").value;
                var city_name = $('#city_name').val();
                window.ParsleyValidator.addValidator('check_city_name', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_city.php',
                            method: "POST",
                            data: {
                                city_name: value,
                                state_name: $('#state_name').val(),
                                old_city_name: old_city_nameDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                // Event listener for when the state changes
                // $('#state_name').on('change', function() {
                //     $('#city_name').parsley().reset(); // Reset validation when the state changes
                //     $('#city_name')[0].selectize.clearOptions(); // Clear selectize options
                //     $('#city_name').parsley().validate(); // Optionally re-validate after reset
                // });

                //AJAX FORM SUBMIT
                $("#ajax_city_details_form").submit(function(event) {
                    var form = $('#ajax_city_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_cities.php?type=add',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            if (response.errors.city_name_required) {
                                TOAST_NOTIFICATION('warning', 'City Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                                $('#city_name').focus();
                            } else if (response.errors.city_name_already_exist) {
                                TOAST_NOTIFICATION('warning', 'City Name Already Exist', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                                $('#city_name').focus();
                            } else if (response.errors.state_name_required) {
                                TOAST_NOTIFICATION('warning', 'State Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                                $('#state_name').focus();
                            } else if (response.errors.state_name_already_exist) {
                                TOAST_NOTIFICATION('warning', 'State Name Already Exist', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                                $('#state_name').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();

                            if (!response.result) {
                                //NOT SUCCESS RESPONSE
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                //SUCCESS RESPOSNE
                                $('#ajax_city_details_form')[0].reset();
                                $('#addCITYFORM').modal('hide');
                                $('#cities_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Submitted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>