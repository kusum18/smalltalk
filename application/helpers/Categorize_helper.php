<?php

class Catergorize{
	function __construct(){
		//parent::__construct();
	}

	function checkForSimilarity($tags_List){
		
		$user_tags = array();
		
		
			$user_tags = explode(',', $tags_List);	
			print_r($user_tags);
		
		
		return $user_tags;
	}
}
?>