<?php 

class facebookfriend extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper('Facebook');
		try{
			//$this->load->model('facebookfriends');	
			$this->load->model('userfriend');
			$this->load->model('users');
		}
		catch(Exception $e){
		}
	}
	public function generateFriendsList(){
		$su = new userfriend();
		$su2 = = new user();
		$context = stream_context_create(array(
		'http' => array(
		'ignore_errors'=>true,
		'method'=>'GET'
	)
	));
	    $response = json_decode(file_get_contents("https://graph.facebook.com/709631419/friends?access_token=CAACEdEose0cBAJTwwtDZAwI0l5jhy7yZBOZBgQ3gZCTApKLjpl3LsXB4mQdYfatrA2Qo29ZCFCbjcjZAy2xVSH53fiCZBuJYokgZCw87p0ZCw1m3mopWqZBlIzbgpPwe0F7TqwfozeZCjIBaZBBZCzEoa20wozMLy8ye4jCsALmPB76YCHAZDZD", true, $context));
	
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator($response, RecursiveIteratorIterator::SELF_FIRST));
		$UserID = 709631419;
		$flag=0;
		foreach($jsonIterator as $key => $val) {
			
			
				echo "$key: => $val\n";
				
				$su->where('FacebookID',$UserID);
				$su->get();
				$su->id=$su2->id;
				if($key=='name'){
					$su->FriendName=$val;
					$flag=$flag+1;
				}
				else if($key=='id'){
					$su2->where('FacebookID',$val);
					$su2.get();
					$su->FriendID=$su2->$id
					$flag=$flag+1;
				}
				$su->isfacebook=1;

				if ($flag==2)
				{
					$su->save();
					$flag=0;
				}
		
	}
	}
	public function postOnWall(){
		$msg='{Middleware,GraphAPI}';
		$token='CAACEdEose0cBAHo1OcI2EMsKDBsST7Fqo4TljkUIIcRoDcC7O7nrBBdOXKZCStI4qkkx8RR39iWPytce3ATvL7LGTP5ZCGuyYflyrCI7ytqSgj1aI0lFVdO1hKa7bk34Hyt22eziq24ZALMMhA40n0nbKbEaavwCzAv1IB5fwZDZD';
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
		$response = json_decode(file_get_contents("https://graph.facebook.com/19292868552_10150189643478553?access_token=CAACEdEose0cBAGEXr4uYaKZCG8IGl4kNgqT5d6ov1VZAx41NUqrQVNAr0GAxd2C7ZB7ip0U6s6UnjFNNI7kGJdXyISBpyX4d17xwTZAMKU1gZAZBkOnJIIyyrPZBsiVBVZCkBK5oHE8euZBIWqNAabQxJ8L6YbwLZAtd2S53HBn8JKUwZDZD", true, $context));
	
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