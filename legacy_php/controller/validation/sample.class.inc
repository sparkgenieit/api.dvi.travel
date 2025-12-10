<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark Descience Pvt Ltd
*
*/
include_once('validationjs.class.inc');

//$currentpage
class brvalidationClass extends validationJSClass
{
	
	//check empty text
	public function BRVALID()
	{
		//extended funtions
	}
	
	public function buildVALIDATION()
	{
		$this->scriptCOMBINETP();
		$this->genvalidationSTRUCTURE('customerfirstname', 'Y', 'Field cannot be empty', 'Y', $this->ALPHAS(), 'Alphabets only allowed here. ');
		$this->genvalidationSTRUCTURE('customermobile', 'Y', 'Field cannot be empty', 'Y', $this->NUM(), 'Only numbers allowed. ');
		$this->genvalidationSTRUCTURE('customeremail', 'Y', 'Field cannot be empty', 'Y', $this->EMAIL(), 'Invalid email address. ');
		$this->genvalidationSTRUCTURE('customergender', 'Y', 'Select any one option');
		$this->genvalidationSTRUCTURE('customerloyaltypoints', 'N', '', 'Y', $this->NUM(), 'Only numbers allowed. ');
		$this->scriptCOMBINEBTM(); //'#save', '#mainform'
	}
	
	
} //End of class

$br_VALIDATIONCLASS = new brvalidationClass();
//echo $displayERR_globalclass->get_currentPAGENAME();

?>