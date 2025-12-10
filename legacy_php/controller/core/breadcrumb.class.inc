<?php

class breadcrumbClass 
{
	//public $formID = '#mainform';

	public function get_currentPAGENAME($data) {

		$this->pagename = strbefore($data, '.');

		return $collected_variable;

	}


} 
//End of class

$breadcrumb_globalclass = new breadcrumbClass();

//echo $breadcrumb_globalclass->get_currentPAGENAME();
?>