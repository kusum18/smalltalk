<?php
 require(APPPATH.'libraries/REST_Controller.php');
class AcceptedAnswer extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		 
	 
	}
	
	function accepted_get($question_id, $answer_id)
	{
						
		//creating the object of table post
		$objpost= new Post();
		//$objquestion= new Post();
		
		//fetching the answer whose id is $questionid
		$objpost->where('id',$question_id)->get();
		$objpost->question_answer_id=$answer_id;
		
		$objpost->save();
	/* 	//fetch the question text
		//$objquestion->where('id',$questionid)->get();
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		$userobj = new User();
		
		//question text
		$finalifno['question'][]=$objquestion->post_text;
		
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
	 */
	
	}
	
	
	function index()
	{
	
	}
 
	}