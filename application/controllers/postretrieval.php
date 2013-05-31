<?php
 require(APPPATH.'libraries/REST_Controller.php');
class Postretrieval extends REST_Controller {
 
  
	function __construct(){
 
		parent::__construct();
		
		//loding the model
		$this->load->model('post_it');
		$this->load->model('geolocation');
 
	 
	}
	function distance ($lat1, $long1, $lat2, $long2)
	{
		/* $theta = $long1 - $long2;
		$dist = (sin(deg2rad($lat1)) * sin(deg2rad($lat2)) )+  (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		echo round($miles,2); */
			//echo "  ";
		//echo "  ";
		
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$long1 *= $pi80;
		$lat2 *= $pi80;
		$long2 *= $pi80;
	 
		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $long2 - $long1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;
		//echo $km * 0.621371192;
		//return ($miles ? ($km * 0.621371192) : $km);
//return $miles;

	}
	function retrieval_get($lat, $long)
	{
							
		//creating the object of table post_it
		$postit_obj= new Post_it();
		
		//creating the object of table geolocation
		$geolocation_obj= new Geolocation();
		
		
		//values
		$postit_obj->get();
		$info = array();
		$finalifno= array();
		foreach ($postit_obj->all as $obj)
		{
			$geolocation_obj->where('id',$obj->location_id)->get();
			$this->distance($lat, $long, $geolocation_obj->lat, $geolocation_obj->long);
			//if ($this->distance($lat, $long, $geolocation_obj->lat, $geolocation_obj->long)<5)
			//{
				$info['place']=$geolocation_obj->place;
				$info['distance']=0;
				$info['notes_id']=$obj->id;
				$info['notes_like']=$obj->likes;
				$info['notes_text']=$obj->text;
				
				$finalifno['notes'][]=$info;
			//}
			
		}
		
		$this->response($finalifno);
	
	}
	

 
	}