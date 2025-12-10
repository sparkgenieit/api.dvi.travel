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
        $vehicle_type = $_POST['vehicle_type'];
        // Process the data and return a response
        // echo "vehicle Type: $vehicle_type";
?>
<div class="row mt-4">
    <div class="col-md-12">
        <h5>Toll Price List</h5>
        <div class="card-body dataTable_select text-nowrap">
            <div class="card-body dataTable_select text-nowrap">
                <div class="table-responsive table-bordered">
                    <table class="table table-hover" id="toll_pricebook_LIST">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Source Location</th>
                                <th scope="col">Destination Location</th>
                                <th scope="col">Vehicle Type</th>
                                <th scope="col">Toll Charge</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var dataTable = $('#toll_pricebook_LIST').DataTable({
    dom: 'lfrtip',
    "bFilter": true,
    ajax: {
        "url": "engine/json/__JSONtollpricebook_latest.php",
        "type": "GET",
        "data": function(d) {
            d.vehicle_type = '<?= $vehicle_type; ?>';
        },
    },
    "columns": [{
            "data": "count"
        },
        {
            "data": "source_location"
        },
        {
            "data": "destination_location"
        },
        {
            "data": "vehicle_type_name"
        },
        {
            "data": "toll_charge"
        }
    ]
});
</script>
<?php
    endif;
endif;

?>