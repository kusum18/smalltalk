<?php
require(APPPATH.'libraries/REST_Controller.php');
class Linkedinpost extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->helpers('linkedin_post');
		 
	 
	}
	function commentfetch_get()
	{
		echo "in method";
		//object declaration for the tables
		$userObj = new User();
		$helperobj = new Linkedin_post();
		$postquestions = new Post();
		$postanswers = new Post();
		$postquestions->where('post_type',1)->get();
		//$postquestions->get();
		foreach($postquestions->all as $question)
		{
			$postanswers->where('question_answer_id',$question->id);
			$postanswers->where('post_type',2);
			$postanswers->get();
			
			foreach($postanswers->all as $answer)
			{
				if ($answer->linkedin_post_id != -1)
				{
					echo "in this";
					print_r($helperobj->getComments($answer->linkedin_post_id));
				}
				
			}
		
		}
		/* $linkedin_post_id=$postObj->linkedin_post_id;
		$post_text=$postObj->post_text;
		$title=$postObj->title;
		$userObj->where('id', $postObj->user_id)->get();
		
		
		$linkedintoken=$userObj->linkedintoken;
		$obj = new Linkedin_post();
		$output =$obj->postOnLinkedin($postid, $linkedin_post_id, $post_text, $title, $linkedintoken);
		
		$postObj->linkedin_post_id = $output->updateKey;
		
		$postObj->save(); */

	}
	function linkedinposts_get($postid)
	{
		//object declaration for the tables
		$userObj = new User();
		$postObj = new Post();
		$postObj->where('id',$postid)->get();
		
		$linkedin_post_id=$postObj->linkedin_post_id;
		$post_text=$postObj->post_text;
		$title=$postObj->title;
		$userObj->where('id', $postObj->user_id)->get();
		
		
		$linkedintoken=$userObj->linkedintoken;
		$obj = new Linkedin_post();
		$output =$obj->postOnLinkedin($postid, $linkedin_post_id, $post_text, $title, $linkedintoken);
		
		$postObj->linkedin_post_id = $output->updateKey;
		
		$postObj->save();

	}
	
	
	 
	}