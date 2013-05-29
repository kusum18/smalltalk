<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Answers extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
 
	 
	}
	
	function getanswers_get($questionid)
	{
						
		//creating the object of table post
		$objpost= new Post();
		$objquestion= new Post();
		
		//fetching the answer whose id is $questionid
		$objpost->where('question_answer_id',$questionid)->get();
		
		//fetch the question text
		$objquestion->where('id',$questionid)->get();
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		$userobj = new User();
		$info_question = array();
		$info_question['question_text']=$objquestion->post_text;
		
		$info_question['accepted_answer']=$objquestion->question_answer_id;
		//question text
		$finalifno['question'][]=$info_question;
		
		//loop to store data into array
		foreach ($objpost->all as $obj)
		{
			$info['id']=$obj->id;
			$info['post_text']=$obj->post_text;
			$info['count']=$obj->count;
			$info['user_info']=$userobj->where('id',$obj->user_id)->get()->username ;
			
			$finalifno['answers'][]=$info;
			
		}
		
		//print the results
		$this->response($finalifno);
	
	
	}
	
	
	function index()
	{
	
	}
 
	}