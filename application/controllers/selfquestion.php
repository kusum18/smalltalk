<?php
require(APPPATH.'libraries/REST_Controller.php');
class Selfquestion extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		 
	 
	}
	
	function self_get($userid, $start, $totalrecords)
	{
		//object declaration for the tables
	
	
		$postObj = new Post();
		$tempuserObj = new User();
		$postObj->where('user_id', $userid);
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
	
	
	function index()
	{
		
	}
 
	}