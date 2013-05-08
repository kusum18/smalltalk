<?php
class Students extends Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Student');
 
        // load url helper
        $this->load->helper('url');
 
        // load session library
        $this->load->library('pagination');        
    }
 
    function index($offset=0)
    {         
        $student_list = new Student();
        $total_rows = $student_list->count();
 
        $student_list->order_by('name');        
        $data['student_list'] = $student_list->get(5, $offset)->all;        
 
        // pagination        
 
        $config['base_url'] = site_url("students");
        $config['total_rows'] = $total_rows;
        $config['per_page'] = '5';
        $config['uri_segment'] = 2;
        $this->pagination->initialize($config);         
 
        $this->load->view('student/index', $data);        
    }   
}
?>