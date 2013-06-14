<?php
session_start();
require_once("OAuth.php");
 require(APPPATH.'libraries/REST_Controller.php');
class linktest extends REST_Controller {
 
 
	var $data;
 
	function __construct(){
 
		parent::__construct();
 
		parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
		$this->load->helper('url');
		$this->load->library('session');
 
		$this->data['consumer_key'] = "fq4youx8soll";
		$this->data['consumer_secret'] = "KpXnbEfvnWyt4zpk";
		$this->data['callback_url'] = "http://localhost/smalltalk/index.php/linktest/gettoken";
 
	}
	
	function authcode_get()
	{
		//$scope_var="r_fullprofile,r_emailaddress,r_network rw_nus";
	
		redirect("https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=fq4youx8soll&scope=r_fullprofile%20r_emailaddress%20r_network%20rw_nus&state=QSHHFHfhjdHDFlsjdjs109djs7sh&redirect_uri=http://localhost/smalltalk/index.php/linktest/gettoken");
		
 	}
	
	function gettoken_get()
	{
		//echo "redirect    ";
		$auth_code = $this->input->get('code');
		//echo "    hello sd      sds     ";
		$state = $this->input->get('state');
		$callback=$this->data['callback_url'];
		
		redirect("https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&code=$auth_code&redirect_uri=$callback&client_id=fq4youx8soll&client_secret=KpXnbEfvnWyt4zpk");
		//echo $this->input->get('code');
	}
	
	
	
	
	function getfriends()
	{
	
	//print_r(stream_get_wrappers());

	
	
		$xml = simplexml_load_file("https://api.linkedin.com/v1/people/~/connections?oauth2_access_token=AQWEmzfjljKEbjjublEw078ULWcHH505ZCb5GjH_c4FkRqukuz34jaeDfurJ8LTktH87Q-KcB2WMrPsYa_TSdmgEHpgVDPBdtBBWH3EwN4-w1amikqWF5BsLrmwP1HleHGHepgKtk6HH7EeuyzE795jupa5NsJu1NgNO6mdInovOY2cPxqI");
		//print_r($data);
		echo $xml['@attributes'];
		//$xml=simplexml_load_string($data);
		//$xml->getName() . "<br />";

		foreach($xml->children() as $child)
		{
			foreach($child->children() as $subchild)
			{
				echo $subchild->getName() . ": " . $subchild . "<br />";
				
			}
		} 
	}

	function getallactitvities()
	{
		$xml = simplexml_load_file("https://api.linkedin.com/v1/people/~/network/updates?oauth2_access_token=AQWEmzfjljKEbjjublEw078ULWcHH505ZCb5GjH_c4FkRqukuz34jaeDfurJ8LTktH87Q-KcB2WMrPsYa_TSdmgEHpgVDPBdtBBWH3EwN4-w1amikqWF5BsLrmwP1HleHGHepgKtk6HH7EeuyzE795jupa5NsJu1NgNO6mdInovOY2cPxqI");
		print_r($xml);
	}
	
	
	
	
	function fetch($method, $resource, $body = '',$format='json') {
        //Query parameters needed to make a basic OAuth transaction
        $params = array(
            'oauth2_access_token' => "AQWEmzfjljKEbjjublEw078ULWcHH505ZCb5GjH_c4FkRqukuz34jaeDfurJ8LTktH87Q-KcB2WMrPsYa_TSdmgEHpgVDPBdtBBWH3EwN4-w1amikqWF5BsLrmwP1HleHGHepgKtk6HH7EeuyzE795jupa5NsJu1NgNO6mdInovOY2cPxqI",
            'format'              => $format,
        );
        
        //There might be query parameters in the requested resource, we need to merge!
        $urlInfo = parse_url('https://api.linkedin.com'.$resource);
        
        if(isset($urlInfo['query'])){
            $query = parse_str($urlInfo['query']);
            $params = array_merge($params,$query);
        }
        
        //Build resource URI
        $url = 'https://api.linkedin.com' . $urlInfo['path'] . '?' . http_build_query($params);
        
        //Some basic encoding to json if an object or array type is send as body
        if(!is_string($body)){
            if($format=='json'){
                $body = json_encode($body);
            }
            if($format=='xml')
                echo "Please use a String in XML calls to SimpleLinkedIn::fetch()";
        }
        
        $response = $this->requestCURL($method,$url,$body,$format);
        //echo "test";
        if($format=='json'){
            
            // Native PHP object, please
			//echo "test1";
			//print_r($response);
            $response = json_decode($response);
            /* if(isset($response->errorCode)){
                
                //Reset token if expired.
                 if($response->status == 401) $this->resetToken ();
                
                throw new SimpleLinkedInException(
                    $response->message .
                      ' (Request ID: # '.$response->requestId.')', 
                    4+$response->errorCode ,
                    $response
                ); 
            } */
        }
		//print_r($response);
        return $response;
    }
	function requestCURL($method,$url,$postData='',$type='json'){
        $ch = curl_init($url);
        
        if( $method != 'GET' ){
            // set correct HTTP Request type
            switch($method){
                case 'POST': curl_setopt($ch, CURLOPT_POST, 1);
                break;
                case 'PUT' : curl_setopt($ch, CURLOPT_PUT, 1);
                break;
                case 'DELETE' : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            
            //Add request data
            if($method=='POST' || $method=='PUT'){
                $contentTypes = array(
                    'json' => array('application/json','json'),
                    'xml' => array('application/xml','xml'),
                );

                $type = $contentTypes[$type];

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-type: application/$type[0]",
                    "x-li-format: $type[1]",
                    'Connection: close')
                );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData );
            }
            
            
            
        }
        
        //Useful for debugging, do not disable ssl in production!
       //  if($this->DISABLE_SSL_CHECK){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       // } 
        
        //Basic CURL settings
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
        curl_setopt($ch, CURLOPT_HEADER          ,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);

        $result = curl_exec($ch);
        
        return $result;
    }
	
	
	
	/* function linkedin(){
 
 
		$this->load->library('linkedin', $this->data);
		$token = $this->linkedin->get_request_token();
		
		echo "in linkedin";
 
		$_SESSION['oauth_request_token'] = $token['oauth_token'];
		$_SESSION['oauth_request_token_secret'] =   $token['oauth_token_secret'];
 
		$request_link = $this->linkedin->get_authorize_URL($token);
 
		$data['link'] = $request_link;
 
 
		header("Location: " . $request_link);
		
				
		
	} */
	
	function getComments(){
	
		print_r(json_encode($this->fetch('GET','/v1/people/~/network/updates/key=UNIU-191877521-5744535918186876928-SHARE/update-comments',
        array(
            'comment' => 'Hello Linkedin',
            'content' => array(
                'title' => 'SimpleLinkedIn (SLinkedin)',
                'description' => 'Open source OAuth2 Implementation for PHP and linkedin.',
                'submittedUrl' => 'https://google.com'
            ),
            'visibility' => array('code' => 'anyone' )
        )
    )));
 
 
 
	}
 
	function postonlinkedin(){
	
		print_r($this->fetch('POST','/v1/people/~/shares',
        array(
            'comment' => 'Does any one have experience with push notification on iOS ?',
            'content' => array(
                'title' => 'iOS - iPhone',
                'description' => '',
				'submittedUrl' => '\t'
            ),
            'visibility' => array('code' => 'anyone' )
        )
    ));
 
 
 
	}
	
	
	
    
 
	}