<?php
require(APPPATH.'libraries/REST_Controller.php');
class Directedquestions extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->model('notification');
		$this->load->model('userfriend');
 
	 
	}
	
	function directed_get($userid, $start, $totalrecords)
	{
		
		//object declaration for the tables
		$noteObj = new Notification();
		$userObj = new User();
		$postObj = new Post();
		$tempuserObj = new User();
		
		//fetching data from userfriend table with user id as given in the get method $userid
		$noteObj->where('FriendID',$userid)->get();
		
		//temp storage array
		$posts = array();
		$flag =0;
		foreach($noteObj->all as $o)
		{
			$flag=1;
			$posts['post_id'][]=$o->postID;	
		}
		
		
		if ($flag==0)
		{
			$posts['status_code'] = 400;
			$this->response($posts);
		}
		else
		{
			//fetching the questions whose user id is $userid
			$postObj->where_in('user_id',$posts['post_id']);
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
				$tempuserObj->where('id',$obj->user_id)->get();
				$info['owner_name']=$tempuserObj->username;
				$info['owner_id']=$tempuserObj->id;
				$finalifno['questions'][]=$info;
				
			}
			
			$finalifno['status_code'] = 200;
			//printing the result
			$this->response($finalifno);  
		}

	
	
	}
	
	
	function index()
	{
		
	}
 
	}