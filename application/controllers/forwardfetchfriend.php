<?php
require(APPPATH.'libraries/REST_Controller.php');

class Forwardfetchfriends extends REST_Controller {
	function __construct(){
 
		parent::__construct();
		//loding the model
		$this->load->model('userfriend');
		$this->load->model('user');
		$this->load->model('notifications');
	}
	function fwdfriendlist_get($uid, $postid)
	{
	
		$notificationObj = new Notifications();
		$notificationObj->where('postID', $postid)->get();
		$fwdfriends = array();
		foreach($notificationObj-> all as $friend)
		{
			$fwdfriends['frid'][] = $friend->FriendID;
		}
		
		$friendobj = new Userfriend();
		$friendobj->where('user_id',$uid);
		
		$friendobj->where_not_in('user_id',$fwdfriends['frid']);
		$friendobj->get();
		$userobj = new User();

		// //array to store the questions
		$frienddetails = array();
		$totalFriends = array();
		// //loop to store data into array
		
		foreach ($friendobj->all as $obj)
		{	
			$friend_id = $obj->friend_id;
			$userobj = $userobj->where('id', $friend_id)->get();	
			if($userobj->isRegistered==1){

				$frienddetails['fid']=$obj->friend_id;
				$frienddetails['fname']=$obj->friend_name;
			
				if($obj->islinkedin){
					$frienddetails['lstatus']=1;
				}else{
					$frienddetails['lstatus']=0;
				}if($obj->isfacebook){
					$frienddetails['fstatus']=1;
				}else{
					$frienddetails['fstatus']=0; 
				}
				$totalFriends['friends'][] = $frienddetails; 
			}
		}
    $this->response($totalFriends);
	}
}