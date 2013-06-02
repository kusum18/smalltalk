<?php
require(APPPATH.'libraries/REST_Controller.php');
class UserReg extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		
		$this->load->model('user');
		$this->load->model('userfriend');
		 
	 
	}
	
	function register_get($user_id,$username,$type,$id,$token, $devicetoken)
	{
		//object declaration for the tables
		$userObj = new User();
		if ($user_id==-1)
		{
			if ($type=="fb")
			{
				$userObj->where('FacebookId',$id)->get();
				if ($userObj->id == null)
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->fbtoken = $token;
					$userObj->FacebookId = $id;

				}
				else
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->fbtoken = $token;
					$userObj->FacebookId = $id;
					
				}
			}
			else
			{
				$userObj->where('linkedInID',$id)->get();
				if ($userObj->id == null)
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->linkedintoken = $token;
					$userObj->linkedInID = $id;

				}
				else
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->linkedintoken = $token;
					$userObj->linkedInID = $id;
					
				}
			}
			
			
		
		}
		else
		{
			$userObj->where('id',$user_id)->get();
			
			if ($type=="fb")
			{
				$userObj->username = $username;
				$userObj->device_id = $devicetoken;
				$userObj->fbtoken = $token;
				$userObj->FacebookId = $id;

			}
			else
			{
				$userObj->username = $username;
				$userObj->device_id = $devicetoken;
				$userObj->linkedintoken = $token;
				$userObj->linkedInID = $id;
					
			}

				
		}
		$userObj->isRegistered = 1;
		$userObj->save();
		echo $userObj->id;
	}
	
	
	function index()
	{
		
	}
 
	}