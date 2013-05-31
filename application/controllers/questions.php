<?php
require(APPPATH.'libraries/REST_Controller.php');
class Questions extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->model('userfriend');
 
	 
	}
	
	function getquestions_get($userid, $start, $totalrecords)
	{
		//object declaration for the tables
		$friedndObj = new Userfriend();
		$userObj = new User();
		$postObj = new Post();
		$tempuserObj = new User();
		
		//fetching data from userfriend table with user id as given in the get method $userid
		$friedndObj->where('user_id',$userid)->get();
		
		//temp storage array
		$friend = array();

		foreach($friedndObj->all as $o)
		{
			$friend['friend_id'][]=$o->friend_id;	
		}
		
		//fetch data from User table only the users which are registed in the poocho app
		$userObj->select('id');
		$userObj->where('isRegistered',1);
		$userObj->where_in('id',$friend['friend_id']);
		$userObj->get(); 
		
		//temp storage array
		$userReg =array();
		
		foreach($userObj->all as $o)
		{
			$userReg['users'][]=$o->id;
			
		}	
		
		//fetching the questions whose user id is $userid
		$postObj->where_in('user_id',$userReg['users']);
		$postObj->where('post_type',1);
		$postObj->order_by('id','desc');
		$postObj->get($totalrecords, $start);
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		
		//loop to store data into array
		foreach ($postObj->all as $obj)
		{
			$info['post_id']=$obj->id;
			$info['post_text']=$obj->post_text;
			$info['post_title']=$obj->title;
			$info['accepted_answer']=$obj->question_answer_id;
			$info['user_name']=$userObj->where('id',$obj->user_id)->get()->username;
			$finalifno['questions'][]=$info;
			
		}
		
		//printing the result
		$this->response($finalifno); 

	
	
	}
	
	
	function index()
	{
		echo "test";
	}
 
	}