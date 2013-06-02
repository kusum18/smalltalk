<?php
require(APPPATH.'libraries/REST_Controller.php');
class Subscription extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		
		$this->load->model('user_subscription');
		 
	 
	}
	
	function subscribe_post()
	{
		//object declaration for the tables
	
	
		$subObj = new User_subscription();
		
		
		echo $this->post('subscription');
		$subscriptions = explode(",", $this->post('subscription'));
		foreach($subscriptions as $sub)
		{
			echo  $sub;
			if($sub==1)
			{
				echo  $sub;
				$subObj->sports=1;
			}
			if($sub==2)
			{
				$subObj->movies=1;
			}
			if($sub==3)
			{
				$subObj->technology=1;
			}
			if($sub==4)
			{
				$subObj->places=1;
			}
			if($sub==5)
			{
				$subObj->music=1;
			}
		}
		$subObj->user_id = $this->post('userid');
		$subObj->save();
		

	}
	

	}