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

class messageDisplayClass 

{

	

	private $default_messageposition = 'bottom-right';

	private $default_messagestack = 'false';

	private $default_messagehideAfter = 6000;

	

	public function defaultTEXT($messagecode)

	{

		return 'Excellent, it works !';

	}

	

	public function defaultMESSAGEPROPERTY($messagecode, $variableTYPE)

	{

		switch($messagecode){

			case "0":

				if($variableTYPE == 'heading') return $heading = 'Error';

				if($variableTYPE == 'text') return $text= 'Unable to create Record';

				if($variableTYPE == 'icon') return $icon = 'error';

				break;

			case "1":

				if($variableTYPE == 'heading') return $heading = 'Success'; 

				if($variableTYPE == 'text') return $text= 'Record created Successfully';

				if($variableTYPE == 'icon') return $icon = 'success';

				break;

			case "2":

				if($variableTYPE == 'heading') return $heading = 'Updated';

				if($variableTYPE == 'text') return $text= 'Record updated Successfully';

				if($variableTYPE == 'icon') return $icon = 'success';

				break;

			case "3":

				if($variableTYPE == 'heading') return $heading = 'Deleted';

				if($variableTYPE == 'text') return $text= 'Record deleted Successfully';

				if($variableTYPE == 'icon') return $icon = 'success';

				break;

			case "4":

				if($variableTYPE == 'heading') return $heading = 'Error';

				if($variableTYPE == 'text') return $text= 'Email ID (or) Mobile number already exists';

				if($variableTYPE == 'icon') return $icon = 'error';

				break;

			default:

				if($variableTYPE == 'heading') return $heading = 'Oh, no !';

				if($variableTYPE == 'text') return $text= 'Something is not right.';

				if($variableTYPE == 'icon') return $icon = 'warning';

				break;

		}		

	}

	

	//default message text

	public function displayMSG($messagecode, $messageheading=NULL, $messagetext=NULL, $messageicon=NULL)

	{

		//arrange-data-view-objects :: PDO

		if($messageheading) {

			$heading = $messageheading;

		} else { 

			$heading = $this->defaultMESSAGEPROPERTY($messagecode, 'heading');

		}

		if($messagetext) {

			$text = $messagetext;

		} else { 

			$text = $this->defaultMESSAGEPROPERTY($messagecode, 'text');

		}

		if($messageicon) {

			$icon = $messageicon;

		} else { 

			$icon = $this->defaultMESSAGEPROPERTY($messagecode, 'icon');

		}

		/*
		$.toast({

				heading: '<?php echo "$heading"; ?>',

				text: '<?php echo "$text"; ?>',

				position: '<?php echo $this->default_messageposition; ?>',

				stack: <?php echo $this->default_messagestack; ?>,

				hideAfter: <?php echo $this->default_messagehideAfter; ?>,

				icon: '<?php echo "$icon"; ?>'

        });
		*/

		?>
		toastr.<?php echo "$icon"; ?>('<?php echo "$text"; ?>', '<?php echo "$heading"; ?>', {timeOut: <?php echo $this->default_messagehideAfter; ?>})
        
		<?php

	}	
	
} //End of class


$displayMSG_globalclass = new messageDisplayClass();

//echo $displayMSG_globalclass->displayMSG('1');