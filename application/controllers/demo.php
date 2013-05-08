<?php 

class demo extends CI_Controller {
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Facebook');
		$this->load->model('Student');	
	}

	function user_get(){
		$student_list = new Student();
		$total_rows = $student_list->count();
		
		$index = 0;
// 		$arr = array();
// 		foreach($o as $obj){
// 			$data[$index] = $obj->name;
// 			$index++;
// 		}
// 		$student_list->clear();
		$list = $student_list->limit(5)->get()->all;
// 		$students = array();
// 		foreach ($list as $stud){
// 			array_push($students,$stud->name);
// 		}
		
// // 		$data = $students;
// 		$data = new obj
// 		$data->name = $list->name;
// 		$data->col = array();
		
// 		foreach($list as $l){
// 			array_push($data->col,$l->name);
// 		}
		
		var_dump($list[1]->id);
		
// 		$this->response($data,200);
		
		
	}
	
	function postit(){
		$data['msg']='ff';
		$this->load->view('demo_facebook',$data);
	}
	
	
	function try(){
		// Get User ID
		$user = $facebook->getUser();

		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.

		if ($user) {
		  try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me');
		  } catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		  }
		}

		// Login or logout url will be needed depending on current user state.
		if ($user) {
		  $logoutUrl = $facebook->getLogoutUrl();
		} else {
		  $loginUrl = $facebook->getLoginUrl();
		}

		// This call will always work since we are fetching public data.
		$naitik = $facebook->api('/naitik');

	}
	
	function postme(){
		
		$data['msg'] = "Hello Facebook";
		$config = array();
		$config['appId'] = '154818154692162';
		$config['secret'] = 'da4f93001d4b00c6568e4ea6b6a665fa';
		$facebook = new Facebook($config);
		$data['status'] = $facebook->getAccessToken();
		$par['req_perms'] = "user_status"; // friends_about_me,friends_location,user_about_me
		if ( $facebook->getUser()==0 ) {
			// no he is not
			//send him to permissions page
			$loginUrl = $facebook->getLoginUrl($par);
			header( "Location: $loginUrl" );
		}
		else {
			//yes, he is already subscribed, or subscribed just now
			echo "<p>everything is ok";
			// write your code here
			}

		$this->load->view('demo_facebook',$data);
	
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */