<?php
require(APPPATH.'libraries/REST_Controller.php');
class Commentfetch extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->helper('linkedin_post');
		 
	 
	}	
	function call ($linkedin_post_id,$linkedintoken)
	{
		$obj = new Linkedin_post();
		return $obj->getComments($linkedin_post_id,$linkedintoken);
	
	}
	function fetch_get($userid)
	{
	
		
		//$this->load->helper('linkedin_post');
		
		//object declaration for the tables
		//object declaration for the tables
		$userObj = new User();
		
		$postanswers = new Post();
	
		$postquestions = new Post();
	
		$tempObj = new User();
		$newans = new Post();
		
		$postquestions->where('post_type',1)->get();
		echo "count:".$postquestions->count();
		//$postquestions->get();
		echo "e ";
		foreach($postquestions->all as $question)
		{
			
			if ($question->linkedin_post_id !=-1)
			{
			// $question->linkedin_post_id
				$userObj->where('id',$question->user_id)->get();
				//$obj = new Linkedin_post();
				$comments=$this->call($question->linkedin_post_id,$userObj->linkedintoken);
				if($comments!=null)
				{
					foreach($comments->values as $comment)
					{
						$postanswers->where('post_type',2);
						$postanswers->where('question_answer_id',$question->id);
						$postanswers->where('linkedin_post_id',$comment->id);
						$postanswers->get();
						
						$flag =0;
						foreach($postanswers->all as $ans)
						{	$flag = 1;						
						}
						if($flag!=1)
						{
							$newans = new Post();
							$newans->post_text=$comment->comment;
							//need to check if user is present in the db
							
							$tempObj->where('linkedInID',$comment->person->id);
							$tempObj->get();
							
							if($tempObj->id==null)
							{
								$newans->user_id=-1;
								$newans->other_username=$comment->person->firstName;
							}
							else
							{
								$newans->user_id=$tempObj->id;
							}
							$newans->post_type=2;
							$newans->question_answer_id=$question->id;
							$newans->linkedin_post_id=$comment->id;
							
							$newans->save();
							
						}
						
					}	
				}
			
			}
			
		
		}

	}
	
	
	function index()
	{
		
	}
 
	}