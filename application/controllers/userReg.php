<?php
require(APPPATH.'libraries/REST_Controller.php');
class UserReg extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		
		$this->load->model('user');
		$this->load->model('userfriend');
		 
	 
	}
	
	function register_get($user_id,$username1,$type,$id,$token, $devicetoken)
	{
	
		$username=str_replace("%20"," ",$username1);
		//object declaration for the tables
		$userObj = new User();
		if ($user_id==-1)
		{
			if ($type=="fb")
			{
				$userObj->where('FacebookId',$id)->get();
				if ($userObj->id == null)
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->fbtoken = $token;
					$userObj->FacebookId = $id;
					

				}
				else
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->fbtoken = $token;
					$userObj->FacebookId = $id;
					
				}
			}
			else
			{
				$userObj->where('linkedInID',$id)->get();
				if ($userObj->id == null)
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->linkedintoken = $token;
					$userObj->linkedInID = $id;

				}
				else
				{
					$userObj->username = $username;
					$userObj->device_id = $devicetoken;
					$userObj->linkedintoken = $token;
					$userObj->linkedInID = $id;
					
					
				}
			}
			
			
		
		}
		else
		{
			$userObj->where('id',$user_id)->get();
			
			if ($type=="fb")
			{
				$userObj->username = $username;
				$userObj->device_id = $devicetoken;
				$userObj->fbtoken = $token;
				$userObj->FacebookId = $id;

			}
			else
			{
				$userObj->username = $username;
				$userObj->device_id = $devicetoken;
				$userObj->linkedintoken = $token;
				$userObj->linkedInID = $this->getlinkedinid($token);
				$this->getfriends($token,$user_id);
				
					
			}

				
		}
		if($userObj->isRegistered ==0)
		{
			$this->generateFriendsList($userObj->FacebookId,$userObj->fbtoken);
		}
		$userObj->isRegistered = 1;
		$userObj->save();
		$uid = array();
		$uid['user_id']=$userObj->id;
		$this->response($uid);
	}
	function generateFriendsList($fbID,$token){
		$u_obj = new user();
		$uf_obj = new userfriend();
		$new_u_obj = new user();
		$context = stream_context_create(array(
		'http' => array(
		'ignore_errors'=>true,
		'method'=>'GET'
	)
	));
	$url = "https://graph.facebook.com/$fbID/friends?access_token=$token";
	//echo $url;
	    $response = json_decode(file_get_contents($url, true, $context));
		$response = $response->data;
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator($response, RecursiveIteratorIterator::SELF_FIRST));
		$flag=0;
		
		foreach($jsonIterator as $key => $val) {
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
							//echo "$key,$val,".$u_obj->id."\n";
							$u_obj = new user();
						}
					}
		}
				foreach($jsonIterator as $key => $val) {
					
					$new_u_obj->where('FacebookId',$fbID);
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
				
				
				

	}
	function getfriends($token,$uid)
	{
	
	//print_r(stream_get_wrappers());

	
		//$token="AQWxVYyfrsRmUxorraZ9xZSeLXtNBhRG_HYthUN1NTRLryl_oiE3YxI1mrtmDsFWs7xCYqX4EgkuKzAxa9rwTCxDTiIAP7KyDPuKIAb_uuSy-I1rqYjpRMeMJPBx7CY17b9bPjtAiykdWF6J-da2_rDANPc7R6h65zdAQSiO_WId5YPYcEc";
		$xml = simplexml_load_file("https://api.linkedin.com/v1/people/~/connections?oauth2_access_token=$token");
		//print_r($data);
		//echo $xml['@attributes'];
		//$xml=simplexml_load_string($data);
		//$xml->getName() . "<br />";
		$userObj = new User();
		$user = array();
		$userDetails = array();
		foreach($xml->children() as $child)
		{
			foreach($child->children() as $subchild)
			{
				$user[$subchild->getName()]= $subchild;
				//echo $subchild;
			}
			$userDetails["user"][]=$user;
		} 
		
		//print_r($userDetails);
		$userfr = new Userfriend();
		foreach ($userDetails["user"] as $u)
		{
			//print_r($u);
			//echo count($userDetails["user"]);
			$userObj->linkedInID = $u["id"]."";
			$userObj->username = $u["first-name"]." ". $u["last-name"];
			$userObj->save();
			$userfr->friend_id=$userObj->id;
			$userfr->friend_name=$userObj->username;
			$userfr->islinkedin=1;
			$userfr->user_id=$uid;
			$userfr->save();
			
		}
		
		
	}
	
	function getlinkedinid($token)
	{
	
		$xml = simplexml_load_file("https://api.linkedin.com/v1/people/~/id/?oauth2_access_token=$token");
		echo $xml;
	}
	function index()
	{
		
	}
 
	}