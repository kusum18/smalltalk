<?php 
require(APPPATH.'libraries/REST_Controller.php');
class ws_api extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Student');
		
	}

	function user_get($row="ss",$col="sdsd"){
		$student_list = new Student();
		$total_rows = $student_list->count();
		
		$index = 0;
		echo $total_rows;
// 		$arr = array();
// 		foreach($o as $obj){
// 			$data[$index] = $obj->name;
// 			$index++;
// 		}
// 		$student_list->clear();
		$list = $student_list->limit(5)->get()->all;
		$stud = new Student();
		$stud->name="Nirav";
		$stud->save();
// 		$students = array();
// 		foreach ($list as $stud){
// 			array_push($students,$stud->name);
// 		}
		
// // 		$data = $students;
// 		$data = new obj
 		//var_dump($list->name);
// 		$data->col = array();
		
// 		foreach($list as $l){
// 			array_push($data->col,$l->name);
// 		}
		
		var_dump($list[1]->id);
		
// 		$this->response($data,200);
		
		
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