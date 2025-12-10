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
        <div class="card mb-4">
          <div class="card-header pb-3 d-flex justify-content-between">
            <div class="col-md-auto">
              <h5 class="card-title mb-3 mt-2">List of Itinerary</h5>
            </div>
            <div class="col-md-auto text-end">
              <a href="newitinerary.php?route=add&formtype=basic_info" id="add_hotel" class="btn btn-label-primary waves-effect">+ Add Itinerary</a>
            </div>
          </div>
          <div class="card-body dataTable_select text-nowrap">
            <div class="text-nowrap table-responsive table-bordered">
              <table id="itinerary_LIST" class="table table-hovera">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Days & Night</th>
                    <th>Route</th>
                    <th>OverallCost</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>No of Person</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Itinerary - Admin Payment Report
            </h5>
            <button type="button" class="fs-2" data-dismiss="modal" aria-label="Close" style="border: none; background-color: transparent">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-2">
            <div class="nav-align-top">
              <ul class="nav nav-tabs nav-fill" role="tablist">
                <li role="presentation">
                  <button type="button" class="nav-link px-4 active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-new" aria-controls="navs-justified-new" aria-selected="true">
                    Passenger & Transport
                  </button>
                </li>
                <li role="presentation">
                  <button type="button" class="nav-link px-4" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-preparing" aria-controls="navs-justified-link-preparing" aria-selected="false" tabindex="-1">
                    Vehicle - Travel Information
                  </button>
                </li>
                <li role="presentation">
                  <button type="button" class="nav-link px-4" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-link-shipping" aria-controls="navs-justified-link-shipping" aria-selected="false" tabindex="-1">
                    Total Amount
                  </button>
                </li>
              </ul>
              <div class="tab-content pb-0">
                <div class="tab-pane fade active show" id="navs-justified-new" role="tabpanel">
                  <div class="row">
                    <div class="d-flex justify-content-between">
                      <h6 class="text-primary">
                        Passenger Information
                      </h6>
                      <div>
                        <h6 class="text-dark">
                          Overall Package Cost:<span class="text-primary ms-2">₹54862.5</span>
                        </h6>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label>Passenger Name</label>
                      <p class="text-light">Manoj</p>
                    </div>
                    <div class="col-md-3">
                      <label>Date Of Booking</label>
                      <p class="text-light">25/11/2023 11.00AM</p>
                    </div>
                    <div class="col-md-3">
                      <label>Total Passenger</label>
                      <p class="text-light">2 Person</p>
                    </div>
                    <div class="col-md-3">
                      <label>Adult</label>
                      <p class="text-light">2</p>
                    </div>
                    <div class="col-md-3">
                      <label>Child[5-12]</label>
                      <p class="text-light">0</p>
                    </div>
                    <div class="col-md-3">
                      <label>Infant[<=5]< /label>
                          <p class="text-light">0</p>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-12">
                      <h6 class="text-primary">Transport Details</h6>
                      <div class="table-responsive text-nowrap">
                        <table id="transport" class="table table-hover">
                          <thead>
                            <tr>
                              <th>S.No</th>
                              <th>Travel Date</th>
                              <th>Travel DESC</th>
                              <th>Distance(Kms)</th>
                              <th>Sight Seeing Distance(kms)</th>
                              <th>Total Distance(kms)</th>
                              <th>Time</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td>25 Nov 2023</td>
                              <td>ChennaitoKanchipuram</td>
                              <td>30</td>
                              <td>25</td>
                              <td>36</td>
                              <td>04hours20minutes</td>
                            </tr>
                            <tr>
                              <td>2.</td>
                              <td>15 Aug 2023</td>
                              <td>ChennaitoTiruvarur</td>
                              <td>150</td>
                              <td>42</td>
                              <td>220</td>
                              <td>09hours45minutes</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
                  <div class="row">
                    <div class="d-flex justify-content-between">
                      <h6 class="text-primary">
                        Vehicle - Travel Information
                      </h6>
                      <div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal2-lg">
                          Transport Report
                        </button>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label>No of Vehicle Taken</label>
                      <p class="text-light">1</p>
                    </div>
                    <div class="col-md-3">
                      <label>Type of Vehicle Taken</label>
                      <p class="text-light">Innova</p>
                    </div>
                    <div class="col-md-3">
                      <label>Vehicle Origin City</label>
                      <p class="text-light">Chennai</p>
                    </div>
                    <div class="col-md-3">
                      <label>Onward Distance</label>
                      <p class="text-light">693-kms</p>
                    </div>
                    <div class="col-md-3">
                      <label>Airport/Railway Pick-up/drop Distance</label>
                      <p class="text-light">80-kms</p>
                    </div>
                    <div class="col-md-3">
                      <label>Total Distance(Including Pickup/drop)</label>
                      <p class="text-light">773-kms</p>
                    </div>
                    <div class="col-md-3">
                      <label>Return Distance</label>
                      <p class="text-light">15-kms</p>
                    </div>
                    <div class="col-md-3">
                      <label>Travel Days</label>
                      <p class="text-light">10</p>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-12">
                      <h6 class="text-primary">Transport Details</h6>
                      <div class="table-responsive">
                        <table id="transport" class="table table-flush-spacing border table-bordered">
                          <thead class="table-head">
                            <tr>
                              <th>S.No</th>
                              <th>TYPE</th>
                              <th>TOTAL DRIVEN DISTANCE(kms)</th>
                              <th>PER DAY RENTAL(₹)</th>
                              <th>RENTAL FOR 10 DAYS(₹)</th>
                              <th>PER KM RENTAL(₹)</th>
                              <th>MAX ALLOWED KMS(PER DAY)</th>
                              <th>MAX ALLOWED KMS(JOURNEY)-10DAYS</th>
                              <th>EXTRA KMS(JOURNEY)</th>
                              <th>CHARGE FOR EXTRA(KMS)</th>
                              <th>PERMIT CHARGE(₹)</th>
                              <th>TOTAL CHARGE(₹)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td>Innova</td>
                              <td>773</td>
                              <td>4750.00</td>
                              <td>10*4750.00=47500</td>
                              <td>16.00</td>
                              <td>250</td>
                              <td>2500</td>
                              <td>0</td>
                              <td>16.00*0=0</td>
                              <td>0</td>
                              <td>47500.00</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
                  <div class="row">
                    <h6 class="text-primary">
                      Total amount chargeable for transport :<span class="text-dark ms-2">₹47500</span>
                    </h6>
                    <dl class="row mb-0">
                      <dt class="col-6 fw-normal text-heading">
                        Grand total of this plan:
                      </dt>
                      <dd class="col-6 text-end text-light">
                        ₹47500.00
                      </dd>

                      <dt class="col-sm-6 fw-normal">
                        Selling cost to agent(RAMKUMAR C[selling% =
                        10.00]):
                      </dt>
                      <dd class="col-6 text-end text-light">
                        ₹52487.5
                      </dd>

                      <dt class="col-6 fw-normal text-heading">
                        Profit amount to DVI:
                      </dt>
                      <dd class="col-6 text-end text-light">
                        ₹4987.5
                      </dd>

                      <dt class="col-6 fw-normal text-heading">
                        Profit amount to agent(5.00%):
                      </dt>
                      <dd class="col-6 text-end text-light">₹2375</dd>

                      <dt class="col-6 fw-normal text-heading">
                        This itinerary cost:
                      </dt>
                      <dd class="col-6 text-end text-light">
                        ₹54862.50
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal2 -->
    <div class="modal fade bd-example-modal2-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <b>Detailed Transport Report</b>
            </h5>
            <button type="button" class="fs-2" data-dismiss="modal" aria-label="Close" style="border: none; background-color: transparent">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="d-flex justify-content-between">
                <h5 class="text-primary">
                  <b>Journey info</b>
                  </h6>
                  <!-- <div>
                            <h6 class="text-dark">
                              Overall Package Cost:<span
                                class="text-primary ms-2"
                                >₹54862.5</span
                              >
                            </h6>
                          </div> -->
              </div>
              <div class="col-md-3">
                <label>Cities available with vehicles</label>
                <p class="text-light">2</p>
              </div>
              <div class="col-md-3">
                <label>Types of vehicle taken</label>
                <p class="text-light">Sedan AC (Etios/Dsire/Indigo)</p>
              </div>
              <div class="col-md-3">
                <label>Onward Distance(Kms)</label>
                <p class="text-light">1252</p>
              </div>
              <div class="col-md-3">
                <label>Airport/Railstation pick-up/drop distance (Kms)</label>
                <p class="text-light">80</p>
              </div>
              <div class="col-md-3">
                <label>Total Distance (Including pick-up/drop - Kms)</label>
                <p class="text-light">1332</p>
              </div>
              <div class="col-md-3">
                <label>Return Distance(Kms)</label>
                <p class="text-light">0</p>
              </div>
              <div class="col-md-3">
                <label>Travel Days</label>
                <p class="text-light">7</p>
              </div>
              <div class="col-md-3">
                <label>Permit states applicable</label>
                <p class="text-light">Tamilnadu</p>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <h5 class="text-primary"><b>Vehicle rental split-up (City-wise):</b></h5>
                <div class="col-3 text-primary">
                  <h6>Return distance (kms): 0Kms</h6>
                </div>
                <div class="table-responsive">
                  <table id="transport" class="table table-flush-spacing border table-bordered table-hover">
                    <thead class="table-head vehicle-report-table">
                      <tr>
                        <th><b>From City</b></th>
                        <th><b>Type</b></th>
                        <th><b>Total Driven Distance(kms)</b></th>
                        <th><b>Transfer Rental(₹)</b></th>
                        <th><b>Rental for 1 Transfer(₹)</b></th>
                        <th><b>Per Day Rental(₹)</b></th>
                        <th><b>Rental for 6 Days(₹)</b></th>
                        <th><b>Per km Rental</b></th>
                        <th><b>Max Allowed kms (per day)</b></th>
                        <th><b>Max Allowed kms (Journey) - 6 day(s)</b></th>
                        <th><b>Extra kms (journey)</b></th>
                        <th><b>Charge for extra kms(₹)</b></th>
                        <th><b>Permit Charge(₹)</b></th>
                        <th><b>Total Charge(₹)</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><b>Bangalore</b></td>
                        <td>Sedan AC
                          (Etios/Dsire/Indigo)
                        </td>
                        <td>1332</td>
                        <td>3250.00</td>
                        <td>1*3250.00=3250</td>
                        <td>3250.00</td>
                        <td>6*3250.00=19500</td>
                        <td>11.00</td>
                        <td>250</td>
                        <td>1500</td>
                        <td>0</td>
                        <td>11.00*0=0</td>
                        <td>500</td>
                        <td>23250.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="total-amount my-4 py-3 text-center" style="background-color: #E6ECF2;border-radius: 4px;">
              <b>Total amount chargeable for vehicle from Bangalore: <span class="text-primary">₹23250</span></b>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <div class="col-3 text-primary">
                  <h6>Return distance (kms): 348Kms</h6>
                </div>
                <div class="table-responsive">
                  <table id="transport" class="table table-flush-spacing border table-bordered">
                    <thead class="table-head vehicle-report-table">
                      <tr>
                        <th><b>From City</b></th>
                        <th><b>Type</b></th>
                        <th><b>Total Driven Distance(kms)</b></th>
                        <th><b>Transfer Rental(₹)</b></th>
                        <th><b>Rental for 1 Transfer(₹)</b></th>
                        <th><b>Per Day Rental(₹)</b></th>
                        <th><b>Rental for 6 Days(₹)</b></th>
                        <th><b>Per km Rental</b></th>
                        <th><b>Max Allowed kms (per day)</b></th>
                        <th><b>Max Allowed kms (Journey) - 6 day(s)</b></th>
                        <th><b>Extra kms (journey)</b></th>
                        <th><b>Charge for extra kms(₹)</b></th>
                        <th><b>Permit Charge(₹)</b></th>
                        <th><b>Total Charge(₹)</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th><b>mysore</b></th>
                        <td>Sedan AC
                          (Etios/Dsire/Indigo)
                        </td>
                        <td>1680</td>
                        <td>3000.00</td>
                        <td>1*3000.00=3000</td>
                        <td>3000.00</td>
                        <td>6*3000.00=18000</td>
                        <td>12.00</td>
                        <td>250</td>
                        <td>1500</td>
                        <td>180</td>
                        <td>12.00*180=2160</td>
                        <td>500</td>
                        <td>23660.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="total-amount my-4 py-3 text-center" style="background-color: #E6ECF2;border-radius: 4px;">
              <b>Total amount chargeable for vehicle from Mysore: <span class="text-primary">₹23660</span></b>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        $('#itinerary_LIST').DataTable({
          dom: 'Blfrtip',
          "bFilter": true,
          buttons: [{
              extend: 'copy',
              text: window.copyButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Only name, email and role
              }
            },
            {
              extend: 'excel',
              text: window.excelButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Only name, email and role
              }
            },
            {
              extend: 'csv',
              text: window.csvButtonTrans,
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Only name, email and role
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
            "url": "engine/json/__JSONitinerary.php",
            "type": "GET"
          },
          columns: [{
              data: "counter"
            }, //0
            {
              data: "arrival_location"
            }, //1
            {
              data: "departure_location"
            }, //2
            {
              data: "no_of_days_and_nights"
            }, //3
            {
              data: "no_of_routes"
            }, //4
            {
              data: "expecting_budget"
            }, //5
            {
              data: "trip_start_date_and_time"
            }, //6
            {
              data: "trip_end_date_and_time"
            }, //7
            {
              data: "no_of_person"
            }, //8
            {
              data: "modify"
            } //9
          ],
          columnDefs: [{
            "targets": 9,
            "data": "modify",
            "render": function(data, type, full) {
              return '<div class="flex align-items-center list-user-action"><a data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-sm btn-icon flex-end" style="margin-right: 10px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="28" height="28" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g data - name = "13-car"><path d = "M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill = "#a8aaae" opacity = "1" data - original = "#000000" class = ""></path><path d = "M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill = "#a8aaae" opacity = "1" data - original = "#000000" class = ""></path></g></g></svg></span></a><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="newitinerary.php?route=edit&formtype=basic_info&id=' +
                data +
                '" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Preview" href="itinerary_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 26px; height: 26px;color:#888686;" fill="none"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> </span> </a> </div>';
            }
          }],
        });
      });
    </script>
<?php
  endif;
endif;
?>