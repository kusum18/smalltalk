<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Postretrieval extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post_it');
		$this->load->model('geolocation');
 
	 
	}
	
	function retrieval_get($lat, $long)
	{
							
		//creating the object of table post_it
		$postit_obj= new Post_it();
		
		//creating the object of table geolocation
		$geolocation_obj= new Geolocation();
		
		
		//values
		$postit_obj->get();
		$info = array();
		$finalifno= array();
		foreach ($postit_obj->all as $obj)
		{
			$geolocation_obj->where('id',$obj->location_id)->get();
			$info['place']=$geolocation_obj->place;
			$info['distance']=0;
			$info['notes_id']=$obj->id;
			$info['notes_like']=$obj->likes;
			$info['notes_text']=$obj->text;
			
			$finalifno['notes'][]=$info;
			
		}
		
		$this->response($finalifno);
	
	}
	

 
	}