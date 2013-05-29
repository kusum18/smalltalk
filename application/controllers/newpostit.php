<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Newpostit extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post_it');
		$this->load->model('geolocation');
 
	 
	}
	
	function postit_post()
	{
							
		//creating the object of table post_it
		$postit_obj= new Post_it();
		
		//creating the object of table geolocation
		$geolocation_obj= new Geolocation();
		
		
		//values
		$geolocation_obj->place = $this->post('place');
		$geolocation_obj->lat = $this->post('lat');
		$geolocation_obj->long = $this->post('long');
		
		//saving data in the db
		$geolocation_obj->save();
		
		//$postit_obj->id = $geolocationtype_obj->id;
		$postit_obj->text = $this->post('notes');
		$postit_obj->location_id = $geolocation_obj->id;
		
		//saving data in the db
		$postit_obj->save();
		
	
	}
	

 
	}