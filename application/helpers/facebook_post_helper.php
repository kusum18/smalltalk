<?php 

class Facebook_post {
	
	function __construct(){
 	
 	}
	
	public function postOnWall($token,$msg)
	{
		//$msg='{Spam post of the day - 1}';
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
		return $result;
	}

}