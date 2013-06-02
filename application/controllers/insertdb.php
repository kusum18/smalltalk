<?php
require(APPPATH.'libraries/REST_Controller.php');

class Insertdb extends REST_Controller{
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->model('notification');
		$this->load->helper('push');
		$this->load->helper('linkedin_post');
		$this->load->helper('facebook_post');
 	}
	function linkedinposts($postid)
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
	
	
	
	public function insert_post(){
		
		$user_obj = new User();
		$post_obj = new Post();
		//values
		$post_obj->title = $this->post('title');
		$post_obj->post_text = $this->post('detail');
		$post_obj->user_id = $this->post('UID');
		$post_obj->question_answer_id = -1;
		$post_obj->post_type = 1;
		
		$user_obj->where('id', $post_obj->user_id)->get();
		$token=$user_obj->fbtoken;
		$obj = new Facebook_post();
		$post_id = $obj->postOnWall($token,$post_obj->post_text);
		$post_id = json_decode($post_id);
		$post_obj->facebook_post_id = $post_id->id;
		$post_obj->save();
		
		
		//to post on to linkedin wall
		$this->linkedinposts($post_obj->id);
		
		$pid = $post_obj->id;
		$pieces = $this->post('tagged_ppl');
		$splitpieces = explode(",", $pieces);
		foreach($splitpieces as $value)
		{	
			$notification_obj = new Notification();
			$notification_obj->postID =  $pid;
			$notification_obj->FriendID = $value;
			$notification_obj->save();
			
			$userObj = new User();
			$userObj->where('id',$value)->get();
			
			//for push notification
			//$pushObj = new Push();
			//$pushObj->pushNotification($userObj->device_id);
		}
		
		
	}
}