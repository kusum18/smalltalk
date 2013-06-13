<?php
require(APPPATH.'libraries/REST_Controller.php');

class Insertdb extends REST_Controller{
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->model('notification');
		$this->load->model('user_subscription');
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
	
	function friendlist($qsubscription,$userid,$friendlist)
	{

		$fetchcount = 15 - count($friendlist);
		if ($fetchcount<=0)
		{	
			return $friendlist;
		}
		
		$quesub="";
		
		/* $i=1;
		foreach ($qsubscriptions as $sub)
		{
			if ($sub ==1)
			{
				$quesub=$quesub.$i;
			}
			$i=$i+1;
		} */
		
		$userfriedn = new Userfriends();
		$userfriedn->where('user_id', $userid)->get();
		
		$friend = array();
		$flag =0;
		foreach($userfriedn->all as $o)
		{
			$flag = 1;
			$friend['friend_id'][]=$o->friend_id;	
		}
		
		if ($flag ==0)
		{
			//error checking if user has no friends
		
		}
		
		else
		
		{	$quesub="";
			$qsubscriptions = explode(",", $qsubscription);
			foreach ($qsubscriptions as $sub)
			{
				$quesub=$quesub.$sub;
			} 
			//$quesub = 
			$usersub = array();
			//echo $usersub;
			$userSubObj = new User_subscription(); 
			//fetch frends from friedns table for below statement
			$userSubObj->where_in('user_id',$friend['friend_id'])->get();
			$objPQ = new SplPriorityQueue (); 
			$friPQ = new SplPriorityQueue ();
			foreach($userSubObj->all as $user)
			{
				$usersub=$user->sports.$user->movies.$user->technology.$user->places.$user->music;
				/* if ($user->sports==1)
				{$usersub=$usersub."1";}
				if ($user->movies==1)
				{$usersub=$usersub."2";}
				if ($user->technology==1)
				{$usersub=$usersub."3";}
				if ($user->places==1)
				{$usersub=$usersub."4";}
				if ($user->music==1)
				{$usersub=$usersub."5";} */
				//$similar = similar_text($quesub, $usersub);
				$similar = $usersub * $quesub;
				if ($similar!=0)
				{
					$objPQ->insert($user->user_id,$similar);	
				}
				else
				{
					$friPQ->insert($user->user_id,$user->no_of_friends);
				}
			}
			$flag=0;
			if ($objPQ->count()< $fetchcount)
			{
				$flag = 1;
			}
			//echo "COUNT->".$objPQ->count()."<BR>"; 
			$objPQ->setExtractFlags(SplPriorityQueue::EXTR_BOTH); 
			
			//Go to TOP 
			$objPQ->top(); 
			$count=1;
			while($objPQ->valid()){ 
				
				$friendlist=$friendlist."'".$objPQ->current()); 
				//echo "<BR>"; 
				$objPQ->next(); 
				$count= $count+1;
				if ($count >$fetchcount)
				{	
					break;
				}
			}
			if($flag==1)
			{
				$fetchcount=$fetchcount- $objPQ->count();
				$friPQ->top(); 
				$count=1;
				while($friPQ->valid()){ 
					
					$friendlist=$friendlist."'".$friPQ->current()); 
					//echo "<BR>"; 
					$friPQ->next(); 
					$count= $count+1;
					if ($count >$fetchcount)
					{	
						break;
					}
				}
				
			}
			
			
		}
		return $friendlist;
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
		
		try{
			$post_obj->save();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		try{
			$user_obj->where('id', $post_obj->user_id)->get();
			$token=$user_obj->fbtoken;
			$obj = new Facebook_post();
			$post_id = $obj->postOnWall($token,$post_obj->post_text);
			$post_id = json_decode($post_id);
			$post_obj->facebook_post_id = $post_id->id;
			$post_obj->save();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
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
			$pushObj = new Push();
			$msg=$user_obj->username.": Asked a question";
			$pushObj->pushNotification($userObj->device_id,$msg);
		}
	}
}