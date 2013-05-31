<?php
require(APPPATH.'libraries/REST_Controller.php');

class Insertdb extends REST_Controller{
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post');
		$this->load->model('notification');
 	}
	public function insert_post(){
		
		
		$post_obj = new Post();
		//values
		$post_obj->title = $this->post('title');
		$post_obj->post_text = $this->post('detail');
		$post_obj->user_id = $this->post('UID');
		$post_obj->question_answer_id = -1;
		$post_obj->post_type = 1;
		$post_obj->save();
		$pid = $post_obj->id;
		$pieces = $this->post('tagged_ppl');
		$splitpieces = explode(",", $pieces);
		foreach($splitpieces as $value)
		{	
			$notification_obj = new Notification();
			$notification_obj->postID =  $pid;
			$notification_obj->FriendID = $value;
			$notification_obj->save();
		}
		
		
	}
}