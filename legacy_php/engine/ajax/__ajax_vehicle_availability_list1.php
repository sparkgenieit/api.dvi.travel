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

?>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card p-0">
                <div class="card-header pb-3 d-flex justify-content-between">

                    <div class="col-md-5">
                        <h5 class="card-title mb-3 mt-2">Vehicle Availability Chart</h5>
                    </div>
                    <div class="col-md-auto text-end">
                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showADDVEHICLEMODAL();" data-bs-dismiss="modal">+ Add New Vehicle</a>
                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showADDDRIVERMODAL();" data-bs-dismiss="modal">+ Add New Driver</a>
                    </div>
                </div>


                <?php
                // Get the first and last day of the current month using strtotime
                $date_from = date('Y-m-01');
                $date_to = date('Y-m-t');
                $startOfMonth = strtotime(date('Y-m-01'));  // First day of the current month
                $endOfMonth = strtotime(date('Y-m-t'));     // Last day of the current month

                // Create an array to hold all the dates of the month
                $dates = [];
                $currentDate = $startOfMonth;

                while ($currentDate <= $endOfMonth) {
                    // Format each date as 'Y-m-d' for easier comparison
                    $dates[] = date('Y-m-d', $currentDate);
                    // Move to the next day
                    $currentDate = strtotime('+1 day', $currentDate);
                }
                ?>


                <div class="card-body" id="vehicle_availability_list">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table id="vehicle-availability-table">
                            <thead>
                                <tr>
                                    <th scope="col">Vendor</th>
                                    <th scope="col">Vehicle Type</th>
                                    <!-- Loop through PHP array and display date headers -->
                                    <?php foreach ($dates as $date): ?>
                                        <th><?php echo date('d-M Y', strtotime($date)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="even">
                                    <td class="dtfc-fixed-left" style="left: 0px; position: sticky;">VSR-CHENNAI</td>
                                    <td class="dtfc-fixed-left" style="left: 125.844px; position: sticky;">Sedan <br><span class="text-blue-color"> TN10BW8382</span></td>
                                    <td class="arrival-vehicle">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="text-blue-color mb-1 d-flex gap-2">
                                                    <a href="latestconfirmeditinerary.php?route=add&amp;formtype=generate_itinerary&amp;id=751" target="_blank">
                                                        CQ-DVI202409-018
                                                    </a>
                                                    <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                        <i class="ti ti-calendar-event text-body ti-sm"></i> 08:00 AM
                                                    </span>
                                                </h6>
                                                <h5 class="mb-0"><span class="badge badge-primary trip-badge" style="background-color: #89c76f;">Trip 1</span></h5>
                                            </div>
                                            <h6 class="text-dark mb-2">Guest : Mr.saran 9889899993</h6>
                                            <h6 class="text-dark mb-2">Chennai, Tamil Nadu, India =&gt; Chennai, Tamil Nadu, India</h6>
                                            <div class="d-flex">
                                                <div>
                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px"> - Manoj-9898989899 </h6>
                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                </div>
                                                <span class="cursor-pointer" onclick="editDRIVERMODAL(751,24,3)">
                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="text-blue-color mb-1 d-flex gap-2">
                                                <a href="latestconfirmeditinerary.php?route=add&amp;formtype=generate_itinerary&amp;id=751" target="_blank">
                                                    CQ-DVI202409-017
                                                </a>
                                                <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                    <i class="ti ti-calendar-event text-body ti-sm"></i> 08:00 AM
                                                </span>                                                
                                            </h6>
                                            <h5 class="mb-0"><span class="badge badge-primary trip-badge" style="background-color: #89c76f;">Trip 2</span></h5>
                                            </div>
                                            <h6 class="text-dark mb-2">Guest : Mr.saran 9889899993</h6>
                                            <h6 class="text-dark mb-2">Chennai, Tamil Nadu, India =&gt; Chennai, Tamil Nadu, India</h6>
                                            <div class="d-flex">
                                                <div>
                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px"> - Manoj-9898989899 </h6>
                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                </div>
                                                <span class="cursor-pointer" onclick="editDRIVERMODAL(751,24,3)">
                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="arrival-vehicle">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="text-blue-color mb-1 d-flex gap-2">
                                                    <a href="latestconfirmeditinerary.php?route=add&amp;formtype=generate_itinerary&amp;id=751" target="_blank">
                                                        CQ-DVI202409-018
                                                    </a>
                                                    <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                        <i class="ti ti-calendar-event text-body ti-sm"></i> 08:00 AM
                                                    </span>
                                                </h6>
                                            </div>
                                            <h6 class="text-dark mb-2">Guest : Mr.saran 9889899993</h6>
                                            <h6 class="text-dark mb-2">Chennai, Tamil Nadu, India =&gt; Chennai, Tamil Nadu, India</h6>
                                            <div class="d-flex">
                                                <div>
                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px"> - Manoj-9898989899 </h6>
                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                </div>
                                                <span class="cursor-pointer" onclick="editDRIVERMODAL(751,24,3)">
                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="even">
                                    <td class="dtfc-fixed-left" style="left: 0px; position: sticky;">VSR-CHENNAI</td>
                                    <td class="dtfc-fixed-left" style="left: 125.844px; position: sticky;">Sedan <br><span class="text-blue-color"> TN10BW8382</span></td>
                                    <td></td>
                                    <td class="arrival-vehicle">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="text-blue-color mb-1 d-flex gap-2">
                                                    <a href="latestconfirmeditinerary.php?route=add&amp;formtype=generate_itinerary&amp;id=751" target="_blank">
                                                        CQ-DVI202409-018
                                                    </a>
                                                    <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                        <i class="ti ti-calendar-event text-body ti-sm"></i> 08:00 AM
                                                    </span>
                                                </h6>
                                            </div>
                                            <h6 class="text-dark mb-2">Guest : Mr.saran 9889899993</h6>
                                            <h6 class="text-dark mb-2">Chennai, Tamil Nadu, India =&gt; Chennai, Tamil Nadu, India</h6>
                                            <div class="d-flex">
                                                <div>
                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px"> - Manoj-9898989899 </h6>
                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                </div>
                                                <span class="cursor-pointer" onclick="editDRIVERMODAL(751,24,3)">
                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="even">
                                    <td class="dtfc-fixed-left" style="left: 0px; position: sticky;">VSR-CHENNAI</td>
                                    <td class="dtfc-fixed-left" style="left: 125.844px; position: sticky;">Sedan <br><span class="text-blue-color"> TN10BW8382</span></td>
                                    <td></td>
                                    <td></td>
                                    <td class="arrival-vehicle">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="text-blue-color mb-1 d-flex gap-2">
                                                    <a href="latestconfirmeditinerary.php?route=add&amp;formtype=generate_itinerary&amp;id=751" target="_blank">
                                                        CQ-DVI202409-018
                                                    </a>
                                                    <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                        <i class="ti ti-calendar-event text-body ti-sm"></i> 08:00 AM
                                                    </span>
                                                </h6>
                                            </div>
                                            <h6 class="text-dark mb-2">Guest : Mr.saran 9889899993</h6>
                                            <h6 class="text-dark mb-2">Chennai, Tamil Nadu, India =&gt; Chennai, Tamil Nadu, India</h6>
                                            <div class="d-flex">
                                                <div>
                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px"> - Manoj-9898989899 </h6>
                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                </div>
                                                <span class="cursor-pointer" onclick="editDRIVERMODAL(751,24,3)">
                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="even">
                                    <td class="dtfc-fixed-left" style="left: 0px; position: sticky;">VSR-CHENNAI</td>
                                    <td class="dtfc-fixed-left" style="left: 125.844px; position: sticky;">Sedan <br><span class="text-blue-color"> TN10BW8382</span></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <link rel="stylesheet" href="assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css">
                <script src="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.min.js"></script>

            <?php
        endif;

            ?>