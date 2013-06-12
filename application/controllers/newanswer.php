<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Newanswer extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->model('notification');
		$this->load->helper('push');
 
	 
	}
	
	function answer_post()
	{
		$post_type = 2;
		$count = 0;
						
		//creating the object of table post
		$answer_obj= new Post();
		
		//values
		$answer_obj->post_text = str_replace ("###","\n",$this->post('answer_text'));
		$answer_obj->user_id = $this->post('user_id');
		$answer_obj->post_type = $post_type;
		$answer_obj->question_answer_id = $this->post('question_id');
		$answer_obj->count = $count;
		
		//saving data in the db
		$answer_obj->save();
		
		$questionObj = new Post();
		$questionObj->where('id',$this->post('question_id'))->get();
		
		
		//for push notification
		$user_obj = new User();
		$user_obj->where('id',$questionObj->user_id)->get();
		$pushObj = new Push();
		$msg=$answer_obj->user_id.": Answered a question";
		$pushObj->pushNotification($user_obj->device_id,$msg);
	
	
	}
	

 
	}