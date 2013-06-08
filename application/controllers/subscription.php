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
		echo similar_text("123", "41");
		
		
		$objPQ = new SplPriorityQueue (); 

		$objPQ->insert('A',3); 
		$objPQ->insert('B',6); 
		$objPQ->insert('C',1); 
		$objPQ->insert('D',2);

		$objPQ->setExtractFlags(SplPriorityQueue::EXTR_BOTH); 

		//Go to TOP 
		$objPQ->top(); 

		while($objPQ->valid()){ 
			print_r($objPQ->current()); 
			echo "<BR>"; 
			$objPQ->next(); 
		} 		
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