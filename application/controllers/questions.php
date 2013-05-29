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
	
		//creating the objct of table post
		$su= new Post();
		
		//fetching the questions whose user id is $userid
		$su->where('user_id',$userid);
		$su->where('post_type',1);
		$su->get($totalrecords,$start);
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		
		//loop to store data into array
		foreach ($su->all as $obj)
		{
			$info['id']=$obj->id;
			$info['post_text']=$obj->post_text;
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