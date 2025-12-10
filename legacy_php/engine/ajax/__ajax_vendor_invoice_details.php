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
	$ID = $_POST['ID'];
	$TYPE = $_POST['TYPE'];
?>
	<div class="row invoice-preview">
	  <!-- Invoice -->
	  <div class="col-12 mb-md-0 mb-4">
		<div class="card invoice-preview-card">
		  <div class="card-body px-1">
			<div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
			  <div class="mb-xl-0 mb-4">
				<div class="d-flex svg-illustration mb-2 gap-2 align-items-center">
					<h4 class="mb-0" style="font-size: 23px;"><span class="text-danger">DVi</span> Holidays</h4>
				</div>
				<p class="mb-2">No-51, Vijaya Nagar, Dheeran Nagar</p>
				<p class="mb-2">Tiruchirapalli - 620009, Tamilnadu</p>
				<p class="mb-0"><i class="ti ti-phone mb-1 me-2"></i>0431 2403615</p>
			  </div>
			  <div>
				<h4 class="fw-medium mb-1 text-end">INVOICE #86423</h4>
				<div class="mb-2 pt-1 text-end">
				  <span class="fw-medium text-primary">January 25, 2024</span>
				</div>
			  </div>
			</div>
		  </div>
		  
			<div class="divider my-0">
				<div class="divider-text">
					<i class="ti ti-map-2 text-primary ti-sm"></i>
				</div>
			</div>
			
		  <div class="card-body px-1">
			<div class="row p-sm-3 p-0">
			  <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
				<h5 class="text-primary mb-3">Invoice To</h5>
				<p class="mb-1">Thomas shelby</p>
				<p class="mb-1">Shelby Company Limited</p>
				<p class="mb-1">Small Heath, B10 0HF, UK</p>
				<p class="mb-1"><i class="ti ti-phone mb-1 me-2"></i>718-986-6062</p>
				<p class="mb-0"><i class="ti ti-mail me-2"></i>peakyFBlinders@gmail.com</p>
			  </div>
			  <div class="col-xl-6 col-md-12 col-sm-7 col-12">
				<table class="ms-auto">
				  <tbody>
					<tr>
						<td class="pe-2">
							<h5 class="text-primary mb-2">Payment Info</h5>
						</td>
					</tr>
					<tr>
					  <td class="pe-2">Total Due:</td>
					  <td class="fw-medium"><?= $global_currency_format; ?> 12,110.55</td>
					</tr>
					<tr>
					  <td class="pe-2">Bank name:</td>
					  <td>American Bank</td>
					</tr>
					<tr>
					  <td class="pe-2">Country:</td>
					  <td>United States</td>
					</tr>
					<tr>
					  <td class="pe-2">IBAN:</td>
					  <td>ETD95476213874685</td>
					</tr>
					<tr>
					  <td class="pe-2">SWIFT code:</td>
					  <td>BR91905</td>
					</tr>
				  </tbody>
				</table>
			  </div>
			</div>
		  </div>
		  
			<div class="divider my-0">
				<div class="divider-text">
					<i class="ti ti-map-2 text-primary ti-sm"></i>
				</div>
			</div>
		  
			<div class="card-body px-1">
				<div class="row mx-2">
					<div class="col-md-12">
						<div class="row">
							<div class="col">
								<h5 class="text-primary">Itinerary Info</h5>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<label>Arrival</label>
						<p class="text-light">Chennai, Tamil Nadu, India</p>
					</div>
					<div class="col-md-3">
						<label>Destination</label>
						<p class="text-light">Trivandrum</p>
					</div>
					<div class="col-md-3">
						<label>Start Date</label>
						<p class="text-light">10-11-2023 08:00 AM</p>
					</div>
					<div class="col-md-3">
						<label>End Date</label>
						<p class="text-light">22-11-2023 12:00 PM</p>
					</div>
				</div>
				<div class="row mx-2">
					<div class="col-md-3">
						<label>Number of Day and Night</label>
						<p class="text-light">3 Days / 4 Nights</p>
					</div>
					<div class="col-md-3">
						<label>Number of Adult</label>
						<p class="text-light">4</p>
					</div>
					<div class="col-md-3">
						<label>Number of Children</label>
						<p class="text-light">0</p>
					</div>
					<div class="col-md-3">
						<label>Number of Infants</label>
						<p class="text-light">4</p>
					</div>
				</div>
			</div>
			
			<div class="divider my-0">
				<div class="divider-text">
					<i class="ti ti-map-2 text-primary ti-sm"></i>
				</div>
			</div>
			
			<h5 class="text-primary mt-3 mx-3">Transport Details</h5>
			<div class="mb-4">
				<table class="table table-striped text-center">
					<thead class="table-light align-middle">
						<tr>
							<th class="w-px-150">Date</th>
							<th>Location</th>
							<th>Travel Distance (In KM)</th>
							<th>Tourist spot Distance (In KM)</th>
							<th>Total Distance (In KM)</th>
							<th class="w-px-200">Total Time</th>
						</tr>
					</thead>
					<tbody class="table-border-bottom-0">
						<tr>
							<td>Jan 2, 2024</td>
							<td>
								<b>Chennai, Tamil Nadu, India</b> To <b>Chennai, Tamil Nadu, India</b>
								<span class="badge bg-label-info me-1">Via Mahabalipuram, Tamil Nadu, India</span>
							</td>
							<td>0</td>
							<td>30</td>
							<td>30</td>
							<td>1 Hours 30 Mins</td>
						</tr>
						<tr>
							<td>Jan 3, 2024</td>
							<td>
								<b>Chennai, Tamil Nadu, India</b> To <b>Puducherry, India</b>
								<span class="badge bg-label-info me-1">Via Puducherry, India</span>
							</td>
							<td>151</td>
							<td>22</td>
							<td>173</td>
							<td>5 Hours 45 Mins</td>
						</tr>
						<tr>
							<td>Jan 4, 2024</td>
							<td>
								<b>Puducherry, India</b> To <b>Puducherry, India</b>
								<span class="badge bg-label-info me-1">Via Puducherry, India</span>
							</td>
							<td>0</td>
							<td>80</td>
							<td>80</td>
							<td>8 Hours</td>
						</tr>
						<tr>
							<td>Jan 5, 2024</td>
							<td>
								<b>Puducherry, India</b> To <b>Chidambaram, Tamil Nadu, India</b>
							</td>
							<td>63</td>
							<td>15</td>
							<td>78</td>
							<td>7 Hours 30 Mins</td>
						</tr>
					</tbody>
				</table>
			</div>
		  
			<div class="divider my-0">
				<div class="divider-text">
					<i class="ti ti-map-2 text-primary ti-sm"></i>
				</div>
			</div>
		  
			<div class="card-body px-1">
				<div class="row mx-2">
					<div class="col-md-12">
						<div class="row">
							<div class="col">
								<h5 class="text-primary">Vehicle - Travel Summary</h5>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label>Number of Vehicle</label>
						<p class="text-light">2</p>
					</div>
					<div class="col-md-4">
						<label>Vehicle Origin City</label>
						<p class="text-light">Chennai, Tamil Nadu, India</p>
					</div>
					<div class="col-md-4">
						<label>Vehicle Destination City</label>
						<p class="text-light">Chidambaram, Tamil Nadu, India</p>
					</div>
					<div class="col-md-3">
						<label>Travel Distance</label>
						<p class="text-light">361</p>
					</div>
					<div class="col-md-3">
						<label>Pick-up / Drop Distance</label>
						<p class="text-light">60</p>
					</div>
					<div class="col-md-3">
						<label>Total Distance</label>
						<p class="text-light">421</p>
					</div>
					<div class="col-md-3">
						<label>Travel Days</label>
						<p class="text-light">10</p>
					</div>
					<div class="col-md-6">
						<label>Permit States</label>
						<p class="text-light">
							Puducherry, India
							<span class="text-primary fw-bolder"> | </span>
							Chidambaram, Tamil Nadu, India
						</p>
					</div>
				</div>
			</div>
			
			<div class="divider my-0">
				<div class="divider-text">
					<i class="ti ti-map-2 text-primary ti-sm"></i>
				</div>
			</div>
			<h5 class="text-primary mt-3 mx-3">Vehicle Rental Split-up</h5>
		  <div class="border-top">
			<table class="table m-0 text-center">
			  <thead class="align-middle">
				<tr>
				  <th>S.No.</th>
				  <th>Vehicle Details</th>
				  <th>Total Travel Distance (KM)</th>
				  <th>Max Allowed KM For 3 Days</th>
				  <th>Per Day Rental (<?= $global_currency_format; ?>)</th>
				  <th>Per KM Rental (<?= $global_currency_format; ?>)</th>
				  <th>Extra KM Rental (<?= $global_currency_format; ?>)</th>
				  <th>Permit Cost (<?= $global_currency_format; ?>)</th>
				  <th>Total Cost (<?= $global_currency_format; ?>)</th>
				  <th>Total Cost For 3 Days (<?= $global_currency_format; ?>)</th>
				</tr>
			  </thead>
			  <tbody class="align-top">
				<tr>
					<td>1</td>
					<td>
						<p class="mb-0"><span class="text-primary">Sedan AC</span> <span class="text-secondary">(Petrol)</span></p>
						<p class="mb-0"><b>Mudaliarpet</b></p>
					</td>
					<td><span class="text-success">421</span></td>
					<td>450<br/><small>(Per Day 150 KM)</small></td>
					<td><span class="fw-bold">1,900</span></td>
					<td><span class="fw-bold">20</span></td>
					<td>0<br/><small>(Per KM - <?= $global_currency_format; ?>8)</small></td>
					<td><span class="fw-bold">500</span></td>
					<td><span class="text-info fw-bold">2,420</span></td>
					<td><span class="text-primary fw-bold fs-5">7,260</span></td>
				</tr>
				<tr>
					<td>1</td>
					<td>
						<p class="mb-0"><span class="text-primary">Indigo AC</span> <span class="text-secondary">(Petrol)</span></p>
						<p class="mb-0"><b>Lawspet</b></p>
					</td>
					<td><span class="text-danger">421</span></td>
					<td>371<br/><small>(Per Day 150 KM)</small></td>
					<td><span class="fw-bold">1,900</span></td>
					<td><span class="fw-bold">20</span></td>
					<td><span class="fw-bold">400</span><br/><small>(Per KM - <?= $global_currency_format; ?>8)</small></td>
					<td><span class="fw-bold">500</span></td>
					<td><span class="text-info fw-bold">2,820</span></td>
					<td><span class="text-primary fw-bold fs-5">8,460</span></td>
				</tr>
				<tr>
				  <td colspan="4" class="align-top px-4 py-4 text-start">
					<h5 class="text-primary mb-1">Notes</h5>
					<span id="notes_text">It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
					  projects. Thank You!</span>
					<textarea class="form-control" id="notes_textarea" rows="3"></textarea>
				  </td>
				  <td colspan="6">
					<div class="d-flex justify-content-end align-items-center my-3 mb-2 py-1 px-0">
					  <div class="order-calculations">
						<div class="d-flex justify-content-between align-items-center mb-2">
						  <span class="w-px-100 text-heading text-end">Subtotal</span>
						  <h6 class="mb-0 ms-4"><?= $global_currency_format; ?> 15,720</h6>
						</div>
						<div class="d-flex justify-content-between align-items-center mb-2">
						  <span class="w-px-100 text-heading text-end">Discount</span>
						  <h6 class="mb-0 ms-4">
							<?= $global_currency_format; ?> 
							<span id="discount_text">0</span>
							<span id="discount_input"><input id="smallInput" class="form-control form-control-sm w-px-75 d-inline ms-1" type="text" placeholder="Discount"></span>
						  </h6>
						</div>
						<div class="d-flex justify-content-between align-items-center mb-2">
						  <span class="w-px-100 text-heading text-end">Tax</span>
						  <h6 class="mb-0 ms-4"><?= $global_currency_format; ?> 50</h6>
						</div>
						<div class="d-flex justify-content-between align-items-center">
						  <h6 class="w-px-100 mb-0 text-end text-primary fs-5">Total</h6>
						  <h6 class="mb-0 ms-4 text-primary fs-5"><?= $global_currency_format; ?> 15,770</h6>
						</div>
					  </div>
					</div>
				  </td>
				</tr>
			  </tbody>
			</table>
		  </div>

		  <div class="card-body">
			<div id="preview_btn">
				<div class="row">
				  <div class="col-12 text-center">
					<button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
					  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-send ti-xs me-2"></i>Send Invoice</span>
					</button>
					<button class="btn btn-label-linkedin">
					  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-download ti-xs me-2"></i>Download</span>
					</button>
					<button class="btn btn-secondary" onclick="show_vendor_invoice_details('edit')">
					  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-edit ti-xs me-2"></i>Edit Invoice</span>
					</button>
				  </div>
				</div>
			</div>
			 
			<div id="edit_btn"> 
				<div class="row">
				  <div class="col-12 text-center d-flex justify-content-between">
					<a href="vendor_invoice.php" class="btn btn-secondary">
					  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-close ti-xs me-2"></i>Cancel</span>
					</a>
					<a href="vendor_invoice.php" class="btn btn-primary">
					  <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-check ti-xs me-2"></i>Submit</span>
					</a>
				  </div>				  
				</div>
			</div>
			
		  </div>
		</div>
	  </div>
	  <!-- /Invoice -->
	</div>
	

    <script>
		$(document).ready(function() {
			<?php if($TYPE == 'edit' && $ID != ''): ?>
				show_vendor_invoice_details('edit');
			<?php else: ?>
				show_vendor_invoice_details('preview');
			<?php endif; ?>
		});
	  
		function show_vendor_invoice_details(type) {
			if(type == 'edit'){
				$('#notes_text').addClass('d-none');
				$('#notes_textarea').removeClass('d-none');
				
				$('#discount_text').addClass('d-none');
				$('#discount_input').removeClass('d-none');
				
				$('#preview_btn').addClass('d-none');
				$('#edit_btn').removeClass('d-none');
			} else if(type == 'preview') {
				$('#notes_text').removeClass('d-none');
				$('#notes_textarea').addClass('d-none');
				
				$('#discount_text').removeClass('d-none');
				$('#discount_input').addClass('d-none');
				
				$('#preview_btn').removeClass('d-none');
				$('#edit_btn').addClass('d-none');
			}
		}
    </script>
<?php
  endif;
endif;
?>