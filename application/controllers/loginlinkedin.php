<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Loginlinkedin extends CI_Controller {

	
	public function index()
	{
		$this->load->view('linkedinlogin');
	}
}

/* End of file loginlinkedin.php */
/* Location: ./application/controllers/loginlinkedin.php */