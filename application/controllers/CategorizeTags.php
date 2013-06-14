<?php
	require(APPPATH.'libraries/REST_Controller.php');
	class categorizeTags extends REST_Controller {
	
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post_tag');
		$this->load->model('similar_tag');
		$this->load->helper('Categorize');
	}
	
	function tagClassify_post(){
		$tags_List = $this->post('tagslist');
		$tag_obj = new Post_tag();
		$pid = $this->post('id');
		$helper_obj = new Catergorize();
		
		$tags = $helper_obj->checkForSimilarity($tags_List);
		//print_r($tags);
		$st_obj_mov = new Similar_tag();
		$st_obj_mus = new Similar_tag();
		$st_obj_tech = new Similar_tag();
		$st_obj = new Similar_tag();
		$st_obj_pl = new Similar_tag();

		$i_count= count($tags);
		$category = array();
		for($i=0;$i<$i_count;$i++){
			//echo($tags[$i]);
			//$st_obj_mov->where('TagName',$tags[$i])->get();
			//$st_obj_mus->where('TagName',$tags[$i])->get();
			//$st_obj_tech->where('TagName',$tags[$i])->get();
			$st_obj->where('TagName',$tags[$i])->get();
			//$st_obj_pl->where('TagName',$tags[$i])->get();
			
			array_push($category, $st_obj);
			$st_obj = new Similar_tag();
		}
		
		//print_r($category);
		foreach ($category as $value) {
			if($value->Sports==1)
				$tag_obj->Sports = 1;
			if($value->Movies==1)
				$tag_obj->Movies = 1;
			if($value->Technology==1)
				$tag_obj->Technology = 1;
			if($value->Places==1)
				$tag_obj->Places = 1;
			if($value->Music==1)
				$tag_obj->Music = 1;
		
		}
		$tag_obj->post_id=$pid;
		$tag_obj->save();
		
	}
}