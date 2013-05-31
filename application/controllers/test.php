<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Test extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('userfriend');
		$this->load->model('user');
		$this->load->model('post_it');
 
	 
	}
	
	function testing_get($user_id)
	{
		echo "out ";
		$obj = new Userfriend();
		$userObj = new User();
		$obj->where('user_id',$user_id)->get();
		
		$friend = array();
		$finalfri =array();
		//echo $obj->result_count();
		foreach($obj->all as $o)
		{
			echo " in";
			$friend['freindid'][]=$o->friend_id;
			
		}
		
		print_r($friend);
		$userObj->select('id');
		$userObj->where('isRegistered',1);
		$userObj->where_in('id',$friend['freindid']);
		$userObj->get(); 
		
		foreach($userObj->all as $o)
		{
			echo " in";
			echo $o->id;
			
		}		
		
	
	
	}
	
	
	function index()
	{
	
	}
 
	}