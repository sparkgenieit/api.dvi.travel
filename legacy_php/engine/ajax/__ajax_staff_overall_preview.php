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

	if ($_GET['type'] == 'staff_preview') :

		$staff_ID = $_POST['ID'];
		$TYPE = $_POST['TYPE'];
		

		if ($staff_ID != "" && $staff_ID != "0") :
			$select_staff_list_query = sqlQUERY_LABEL("SELECT `staff_name`, `staff_mobile`, `staff_email`, `status` FROM `dvi_staff_details` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_LIST:" . sqlERROR_LABEL());
			while ($fetch_staff_list_data = sqlFETCHARRAY_LABEL($select_staff_list_query)) :
				$staff_name = $fetch_staff_list_data['staff_name'];
				$staff_mobile = $fetch_staff_list_data['staff_mobile'];
				$staff_email = $fetch_staff_list_data['staff_email'];
				$status = $fetch_staff_list_data['status'];
			endwhile;

			$select_staff_credientials = sqlQUERY_LABEL("SELECT `userID`, `staff_id`, `user_profile`, `username`, `password`, `roleID` FROM `dvi_users` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
			while ($fetch_staff_credientials_list_data = sqlFETCHARRAY_LABEL($select_staff_credientials)) :
				$staff_select_role = $fetch_staff_credientials_list_data['roleID'];
				$staff_username = $fetch_staff_credientials_list_data['username'];
				$staff_password = $fetch_staff_credientials_list_data['password'];
			endwhile;

			if ($status == 1) :
				$status = 'Active';
				$status_color = 'text-success';
			else :
				$status = 'In Active';
				$status_color = 'text-danger';
			endif;
		endif;

?>

		<div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
		
		</div>
			<div class="">
			<div class="tab-content p-0" id="pills-tabContent">
				<div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
					<div>
						<h5 class="text-primary my-1">Staff Details</h5>
					</div>
					<div class="row mt-3">
						<div class="col-md-3">
							<label>Staff Name</label>
							<p class="disble-stepper-title"><?= $staff_name; ?></p>
						</div>
						<div class="col-md-3">
							<label>Email ID</label>
							<p class="disble-stepper-title"><?= $staff_email; ?></p>
						</div>
						<div class="col-md-3">
							<label>Mobile Number</label>
							<p class="disble-stepper-title"><?= $staff_mobile; ?></p>
						</div>
						<div class="col-md-3">
							<label>Role</label>
							<p class="disble-stepper-title"><?= getRole($staff_select_role, 'label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>User Name</label>
							<p class="disble-stepper-title"><?= $staff_username ?></p>
						</div>
						<div class="col-md-3">
							<label>Status</label>
							<p class="<?= $status_color; ?> fw-bold"><?= $status ?></p>
						</div>
						
					</div>
					
				</div>


			</div>


			<!-- Galley Modal -->
			<div id="myModal" class="modal room-details-modal">
				<span class="close room-details-close cursor" onclick="closeModal()">&times;</span>
				<a class="prev room-details-prev mx-3" onclick="plusSlides(-1)">&#10094;</a>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (1).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (2).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (3).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (4).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (5).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (1).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (2).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (3).jpg" class="rounded" width="" height="700px">
					</div>
				</div>

				<a class="next room-details-next mx-3" onclick="plusSlides(1)">&#10095;</a>
			</div>
		</div>

	
<?php
	endif;
endif; ?>