<?php
require(APPPATH.'libraries/REST_Controller.php');

class fetchFacebookComment extends REST_Controller {
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post');
		$this->load->model('user');
		$this->load->helper('facebook_post');
	}
 
	public function fetchComment_get(){
		$helper_obj = new Facebook_post();
		$post_obj_ret = new Post();
		$post_obj_insert  = new Post();
		$user_obj = new User();
		$user_obj_2 = new User();
		$post_obj_ret->where('post_type',1);
		$post_obj_ret->where('question_answer_id',-1)->get();
		$i=0;
		foreach($post_obj_ret->all as $question)
		{
			if ($question->facebook_post_id !=-1 && $question->facebook_post_id != ''){
				
				//print_r($question);
				$user_obj->where('id', $question->user_id)->get();
				
				$response = $helper_obj->fetchPostComment($user_obj->fbtoken, $question->facebook_post_id);
				print_r($response);
				$data = $response->data;
				
				
				$flag=0;
				$flag_usr=0;
    			for ($i = 0; $i < count($data); $i++)  {
					//echo "$key: => $val\n";
						$response_obj = $data[$i];
						//echo $first->from->id;

						$post_obj_insert->user_id = $question->user_id;
						
						$user_obj->where('FacebookId', $response_obj->from->id)->get();
						if($user_obj->FacebookId=''){
							$user_obj->name = $response_obj->from->name;
							$user_obj->FacebookId = $response_obj->from->id;	
								$user_obj->isRegistered=0;
								$user_obj->save();	
								$user_obj = new user();
						}
						$post_obj_insert->post_text = $response_obj->message;
						$post_obj_insert->count = $response_obj->like_count;
						$post_obj_insert->facebook_post_id = $response_obj->id;
					
						$post_obj_insert->save();
						$post_obj_insert = new Post();
					}			
				} 
			$user_obj = new User();
			}
		}
	
	
	function demo_get(){
		$str = '
{
   "data": [
      {
         "id": "10151689144016420_29151218",
         "from": {
            "name": "Nirav Vora",
            "id": "653421556"
         },
         "message": "fetch this comment :P",
         "can_remove": true,
         "created_time": "2013-06-05T09:00:00+0000",
         "like_count": 0,
         "user_likes": false
      },
      {
         "id": "10151689144016420_29156322",
         "from": {
            "name": "Karan Dhamejani",
            "id": "709631419"
         },
         "message": "Fetching comments.",
         "can_remove": true,
         "created_time": "2013-06-05T17:22:34+0000",
         "like_count": 0,
         "user_likes": false
      },
      {
         "id": "10151689144016420_29156397",
         "from": {
            "name": "Ishdeep Hora",
            "id": "100004794607688"
         },
         "message": "?",
         "can_remove": true,
         "created_time": "2013-06-05T17:27:55+0000",
         "like_count": 0,
         "user_likes": false
      },
      {
         "id": "10151689144016420_29156414",
         "from": {
            "name": "Karan Dhamejani",
            "id": "709631419"
         },
         "message": "Working on a project.. How have you been sirjee?",
         "can_remove": true,
         "created_time": "2013-06-05T17:29:09+0000",
         "like_count": 0,
         "user_likes": false
      },
      {
         "id": "10151689144016420_29162207",
         "from": {
            "name": "Anukriti Dhar",
            "id": "629400346"
         },
         "message": "Yes Karan. Fetch the comment.",
         "can_remove": true,
         "created_time": "2013-06-06T01:18:11+0000",
         "like_count": 0,
         "user_likes": false
      },
      {
         "id": "10151689144016420_29167671",
         "from": {
            "name": "Raunaq S Chhabra",
            "id": "626301217"
         },
         "message": "the little doggy wants to play fetch????",
         "can_remove": true,
         "created_time": "2013-06-06T12:15:35+0000",
         "like_count": 0,
         "user_likes": false
      }
   ],
   "paging": {
      "cursors": {
         "after": "Ng==",
         "before": "MQ=="
      }
   }
}';
$obj = json_decode($str);
$data = $obj->data;
echo count($data).'\n';
$first = $data[4];
echo $first->from->id;
	
	}
	
}