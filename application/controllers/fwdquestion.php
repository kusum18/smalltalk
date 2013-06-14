<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Fwdquestion extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('userfriend');
		$this->load->model('user');
		$this->load->model('notification');
		
		$this->load->helper('push');
	
	 
	}
	
	function forward_post()
	{
		$pid = $this->post('post_id');
		$user_id = $this->post('user_id');
		
		$user_Obj = new User();
		$user_Obj->where('id',$user_id)->get();
		$pieces = $this->post('tagged_ppl');
		$splitpieces = explode(",", $pieces);
		
		
		foreach($splitpieces as $value)
		{	
			$notification_obj = new Notification();
			$notification_obj->postID =  $pid;
			$notification_obj->FriendID = $value;
			$notification_obj->save();
			
			$userObj = new User();
			$userObj->where('id',$value)->get();
			
			//for push notification
			$pushObj = new Push();
			$msg=$user_obj->username.": Forwarded a question";
			$pushObj->pushNotification($userObj->device_id,$msg);
		}
	
	
	}
	
	
	function index()
	{
	
	}
 
	}