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
        $state = $_POST['state'];
        $city = $_POST['city'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        // echo "State: $state, City: $city, Month: $month, Year: $year";
?>
        <div class="row mt-4">
            <div class="col-md-12">
                <h5>Amenities Price List</h5>
                <div class="card-body dataTable_select text-nowrap">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table class="table table-hover" id="hotelamenities_LIST">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Hotel Name</th>
                                    <th scope="col">Amentities Name</th>
                                    <th scope="col">Amentities Type</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Month</th>

                                    <?php for ($day_count = 1; $day_count <= 31; $day_count++) : ?>
                                        <th scope="col">Day <?= $day_count; ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var currencySymbol = '<?= $global_currency_format; ?>'; // '₹' from PHP config
            var dataTable = $('#hotelamenities_LIST').DataTable({
                dom: 'lfrtip',
                "bFilter": true,
                ajax: {
                    "url": "engine/json/__JSONhotelamenities_pricebook_latest.php",
                    "type": "GET",
                    "data": function(d) {
                        d.state = '<?= $state; ?>'; // Replace 'state_value' with the actual state
                        d.city = '<?= $city; ?>'; // Replace 'city_value' with the actual city
                        d.month = '<?= $month; ?>'; // Replace 'month_value' with the actual month
                        d.year = '<?= $year; ?>'; // Replace 'year_value' with the actual year
                    },
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "hotel_name"
                    }, //1
                    {
                        data: "amenities_title"
                    }, //2
                    {
                        data: "pricetype"
                    }, //3
                    {
                        data: "year"
                    }, //5
                    {
                        data: "month"
                    }, //6
                    {
                        data: "day_1"
                    }, //7
                    {
                        data: "day_2"
                    }, //8
                    {
                        data: "day_3"
                    }, //9
                    {
                        data: "day_4"
                    }, //10
                    {
                        data: "day_5"
                    }, //11
                    {
                        data: "day_6"
                    }, //12
                    {
                        data: "day_7"
                    }, //13
                    {
                        data: "day_8"
                    }, //14
                    {
                        data: "day_9"
                    }, //15
                    {
                        data: "day_10"
                    }, //16
                    {
                        data: "day_11"
                    }, //17
                    {
                        data: "day_12"
                    }, //18
                    {
                        data: "day_13"
                    }, //19
                    {
                        data: "day_14"
                    }, //20
                    {
                        data: "day_15"
                    }, //21
                    {
                        data: "day_16"
                    }, //22
                    {
                        data: "day_17"
                    }, //23
                    {
                        data: "day_18"
                    }, //24
                    {
                        data: "day_19"
                    }, //25
                    {
                        data: "day_20"
                    }, //26
                    {
                        data: "day_21"
                    }, //27
                    {
                        data: "day_22"
                    }, //28
                    {
                        data: "day_23"
                    }, //29
                    {
                        data: "day_24"
                    }, //30
                    {
                        data: "day_25"
                    }, //31
                    {
                        data: "day_26"
                    }, //32
                    {
                        data: "day_27"
                    }, //33
                    {
                        data: "day_28"
                    }, //34
                    {
                        data: "day_29"
                    }, //35
                    {
                        data: "day_30"
                    }, //36
                    {
                        data: "day_31"
                    }, //37
                ],
            });
        </script>

<?php
    endif;
endif;
?>