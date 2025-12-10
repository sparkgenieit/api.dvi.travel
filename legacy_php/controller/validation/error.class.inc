<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2019 Touchmark Descience Pvt Ltd
*
*/


//$currentpage

class errorDisplayClass 

{

	

	//check empty text

	public function displayerror($data)

	{

		?>
		
		toastr.<?php echo "$icon"; ?>('<?php echo "$data"; ?>', '<?php echo "$heading"; ?>', {timeOut: <?php echo $this->default_messagehideAfter; ?>})

        $.toast({

			heading: 'Please fix these error(s),',

			text: '<?php echo "$data"; ?>',

			position: 'bottom-right',

			stack: false,

			hideAfter: 6000,

			icon: 'error'

        });

        <?php

	}
	

} //End of class

$displayERR_globalclass = new errorDisplayClass();

//echo $displayERR_globalclass->get_currentPAGENAME();

?>