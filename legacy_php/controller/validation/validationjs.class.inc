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


class validationJSClass 
{
	
	public $formID = '#mainform';
	public $buttonID = '#save';

	//////////////////////          REGULAR EXPRESSIONS              ////////////////////////////
	//Alpha
	public function ALPHA(){
		$this->alpha = "/^[a-zA-Z/-\s]*$/";		
		return $this->alpha;
	}
	//Alpha w/t space
	public function ALPHASP(){
		$this->alphas = "/^[a-zA-Z,&()./-\s]*$/";		
		return $this->alphas;
	}
	//Alpha numeric
	public function ALPHANS(){
		$this->alphan = "/^[a-zA-Z0-9./-\s]*$/";		
		return $this->alphan;
	}
	//Numeric
	public function NUMS(){
		$this->num = "/^[0-9./-\s]*$/";		
		return $this->num;
	}
	//Float
	public function FLOATS(){
		$this->numf = "/^[0-9./-\s]*$/";		
		return $this->numf;
	}				
	//Email
	public function EMAIL(){
		$this->email = "/^([\w-\.]+)@((\[[0-9]{2,9}\.[0-9]{2,9}\.[0-9]{2,9}\.)|(([\w-]+\.)+))([a-zA-Z]{2,9}|[0-9]{2,9})(\]?)$/";		
		return $this->email;
	}

	///// Remove Slashes  //////
	
	//Alpha w/t space
	public function ALPHAS(){
		$this->alphas = "/^[a-zA-Z ]*$/";		
		return $this->alphas;
	}
	
	//Alpha numeric
	public function ALPHAN(){
		$this->alphan = "/^[a-zA-Z0-9-]*$/";		
		return $this->alphan;
	}
	//Numeric
	public function NUM(){
		$this->num = "/^[0-9]*$/";		
		return $this->num;
	}
	//Float
	public function FLOAT(){
		$this->numf = "/^[0-9.]*$/";		
		return $this->numf;
	}				
	//////////////////////          BUILD VALIDATION SCRIPT              ////////////////////////////
	//SCRIPT HEADING
	public function scriptHEADING(){
		$FID = $this->formID;
		$this->scriptFIRST = "
		$('$FID').bootstrapValidator({
			";		
		return $this->scriptFIRST;
	}
	//SCRIPT FOOTER
	public function scriptFOOTER(){
		$this->scriptLAST = "
		});";		
		return $this->scriptLAST;
	}
	//SCRIPT TRIGGER SUBMIT
	public function scriptTRIGGER(){
		
		$FID = $this->formID;
		$BID = $this->buttonID;
		
		$this->scriptTRIGGERID = "
		$('$BID').click(function() { 
			$('$FID').bootstrapValidator();
		 });";		
		return $this->scriptTRIGGERID;
		
	}
	//SCRIPT COMBINATION-I
	public function scriptCOMBINETP(){
		
		$this->scriptCOMBINEDETAIL = $this->scriptHEADING($formID);//open
			$this->scriptCOMBINEDETAIL .= "
			fields: {
			";
		
		echo $this->scriptCOMBINEDETAIL;
	}
	//SCRIPT COMBINATION-II
	public function scriptCOMBINEBTM(){

			$this->scriptCOMBINEDETAIL = "
			}";
		$this->scriptCOMBINEDETAIL .= $this->scriptFOOTER(); //close
		$this->scriptCOMBINEDETAIL .= $this->scriptTRIGGER($buttonID, $formID);  //button props
		
		echo $this->scriptCOMBINEDETAIL;
	}
	
	//generate
	public function genvalidationSTRUCTURE($fieldNAME, $emptyCHECK=NULL, $emptyMESSAGE=NULL, $regCHECK=NULL, $regEXP=NULL, $regMESSAGE=NULL)
	{
		$script = null;
		
			if (!empty($fieldNAME)) {
				
				//#start
				$script .= "
				$fieldNAME: {
					validators: {";
					
					//#checking empty
					if ($emptyCHECK=='Y') {
						$script .= "
						notEmpty: {
							message: '".$emptyMESSAGE."'
						},";
					}
					
					//#checking empty
					if ($regCHECK=='Y') {
						$script .= "
						regexp: {
							regexp: $regEXP, 
							message: '".$regMESSAGE."'
						},";
					}
					
				//#end
				$script .= "  
					}
				},";
				
			}
			
		echo $script;
	}


} //End of class

$validation_globaljsclass = new validationJSClass();