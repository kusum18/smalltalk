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
	//creating the object of table post
		$objpost= new Post();
		echo "new";
		echo "new";
		$objquestion= new Post();
		
		//fetching the answer whose id is $questionid
		$objpost->where('user_id',$userid)->get();
		
		//fetch the question text
		//$objquestion->where('id',$questionid)->get();
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		/* $userobj = new User();
		$info_question = array();
		$info_question['question_text']=$objquestion->post_text;
		
		$info_question['accepted_answer']=$objquestion->question_answer_id;
		//question text
		$finalifno['question'][]=$info_question; */
		
		//loop to store data into array
		foreach ($objpost->all as $obj)
		{
			$info['id']=$obj->prim;
			/* $info['post_text']=$obj->post_text;
			$info['count']=$obj->count;
			$info['user_info']=$userobj->where('id',$obj->user_id)->get()->username ; */
			
			$finalifno['answers'][]=$info;
			
		}
		
		//print the results
		$this->response($finalifno);
	

	
		/* $o = new Userfriend();
		$o->get();

		foreach ($o as $obj)
		{
			echo $obj->prim;
		}
	
		echo "tessst";
		//feteching user's friends
		$friendObj = new Userfriend();
		$friendObj->get();
		foreach ($friendObj as $fobj)
		{
			foreach( $fobj->all as $obj)
			{
			echo $obj->user_id;
			echo $obj->prim; 
			}
			//$friendids=$fobj->FriendID;
		} */
	/* 	$userObj = new User();
		$postObj= new Post(); */
		
		//generating all the friends of the given user id
		//$friendObj->select('FriendID');
		//$friendObj->where('user_id',$userid)->get();
		
		//$friendObj->get();
		//S$friendids = array();
		
		/* //print_r( $friendids);
		$userObj->select('id');
		$userObj->where('isRegistered',1);
		$userObj->where_in('id',$friendObj);
		$userObj->get();
		
		
		
		//creating the object of table post
		
		
		//fetching the questions whose user id is $userid
		$postObj->where_in('user_id',$userObj);
		$postObj->where('post_type',1);
		$postObj->get($totalrecords,$start);
		
		//array to store the questions
		$info = array();
		$finalifno= array();
		
		//loop to store data into array
		foreach ($postObj->all as $obj)
		{
			$info['id']=$obj->id;
			$info['post_text']=$obj->post_text;
			$finalifno['questions'][]=$info;
			
		}
		
		//printing the result
		$this->response($finalifno); */

	
	
	}
	
	
	function index()
	{
		echo "test";
	}
 
	}