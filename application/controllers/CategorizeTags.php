<?php
	require(APPPATH.'libraries/REST_Controller.php');
	class categorizeTags extends REST_Controller {
	
	function __construct(){
		parent::__construct();
		//loding the model
		$this->load->model('post_tag');
		$this->load->model('similar_tag');
		$this->load->helper('categorize');
	}
	
	function tagClassify_post($tags_List, $pid){
		$tag_obj = new Post_tag();
		$tags = checkForSimilarity($tags_List);
		$st_obj_mov = new Similar_tag();
		$st_obj_mus = new Similar_tag();
		$st_obj_tech = new Similar_tag();
		$st_obj_sp = new Similar_tag();
		$st_obj_pl = new Similar_tag();
		$i_count= count($tags);
		$category = array();
		for($i=0;$i<$i_count;$i++){
			$st_obj_mov->where('TagName',tags[$i])->get();
			$st_obj_mus->where('TagName',tags[$i])->get();
			$st_obj_tech->where('TagName',tags[$i])->get();
			$st_obj_sp->where('TagName',tags[$i])->get();
			$st_obj_pl->where('TagName',tags[$i])->get();
		}
		$tag_stack = array();
		if(($st_obj_pl->where('Places',1)->get('TagName'))!='');
			array_push($tag_stack, 'Places');
		if(($st_obj_mov->where('Movies',1)->get('TagName'))!='');
			array_push($tag_stack,'Movies');
		if($st_obj_mus->where('Music',1)->get('TagName'))!='');
			array_push($tag_stack,'Music');
		if(($st_obj_tech->where('Technology',1)->get('TagName'))!='');
			array_push($tag_stack,'Technology');
		if(($st_obj_sp->where('Sports',1)->get('TagName'))!='');
			array_push($tag_stack,'Sports');
		
		$tag_obj->post_id = $pid;
		$size = count($tag_stack);
		for($i = 0; $i < $size; $i++){
			if($tag_stack[i] == 'Places')
				$tag_obj->Places = 1;
			if($tag_stack[i] == 'Movies')
				$tag_obj->Movies = 1;
			if($tag_stack[i] == 'Music')
				$tag_obj->Music = 1;
			if($tag_stack[i] == 'Sports')
				$tag_obj->Sports = 1;
			if($tag_stack[i] == 'Technology')
				$tag_obj->Technology = 1;
		}
		$tag_obj->save();
	}
?>