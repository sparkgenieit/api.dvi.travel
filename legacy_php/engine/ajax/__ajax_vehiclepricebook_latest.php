 <div class="row">
     <div class="col-3">
         <label class="form-label" for="vendor_vehicle">Vendor<span class=" text-danger"> *</span></label>
         <select id="vendor_vehicle" name="vendor_vehicle" class="form-select form-control" data-parsley-trigger="keyup" required>
             <option value="">Choosen Vendor</option>
         </select>
     </div>
     <div class="col-md-3">
         <label class="form-label" for="vendor_branch">Vendor Branch <span class=" text-danger"> *</span></label>
         <select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
             <?= getVENDORBRANCHDETAIL($vendor_branch, $logged_vendor_id, 'select'); ?>
         </select>
     </div>
     <div class="col-md-2">
         <label class="form-label" for="vehicle_month">Month <span class="text-danger">*</span> </label>
         <select class="form-select" name="vehicle_month" id="vehicle_month">
             <option value="">Choosen Month</option>
             <option value="1">Januvary</option>
             <option value="2">Febrary</option>
             <option value="3">March</option>
             <option value="4">April</option>
         </select>
     </div>
     <div class="col-md-2">
         <label class="form-label" for="vehicle_year">Year <span class="text-danger">*</span></label>
         <div class="input-group">
             <input name="vehicle_year" id="vehicle_year" autocomplete="off" required class="form-control" placeholder="Month" />
         </div>
     </div>
     <div class="col-md-2 d-flex align-items-end justify-content-end">
         <button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
     </div>
 </div>
 <div class="row mt-4">
     <div class="col-md-12">
         <h5>Vehicle Price List</h5>
         <div class="card-body dataTable_select text-nowrap">
             <div class="text-nowrap table-responsive table-bordered">
                 <table class="table table-hover" id="language_LIST">
                     <thead>
                         <tr>
                             <th scope="col">S.No</th>
                             <th scope="col">Vehicle Name</th>
                             <th scope="col">Vendor</th>
                             <th scope="col">Branch</th>
                             <th scope="col">Month</th>
                             <th scope="col">Year</th>
                             <th scope="col">Day 1</th>
                             <th scope="col">Day 2</th>
                             <th scope="col">Day 3</th>
                             <th scope="col">Day 4</th>
                             <th scope="col">Day 5</th>
                             <th scope="col">Day 6</th>
                             <th scope="col">Day 7</th>
                             <th scope="col">Day 8</th>
                             <th scope="col">Day 9</th>
                             <th scope="col">Day 10</th>
                             <th scope="col">Day 11</th>
                             <th scope="col">Day 12</th>
                             <th scope="col">Day 13</th>
                             <th scope="col">Day 14</th>
                             <th scope="col">Day 15</th>
                             <th scope="col">Day 16</th>
                             <th scope="col">Day 17</th>
                             <th scope="col">Day 18</th>
                             <th scope="col">Day 19</th>
                             <th scope="col">Day 20</th>
                             <th scope="col">Day 21</th>
                             <th scope="col">Day 22</th>
                             <th scope="col">Day 23</th>
                             <th scope="col">Day 24</th>
                             <th scope="col">Day 25</th>
                             <th scope="col">Day 26</th>
                             <th scope="col">Day 27</th>
                             <th scope="col">Day 28</th>
                             <th scope="col">Day 29</th>
                             <th scope="col">Day 30</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr>
                             <td>1.</td>
                             <td>Sedan</td>
                             <td>Uber</td>
                             <td>Uber-Chennai</td>
                             <td>November</td>
                             <td>2024</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                             <td>₹ 500</td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>