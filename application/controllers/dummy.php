<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Dummy extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('user');
 
	 
	}
	
	function register_post()
	{
		
								
		//creating the object of table User
		$user_obj= new User();
		
		//values
		$user_obj->username = "dummy";
		$user_obj->password = "dummy";
		$user_obj->emailid = "dummy@dummy.com";
		$user_obj->fbtoken = $this->post('facebook_token');
		$user_obj->FacebookId = $this->post('facebook_id');
		
		//saving data in the db
		$user_obj->save();
		$this->response($user_obj->id);
	
	
	}
	

 
	}