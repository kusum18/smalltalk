<?php 

class facebookfriend extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper('Facebook');
		try{
			//$this->load->model('facebookfriends');	
			$this->load->model('userfriend');
			$this->load->model('user');
		}
		catch(Exception $e){
		}
	}
	public function generateFriendsList(){
		$u_obj = new user();
		$uf_obj = new userfriend();
		$new_u_obj = new user();
		$context = stream_context_create(array(
		'http' => array(
		'ignore_errors'=>true,
		'method'=>'GET'
	)
	));
	    $response = json_decode(file_get_contents("https://graph.facebook.com/709631419/friends?access_token=CAACEdEose0cBANC7IU0UBSdY7Ifawtm9tr7QQKHhlzh4q1PBVrUqiZCv7Ejv3uPfnKX4aeIhbf3eZAZAdmV2ttZCGUQIyFAAGDOsN7EzfpJXIgpl8ePoPLyY4ZAYBhUSW4LEpcFPWakpZBTiTsiiXY35038HmDjMCVlA1UZCi0hVgZDZD", true, $context));
	
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator($response, RecursiveIteratorIterator::SELF_FIRST));
		$UserID = 500815576;
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
	public function postOnWall(){
		$msg='{Spam post of the day - 1}';
		$token='CAACEdEose0cBAMZBsCqNwI5QWKloynJmel8iaXpFhxksf7l54SOB2rRkYvYiiHd7Ay9IrRrCj5JZCjoWJyJXBeZCZAWq0MOIrs64LaD9sS0ZCeG7DvZC3MKeUGKpdeZADZAlP5GygpRKFqKHbz47PljU7LF3WrUCZBPbcmVSXPnZByBAZDZD';
		$id='';
			$attachment =  array(
			'access_token' => $token,
			'message' => $msg,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/me/feed');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
		$result = curl_exec($ch);
		curl_close ($ch);
	}
	
	public function fetchPostComment(){
		$context = stream_context_create(array(
			'http' => array(
			'ignore_errors'=>true,
			'method'=>'GET'
		)
		));
		$response = json_decode(file_get_contents("https://graph.facebook.com/19292868552_10150189643478553/comment?access_token=CAACEdEose0cBANC7IU0UBSdY7Ifawtm9tr7QQKHhlzh4q1PBVrUqiZCv7Ejv3uPfnKX4aeIhbf3eZAZAdmV2ttZCGUQIyFAAGDOsN7EzfpJXIgpl8ePoPLyY4ZAYBhUSW4LEpcFPWakpZBTiTsiiXY35038HmDjMCVlA1UZCi0hVgZDZD", true, $context));
	
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator($response, RecursiveIteratorIterator::SELF_FIRST));

		foreach($jsonIterator as $key => $val) {	
				echo "$key: => $val\n";
		}
	}
	
	
	public function index()
	{ 
	}			
}
	