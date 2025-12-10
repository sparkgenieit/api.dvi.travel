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


class validationGENJSEXPClass 

{



	//Alpha

	public function ALPHA(){

		$this->alpha = "/^[a-zA-Z/-\s]*$/";		

		return $this->alpha;

	}

	//Alpha w/t space

	public function ALPHAS(){

		$this->alphas = "/^[a-zA-Z,&()./-\s]*$/";		

		return $this->alphas;

	}

	//Alpha numeric

	public function ALPHAN(){

		$this->alphan = "/^[a-zA-Z0-9/-\s]*$/";		

		return $this->alphan;

	}

	//Numeric

	public function NUM(){

		$this->num = "/^[0-9/-\s]*$/";		

		return $this->num;

	}

	//Float

	public function FLOAT(){

		$this->numf = "/^[0-9./-\s]*$/";		

		return $this->numf;

	}				

	//Email

	public function EMAIL(){

		$this->email = "/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";		

		return $this->email;

	}



} //End of class



?>