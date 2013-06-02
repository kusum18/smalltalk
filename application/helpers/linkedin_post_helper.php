<?php
session_start();
require_once("OAuth.php");

 
class Linkedin_post {
 
 
	var $data;
 
	function __construct(){
 
		//parent::__construct();
		
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


	
	function fetch($method, $resource, $body = '',$format='json') {
	
		
	
		
		//"AQWEmzfjljKEbjjublEw078ULWcHH505ZCb5GjH_c4FkRqukuz34jaeDfurJ8LTktH87Q-KcB2WMrPsYa_TSdmgEHpgVDPBdtBBWH3EwN4-w1amikqWF5BsLrmwP1HleHGHepgKtk6HH7EeuyzE795jupa5NsJu1NgNO6mdInovOY2cPxqI"
        //echo $this->data['linkedintoken'];
		//Query parameters needed to make a basic OAuth transaction
        $params = array(
            'oauth2_access_token' => $this->data['linkedintoken'],
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
        //print_r($result);
        return $result;
    }
	

	function getComments($linkedinpostid,$linkedintoken){
	
		$this->data['linkedintoken']=$linkedintoken;
	
		$path='/v1/people/~/network/updates/key='.$linkedinpostid.'/update-comments';
		//echo $path;
		return $this->fetch('GET',$path,
        array(
            'comment' => 'Hello Linkedin',
            'content' => array(
                'title' => 'SimpleLinkedIn (SLinkedin)',
                'description' => 'Open source OAuth2 Implementation for PHP and linkedin.',
                'submittedUrl' => 'https://google.com'
            ),
            'visibility' => array('code' => 'anyone' )
        )
    );
 
 
 
	}
 
	function postlinkedin(){
	
	
		return $this->fetch('POST','/v1/people/~/shares',
        array(
            'comment' => '',
            'content' => array(
                'title' => $this->data['post_title'],
                'description' => $this->data['post_text'],
				'submittedUrl' => '\t'
            ),
            'visibility' => array('code' => 'anyone' )
        )
    );
 
 
 
	}
	function postOnLinkedin($post_id, $linkedin_post_id, $post_text, $title, $linkedintoken )
	{
		
		$this->data['linkedinpostid']=$linkedin_post_id;
		$this->data['post_text']=$post_text;
		$this->data['post_title']=$title;
				
		$this->data['linkedintoken']=$linkedintoken;
		$response = $this->postlinkedin();
		return $response;
	
	}
	
	
	
    
 
	}