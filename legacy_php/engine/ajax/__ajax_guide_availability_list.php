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

    <div class="row">
        <div class="col-md-12">
            <div class="card p-0">
                <div class="card-header pb-3 d-flex justify-content-between">

                    <div class="col-md-5">
                        <h5 class="card-title mb-3 mt-2">Guide Availability Chart</h5>
                    </div>
                    <div class="col-md-auto text-end">
                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect">+ Add Guide</a>
                    </div>
                </div>

                <div class="card  bg-transparent border border-primary mb-3 mx-4">
                    <div class="card-body p-3">
                        <h5 class="card-title">Filter</h5>
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="date_from">Date from<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="date_from" name="date_from" value="" required />
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="date_to">Date from<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="date_to" name="date_to" value="" required />
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="filter_guide">Vendor <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="filter_guide" id="filter_guide" class="form-select form-control" required>
                                        <?= getGUIDEDETAILS('', 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="filter_guide">Guide Slot <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="filter_guide" id="filter_guide" class="form-select form-control" required>
                                        <?= getGUIDEDETAILS('', 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label" for="filter_location"> Location <span class="text-danger">*</span></label>
                                <!--<input type="text" name="source_location" id="source_location" class="form-control">-->
                                <div class="form-group">
                                    <select name="filter_location" id="filter_location" class="form-select form-control filter_select" required>
                                        <?= getSOURCE_LOCATION_DETAILS($selected_value, 'select_source'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <a href="vehicle_availability_chart.php" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table>
                            <thead>
                                <tr>
                                    <th class="sticky-col" scope="col">Guide Name</th>
                                    <th scope="col">Sep 01,2024</th>
                                    <th scope="col">Sep 02,2024</th>
                                    <th scope="col">Sep 03,2024</th>
                                    <th scope="col">Sep 04,2024</th>
                                    <th scope="col">Sep 05,2024</th>
                                    <th scope="col">Sep 06,2024</th>
                                    <th scope="col">Sep 07,2024</th>
                                    <th scope="col">Sep 08,2024</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="sticky-col fw-bold">Saran </br><span class="text-blue-color">97858558588</span></td>
                                    <td></td>
                                    <td class="not-assign-vehicle">
                                        <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2 mb-1" data-bs-toggle="modal" data-bs-target="#assignvehicle"><i class="ti ti-plus fw-bold fs-6 me-1"></i>Assign Vehicle</button>
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-002 </h6>
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 </h6>
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-011 </h6>
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-023 </h6>
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-007 </h6>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td class="not-assign-vehicle">
                                        <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2" data-bs-toggle="modal" data-bs-target="#assignvehicleNOTMULTIPLE"><i class="ti ti-plus fw-bold fs-6 me-1"></i>Assign Vehicle</button>
                                        <h6 class="text-blue-color mb-1">CQ-DVI202409-004</h6>
                                    </td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                <tr>
                                    <td class="sticky-col">Saran - 97858558588 Travels</td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                        <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>

                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Thiruvarur => Kumbakonam</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Kumbakonam => Kumbakonam</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                        <h6 class="text-dark mb-2">trichy => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky-col">Uma Travels</td>
                                    <td></td>
                                    <td></td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                        <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>

                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="inbetween-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                        <h6 class="text-dark mb-2">trichy => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sticky-col">Saran - 97858558588 Travels</td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                        <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>

                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Thiruvarur => Kumbakonam</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Kumbakonam => Kumbakonam</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="completed-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                        <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="arrival-deparure-vehicle">
                                        <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                        <h6 class="text-dark mb-2">trichy => trichy</h6>
                                        <div class="d-flex">
                                            <div>
                                                <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                            </div>
                                            <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adddriver" tabindex="-1" aria-labelledby="adddriverLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Add Driver</h4>
                    <form id="adddriver_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vendor_name">Vendor<span class=" text-danger">
                                    *</span></label>
                            <select id="vendor_name" name="vendor_name" required class="form-control form-select">
                                <?= getVENDOR_DETAILS('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger">
                                    *</span></label>
                            <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                                <?= getVEHICLETYPE('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="driver-text-label w-100" for="driver_name">Driver Name<span class=" text-danger">
                                    *</span></label>
                            <div class="form-group">
                                <input type="text" name="driver_name" id="driver_name" placeholder="Driver Name" value="Kumar" required="" autocomplete="off" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="driver-text-label w-100" for="driver_primary_mobile_number">Primary Mobile
                                Number<span class=" text-danger">*</span></label>
                            <div class="form-group">
                                <input type="tel" id="driver_primary_mobile_number" name="driver_primary_mobile_number" class="form-control parsley-success" placeholder="Primary Mobile Number" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_driver_primary_number="" data-parsley-check_driver_primary_number-message="Entered Mobile Number Already Exists" autocomplete="off" required="" maxlength="10" data-parsley-id="17">
                                <input type="hidden" name="old_driver_primary_mobile_number" id="old_driver_primary_mobile_number" data-parsley-type="number">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addvehicle" tabindex="-1" aria-labelledby="addvehicleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Add Vehicle</h4>
                    <form id="addvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger">
                                    *</span></label>
                            <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                                <?= getVEHICLETYPE('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="registration_number">Registration Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Registration Number" value="" required="" data-parsley-check_registration_number="" data-parsley-check_registration_number-message="Entered Registration Number Already Exists" data-parsley-pattern="^[A-Z]{2}\s?[0-9]{1,2}\s?[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{0,1}[0-9]{0,4}$">

                                <input type="hidden" name="old_registration_number" id="old_registration_number" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="chassis_number">Vehicle Origin <span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="vehicle_orign" id="vehicle_orign" class="form-control" placeholder="Choose Vehicle Origin" value="" required="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
							<label class="form-label" for="vehicle_fc_expiry_date">Vehicle Expiry Date <span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="vehicle_fc_expiry_date" id="vehicle_fc_expiry_date" class="form-control flatpickr-input" placeholder="Vehicle Expiry Date" value="" required="" readonly="readonly">
							</div>
						</div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="insurance_start_date">Insurance Start Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_start_date" id="insurance_start_date" class="form-control flatpickr-input" placeholder="Insurance Start Date" value="" required="" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="insurance_end_date">Insurance End Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_end_date" id="insurance_end_date" class="form-control flatpickr-input" placeholder="Insurance End Date" value="" required="" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignvehicle" tabindex="-1" aria-labelledby="assignvehicleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Code ID<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Code ID</option>
                                <option value="1">CQ-DVI202409-002</option>
                                <option value="2">CQ-DVI202409-003</option>
                                <option value="3">CQ-DVI202409-011</option>
                                <option value="4">CQ-DVI202409-023</option>
                                <option value="5">CQ-DVI202409-007</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam</option>
                                <option value="2">G. Pavithren</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignvehicleNOTMULTIPLE" tabindex="-1" aria-labelledby="assignvehicleNOTMULTIPLELabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam</option>
                                <option value="2">G. Pavithren</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editassign" tabindex="-1" aria-labelledby="editassignLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam - 9656565666</option>
                                <option value="2">G. Pavithren - 98989565665</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            
                            <button type="submit" class="btn btn-success">Re-Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $("#filterButton").click(function() {
                $("#filterDiv").toggleClass("d-none");
            });

        });
    </script>
<?php
endif;

?>