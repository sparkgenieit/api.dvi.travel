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
             <div class="col-md-3" id="vehicletypeDiv">
                 <label class="form-label" for="vehicle_type">Vehicle Type <span class=" text-danger"> *</span></label>

                 <select id="vehicle_type" name="vehicle_type" required class="form-select form-control" onchange="changeCosttype()">

                     <?= getVEHICLETYPE($vehicle_type, 'pricebook_select') ?>
                 </select>
             </div>
             <!-- <div class="col-md-9 d-flex align-items-end justify-content-end">
         <button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
     </div> -->
             <div class="col-9 d-flex align-items-end justify-content-end">
                 <button id="export-toll-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
             </div>
         </div>
         <div id="toll_pricebook_details"></div>

         <script>
             $('#vehicle_type').selectize();

             function changeCosttype() {
                 var vehicleType = $('#vehicle_type').val(); // Get the selected vehicle type
                 if (vehicleType) {
                     $.ajax({
                         url: 'engine/ajax/__ajax_toll_pricebook_details_list.php?type=show_form', // Ensure this is the correct path
                         type: 'POST', // If you are sending data other than type via POST
                         data: {
                             vehicle_type: vehicleType
                         },
                         success: function(response) {
                             $('#toll_pricebook_details').html(response);
                         },
                         error: function(xhr, status, error) {
                             console.error('AJAX error:', status, error);
                         }
                     });
                 }
                 $('#export-toll-btn').click(function() {
                     let vehicle_type = $('#vehicle_type').val();
                     window.location.href = 'excel_export_toll_pricebook.php?vehicle_type=' + vehicle_type;
                 });
             }
         </script>

 <?php
        endif;
    endif;
    ?>