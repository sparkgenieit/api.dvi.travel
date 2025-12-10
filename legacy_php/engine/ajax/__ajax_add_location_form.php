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

    if ($_GET['type'] == 'preview') :

        $location_ID = $_POST['ID'];

        $select_hotspot_query = sqlQUERY_LABEL("SELECT `location_ID`, `source_location`, `source_location_lattitude`, `source_location_longitude`, `source_location_city`, `destination_location`, `destination_location_lattitude`, `destination_location_longitude`, `destination_location_city`, `distance`, `duration` FROM `dvi_stored_locations` WHERE `deleted` = '0' and `location_ID` = '$location_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
            $counter++;
            $location_ID = $fetch_list_data['location_ID'];
            $source_location = $fetch_list_data['source_location'];
            $source_location_lattitude = $fetch_list_data['source_location_lattitude'];
            $source_location_longitude = $fetch_list_data['source_location_longitude'];
            $source_location_city = $fetch_list_data['source_location_city'];
            $destination_location = $fetch_list_data['destination_location'];
            $destination_location_lattitude = $fetch_list_data['destination_location_lattitude'];
            $destination_location_longitude = $fetch_list_data['destination_location_longitude'];
            $destination_location_city = $fetch_list_data['destination_location_city'];
            $distance = $fetch_list_data['distance'];
            $duration = $fetch_list_data['duration'];
        endwhile;
?>

        <div class="card p-4">
            <div class="row">
                <h4 class="text-primary">Location Details</h4>
                <div class="col-md-3">
                    <label>Source</label>
                    <p class="text-light">
                        <?= $source_location ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source Latitude</label>
                    <p class="text-light">
                        <?= $source_location_lattitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source Longitude </label>
                    <p class="text-light">
                        <?= $source_location_longitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Source City</label>
                    <p class="text-light">
                        <?= $source_location_city ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination</label>
                    <p class="text-light">
                        <?= $destination_location ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination Latitude</label>
                    <p class="text-light">
                        <?= $destination_location_lattitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination Longitude </label>
                    <p class="text-light">
                        <?= $destination_location_longitude ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Destination City</label>
                    <p class="text-light">
                        <?= $destination_location_city ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Distance</label>
                    <p class="text-light">
                        <?= $distance ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Duration</label>
                    <p class="text-light">
                        <?= $duration ?>
                    </p>
                </div>
            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 text-center p-2">
                    <h5 class="p-1"><strong>Add Via</strong></h5>
                    <div class="operating-hours-entry">
                        <div class="time-fields" id="time-fields-1">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label" for="via_location">Via Location<span class="text-danger"> *</span></label>
                                        <input id="via_location" name="via_location" class="form-control" type="text" placeholder="Select Via Location" required value="" />
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addOperatingTime(this, '1')">
                                        Add More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center p-2">
                    <h5 class="p-1"><strong>List Of Via</strong></h5>
                    <table class="table text-center">
                        <thead class="text-center">
                            <tr>
                                <th class="text-start">Location</th>
                                <th>Latitude</th>
                                <th>Longtitude</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="table-default">
                                <td class="text-start ">
                                    <p class="fw-medium">
                                        Kalpakkam
                                    </p>
                                </td>
                                <td>
                                    <p class="badge bg-label-primary mt-1 mb-1">9.65786</p>
                                </td>
                                <td>
                                    <p class="badge bg-label-primary mt-1 mb-1">19.65786</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-5">
                        <div class="text-end">
                            <a href="newhotspot.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function addOperatingTime(button, day_text) {
                var parentElement = button.parentElement;

                // Get the grandparent element (parent of the parent)
                var grandparentElement = parentElement.parentNode;

                // Find the last .operating-hours-entry element within the parent element
                var operatingHoursEntry = grandparentElement.querySelector('.operating-hours-entry:last-child');


                var time_filed_count = $('.operating-hours-entry .time-fields#time-fields-' + day_text).length++;
                // Append the cloned time-fields to the operatingHoursEntry
                //operatingHoursEntry.parentNode.insertBefore(newTimeFields, operatingHoursEntry.nextSibling);
                operatingHoursEntry.insertAdjacentHTML('beforeend', '<div class="time-fields" id="time-fields-' + day_text + '"><div class="row"><div class="col"><div class="form-group"><label class="form-label" for="via_location">Via Location<span class="text-danger"> *</span></label><input id="via_location" name="via_location[' + day_text + '][' + time_filed_count + ']" class="form-control" type="text" placeholder="Select Via Location" required value="" /></div></div><div class="col"><button type="button" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addOperatingTime(this, 1)">Add More</button></div></div></div>');
            }
        </script>


<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>