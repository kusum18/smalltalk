<?php

class Student extends DataMapper {
	
	public $has_many = array('course');
	
	public function __contruct()
	{
		parent::__construct();
	
	}


}
?>