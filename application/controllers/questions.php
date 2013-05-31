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
		$flag =0;
		foreach($friedndObj->all as $o)
		{
			$flag = 1;
			$friend['friend_id'][]=$o->friend_id;	
		}
		
		if ($flag ==0)
		{
			$friend['status_code'] = 400;
			$this->response($friend);
		
		}
		
		else
		
		{
		
			//fetch data from User table only the users which are registed in the poocho app
			$userObj->select('id');
			$userObj->where('isRegistered',1);
			$userObj->where_in('id',$friend['friend_id']);
			$userObj->get(); 
			
			//temp storage array
			$userReg =array();
			$flag  =0;
			foreach($userObj->all as $o)
			{
				$flag =1;
				$userReg['users'][]=$o->id;
				
			}	
			if($flag==0)
			{
				$friend['status_code'] = 400;
				$this->response($friend);
			
			}
			else
			{
			
				//fetching the questions whose user id is $userid
				$postObj->where_in('user_id',$userReg['users']);
				$postObj->where('post_type',1);
				$postObj->order_by('id','desc');
				$postObj->get($totalrecords, $start);
				
				//array to store the questions
				$info = array();
				$finalifno= array();
				
				$flag = 0;
				//loop to store data into array
				foreach ($postObj->all as $obj)
				{
					$flag=1;
					$info['post_id']=$obj->id;
					$info['post_text']=$obj->post_text;
					$info['post_title']=$obj->title;
					$info['accepted_answer']=$obj->question_answer_id;
					$tempuserObj->where('id',$obj->user_id)->get();
					$info['owner_name']=$tempuserObj->username;
					$info['owner_id']=$tempuserObj->id;
					$finalifno['questions'][]=$info;
					
				}
				if ($flag==1)
				{
					$finalifno['status_code']=200;
					//printing the result
					$this->response($finalifno); 
				}
				
				else
				{
					$finalifno['status_code']=400;
					//printing the result
					$this->response($finalifno);
				}
			}
		}

	
	
	}
	
	
	function index()
	{
		
	}
 
	}