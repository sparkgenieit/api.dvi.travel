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
	if ($_POST['TYPE'] == 'select_hotel') :
		$hotel_category = $_POST['ID'];
		$hotel_ID = $_POST['hotel_ID'];
		$CITY_ID = $_POST['CITY_ID'];

		if (($hotel_category != 0) && ($hotel_category != '')) :
			$filter_by_category = " AND `hotel_category` = $hotel_category ";
		else :
			$filter_by_category = "";
		endif;

		if (($CITY_ID != 0) && ($CITY_ID != '')) :
			$filter_by_city = " AND `hotel_city` = $CITY_ID ";
		else :
			$filter_by_city = "";
		endif;
?>
		<label class="form-label" for="hotel_name">Choose Hotel</label>
		<select id="hotel_name" name="hotel_name" class="form-control" required>
			<?php // getHOTEL_DETAIL('', $hotel_category, 'select'); 

			$selected_query = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name`,`hotel_city` FROM `dvi_hotel` where `deleted` = '0' AND `status`='1' {$filter_by_category} {$filter_by_city} ") or die("#PARENT-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());

			if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			?>
				<option value="">Choose Hotel</option>
				<?php
				while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
					$hotel_id = $fetch_data['hotel_id'];
					$hotel_name = $fetch_data['hotel_name'];
					$city_id = $fetch_data['hotel_city'];
					$city_name = getCITYLIST('', $city_id, 'city_label');

				?>
					<option value="<?= $hotel_id; ?>" <?php if ($hotel_id == $hotel_ID) : echo "selected";
														endif; ?>>
						<?= $hotel_name . ' , ' . $city_name; ?>
					</option>
				<?php endwhile;
			else : ?>
				<option value="">No Hotels Found</option>
			<?php endif; ?>
		</select>

		<script>
			// JavaScript code for handling interactions
			$(document).ready(function() {
				var hotel_id = $('#hotel_name').val();
				show_room_for_the_hotel('select_room_for_hotel', hotel_id);

				// Listen for the change event on Selectize for hotel name
				$('#hotel_name').selectize()[0].selectize.on('change', function() {
					var hotelFilterValue = $('#hotel_name').val();
					console.log("Selected hotelFilter value: " + hotelFilterValue);
					if (hotelFilterValue !== '' && hotelFilterValue !== '0') {
						show_room_for_the_hotel('select_room_for_hotel', hotelFilterValue);
					}
				});

				function show_room_for_the_hotel(TYPE, ID) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
						data: {
							ID: ID,
							TYPE: TYPE
						},
						success: function(response) {
							$('#roomTypeDiv').html(response);
						}
					});
				}
			});
		</script>
	<?php
	elseif ($_POST['TYPE'] == 'select_room_for_hotel') :

		$hotel_ID = $_POST['ID'];
	?>
		<label class="form-label" for="room_type">Room Type</label>
		<select id="room_type" name="room_type" class="form-control" required>
			<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_ID, '', '', 'select'); ?>
		</select>

		<script>
			$('#room_type').selectize();
		</script>
	<?php
	elseif ($_POST['TYPE'] == 'select_room') :

		$hotel_ID = $_POST['ID'];
	?>
		<label class="form-label" for="roomTypeFilter">Room Type</label>
		<select id="roomTypeFilter" name="roomTypeFilter" class="form-control">
			<option value="null">All</option>
			<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_ID, '', '', 'select'); ?>
		</select>

		<script>
			$('#hotelFilter, #roomTypeFilter').selectize();

			// Initialize Selectize for both dropdowns
			var roomTypeFilterSelectize = $('#roomTypeFilter').selectize()[0].selectize;

			roomTypeFilterSelectize.on('change', function() {
				var hotelFilterValue = hotelFilterSelectize.getValue();
				var roomTypeFilterValue = roomTypeFilterSelectize.getValue();
				console.log("Selected roomTypeFilter value: " + roomTypeFilterValue);
				filter_calendar(hotelFilterValue, roomTypeFilterValue);
			});
		</script>
<?php
	endif;
else :
	echo "Request Ignored";
endif;
?>