<?php

require(APPPATH.'libraries/REST_Controller.php');

class Getfriends extends REST_Controller{
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		
	}
	
	
	public function generateFriendsList_get($UserID,$token){
		$u_obj = new user();
		$uf_obj = new userfriend();
		$new_u_obj = new user();
		$context = stream_context_create(array(
		'http' => array(
		'ignore_errors'=>true,
		'method'=>'GET'
	)
	));
	    $response = json_decode(file_get_contents("https://graph.facebook.com/$UserID/friends?access_token=$token", true, $context));
	
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator($response, RecursiveIteratorIterator::SELF_FIRST));
		$flag=0;
		
		foreach($jsonIterator as $key => $val) {
					echo "$key: => $val\n";
					if($key=='name'){
					$nameHolder = $val;
					}			
					if($key=='id'){			
					
						$u_obj->where('FacebookId',$val);
						$u_obj->get();
						if($u_obj->FacebookId==''){
						
							//if($key=='id'){
								$u_obj->FacebookId=$val;
								
							//}
							//else if($key=='name'){
								$u_obj->username=$nameHolder;
								
							//}
							$u_obj->isRegistered=0;
							$u_obj->save();	
							$u_obj = new user();
						}
					}
		}
				foreach($jsonIterator as $key => $val) {
					
					$new_u_obj->where('FacebookId',$UserID);
					$new_u_obj->get();
					$uf_obj->user_id=$new_u_obj->id;
					if($key=='id'){
						$new_u_obj->where('FacebookId',$val);
						$new_u_obj->get();
						$uf_obj->friend_id=$new_u_obj->id;
						$flag=$flag+1;
					}
					else if($key=='name'){
						$uf_obj->friend_name=$val;
						$flag=$flag+1;
					}
				
					$uf_obj->isfacebook=1;
					if ($flag==2)
					{
						$uf_obj->save();	
						$uf_obj = new userfriend();
						$flag=0;	
					}	
				}
				
				
				
				/*	
	*/
	}
	
}
	
