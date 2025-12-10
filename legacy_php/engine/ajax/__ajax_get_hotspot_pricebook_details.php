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
        $hotspot_location = $_POST['hotspot_location'];
        // Process the data and return a response
        // echo "hotspot location: $hotspot_location";
?>
<div class="row mt-4">
    <div class="col-md-12">
        <h5>Toll Price List</h5>
        <div class="card-body dataTable_select text-nowrap">
            <div class="card-body dataTable_select text-nowrap">
                <div class="table-responsive table-bordered">
                    <table class="table table-hover" id="hotspot_pricebook_LIST">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Hotspot Name</th>
                                <th scope="col">Hotspot Location</th>
                                <th scope="col">India Adult</th>
                                <th scope="col">India Child</th>
                                <th scope="col">India Infant</th>
                                <th scope="col">Foreign Adult</th>
                                <th scope="col">Foreign Child</th>
                                <th scope="col">Foreign Infant</th>
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
var dataTable = $('#hotspot_pricebook_LIST').DataTable({
    dom: 'lfrtip',
    "bFilter": true,
    ajax: {
        "url": "engine/json/__JSONhotspot_pricebook_latest.php",
        "type": "GET",
        "data": function(d) {
            d.hotspot_location = '<?= $hotspot_location; ?>';
        },
    },
    "columns": [{
            "data": "count"
        },
        {
            "data": "hotspot_name"
        },
        {
            "data": "hotspot_location"
        },
        {
            "data": "hotspot_adult_entry_cost"
        },
        {
            "data": "hotspot_child_entry_cost"
        },
        {
            "data": "hotspot_infant_entry_cost"
        },
        {
            "data": "hotspot_foreign_adult_entry_cost"
        },
        {
            "data": "hotspot_foreign_child_entry_cost"
        },
        {
            "data": "hotspot_foreign_infant_entry_cost"
        }
    ]
});
</script>
<?php
    endif;
endif;

?>