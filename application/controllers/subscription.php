<?php
require(APPPATH.'libraries/REST_Controller.php');
class Subscription extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		
		$this->load->model('user_subscription');
		 
	 
	}
	function test_get()
	{
		echo similar_text("123", "45");
	}
	function subscribe_post()
	{
		//object declaration for the tables
	
	
		$subObj = new User_subscription();
		
		
		//echo $this->post('subscription');
		$subscriptions = explode(",", $this->post('subscription'));
		//echo " ";
		
		$subObj->where('user_id',$this->post('userid'))->get();
		$subObj->sports = $subscriptions[0];
		$subObj->movies = $subscriptions[1];
		$subObj->technology = $subscriptions[2];
		$subObj->places = $subscriptions[3];
		$subObj->music = $subscriptions[4];
		
		$subObj->user_id = $this->post('userid');
		$subObj->save();
		

	}
	

	}