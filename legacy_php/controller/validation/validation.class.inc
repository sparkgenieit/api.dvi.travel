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

class validationClass 

{

	

	//getting pagename

	public function get_currentPAGENAME($currentpage) {

		

		//apply-only for create-update

		if(strbefore($currentpage,'_') == 'crud') {

			$pagename_after = strafter($currentpage,'_');  //remove-curd

			$pagename_before = strbefore($pagename_after,'.');  //remove-extension

		}

		

		$this->pagename = $pagename_before;

		return $this->pagename;

		

	}

	

	//checks and stops mysql injection

	public function sanitize($data){

		$this->safeDATA = trim(htmlentities(strip_tags($data)));
		$this->safeDATA = sqlREALESCAPESTRING_LABEL($this->safeDATA);		
		return $this->safeDATA;

	}

	

	//check email

	public function is_email_valid($email)

	{

		//if (preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {	

			return true;  

		}

		return false;

	}

		

	//check empty text

	public function check_empty($data, $fields)

	{

		$msg = null;

		

		foreach ($fields as $value) {

			//strip || for validation

			$fieldnames_before = strbefore($value,'||');

			$fieldnames_after = strafter($value,'||');

			

			if (empty($data[$fieldnames_before])) {

				$msg .= "- $fieldnames_after is empty <br />";

			}

		} 

		return $msg;

	}	

	

	

	

} //End of class



$validation_globalclass = new validationClass();

//echo $validation_globalclass->get_currentPAGENAME();



$validationCLASS = $validation_globalclass->get_currentPAGENAME($currentpage);



?>