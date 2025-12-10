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

?>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-0">
                    <div class="card-header pb-3 d-flex justify-content-between">
                        <div class="col-md-auto">
                            <h5 class="card-title mb-3 mt-2">List of Daily Moment Details</h5>
                        </div>
                    </div>

                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="travelexpert_dailymoment_LIST">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Action</th>
                                        <th>Booking Id</th>
                                        <th>Guest Name</th>
                                        <th>Source</th>
                                        <th>Destination</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#travelexpert_dailymoment_LIST').DataTable({
                    dom: 'Blfrtip',
                    "bFilter": true,
                    buttons: [{
                            extend: 'copy',
                            text: window.copyButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
                            }
                        },
                        {
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
                            }
                        },
                        {
                            extend: 'csv',
                            text: window.csvButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
                            }
                        }
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                        $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                        $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');


                    },
                    ajax: {
                        "url": "engine/json/__JSONtravelexpertmoment.php",
                        "type": "GET"
                    },
                    columns: [{
                            data: "counter"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "itinerary_plan_ID"
                        }, //2
                        {
                            data: "guest_name"
                        }, //3
                        {
                            data: "arrival_location"
                        }, //4
                        {
                            data: "departure_location"
                        }, //5
                        {
                            data: "days"
                        } //6
                    ],
                    columnDefs: [{
                            "targets": 2,
                            "data": "quote_id",
                            "render": function(data, type, row, full) {
                                return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                                    data + '" target="_blank" style="margin-right: 10px;">' + row.quote_id +
                                    '</a>';

                            }
                        },
                        {
                            "targets": 1,
                            "data": "modify",
                            "render": function(data, type, row, full) {

                                if (row.GUIDE_REQUIRED == data) {
                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="dailymoment.php?formtype=day_list&id=' + data + '" style="margin-right: 3px;"><span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g data-name="13-car"><path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path><path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path></g></g></svg></span> </a><a class="btn btn-sm btn-icon text-danger flex-end" href="dailymoment.php?formtype=day_list_guide&id=' + data + '" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="22px" x="0" y="0" viewBox="0 0 510 510" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m201.419 302.352-.951 3.955L255 343.627l54.516-37.315-.935-3.961c-33.671 16.875-73.493 16.873-107.162.001zM255 0c-52.806 0-87.355 39.182-94.654 90h189.309C342.355 39.182 307.806 0 255 0zM360 120H150l-30 45h270zM225 450h60v60h-60zM240 368.012l-79.234-53.047C104.751 317.19 60 363.311 60 419.88v90h60V450h30v59.88h45V420h45zM349.237 314.965 270 368.01V420h45v89.88h45V450h30v59.88h60v-90c0-56.45-44.626-102.679-100.763-104.915zM345 195H165c0 49.706 40.294 90 90 90s90-40.294 90-90z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path></g></svg></span></a></div>';
                                } else {
                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="dailymoment.php?formtype=day_list&id=' + data + '" style="margin-right: 3px;"><span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g data-name="13-car"><path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path><path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path></g></g></svg></span></a></div>';
                                }
                            }
                        }
                    ],
                });
            });
        </script>
    <?php elseif ($_GET['type'] == 'day_list') :

        $itinerary_plan_ID = $_POST['ID'];

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `guide_for_itinerary` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_data['itinerary_quote_ID'];
            $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
            $formattedstart_date = date('M d,Y', strtotime($trip_start_date_and_time));
            $formattedimagestart_date = date('Y-m-d', strtotime($trip_start_date_and_time));
            $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
            $formattedend_date = date('M d,Y', strtotime($trip_end_date_and_time));
            $formattedimageend_date = date('Y-m-d', strtotime($trip_end_date_and_time));
            $arrival_location = $fetch_data['arrival_location'];
            $departure_location = $fetch_data['departure_location'];
            $no_of_days = $fetch_data['no_of_days'];
            $no_of_nights = $fetch_data['no_of_nights'];
            $total_adult = $fetch_data['total_adult'];
            $total_children = $fetch_data['total_children'];
            $total_infants = $fetch_data['total_infants'];
            $guide_for_itinerary = $fetch_data['guide_for_itinerary'];

        endwhile;
        $total_pax_count = $total_adult + $total_children + $total_infants;

        $select_itinerary_plan = sqlQUERY_LABEL("SELECT  `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id` FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan)) :
            $vendor_id = $fetch_data['vendor_id'];
            $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
            $vehicle_id = $fetch_data['vehicle_id'];
        endwhile;

        // Ensure both variables are available
        if (isset($_POST['ID'])) {
            $itinerary_plan_ID = htmlspecialchars($_POST['ID'], ENT_QUOTES, 'UTF-8'); // Sanitize input
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

    ?>
        <div class="row" id="pdf-container-confirmed">
            <div class="col-md-12">
                <div class="itinerary-header-sticky-element sticky-element bg-label-primary p-3 mb-3 d-flex align-items-center justify-content-between" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                    <div>
                        <div class="d-flex align-items-center gap-4 mb-2">
                            <h6 class="m-0 text-blue-color"><?= $itinerary_quote_ID; ?></h6>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-calendar-event text-body ti-sm me-1"></i>
                                <h6 class="text-capitalize m-0">
                                    <b><?= $formattedstart_date; ?></b> to
                                    <b><?= $formattedend_date; ?></b> (<b><?= $no_of_nights; ?></b> N,
                                    <b><?= $no_of_days; ?></b> D)
                                </h6>
                            </div>
                        </div>
                        <div>
                            <h6 class="m-0"><?= $arrival_location; ?> <span><i class="ti ti-arrow-big-right-lines"></i></span> <?= $departure_location; ?> </h6>
                        </div>
                    </div>

                    <div id="remove-this-confirmed">
                        <button type="button" class="btn btn-sm btn-label-success waves-effect ps-3" id="download-confirmed-pdf-btn"><i class="tf-icons ti ti-download ti-xs me-1"></i> Download PDF</button>
                        <a href="dailymoment.php" type="button" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back
                            to List</a>
                    </div>

                </div>
                <div>
                    <?php

                    $select_itinerary_plan_customer = sqlQUERY_LABEL(" SELECT 
c.`confirmed_itinerary_customer_ID`, 
c.`confirmed_itinerary_plan_ID`, 
c.`itinerary_plan_ID`, 
c.`agent_id`, 
c.`primary_customer`, 
c.`customer_type`, 
c.`customer_name`, 
c.`customer_age`, 
c.`primary_contact_no`, 
c.`altenative_contact_no`, 
c.`email_id`, 
c.`createdby`, 
c.`createdon`, 
c.`updatedon`, 
c.`status`, 
c.`deleted`, 
r.`confirmed_itinerary_route_ID`, 
r.`itinerary_route_ID`, 
r.`itinerary_route_date`,
-- Subquery to get Day 1 itinerary route date
(SELECT MIN(r2.`itinerary_route_date`) 
 FROM `dvi_confirmed_itinerary_route_details` AS r2 
 WHERE r2.`itinerary_plan_ID` = '$itinerary_plan_ID'
   AND r2.`deleted` = '0' 
   AND r2.`status` = '1'
) AS day_1_itinerary_route_date
FROM 
`dvi_confirmed_itinerary_customer_details` AS c
JOIN 
`dvi_confirmed_itinerary_route_details` AS r
ON 
c.`itinerary_plan_ID` = r.`itinerary_plan_ID`
WHERE 
c.`deleted` = '0' 
AND c.`status` = '1' 
AND c.`primary_customer` = '1' 
AND r.`deleted` = '0' 
AND r.`status` = '1' 

AND r.`itinerary_plan_ID` = '$itinerary_plan_ID'
AND c.`itinerary_plan_ID` = '$itinerary_plan_ID';
") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                    if (sqlNUMOFROW_LABEL($select_itinerary_plan_customer) > 0) :
                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_customer)) :
                            $agent_id = $fetch_data['agent_id'];
                            $customer_name = $fetch_data['customer_name'];
                            $primary_contact_no = $fetch_data['primary_contact_no'];
                            $email_id = $fetch_data['email_id'];
                            $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
                            $travel_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
                            $travel_contactno = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
                            $travel_emailid = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
                            $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                            $itinerary_route_date = $fetch_data['itinerary_route_date'];
                            $day_1_itinerary_route_date = $fetch_data['day_1_itinerary_route_date'];

                            if ($primary_contact_no != ''):
                                $primary_contact_no = $fetch_data['primary_contact_no'];
                            else:
                                $primary_contact_no = '--';
                            endif;

                            if ($email_id != ''):
                                $email_id = $fetch_data['email_id'];
                            else:
                                $email_id = '--';
                            endif;

                            $total_children = $fetch_data['total_children'];
                            $total_infants = $fetch_data['total_infants'];
                        endwhile;

                        $day_1_itinerary_route_date_formatted = date('Y-m-d', strtotime($day_1_itinerary_route_date)); // Ensures proper format

                        // Get current date in the same format
                        $current_date = date('Y-m-d');

                    ?>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="border p-2 rounded"><img src="assets/img/svg/travelagent.svg" width="50px" /></div>
                                        <div>
                                            <h6 class="fw-bold ms-3 mb-0">Travel Expert</h6>
                                            <h6 class="text-primary ms-3 mb-0"><?= $travel_name; ?></h6>
                                            <p class="ms-3 fs-6 mb-0"><?= $travel_contactno; ?> / <?= $travel_emailid; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="border p-2 rounded"><img src="assets/img/svg/tourism.svg" width="50px" /></div>
                                        <div>
                                            <h6 class="fw-bold ms-3 mb-0">Guest</h6>
                                            <h6 class="text-primary ms-3 mb-0"><?= $customer_name; ?></h6>
                                            <p class="ms-3 fs-6 mb-0"><?= $primary_contact_no; ?> / <?= $email_id; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <div>
                    <div id="accordionIcon" class="accordion mt-3 accordion-without-arrow">
                        <?php
                        $select_itinerary_plan_route = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `driver_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route);
                        if ($total_itinerary_plan_details_count > 0) :
                            $daycount = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route)) :
                                $daycount++;
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                                $formattedroute_date = date('D, M d,Y', strtotime($itinerary_route_date));
                                $location_name = $fetch_data['location_name'];
                                $driver_trip_completed = $fetch_data['driver_trip_completed'];
                                $next_visiting_location = $fetch_data['next_visiting_location'];
                                $current_date = date('Y-m-d'); ?>

                                <div class="accordion-item card  p-3" id="accordionIconTwo">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#accordionIcon-<?= $daycount; ?>" aria-controls="accordionIcon-<?= $daycount; ?>">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="m-0 fs-6">DAY <?= $daycount; ?> - <?= $formattedroute_date ?></p>
                                                <h6 class="m-0"><?= $location_name; ?><span><i class="ti ti-arrow-big-right-lines"></i></span><?= $next_visiting_location; ?></h6>
                                            </div>
                                        </div>
                                        <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="badge badge-center rounded-pill bg-label-secondary p-3 cursor-pointer" onclick="showKILOMETERDRIVER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_ID; ?>','<?= $vendor_id; ?>','<?= $vendor_vehicle_type_id; ?>','<?= $vehicle_id; ?>')"
                                                    data-bs-toggle="modal" data-bs-target="#addDRIVERKILOMETERFORM"><img src="../head/assets/img/meter.png" width="20px" /></span>
                                                <?php if ($formattedimagestart_date == $itinerary_route_date || $formattedimageend_date == $itinerary_route_date): ?>
                                                    <span class="badge badge-center rounded-pill bg-label-secondary p-3 cursor-pointer" onclick="showDRIVERGALLERY('<?= $itinerary_plan_ID ?>', '<?= $itinerary_route_ID; ?>');"
                                                        data-bs-toggle="modal" data-bs-target="#GALLERYMODALINFODATA"><img src="../head/assets/img/image.png" width="20px" /></span>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-outline-warning waves-effect ps-3 py-2" onclick="showRATINGMODAL(<?= $itinerary_plan_ID ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal"><i class="ti ti-star me-2"></i>Review</button>
                                                <?php if ($formattedimagestart_date == $itinerary_route_date || $formattedimageend_date == $itinerary_route_date): ?>
                                                    <button type="button" class="btn btn-outline-danger waves-effect ps-3 py-2" onclick="showDRIVERIMAGEMODAL(<?= $itinerary_plan_ID ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal">+ Upload Image</button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-label-primary waves-effect" onclick="showDRIVERCHARGEMODAL(<?= $itinerary_plan_ID ?>, <?= $itinerary_route_ID; ?>);" data-bs-dismiss="modal">+ Add Charge</button>

                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div id="accordionIcon-<?= $daycount; ?>" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                        <div class="accordion-body">
                                            <ul class="timeline pt-3 px-3 mb-0">

                                                <?php
                                                $driver_opening_km = get_CONFIRMED_ITINEARY_DAILYMOMENT_KILOMETER($itinerary_plan_ID, $itinerary_route_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'driver_opening_km');
                                                $driver_closing_km = get_CONFIRMED_ITINEARY_DAILYMOMENT_KILOMETER($itinerary_plan_ID, $itinerary_route_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'driver_closing_km');
                                                $driver_trip_completed_status = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'driver_trip_completed_status', '');
                                                if ($driver_trip_completed_status == 1):
                                                    $driver_running_km = get_CONFIRMED_ITINEARY_DAILYMOMENT_KILOMETER($itinerary_plan_ID, $itinerary_route_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'driver_running_km');
                                                else:
                                                    $driver_running_km = 0;
                                                endif;

                                                $total_driver_opening_km += $driver_opening_km;
                                                $total_driver_closing_km += $driver_closing_km;
                                                $total_driver_running_km += $driver_running_km;
                                                ?>
                                                <li class="mb-3">
                                                    <div style="border-radius:3px;" class="px-3 py-2 rounded-3 bg-label-success">
                                                        <div class="row">
                                                            <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar me-3 ms-2">
                                                                        <span class="avatar-initial rounded-circle bg-white text-dark p-2"><img src="../head/assets/img/meter.png" width="24px" height="24px"></span>
                                                                    </div>
                                                                    <div class="d-flex gap-5 align-items-center ">
                                                                        <h6 class="m-0" style="color:#4d287b;">Starting KM - <b><?= $driver_opening_km ?>KM</b></h6>
                                                                        <h6 class="m-0" style="color:#4d287b;">Closing KM - <b><?= $driver_closing_km ?>KM</b></h6>
                                                                        <h6 class="m-0" style="color:#4d287b;">Running KM - <b><?= $driver_running_km ?>KM</b></h6>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                                $pricebook_true = check_guide_pricebook($itinerary_route_date, $total_pax_count);

                                                if ($guide_for_itinerary == 0 && $pricebook_true) :
                                                    $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_id`, `driver_guide_status`, `route_guide_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                    if ($total_itinerary_guide_route_count > 0) :
                                                        while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                            $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                                                            $driver_guide_status = $fetch_itinerary_guide_route_data['driver_guide_status'];
                                                            $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                            $guide_name = getGUIDEDETAILS($guide_id, 'label');
                                                        endwhile;
                                                ?>
                                                        <li class="mb-3">
                                                            <div style="border-radius:3px;" class="px-3 py-2 rounded-3 bg-label-warning">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark p-2"><img src="../head/assets/img/tour-guide.png" width="24px" height="24px"></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0" style="color:#4d287b;">Guide
                                                                                    Name - <span class="text-primary"><?= $guide_name; ?></span>
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                        <div id="guidecontainer-<?= $route_guide_ID; ?>" class="d-flex gap-3">
                                                                            <?php if ($driver_guide_status == 1): ?>
                                                                                <span id="visited-badge-<?= $route_guide_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <span class="cursor-pointer" onclick="showEDITSTATUSMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            <?php elseif ($driver_guide_status == 2): ?>
                                                                                <span id="not-visited-badge-<?= $route_guide_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <span class="cursor-pointer" onclick="showEDITSTATUSMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                                <?php else:
                                                                                if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <button type="button" id="visited-btn-<?= $route_guide_ID; ?>" onclick="toggleguidestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                    <button type="button" id="not-visited-btn-<?= $route_guide_ID; ?>" onclick="showNotVisitedguideModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                            <?php
                                                                                endif;
                                                                            endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php elseif ($guide_for_itinerary == 1 && $pricebook_true) :

                                                    $select_itinerar_route_details = sqlQUERY_LABEL("SELECT `wholeday_guidehotspot_status` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID`='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerar_route_details);
                                                    if ($total_itinerary_route_count > 0) :
                                                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerar_route_details)) :
                                                            $wholeday_guidehotspot_status = $fetch_itinerary_route_data['wholeday_guidehotspot_status'];
                                                        endwhile;
                                                    endif;

                                                    $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_id`, `driver_guide_status`, `route_guide_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                    if ($total_itinerary_guide_route_count > 0) :
                                                        while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                            $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                                                            $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                            $guide_name = getGUIDEDETAILS($guide_id, 'label');
                                                        endwhile;
                                                    ?>
                                                        <li class="mb-3">
                                                            <div style="border-radius:3px;" class="px-3 py-2 rounded-3 bg-label-warning">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark p-2"><img src="../head/assets/img/tour-guide.png" width="24px" height="24px"></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0" style="color:#4d287b;">Guide
                                                                                    Name - <span class="text-primary"><?= $guide_name; ?></span>
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                        <div id="wholedayguidecontainer-<?= $route_guide_ID; ?>" class="d-flex gap-3">
                                                                            <?php if ($wholeday_guidehotspot_status == 1): ?>
                                                                                <span id="visited-badge-<?= $route_guide_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <span class="cursor-pointer" onclick="showEDITSTATUSMODALWHOLEDAYGUIDE(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            <?php elseif ($wholeday_guidehotspot_status == 2): ?>
                                                                                <span id="not-visited-badge-<?= $route_guide_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <span class="cursor-pointer" onclick="showEDITSTATUSMODALWHOLEDAYGUIDE(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                                <?php else:
                                                                                if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                    <button type="button" id="visited-btn-<?= $route_guide_ID; ?>" onclick="togglewholedayguidestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                    <button type="button" id="not-visited-btn-<?= $route_guide_ID; ?>" onclick="showWholedayNotVisitedguideModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_guide_ID; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                            <?php endif;
                                                                            endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php
                                                $select_itinerary_plan_route_hotspot = sqlQUERY_LABEL("SELECT `confirmed_route_hotspot_ID`, `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `item_type`, `hotspot_order`, `hotspot_ID`, `driver_hotspot_status`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost`, `hotspot_amout`, `hotspot_traveling_time`, `itinerary_travel_type_buffer_time`, `hotspot_travelling_distance`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_plan_own_way` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE  `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('4','6','7') ORDER BY `hotspot_order` ASC") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                                $total_itinerary_plan_details_route_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot);
                                                if ($total_itinerary_plan_details_route_count > 0) :
                                                    $daycount_hotspot = 0;
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot)) :
                                                        $daycount_hotspot++;
                                                        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                                        $item_type = $fetch_data['item_type'];
                                                        $hotspot_ID = $fetch_data['hotspot_ID'];
                                                        $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
                                                        $hotspot_start_time = $fetch_data['hotspot_start_time'];
                                                        $route_hotspot_ID = $fetch_data['route_hotspot_ID'];
                                                        $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                                                        $hotspot_start_time = $fetch_data['hotspot_start_time'];
                                                        $hotspot_end_time = $fetch_data['hotspot_end_time'];
                                                        $hotspot_traveling_time = $fetch_data['hotspot_traveling_time'];
                                                        $hotspot_travelling_distance = $fetch_data['hotspot_travelling_distance'];
                                                ?>

                                                        <?php if ($item_type == 4): ?>
                                                            <li class="mb-3">
                                                                <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                    <div class="row">
                                                                        <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="avatar me-3 ms-2">
                                                                                    <span class="avatar-initial rounded-circle bg-white text-dark"><?= $daycount_hotspot; ?></span>
                                                                                </div>
                                                                                <div class="d-flex gap-3 align-items-center">
                                                                                    <h6 class="m-0"><?= $hotspot_name; ?></h6>
                                                                                    <div class="d-flex align-items-center gap-4 text-dark">
                                                                                        <p class="mt-1 mb-0">
                                                                                            <i class="ti ti-clock me-1 mb-1"></i>
                                                                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?> - <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                        </p>
                                                                                        <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i><?= formatTimeDuration($hotspot_traveling_time); ?> </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="container-<?= $route_hotspot_ID; ?>" class="d-flex gap-3">
                                                                                <?php if ($driver_hotspot_status == 1): ?>
                                                                                    <span id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                    <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                <?php elseif ($driver_hotspot_status == 2): ?>
                                                                                    <span id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                    <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                    <?php else:
                                                                                    if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <button type="button" id="visited-btn-<?= $route_hotspot_ID; ?>" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                        <button type="button" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-Visited</button>
                                                                                <?php endif;
                                                                                endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- activity -->

                                                                    <?php

                                                                    $select_itineary_hotspot_activity_details = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ROUTE_ACTIVITY.`route_activity_ID`, ROUTE_ACTIVITY.`route_hotspot_ID`, ROUTE_ACTIVITY.`activity_order`, ROUTE_ACTIVITY.`activity_ID`, ROUTE_ACTIVITY.`driver_activity_status`, ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_traveling_time`,  ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time` FROM `dvi_confirmed_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ROUTE_ACTIVITY.`activity_ID` = ACTIVITY.`activity_id` WHERE ROUTE_ACTIVITY.`deleted` = '0' and ROUTE_ACTIVITY.`status` = '1' AND ROUTE_ACTIVITY.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_ACTIVITY.`itinerary_route_ID` = '$itinerary_route_ID' AND ROUTE_ACTIVITY.`route_hotspot_ID` = '$route_hotspot_ID' AND ROUTE_ACTIVITY.`hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT__ACTIVITY_LIST:" . sqlERROR_LABEL());
                                                                    $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_activity_details);
                                                                    if ($total_hotspot_activity_num_rows_count > 0) :
                                                                        $activitycount = 0;
                                                                    ?>
                                                                        <div class="mb-2">
                                                                            <hr />
                                                                            <h6 class="my-2">Activity</h6>
                                                                            <?php while ($fetch_hotspot_activity_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_activity_details)) :
                                                                                $activitycount++;
                                                                                $route_activity_ID = $fetch_hotspot_activity_data['route_activity_ID'];
                                                                                $route_hotspot_ID = $fetch_hotspot_activity_data['route_hotspot_ID'];
                                                                                $driver_activity_status = $fetch_hotspot_activity_data['driver_activity_status'];
                                                                                $activity_order = $fetch_hotspot_activity_data['activity_order'];
                                                                                $activity_ID = $fetch_hotspot_activity_data['activity_ID'];
                                                                                $activity_amout = $fetch_hotspot_activity_data['activity_amout'];
                                                                                $activity_traveling_time = $fetch_hotspot_activity_data['activity_traveling_time'];
                                                                                $activity_start_time = $fetch_hotspot_activity_data['activity_start_time'];
                                                                                $activity_end_time = $fetch_hotspot_activity_data['activity_end_time'];
                                                                                $activity_title = $fetch_hotspot_activity_data['activity_title'];
                                                                                $activity_description = $fetch_hotspot_activity_data['activity_description'];
                                                                                $get_first_activity_image_gallery_name = getACTIVITY_IMAGE_GALLERY_DETAILS($activity_ID, 'get_first_activity_image_gallery_name');
                                                                            ?>
                                                                                <div class="d-flex align-items-center justify-content-between bg-label-white rounded mt-2 ms-3 px-2 p-1">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <div class="avatar me-3 ms-2">
                                                                                            <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-send rounded-circle text-primary"></i></span>
                                                                                        </div>
                                                                                        <div class="d-flex gap-3 align-items-center">
                                                                                            <h6 class="m-0">#<?= $activitycount; ?> <?= $activity_title; ?></h6>
                                                                                            <div class="d-flex align-items-center gap-4 text-dark">
                                                                                                <p class="mt-1 mb-0">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    <?= date('h:i A', strtotime($activity_start_time)); ?> - <?= date('h:i A', strtotime($activity_end_time)); ?>
                                                                                                </p>
                                                                                                <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i><?= formatTimeDuration($activity_traveling_time); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="activitycontainer-<?= $route_activity_ID; ?>" class="d-flex gap-3">
                                                                                        <?php if ($driver_activity_status == 1): ?>
                                                                                            <span id="visited-badge-<?= $route_activity_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                            <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                                <span class="cursor-pointer" onclick="showEDITACTIVITYMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)" data-bs-dismiss="modal">
                                                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                                </span>
                                                                                            <?php endif; ?>
                                                                                        <?php elseif ($driver_activity_status == 2): ?>
                                                                                            <span id="not-visited-badge-<?= $route_activity_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                            <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                                <span class="cursor-pointer" onclick="showEDITACTIVITYMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)" data-bs-dismiss="modal">
                                                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                                </span>
                                                                                            <?php endif; ?>
                                                                                            <?php else:
                                                                                            if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                                <button type="button" id="visited-btn-<?= $route_activity_ID; ?>" onclick="toggleactivitystatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                                <button type="button" onclick="showNotVisitedActivityModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_activity_ID; ?>, <?= $route_hotspot_ID; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                                        <?php endif;
                                                                                        endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php
                                                                            endwhile;
                                                                            ?>
                                                                        </div>
                                                                    <?php
                                                                    endif;
                                                                    ?>
                                                                </div>
                                                                <div class="dailymoment-daywise-border"></div>
                                                            </li>
                                                        <?php elseif ($item_type == 6):

                                                            $get_hotel_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
                                                            $get_hotel_address = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'hotel_address');
                                                            if ($get_hotel_title) :
                                                                $get_hotel_name = $get_hotel_title;
                                                            else :
                                                                $get_hotel_name = 'N/A';
                                                            endif;
                                                            if ($get_hotel_address) :
                                                                $get_hotel_address_format = $get_hotel_address;
                                                            else :
                                                                $get_hotel_address_format = 'N/A';
                                                            endif;


                                                        ?>
                                                            <li class="mb-3">
                                                                <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                    <div class="row">
                                                                        <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                            <div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="avatar me-3 ms-2">
                                                                                        <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                                    </div>
                                                                                    <div>
                                                                                        <div class="d-flex gap-3 align-items-center">
                                                                                            <h6 class="m-0"><?= $get_hotel_name; ?></h6>
                                                                                            <p class="mt-1 mb-0 text-dark">
                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div>
                                                                                            <p class="mt-1 mb-0 text-dark"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i><?= $get_hotel_address_format; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div id="container-<?= $route_hotspot_ID; ?>" class="d-flex gap-3">
                                                                                <?php if ($driver_hotspot_status == 1): ?>
                                                                                    <span id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                    <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                <?php elseif ($driver_hotspot_status == 2): ?>
                                                                                    <span id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                    <?php if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                    <?php else:
                                                                                    if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <button type="button" id="visited-btn-<?= $route_hotspot_ID; ?>" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                        <button type="button" id="not-visited-btn-<?= $route_hotspot_ID; ?>" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                                <?php endif;
                                                                                endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php elseif ($item_type == 7):
                                                        ?>
                                                            <li class="mb-3">
                                                                <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                    <div class="row">
                                                                        <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="avatar me-3 ms-2">
                                                                                    <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                                </div>
                                                                                <div>

                                                                                    <div class="d-flex align-items-center gap-4 text-dark">
                                                                                        <h6 class="m-0"><?= getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location'); ?></h6>
                                                                                        <p class="mt-1 mb-0">
                                                                                            <i class="ti ti-clock me-1 mb-1"></i>
                                                                                            <?= date('h:i A', strtotime($hotspot_start_time)); ?>
                                                                                            -
                                                                                            <?= date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                        </p>
                                                                                        <p class="mt-1 mb-0">
                                                                                            <i class="ti ti-route me-1 mb-1"></i>
                                                                                            <?= $hotspot_travelling_distance; ?> KM
                                                                                        </p>

                                                                                    </div>
                                                                                    <p class="mt-1 mb-0 text-dark"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i> <?= formatTimeDuration($hotspot_traveling_time); ?>
                                                                                        (This may vary due to traffic
                                                                                        conditions) </p>
                                                                                </div>
                                                                            </div>
                                                                            <div id="container-<?= $route_hotspot_ID; ?>" class="d-flex gap-3">
                                                                                <?php if ($driver_hotspot_status == 1): ?>
                                                                                    <span id="visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                                    <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                <?php elseif ($driver_hotspot_status == 2): ?>
                                                                                    <span id="not-visited-badge-<?= $route_hotspot_ID; ?>" class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: inline;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                                    <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                    </span>
                                                                                    <?php else:
                                                                                    if ($logged_user_level == 1 || $logged_user_level == 3): ?>
                                                                                        <button type="button" id="visited-btn-<?= $route_hotspot_ID; ?>" onclick="togglestatusITEM(1, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                                        <button type="button" id="not-visited-btn-<?= $route_hotspot_ID; ?>" onclick="showNotVisitedHotspotModal(2,<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                                <?php endif;
                                                                                endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php endif; ?>
                                                <?php endwhile;
                                                endif;
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                        <?php endwhile;
                        endif;
                        ?>

                    </div>
                </div>

                <div class="row mt-3">
                    <div class=" col-md-12">
                        <div class="card p-4">
                            <h5 class="card-header p-0 mb-3 text-uppercase"><b>Overall KiloMeter Summary</b></h5>
                            <div class="d-flex justify-content-end">
                                <div>
                                    <h6 class="text-heading fw-bold">Total Running KM - <b class="fs-5 text-primary"><?= $total_driver_running_km ?> KM</b></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card p-0">
                            <div class="card-header pt-3 pb-0 d-flex justify-content-between">
                                <div class="col-md-auto">
                                    <h5 class="card-title mb-0">List of Charge Details</h5>
                                </div>
                            </div>

                            <div class="card-body dataTable_select text-nowrap">
                                <div class="text-nowrap table-responsive table-bordered">
                                    <table class="table table-hover" id="add_charge_LIST">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Action</th>
                                                <th>Day</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th>Charge Title</th>
                                                <th>Charge Amount</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card p-0">
                            <div class="card-header pt-3 pb-0 d-flex justify-content-between">
                                <div class="col-md-auto">
                                    <h5 class="card-title mb-0">List of Rating Details</h5>
                                </div>
                            </div>

                            <div class="card-body dataTable_select text-nowrap">
                                <div class="text-nowrap table-responsive table-bordered">
                                    <table class="table table-hover" id="add_rating_LIST">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Action</th>
                                                <th>Day</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th>Rating</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addDRIVERCHARGEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-drivercharge-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body receiving-confirm-delete-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmRATINGDELETEINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body receiving-confirm-ratingdelete-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDGUIDEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-guideform-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDWHOLEDAYGUIDEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-wholedayguideform-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNOTVISITEDACTIVITYFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-notvisited-activityform-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addDRIVERIMAGEFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-driverimage-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="GALLERYMODALINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top modal-lg receiving-gallery-modal-info-form-data">
            </div>
        </div>

        <div class="modal fade" id="ratingMODALINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-rating-info-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMODALINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-editguide-info-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMODALINFODATAWHOLEDAYGUIDE" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-editwholedayguide-info-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addDRIVERKILOMETERFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-lg modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-driverkilometer-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMODALHOTSPOTDATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-edithotspot-info-form-data">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editMODALACTIVITYDATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-editactivity-info-form-data">
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/vendor/js/jspdf.js"></script>
        <script src="assets/vendor/js/html2canvas.js"></script>
        <script>
            function showDRIVERCHARGEMODAL(PLAN_ID, ROUTE_ID) {
                $('.receiving-drivercharge-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=drivercharge&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("addDRIVERCHARGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedHotspotModal(STATUS, PLAN_ID, ROUTE_ID, route_hotspot_ID, item_type) {
                $('.receiving-notvisited-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=not_visiting&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&HOTSPOT_ID=' + route_hotspot_ID + '&TYPE_ID=' + item_type, function() {
                    const container = document.getElementById("addNOTVISITEDFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedguideModal(STATUS, PLAN_ID, ROUTE_ID, GUIDE_ID) {
                $('.receiving-notvisited-guideform-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=not_visiting_guide&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDE_ID=' + GUIDE_ID, function() {
                    const container = document.getElementById("addNOTVISITEDGUIDEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showWholedayNotVisitedguideModal(STATUS, PLAN_ID, ROUTE_ID, GUIDE_ID) {
                $('.receiving-notvisited-wholedayguideform-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=not_visiting_wholedayguide&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDE_ID=' + GUIDE_ID, function() {
                    const container = document.getElementById("addNOTVISITEDWHOLEDAYGUIDEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showNotVisitedActivityModal(STATUS, PLAN_ID, ROUTE_ID, ACTIVITY_ID, HOTSPOT_ID) {
                $('.receiving-notvisited-activityform-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=not_visiting_activity&STATUS=' + STATUS + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&ACTIVITY_ID=' + ACTIVITY_ID + '&HOTSPOT_ID=' + HOTSPOT_ID, function() {
                    const container = document.getElementById("addNOTVISITEDACTIVITYFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showKILOMETERDRIVER(PLAN_ID, ROUTE_ID, VENDOR_ID, VEHICLE_TYPE, VEHICLE_ID) {
                $('.receiving-driverkilometer-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=driverkilometer&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&VENDOR_ID=' + VENDOR_ID + '&VEHICLE_TYPE=' + VEHICLE_TYPE + '&VEHICLE_ID=' + VEHICLE_ID + '', function() {
                    const container = document.getElementById("addDRIVERKILOMETERFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERIMAGEMODAL(PLAN_ID, ROUTE_ID) {
                $('.receiving-driverimage-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=add_image&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("addDRIVERIMAGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDRIVERGALLERY(PLAN_ID, ROUTE_ID) {
                $('.receiving-gallery-modal-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=showgallerymodal&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID,
                    function() {
                        const container = document.getElementById("GALLERYMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showRATINGMODAL(PLAN_ID, ROUTE_ID) {
                $('.receiving-rating-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=driver_rating&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID,
                    function() {
                        const container = document.getElementById("ratingMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showEDITSTATUSMODAL(PLAN_ID, ROUTE_ID, GUIDEID) {
                $('.receiving-editguide-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=edit_guide&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDEID=' + GUIDEID + '',
                    function() {
                        const container = document.getElementById("editMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showEDITSTATUSMODALWHOLEDAYGUIDE(PLAN_ID, ROUTE_ID, GUIDEID) {
                $('.receiving-editwholedayguide-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=edit_wholedayguide&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&GUIDEID=' + GUIDEID + '',
                    function() {
                        const container = document.getElementById("editMODALINFODATAWHOLEDAYGUIDE");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showEDITHOTSPOTMODAL(PLAN_ID, ROUTE_ID, HOTSPOTID, TYPEID) {
                $('.receiving-edithotspot-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=edit_hotspot&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&HOTSPOTID=' + HOTSPOTID + '&TYPEID=' + TYPEID + '',
                    function() {
                        const container = document.getElementById("editMODALHOTSPOTDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showEDITACTIVITYMODAL(PLAN_ID, ROUTE_ID, ACTIVITYID, HOTSPOTID) {
                $('.receiving-editactivity-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=edit_activity&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '&ACTIVITYID=' + ACTIVITYID + '&HOTSPOTID=' + HOTSPOTID + '',
                    function() {
                        const container = document.getElementById("editMODALACTIVITYDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }


            function toggleguidestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_guide_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = route_guide_ID;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'guidestatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedguideContent = generateguideStatusHTML(SELECTED_STATUS, GUIDE_ID, PLAN_ID, ROUTE_ID);
                            $('#guidecontainer-' + GUIDE_ID).html(updatedguideContent);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generateguideStatusHTML(status, GUIDE_ID, itinerary_plan_ID, itinerary_route_ID) {
                if (status === 1) { // Visited
                    return `
                <span id="visited-badge-${GUIDE_ID}" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                <span class="cursor-pointer" id="edit-icon" data-bs-toggle="modal" data-bs-target="#edit" style="display: inline;">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                `;
                } else if (status === 2) { // Not Visited
                    return `
                <span id="not-visited-badge-${GUIDE_ID}" class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                <span class="cursor-pointer" id="edit-icon" data-bs-toggle="modal" data-bs-target="#edit" style="display: inline;">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                `;
                } else {
                    return `
                <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2" id="visited-btn-${GUIDE_ID}" onclick="toggleguidestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <i class="ti ti-check fs-6 me-1"></i>Visited
                </button>

                <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2" id="not-visited-btn-${GUIDE_ID}" onclick="toggleguidestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <i class="ti ti-x fs-6 me-1"></i>Not-visted
                </button>`;
                }
            }

            function togglewholedayguidestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_guide_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = route_guide_ID;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'wholeday_guidestatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedguideContent = generatewholedayguideStatusHTML(SELECTED_STATUS, GUIDE_ID, PLAN_ID, ROUTE_ID);
                            $('#wholedayguidecontainer-' + GUIDE_ID).html(updatedguideContent);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generatewholedayguideStatusHTML(status, GUIDE_ID, itinerary_plan_ID, itinerary_route_ID) {
                if (status === 1) { // Visited
                    return `
                <span id="visited-badge-${GUIDE_ID}" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                <span class="cursor-pointer" id="edit-icon" data-bs-toggle="modal" data-bs-target="#edit" style="display: inline;">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                `;
                } else if (status === 2) { // Not Visited
                    return `
                <span id="not-visited-badge-${GUIDE_ID}" class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                <span class="cursor-pointer" id="edit-icon" data-bs-toggle="modal" data-bs-target="#edit" style="display: inline;">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                `;
                } else {
                    return `
                <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2" id="visited-btn-${GUIDE_ID}" onclick="toggleguidestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <i class="ti ti-check fs-6 me-1"></i>Visited
                </button>

                <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2" id="not-visited-btn-${GUIDE_ID}" onclick="toggleguidestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${GUIDE_ID}, 'guide')">
                    <i class="ti ti-x fs-6 me-1"></i>Not-visted
                </button>`;
                }
            }

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        type: 'hotspotstatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedContent = generateStatusHTML(SELECTED_STATUS, ROUTE_HOTSPOT_ID, PLAN_ID, ROUTE_ID, TYPE_ID);
                            $('#container-' + ROUTE_HOTSPOT_ID).html(updatedContent);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generateStatusHTML(status, route_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, item_type) {
                if (status === 1) { // Visited
                    return `
                <span id="visited-badge-${route_hotspot_ID}" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                `;
                } else if (status === 2) { // Not Visited
                    return `
                    <span id="not-visited-badge-${route_hotspot_ID}" class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                    <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $route_hotspot_ID; ?>, <?= $item_type; ?>)" data-bs-dismiss="modal">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                    `;
                } else {
                    return `
                    <button type="button" id="visited-btn-${route_hotspot_ID}" onclick="togglestatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                    <button type="button" id="not-visited-btn-${route_hotspot_ID}" onclick="togglestatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${route_hotspot_ID}, ${item_type})" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>`;
                }
            }

            function toggleactivitystatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_activity_ID, route_hotspot_ID) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_ACTIVITY_ID = route_activity_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php',
                    type: 'GET',
                    data: {
                        type: 'activitystatus',
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ROUTE_ACTIVITY_ID,
                        route_hotspot_ID: ROUTE_HOTSPOT_ID
                    },
                    success: function(response) {
                        console.log(response);
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            // Replace the entire content of the container with the updated HTML
                            var updatedactivityContent = generateActivityStatusHTML(SELECTED_STATUS, ROUTE_ACTIVITY_ID, PLAN_ID, ROUTE_ID, ROUTE_HOTSPOT_ID);
                            $('#activitycontainer-' + ROUTE_ACTIVITY_ID).html(updatedactivityContent);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }

            function generateActivityStatusHTML(status, ACTIVITY_ID, itinerary_plan_ID, itinerary_route_ID, hotspot_route_ID) {
                if (status === 1) { // Visited
                    return `
                    <span id="visited-badge-${ACTIVITY_ID}" class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                `;
                } else if (status === 2) { // Not Visited
                    return `
                <span id="not-visited-badge-${ACTIVITY_ID}" class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                `;
                } else {
                    return `
                <button type="button" id="visited-btn-${ACTIVITY_ID}" onclick="toggleactivitystatusITEM(1, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                <button type="button" id="not-visited-btn-${ACTIVITY_ID}" onclick="toggleactivitystatusITEM(2, ${itinerary_plan_ID}, ${itinerary_route_ID}, ${ACTIVITY_ID}, ${hotspot_route_ID})" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                `;
                }
            }

            $(document).ready(function() {
                $(".form-select").selectize();

                var dataTable = $('#add_charge_LIST').DataTable({
                    dom: 'Blfrti',
                    "bFilter": true,
                    "paging": false,
                    "info": false,
                    buttons: [{
                            extend: 'copy',
                            text: window.copyButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        },
                        {
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        },
                        {
                            extend: 'csv',
                            text: window.csvButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        }
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html(
                            '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>'
                        );

                        $('.buttons-csv').html(
                            '<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>'
                        );

                        $('.buttons-excel').html(
                            '<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
                        );


                    },
                    ajax: {
                        "url": "engine/json/__JSONdailymomentaddcharge.php?plan_ID=<?= $itinerary_plan_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "get_route_date"
                        }, //2
                        {
                            data: "location_name"
                        }, //3
                        {
                            data: "next_visiting_location"
                        }, //4
                        {
                            data: "charge_type"
                        }, //5
                        {
                            data: "charge_amount"
                        } //6

                    ],
                    columnDefs: [{
                        "targets": 1,
                        "data": "modify",
                        "render": function(data, type, row, full) {
                            return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="javascript:void(0);" onclick="showEDITCHARGEMODAL(' + data + ',' + row.itinerary_plan_ID + ',' + row.itinerary_route_ID + ');" style="margin-right: 3px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETECHARGEMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                        }
                    }],
                });


            });

            function showEDITCHARGEMODAL(ID, PLAN_ID, ROUTE_ID) {
                $('.receiving-drivercharge-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=drivercharge&ID=' + ID + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID + '', function() {
                    const container = document.getElementById("addDRIVERCHARGEFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showDELETECHARGEMODAL(ID) {
                $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=deletecharge&ID=' + ID, function() {
                    const container = document.getElementById("confirmDELETEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }
            $(document).ready(function() {
                $(".form-select").selectize();

                var dataTable = $('#add_rating_LIST').DataTable({
                    dom: 'Blfrti',
                    "bFilter": true,
                    "paging": false,
                    "info": false,
                    buttons: [{
                            extend: 'copy',
                            text: window.copyButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        },
                        {
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        },
                        {
                            extend: 'csv',
                            text: window.csvButtonTrans,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 6], // Only name, email and role
                            }
                        }
                    ],
                    initComplete: function() {
                        $('.buttons-copy').html(
                            '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>'
                        );

                        $('.buttons-csv').html(
                            '<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>'
                        );

                        $('.buttons-excel').html(
                            '<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
                        );


                    },
                    ajax: {
                        "url": "engine/json/__JSONdailymomentdriverrating.php?plan_ID=<?= $itinerary_plan_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "get_route_date"
                        }, //2
                        {
                            data: "location_name"
                        }, //3
                        {
                            data: "next_visiting_location"
                        }, //4
                        {
                            data: "customer_rating"
                        }, //5
                        {
                            data: "feedback_description"
                        } //6

                    ],
                    columnDefs: [{
                        "targets": 1,
                        "data": "modify",
                        "render": function(data, type, row, full) {
                            return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="javascript:void(0);" onclick="showEDITRATINGMODAL(' + data + ',' + row.itinerary_plan_ID + ',' + row.itinerary_route_ID + ');" style="margin-right: 3px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETERATINGMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                        }
                    }],
                });


            });

            function showEDITRATINGMODAL(ID, PLAN_ID, ROUTE_ID) {
                $('.receiving-rating-info-form-data').load(
                    'engine/ajax/__ajax_travelexpert_moment.php?type=driver_rating&ID=' + ID + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID,
                    function() {
                        const container = document.getElementById("ratingMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            function showDELETERATINGMODAL(ID) {
                $('.receiving-confirm-ratingdelete-form-data').load('engine/ajax/__ajax_travelexpert_moment.php?type=rating_deletecharge&ID=' + ID, function() {
                    const container = document.getElementById("confirmRATINGDELETEINFODATA");
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

    <?php elseif ($_GET['type'] == 'drivercharge') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $charge_ID = $_GET['ID'];


        if ($charge_ID != '' && $charge_ID != 0) :

            $select_language_list = sqlQUERY_LABEL("SELECT `driver_charge_ID`, `charge_type`, `charge_amount` FROM `dvi_confirmed_itinerary_dailymoment_charge` WHERE `deleted` = '0' AND  `driver_charge_ID` = '$charge_ID'") or die("#1-UNABLE_TO_COLLECT_vehicle_type_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_language_list)) :
                $driver_charge_ID = $fetch_data['driver_charge_ID'];
                $charge_type = $fetch_data['charge_type'];
                $charge_amount = $fetch_data['charge_amount'];
            endwhile;
            $btn_label = 'Update';
            $title_label = 'Edit';
        else :
            $btn_label = 'Save';
            $title_label = 'Add';
        endif;


    ?>
        <!-- Plugins css Ends-->
        <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2"><?= $title_label ?> Charges</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12">
                <label class="form-label w-100" for="visited_charge">Charge Type<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="visited_charge" name="visited_charge" required class="form-control" placeholder="Enter the Charge" value="<?= $charge_type; ?>" autocomplete="off">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="visited_charge_amount">Charge Amount<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="visited_charge_amount" name="visited_charge_amount" required class="form-control" placeholder="Enter the Charge" value="<?= $charge_amount; ?>" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off">
                    <input type="hidden" name="hidden_charge" id="hidden_charge" value="<?= $driver_charge_ID; ?>" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });


                //AJAX FORM SUBMIT
                $("#drivercharge_details_form").submit(function(event) {
                    var form = $('#drivercharge_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_dailymoment_manage.php?type=drivercharge&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            if (response.errors.visited_charge_required) {
                                TOAST_NOTIFICATION('warning', 'Visited Charge Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.visited_charge_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Visited Charge Amount Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#drivercharge_details_form')[0].reset();
                                $('#addDRIVERCHARGEFORM').modal('hide');
                                window.location.reload();
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

    <?php elseif ($_GET['type'] == 'deletecharge') :

        $charge_ID = $_GET['ID'];


    ?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmLANGUAGEDELETE('<?= $charge_ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>

        <script>
            function confirmLANGUAGEDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_dailymoment_manage.php?type=confirmdelete",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#add_charge_LIST').DataTable().ajax.reload();
                            $('#confirmDELETEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Delete Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'rating_deletecharge') :

        $driver_feedback_ID = $_GET['ID'];


    ?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmRATINGDELETE('<?= $driver_feedback_ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>

        <script>
            function confirmRATINGDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_dailymoment_manage.php?type=confirmdelete_rating",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#add_rating_LIST').DataTable().ajax.reload();
                            $('#confirmRATINGDELETEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Delete Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        </script>


    <?php elseif ($_GET['type'] == 'add_image') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];

    ?>
        <!-- Plugins css Ends-->
        <form id="driver" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Upload Image</h4>
                </div>
                <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <span id="response_modal"></span>

            <div class="col-12">
                <label class="form-label w-100" for="dailymoment_uploadimage">Upload Image</label>
                <div class="form-group">
                    <input type="file" id="dailymoment_uploadimage" name="dailymoment_uploadimage[]" class="form-control" multiple>
                </div>
                <!-- Container for image previews -->
                <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });


                //AJAX FORM SUBMIT
                $("#driver").submit(function(event) {
                    var form = $('#driver')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_dailymoment_manage.php?type=driverImage&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            TOAST_NOTIFICATION('error', 'Image not Uploaded', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#driver')[0].reset();
                                $('#addDRIVERIMAGEFORM').modal('hide');
                                TOAST_NOTIFICATION('success', 'Upload Image Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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

            document.getElementById('dailymoment_uploadimage').addEventListener('change', function(event) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                imagePreviewContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-2 border';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close me-3 mt-2 p-2 py-1';
                        closeButton.style.top = '0';
                        closeButton.style.width = '2px';
                        closeButton.style.right = '0';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('dailymoment_uploadimage').files = dataTransfer.files;
                }
            });
        </script>

    <?php elseif ($_GET['type'] == 'showgallerymodal') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];

    ?>
        <div class="modal-content">
            <div class="modal-body pt-0">
                <div class="d-flex align-items-center justify-content-between my-3 mx-2">
                    <div class="text-center">
                        <h4 class="mb-2">Gallery</h4>
                    </div>
                    <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <span id="response_modal"></span>

                <div id="swiper-gallery">
                    <div class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            <?php
                            // Fetch images from the database
                            $select_driver_gallery_list_query = sqlQUERY_LABEL("SELECT `driver_uploadimage_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `driver_upload_image` FROM `dvi_confirmed_driver_uploadimage` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_GALLERY_LIST:" . sqlERROR_LABEL());

                            // Check if any images are available
                            if (sqlNUMOFROW_LABEL($select_driver_gallery_list_query) > 0) :
                                // Loop through the images and generate Swiper slides
                                while ($fetch_driver_gallery_data = sqlFETCHARRAY_LABEL($select_driver_gallery_list_query)) :
                                    $driver_photo_url = BASEPATH . 'uploads/driver_dailymoment_gallery/' . $fetch_driver_gallery_data['driver_upload_image'];
                            ?>
                                    <div class="swiper-slide" style="background-image:url('<?= $driver_photo_url; ?>');"></div>
                                <?php
                                endwhile;
                            else :
                                ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h4>No more gallery found !!!</h4>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper gallery-thumbs mt-2">
                        <div class="swiper-wrapper">
                            <?php
                            // Rewind the query result pointer to fetch the same images for the thumbnails
                            sqlDATASEEK_LABEL($select_driver_gallery_list_query, 0);
                            while ($fetch_driver_gallery_data = sqlFETCHARRAY_LABEL($select_driver_gallery_list_query)) :
                                $driver_photo_url = BASEPATH . 'uploads/driver_dailymoment_gallery/' . $fetch_driver_gallery_data['driver_upload_image'];
                            ?>
                                <div class="swiper-slide" style="background-image:url('<?= $driver_photo_url; ?>')"></div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/vendor/libs/swiper/swiper.js"></script>
        <script src="assets/js/ui-carousel.js"></script>

    <?php elseif ($_GET['type'] == 'driverkilometer') :
        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $vendor_ID = $_GET['VENDOR_ID'];
        $vehicle_type_ID = $_GET['VEHICLE_TYPE'];
        $vehicle_ID = $_GET['VEHICLE_ID'];

        $select_itinerary_vendor_vehicle = sqlQUERY_LABEL("SELECT `driver_opening_km`, `opening_speedmeter_image`, `driver_closing_km`, `closing_speedmeter_image` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and  `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_ID' and `vendor_vehicle_type_id` = '$vehicle_type_ID' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_vendor_vehicle = sqlNUMOFROW_LABEL($select_itinerary_vendor_vehicle);
        if ($total_itinerary_plan_details_vendor_vehicle > 0) :
            $daycount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_vendor_vehicle)) :
                $driver_opening_km = $fetch_data['driver_opening_km'];
                $opening_speedmeter_image = $fetch_data['opening_speedmeter_image'];
                $driver_closing_km = $fetch_data['driver_closing_km'];
                $closing_speedmeter_image = $fetch_data['closing_speedmeter_image'];
            endwhile;
        endif;
    ?>
        <!-- Plugins css Ends-->
        <div class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2"><?= $title_label ?> Show Kilometer</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" onclick="closeDriverKilometerModal()"></button>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="d-flex align-items-center my-3">
                        <h6 class="cost-details-title">Opening Kilometer :</h6>
                        <?php if ($driver_opening_km): ?>
                            <h4 class="cost-details-amount fw-bold fs-5 mx-2"><?= $driver_opening_km; ?> KM</h4>
                        <?php else: ?>
                            <h4 class="cost-details-amount fw-bold fs-5 mx-2">NAN</h4>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($opening_speedmeter_image): ?>
                            <img src="uploads/driver_speedmeter_gallery/<?= $opening_speedmeter_image; ?>" width="300px" height="150px" />
                        <?php else: ?>
                            <h5 class="plan-location-title ">No Image Found</h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="d-flex align-items-center my-3">
                        <h6 class="cost-details-title">Closing Kilometer :</h6>
                        <?php if ($driver_closing_km): ?>
                            <h4 class="cost-details-amount fw-bold fs-5 mx-2"><?= $driver_closing_km; ?> KM</h4>
                        <?php else: ?>
                            <h4 class="cost-details-amount fw-bold fs-5 mx-2">NAN</h4>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($closing_speedmeter_image): ?>
                            <img src="uploads/driver_speedmeter_gallery/<?= $closing_speedmeter_image; ?>" width="300px" height="150px" />
                        <?php else: ?>
                            <h5 class="plan-location-title ">No Image Found</h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            function closeDriverKilometerModal() {
                const modalElement = document.getElementById('addDRIVERKILOMETERFORM');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);

                // Check if the modal instance exists
                if (modalInstance) {
                    // Destroy the modal instance to clean up
                    modalInstance.dispose();
                }

                // Remove the modal element from the DOM
                if (modalElement) {
                    modalElement.classList.remove('show'); // Remove 'show' class to hide modal
                    modalElement.setAttribute('aria-hidden', 'true'); // Set aria-hidden to true for accessibility
                    modalElement.style.display = 'none'; // Ensure the modal is completely hidden
                }

                // Remove the backdrop manually if it's still there
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.classList.remove('fade', 'show'); // Remove fade and show classes to remove opacity
                    backdrop.remove(); // Remove the backdrop element from the DOM
                }

                // Optional: Remove focus from the active element (to address accessibility issue)
                if (document.activeElement) {
                    document.activeElement.blur();
                }

                // Ensure that body scroll is restored and padding-right is removed
                setTimeout(() => {
                    // Remove 'modal-open' class and restore page scroll
                    document.body.classList.remove('modal-open');

                    // Remove any 'padding-right' if added by Bootstrap to compensate for the scrollbar
                    document.body.style.paddingRight = '';

                    // Re-enable scrolling on the page (if needed, you can also add an additional scroll restoration here)
                    document.body.style.overflow = ''; // Ensure overflow is reset (if manually modified)
                }, 100);
            }
        </script>

    <?php elseif ($_GET['type'] == 'driver_rating') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $customer_rating_ID = $_GET['ID'];

        // SELECT `customer_feedback_ID`, `customer_id`, `itinerary_plan_ID`, `itinerary_route_ID`, `customer_rating`, `feedback_description`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_customer_feedback` WHERE 1


        if ($customer_rating_ID != '' && $customer_rating_ID != 0) :

            $select_customerrating_list = sqlQUERY_LABEL("SELECT `customer_feedback_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `customer_rating`, `feedback_description` FROM `dvi_confirmed_itinerary_customer_feedback` WHERE `deleted` = '0' AND  `customer_feedback_ID` = '$customer_rating_ID'") or die("#1-UNABLE_TO_COLLECT_vehicle_type_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_customerrating_list)) :
                $customer_feedback_ID = $fetch_data['customer_feedback_ID'];
                $customer_rating = $fetch_data['customer_rating'];
                $feedback_description = $fetch_data['feedback_description'];
            endwhile;
            $btn_label = 'Update';
            $title_label = 'Edit';
        else :
            $btn_label = 'Save';
            $title_label = 'Add';
        endif;

        if ($customer_rating == '1'):
            $rating_select1 = 'selected';
        elseif ($customer_rating == '2'):
            $rating_select2 = 'selected';
        elseif ($customer_rating == '3'):
            $rating_select3 = 'selected';
        elseif ($customer_rating == '4'):
            $rating_select4 = 'selected';
        elseif ($customer_rating == '5'):
            $rating_select5 = 'selected';
        endif;

    ?>
        <!-- Plugins css Ends-->
        <form id="customerrating_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2"><?= $title_label; ?> Rating Modal</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Rating<span class=" text-danger"> *</span></label>
                <select class="form-select" name="driver_rating" id="driver_rating" data-parsley-trigger="keyup" required>
                    <option value="">Choose the Rating </option>
                    <option value="1" <?= $rating_select1; ?>>1 Star</option>
                    <option value="2" <?= $rating_select2; ?>>2 Star</option>
                    <option value="3" <?= $rating_select3; ?>>3 Star</option>
                    <option value="4" <?= $rating_select4; ?>>4 Star</option>
                    <option value="5" <?= $rating_select5; ?>>5 Star</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">Notes<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="review_description" name="review_description" class="form-control" placeholder="Enter the Notes"><?= $feedback_description; ?></textarea>
                    <input type="hidden" name="hidden_feedback_ID" id="hidden_feedback_ID" value="<?= $customer_feedback_ID; ?>" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });


                //AJAX FORM SUBMIT
                $("#customerrating_details_form").submit(function(event) {
                    var form = $('#customerrating_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_dailymoment_manage.php?type=driverrating&Plan_id=<?= $itinerary_plan_ID; ?>&Route_id=<?= $itinerary_route_ID; ?>',
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
                            TOAST_NOTIFICATION('error', 'Feedback not updated !', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#customerrating_details_form')[0].reset();
                                $('#ratingMODALINFODATA').modal('hide');
                                window.location.reload();
                                TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.i_result == false) {
                                //RESULT FAILED

                                TOAST_NOTIFICATION('error', 'Feedback not updated !', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
    <?php elseif ($_GET['type'] == 'not_visiting') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $route_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_type_ID = $_GET['TYPE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT `driver_hotspot_status`, `driver_not_visited_description`, `hotspot_ID`, `item_type` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$itinerary_type_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
            $item_type = $fetch_data['item_type'];
            $hotspot_ID = $fetch_data['hotspot_ID'];
            $driver_not_visited_description = $fetch_data['driver_not_visited_description'];
        endwhile;

        if ($item_type == 4):
            $get_title = getHOTSPOTDETAILS($hotspot_ID, 'label');
        elseif ($item_type == 6):
            $get_title = getHOTEL_DETAIL(get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'hotel_id'), '', 'label');
        elseif ($item_type == 7):
            $get_title = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location');
        endif;

    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Hotspot NotVisited Confirmation </h4>
                    <h6 class="text-primary">[ <?= $get_title; ?>]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-hotspot-id="<?php echo $route_hotspot_ID; ?>"
                    data-type-id="<?php echo $itinerary_type_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var typeID = saveButton.getAttribute('data-type-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, hotspotID, typeID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, item_type, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var ROUTE_HOTSPOT_ID = route_hotspot_ID;
                var TYPE_ID = item_type;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php?type=hotspotstatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        routehotspot_ID: ROUTE_HOTSPOT_ID,
                        type_ID: TYPE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'not_visiting_guide') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_guide_ID = $_GET['GUIDE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT  `guide_id` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_guide_ID` = '$itinerary_guide_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $guide_id = $fetch_data['guide_id'];
        endwhile;


    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Guide NotVisited Confirmation </h4>
                    <h6 class="text-primary">[ <?= getGUIDEDETAILS($guide_id, 'label'); ?> ]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-guide-id="<?php echo $itinerary_guide_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            // function handleSaveClick() {
            //     var notDescription = document.getElementById('not_description').value;
            //     var saveButton = document.getElementById('save-button');

            //     var planID = saveButton.getAttribute('data-plan-id');
            //     var routeID = saveButton.getAttribute('data-route-id');
            //     var guideID = saveButton.getAttribute('data-guide-id');
            //     var status = saveButton.getAttribute('data-status');

            //     togglestatusITEM(status, planID, routeID, guideID, notDescription);
            // }

            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var guideID = saveButton.getAttribute('data-guide-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, guideID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, guideID, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = guideID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php?type=guidestatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDGUIDEFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>
    <?php elseif ($_GET['type'] == 'not_visiting_wholedayguide') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_guide_ID = $_GET['GUIDE_ID'];
        $itinerary_status = $_GET['STATUS'];

        $selected_query = sqlQUERY_LABEL("SELECT  `guide_id` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `route_guide_ID` = '$itinerary_guide_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $guide_id = $fetch_data['guide_id'];
        endwhile;


    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Guide NotVisited Confirmation </h4>
                    <h6 class="text-primary">[ <?= getGUIDEDETAILS($guide_id, 'label'); ?> ]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-guide-id="<?php echo $itinerary_guide_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var guideID = saveButton.getAttribute('data-guide-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, guideID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, itinerary_plan_ID, itinerary_route_ID, guideID, not_description) {
                var PLAN_ID = itinerary_plan_ID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = itinerary_route_ID;
                var GUIDE_ID = guideID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php?type=wholeday_guidestatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_guide_ID: GUIDE_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDWHOLEDAYGUIDEFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>
    <?php elseif ($_GET['type'] == 'not_visiting_activity') :

        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $itinerary_activity_ID = $_GET['ACTIVITY_ID'];
        $itinerary_hotspot_ID = $_GET['HOTSPOT_ID'];
        $itinerary_status = $_GET['STATUS'];



        $selected_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$itinerary_hotspot_ID' AND `route_activity_ID` = '$itinerary_activity_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $activity_ID = $fetch_data['activity_ID'];
        endwhile;
    ?>
        <!-- Plugins css Ends-->
        <form id="notvisiting_details_form" class="row g-3" action="engine/ajax/ajax_dailymoment_manage.php" method="get" data-parsley-validate>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Activity NotVisited Confirmation </h4>
                    <h6 class="text-primary">[ <?= getACTIVITYDETAILS($activity_ID, 'label', ''); ?>]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="col-12 mt-0">
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" required class="form-control" placeholder="Enter the Notes"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-button"
                    data-plan-id="<?php echo $itinerary_plan_ID; ?>"
                    data-route-id="<?php echo $itinerary_route_ID; ?>"
                    data-activity-id="<?php echo $itinerary_activity_ID; ?>"
                    data-hotspot-id="<?php echo $itinerary_hotspot_ID; ?>"
                    data-status="<?php echo $itinerary_status; ?>"
                    onclick="handleSaveClick()">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Parsley validation on the form
                $('#notvisiting_details_form').parsley();

                // Handle form submission
                $('#notvisiting_details_form').on('submit', function(event) {
                    event.preventDefault();

                    // Check if form is valid using Parsley
                    if ($(this).parsley().isValid()) {
                        var notDescription = document.getElementById('not_description').value;
                        var saveButton = document.getElementById('save-button');

                        var planID = saveButton.getAttribute('data-plan-id');
                        var routeID = saveButton.getAttribute('data-route-id');
                        var activityID = saveButton.getAttribute('data-activity-id');
                        var hotspotID = saveButton.getAttribute('data-hotspot-id');
                        var status = saveButton.getAttribute('data-status');

                        togglestatusITEM(status, planID, routeID, activityID, hotspotID, notDescription);
                    }
                });
            });

            function togglestatusITEM(status, planID, routeID, route_activity_ID, route_hotspot_ID, not_description) {
                var PLAN_ID = planID;
                var SELECTED_STATUS = status;
                var ROUTE_ID = routeID;
                var ACTIVITY_ID = route_activity_ID;
                var HOTSPOT_ID = route_hotspot_ID;
                var DESCRIPTION = not_description;

                $.ajax({
                    url: 'engine/ajax/ajax_dailymoment_manage.php?type=activitystatus',
                    type: 'GET', // You can also use 'POST'
                    data: {
                        plan_ID: PLAN_ID,
                        status: SELECTED_STATUS,
                        route_ID: ROUTE_ID,
                        route_activity_ID: ACTIVITY_ID,
                        route_hotspot_ID: HOTSPOT_ID,
                        description: DESCRIPTION // Add the description to the data sent
                    },
                    success: function(response) {
                        console.log(response); // Log the response to the console
                        var response = JSON.parse(response);
                        if (response.result_success) {
                            TOAST_NOTIFICATION('success', 'Status updated Successfully', 'Success !!!');

                            $('#addNOTVISITEDACTIVITYFORM').modal('hide');

                            window.location.reload();

                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update status', 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                        TOAST_NOTIFICATION('error', 'AJAX error occurred', 'Error !!!');
                    }
                });
            }
        </script>

    <?php elseif ($_GET['type'] == 'edit_guide') :
        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $GUIDEID = $_GET['GUIDEID'];


        $selected_query = sqlQUERY_LABEL("SELECT `confirmed_route_guide_ID`, `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_id`, `driver_guide_status`, `driver_not_visited_description`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_guide_ID` = '$GUIDEID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_guide_status = $fetch_data['driver_guide_status'];
            $guide_id = $fetch_data['guide_id'];
            $driver_not_visited_description = $fetch_data['driver_not_visited_description'];
        endwhile;

        $selectedvisit = '';
        $selectednotvisit = ''; // Initialize as empty

        if ($driver_guide_status == '1'):
            $selectedvisit = 'selected';
        elseif ($driver_guide_status == '2'):
            $selectednotvisit = 'selected';
        else:
            $selectednone = '';
        endif;
    ?>
        <!-- Plugins css Ends-->
        <form id="driverrating_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Edit Guide Status</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Status</label>
                <select class="form-select" name="status" id="status" data-parsley-trigger="keyup">
                    <option value="">Choose the Status</option>
                    <option value="1" <?= $selectedvisit; ?>>Visit</option>
                    <option value="2" <?= $selectednotvisit; ?>>Not-Visited</option>
                </select>
            </div>
            <div class="col-12 mt-2" id="description-field" style="display:none;"> <!-- Hidden by default -->
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" class="form-control" placeholder="Enter the Notes"><?= $driver_not_visited_description; ?></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                var selectedStatus = $('#status').val();
                var initialDescription = "<?= $driver_not_visited_description; ?>"; // PHP value for the initial description

                // Show description field if the default selection is "Not Visited" (value = 2)
                if (selectedStatus == '2') {
                    $('#description-field').show();
                    $('#not_description').val(initialDescription); // Set the value from PHP
                    $('#not_description').prop('required', true); // Make it required
                }

                // Show/Hide the description field based on the selected status
                $('#status').on('change', function() {
                    selectedStatus = $(this).val();
                    if (selectedStatus == '2') {
                        $('#description-field').show(); // Show description if "Not Visited"
                        $('#not_description').val(initialDescription); // Set the value from PHP
                        $('#not_description').prop('required', true); // Make it required
                    } else {
                        $('#description-field').hide(); // Hide description otherwise
                        $('#not_description').val(''); // Clear description when hidden
                        $('#not_description').prop('required', false); // Remove required
                    }
                });

                $("#driverrating_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var form = $('#driverrating_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    var selectedStatus = $("#status").val();
                    var notDescription = $("#not_description").val(); // Get the description value
                    var submitButton = $(this).find("button[type='submit']");
                    var routeGuideId = <?= json_encode($GUIDEID); ?>; // PHP variable passed into JS

                    submitButton.prop('disabled', true); // Disable the submit button

                    // Dynamically construct the URL
                    var url = 'engine/ajax/ajax_dailymoment_manage.php?type=guidestatus&plan_ID=<?= $itinerary_plan_ID; ?>&route_ID=<?= $itinerary_route_ID; ?>&route_guide_ID=' + routeGuideId + '&description=' + notDescription + '&status=' + selectedStatus;

                    console.log(url); // Log the URL for debugging purposes

                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                        } else {
                            spinner.hide();

                            if (response.result_success == true) {
                                // Close modal after updating the label
                                $('#driverrating_details_form')[0].reset();
                                $('#editMODALINFODATA').modal('hide');

                                console.log("Selected Status: ", selectedStatus); // Debugging status
                                console.log("Route Guide ID: ", routeGuideId); // Debugging guide ID
                                console.log($("#guidecontainer-" + routeGuideId)); // Debug if element exists

                                if (selectedStatus == 1) {
                                    // If status is "Visited"
                                    $("#guidecontainer-" + routeGuideId).html(`
                                <span id="visited-badge-${routeGuideId}" class="badge badge-dailymoment-visited">
                                    <i class="ti ti-check fs-6 me-1"></i>Visited
                                </span>
                                <span class="cursor-pointer" onclick="showEDITSTATUSMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeGuideId})" data-bs-dismiss="modal">
                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                </span>
                        `);
                                } else if (selectedStatus == 2) {
                                    // If status is "Not Visited"
                                    $("#guidecontainer-" + routeGuideId).html(`
                            <span id="not-visited-badge-${routeGuideId}" class="badge badge-dailymoment-notvisited">
                                <i class="ti ti-x fs-6 me-1"></i>Not Visited
                            </span>
                            <span class="cursor-pointer" onclick="showEDITSTATUSMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeGuideId})" data-bs-dismiss="modal">
                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                            </span>
                        `);
                                }

                                // Display a success toast
                                TOAST_NOTIFICATION('success', 'Status updated successfully', 'Success !!!');

                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                            }
                        }
                    }).fail(function(xhr, status, error) {
                        console.error("AJAX error:", status, error); // Log AJAX errors if any
                    });
                });
            });
        </script>

    <?php elseif ($_GET['type'] == 'edit_wholedayguide') :
        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $GUIDEID = $_GET['GUIDEID'];


        $selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`, `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time`, `wholeday_guidehotspot_status`, `guide_not_visited_description`, `driver_trip_completed`, `guide_trip_completed`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $wholeday_guidehotspot_status = $fetch_data['wholeday_guidehotspot_status'];
            $guide_not_visited_description = $fetch_data['guide_not_visited_description'];
        endwhile;

        $selectedvisit = '';
        $selectednotvisit = ''; // Initialize as empty

        if ($wholeday_guidehotspot_status == '1'):
            $selectedvisit = 'selected';
        elseif ($wholeday_guidehotspot_status == '2'):
            $selectednotvisit = 'selected';
        else:
            $selectednone = '';
        endif;
    ?>
        <!-- Plugins css Ends-->
        <form id="driverguide_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                    <h4 class="mb-2">Edit Guide Status</h4>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Status</label>
                <select class="form-select" name="status" id="status" data-parsley-trigger="keyup">
                    <option value="">Choose the Status</option>
                    <option value="1" <?= $selectedvisit; ?>>Visit</option>
                    <option value="2" <?= $selectednotvisit; ?>>Not-Visited</option>
                </select>
            </div>
            <div class="col-12 mt-2" id="description-field" style="display:none;"> <!-- Hidden by default -->
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description" name="not_description" class="form-control" placeholder="Enter the Notes"><?= $guide_not_visited_description; ?></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                var selectedStatus = $('#status').val();
                var initialDescription = "<?= $guide_not_visited_description; ?>"; // PHP value for the initial description

                // Show description field if the default selection is "Not Visited" (value = 2)
                if (selectedStatus == '2') {
                    $('#description-field').show();
                    $('#not_description').val(initialDescription); // Set the value from PHP
                    $('#not_description').prop('required', true); // Make it required
                }

                // Show/Hide the description field based on the selected status
                $('#status').on('change', function() {
                    selectedStatus = $(this).val();
                    if (selectedStatus == '2') {
                        $('#description-field').show(); // Show description if "Not Visited"
                        $('#not_description').val(initialDescription); // Set the value from PHP
                        $('#not_description').prop('required', true); // Make it required
                    } else {
                        $('#description-field').hide(); // Hide description otherwise
                        $('#not_description').val(''); // Clear description when hidden
                        $('#not_description').prop('required', false); // Remove required
                    }
                });

                $("#driverguide_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var form = $('#driverguide_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    var selectedStatus = $("#status").val();
                    var notDescription = $("#not_description").val(); // Get the description value
                    var submitButton = $(this).find("button[type='submit']");
                    var routeGuideId = <?= json_encode($GUIDEID); ?>; // PHP variable passed into JS

                    submitButton.prop('disabled', true); // Disable the submit button

                    // Dynamically construct the URL
                    var url = 'engine/ajax/ajax_dailymoment_manage.php?type=wholeday_guidestatus&plan_ID=<?= $itinerary_plan_ID; ?>&route_ID=<?= $itinerary_route_ID; ?>&route_guide_ID=' + routeGuideId + '&description=' + notDescription + '&status=' + selectedStatus;

                    console.log(url); // Log the URL for debugging purposes

                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                        } else {
                            spinner.hide();

                            if (response.result_success == true) {
                                // Close modal after updating the label
                                $('#driverguide_details_form')[0].reset();
                                $('#editMODALINFODATAWHOLEDAYGUIDE').modal('hide');

                                console.log("Selected Status: ", selectedStatus); // Debugging status
                                console.log("Route Guide ID: ", routeGuideId); // Debugging guide ID
                                console.log($("#wholedayguidecontainer-" + routeGuideId)); // Debug if element exists

                                if (selectedStatus == 1) {
                                    // If status is "Visited"
                                    $("#wholedayguidecontainer-" + routeGuideId).html(`
                                <span id="visited-badge-${routeGuideId}" class="badge badge-dailymoment-visited">
                                    <i class="ti ti-check fs-6 me-1"></i>Visited
                                </span>
                                <span class="cursor-pointer" onclick="showEDITSTATUSMODALWHOLEDAYGUIDE(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeGuideId})" data-bs-dismiss="modal">
                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                </span>`);
                                } else if (selectedStatus == 2) {
                                    // If status is "Not Visited"
                                    $("#wholedayguidecontainer-" + routeGuideId).html(`
                            <span id="not-visited-badge-${routeGuideId}" class="badge badge-dailymoment-notvisited">
                                <i class="ti ti-x fs-6 me-1"></i>Not Visited
                            </span>
                            <span class="cursor-pointer" onclick="showEDITSTATUSMODALWHOLEDAYGUIDE(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeGuideId})" data-bs-dismiss="modal">
                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                            </span>`);
                                }

                                // Display a success toast
                                TOAST_NOTIFICATION('success', 'Status updated successfully', 'Success !!!');

                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                            }
                        }
                    }).fail(function(xhr, status, error) {
                        console.error("AJAX error:", status, error); // Log AJAX errors if any
                    });
                });
            });
        </script>

    <?php elseif ($_GET['type'] == 'edit_hotspot') :
        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $HOTSPOTID = $_GET['HOTSPOTID'];
        $TYPEID = $_GET['TYPEID'];

        $selected_query = sqlQUERY_LABEL("SELECT `driver_hotspot_status`, `driver_not_visited_description`, `hotspot_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$HOTSPOTID' AND `item_type` = '$TYPEID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_hotspot_status = $fetch_data['driver_hotspot_status'];
            $hotspot_ID = $fetch_data['hotspot_ID'];
            $driver_not_visited_description = $fetch_data['driver_not_visited_description'];
        endwhile;

        $selectedvisit = '';
        $selectednotvisit = ''; // Initialize as empty

        if ($driver_hotspot_status == '1'):
            $selectedvisit = 'selected';
        elseif ($driver_hotspot_status == '2'):
            $selectednotvisit = 'selected';
        else:
            $selectednone = '';
        endif;
    ?>
        <!-- Plugins css Ends-->
        <form id="edithotspot_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Edit Hotspot Status </h4>
                    <h6 class="text-primary">[<?= getHOTSPOTDETAILS($hotspot_ID, 'label'); ?>]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Status<span class="text-danger"> *</span></label>
                <select class="form-select" name="status" id="status" data-parsley-trigger="keyup" required>
                    <option value="">Choose the Status</option>
                    <option value="1" <?= $selectedvisit; ?>>Visit</option>
                    <option value="2" <?= $selectednotvisit; ?>>Not-Visited</option>
                </select>
            </div>
            <div class="col-12 mt-2" id="description-field-hotspot" style="display:none;"> <!-- Hidden by default -->
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description_hotspot" name="not_description_hotspot" class="form-control" placeholder="Enter the Notes"><?= $driver_not_visited_description; ?></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                var selectedStatus = $('#status').val();
                var initialDescription = "<?= $driver_not_visited_description; ?>"; // PHP value for the initial description

                // Show description field if the default selection is "Not Visited" (value = 2)
                if (selectedStatus == '2') {
                    $('#description-field-hotspot').show();
                    $('#not_description_hotspot').val(initialDescription); // Set the value from PHP
                    $('#not_description_hotspot').prop('required', true); // Make it required
                }

                // Show/Hide the description field based on the selected status
                $('#status').on('change', function() {
                    selectedStatus = $(this).val();
                    if (selectedStatus == '2') {
                        $('#description-field-hotspot').show(); // Show description if "Not Visited"
                        $('#not_description_hotspot').val(initialDescription); // Set the value from PHP
                        $('#not_description_hotspot').prop('required', true); // Make it required
                    } else {
                        $('#description-field-hotspot').hide(); // Hide description otherwise
                        $('#not_description_hotspot').val(''); // Clear description when hidden
                        $('#not_description_hotspot').prop('required', false); // Remove required
                    }
                });


                $("#edithotspot_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var form = $('#edithotspot_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    var selectedStatus = $("#status").val();
                    var notDescription = $("#not_description_hotspot").val(); // Get the description value
                    var submitButton = $(this).find("button[type='submit']");
                    var routeHOTSPOTID = <?= json_encode($HOTSPOTID); ?>; // PHP variable passed into JS

                    submitButton.prop('disabled', true); // Disable the submit button

                    // Dynamically construct the URL
                    var url = 'engine/ajax/ajax_dailymoment_manage.php?type=hotspotstatus&plan_ID=<?= $itinerary_plan_ID; ?>&route_ID=<?= $itinerary_route_ID; ?>&type_ID=<?= $TYPEID; ?>&routehotspot_ID=' + routeHOTSPOTID + '&description=' + notDescription + '&status=' + selectedStatus;

                    // if (selectedStatus == '2') {
                    //     // Append the description only if 'Not Visited' is selected
                    //     data.append('not_description_hotspot', notDescription);
                    // }


                    console.log(url); // Log the URL for debugging purposes

                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                        } else {
                            spinner.hide();

                            if (response.result_success == true) {
                                // Close modal after updating the label
                                $('#edithotspot_details_form')[0].reset();
                                $('#editMODALHOTSPOTDATA').modal('hide');

                                console.log("Selected Status: ", selectedStatus); // Debugging status
                                console.log("Route hotspot ID: ", routeHOTSPOTID); // Debugging guide ID
                                console.log($("#container-" + routeHOTSPOTID)); // Debug if element exists

                                if (selectedStatus == 1) {
                                    // If status is "Visited"
                                    $("#container-" + routeHOTSPOTID).html(`
                                    <span id="visited-badge-${routeHOTSPOTID}" class="badge badge-dailymoment-visited">
                                        <i class="ti ti-check fs-6 me-1"></i>Visited
                                    </span>
                                    <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeHOTSPOTID},<?= $TYPEID; ?>)" data-bs-dismiss="modal">
                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                    </span>
                                `);
                                } else if (selectedStatus == 2) {
                                    // If status is "Not Visited"
                                    $("#container-" + routeHOTSPOTID).html(`
                                    <span id="not-visited-badge-${routeHOTSPOTID}" class="badge badge-dailymoment-notvisited">
                                        <i class="ti ti-x fs-6 me-1"></i>Not Visited
                                    </span>
                                    <span class="cursor-pointer" onclick="showEDITHOTSPOTMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeHOTSPOTID}, <?= $TYPEID; ?>)" data-bs-dismiss="modal">
                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                    </span>
                                `);
                                }

                                // Display a success toast
                                TOAST_NOTIFICATION('success', 'Status updated successfully', 'Success !!!');

                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                            }
                        }
                    }).fail(function(xhr, status, error) {
                        console.error("AJAX error:", status, error); // Log AJAX errors if any
                    });
                });
            });
        </script>

    <?php elseif ($_GET['type'] == 'edit_activity') :
        $itinerary_plan_ID = $_GET['PLAN_ID'];
        $itinerary_route_ID = $_GET['ROUTE_ID'];
        $HOTSPOTID = $_GET['HOTSPOTID'];
        $ACTIVITYID = $_GET['ACTIVITYID'];

        $selected_query = sqlQUERY_LABEL("SELECT `confirmed_route_activity_ID`, `route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `activity_ID`, `driver_activity_status`, `driver_not_visited_description` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$HOTSPOTID' AND `route_activity_ID` = '$ACTIVITYID'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
            $driver_activity_status = $fetch_data['driver_activity_status'];
            $activity_ID = $fetch_data['activity_ID'];
            $driver_not_visited_description = $fetch_data['driver_not_visited_description'];
        endwhile;

        $selectedvisit = '';
        $selectednotvisit = ''; // Initialize as empty

        if ($driver_activity_status == '1'):
            $selectedvisit = 'selected';
        elseif ($driver_activity_status == '2'):
            $selectednotvisit = 'selected';
        else:
            $selectednone = '';
        endif;
    ?>
        <!-- Plugins css Ends-->
        <form id="editactivity_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2">Edit Activity Status</h4>
                    <h6 class="text-primary">[ <?= getACTIVITYDETAILS($activity_ID, 'label', ''); ?>]</h6>
                </div>
            </div>
            <span id="response_modal"></span>
            <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Status</label>
                <select class="form-select" name="status" id="status" data-parsley-trigger="keyup">
                    <option value="">Choose the Status</option>
                    <option value="1" <?= $selectedvisit; ?>>Visit</option>
                    <option value="2" <?= $selectednotvisit; ?>>Not-Visited</option>
                </select>
            </div>
            <div class="col-12 mt-2" id="description-field-activity" style="display:none;"> <!-- Hidden by default -->
                <label class="form-label w-100" for="modalAddCardCvv">Description<span class="text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="not_description_activity" name="not_description_activity" class="form-control" placeholder="Enter the Notes"><?= $driver_not_visited_description; ?></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                var selectedStatus = $('#status').val();
                var initialDescription = "<?= $driver_not_visited_description; ?>"; // PHP value for the initial description

                // Show description field if the default selection is "Not Visited" (value = 2)
                if (selectedStatus == '2') {
                    $('#description-field-activity').show();
                    $('#not_description_activity').val(initialDescription); // Set the value from PHP
                    $('#not_description_activity').prop('required', true); // Make it required
                }

                // Show/Hide the description field based on the selected status
                $('#status').on('change', function() {
                    selectedStatus = $(this).val();
                    if (selectedStatus == '2') {
                        $('#description-field-activity').show(); // Show description if "Not Visited"
                        $('#not_description_activity').val(initialDescription); // Set the value from PHP
                        $('#not_description_activity').prop('required', true); // Make it required
                    } else {
                        $('#description-field-activity').hide(); // Hide description otherwise
                        $('#not_description_activity').val(''); // Clear description when hidden
                        $('#not_description_activity').prop('required', false); // Remove required
                    }
                });

                $("#editactivity_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var form = $('#editactivity_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    var selectedStatus = $("#status").val();
                    var notDescription = $("#not_description_activity").val(); // Get the description value
                    var submitButton = $(this).find("button[type='submit']");
                    var routeACTIVITYID = <?= json_encode($ACTIVITYID); ?>; // PHP variable passed into JS

                    submitButton.prop('disabled', true); // Disable the submit button

                    // Dynamically construct the URL
                    var url = 'engine/ajax/ajax_dailymoment_manage.php?type=activitystatus&plan_ID=<?= $itinerary_plan_ID; ?>&route_ID=<?= $itinerary_route_ID; ?>&route_hotspot_ID=<?= $HOTSPOTID; ?>&route_activity_ID=' + routeACTIVITYID + '&description=' + notDescription + '&status=' + selectedStatus;

                    console.log(url); // Log the URL for debugging purposes

                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                        } else {
                            spinner.hide();

                            if (response.result_success == true) {
                                // Close modal after updating the label
                                $('#editactivity_details_form')[0].reset();
                                $('#editMODALACTIVITYDATA').modal('hide');

                                console.log("Selected Status: ", selectedStatus); // Debugging status
                                console.log("Route hotspot ID: ", routeACTIVITYID); // Debugging guide ID
                                console.log($("#activitycontainer-" + routeACTIVITYID)); // Debug if element exists

                                if (selectedStatus == 1) {
                                    // If status is "Visited"
                                    $("#activitycontainer-" + routeACTIVITYID).html(`
                                    <span id="visited-badge-${routeACTIVITYID}" class="badge badge-dailymoment-visited">
                                        <i class="ti ti-check fs-6 me-1"></i>Visited
                                    </span>
                                    <span class="cursor-pointer" onclick="showEDITACTIVITYMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeACTIVITYID},<?= $HOTSPOTID; ?>)" data-bs-dismiss="modal">
                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                    </span>
                                `);
                                } else if (selectedStatus == 2) {
                                    // If status is "Not Visited"
                                    $("#activitycontainer-" + routeACTIVITYID).html(`
                                    <span id="not-visited-badge-${routeACTIVITYID}" class="badge badge-dailymoment-notvisited">
                                        <i class="ti ti-x fs-6 me-1"></i>Not Visited
                                    </span>
                                    <span class="cursor-pointer" onclick="showEDITACTIVITYMODAL(<?= $itinerary_plan_ID; ?>, <?= $itinerary_route_ID; ?>, ${routeACTIVITYID}, <?= $HOTSPOTID; ?>)" data-bs-dismiss="modal">
                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                    </span>
                                `);
                                }

                                // Display a success toast
                                TOAST_NOTIFICATION('success', 'Status updated successfully', 'Success !!!');

                            } else if (response.result == false) {
                                TOAST_NOTIFICATION('error', 'Status not updated', 'Error !!!');
                            }
                        }
                    }).fail(function(xhr, status, error) {
                        console.error("AJAX error:", status, error); // Log AJAX errors if any
                    });
                });
            });
        </script>

<?php
    endif;
endif;
?>