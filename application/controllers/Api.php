<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    
	function __construct() {
		parent::__construct();
		

		$this->load->database();
		$this->load->model('crud_model');
		$this->load->library('session');
		$this->load->model('Api_model');
		$this->load->model('Email_model');
		
		$this->load->library('Nexmo');
        // set response format: xml or json, default json
        $this->nexmo->set_format('json');

		//$this->load->model('Api_model');
		//$this->load->model('auth_model');
	 	//$this->load->model('Section_model');
		//$this->load->model('Gallery_model');
        //Utils::no_cache();
    }
    
    //nikhil code
    public function get_single_broadcast_data()
        {
            $broad_id=$this->input->post('broad_id');
        	
        	if( empty($broad_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Broad ID is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	    $q="select broadcast_details.id as id,user.name as name, category.name as cat_name, broadcast_details.created_date as c_date,broadcast_details.content_description as cont_description, broadcast_details.broadcast_img as broad_image, broadcast_details.broadcast_video as broadcast_vdo from broadcast_details join user on broadcast_details.user_id=user.user_id join category on broadcast_details.cat_id=category.cat_id";
			$bdata = $this->db->query($q)->result_array();
			$ind_data=array();
    		foreach($bdata as $row){
				if($row['id']==$broad_id){
					$ind_data=$row;
				break;
				}
			}
			//$JSON_ARR[]=$ind_data;
    		$JSON_ARR[]=array(
							'id'=>$ind_data['id'],
							'user_name'=>$ind_data['name'],
							'cat_name'=>$ind_data['cat_name'],
				            'created_data'=>  $ind_data['c_date'],
				            'content_description'=>$ind_data['cont_description'],
				            'broad_image'=>$ind_data['broad_image'],
				            'broad_vdo'=>$ind_data['broad_vdo']	
							);		
				
							
					print json_encode($JSON_ARR);
    		
        }

	public function getmovie($user_id=0)
    {
	  $result_genre= $this->Api_model->get_genre_movie();
	  foreach($result_genre as $row_cat)
	  {
		$result_movie= $this->Api_model->GetAll_MovieOfGenre($row_cat['genre_id']);
		
		$movie_content=[];
		foreach($result_movie as $row_movie)
		{
				$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
				
				
                    $subtitle_contents=[];				
				  if(!empty($row_movie['subtitle_id'] ))
			       {
    			            $array_subtitles = explode(",", $row_movie['subtitle_id']);
                            foreach($array_subtitles as $array_subtitle )    
    			            {
    			                $subtitle_contents[]=$this->Api_model->get_subtitle($array_subtitle);
    			            }
			       }            
			     
				
				
				
				
				
				$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
				// print_r($adtime_content);
				$movie_content[]= array(
					 "id" => $row_movie['movie_id'],
					 "title"=>$row_movie['title'],
					 "subtitle"=>$subtitle_contents,
					 
					 "audio_track"=>$row_movie['audio_track'],
					 
					 
					 
					 
					 
					 "description_short"=>$row_movie['description_short'],
					 
					 "description_long"=>$row_movie['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_movie['url'],
					 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
					 "rating"=>$row_movie['rating'],
					 "advertisement_time"=> $adtime_content 
				   );	
		}
			
			//print_r($row_cat);
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>1,
				'genre_name'=>$row_cat['name'],
				'contents'=>$movie_content
				);
		
	  }
		$continue_watching=$this->get_watch_later_video($user_id);
		
		//$new_released=$this->Api_model->new_released();
		
		$new_released=$this->get_new_released_video();
		
		$ALL_JSON_ARR = array(
				'launch'=>'Movie',
				'continue_watching'=>$continue_watching,
				'new_released'=>$new_released,
				'contents'=>$JSON_ARR
				);
		print json_encode($ALL_JSON_ARR);
		//print json_encode($JSON_ARR);

	 
	}  
	  
	 
	 
	 
	 
	 	public function getmovie_new($user_id=0)
    {
	  $result_genre= $this->Api_model->get_genre_movie();
	  foreach($result_genre as $row_cat)
	  {
		$result_movie= $this->Api_model->GetAll_MovieOfGenre($row_cat['genre_id']);
		$movie_content=[];
		foreach($result_movie as $row_movie)
		{
				
				/*
				$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);

				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
				*/
				
				  $subtitle_contents=[];				
				  if(!empty($row_movie['subtitle_id'] ))
			       {
    			            
    			            $array_subtitles = explode(",", $row_movie['subtitle_id']);
                            foreach($array_subtitles as $array_subtitle )    
    			            {
    			                $array_sub_title=$this->Api_model->get_subtitle($array_subtitle);
    			                $subtitle_contents[]=$array_sub_title[0]['name'];
    			                
    			            }
			       }            
			     
			     
			     
			     $audio_track_contents=[];				
				  if(!empty($row_movie['audio_track_id'] ))
			       {
    			            
    			            $array_audio_tracks = explode(",", $row_movie['audio_track_id']);
                            foreach($array_audio_tracks as $array_audio_track )    
    			            {
    			                $array_track=$this->Api_model->get_audio_track($array_audio_track);
    			                $audio_track_contents[]=$array_track[0]['name'];
    			                
    			            }
			       }
				
				
				 $view_count= $this->Api_model->total_view_movie($row_movie['movie_id']);
				$increase_view=$this->Api_model->increase_movie_view($row_movie['movie_id']);			
				$view_count=$view_count+$increase_view;
			
			
				$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
				// print_r($adtime_content);
				$movie_content[]= array(
					 "id" => $row_movie['movie_id'],
					 "title"=>$row_movie['title'],
					 
					 "subtitle"=>$subtitle_contents,
					 "audio_track"=>$audio_track_contents,
					 
					 "description_short"=>$row_movie['description_short'],
					 "description_long"=>$row_movie['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_movie['url'],
					 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
					 "rating"=>$row_movie['rating'],
					 "view_count"=>number_format($view_count)
				   );	
		}
			
			//print_r($row_cat);
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>1,
				'genre_name'=>$row_cat['name'],
				'contents'=>$movie_content
				);
		
	  }
		$continue_watching=$this->get_watch_later_video($user_id);
		
		
		
		   // $new_released=$this->get_new_released_video();
			$result_ad_time= $this->Api_model->Get_MovieAdv_Time();
            //print_r($result_ad_time);    
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "is_preroll" => $row_ad_time['is_preroll'],
					 "add_time"=>$row_ad_time['add_time']*60
				      );	
    				    
    				}
				}
		
		//'new_released'=>$new_released,
		$new_released=$this->get_new_released_video_new();

		$ALL_JSON_ARR = array(
				'launch'=>'Movie',
				'continue_watching'=>$continue_watching,
				
				'advertisement_time'=> $adtime_content,
				'new_released'=>$new_released,
				'contents'=>$JSON_ARR
				);
		print json_encode($ALL_JSON_ARR);
		//print json_encode($JSON_ARR);

	 
	}  
	 
	 
	  public function get_watch_later_video($user_id=0)
	  {
			$result_movie= $this->Api_model->get_watch_later_movie($user_id);
			$movie_content=[];
			foreach($result_movie as $row_movie)
			{
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
					$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
					// print_r($adtime_content);
					$movie_content[]= array(
						 "id" => $row_movie['movie_id'],
						 "title"=>$row_movie['title'],
						 "description_short"=>$row_movie['description_short'],
						 "description_long"=>$row_movie['description_long'],
						 "streamFormat"=>$ext,
						 "movie_url"=>$row_movie['url'],
						 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
						 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
						 "rating"=>$row_movie['rating'],
						 "advertisement_time"=> $adtime_content 
					   );	
			}
				
			return $movie_content;	
	  }
  
  
  
  
  
  
  
  public function get_new_released_video()
  {
	$new_released=$this->Api_model->new_released();

	$movie_content=[];
			foreach($new_released as $row_movie)
			{
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
					$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
					// print_r($adtime_content);
					$movie_content[]= array(
						 "id" => $row_movie['movie_id'],
						 "title"=>$row_movie['title'],
						 "description_short"=>$row_movie['description_short'],
						 "description_long"=>$row_movie['description_long'],
						 "streamFormat"=>$ext,
						 "movie_url"=>$row_movie['url'],
						 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
						 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
						 "rating"=>$row_movie['rating'],
						 "advertisement_time"=> $adtime_content 
					   );	
			}
			
			return $movie_content;	
				
  }
  
  public function get_new_released_video_new()
  {
	$new_released=$this->Api_model->new_released();

	$movie_content=[];
			foreach($new_released as $row_movie)
			{
					
					/*
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);
					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
					*/
					
					$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
					// print_r($adtime_content);
					$movie_content[]= array(
						 "id" => $row_movie['movie_id'],
						 "title"=>$row_movie['title'],
						 "description_short"=>$row_movie['description_short'],
						 "description_long"=>$row_movie['description_long'],
						 "streamFormat"=>$ext,
						 "movie_url"=>$row_movie['url'],
						 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
						 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
						 "rating"=>$row_movie['rating'],
						 "advertisement_time"=> $adtime_content 
					   );	
			}
			
			return $movie_content;	
				
  }
  

  
	
	public function watch_later_video($user_id=0,$movie_id=0)
	{
		if(!empty($movie_id) and !empty($user_id))
		{
			$sql = "SELECT * FROM watch_later where 
			user_id ='".$user_id."' and movie_id='".$movie_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$JSON_ARR[] = array(
						'response'=>"watch later already added"
					);
					print json_encode($JSON_ARR);
			}
			else
			{
				$data=array(
					'user_id'=>$user_id,
					'movie_id'=>$movie_id
				
				);
				$watch_later_flag= $this->Api_model->add_watch_later_movie($data);
				
				if($watch_later_flag)
				{	
					$JSON_ARR[] = array(
						'response'=>"watch later successfully added"
					);

					print json_encode($JSON_ARR);
					
				}
				
			}	
			
			
			
			
		}
		else
		{
				$JSON_ARR[] = array(
								'response'=>"Wrong data"
								);
						print json_encode($JSON_ARR);
			
		}
		
		
	}
	
	
	public function watch_later_episode($user_id=0,$episode_id=0)
	{
		if(!empty($episode_id) and !empty($user_id))
		{
			$sql = "SELECT * FROM watch_later where 
			user_id ='".$user_id."' and episode_id='".$episode_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$JSON_ARR[] = array(
						'response'=>"watch later already added"
					);
					print json_encode($JSON_ARR);
			}
			else
			{
				$data=array(
					'user_id'=>$user_id,
					'episode_id'=>$episode_id
				
				);
				$watch_later_flag= $this->Api_model->add_watch_later_movie($data);
				
				if($watch_later_flag)
				{	
					$JSON_ARR[] = array(
						'response'=>"watch later successfully added"
					);

					print json_encode($JSON_ARR);
					
				}
				
			}	
			
			
			
			
		}
		else
		{
				$JSON_ARR[] = array(
								'response'=>"Wrong data"
								);
						print json_encode($JSON_ARR);
			
		}
		
		
	}
	
	public function delete_watch_later_episode($user_id=0,$episode_id=0)
	{
		if(!empty($episode_id) and !empty($user_id))
		{
			$watch_later_flag= $this->Api_model->delete_watch_later_episode($user_id,$episode_id);
			
			if($watch_later_flag)
			{
				$JSON_ARR[] = array(
					'response'=>"watch later successfully deleted"
				);
				print json_encode($JSON_ARR);
			}	
			
		}	
		else
		{
				$JSON_ARR[] = array(
								'response'=>"Wrong data"
								);
						print json_encode($JSON_ARR);
			
		}
	}
	
	
	
	
	
	public function delete_watch_later_video($user_id=0,$movie_id=0)
	{
		if(!empty($movie_id) and !empty($user_id))
		{
			
			$watch_later_flag= $this->Api_model->delete_watch_later_movie($user_id,$movie_id);
			
			if($watch_later_flag)
			{
				$JSON_ARR[] = array(
					'response'=>"watch later successfully deleted"
				);
				print json_encode($JSON_ARR);
			}	
			
		}	
		else
		{
				$JSON_ARR[] = array(
								'response'=>"Wrong data"
								);
						print json_encode($JSON_ARR);
			
		}
	}
	
	
	/*

	public function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = $this->utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	
*/
	
	
		
	 public function getlive_new21()
   {
	 
	  
	  	 $type="";
		 $page_no=$this->input->post('page');
       	 
		
		 $limit=30; 
         //expression ? trueValue : falseValue
         $page=(!empty($page_no)) ? $page_no : 1;
         if($page==1)
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }	 	 
		 
		
	  $result_genre= $this->Api_model->get_genre_live($start,$limit);
	  //echo $this->db->last_query();
	  //echo "<pre>";
	  //print_r($result_genre);
	  ///exit;
	  
		 
	  $JSON_ARR11=[];
	  foreach($result_genre as $row_cat)
	  {
		
		 $type="live"; 		
		   //echo "======".$row_cat['genre_id']; 
		$live_content=[];
		$result_live= $this->Api_model->GetAll_LiveOfGenre($row_cat['genre_id']);
		  
		
		  
		$live_content=[];
		$music_parent_JSON_ARR=[];  
		foreach($result_live as $row_live)
		{
				//echo $ii."======".$row_cat['genre_id'];		
				//$view_count= $this->Api_model->total_view_live($row_live['live_id']);
				
				$view_count=$row_live['view_count'];
				$increease_view_count=0;
				$increease_view_count= $this->Api_model->increase_live_view($row_live['live_id']); 	
				$view_count=$view_count+$increease_view_count;   
				
			
				$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
				$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "sub_title"=>$row_live['sub_title'],
					 "audio_track"=>$row_live['audio_track'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_live['url'],
					 "movie_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "view_count"=>number_format($view_count),
					 "rating"=>$row_live['rating']
					 
				   );	
			
		}
		
		  
		if($row_cat['parent']==1)
		{
		 	//$music_live_content=[];
			$live_content=[];
			$type="music"; 	
		/////////////////////////music///////////////////////////////
	  
	  //$result_parent_genre= $this->Api_model->get_music_parent_genre_live($row_cat['genre_id']);
		
	
		
		//foreach($result_parent_genre as $parent_genre )
		//{
		 
			///echo "==========================".$row_cat['genre_id'];
		
		 $result_music_genre= $this->Api_model->get_music_genre_live($row_cat['genre_id']);
				$music_JSON_ARR=[];
        		foreach($result_music_genre as $music_genre )
        		{
        			//print_r($music_genre);
				
					$result_music_lives= $this->Api_model->GetAll_music_LiveOfGenre($music_genre['genre_id']);
        	        
					
					//echo "<pre>";
					//print_r($result_music_lives);
					
					foreach($result_music_lives as $result_music_live )
        	        {
                        $view_count= $this->Api_model->total_view_live($result_music_live['live_id']);
						$increease_view_count=0;
						$increease_view_count= $this->Api_model->increase_live_view($row_live['live_id']); 	
						$view_count=$view_count+$increease_view_count;   

						
                	    $ext = pathinfo($result_music_live['url'], PATHINFO_EXTENSION);
        				$live_content[]= array(
							"genre_id"=>$music_genre['genre_id'],
        					'genre_name'=>$music_genre['name'],
        					 "id" => $result_music_live['live_id'],
        					 "title"=>$result_music_live['title'],
        					 "sub_title"=>$result_music_live['sub_title'],
        					 "audio_track"=>$result_music_live['audio_track'],
        					 "description_short"=>$result_music_live['description_short'],
        					 "description_long"=>$result_music_live['description_long'],
        					 "streamFormat"=>$ext,
        					 "movie_url"=>$result_music_live['url'],
        					 "movie_poster"=>base_url().'assets/global/live_poster/'.$result_music_live['live_id'].".jpg",
        					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$result_music_live['live_id'].".jpg",
        					 "view_count"=>number_format($view_count),
        					 "rating1"=>$result_music_live['rating']
        					 
        				   );	
        	        }
                       /*
                        $music_JSON_ARR[] = array(
        				"genre_id"=>$music_genre['genre_id'],
        			
        				'genre_name'=>$music_genre['name'],
        				'contents'=>$music_live_content
        				);		
						*/
        	
        		}
        		/*
        		 $music_parent_JSON_ARR = array(
        				"parent_genre_id"=>$row_cat['genre_id'],
        			
        				'parent_genre_name'=>$row_cat['name'],
        				'contents'=>$music_JSON_ARR
        				);		
        		
        		*/	
		//}
	  
	  //////////////////////////end music/////////////////////

			
			
			
			  //$live_content=[];
			  //$live_content[]=$music_live_content;
		  
		  }	  

			
		
			
			$JSON_ARR11[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"type"=>$type,
				"channel_id"=>2,
				'genre_name'=>$row_cat['name'],
				'live_contents'=>$live_content
				);
		  
		  
		
		  
		  
		///print json_encode($JSON_ARR11);
	
	  }
	 
	  
	  $ALL_JSON_ARR = array(
				"launch"=>'Live',
		  		
				"contents"=>$JSON_ARR11,			
				);
		print json_encode($ALL_JSON_ARR);

			        /*
            $result_ad_time= $this->Api_model->Get_liveAdv_Time();
          
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "add_time"=>$row_ad_time['add_time']*60,
				     "is_preroll"=>$row_ad_time['is_preroll']
				      );	
    				    
    				}
				}
		$ALL_JSON_ARR = array(
				"launch"=>'Live',
				"advertisement_time"=> $adtime_content ,
				'contents'=>$JSON_ARR,
				'music_contents'=> $music_parent_JSON_ARR
				);
		print json_encode($ALL_JSON_ARR);
	  
	  */
	}  


	
	
	
	


  public function getlive_new11_old()
   {
	 
	  
	  /////////////////////////music///////////////////////////////
	  
	  $result_parent_genre= $this->Api_model->get_music_parent_genre_live();
		
	
		$music_live_content=[];
		foreach($result_parent_genre as $parent_genre )
		{
		 
		 $result_music_genre= $this->Api_model->get_music_genre_live($parent_genre['genre_id']);
		
        		foreach($result_music_genre as $music_genre )
        		{
        			$result_music_lives= $this->Api_model->GetAll_music_LiveOfGenre($music_genre['genre_id']);
        	        foreach($result_music_lives as $result_music_live )
        	        {
                        $view_count= $this->Api_model->total_view_live($result_music_live['live_id']);
						

                	    $ext = pathinfo($result_music_live['url'], PATHINFO_EXTENSION);
        				$music_live_content[]= array(
        					 "id" => $result_music_live['live_id'],
        					 "title"=>$result_music_live['title'],
        					 "sub_title"=>$result_music_live['sub_title'],
        					 "audio_track"=>$result_music_live['audio_track'],
        					 "description_short"=>$result_music_live['description_short'],
        					 "description_long"=>$result_music_live['description_long'],
        					 "streamFormat"=>$ext,
        					 "movie_url"=>$result_music_live['url'],
        					 "movie_poster"=>base_url().'assets/global/live_poster/'.$result_music_live['live_id'].".jpg",
        					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$result_music_live['live_id'].".jpg",
        					 "view_count"=>$view_count,
        					 "rating1"=>$result_music_live['rating']
        					 
        				   );	
        	        }
                        
                        $music_JSON_ARR[] = array(
        				"genre_id"=>$music_genre['genre_id'],
        			
        				'genre_name'=>$music_genre['name'],
        				'contents'=>$music_live_content
        				);		

        	
        		}
        		
        		 $music_parent_JSON_ARR = array(
        				"parent_genre_id"=>$parent_genre['genre_id'],
        			
        				'parent_genre_name'=>$parent_genre['name'],
        				'contents'=>$music_JSON_ARR
        				);		
        		
        			
		}

	  
	  
	  
	  
	  
	  
	  //////////////////////////end music/////////////////////
	  
	  $result_genre= $this->Api_model->get_genre_live();
	  //echo $this->db->last_query();
	  //echo "<pre>";
	  //print_r($result_genre);
	  ///exit;
	  
	  $JSON_ARR11=[];
	  foreach($result_genre as $row_cat)
	  {
		
		
		   //echo "======".$row_cat['genre_id']; 
		$live_content=[];
		$result_live= $this->Api_model->GetAll_LiveOfGenre($row_cat['genre_id']);
		$live_content=[];
		foreach($result_live as $row_live)
		{
				//echo $ii."======".$row_cat['genre_id'];		
				$view_count= $this->Api_model->total_view_live($row_live['live_id']);
				
				$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
				$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "sub_title"=>$row_live['sub_title'],
					 "audio_track"=>$row_live['audio_track'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_live['url'],
					 "movie_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "view_count"=>$view_count,
					 "rating"=>$row_live['rating']
					 
				   );	
			
		}
		
		  
		if($row_cat['genre_id']==39)
		{
		  //echo "===========================";
			//  exit;
			  $live_content=[];
			  $live_content[]=$music_parent_JSON_ARR;
		  
		  }	  

			
		
			
			$JSON_ARR11[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>2,
				'genre_name'=>$row_cat['name'],
				'live_contents'=>$live_content
				);
		  
		  
		
		  
		  
		///print json_encode($JSON_ARR11);
	
	  }
	 
	  
	  $ALL_JSON_ARR = array(
				"launch"=>'Live',
				'contents'=>$JSON_ARR11,			
				);
		print json_encode($ALL_JSON_ARR);

			        /*
            $result_ad_time= $this->Api_model->Get_liveAdv_Time();
          
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "add_time"=>$row_ad_time['add_time']*60,
				     "is_preroll"=>$row_ad_time['is_preroll']
				      );	
    				    
    				}
				}
		$ALL_JSON_ARR = array(
				"launch"=>'Live',
				"advertisement_time"=> $adtime_content ,
				'contents'=>$JSON_ARR,
				'music_contents'=> $music_parent_JSON_ARR
				);
		print json_encode($ALL_JSON_ARR);
	  
	  */
	}  
	

	
	  public function getlive_new_old()
   {
	  $result_genre= $this->Api_model->get_genre_live();
	  foreach($result_genre as $row_cat)
	  {
		$result_live= $this->Api_model->GetAll_LiveOfGenre($row_cat['genre_id']);
		$live_content=[];
		foreach($result_live as $row_live)
		{
						
				$view_count= $this->Api_model->total_view_live($row_live['live_id']);

				$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
				$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "sub_title"=>$row_live['sub_title'],
					 "audio_track"=>$row_live['audio_track'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_live['url'],
					 "movie_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "view_count"=>number_format($view_count),
					 "rating"=>$row_live['rating']
					 
				   );	
			
		}
			
		
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>2,
				'genre_name'=>$row_cat['name'],
				'contents'=>$live_content
				);
	  }
	 

		$result_parent_genre= $this->Api_model->get_music_parent_genre_live();
		
	
		$music_live_content=[];
		foreach($result_parent_genre as $parent_genre )
		{
		 
		 $result_music_genre= $this->Api_model->get_music_genre_live($parent_genre['genre_id']);
		
        		foreach($result_music_genre as $music_genre )
        		{
        			$result_music_lives= $this->Api_model->GetAll_music_LiveOfGenre($music_genre['genre_id']);
        	        foreach($result_music_lives as $result_music_live )
        	        {
                        $view_count= $this->Api_model->total_view_live($result_music_live['live_id']);
                	    $ext = pathinfo($result_music_live['url'], PATHINFO_EXTENSION);
        				$music_live_content[]= array(
        					 "id" => $result_music_live['live_id'],
        					 "title"=>$result_music_live['title'],
        					 "sub_title"=>$result_music_live['sub_title'],
        					 "audio_track"=>$result_music_live['audio_track'],
        					 "description_short"=>$result_music_live['description_short'],
        					 "description_long"=>$result_music_live['description_long'],
        					 "streamFormat"=>$ext,
        					 "movie_url"=>$result_music_live['url'],
        					 "movie_poster"=>base_url().'assets/global/live_poster/'.$result_music_live['live_id'].".jpg",
        					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$result_music_live['live_id'].".jpg",
        					 "view_count"=>number_format($view_count),
        					 "rating"=>$result_music_live['rating']
        					 
        				   );	
        	        }
                        
                        $music_JSON_ARR[] = array(
        				"genre_id"=>$music_genre['genre_id'],
        			
        				'genre_name'=>$music_genre['name'],
        				'contents'=>$music_live_content
        				);		

        	
        		}
        		
        		 $music_parent_JSON_ARR[] = array(
        				"parent_genre_id"=>$parent_genre['genre_id'],
        			
        				'parent_genre_name'=>$parent_genre['name'],
        				'contents'=>$music_JSON_ARR
        				);		
        		
        			
		}
	        
            $result_ad_time= $this->Api_model->Get_liveAdv_Time();
          
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "add_time"=>$row_ad_time['add_time']*60,
				     "is_preroll"=>$row_ad_time['is_preroll']
				      );	
    				    
    				}
				}
		$ALL_JSON_ARR = array(
				"launch"=>'Live',
				"advertisement_time"=> $adtime_content ,
				'contents'=>$JSON_ARR,
				'music_contents'=> $music_parent_JSON_ARR
				);
		print json_encode($ALL_JSON_ARR);
	}  
	

	
	
	
	
	public function getlive_old()
   {
	  $result_genre= $this->Api_model->get_genre_live();
	  
	  foreach($result_genre as $row_cat)
	  {
		$result_live= $this->Api_model->GetAll_LiveOfGenre($row_cat['genre_id']);
		$live_content=[];
		foreach($result_live as $row_live)
		{
				$result_ad_time= $this->Api_model->Get_liveAdvertisement_Time($row_live['live_id']);

				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
			
				$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
				$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "sub_title"=>$row_live['sub_title'],
					 "audio_track"=>$row_live['audio_track'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_live['url'],
					 "movie_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "rating"=>$row_live['rating'],
					 "advertisement_time"=> $adtime_content 
				   );	
			
		}
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>2,
				'genre_name'=>$row_cat['name'],
				'contents'=>$live_content
				);

	  }
	 
		$ALL_JSON_ARR = array(
				"launch"=>'Live',
				'contents'=>$JSON_ARR
				
				);
		print json_encode($ALL_JSON_ARR);
	}  
	
	
	
	
	public function GetSeries_mobile()	
	{
		$result_cat=$this->Api_model->GetAll_Series_Genre();
		
		$season_genre=[];
		foreach($result_cat as $cat_row)
		{
			$result_series= $this->Api_model->GetAll_SeriesOfGenre($cat_row['genre_id']);
			$series_content=[];
			foreach($result_series as $row_series)
			{
					$result_season= $this->Api_model->GetAll_SeasonOfSeries($row_series['series_id']);
					$season_content=[];
					foreach($result_season as $row_season )
					{
						
						
						$result_episode= $this->Api_model->GetAll_episodeOfSeason($row_season['season_id']);
						$episode_content=[];
						foreach($result_episode as $row_episode)
						{
							 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
							 
							 $episode_content[]= array(
							 "episode_id" => $row_episode['episode_id'],
							 "title" => $row_episode['title'],
							 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['episode_id'].'.jpg',
							 "streamFormat"=>$ext,
							 "url" => $row_episode['url']
							);	
						}
						
						$season_content[]= array(
						"season_id" => $row_season['season_id'],
						"name" => $row_season['name'],
						"episode_content"=>$episode_content
						);	
					

					}


				$series_content[]= array(
				"series_id"=>$row_series['series_id'],
				"title"=>$row_series['title'],
				"description_short"=>$row_series['description_short'],
				"description_long"=>$row_series['description_long'],
				"year"=>$row_series['year'],
				"rating"=>$row_series['rating'],
				"series_poster"=>base_url().'assets/global/series_poster/'.$row_series['series_id'].".jpg",
				"series_thumb"=>base_url().'assets/global/series_thumb/'.$row_series['series_id'].".jpg",
				"season_content"=>$season_content
				);
			}
			
			$season_genre[]= array(
			"genre_id" => $cat_row['genre_id'],
			"name" => $cat_row['name'],
			"series_content"=>$series_content
			);	
		}


			$ALL_JSON_ARR = array(
				"launch"=>'series',
				'contents'=>$season_genre
				);
			print json_encode($ALL_JSON_ARR);
	}

	
	
	
	
	public function signup_flutter()	
	{		
			$email=$this->input->post('email');
			$password=$this->input->post('password');
			$dob=trim($this->input->post('dob'));
			$name=trim($this->input->post('name'));
			$gender=$this->input->post('gender');
			$country_name=$this->input->post('country_name');
			
		
			//$country_code=$this->input->post('country_code');
			//$mobile=$this->input->post('mobile');
			
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');
			
				//$password=$_REQUEST['password'];
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
			 $JSON_ARR = array(
			 'response'=>"Not valid email",
			 'success'=>0
			 
			 );

				print json_encode($JSON_ARR);
				die();
						
			}


            
			
			if( empty($name))
            {
                
                $JSON_ARR = array(
    					'response'=>"Name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			
			
			
			if( empty($dob))
            {
                
                $JSON_ARR= array(
    					'response'=>"Dob is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($gender))
            {
                
                $JSON_ARR= array(
    					'response'=>"Gender is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
           

			
			$array_dob= explode('-',$dob);
			$date_of_birth=$array_dob[2]."-".$array_dob[1]."-".$array_dob[0];
			if(	isset($email) &&  isset($password)  )
			{
				$sql = "SELECT *  FROM user where email ='".trim($email)."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					//return $row;
					$JSON_ARR= array(
							'response'=>"This user already exists",
							'user_id'=>intval($row[0]['user_id']),
					        "otp"=>'',
							'success'=>0
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
				    
				    
				    $otps=$this->crud_model->generateNumericOTP(6);
					$data=array(
					"email"=>$email,
					"password"=>sha1($password),
					"name"=>$name,
					"otp"=>$otps,
					"country"=>$country_name,
					"gender"=>$gender,
					
					"dob"=>$date_of_birth,
					"device_token"=>$device_token,
					"device_type"=>$device_type,
					"created_date"=>date('Y-m-d')
					//"device_token"=>$this->input->post('device_token')
					);
			
						$signup_id= $this->Api_model->signup($data);
						
						if($signup_id){
							
							$new_message = "
								<html>
                                <head>
                                <title>Verification Code</title>
                                </head>
                                <body>
                                <h2>Thank you for registration with Happy Watch 99</h2>
                                <p>You have received your verification code</p>
                                <p>Your Account:</p>
                                <p>Name: ".$name."</p>
                                <p>Email: ".$email."</p>
                                <p>mobile number: ".$country_code."-".$mobile."</p>
                                <p>your verification code is </p>
                                <p>".$otps."</p>
                                </body>
                                </html>
							";


                                ///<p>Please click the link below to activate your account.</p>
                                ///<h4><a href='".base_url()."index.php?home/activate/".$this->my_simple_crypt($user_id,'e')."/".$code."'>Activate My Account</a></h4>
                                


							$email_msg	=	$new_message;
							$email_sub	=	"Activate your happy watch 99";
							$email_to	=	$_REQUEST['email'];
							$this->Email_model->do_email($email_msg , $email_sub , $email_to);
							
							

							$JSON_ARR = array(
									'response'=>"Your account is register successfully",
									'user_id'=>$signup_id,
					                "otp"=>$otps,
					                'success'=>1
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data"
									);
							print json_encode($JSON_ARR);
				
			}
		
	}
	
	
	
	
	
		
	
	public function signup()	
	{	
			$email=$this->input->post('email');
			$password=$this->input->post('password');
			$dob=trim($this->input->post('dob'));
			
			$name=trim($this->input->post('name'));
			
			$gender=$this->input->post('gender');
			$country_name=$this->input->post('country_name');
			
			$country_code=$this->input->post('country_code');
			$mobile=$this->input->post('mobile');
			
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');
			
				//$password=$_REQUEST['password'];
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
			 $JSON_ARR = array(
			 'response'=>"Not valid email",
			 'success'=>0
			 
			 );

				print json_encode($JSON_ARR);
				die();
						
			}

            if( empty($password))
            {
                
                $JSON_ARR= array(
    					'response'=>"Password is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			if( empty($name))
            {
                
                $JSON_ARR = array(
    					'response'=>"Name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			
			
			
			if( empty($dob))
            {
                
                $JSON_ARR= array(
    					'response'=>"Dob is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($gender))
            {
                
                $JSON_ARR= array(
    					'response'=>"Gender is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_name))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_code))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country code is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            /*
            if( empty($mobile))
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no is required"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            */
            if( !empty($mobile))
            {
            $where_as=" ( (SUBSTRING(mobile,-10)='".trim($mobile)."') or mobile='".$mobile."')";
				$sql = "SELECT *  FROM user where $where_as ";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					$JSON_ARR= array(
							'response'=>"This mobile number already exists",
							'user_id'=>intval($row[0]['user_id']),
							'success'=>0
							);
					print json_encode($JSON_ARR);
					die();

				}
            }
            

			
			$array_dob= explode('-',$dob);
			$date_of_birth=$array_dob[2]."-".$array_dob[1]."-".$array_dob[0];

			
			
			if(	isset($email) &&  isset($password)  )
			{
                
				
			

				$sql = "SELECT *  FROM user where email ='".trim($email)."'";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					//$row = $res->result_array();
					//return $row;
					$JSON_ARR= array(
							'response'=>"This user already exists",
							'success'=>0
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
				    
				    
				    $otps=$this->crud_model->generateNumericOTP(6);
					$data=array(
					"email"=>$email,
					"password"=>sha1($password),
					"name"=>$name,
					"otp"=>$otps,
					"country_code"=>$country_code,
					"mobile"=>$mobile,
					"gender"=>$gender,
					"country"=>$country_name,
					"dob"=>$date_of_birth,
					"device_token"=>$device_token,
					"device_type"=>$device_type,
					"created_date"=>date('Y-m-d')
					//"device_token"=>$this->input->post('device_token')
					);
			
						$signup_id= $this->Api_model->signup($data);
						
						if($signup_id){
							
							$new_message = "
								<html>
                                <head>
                                <title>Verification Code</title>
                                </head>
                                <body>
                                <h2>Thank you for registration with Happy Watch 99</h2>
                                <p>You have received your verification code</p>
                                <p>Your Account:</p>
                                <p>Name: ".$name."</p>
                                <p>Email: ".$email."</p>
                                <p>mobile number: ".$country_code."-".$mobile."</p>
                                <p>your verification code is </p>
                                <p>".$otps."</p>
                                </body>
                                </html>
							";


                                ///<p>Please click the link below to activate your account.</p>
                                ///<h4><a href='".base_url()."index.php?home/activate/".$this->my_simple_crypt($user_id,'e')."/".$code."'>Activate My Account</a></h4>
                                


							$email_msg	=	$new_message;
							$email_sub	=	"Activate your happy watch 99";
							$email_to	=	$_REQUEST['email'];
							$this->Email_model->do_email($email_msg , $email_sub , $email_to);
							
							

							$JSON_ARR = array(
									'response'=>"Your account is register successfully",
									'user_id'=>$signup_id,
									
									"email"=>$email,
					                "name"=>$name,
					                "otp"=>$otps,
					                "country_code"=>$country_code,
					               "mobile"=>$mobile,
					                "gender"=>$gender,
					                "country_name"=>$country_name,
					                "dob"=>$date_of_birth,
					                'success'=>1
									

									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data"
									);
							print json_encode($JSON_ARR);
				
			}
		
	}
	
	
	
	
	///////////////////////////////////////////////
	
public function otp_resend()	
	{	
			$user_id=$this->input->post('user_id');

            if( empty($user_id))
            {
                
                $JSON_ARR= array(
    					'response'=>"User Id is required"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
            
            if( !empty($user_id))
            {
            	$sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() == 0) 
				{
					$row = $res->result_array();
					$JSON_ARR= array(
							'response'=>"this user does not exist"
							);
					print json_encode($JSON_ARR);
					die();

				}
            }
            

				    $otps=$this->crud_model->generateNumericOTP(6);
					$data=array(
					"otp"=>$otps
					);
			
						$otp_update= $this->Api_model->edit_profile($data,$user_id);
							$JSON_ARR = array(
									'response'=>"you have successfully reset your otp",
								    'OTP'=>$otps,
									'user_id'=>$user_id
									);

							print json_encode($JSON_ARR);
					       die();     

	}
	
	
	
	
	
	////////////////////////////////////////////////////
public function activate($data, $id){
	    
		$this->db->where('user.user_id', $this->my_simple_crypt($id,'d'));
		return $this->db->update('user', $data);
	}
	
function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}
	
// 	public function otp_account_active()	
// 	{
// 		$email=	$this->input->post('email');
// 		$otps=	$this->input->post('otps');
// 		$acitve_process= $this->Api_model->otp_active($email,$otps);
// 		if($acitve_process>0){
// 			$this->Api_model->otp_active_success($email);
// 			$this->Api_model->otp_delete($email);
// 			$JSON_ARR[] = array(
// 				'response'=>"Welcome! your Account Activated successfully...",
// 				);

// 			print json_encode($JSON_ARR);
// 		}else{
// 			$JSON_ARR[] = array(
// 				'response'=>"Wrong data"
// 				);
// 			print json_encode($JSON_ARR);
// 		}

// 	}
	
	public function signin()	
	{		
			
			$email=	$this->input->post('email');
			$password=$this->input->post('password');
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');
			
			if(	!empty($email)  and   !empty($password)  )
			{	
				
				
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR = array(
				 'response'=>"Not valid email"
				 );
							print json_encode($JSON_ARR);
							die();
							
				}
				$result_signin= $this->Api_model->user_signin();
				
				if(!empty($result_signin))
				{
					$updateData=array(
					"device_token"=>$device_token,
					"device_type"=>$device_type
					);
					
					$this->db->where("user_id",$result_signin[0]['user_id']);
					$this->db->update("user",$updateData);  
                        $country_name=$result_signin[0]['country'];
					

					$user_id=intval($result_signin[0]['user_id']);
					$broadcast_row= $this->Api_model->get_channel_details($user_id);
					
					
					
					 //$channel_details_jsoan=json_decode( $broadcast_row[0]['channel_details_jsoan']);
					 
					 $array_channel_details= array(
    				'show_dist_protocol'=>$broadcast_row[0]['show_dist_protocol'],
                    'show_domain'=>$broadcast_row[0]['show_domain'],
                    'show_application'=>$broadcast_row[0]['show_application'],
                    'show_master_stream'=>$broadcast_row[0]['show_master_stream'],
                    'show_pub_protocol'=>$broadcast_row[0]['show_pub_protocol'],
                    'show_primary'=>$broadcast_row[0]['show_primary'],
                    'show_backup'=>$broadcast_row[0]['show_backup'],
                    'show_stream_name'=>$broadcast_row[0]['show_stream_name'],
                    'show_password'=>$broadcast_row[0]['show_password']	
				
				        );
	            
	            
	            
                
					 

					$JSON_ARR=array(
							'user_id'=>intval($result_signin[0]['user_id']),
							'useremail'=>$email,
							'country_code'=>$result_signin[0]['country_code'],
							'mobile'=>$result_signin[0]['mobile'],
				            'dob'=>  $result_signin[0]['dob'],
				            'name'=>$result_signin[0]['name'],
				            'gender'=>$result_signin[0]['gender'],
				            'country_name'=>$country_name,
				            'profile_image'=>$this->crud_model->get_profile_image_url(intval($result_signin[0]['user_id'])),
				            
							'is_login'=>"YES",
						'channel_id'=>$broadcast_row[0]['channel_id'],	
						'channel_name'=>$broadcast_row[0]['channel_name'],
						'channel_detrail'=>$array_channel_details
                	    
                	    
							);		
				
							
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR=array(
							'useremail'=>"",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'is_login'=>"NO"
									);
					print json_encode($JSON_ARR);
			}

	
	
	
	}
	
	
	/*
	public function email_signup()	
	{	
			if(	isset($_REQUEST['email'])  )
			{
				$email=$_REQUEST['email'];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR[] = array(
				 'response'=>"Not valid email",
				 'success'=>0
				 );
							print json_encode($JSON_ARR);
							die();
				}


				$sql = "SELECT *  FROM user where email ='".trim($_REQUEST['email'])."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					//$row = $res->result_array();
					//return $row;
					$JSON_ARR[] = array(
							'response'=>"This email already exists",
							'success'=>1
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
					$password=$this->randomPassword();
					$data=array(
					"email"=>$_REQUEST['email'],
					"password"=>sha1($password)
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'success'=>1,
									'password'=>$password,
									'user_id'=>intval($signup_id),
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data"
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	*/
	
	
		public function email_signup_old()	
		{	
			if(	isset($_REQUEST['email'])  )
			{
				$email=$_REQUEST['email'];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR[] = array(
				 'response'=>"Not valid email",
				 'success'=>0
				 );
							print json_encode($JSON_ARR);
							die();
				}


				$sql = "SELECT *  FROM user where email ='".trim($_REQUEST['email'])."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					
					$JSON_ARR[] = array(
							'response'=>"you have signed in successfully...",
							'success'=>1,
							'user_id'=>intval($row[0]['user_id'])
							);
					print json_encode($JSON_ARR);
					exit;
				}
				else
				{
					///$password=$this->randomPassword();
					$data=array(
					"email"=>$_REQUEST['email']
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'success'=>1,
									'user_id'=>intval($signup_id),
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data"
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	
	
	
	
	public function mobile_signup_27_5_20()	
	{	
			$mobile=	$this->input->post('mobile');
			$password=$this->input->post('password');
			
			if(	!empty($mobile) and !empty($password) )  
			{
				
				//$sql = "SELECT *  FROM user where 	mobile ='".trim($mobile)."'";
				
				$where_as=" ( (SUBSTRING(mobile,-10)='".trim($mobile)."') or mobile='".$mobile."')";
				$sql = "SELECT *  FROM user where $where_as ";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					$JSON_ARR[] = array(
							'response'=>"you have signed in successfully...",							'success'=>1,
							'user_id'=>intval($row[0]['user_id'])
							);
					print json_encode($JSON_ARR);
				}
				else
				{
					$data=array(
					"mobile"=>trim($mobile),
					"password"=>sha1($password)
					);
					

						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>intval($signup_id),
									'success'=>1
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	


    public function mobile_signup()	
	{	
			$mobile=	$this->input->post('mobile');
			////$password=$this->input->post('password');
			
			$email=$this->input->post('email');
			////$password=$this->input->post('password');
			$dob=trim($this->input->post('dob'));
			$name=trim($this->input->post('name'));
			$gender=$this->input->post('gender');
			$country_name=$this->input->post('country_name');
			$country_code=$this->input->post('country_code');
			
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');

			
			
			if( empty($mobile))
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
            if( strlen($mobile)>10)
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no not exists ten digit",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
			
			
		if(!empty($email))
		{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
			 $JSON_ARR = array(
			 'response'=>"Not valid email",
			 'success'=>0
			 );

				print json_encode($JSON_ARR);
				die();
						
			}

		}    

          
			
			if( empty($name))
            {
                
                $JSON_ARR = array(
    					'response'=>"Name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			
			
			
			if( empty($dob))
            {
                
                $JSON_ARR= array(
    					'response'=>"Dob is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($gender))
            {
                
                $JSON_ARR= array(
    					'response'=>"Gender is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_name))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_code))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country code is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
			

			
			 if(!empty($email))
             {
                	$sql = "SELECT *  FROM user where email ='".trim($email)."'";
    				$res = $this->db->query($sql);
    				if ($res->num_rows() > 0) 
    				{
    					$JSON_ARR= array(
    							'response'=>"This email is already registered",
    							'success'=>0
    							);
    					print json_encode($JSON_ARR);
    					die();
    			    }
                }
    		
    		$array_dob= explode('-',$dob);
			$date_of_birth=$array_dob[2]."-".$array_dob[1]."-".$array_dob[0];
		
			
			if(	!empty($mobile))  
			{

				$where_as=" ( (SUBSTRING(mobile,-10)='".trim($mobile)."') or mobile='".$mobile."')";
				$sql = "SELECT *  FROM user where $where_as ";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					$JSON_ARR= array(
							'response'=>"phone number is already registered",
							'user_id'=>intval($row[0]['user_id']),
							'success'=>0
							);
					print json_encode($JSON_ARR);
				}
				

				else
				{
					$otps=$this->crud_model->generateNumericOTP(6);
					$data=array(
					"email"=>$email,
					"name"=>$name,
					"otp"=>$otps,
					"country_code"=>$country_code,
					"mobile"=>$mobile,
					"gender"=>$gender,
					"country"=>$country_name,
					"dob"=>$date_of_birth,
				    "device_token"=>$device_token,
					"device_type"=>$device_type,
					"created_date"=>date('Y-m-d')
					//"device_token"=>$this->input->post('device_token')
					);
                        
                        
                        require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
                		$message = 'Happy Watch 99 code: '.$data['otp'].'. Valid for 5 minutes.';
                		$phone = $country_code.$mobile;
                
                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			///print_r($result->MessageId);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		} 
                                        
                        
                        
                        
                        
                        ////////////////////////////////////////////////////
                        /*
                        $from = '16268668783';
                        $to = $data['country_code'].$data['mobile'];
                        
                        $to = $country_code.$mobile;
                        
                        $message = array(
                            'text' => 'Happy Watch 99 Code : '.$data['otp'].'Valid For 5 minutes'
                        );
                         $response = $this->nexmo->send_message($from, $to, $message);
                        
                        */
                        /////////////////////////////////////////////////////
                        
                        
						$signup_id= $this->Api_model->signup($data);
					////////////////////////////////////////////////////
					
					if($signup_id){
							
							$new_message = "
								<html>
                                <head>
                                <title>Verification Code</title>
                                </head>
                                <body>
                                <h2>Thank you for registration with Happy Watch 99</h2>
                                <p>You have received your verification code</p>
                                <p>Your Account:</p>
                                <p>Name: ".$name."</p>
                                <p>Email: ".$email."</p>
                                <p>mobile number: ".$country_code."-".$mobile."</p>
                                <p>your verification code is </p>
                                <p>".$otps."</p>
                                </body>
                                </html>
							";


                                


							$email_msg	=	$new_message;
							$email_sub	=	"Activate your happy watch 99";
							$email_to	=	$_REQUEST['email'];
							$this->Email_model->do_email($email_msg , $email_sub , $email_to);
							
						
					}
					
						
				//////////////////////////////////////////////////////////////	
						if($signup_id)
						{
							$JSON_ARR = array(
									'response'=>"Your account is register successfully",
									    'user_id'=>intval($signup_id),
									    'otp'=>$otps,
										"email"=>$email,
					                    "name"=>$name,
					                    "country_code"=>$country_code,
					                    "mobile"=>$mobile,
					                    "gender"=>$gender,
					                    "country_name"=>$country_name,
					                    "dob"=>$date_of_birth,
					                    'success'=>1
									);

						print json_encode($JSON_ARR);
						}
				
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	




    public function mobile_signup_flutter()	
	{	
			$mobile=	$this->input->post('mobile');
			$password=$this->input->post('password');
			
			$dob=trim($this->input->post('dob'));
			$name=trim($this->input->post('name'));
			$gender=$this->input->post('gender');
			$country_name=$this->input->post('country_name');
			$country_code=$this->input->post('country_code');
			
		
			//$email=$this->input->post('email');
			
			
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');

			
			
			if( empty($mobile))
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
            if( strlen($mobile)>10)
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no not exists ten digit",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			if( empty($name))
            {
                
                $JSON_ARR = array(
    					'response'=>"Name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
			
			
			
			
			if( empty($dob))
            {
                
                $JSON_ARR= array(
    					'response'=>"Dob is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($gender))
            {
                
                $JSON_ARR= array(
    					'response'=>"Gender is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_name))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($country_code))
            {
                
                $JSON_ARR= array(
    					'response'=>"Country code is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
			
    		
    		$array_dob= explode('-',$dob);
			$date_of_birth=$array_dob[2]."-".$array_dob[1]."-".$array_dob[0];
		
			
			if(	!empty($mobile))  
			{

				$where_as=" ( (SUBSTRING(mobile,-10)='".trim($mobile)."') or mobile='".$mobile."')";
				$sql = "SELECT *  FROM user where $where_as ";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					$JSON_ARR= array(
							'response'=>"phone number is already registered",
							'user_id'=>intval($row[0]['user_id']),
							 'otp'=>'',
							'success'=>0
							);
					print json_encode($JSON_ARR);
				}
				

				else
				{
					$otps=$this->crud_model->generateNumericOTP(6);
					$data=array(
					
					"name"=>$name,
					"otp"=>$otps,
					"country_code"=>$country_code,
					"mobile"=>$mobile,
					"gender"=>$gender,
					"country"=>$country_name,
					
				    "device_token"=>$device_token,
					"device_type"=>$device_type,
					"created_date"=>date('Y-m-d')
					);
                        
                        
						
                        require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
                		$message = 'Happy Watch 99 code: '.$data['otp'].'. Valid for 5 minutes.';
                		$phone = $country_code.$mobile;
                
                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			///print_r($result->MessageId);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		} 
                                        
                    
                        
                        
                        
                        ////////////////////////////////////////////////////
                        /*
                        $from = '16268668783';
                        $to = $data['country_code'].$data['mobile'];
                        
                        $to = $country_code.$mobile;
                        
                        $message = array(
                            'text' => 'Happy Watch 99 Code : '.$data['otp'].'Valid For 5 minutes'
                        );
                         $response = $this->nexmo->send_message($from, $to, $message);
                        
                        */
                        /////////////////////////////////////////////////////
                        
                        
						$signup_id= $this->Api_model->signup($data);
						

						if($signup_id)
						{
							$JSON_ARR = array(
									'response'=>"Your account is register successfully",
									    'user_id'=>intval($signup_id),
									    'otp'=>$otps,
					                    'success'=>1
									);

						print json_encode($JSON_ARR);
						}
				
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	





	
	/*
	public function mobile_signup_23_4_20()	
	{	
			if(	isset($_REQUEST['mobile']) )  
			{
				$mobile=$_REQUEST['mobile'];
				//$sql = "SELECT *  FROM user where 	mobile ='".trim($mobile)."'";
				
				$where_as=" ( (SUBSTRING(mobile,-10)='".trim($mobile)."') or mobile='".$mobile."')";
				$sql = "SELECT *  FROM user where $where_as ";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					$JSON_ARR[] = array(
							'response'=>"you have signed in successfully...",							'success'=>1,
							'user_id'=>intval($row[0]['user_id'])
							);
					print json_encode($JSON_ARR);
				}
				else
				{
					$data=array(
					"mobile"=>trim($mobile)
					);
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>intval($signup_id),
									'success'=>1
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	
	
*/	
	
	
	
	public function mobile_signinwithpassword_old()	
	{		
			$mobile=	$this->input->post('mobile');
			$country_code=$this->input->post('country_code');
		
			$password=$this->input->post('password');
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');
			
			
		
			if( empty($password))
            {
                $JSON_ARR= array(
    					'response'=>"Password is required",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
            }
		
			if( empty($mobile))
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no is required",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
            if( strlen($mobile)>10)
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile doesn't exist ten digit",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
		

			if(	isset($mobile)  )
			{	
				$result_signin= $this->Api_model->user_signinwithmobile();
				//$otps=$this->crud_model->generateNumericOTP(6);
				if(!empty($result_signin))
				{
					$updateData=array(
					"device_token"=>$device_token,
					"device_type"=>$device_type,
					"otp"=>$otps    
					);
					
					$this->db->where("user_id",$result_signin[0]['user_id']);
					$this->db->update("user",$updateData);  

                    $country_name=$result_signin[0]['country'];
					$broadcast_row= $this->Api_model->get_channel_details($result_signin[0]['user_id']);
					
					//$channel_details_jsoan=json_decode( $broadcast_row[0]['channel_details_jsoan']);
					
					
					$array_channel_details= array(
        				'show_dist_protocol'=>$broadcast_row[0]['show_dist_protocol'],
                        'show_domain'=>$broadcast_row[0]['show_domain'],
                        'show_application'=>$broadcast_row[0]['show_application'],
                        'show_master_stream'=>$broadcast_row[0]['show_master_stream'],
                        'show_pub_protocol'=>$broadcast_row[0]['show_pub_protocol'],
                        'show_primary'=>$broadcast_row[0]['show_primary'],
                        'show_backup'=>$broadcast_row[0]['show_backup'],
                        'show_stream_name'=>$broadcast_row[0]['show_stream_name'],
                        'show_password'=>$broadcast_row[0]['show_password']	
				
				        );
	            
					
				
					
					 require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
		
                		$message = 'Happy Watch 99 code: '.$otps.'. Valid for 5 minutes.';
                		
						if(!empty($country_code))
						{
						$phone = $country_code.$result_signin[0]['mobile'];
						}	
						else
						{	
						$phone = $result_signin[0]['country_code'].$result_signin[0]['mobile'];
						}
							
						

                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			///print_r($result->MessageId);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		} 
                                        
                        
                        
					
					
					
					
					$JSON_ARR=array(
							
							'user_id'=>intval($result_signin[0]['user_id']),
							'useremail'=>$result_signin[0]['email'],
							'country_code'=>$result_signin[0]['country_code'],
							'mobile'=>$result_signin[0]['mobile'],
				            'dob'=>  $result_signin[0]['dob'],
				            'name'=>$result_signin[0]['name'],
				            'gender'=>$result_signin[0]['gender'],
				            'country_name'=>$country_name,
				            'profile_image'=>$this->crud_model->get_profile_image_url(intval($result_signin[0]['user_id'])),
				            
							'is_login'=>"YES",
							
						'channel_id'=>$broadcast_row[0]['channel_id'],		
						'channel_name'=>$broadcast_row[0]['channel_name'],
                	    'channel_detrail'=>$array_channel_details
                	    
							);		
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR=array(
							'error'=>"This mobile no is incorrect ",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'is_login'=>"NO"
									);
					print json_encode($JSON_ARR);
			}

	}
	


	
	
	
	 
	public function mobile_signin()	
	{		
			
			//echo "========".$this->input->post('mobile');
		
			$mobile=	$this->input->post('mobile');
			$country_code=$this->input->post('country_code');
			
			$device_token=$this->input->post('device_token');
			$device_type=$this->input->post('device_type');
			
			
			if( empty($mobile))
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile no is required",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
            if( strlen($mobile)>10)
            {
                
                $JSON_ARR= array(
    					'response'=>"Mobile doesn't exist ten digit",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            
		

			if(	isset($mobile)  )
			{	
			
				//ratnesh kumar
				$result_signin= $this->Api_model->user_signin('mobile');
				$otps=$this->crud_model->generateNumericOTP(6);
				$tmp_email="";
				$tmp_name="";
				if(!empty($result_signin))
				{
				
				$sql = "SELECT *  FROM user where 1 and mobile='".$mobile."'";
				$res = $this->db->query($sql);
				if ($res->num_rows()> 0) 
				{
					$row = $res->result_array();
					$tmp_email=$row[0]['email'];
					
					$tmp_name=$row[0]['name'];
					//print_r($row);
				
					
					
					$new_message = "
								<html>
                                <head>
                                <title>Verification Code</title>
                                </head>
                                <body>
                                <h2>Received your verification code</h2>
                                <p>You have received your verification code</p>
                                <p>Your Account:</p>
                                <p>Name: ".$tmp_name."</p>
                                <p>Email: ".$tmp_email."</p>
                                <p>mobile number: ".$country_code."-".$mobile."</p>
                                <p>your verification code is </p>
                                <p>".$otps."</p>
                                </body>
                                </html>
							";

							$email_msg	=	$new_message;
							$email_sub	=	"Activate your happy watch 99";
							$email_to	=	$tmp_email;
							$this->Email_model->do_email($email_msg , $email_sub , $email_to);
				}
							
				}
			
				
				if(!empty($result_signin))
				{
					$updateData=array(
					"device_token"=>$device_token,
					"device_type"=>$device_type,
					"otp"=>$otps    
					);
					
					$this->db->where("user_id",$result_signin[0]['user_id']);
					$this->db->update("user",$updateData);  

                    $country_name=$result_signin[0]['country'];
					$broadcast_row= $this->Api_model->get_channel_details($result_signin[0]['user_id']);
					
					//$channel_details_jsoan=json_decode( $broadcast_row[0]['channel_details_jsoan']);
					
					
					$array_channel_details= array(
        				'show_dist_protocol'=>$broadcast_row[0]['show_dist_protocol'],
                        'show_domain'=>$broadcast_row[0]['show_domain'],
                        'show_application'=>$broadcast_row[0]['show_application'],
                        'show_master_stream'=>$broadcast_row[0]['show_master_stream'],
                        'show_pub_protocol'=>$broadcast_row[0]['show_pub_protocol'],
                        'show_primary'=>$broadcast_row[0]['show_primary'],
                        'show_backup'=>$broadcast_row[0]['show_backup'],
                        'show_stream_name'=>$broadcast_row[0]['show_stream_name'],
                        'show_password'=>$broadcast_row[0]['show_password']	
				
				        );
	            
					
				
					
					 require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
		
                		$message = 'Happy Watch 99 code: '.$otps.'. Valid for 5 minutes.';
                		
						if(!empty($country_code))
						{
						$phone = $country_code.$result_signin[0]['mobile'];
						}	
						else
						{	
						$phone = $result_signin[0]['country_code'].$result_signin[0]['mobile'];
						}
							
						//	echo "=====================".$phone;
					/////exit;
					

                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			///print_r($result->MessageId);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		} 
                                        
                        
                        
					
					
					
					
					$JSON_ARR=array(
							
							'user_id'=>intval($result_signin[0]['user_id']),
							'otp'=>$otps,
							'useremail'=>$result_signin[0]['email'],
							'country_code'=>$result_signin[0]['country_code'],
							'mobile'=>$result_signin[0]['mobile'],
				            'dob'=>  $result_signin[0]['dob'],
				            'name'=>$result_signin[0]['name'],
				            'gender'=>$result_signin[0]['gender'],
				            'country_name'=>$country_name,
				            'profile_image'=>$this->crud_model->get_profile_image_url(intval($result_signin[0]['user_id'])),
				            
							'is_login'=>"YES",
							
						'channel_id'=>$broadcast_row[0]['channel_id'],		
						'channel_name'=>$broadcast_row[0]['channel_name'],
                	    'channel_detrail'=>$array_channel_details
                	    
							);		
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR=array(
							'error'=>"This mobile no is incorrect ",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'is_login'=>"NO"
									);
					print json_encode($JSON_ARR);
			}

	}
	
	
		
	public function SignupSigninWithFacebook()	
	{	
        $email=$this->input->post('email');
	    $profile_image=$this->input->post('profile_image');
	    $first_name=$this->input->post('first_name');
	    $last_name=$this->input->post('last_name');
	    $full_name=$first_name." ".$last_name;
        $facebookid=$this->input->post('facebookid');
        

		if( empty($facebookid))
            {
                
                $JSON_ARR= array(
    					'response'=>"Facebookid is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
        
        if( empty($first_name))
            {
                
                $JSON_ARR= array(
    					'response'=>"First name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }

			if(	isset($facebookid) )  
			{
				
				$sql = "SELECT *  FROM user where 	facebook_id ='".trim($facebookid)."' and loginwith='FACEBOOK'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					
					$row = $res->result_array();
					$user_id=intval($row[0]['user_id']);
					
					$tmp_profile_img=($profile_image=="")  ? "" : $profile_image;
					
					$tmp_name=($full_name=="")  ? "" : trim($full_name);
					
					$data_update=array(
					"loginwith"=>'FACEBOOK',
					"name"=>$tmp_name,
					"status"=>1,	
					"profile_image"=>$tmp_profile_img
					);
			

					$signup_id= $this->Api_model->edit_profile($data_update,$user_id);
					
					
					
					$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed in successfully...",
									'user_id'=>intval($row[0]['user_id']),
									"profile_image"=>$tmp_profile_img,
									"name"=>$tmp_name,
									"email"=>$row[0]['email'],
									'success'=>1
									);
				    print json_encode($JSON_ARR);
					exit;
				}
				else
				{
					$data=array(
					"facebook_id"=>trim($facebookid),
					"loginwith"=>'FACEBOOK',
					"name"=>($full_name=="")  ? "" : trim($full_name),
					"profile_image"=>($profile_image=="")  ? "" : $profile_image,
					"email"=>($email=="")  ? "" : $email,
					"status"=>1,	
					"created_date"=>date('Y-m-d')
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>intval($signup_id),
									"profile_image"=>$profile_image,
									"name"=>($full_name=="")  ? "" : trim($full_name),
									"email"=>$email,
									'success'=>1
									);

							print json_encode($JSON_ARR);
								}
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	
	
	public function SignupSigninWithApple()	
	{	
        $email=$this->input->post('email');
	    //$profile_image=$this->input->post('profile_image');
	    $first_name=$this->input->post('first_name');
	    $last_name=$this->input->post('last_name');
	    $full_name=$first_name." ".$last_name;
        $apple_id=$this->input->post('apple_id');
        if( empty($apple_id))
            {
                
                $JSON_ARR= array(
    					'response'=>"Apple id is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
        
        if( empty($first_name))
            {
                
                $JSON_ARR= array(
    					'response'=>"First name is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }

			if(	isset($apple_id) )  
			{
				
				$sql = "SELECT *  FROM user where 	apple_id ='".trim($apple_id)."' and loginwith='APPLE'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					
					$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed in successfully...",
									'user_id'=>intval($row[0]['user_id']),
									"name"=>$row[0]['name'],
									"email"=>$row[0]['email'],
									'success'=>1
									);
				    print json_encode($JSON_ARR);
					exit;
				}
				else
				{
					$data=array(
					"apple_id"=>trim($apple_id),
					"loginwith"=>'APPLE',
					"name"=>($full_name=="")  ? "" : trim($full_name),
					"email"=>($email=="")  ? "" : $email,
					"created_date"=>date('Y-m-d')
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>intval($signup_id),
									"name"=>($full_name=="")  ? "" : trim($full_name),
									"email"=>$email,
									'success'=>1
									);

							print json_encode($JSON_ARR);
								}
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data",
									'success'=>0
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	
	
	
	
	
	
	public function get_series($user_id=0)
	{
		
	/*
		if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
 
 
        if(!empty($user_id))
        {
          $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
        
        }
		
	*/	
		
		
		$result_cat=$this->Api_model->GetAll_Series_Genre();
		$genre_content=[];
		foreach($result_cat as $cat_row)
		{
			$result_series= $this->Api_model->GetAll_SeriesOfGenre($cat_row['genre_id']);
			$series_content=[];
			foreach($result_series as $row_series)
			{
				$series_content[]= array(
				"series_id"=>$row_series['series_id'],
				"title"=>$row_series['title'],
				"sub_title"=>$row_series['sub_title'],
				"audio_track"=>$row_series['audio_track'],

				"description_short"=>$row_series['description_short'],
				"description_long"=>$row_series['description_long'],
				"year"=>$row_series['year'],
				"rating"=>$row_series['rating'],
				"series_poster"=>base_url().'assets/global/series_poster/'.$row_series['series_id'].".jpg",
				"series_thumb"=>base_url().'assets/global/series_thumb/'.$row_series['series_id'].".jpg"
				);
			}
			//////////continue_watching
			//'new_released'=>$new_released,
			
			
			$genre_content[]= array(
			"genre_id" => $cat_row['genre_id'],
			"name" => $cat_row['name'],
			"series_content"=>$series_content
			);	
		
		}
		
		$continue_watching_episode=$this->get_watch_later_episode($user_id);
		$new_released=$this->get_new_released_series();
		//print_r($continue_watching_episode);
		//exit;
		$ALL_JSON_ARR = array(
				"launch"=>'series',
				'continue_watching'=>$continue_watching_episode,
				'new_released'=>$new_released,
				'contents'=>$genre_content
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	
	public function GetSeason()
	{
		$series_id= $this->uri->segment(3);
		if(empty($series_id) )
		{
			$JSON_ARR[] = array(
			'response'=>"Series id not found"
			);
				print json_encode($JSON_ARR);
				die();
		}	
		
		$result_season= $this->Api_model->GetAll_SeasonOfSeries($series_id);
		
		$season_content=[];
		foreach($result_season as $row_season )
		{
			$result_episode= $this->Api_model->GetAll_episodeOfSeason($row_season['season_id']);
			$episode_content=[];
			foreach($result_episode as $row_episode)
			{
				$result_ad_time= $this->Api_model->Get_episodeAdvertisement_Time($row_episode['episode_id']);

				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
				
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "episode_id" => $row_episode['episode_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url'],
				 "advertisement_time"=> $adtime_content 
				);	
			}
			
			$season_content[]= array(
			"season_id" => $row_season['season_id'],
			"name" => $row_season['name'],
			"episode_content"=>$episode_content
			);	
		

		}

		$ALL_JSON_ARR = array(
				"launch"=>'Season',
				'contents'=>$season_content
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	
	
public function GetSeasonnew()
	{
		$series_id= $this->uri->segment(3);
		if(empty($series_id) )
		{
			$JSON_ARR[] = array(
			'response'=>"Series id not found"
			);
				print json_encode($JSON_ARR);
				die();
		}	
		
		$result_season= $this->Api_model->GetAll_SeasonOfSeries($series_id);
		$season_content=[];
		foreach($result_season as $row_season )
		{
			$result_episode= $this->Api_model->GetAll_episodeOfSeason($row_season['season_id']);
			$episode_content=[];
			foreach($result_episode as $row_episode)
			{
				 
				 $view_count= $this->Api_model->total_view_season($row_episode['episode_id']);
				$increease_view_count= $this->Api_model->increase_episode_view($row_episode['episode_id']);	
		
	    		$view_count=$view_count+$increease_view_count;
				

				 
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "episode_id" => $row_episode['episode_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url'],
				 "view_count"=>number_format($view_count)
				 
				);	
			}
			
			$season_content[]= array(
			"season_id" => $row_season['season_id'],
			"name" => $row_season['name'],
			"episode_content"=>$episode_content
			);	
		

		}
        

        	$result_ad_time= $this->Api_model->Get_SeriesAdv_Time($row_episode['episode_id']);
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "is_preroll"=>$row_ad_time['is_preroll'],
					 "add_time"=>$row_ad_time['add_time']*60
				      );	
    				    
    				}
				}
        
        
        
		$ALL_JSON_ARR = array(
				"launch"=>'Season',
				"advertisement_time"=> $adtime_content ,
				'contents'=>$season_content
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	
	
	
	///////////////////////////////////////////
	
	public function get_watch_later_episode($user_id=0)
	{
		$result_episode= $this->Api_model->GetAll_watch_later_episode($user_id);

		$episode_content=[];
		foreach($result_episode as $row_episode )
		{
				$result_ad_time= $this->Api_model->Get_episodeAdvertisement_Time($row_episode['episode_id']);
				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "episode_id" => $row_episode['episode_id'],
				 'series_id'=>$row_episode['series_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url'],
				 "advertisement_time"=> $adtime_content 
				);	
		}
		return $episode_content;
	}
	
	
	public function get_new_released_series()
	{
			$result_series= $this->Api_model->new_released_series();
			$series_content=[];
			foreach($result_series as $row_series)
			{
				$series_content[]= array(
				"series_id"=>$row_series['series_id'],
				"title"=>$row_series['title'],
				"description_short"=>$row_series['description_short'],
				"description_long"=>$row_series['description_long'],
				"year"=>$row_series['year'],
				"rating"=>$row_series['rating'],
				"series_poster"=>base_url().'assets/global/series_poster/'.$row_series['series_id'].".jpg",
				"series_thumb"=>base_url().'assets/global/series_thumb/'.$row_series['series_id'].".jpg"
				);
			}
			
			return $series_content;
		
	}
	
	////////////////////////////////////////////
	public function GetSubscriptionDetail()
	{
		$user_id=$this->uri->segment(3);
		if(!empty($user_id))
		{		
			$result_episode= $this->Api_model->GetUser_Subscription_Detail($user_id);
			if(!empty($result_episode))
			{	
				foreach($result_episode as $row)
				{
					$subscription_content= array(
					 "subscription_id" => $row['subscription_id'],
					 "plan_id" => $row['plan_id'],	
					 "user_id" => $row['user_id'],	
					 "name" => $row['name'],	
					 "email"=> $row['email'],	
					 "price_amount" =>$row['price_amount'],	 
					 "paid_amount" => $row['paid_amount'],	 
					 "timestamp_from" => date('d/m/Y H:i:s', $row['timestamp_from']),	  
					 "timestamp_to" => date('d/m/Y H:i:s', $row['timestamp_to']) ,	  
					 "payment_method" =>$row['payment_method'],	   
					 "payment_details" =>$row['payment_details'],	    
					 "payment_timestamp" =>date('d/m/Y H:i:s', $row['payment_timestamp']) 
					);		
				}
				print json_encode($subscription_content);
			}
			else
			{
				$JSON_ARR = array(
				'response'=>"Subscription not found"
				);
				print json_encode($JSON_ARR);
			}		
			
			
		}	
		else
		{
			$JSON_ARR = array(
			'response'=>"User Id not found"
			);
			print json_encode($JSON_ARR);
		}	
			
			
			
			
			
			
	}
	
	
	
	public function search_old()
	{
		$search_key=urldecode($this->uri->segment(3));
		
		if(!empty($search_key))
		{	
				$movies		=	$this->crud_model->get_search_result('movie' , $search_key);
				$series		=	$this->crud_model->get_search_result('series', $search_key);
				//$live		=	$this->crud_model->get_search_result('live', $search_key);
				
				
				$live=	$this->crud_model->get_search_result_live( $search_key);
				$live_songs=	$this->crud_model->get_search_result_live_songs( $search_key);
				
				$u_watch=	$this->crud_model->get_search_result_uwatch( $search_key);
				$user_result= $this->Api_model->search_user($search_key);
				
				
				$fan_page_result= $this->Api_model->search_fan_page($search_key);
				
				$channel_page_result= $this->Api_model->search_channel_page($search_key);
				
				$movie_content=[];
				$result_cat="";
				foreach($movies as $row_movie)
				{
				
					$result_cat= $this->Api_model->GetGenre_details($row_movie['genre_id']);
					
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_movie['movie_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
				
					$ext = pathinfo($row_movie['url'], PATHINFO_EXTENSION);
					$movie_content[]= array(
					 "id" => $row_movie['movie_id'],
					 "title"=>$row_movie['title'],
					 "category_id"=>$result_cat[0]['genre_id'],
					 "category_name"=>$result_cat[0]['name'],
					 "description_short"=>$row_movie['description_short'],
					 "description_long"=>$row_movie['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_movie['url'],
					 "movie_poster"=>base_url().'assets/global/movie_poster/'.$row_movie['movie_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/movie_thumb/'.$row_movie['movie_id'].".jpg",
					 "rating"=>$row_movie['rating'],
					 "advertisement_time"=> $adtime_content
				   );	
				}
				$series_content=[];
				$result_cat="";
				foreach($series as $row_series)
				{
					$result_cat= $this->Api_model->GetGenre_details($row_series['genre_id']);
					$series_content[]= array(
						"series_id"=>$row_series['series_id'],
						"title"=>$row_series['title'],
						"category_id"=>$result_cat[0]['genre_id'],
						"category_name"=>$result_cat[0]['name'],
						"description_short"=>$row_series['description_short'],
						"description_long"=>$row_series['description_long'],
						"year"=>$row_series['year'],
						"rating"=>$row_series['rating'],
						"series_poster"=>base_url().'assets/global/series_poster/'.$row_series['series_id'].".jpg",
						"series_thumb"=>base_url().'assets/global/series_thumb/'.$row_series['series_id'].".jpg"
						);
				}
		
		
		        
		
				$live_content=[];
				$result_cat="";
				foreach($live as $row_live)
				{
				
					$result_cat= $this->Api_model->GetGenre_details($row_live['genre_id']);
					
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_live['live_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
				
					$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
					$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "category_id"=>$result_cat[0]['genre_id'],
					 "category_name"=>$result_cat[0]['name'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "live_url"=>$row_live['url'],
					 "live_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "live_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "rating"=>$row_live['rating'],
					 "advertisement_time"=> $adtime_content
				   );	
				}
				

            $live_songs_content=[];
				$result_cat="";
				foreach($live_songs as $row_live)
				{
				
					$result_cat= $this->Api_model->GetGenre_details($row_live['genre_id']);
					
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_live['live_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
				
					
					$live_songs_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "category_id"=>$result_cat[0]['genre_id'],
					 "category_name"=>$result_cat[0]['name'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					
					 "live_poster"=>$this->crud_model->get_thumb_url('live' , $row_live['live_id']),
					 "live_thumb"=>$this->crud_model->get_thumb_url('live' , $row_live['live_id']),
					 "rating"=>$row_live['rating'],
					 "advertisement_time"=> $adtime_content
				   );	
				}

        
                $uwatch_content=[];
				$result_cat="";
				foreach($u_watch as $row_uwatch)
				{
				
					$result_cat= $this->Api_model->GetGenre_details($row_uwatch['genre_id']);
					
					$result_ad_time= $this->Api_model->Get_Advertisement_Time($row_uwatch['u_watch_id']);

					$adtime_content=[];
					if(!empty($result_ad_time))
					{
						foreach($result_ad_time as $row_ad_time )
						{
						  $adtime_content[]= array(
						 "ad_time_id" => $row_ad_time['id'],
						 "videos_id"=>$row_ad_time['videos_id'],
						 "add_time"=>$row_ad_time['add_time']
						  );	
							
						}
					}
				
						$video_url="https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/Content_Provider_folder/".$row_uwatch['url']."/playlist.m3u8";
					
					$uwatch_content[]= array(
					 "id" => $row_uwatch['u_watch_id'],
					 "title"=>$row_uwatch['title'],
					 "category_id"=>$result_cat[0]['genre_id'],
					 "category_name"=>$result_cat[0]['name'],
					
					 "description_long"=>$row_uwatch['description_long'],

					 "live_thumb"=>$this->crud_model->get_uwatch_thumb_url( $row_uwatch['u_watch_thumb']),
					 
					 "url"=>$video_url,
					 "advertisement_time"=> $adtime_content
				   );	
				}
            
                $user_contents=[];
            	foreach($user_result as $user_details )		     
		        {		     
		        $user_contents[]=array(
					'user_id'=>intval($user_details['user_id']),
					'user'=>$user_details['name'],
					'useremail'=>$user_details['email'],
					'country_code'=>$user_details['country_code'],
					'mobile'=>$user_details['mobile'],
		            'dob'=>  $user_details['dob'],
		            'name'=>$user_details['name'],
		            'gender'=>$user_details['gender'],
		            
		            'profile_image'=>$this->crud_model->get_profile_image_url(intval($user_details[0]['user_id']))
					);
					
				
		        }		    
            

            $fan_page_contents=[];
            	foreach($fan_page_result as $fanpage_details )		     
		        {		     
		        $fan_page_contents[]=array(
					'fan_page_id'=>$fanpage_details['id'],
					'name'=>$fanpage_details['name'],
					'user_id'=>$fanpage_details['user_id']
					
					);
					
				
		        }		    
				
		
		$channel_page_contents=[];
            	foreach($channel_page_result as $channelpage_details )		     
		        {		     
		        $channel_page_contents[]=array(
					'fan_page_id'=>$channelpage_details['id'],
					'name'=>$channelpage_details['name'],
					'user_id'=>$channelpage_details['user_id']
					
					);
					
				
		        }		    
		
		
			$search_content= array(
			"search"=>'Search' ,
			"movie"=> $movie_content,
			"series"=> $series_content,
			"live"=> $live_content,
			"songs"=>$live_songs_content,
			"u_watch"=>$uwatch_content,
			"user"=>$user_contents,
			"fan_page"=>$fan_page_contents,
			"channel_page"=>$channel_page_contents
			);	
			print json_encode($search_content);
		
		}
		else
		{
			$JSON_ARR = array(
			'response'=>"Search string not found"
			);
			print json_encode($JSON_ARR);
			
		}		
	


	
	}
	
	
	
	public function advertisement()
	{
	    $result_adv= $this->Api_model->GetAdvertisement();
	    
	    //print_r($result_adv);
	    
	    
	   if(!empty($result_adv))
	   {
	    $content= array(
			"ChannelLaunch"=>"on",
			"adsURL1"=>$result_adv[0]['adsURL1'],
			"adsURL2"=>$result_adv[0]['adsURL2'],
			"adsURL3"=>$result_adv[0]['adsURL3'],
			"adsURL4"=>$result_adv[0]['adsURL4'],
			"adsURL5"=>$result_adv[0]['adsURL5'],
			"adsURL6"=>$result_adv[0]['adsURL6'],
			"add_rander_time"=>420
			);	
			print json_encode($content);
	   }
	   else
	   {
	    	$JSON_ARR = array(
			'response'=>"advertisement string not found"
			);
			print json_encode($JSON_ARR);   
	       
	   }
	    
	}
	
	
	
	public function changepassword_old()
	{
	
	
	  $current_password=$this->input->post('current_password');
	  $new_password=$this->input->post('new_password');
	  $user_id=$this->input->post('user_id');
	  
	  //print_r($_REQUEST);
	  ////echo "=====".$current_password;
	  
	  if (!empty($current_password) and !empty($new_password) )
		{
			$user_details=	$this->db->get_where('user', array('user_id'=>$user_id))->row();
			//$old_password_encrypted				=	$this->crud_model->get_current_user_detail()->password;
			$old_password_encrypted=$user_details->password;
			

			$old_password_submitted_encrypted	=	sha1($current_password);
			$new_password						=	$this->input->post('new_password');
			$new_password_encrypted				=	sha1($this->input->post('new_password'));


//echo "*********".$old_password_submitted_encrypted;
			// NEW PASSWORD MUST BE 6 CHARACTER LONG
			if (strlen($new_password) <6)
			{
                $JSON_ARR = array(
    			'response'=>"Current Password Given Wrong Or New Password Must Be At Least 6 Character Long. Please Try Again."
    			);
    			print json_encode($JSON_ARR);      
    			exit;	
				//$this->session->set_flashdata('status', 'password_change_failed');
				//redirect(base_url().'index.php?browse/passwordchange' , 'refresh');
			}

			// CORRECT OLD PASSWORD NEEDED TO CHANGE PASSWORD
			if ($old_password_encrypted 		==	$old_password_submitted_encrypted)
			{
				
				$this->db->update('user', array('password'=>$new_password_encrypted), array('user_id'=>$user_id));
			
	            $email_to=$user_details->email;
				// Sending user the notification email with new password
    			$email_msg	=	"Your new password is : ".$new_password;
    			$email_sub	=	"Password reset request";
    			//echo "=============".$email_to;
    		    $this->email_model->Do_email($email_msg , $email_sub , $email_to);
    			 $JSON_ARR = array(
    			'response'=>"your password has been changed successfully,please check email",
    			'change_password'=>1
    			);
    			print json_encode($JSON_ARR);	
				
				
				//your password has been changed successfully
				
				//$this->session->set_flashdata('status', 'password_changed');
				//redirect(base_url().'index.php?browse/youraccount' , 'refresh');
			}
			else
			{

			$JSON_ARR = array(
			'response'=>"your password has been not changed successfully",
			'change_password'=>0
			);
			print json_encode($JSON_ARR);  
			
				//$this->session->set_flashdata('status', 'password_change_failed');
				//redirect(base_url().'index.php?browse/passwordchange' , 'refresh');
			}
  
	    
	}
	else
	{
	    
	    	$JSON_ARR = array(
			'response'=>"Current Password and New Password is required"
			);
			print json_encode($JSON_ARR);  
	    
	}
	
	
	
	


	
	}
	
	
	public function all_genre()
	{
	    
	    $result= $this->Api_model->getgenre();
    	if(!empty($result))
			{
				$content=[];
				foreach($result as $row )
				{
				  $content[]= array(
				 "genre_id" => $row['genre_id'],
				 "name"=>$row['name']
				  );	
					
				}
			
			/*
			    $JSON_ARR[] = array(
				
				'contents'=>$movie_content
				);    
			*/    
			    
				print json_encode($content); 
			    
			    
			}
			
			
			

	    
	}

	
	
	
	
	public function all_category()
	{
	    $result= $this->Api_model->getcategory();
    	if(!empty($result))
			{
				$content=[];
				foreach($result as $row )
				{
				  $content[]= array(
				 "category_id" => $row['cat_id'],
				 "name"=>$row['name']
				  );	
					
				}
			
				print json_encode($content); 
			}
	}

	
	
	
	
/*
	public function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = $this->utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}
*/

	
	   
	   
	//public function get_gallery()
	//{
			//$username=$this->input->post('username');
			//$username='ratnesh@yahoo.com';
			//if(!empty($username))
			//{	
			//$user_details=$this->Api_model->get_userID($username);
			//echo "=====".$user_details->users_id;
			//$user_id=$user_details->users_id;
			//$user_details=$this->Api_model->get_gallery($movie_id);
			/*foreach( $user_details as $row)
			{
			print_r($row);
			
			if(!empty($row['image']))
			{	
					$content[]= array(
					 "id" => $row['id'],
					 "type" => 'Image',
					 "title" =>$row['image'],
					 "path"=>base_url().'uploads/gallery_image/'.$row['image'],
					 "duration"=>$row['video_duration']
				   );
			}		
			if(!empty($row['video']))
			{	
					$content[]= array(
					 "id" => $row['id'],
					 "type" => 'Video',
					 "title" => $row['video'],
					 "path"=> base_url().'uploads/gallery_video/'. $row['video'],
					 "duration"=>$row['video_duration']
					 
				   );
					
			}
			}
			
			$JSON_ARR = array(
				'media_details'=>$content

				);

				print json_encode($JSON_ARR);

			
			//}
		
	}*/
	
	
	
	
	/**public function login()
	{
		$data['notif'] = $this->auth_model->Authentification();
		if(empty($data['notif']))
		{
			$content=array('error'=>'Success','login'=>'1','user_id'=>$this->session->userdata['logged_in']['users_id']);	
		}
		else
		{
			
			//$notif['message'] = 'Username or password incorrect !';
            //$notif['type'] = 'danger';
			
			$content=array('error'=>$data['notif']['message'],'login'=>'0');
		}	
		$JSON_ARR = array(
				'valid_login'=>$content
				);

				print json_encode($JSON_ARR);
	}
   
   public function get_section($user_id=0,$schedule="")
   {
	   if ( $user_id<=0) 
	   {
           $JSON_ARR = array(
				'response'=>"Please login first"
				);

		    print json_encode($JSON_ARR);
       }
	   else
	   {
		  $content=array();
		  $section_data= $this->Section_model->get_section_app($user_id,$schedule); 
		  
		  //echo $this->db->last_query();
		  
		  foreach($section_data as $row)
		  {
			  $input = array("Video", "Image");
			  $arr_gallery_id= explode(',',$row['gallery_id']);
			  for($ii=0;$ii<count($arr_gallery_id);$ii++)
			  {
				  $gallery_details=$this->Gallery_model->get_gallerydetails($arr_gallery_id[$ii]);
	
				

			
				
				if($gallery_details[0]['display_type']=='Video')
				{
					$content[]= array(
					 "id" => $gallery_details[0]['id'],
					 "title"=>$gallery_details[0]['title'],
					 "type" => 'Video',
					 "path"=> base_url().'uploads/gallery_video/'. $gallery_details[0]['video'],
					 "duration"=>$gallery_details[0]['video_duration']
					 
				   );
					
				}	
				elseif($gallery_details[0]['display_type']=='Image')
				{
					$content[]= array(
					 "id" => $gallery_details[0]['id'],
					 "title"=>$gallery_details[0]['title'],
					 "type" => 'Image',
					 "path"=>base_url().'uploads/gallery_image/'.$gallery_details[0]['image'],
					 "duration"=>$gallery_details[0]['video_duration']
				   );
				
			    }
			
			  
			  }
			  
			  	  $section_content[]= array(
					 "sectionid"=>$row['id'],
					 "schedule"=>$row['schedule'],
					 "horizontal" => $row['H_size'],
					 
					 "vertical" => $row['V_size'],
					 "actions" => $row['on_off'],
					 "gallery"=>$content
				   );
			  
			  
			  //$this->Gallery_model->get_gallerydetails();
			  
			  //print_r($row);
			  
		  }	
		  print json_encode($section_content); 
	   }	   
	   
	   
   }
   
   public function login_old()
	{
		$data['notif'] = $this->auth_model->Authentification();
		if(empty($data['notif']))
		{
			$content=array('login'=>'1');	
		}
		else
		{
			$content=array('login'=>'0');
		}	
		$JSON_ARR = array(
				'valid_login'=>$content
				);

				print json_encode($JSON_ARR);

		
		//print_r($data['notif']);
	
	}
   
   
    public function chk_update()
	{
		$update_data = $this->Api_model->get_data_update();
		$JSON_ARR = array(
				'is_change'=>$update_data[0]['reaccess_flag']
				);
		$sql = "update reaccess set reaccess_flag=0 ";
		$this->db->query($sql);
		print json_encode($JSON_ARR);		
	}
   

    /*
     * 
     */
  //nk
// 	function randomPassword() {
// 		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
// 		$pass = array(); //remember to declare $pass as an array
// 		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
// 			for ($i = 0; $i < 8; $i++) {
// 				$n = rand(0, $alphaLength);
// 				$pass[] = $alphabet[$n];
// 			}
// 		return implode($pass); //turn the array into a string
// 	}
	
//enk	
	/*
	function edit_profile()
	{
		$email=$this->input->post('email');
		$mobile=$this->input->post('mobile');
		$password=$this->input->post('password');
		$conf_password=$this->input->post('conf_password');
		if(!empty($email) or !empty($mobile))
		{
			
			if (!empty($password) and !empty($conf_password) )
			{
				
				if($password==$conf_password)
				{
					
					$mobile=substr($mobile, -10);
					$cond_and_or="and";
					$where_as="";
					if(!empty($email))
					{	
						$where_as.=" and ( email ='".trim($email)."'"; 
						$cond_and_or="OR";
					}
					
					if(!empty($mobile))
					{	
						$where_as.=" $cond_and_or (SUBSTRING(mobile,-10)='".trim($mobile)."')";
					}
					
					if(!empty($email))
					{	
						$where_as.=")"; 
					}
					
					
					
					echo $sql = "SELECT *  FROM user where 1".$where_as;
					$res = $this->db->query($sql);
					if ($res->num_rows() <= 0) 
					{
						
						//return $row;
						$JSON_ARR[] = array(
								'response'=>"This user does not exist"
								);
						print json_encode($JSON_ARR);
						exit;
						
					}
					else
					{
						$row = $res->result_array();
						//print_r($row[0]['user_id']);
					

					if(!empty($email))
					{
						echo $sql = "SELECT *  FROM user where 1 and email ='".trim($email)."' and user_id!='".$row[0]['user_id']."'";
						$res_email = $this->db->query($sql);
						if ($res_email->num_rows() > 0) 
						{
							//return $row;
							$JSON_ARR[] = array(
									'response'=>"This email already exist",
									'success'=>0
									);
							print json_encode($JSON_ARR);
							exit;
							
						}
					
					}
					
					
					echo $sql = "SELECT *  FROM user where 
					1 and  SUBSTRING(mobile,-10)='".trim($mobile)."' and user_id!='".$row[0]['user_id']."'";
					$res_phone = $this->db->query($sql);
					if ($res_phone->num_rows() > 0) 
					{
						//return $row;
						$JSON_ARR[] = array(
								'response'=>"This phone already exist",
								'success'=>0
								);
						print json_encode($JSON_ARR);
						exit;
						
					}


					
						
						
						$password=sha1($password);
						$data['password']=$password;
						if(!empty($mobile))
						{
						$data['mobile']=$this->input->post('mobile');
						}
						
						if(!empty($email))
						{
						$data['email']=$this->input->post('email');
						}
						
						//edit_profile
					
					
					}
					
					
					
				}	
				else
				{
					$JSON_ARR = array(
					'response'=>"Passwords do not match.",
					'success'=>0
					);
					print json_encode($JSON_ARR);  
					
				}	
			}
			else
			{
				$JSON_ARR = array(
					'response'=>"Please enter password.",
					'success'=>0
					);
					print json_encode($JSON_ARR);  
				
			}	
			
			
		}	
		else
		{
			$JSON_ARR[] = array(
			'response'=>"Wrong data",
			'success'=>0
			);
			print json_encode($JSON_ARR);

			
		}	
		
		
		
	}
*/


	function edit_profile()
	{
		$user_id=$this->input->post('user_id');
		$name=$this->input->post('name');
		
		//$email=$this->input->post('email');
		//$mobile=$this->input->post('mobile');
		//$password=$this->input->post('password');
		//$conf_password=$this->input->post('conf_password');

		
		if(!empty($user_id))
		{
			
			
			    $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR[] = array(
								'response'=>"This user does not exist"
								);
						print json_encode($JSON_ARR);
						exit;
						
					}
			
			
			
			
			
			
			/*
			if(!empty($email))
					{
						$sql = "SELECT * FROM user where 1 and email ='".trim($email)."' and user_id!='".$user_id."'";
						$res_email = $this->db->query($sql);
						if ($res_email->num_rows() > 0) 
						{
							//return $row;
							$JSON_ARR[] = array(
									'response'=>"This email already exist",
									'success'=>1
									);
							print json_encode($JSON_ARR);
							exit;
							
						}
					
					}

			*/
		    /*
		
				if(!empty($mobile))
				{	
					$sql = "SELECT *  FROM user where 
					1 and  SUBSTRING(mobile,-10)='".trim($mobile)."' and user_id!='".$user_id."'";
					$res_phone = $this->db->query($sql);
					if ($res_phone->num_rows() > 0) 
					{
						//return $row;
						$JSON_ARR[] = array(
								'response'=>"This phone already exist",
								'success'=>1
								);
						print json_encode($JSON_ARR);
						exit;
						
					}
			
				}
			
			*/
			
			/*
						if (!empty($password) and !empty($conf_password) )
						{	
							if($password==$conf_password)
							{
							$password=sha1($password);
							$data['password']=$password;
							}
							else
							{
								$JSON_ARR[] = array(
								'response'=>"Passwords do not match.",
								'success'=>0
								);
								print json_encode($JSON_ARR);  	
								exit;		
							}		
						
						}	
			*/			
			
					
					/*
						if(!empty($mobile))
						{
							$sql = "SELECT mobile FROM user where user_id='".$user_id."' and mobile!='' ";
							$res_che_mob = $this->db->query($sql);
							if ($res_che_mob->num_rows() <= 0) 
							{
								$data['mobile']=$this->input->post('mobile');
							}
						
						}
						
						if(!empty($email))
						{
							$sql = "SELECT email  FROM user where user_id='".$user_id."' and email!='' ";
							$res_che_mail = $this->db->query($sql);
							if ($res_che_mail->num_rows() <= 0) 
							{
								$data['email']=$this->input->post('email');
							}
						}
						$updatesuccess="";
					
					*/
					
					$data['name']=$name;	
					$filename=$_FILES['profile_image']['name'];
                    
                    
                 
                    
                    if(!empty($filename))
                    {
                        
                       
                        $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $uniquesavename=time().uniqid(rand());
                        $unique_image_name=$uniquesavename.'.'.$img_ext;
                        $data['profile_image']=$unique_image_name;
                     move_uploaded_file($_FILES['profile_image']['tmp_name'], 'assets/frontend/profile_image/'.$unique_image_name);
                    }	
						
						
						
						
						
						if(!empty($data))
						{	
						$updatesuccess=  $this->Api_model->edit_profile($data,$user_id);
						}
						if($updatesuccess)
						{
							$JSON_ARR[] = array(
							'response'=>"your data has been changed successfully",
							'success'=>1,
							'user_id'=>$user_id,
							'name'=>$name,
							'profile_image'=>$this->crud_model->get_profile_image_url($user_id)
							);
							print json_encode($JSON_ARR);  
						}	
						else
						{
							$JSON_ARR[] = array(
							'response'=>"your data has not been changed successfully",
							'success'=>1
							);
							print json_encode($JSON_ARR);  
							
						}	
		}

		else
		{
			
			$JSON_ARR[] = array(
			'response'=>"User Id required",
			'success'=>0
			);
			print json_encode($JSON_ARR);	
			
			
		}		

	}


	function GetUserDetail($user_id=0)
	{

				$sql = "SELECT *  FROM user where user_id ='".$user_id."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$row = $res->result_array();
					//return $row;
					$JSON_ARR[] = array(
							'user_id'=>$row[0]['user_id'],
							'name'=>$row[0]['name'],
							'email'=>$row[0]['email'],
							'mobile'=>$row[0]['mobile'],
							'loginwith'=>$row[0]['loginwith'],
							'facebook_id'=>$row[0]['facebook_id'],
							'success'=>1
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
					$JSON_ARR[] = array(
					'response'=>"There is not User exist",
					'success'=>0
					);
					print json_encode($JSON_ARR);	
				}	

	}

    function upload_broadcasting()
    {

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


        $user_id=$this->input->post('user_id');
		$cat_id=$this->input->post('cat_id');    
        $content_description=$this->input->post('content_description');
		 $post_privacy_type=$this->input->post('post_privacy_type');
        

        if(!empty($user_id) and !empty($cat_id))
        {
            
          
            
            $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR[] = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
	
		
            
            

            $data['user_id']=$user_id;
            $data['cat_id']=$cat_id;
            $data['content_description']=$content_description;
					
			$data['post_privacy_type']=abs($post_privacy_type);
            $data['created_date']=date('Y-m-d H:i:s');
            
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();
            
            
            $platformcraft_token=$this->get_platformcraft_token();
            
	            
            $array_image=[];
			if(!empty($_FILES['broadcast_img']))
			{
					
            for($i=0; $i<count($_FILES['broadcast_img']['name']); $i++) 
            {
            
                if($_FILES['broadcast_img']['name'][$i]!="")
                {
                    
                    //////////////////////////////////////////////////
                 
                 
                 $tmp_image='';
                 $filename=$_FILES['broadcast_img']['name'][$i];
                 $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                   

                  $_FILES['broadcast_img']['tmp_name'][$i]=$this->compressImage($_FILES['broadcast_img']['tmp_name'][$i],$_FILES['broadcast_img']['tmp_name'][$i],$img_ext);
                  

                   //echo "=============111";
                 ///exit; 
                    //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    
                   //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.jpeg';
                   
                   
                   
                   $filename=$_FILES['broadcast_img']['name'][$i];
                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $array_image[]='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    move_uploaded_file($_FILES['broadcast_img']['tmp_name'][$i], 'assets/global/broadcast/images/'.'img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext);
                   
                   
                             

                }
              
            }
            
			}
			
			
			
            $array_video=[];
            
            //print_r($_FILES['broadcast_video']);
			//echo "--------------------------".is_array($_FILES['broadcast_video']['name']);
			//echo "======================".count($_FILES['broadcast_video']['name']);
           //exit;
           
            for($ii=0; $ii<count( $_FILES['broadcast_video']['name']); $ii++) 
            {

               if($_FILES['broadcast_video']['name'][$ii]!="")
                { 
                    
                    ///////////////video_thumblain////////////////////////
    			require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                
                $movie=$_FILES['broadcast_video']['tmp_name'][$ii];
                
                $extension='.jpeg';
    		    $tmp_video_thumb='videothumb_'.$broadcast_id .'_'.($ii+1).$extension;   
                    
                
                $thumbnail = 'assets/global/broadcast/video_thumb/'.$tmp_video_thumb;
                $video = $ffmpeg->open($movie);
                /*
                $video->filters()->resize(new FFMpeg\Coordinate\Dimension(300, 310), $mode = RESIZEMODE_SCALE_HEIGHT)->synchronize();

                */
                
                /*
                $video
                ->filters()
                ->resize(new FFMpeg\Coordinate\Dimension(640, 480),3)
                ->synchronize();
                 */           
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                
                $frame->save($thumbnail);
                $array_video_thumb[]=$tmp_video_thumb;
    			
    		/*
    				$extension='.jpeg';
    				 $tmp_video_thumb='videothumb_'.$broadcast_id .'_'.($ii+1).$extension;   
                        
                        $url2='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video_thumb/'.$tmp_video_thumb;
            				
            				$ch = curl_init($url2);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            				curl_setopt($ch, CURLOPT_POST, 1);
                            $args['file'] = new CurlFile($thumbnail,'image/jpeg','thumbnail.jpeg');
                            
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            
            				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            				curl_setopt($ch, CURLOPT_HTTPHEADER,
            				array(
            				'Authorization: Bearer '.$platformcraft_token,
            				'Content-Type: multipart/form-data'
            				
            	            ));
            				$result2 = curl_exec($ch);
            				curl_close($ch);
            				$response3=json_decode($result2);
                            
                            $array_video_thumb[]=$response3->download_url;

    			
    				
    				/////////////video_thumo///////////////////

                 */   
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    $tmp_video_name='';
                    $filename1=$_FILES['broadcast_video']['name'][$ii];
                    $video_ext = pathinfo($filename1, PATHINFO_EXTENSION);
                        
                        
                    ///$array_video[]='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                        
                    $tmp_video_name='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
				   
				   copy($_FILES['broadcast_video']['tmp_name'][$ii], 'assets/global/tmp_uwatch_video/' . 'tmp_post_video' .'.'. $video_ext);
                       
                    /*
                   $url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video/'.$tmp_video_name;
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    				curl_setopt($ch, CURLOPT_POST, 1);
                    $args['file'] = new CurlFile($_FILES['broadcast_video']['tmp_name'][$ii],'video/mp4',$_FILES['broadcast_video']['name'][$ii]);
                    
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'Authorization: Bearer '.$platformcraft_token,
    				'Content-Type: multipart/form-data'
    				
    	            ));
    				$result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
				  
    				//$array_video[]=$response->download_url;
    			    

    			   // $array_video[]=$response->hls;	
    				if(empty($response->hls))
    				{
    				    $array_video[]=$response->download_url;
    				}
    				else
    				{
    				     $array_video[]="happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/broadcast_video/".$tmp_video_name."/playlist.m3u8";
    				    
    				    
    				}
				   
				   
*/
				   
				    
		//	if(!empty($_FILES['u_watch']['name']))
		 //{
		///$tmpextension=pathinfo($_FILES['u_watch']['name'], PATHINFO_EXTENSION);
		//copy($_FILES['u_watch']['tmp_name'], 'assets/global/tmp_uwatch_video/' . 'tmp_uwatch_video' .'.'. $tmpextension);	
		///}

			 
///////////////*******************/////////////////////////////
			 
	$temp_video_broadcast=base_url().'assets/global/tmp_uwatch_video/'.'tmp_post_video'.'.'. $video_ext;			 
	
///$u_watch_video='https://4ykn7pwvnwqg-hls-push.5centscdn.com/content_provider_folder/'.$u_watch_video.'/playlist.m3u8';
			 
	$url='https://api.5centscdn.com/v2/zones/vod/push/4144/import';
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        				curl_setopt($ch, CURLOPT_POST, 1);
                   // $args['file'] = new CurlFile($_FILES['u_watch']['tmp_name'],'video/mp4',$_FILES['u_watch']['name']);
                
			 
			 		$args= array('url' => $temp_video_broadcast,'filename' =>  $tmp_video_name,'folder' => 'broadcast_video');
  

			 
			 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'X-API-Key:  558997c55d2bdfd3f567b184045ee72c',
    				'Content-Type: multipart/form-data'
    				
    	            ));
    		    $result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);

				   $array_video[]="cdn.happywatch99.com/broadcast_video/".$tmp_video_name."/playlist.m3u8";
				   
				   
    				
                }
                

            }
            
            
            
            
            /*
            
            for($ii=0; $ii<count($_FILES['broadcast_video_thumbnail']['name']); $ii++) 
            {
                
                if($_FILES['broadcast_video_thumbnail']['name'][$ii]!="")
                {
                        
                   

                   
                  /// $im = imagecreatefromjpeg///($_FILES['broadcast_video_thumbnail']['tmp_name'][$ii]);

                    
                ///if($im){
                ///    imagejpeg($im, $_FILES['broadcast_video_thumbnail']['tmp_name'][$ii], 30);
                ///}
                    
                    
                    $_FILES['broadcast_video_thumbnail']['tmp_name'][$ii]=$this->compressImage($_FILES['broadcast_video_thumbnail']['tmp_name'][$ii],$_FILES['broadcast_video_thumbnail']['tmp_name'][$ii],80);
                   
                    
                    $tmp_video_thumb='';    
                         $filename1=$_FILES['broadcast_video_thumbnail']['name'][$ii];
                    $extension = pathinfo($filename1, PATHINFO_EXTENSION);
                    
                    
                   // $array_video_thumb[]='videothumb_'.$broadcast_id .'_'.($ii+1).'.'.$extension;
                       
                    $tmp_video_thumb='videothumb111_'.$broadcast_id .'_'.($ii+1).'.'.$extension;   
                        
                        $url2='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video_thumb/'.$tmp_video_thumb;
            				
            				$ch = curl_init($url2);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            				curl_setopt($ch, CURLOPT_POST, 1);
                            $args['file'] = new CurlFile($_FILES['broadcast_video_thumbnail']['tmp_name'][$ii],'image/jpeg',$_FILES['broadcast_video_thumbnail']['name'][$ii]);
                            
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            
            				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            				curl_setopt($ch, CURLOPT_HTTPHEADER,
            				array(
            				'Authorization: Bearer '.$platformcraft_token,
            				'Content-Type: multipart/form-data'
            				
            	            ));
            				$result2 = curl_exec($ch);
            				curl_close($ch);
            				$response3=json_decode($result2);
                            
                             $array_video_thumb11[]=$response3->download_url;

                }
                
               

            }
            */
            
            
            $str_image= implode(",",$array_image);
            $str_video= implode(",",$array_video);
            $str_video_thumb= implode(",",$array_video_thumb);
            
            
            
            $data2=array(
                "broadcast_img"=>$str_image,
                "broadcast_video"=>$str_video,
                "broadcast_video_thumbnail"=>$str_video_thumb
                );
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data2);

            $JSON_ARR[] = array(
				'response'=>"your data has been saved successfully",
				'success'=>1,
				'broadcast_id'=>$broadcast_id,
				'user_id'=>$user_id,
				'category_id'=>$cat_id
				);
				print json_encode($JSON_ARR);

        }
        else
        {
            	$JSON_ARR[] = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
        }

    }
    
    
    
    
    function broadcast_comments()
    {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id');  
        $comment=$this->input->post('comment');  
    
        // broadcast_comments
        
        
        
        if( empty($user_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($broadcast_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"broadcast_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($comment))
        {
            
            $JSON_ARR[] = array(
					'response'=>"comment is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        
        
        if(!empty($user_id) and !empty($broadcast_id))
        {
            $data['user_id']=$user_id;
            $data['user_comment']=$comment;
            //$data['created_date']=date('Y-m-d');
            $data['broadcast_id']=$broadcast_id;
            $data['created_date']=date('Y-m-d H:i:s');;
 

            $this->db->insert('broadcast_comments', $data);
    		$comment_id = $this->db->insert_id();

            $JSON_ARR[] = array(
				'response'=>"your comment has been saved successfully",
				'success'=>1,
				'comment_id'=>$comment_id,
				'user_id'=>$user_id
				);
				print json_encode($JSON_ARR);

            
        }
    }
    
    

function broadcast_text_post()
    {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id');  
        $comment=$this->input->post('comment');  
    
        // broadcast_comments
        
        
        
        if( empty($user_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($broadcast_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"broadcast_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($comment))
        {
            
            $JSON_ARR[] = array(
					'response'=>"comment is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        
        
        if(!empty($user_id) and !empty($broadcast_id))
        {
            $data['user_id']=$user_id;
            $data['user_comment']=$comment;
            //$data['created_date']=date('Y-m-d');
            $data['broadcast_id']=$broadcast_id;
            $data['created_date']=date('Y-m-d H:i:s');;
 

            $this->db->insert('broadcast_comments', $data);
    		$comment_id = $this->db->insert_id();

            $JSON_ARR[] = array(
				'response'=>"your comment has been saved successfully",
				'success'=>1,
				'comment_id'=>$comment_id,
				'user_id'=>$user_id
				);
				print json_encode($JSON_ARR);

            
        }
    }
    





    
    
    function broadcasting_list($user_id=0)
    {
        //$super_admin_id= $this->uri->segment(3);
        
		
		if(empty($user_id))
			
		{	
		
		$user_id=$this->input->post('user_id');
        
		}
        
        if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        /*
        if(!empty($user_id))
        {
          $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
        
        }
        */
        $where_as="";
        //if($super_admin_id>0)
        ///{
        //$where_as="and BD.user_id!='".$super_admin_id."'";    
        //}


/*
        $sql = "select BD.*,C.name,U.name as user_name from broadcast_details BD
        left join category C on BD.cat_id= C.cat_id
        left join user U on BD.user_id=U.user_id
        where 1  ".$where_as." order by BD.id desc ";
  */      
        
        $page_no=$this->input->post('page');
        $limit=10; 
         //expression ? trueValue : falseValue
         $page=(!empty($page_no)) ? $page_no : 1;
         if($page==1)
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }

        
        $where_as="and U.user_id not in (SELECT user_id FROM broadcast_post_block_unblock_user where blocking_user_id='".$user_id."')";
        
        
        //$sql = "select BD.*,C.name,U.name as user_name from user U
        //left join broadcast_details BD on U.user_id=BD.user_id
        //left join category C on BD.cat_id= C.cat_id
        
        ///where 1  ".$where_as." order by BD.id desc limit $start,$limit ";
        
        
        
        $sql = "select BD.*,C.name,U.name as user_name from user U
        left join broadcast_details BD on U.user_id=BD.user_id
        left join category C on BD.cat_id= C.cat_id
        
        where 1  ".$where_as." order by BD.id desc limit $start,$limit ";
        
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			
			
			//echo $this->db->last_query();
			
			
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcasts as $row)
			{
        			    
			   
			    if($row['live_status']==0 or $row['live_status']=1) 
			   {
			   
        			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                        $res_like = $this->db->query($query_like);
                        $likes= $res_like->result_array();
                        $likes_count=abs($likes[0]['no_likecount']);
                    			    
                		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".abs($row['id']);
                        $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue="false";
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue='true';
                            }
                            else
                            {
                                $current_user_statue='false';
                            }
                    	}
            	    
                			    //$sql = "SELECT *  FROM broadcast_comments where user_id ='".$row['user_id']."' and broadcast_id='".$row['id']."'";
                				
                				$sql = "SELECT *  FROM broadcast_comments where  broadcast_id='".$row['id']."'";
                				
                				$comment_contents=[];
                				$res = $this->db->query($sql);
                				if ($res->num_rows() > 0) 
                				{
                					
                					
                					$connents = $res->result_array();
                					
                					foreach($connents as $connent)
                					{
                					        $comment_contents[] = array(
                							'user_id'=>$connent['user_id'],
                							'broadcast_id'=>$connent['broadcast_id'],
                							'user_comment'=>$connent['user_comment'],
                							'created_date'=>$connent['created_date'],	  	
                							);
                					
                					}
                				}
                			    
        			   // echo "=======".$row['created_date'];
        			    
        			    
        			    
        			   $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			       
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			       
        			       
        			          // $images[]='https://'.$img;
        			           
        			       }            
        			        
        			        
        			        
        			    }
        			    $array_videos=[];
        			    
        			 
			    
			    
                        if(!empty($row['full_video_url']) and $row['live_status']==0) 
    			        {   
                           
        			        $array_videos[]=$row['full_video_url'];
    			        }
        			    elseif($row['live_status']==1)
        			    {
        			        $array_videos[]=$row['broadcast_video'];
        			        
        			        
        			        
        			        
        			        
        			    }
        			    
        			    else
        			    {
        			    
        			    foreach($array_broadcast_video as $video )
        			    {
        			        
        			       if(!empty($video))
        			       {
        	 
            			        /*
            			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                {    
                                    $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                }    
                                
                                */
                                
                                
                                $array_videos[]='https://'.$video;
        			       }  
        			        
        			        
        			        
        			        
        			    }
        			    
        			    }
        			    
        			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
        				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
        			    
        			   
        			$array_videosthumb=[];   
        			
        			
        			//if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			
        			if(!empty('assets/global/channel_thumb/' .$row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    //$array_videosthumb[] =$row['full_video_thumb_url'];
        			    $array_videosthumb[] =base_url() .'assets/global/channel_thumb/'.$row['full_video_thumb_url'];
        			}
        			elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			                  $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			       if(!empty($thumb))
                			       {
                                        
                                        
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                        
                                        
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        }    
                	                    		       
	                
	                                   //$array_videosthumb[]=    'https://'.$thumb;
	                                }
                			       ///print_r($array_videosthumb);
                			    }   
			        }
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			    $sharing_count=0;
        			    $view_count=0;
        			        if(!empty($row['id']))
        			        {
        			        
        			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
        			   
        			        $view_count=$this->Api_model->broadcast_view($row['id']); 
								
							$increase_view=$this->Api_model->increase_broadcast_view($row['id']);			
							$view_count=$view_count+$increase_view;

        			        }
        			   
        			   
        			   
        
                       //// echo $this->db->last_query();
        
        			    $broadcast_contents[] = array(
        					'broadcast_id'=>$row['id'],
        					'user_id'=>$row['user_id'],
        					'post_privacy'=>$row['post_privacy_type'],
        					'name'=>$row['user_name'],
        					'category_id'=>$row['cat_id'],
        					'category_name'=>$row['name'],
        					'image'=>$images,
        					'video'=>$array_videos,
        					'video_thumbnail'=>$array_videosthumb,
        					'created_date'=>$row['created_date'],
							'date'=>date("d-M-Y H:i:s", strtotime($row['created_date'])),
        					'content_description'=>	$row['content_description'],
        					'comment_contents'=>$comment_contents,
        					
        					'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
        					
        					'view_count'=>$this->DisplayViews($view_count),
        					'sharing_count'=>$sharing_count,
        					'self_like'=>$current_user_statue,
        					'like_count'=>$likes_count
        					
        					);
        			
			
			    }
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'launch'=>'broadcasting',
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);
            
            
        
		}
		else
		{
		   $JSON_ARR[] = array(
			'response'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		    
		}
            
                
    }
    
    
    
    
    
    function broadcasting_list_old_22_12_20($super_admin_id=0)
    {
        //$super_admin_id= $this->uri->segment(3);

        $where_as="";
        if($super_admin_id>0)
        {
        $where_as="and BD.user_id!='".$super_admin_id."'";    
        }

        $sql = "select BD.*,C.name,U.name as user_name from broadcast_details BD 
        left join category C on BD.cat_id= C.cat_id
        left join user U on BD.user_id=U.user_id
        where 1  ".$where_as." order by BD.id desc ";
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcasts as $row)
			{
        			    
			   
			    if($row['live_status']==0 or $row['live_status']=1) 
			   {
			   
        			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                        $res_like = $this->db->query($query_like);
                        $likes= $res_like->result_array();
                        $likes_count=abs($likes[0]['no_likecount']);
                    			    
                		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$row['user_id']."' and broadcast_id=".$row['id'];
                        $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue="false";
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue='true';
                            }
                            else
                            {
                                $current_user_statue='false';
                            }
                    	}
            	    
                			    //$sql = "SELECT *  FROM broadcast_comments where user_id ='".$row['user_id']."' and broadcast_id='".$row['id']."'";
                				
                				$sql = "SELECT *  FROM broadcast_comments where  broadcast_id='".$row['id']."'";
                				
                				$comment_contents=[];
                				$res = $this->db->query($sql);
                				if ($res->num_rows() > 0) 
                				{
                					
                					
                					$connents = $res->result_array();
                					
                					foreach($connents as $connent)
                					{
                					        $comment_contents[] = array(
                							'user_id'=>$connent['user_id'],
                							'broadcast_id'=>$connent['broadcast_id'],
                							'user_comment'=>$connent['user_comment'],
                							'created_date'=>$connent['created_date'],	  	
                							);
                					
                					}
                				}
                			    
        			   // echo "=======".$row['created_date'];
        			    
        			    
        			    
        			   $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			        /*
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			        */
        			       
        			           $images[]='https://'.$img;
        			           
        			       }            
        			        
        			        
        			        
        			    }
        			    $array_videos=[];
        			    
        			 
			    
			    
                        if(!empty($row['full_video_url']) and $row['live_status']==0) 
    			        {   
                           
        			        $array_videos[]=$row['full_video_url'];
    			        }
        			    elseif($row['live_status']==1)
        			    {
        			        $array_videos[]=$row['broadcast_video'];
        			        
        			        
        			        
        			        
        			        
        			    }
        			    
        			    else
        			    {
        			    
        			    foreach($array_broadcast_video as $video )
        			    {
        			        
        			       if(!empty($video))
        			       {
        	 
            			        /*
            			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                {    
                                    $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                }    
                                
                                */
                                
                                
                                $array_videos[]='https://'.$video;
        			       }  
        			        
        			        
        			        
        			        
        			    }
        			    
        			    }
        			    
        			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
        				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
        			    
        			   
        			$array_videosthumb=[];   
        			
        			
        			if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    $array_videosthumb[] =$row['full_video_thumb_url'];
        			}
        			elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			                  $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			       if(!empty($thumb))
                			       {
                                        
                                        /*
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                        
                                        
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        }    
                	                    */		       
	                
	                                    $array_videosthumb[]=    'https://'.$thumb;
	                                }
                			       ///print_r($array_videosthumb);
                			    }   
			        }
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			    $sharing_count=0;
        			    $view_count=0;
        			        if(!empty($row['id']))
        			        {
        			        
        			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
        			   
        			        $view_count=$this->Api_model->broadcast_view($row['id']); 
        			        }
        			   
        			   
        			   
        
                       //// echo $this->db->last_query();
        
        			    $broadcast_contents[] = array(
        					'broadcast_id'=>$row['id'],
        					'user_id'=>$row['user_id'],
        					'name'=>$row['user_name'],
        					'category_id'=>$row['cat_id'],
        					'category_name'=>$row['name'],
        					'image'=>$images,
        					'video'=>$array_videos,
        					'video_thumbnail'=>$array_videosthumb,
        					'created_date'=>$row['created_date'],
        					'content_description'=>	$row['content_description'],
        					'comment_contents'=>$comment_contents,
        					
        					'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
        					
        					'view_count'=>$view_count,
        					'sharing_count'=>$sharing_count,
        					'self_like'=>$current_user_statue,
        					'like_count'=>$likes_count
        					
        					);
        			
			
			    }
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'launch'=>'broadcasting',
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);
            
            
        
		}
		else
		{
		   $JSON_ARR[] = array(
			'response'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		    
		}
            
                
    }
    
    function get_broadcast_comments()
    {
    
         
        $broadcast_id=$this->input->post('broadcast_id');
        
        if( empty($broadcast_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"Broadcast_id is required"
					);
				print json_encode($JSON_ARR);
                die();
        }
         $sql = "SELECT broadcast_comments.*, user.user_id,user.name,user.email,user.profile_image,user.loginwith  FROM broadcast_comments left join  user on broadcast_comments.user_id=user.user_id where 1 and broadcast_comments.broadcast_id='". $broadcast_id."'";
    		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$connents = $res->result_array();
				$comment_contents=[];
				foreach($connents as $connent)
				{
				        $comment_contents[] = array(
						'user_id'=>$connent['user_id'],
						'user_name'=>$connent['name'],
						
						'profile_image'=>$this->crud_model->get_profile_image_url($connent['user_id']),
						
						'email'=>$connent['email'],
						
						'broadcast_id'=>$connent['broadcast_id'],
						'user_comment'=>$connent['user_comment'],
						'created_date'=>$connent['created_date'],	  	
						);
				
				}
				
			    $ALL_JSON_ARR= array(
				'response'=>$comment_contents,
				);
		        print json_encode($ALL_JSON_ARR);
			    
			    
			    
			}
			else
			{
			    /*
			    $JSON_ARR[] = array(
					'response'=>"There is not comment in this broadcast"
					);
				
				*/
				
				$JSON_ARR= array(
					'response'=>array()
					);
				
				print json_encode($JSON_ARR);
                die();
			    
			    
			}

    
    }
    ///////////////////////////////////////////////////
    function get_broadcastvideo_list()
    {
    
    $user_id=$this->input->post('user_id');
    
        if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
       
       
        $page_no=$this->input->post('page');
        $limit=10; 
         $page=(!empty($page_no)) ? $page_no : 1;
         if($page==1)
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }

       
       
       
       
        
        /*
        if(!empty($user_id))
        {
          $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
        
        }
        
        */
        
        
        /*
        $where_as="and broadcast_video!='' ";    
       $sql = "select BD.*,C.name,U.name as user_name from broadcast_details BD 
        left join category C on BD.cat_id= C.cat_id
        left join user U on BD.user_id=U.user_id
        where 1  ".$where_as." order by BD.id desc ";
        */
        
        
        $where_as="and BD.broadcast_video!='' ";    
        $sql = "select BD.*,C.name,U.name as user_name from user U 
        left join broadcast_details BD   on U.user_id=BD.user_id
        left join category C on BD.cat_id= C.cat_id
        where 1  ".$where_as." order by BD.id desc limit $start,$limit ";
        
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcasts as $row)
			{
        			    
			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                $res_like = $this->db->query($query_like);
                $likes= $res_like->result_array();
                $likes_count=abs($likes[0]['no_likecount']);
            			    
        		
        		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".$row['id'];
                $res_self = $this->db->query($status_query);
            	
            	$current_user_statue="false";
            	if ($res_self->num_rows() > 0) 
            	{
                	
                	$user_status = $res_self->result_array();
                	
                	///print_r($user_status);
                	
                	if($user_status[0]['type']==1)
                    {
                        $current_user_statue='true';
                    }
                    else
                    {
                        $current_user_statue='false';
                    }
            	}
    	    
        			    
        			    
        			    
        			    
        			    $sql = "SELECT *  FROM broadcast_comments where 1 and broadcast_id='". $row['id']."'";
        				$res = $this->db->query($sql);
        				if ($res->num_rows() > 0) 
        				{
        					$connents = $res->result_array();
        					$comment_contents=[];
        					foreach($connents as $connent)
        					{
        					        $comment_contents[] = array(
        							'user_id'=>$connent['user_id'],
        							'broadcast_id'=>$connent['broadcast_id'],
        							'user_comment'=>$connent['user_comment'],
        							'created_date'=>$connent['created_date'],	  	
        							);
        					
        					}
        				}
        			    
			   // echo "=======".$row['created_date'];
			    
			    
			    
			    $array_videos=[];
			    
			    
			   if($row['live_status']==0) 
			   {
			    
			    
                        if(!empty($row['full_video_url'])) 
    			        {   
                           
                          
                           /*
                            require_once(APPPATH.'libraries/aws/s3bucket_configuration.php');
        
                            $bucket = 'happywatch';
                            $keyname=$row['broadcast_video'];
                           
                           
                           $aws_movie_name= basename($row_movie['url']);
								$cmd = $s3Client->getCommand('GetObject', [
									'Bucket' => $bucket,
									'Key' => $keyname
								]);

								$request = $s3Client->createPresignedRequest($cmd, '+120 minutes');

								// Get the actual presigned-url
								$movie_url= $presignedUrl = (string)$request->getUri();
                           
                           */
                           
                           
                           
                           
                           $array_videos[]=$row['full_video_url'];
                           
                         
    			        }  
                        else
                        {
                           
                                $array_broadcast_video= explode(',', $row['broadcast_video']);
                			    
                			    $array_videos=[];
                			    foreach($array_broadcast_video as $video )
                			    {
                			        
                			       if(!empty($video))
                			       {
                	 
                    			        /*
                    			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                        {    
                                            $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                        
                                            
                                        }    
                                        */
                                        
                                        $array_videos[]='https://'.$video;
                                    
                			       }  
                			        
                			        
                			        
                			        
                			    }
                        }  
            			    
			        }
			        elseif($row['live_status']==1)
			        {
			           $array_videos[]= $row['broadcast_video'];
			        }
			        
			        
			        
			        $array_videosthumb=[];
			        
			        
			        
			        //if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			
        			 if (file_exists('assets/global/channel_thumb/' . $row['full_video_thumb_url']) and !empty($row['full_video_url']))
        			{
        			    $array_videosthumb[] =base_url() .'assets/global/channel_thumb/'.$row['full_video_thumb_url'];
        			}
			        
			        elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			        
			        $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
			        
			        
                			    
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			      
                			      
                			        
                			       if(!empty($thumb))
                			       {

                    			       
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        } 
                                       
                                    
                                    //$array_videosthumb[]='https://'.$thumb;
                                    
                			       }
                			       
                			       
                			       ///print_r($array_videosthumb);
                			    }   
			        
			        }
			        
			        
			        
			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
			    
			        $view_count=0;
			        $sharing_count=0;
			        if(!empty($row['id']))
			        {
			        
			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
			    
			    
			        $view_count=$this->Api_model->broadcast_view($row['id']);
			    
			        }
			    
			    
			    
			    
			    
			    
			    $broadcast_contents[] = array(
					'broadcast_id'=>$row['id'],
					'user_id'=>$row['user_id'],
					'name'=>$row['user_name'],
					
					'profile_image'=>$this->crud_model->get_profile_image_url($row['user_id']),
					
					'category_id'=>$row['cat_id'],
					'category_name'=>$row['name'],
					'video'=>$array_videos,
					 'video_thumbnail'=>$array_videosthumb,
					'created_date'=>$row['created_date'],
					'content_description'=>	$row['content_description'],
					'comment_contents'=>$comment_contents,
					
					'view_count'=>$this->DisplayViews($view_count),
					'sharing_count'=>$sharing_count,
					'self_like'=>$current_user_statue,
					'like_count'=>$likes_count,
					'live_status'=> $row['live_status']
					
					);
			
			
			    
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'launch'=>'broadcasting',
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);

		}
		else
		{
		   $JSON_ARR[] = array(
			'response'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		    
		}
       

    }





 function get_broadcastvideo_list_old_22_12_20()
    {
    
    
        
        $where_as="and broadcast_video!='' ";    
        
       $sql = "select BD.*,C.name,U.name as user_name from broadcast_details BD 
        left join category C on BD.cat_id= C.cat_id
        left join user U on BD.user_id=U.user_id
        where 1  ".$where_as." order by BD.id desc ";
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcasts as $row)
			{
        			    
			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                $res_like = $this->db->query($query_like);
                $likes= $res_like->result_array();
                $likes_count=abs($likes[0]['no_likecount']);
            			    
        		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$row['user_id']."' and broadcast_id=".$row['id'];
                $res_self = $this->db->query($status_query);
            	
            	$current_user_statue="false";
            	if ($res_self->num_rows() > 0) 
            	{
                	
                	$user_status = $res_self->result_array();
                	
                	///print_r($user_status);
                	
                	if($user_status[0]['type']==1)
                    {
                        $current_user_statue='true';
                    }
                    else
                    {
                        $current_user_statue='false';
                    }
            	}
    	    
        			    
        			    
        			    
        			    
        			    $sql = "SELECT *  FROM broadcast_comments where 1 and broadcast_id='". $row['id']."'";
        				$res = $this->db->query($sql);
        				if ($res->num_rows() > 0) 
        				{
        					$connents = $res->result_array();
        					$comment_contents=[];
        					foreach($connents as $connent)
        					{
        					        $comment_contents[] = array(
        							'user_id'=>$connent['user_id'],
        							'broadcast_id'=>$connent['broadcast_id'],
        							'user_comment'=>$connent['user_comment'],
        							'created_date'=>$connent['created_date'],	  	
        							);
        					
        					}
        				}
        			    
			   // echo "=======".$row['created_date'];
			    
			    
			    
			    $array_videos=[];
			    
			    
			   if($row['live_status']==0) 
			   {
			    
			    
                        if(!empty($row['full_video_url'])) 
    			        {   
                           
                          
                           /*
                            require_once(APPPATH.'libraries/aws/s3bucket_configuration.php');
        
                            $bucket = 'happywatch';
                            $keyname=$row['broadcast_video'];
                           
                           
                           $aws_movie_name= basename($row_movie['url']);
								$cmd = $s3Client->getCommand('GetObject', [
									'Bucket' => $bucket,
									'Key' => $keyname
								]);

								$request = $s3Client->createPresignedRequest($cmd, '+120 minutes');

								// Get the actual presigned-url
								$movie_url= $presignedUrl = (string)$request->getUri();
                           
                           */
                           
                           
                           
                           
                           $array_videos[]=$row['full_video_url'];
                           
                         
    			        }  
                        else
                        {
                           
                                $array_broadcast_video= explode(',', $row['broadcast_video']);
                			    
                			    $array_videos=[];
                			    foreach($array_broadcast_video as $video )
                			    {
                			        
                			       if(!empty($video))
                			       {
                	 
                    			        /*
                    			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                        {    
                                            $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                        
                                            
                                        }    
                                        */
                                        
                                        $array_videos[]='https://'.$video;
                                    
                			       }  
                			        
                			        
                			        
                			        
                			    }
                        }  
            			    
			        }
			        elseif($row['live_status']==1)
			        {
			           $array_videos[]= $row['broadcast_video'];
			        }
			        
			        
			        
			        $array_videosthumb=[];
			        
			        
			        
			        if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    $array_videosthumb[] =$row['full_video_thumb_url'];
        			}
			        
			        elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			        
			        $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
			        
			        
                			    
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			      
                			      
                			        
                			       if(!empty($thumb))
                			       {

                    			        /*
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        } 
                                        */
                                    
                                    $array_videosthumb[]='https://'.$thumb;
                                    
                			       }
                			       
                			       
                			       ///print_r($array_videosthumb);
                			    }   
			        
			        }
			        
			        
			        
			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
			    
			        $view_count=0;
			        $sharing_count=0;
			        if(!empty($row['id']))
			        {
			        
			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
			    
			    
			        $view_count=$this->Api_model->broadcast_view($row['id']);
			    
			        }
			    
			    
			    
			    
			    
			    
			    $broadcast_contents[] = array(
					'broadcast_id'=>$row['id'],
					'user_id'=>$row['user_id'],
					'name'=>$row['user_name'],
					
					'profile_image'=>$this->crud_model->get_profile_image_url($row['user_id']),
					
					'category_id'=>$row['cat_id'],
					'category_name'=>$row['name'],
					'video'=>$array_videos,
					 'video_thumbnail'=>$array_videosthumb,
					'created_date'=>$row['created_date'],
					'content_description'=>	$row['content_description'],
					'comment_contents'=>$comment_contents,
					'view_count'=>$view_count,
					'sharing_count'=>$sharing_count,
					'self_like'=>$current_user_statue,
					'like_count'=>$likes_count,
					'live_status'=> $row['live_status']
					
					);
			
			
			    
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'launch'=>'broadcasting',
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);

		}
		else
		{
		   $JSON_ARR[] = array(
			'response'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		    
		}
       

    }




    function delete_broadcast()
    {
            $user_id=$this->input->post('user_id');
    		$broadcast_id=$this->input->post('broadcast_id');
    		
    		if( empty($user_id))
            {
                
                $JSON_ARR[] = array(
    					'response'=>"user_id is required"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            if( empty($broadcast_id))
            {
                
                $JSON_ARR[] = array(
    					'response'=>"broadcast_id is required"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
            
            $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
    		$res = $this->db->query($sql);
    		if ($res->num_rows() <= 0) 
    		{
    			
    			
    			$JSON_ARR= array(
    					'response'=>"This user does not exist"
    				
    					);
    			print json_encode($JSON_ARR);
    			die();
    			
    		}

            $sql = "SELECT *  FROM broadcast_details where 1 and id='".$broadcast_id."'";
    		$res = $this->db->query($sql);
    		if ($res->num_rows() <= 0) 
    		{
    			
    			
    			$JSON_ARR= array(
    					'response'=>"This broadcast does not exist"
    					
    					);
    			print json_encode($JSON_ARR);
    			die();
    			
    		}

            
            
            
            $this->Api_model->delete_broadcast($user_id,$broadcast_id);
            
            //echo $this->db->last_query();
            
            $this->Api_model->delete_broadcast_comments($user_id,$broadcast_id);
            $this->Api_model->delete_broadcast_like_unlike($user_id,$broadcast_id);
            
             $JSON_ARR = array(
				'response'=>"your broadcast has been deleted successfully",
				'user_id'=>$user_id,
				'broadcast_id'=>$broadcast_id
				);
				print json_encode($JSON_ARR);
			    die();
		

    }
    
    ///////////////////////////////////////////////////////
    function broadcast_list_old()
    {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id');
		
		if( empty($user_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($broadcast_id))
        {
            
            $JSON_ARR[] = array(
					'response'=>"broadcast_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
		
		
		
		$sql = "SELECT *  FROM broadcast_comments where user_id ='".$user_id."' and broadcast_id='". $broadcast_id."'";
	
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$connents = $res->result_array();
					$comment_contents=[];
					foreach($connents as $row)
					{
					        $comment_contents[] = array(
							'user_id'=>$row['user_id'],
							'broadcast_id'=>$row['broadcast_id'],
							'user_comment'=>$row['user_comment']
							);
					
					}
					
					print json_encode($comment_contents);
					
				}
				else
				{
				    
				    $JSON_ARR[] = array(
					'response'=>"There is not comments in this user",
					);
					print json_encode($JSON_ARR);
				    
				    
				    
				    
				    
				}
        
    
    
    }
    
    
    
    function send_notification($user_id='',$broadcast_id='')
    {
       
      
       ///ini_set('display_errors', 'On');
     
        if(!empty($user_id))
        {
             
			
			$sql = "SELECT *  FROM user where user_id ='".$user_id."'";
				$res = $this->db->query($sql);
				
				$name_email="";
				if ($res->num_rows() > 0) 
				{
					$row_user = $res->result_array();
			        
			        
			        if(!empty($row_user[0]['name']))
			        {
			            $name_email=$row_user[0]['name'];
			        }
			        elseif(!empty($row_user[0]['email']))
			        {
			            $name_email=$row_user[0]['email'];
			        }
			        
				}
				else
				{
				    
				  $JSON_ARR[] = array(
					'response'=>"this user doesn't exist",
					);
					print json_encode($JSON_ARR);  
				    
				    exit;
				}
			
			
			$title="Live";
			require_once APPPATH."libraries/FCMPushNotification.php";
			$FCMPushNotification = new \BD\FCMPushNotification('AAAA6taNbw4:APA91bFqeAlrPgNUsVKgsJ8hiwOkry7WcDtjINQMOYhzspDVnnvKJpnHCtoy-bls5T8QUCc1gaAV4HYOqJJRyWKD0vViG5zMF-DJrwn4BAOhEcF2CRIA6FwccXkafsqZsGHAZQqK2h1-');
			

			
			$this->db->where('device_token!=', '');
			//$this->db->where('is_notification',0);
			$query 		=	 $this->db->get('user');
			$result=  $query->result_array();
			foreach($result as $row)
			{
				
				
				
    			$body=$name_email." is live click to join";
    			$aPayload = array(
    			'notification' => array(
    			'title' => $title,
    			'body'=> $body,
    			'data'=>array(
    			    'broadcast_id'=>$broadcast_id
    			    
    			    ),
    			'sound'=> 'default'
    				)
    			);
    			
    			

				$sDeviceToken =$row['device_token'];
				
				
				
				 $aResult = $FCMPushNotification->sendToDevice(
				$sDeviceToken,		
				$aPayload,
				$aOptions // optional
				);
				
				
			
				
				//echo "============".$aResult;
				//print_r($aPayload);
    			//exit;
			}	

                $JSON_ARR[] = array(
					'response'=>"notification sent successfully",
					);
					print json_encode($JSON_ARR);
        
        }
        else
        {
            $JSON_ARR[] = array(
					'response'=>"user id is required",
					);
					print json_encode($JSON_ARR);
            
        }
        
    }
    
    
    
    
    function create_broadcast_like_unlike()
    {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id');
		$type=abs($this->input->post('type'));
        
      
        
        if(!empty($user_id) and !empty($broadcast_id) )
        {
            
            $query = "SELECT COUNT(*) AS cntpost FROM broadcast_like_unlike WHERE broadcast_id=".$broadcast_id." and user_id=".$user_id;
            $res = $this->db->query($query);

    				$connents = $res->result_array();
                    $count= $connents[0]['cntpost'];
                    
                    if($count == 0){
                        
                        $data=array(
                            "broadcast_id"=>$broadcast_id,
    	                    "user_id"=>$user_id,	
    	                    "type"=>$type,
    	                    "created_date"=>date('Y-m-d H:i:s')
                            );
                        $this->Api_model->create_broadcast_like_unlike($data);
                }else {
                    
                        $data=array(
    	                    "type"=>$type
                            );
                    
                    $this->Api_model->update_broadcast_like_unlike($data,$user_id,$broadcast_id);
                }

                $JSON_ARR[] = array(
					'response'=>"Success"
					);
				print json_encode($JSON_ARR);
            
                
		
            
		     

            
            
        }
        else
        {
            
            $JSON_ARR[] = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
            
        }
        
        
    }
    
    
    
    function get_broadcast_like_unlike()
    {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id');
    
    
        if(!empty($user_id) and !empty($broadcast_id) )
        {
        
            $status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id=".$user_id." and broadcast_id=".$broadcast_id;
            $res = $this->db->query($status_query);
        	
        	$current_user_statue="";
        	if ($res->num_rows() > 0) 
        	{
            	$user_status = $res->result_array();
            	if($user_status[0]['type']==1)
                {
                    $current_user_statue='Like';
                }
                else
                {
                    $current_user_statue='Unlike';
                }
        	}

            


            $query_unlike = "SELECT count(*) as no_unlikecount FROM broadcast_like_unlike WHERE broadcast_id='".$broadcast_id."' and type=0";
            $res_unlike = $this->db->query($query_unlike);
            $unlikes= $res_unlike->result_array();
            $unlikes_count=abs($unlikes[0]['no_unlikecount']);
            
            $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$broadcast_id."' and type=1";
            $res_like = $this->db->query($query_like);
            $likes= $res_like->result_array();
            $likes_count=abs($likes[0]['no_likecount']);

            $contents[] = array(
    			'user_id'=>$user_id,
    			'broadcast_id'=>$broadcast_id,
    			'current_user_statue'=>$current_user_statue,
    			'total_like'=>$likes_count,
    			'total_unlike'=>$unlikes_count
    			);
					print json_encode($contents);
                
        
            

        }
        else
        {
           $JSON_ARR[] = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
        }
    
    
    }    
    
    
    
    
    function user_watch_list()
    {
        $user_id=0;
        $sql = "SELECT *  FROM user where email ='eric@happywatch99.com' and type=1";
		$res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{
		    $row = $res->result_array();
		    $user_id=$row[0]['user_id'];
		}

       
        if($user_id>0)
        {
            $this->broadcasting_list($user_id);
        }
        else
        {
            $JSON_ARR = array(
				'response'=>"This user have not super admin "
				);
			    print json_encode($JSON_ARR);
        }
        
        
        
    }
    
   
   
   
   
   function country_list()
    {
     
        $sql = "SELECT *  FROM country where 1 order by name ";
		$res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{
		    $row = $res->result_array();
		    $content_country=[];
		    foreach($row as $country)
		    {
		        
		        $content_country[]=array(
		            
		            "country_id"=>$country['country_id'],
		            "country_name"=>$country['name']
		            );
		    }
		    
		    $JSON_ARR = array(
				'launch'=>'country',
			    "content"=>$content_country	            
				);
			    print json_encode($JSON_ARR);
			    
		    
		}
        else
        {
          
          $JSON_ARR = array(
				'response'=>"There is not country in this list"
				);
			    print json_encode($JSON_ARR);

            
        }
   
   
    
    }
    
    
    
    
    
    function check_otp()
    {
        
      $otp=$this->input->post('otp');  
      $user_id=$this->input->post('user_id');
      
      if( empty($otp))
        {
            
            $JSON_ARR= array(
					'response'=>"OTP is required"
					);
				print json_encode($JSON_ARR);
                die();
        }
  

    if( empty($user_id))
    {
        
        $JSON_ARR= array(
				'response'=>"user_id is required"
				);
			print json_encode($JSON_ARR);
            die();
    }
  
        $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
    	$res = $this->db->query($sql);
    	if ($res->num_rows() > 0) 
    	{
    	    $row = $res->result_array();
    	    $user_otp=$row[0]['otp'];
    	    $email=$row[0]['email'];
    	
    	    if($user_otp==$otp)
    	    {
    	       $this->Api_model->otp_active_success($user_id);
    	       $this->Api_model->otp_delete($user_id);
    	       
    	       
    	       $country_name=$row[0]['country'];
    	       	
    	       	/*
    	       	if(!empty($country_id))
				{
					$array_countrye=$this->crud_model->GetCountryDetails($country_id);
					$country_name=$array_countrye[0]['name'];
					
				}
				
				
				if(empty($country_name))
				{
				    $country_name="";
				    
				}
				*/			
    	       
    	       
    	       $JSON_ARR = array(
					'response'=>"otp match success successfully",
					'user_id'=>$user_id,
					'Email'=>$email,
					"name"=>$row[0]['name'],
					"country_code"=>$row[0]['country_code'],
					"mobile"=>$row[0]['mobile'],
					"gender"=>$row[0]['gender'],
					"country_name"=>$country_name,
					"dob"=>$row[0]['dob'],

					);
    			print json_encode($JSON_ARR);
    			exit; 
    	       
    	       
    	        
    	    }
    	    else
    	    {
    	        $JSON_ARR= array(
					'response'=>"The OTP entered is incorrect"
					);
    			print json_encode($JSON_ARR);
    			exit; 
    	    }
    	    
    	    
    	}
        else
        {
            
           $JSON_ARR= array(
					'response'=>"This user does not exist"
					);
			print json_encode($JSON_ARR);
			exit; 
            
        }
        
        
        
        
        
    }
    
    
    
    function forget_password()
	{
		   $email_phone=$this->input->post('email_phone');

        	if( empty($email_phone))
            {
                
                $JSON_ARR= array(
        				'response'=>"Email or mobile is required"
        				);
        			print json_encode($JSON_ARR);
                    die();
            }
	    $sql="select * from  user where email='".$email_phone."' or mobile='".$email_phone."'  and status=1" ;
		$res = $this->db->query($sql);
        if ($res->num_rows() > 0) 
        {
			
			$result= $res->result_array();
			$otps=$this->crud_model->generateNumericOTP(6);
			
			$updateData=array(
					"otp"=>$otps
					);
			$this->db->where("user_id",$result[0]['user_id']);
			$this->db->update("user",$updateData);  

			
			$JSON_ARR=array(
				'user_id'=>intval($result[0]['user_id']),
				'useremail'=>$result[0]['email'],
				'mobile'=>$result[0]['mobile'],
	            'country_code'=>$result[0]['country_code'],
	            'otp'=>$otps,
	            'success' => 1
			);		
			
				if(!empty($result[0]['email']))
				{
				$new_message = "
								<html>
                                <head>
                                <title>Verification Code</title>
                                </head>
                                <body>
                                <h2>You have received your verification code.</h2>
                                <p>Your Account:</p>
                                <p>Name: ".$result[0]['name']."</p>
                                <p>Email: ".$result[0]['email']."</p>
                                <p>Your verification code is: </p>
                                <p>".$otps."</p> 
                                </body>
                                </html>
							";

							$email_msg	=	$new_message;
							$email_sub	=	"Forget Password Verification";
							$email_to	=	$result[0]['email'];
							$this->Email_model->do_email($email_msg , $email_sub , $email_to);
							
						
                       
                       require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
                		$message = 'Happy Watch 99 code: '.$JSON_ARR['otp'].'. Valid for 5 minutes.';
                		
                		$phone = $JSON_ARR['country_code'].$JSON_ARR['mobile'];
                
                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			///print_r($result->MessageId);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		}
                                       
                       
                       
                       /*
                       $from = '16268668783';
                        $to = $JSON_ARR['country_code'].$JSON_ARR['mobile'];
                        $message = array(
                            'text' => 'Happy Watch 99 Code : '.$JSON_ARR['otp'].'Valid For 5 minutes'
                        );
                         $response = $this->nexmo->send_message($from, $to, $message);

                    */
				}
			

			
			print json_encode($JSON_ARR);
            die();    			
			
			
        }
		else
		{
		    $JSON_ARR = array(
					'response'=>"This email or mobile does not exist",
					'user_id'=>"",
				    'useremail'=>"",
				    'mobile'=>"",
	                'country_code'=>"",
					'success' => 0
					);
			print json_encode($JSON_ARR);
			exit; 
		
		}

	}
    
   
   public function create_password()
	{
	  $password=$this->input->post('password');
	  $confirm_password=$this->input->post('confirm_password');
	  $user_id=$this->input->post('user_id');

	  if( empty($password))
        {
                
            $JSON_ARR= array(
				'response'=>"Password is required",
				'success'=>0
				);
			print json_encode($JSON_ARR);
            die();
        }
	  
	  
	  if (strlen($password) <6)
		{
            $JSON_ARR = array(
			'response'=>" New Password Must Be At Least 6 Character Long. Please Try Again.",
			'success'=>0
			);
			print json_encode($JSON_ARR);      
			die();
			
		}


	  if( empty($confirm_password))
        {
                
            $JSON_ARR= array(
				'response'=>"Confirm password is required",
				'success'=>0
				);
			print json_encode($JSON_ARR);
            die();
        }
	  
	  if( empty($user_id))
        {
                
            $JSON_ARR= array(
				'response'=>"user_id is required",
				'success'=>0
				);
			print json_encode($JSON_ARR);
            die();
        }
	  
	  $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() <= 0) 
		{
			
			
			$JSON_ARR= array(
					'response'=>"This user does not exist",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			die();
			
		}

	  
	  
	  if ($password==$confirm_password)
		{
			
		
	
            if(!empty($user_id))
            {
				$new_password_encrypted				=	sha1($password);
				$this->db->update('user', array('password'=>$new_password_encrypted), array('user_id'=>$user_id));
			
	           
    			 $JSON_ARR = array(
    			'response'=>"your password has been changed successfully",
    			'user_id'=>$user_id,
    			'success'=>1
    			);
    			print json_encode($JSON_ARR);	

            }
                
                
            }
			else
			{

    			$JSON_ARR = array(
    			'response'=>"password and confirm password should be same",
    			'success'=>0
    			);
    			print json_encode($JSON_ARR);  
    			
				
			}
  
	    
	}

	


        public function add_video_broadcast()
        {
            $broadcastchannel_id=$this->input->post('broadcastchannel_id');
        	$broadcastchannel_name=$this->input->post('broadcastchannel_name');
        	$user_id=$this->input->post('user_id');
        	
        	
        	if( empty($broadcastchannel_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Broadcastchannel_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
            if( empty($broadcastchannel_name))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Broadcastchannel name is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	              
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
            
            
            $data=array(
                'broadcastchannel_id'=>$broadcastchannel_id,
                'broadcastchannel_name'=>$broadcastchannel_name,
                'user_id'=>$user_id 
                );
            
            $this->db->insert('video_broadcast_list', $data);
    		$broadcast_id = $this->db->insert_id();
    		
    		$JSON_ARR[] = array(
			'response'=>"your data has been saved successfully",
			'success'=>1,
			'broadcastchannel_id'=>$broadcastchannel_id,
            'broadcastchannel_name'=>$broadcastchannel_name,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR);
            

        }


        public function get_video_broadcast()
        {   
          
          $broadcastchannel_id=$this->input->post('broadcastchannel_id');
        $user_id=$this->input->post('user_id');
          if( empty($broadcastchannel_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Broadcastchannel_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
                  
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
            
          
          
          
          
          $result=  $this->Api_model->get_video_broadcast();
          if (!empty($result))
          {
            $JSON_ARR[] = array(
			'broadcastchannel_id'=>$result[0]['broadcastchannel_id'],
            'broadcastchannel_name'=>$result[0]['broadcastchannel_name'],
            'user_id'=>$result[0]['user_id'] 
			);
			print json_encode($JSON_ARR);

          }
        }
   
 
   
   public function add_broadcast_view()
        {
            $broadcast_id=$this->input->post('broadcast_id');
        	$type=$this->input->post('type');
        	$user_id=$this->input->post('user_id');
        	
        	
        	
        		if( empty($broadcast_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Broadcast id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
            if( empty($type))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Type is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	              
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
        
        
         $data=array(
                 	'user_id'=>$user_id,
                 	'broadcast_id'=>$broadcast_id,
                 	'type'=>$type,
                 	'datetime'=>date('Y-m-d H:i:s')
                );
            
            $this->db->insert('broadcast_view', $data);
    		$broadcast_id = $this->db->insert_id();
    		
    		$JSON_ARR[] = array(
			'response'=>"your views has been saved successfully",
			'success'=>1,
			'broadcast_id'=>$broadcast_id,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR);

		}
		
		 
	public function share_count()
	{
		$broadcast_id=$this->input->post('broadcast_id'); 	
		$user_id=$this->input->post('user_id'); 
		if( empty($broadcast_id))
        {
            $JSON_ARR= array(
				'response'=>"Broadcast id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		 
		 
		  $data=array(
                 	'user_id'=>$user_id,
                 	'broadcast_id'=>$broadcast_id,
                 	'share_date'=>date('Y-m-d')
                );
		 
		 $result=  $this->Api_model->save_share_info($data);
		 
	
		 if($result)
		 {
		    $JSON_ARR = array(
			'response'=>"Share details has been saved successfully",
			'broadcast_id'=>$broadcast_id,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR); 
		     
		 }
		 else
		 {
		     $JSON_ARR = array(
			'response'=>"Share details has not been saved successfully"
			
			);
			print json_encode($JSON_ARR); 
		     
		     
		     
		 }
		 	 
	}
	
	

    
    public function signup_for_tv()	
	{	
			
		$email=$this->input->post('email');
		$password=$this->input->post('password');
		$name=trim($this->input->post('name'));
		
		
			if( empty($email))
            {
                
                $JSON_ARR= array(
    					'response'=>"Email is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
				
			if( empty($password))
            {
                
                $JSON_ARR= array(
    					'response'=>"Password is required",
    					'success'=>0
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
			
			
		
			
			if(	isset($email)  )
			{
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR= array(
				 'response'=>"Not valid email",
				 'success'=>0
				 );
							print json_encode($JSON_ARR);
							die();
				}


				$sql = "SELECT *  FROM user where email ='".trim($email)."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$JSON_ARR = array(
							'response'=>"This email already exists",
							'success'=>0
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
					
					if(empty($name))
					{
					    $name="";
					}
					
					$data=array(
					"email"=>$email,
					"password"=>sha1($password),
					"name"=>$name,
					'signup_type'=>2,
					'status'=>1,
					"created_date"=>date('Y-m-d')
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR = array(
									'response'=>"Welcome! you have signed up successfully...",
									'success'=>1,
									'name'=>$name,
									'user_id'=>intval($signup_id),
									);

							print json_encode($JSON_ARR);
								}
				
				}

			}
			else
			{
					$JSON_ARR[] = array(
									'response'=>"Wrong data",
									'success'=>0,
									);
							print json_encode($JSON_ARR);
				
			}
		
	}	
	
	public function signin_for_tv()	
	{		
			
			$email=	$this->input->post('email');
			$password=$this->input->post('password');
			
			
			if( empty($email))
            {
                
                $JSON_ARR= array(
    					'response'=>"Email is required",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
				
			if( empty($password))
            {
                
                $JSON_ARR= array(
    					'response'=>"Password is required",
    					'is_login'=>"NO"
    					);
    				print json_encode($JSON_ARR);
                    die();
                
            }
			
			
			
			
			
			
			if(	isset($email) &&  isset($password)  )
			{	
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR = array(
				 'response'=>"Not valid email",
				 'is_login'=>"NO"
				 );
							print json_encode($JSON_ARR);
							die();
							
				}
				$result_signin= $this->Api_model->user_signin();

				if(!empty($result_signin))
				{
					$JSON_ARR=array(
							'user_id'=>intval($result_signin[0]['user_id']),
							'useremail'=>$email,
				            'name'=>$result_signin[0]['name'],
							'is_login'=>"YES"		
							
							);		
				
							
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR=array(
							'response'=>" Login Failed! Please Try Again. ",
							'useremail'=>"",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
				}

			}
			else
			{
					$JSON_ARR = array(
									'response'=>"Wrong data",
									'is_login'=>"NO"
									);
					print json_encode($JSON_ARR);
			}

	
	}
	
	
	
	public function create_channel()
	{

        $user_id=	(empty($this->input->post('user_id'))) ? '' : $this->input->post('user_id');

		if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
	    $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0) 
		{
			//$row = $res->result_array();
			//return $row;
			$JSON_ARR= array(
					'response'=>"This user does not exist",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();
		}
		
		
		$sql = "SELECT *  FROM broadcast_live_channel where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{
			//$row = $res->result_array();
			//return $row;
			$JSON_ARR= array(
					'response'=>"This user already created channel",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();
		}
		
	
       $channel_name='Happywatch99-channel-'.$user_id;
       $stream_name='stream-watch99-'.$user_id;
       
        $cdnvideo_token=$this->get_cdnvideo_token();
       
       
       
       ////////////////////////////////////////////////////////////////
			$request_array=array
			(
			  "name"=>$channel_name,
			  "type"=>"RTMP-publish" ,
			  "streams"=>array('strm'.$user_id=>array('name'=>$stream_name,'resolution'=>'720p'))
			);
		
				$postdata = json_encode($request_array,JSON_UNESCAPED_SLASHES);

				$url='https://api.cdnvideo.ru/cdn/api/v1/'.ACCOUNT_NAME.'/resource/live';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,
				array(
				'cdn-auth-token:'. $cdnvideo_token,
				'Content-Type: application/json'
	            ));
				$result = curl_exec($ch);
				curl_close($ch);
				$response=json_decode($result);
				$status=$response->status;
				$task_id=$response->task_id;
                $resource_id=$response->resource_id;
                
                if(!empty($resource_id))
                {
                        $url='https://api.cdnvideo.ru/cdn/api/v1/'.ACCOUNT_NAME.'/resource/live/'.$resource_id;
        				$ch2 = curl_init($url);
        				curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        				curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        				curl_setopt($ch2, CURLOPT_HTTPHEADER,
        				array(
        				'cdn-auth-token:'. $cdnvideo_token,
        				'Content-Type: application/json'
        	            ));
        				$result_get = curl_exec($ch2);
        				curl_close($ch2);
        				$response_channel=json_decode($result_get);
                      
                        foreach($response_channel->settings as $row)
                        {
                        
                            $show_dist_protocol=$row->dist_protocol;
                            $show_domain=$row->domain;
                            $show_application=$row->application;
                            $show_master_stream=$row->master_stream;
                            $show_pub_protocol=$row->pub_protocol;
                            $show_primary=$row->primary;
                            $show_backup  =$row->backup;
                        }
                        

                        foreach($response_channel->streams as $row1)
                        {
                            $show_stream_name=$row1->stream_name;
                            $show_password=$row1->password; 	
                        }
                         
                        
                        


                }
                else
                {
                   $JSON_ARR= array(
					'response'=>$response,
					'success'=>0
					);
        			print json_encode($JSON_ARR);
        			 die(); 
                }
       
       
       
       ////////////////////////////////////////////////////////////////////////
       /*
       require_once(APPPATH.'libraries/aws/configuration.php');
   
       $result = $IVSClient->createChannel([
        'authorized' => false,
        'latencyMode' => 'NORMAL',
        'name' => $channel_name,
        'type' => 'BASIC',
        ]);
       
   
        $channel_arn=   $result['channel']['arn'];
        $channel_name=   $result['channel']['name'];
        $channel_Ingest_endpoint=   $result['channel']['ingestEndpoint']; 
        
        $channel_playbackurl=   $result['channel']['playbackUrl']; 
        
        
        
        $streamkey_arn=$result['streamKey']['arn'];
        $streamkey_channel_arn=$result['streamKey']['channelArn'];
        
        $streamkey_value=$result['streamKey']['value'] ;  
        */
        
        

		$data=array(
    		'user_id'=>$user_id,
    	    'channel_name'=>$channel_name,
    	    'channel_details_jsoan'=>$result_get,
            'channel_task_id'=>$task_id,
            'channel_status'=>$status,
            'resource_id'=>$resource_id,
            
            'show_dist_protocol'=>$show_dist_protocol,
            'show_domain'=>$show_domain,
            'show_application'=>$show_application,
            'show_master_stream'=>$show_master_stream,
            'show_pub_protocol'=>$show_pub_protocol,
            'show_primary'=>$show_primary,
            'show_backup'=>$show_backup,
            'show_stream_name'=>$show_stream_name,
            'show_password'=> $show_password	
			);
			$broadcast_live_id= $this->Api_model->create_channel($data);
			if($broadcast_live_id)
			{
	        
	        $broadcast_row= $this->Api_model->get_channel_list($broadcast_live_id);
	
	            
	            
	            $array_channel_details= array(
    				'show_dist_protocol'=>$broadcast_row[0]['show_dist_protocol'],
                    'show_domain'=>$broadcast_row[0]['show_domain'],
                    'show_application'=>$broadcast_row[0]['show_application'],
                    'show_master_stream'=>$broadcast_row[0]['show_master_stream'],
                    'show_pub_protocol'=>$broadcast_row[0]['show_pub_protocol'],
                    'show_primary'=>$broadcast_row[0]['show_primary'],
                    'show_backup'=>$broadcast_row[0]['show_backup'],
                    'show_stream_name'=>$broadcast_row[0]['show_stream_name'],
                    'show_password'=>$broadcast_row[0]['show_password']	
				
				);
	            
	            
	            //'channel_id'=>$broadcast_row[0]['channel_id'],
                $JSON_ARR = array(
    					'response'=>"broadcast live data saved successfully",
    					'success'=>1,
    					'channel_name'=>$channel_name,
    					'user_id'=>$user_id,
                	    'channel_detrail'=>$array_channel_details
    					);
    
    			print json_encode($JSON_ARR);
			}	 
				 
	    
	    
	}
	
	
	
	function start_broadcast_live()
    {

        $post_privacy_type=$this->input->post('post_privacy_type');
		$user_id=$this->input->post('user_id');
        $playback_url=$this->input->post('playback_url');	
		$cat_id=	(empty($this->input->post('cat_id'))) ? 0 : $this->input->post('cat_id');
		
		$content_description=(empty($this->input->post('content_description'))) ? '' : $this->input->post('content_description');
		
		
		 if($post_privacy_type>2 OR $post_privacy_type<0  )
		 {
            $JSON_ARR= array(
				'response'=>"Privacy Type is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
			
		if( empty($content_description))
        {
            $JSON_ARR= array(
				'response'=>"Description is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
			




        if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
	    $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0) 
		{
			//$row = $res->result_array();
			//return $row;
			$JSON_ARR= array(
					'response'=>"This user does not exist",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();
		}
		
        if( empty($playback_url))
        {
            $JSON_ARR= array(
				'response'=>"Playback url is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        

            $data['user_id']=$user_id;
            $data['cat_id']=$cat_id;
            $data['content_description']=$content_description;
            $data['created_date']=date('Y-m-d H:i:s');
            $data['broadcast_video']= $playback_url;
            $data['live_status']=1;
            $data['post_privacy_type']=$post_privacy_type;
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();


            $JSON_ARR = array(
				'response'=>"your data has been saved successfully",
				'success'=>1,
				'broadcast_id'=>$broadcast_id,
				'user_id'=>$user_id,
				'cat_id'=>$cat_id,
				'playback_url'=>$playback_url
				);
				print json_encode($JSON_ARR);

            
      
        
        
        
    }
    

	
	
	




	
	
	/*
 	public function smstest()
 	{
 	    $from = '16268668783';
         $to = '+85512812598';
         $message = array(
             'text' => 'Happy Watch 99 Code : 123456 Valid For 5 minutes'
         );
	    
 	   $response = $this->nexmo->send_message($from, $to, $message); 
 	
 	print_r($response);
 	    
 	    
 	}
	
	*/

	
	
	public function save_channel_video()
 	{
        
        //strtotime(date("Y-m-d H:i:s"))
        $user_id=$this->input->post('user_id');
        //$channel_id=$this->input->post('channel_id');
        $broadcast_id=$this->input->post('broadcast_id');
        
         
        
        
        
        if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
	    $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0) 
		{
			$JSON_ARR= array(
					'response'=>"This user does not exist",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();
		}
        
/*
        if( empty($channel_id))
        {
            $JSON_ARR= array(
				'response'=>"channel id is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
  */
        if(empty($_FILES['upload']['name']))
        {
            $JSON_ARR= array(
				'response'=>"channel video is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        if( empty($broadcast_id))
        {
            $JSON_ARR= array(
				'response'=>"Broadcast id is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        



        //require_once(APPPATH.'libraries/aws/s3bucket_configuration.php');

        ///$bucket = 'happywatch';
        $key=$_FILES['upload']['name'];
       
       if(!empty($_FILES['upload']['name']))
		{	
                //////$uniquesavename=time().uniqid(rand());
        		
        $file_extension=pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        		
        $channel_video_name='channel_'.$broadcast_id.'_'.strtotime(date("Y-m-d H:i:s")).'_'.$user_id.'.'.$file_extension;
        		
        		
        	
        		
        		$platformcraft_token=$this->get_platformcraft_token();
        		
        		
        		
        	
        		
        		///////////////////save video to cdn//////////////////////////
        		

        		
        		$postdata = $_FILES['upload']['tmp_name'];
                

				$url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/channel_video/'.$channel_video_name;
				
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				curl_setopt($ch, CURLOPT_POST, 1);
                $args['file'] = new CurlFile($_FILES['upload']['tmp_name'],'video/mp4',$_FILES['upload']['name']);
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, $args);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,
				array(
				'Authorization: Bearer '.$platformcraft_token,
				'Content-Type: multipart/form-data'
				
	            ));
				$result = curl_exec($ch);
				curl_close($ch);
				$response=json_decode($result);

        
        		///////////////////////////////////////////////////
        		/*

        		if(!empty($broadcastid))
                {        		
               
                    $key=$channel_video_name;    
        			$result_thumb=$s3Client->putObject(array(
        				'Bucket'     => $bucket,
        				'SourceFile' => $_FILES['channel_video']['tmp_name'],
        				'Key'        => $key
        			));
	        
                }
                
                */
                
                ///////////////full_videothumo_url of cdn7 /////////////////
                $full_video_thumb_url="";
                require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                  $movie=$_FILES['upload']['tmp_name'];
                 $extension='.jpeg';
                $channel_video_thumb_name='channel_thumb_'.$broadcast_id.'_'.strtotime(date("Y-m-d H:i:s")).'_'.$user_id.$extension;
                $thumbnail = 'assets/global/channel_thumb/'.$channel_video_thumb_name;
                $full_video_thumb_url=$channel_video_thumb_name;
                //$thumbnail = 'assets/global/temp_thumb_channel_video/thumbnail.jpeg';
                $video = $ffmpeg->open($movie);
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $frame->save($thumbnail);
                
                
                /*
                
                $extension='.jpeg';
                  
                	$channel_video_thumb_name='channel_thumb_'.$broadcast_id.'_'.strtotime(date("Y-m-d H:i:s")).'_'.$user_id.$extension;

            				$url2='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/channel_video_thumb/'.$channel_video_thumb_name;
            				
            				$ch = curl_init($url2);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            				curl_setopt($ch, CURLOPT_POST, 1);
                            $args['file'] = new CurlFile($thumbnail,'image/jpeg','thumbnail.jpeg');
                            
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            
            				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            				curl_setopt($ch, CURLOPT_HTTPHEADER,
            				array(
            				'Authorization: Bearer '.$platformcraft_token,
            				'Content-Type: multipart/form-data'
            				
            	            ));
            				$result2 = curl_exec($ch);
            				curl_close($ch);
            				$response2=json_decode($result2);
                            
                             $full_video_thumb_url=$response2->download_url;
                             $full_video_thumb_url='https://'.$full_video_thumb_url;
                      
                  
                 */ 
   
                
                
                
                /*
                if(!empty($_FILES['upload_thumb']['name']))
    		    {	
                    //////$uniquesavename=time().uniqid(rand());
        		
        		
        		 $_FILES['upload_thumb']['tmp_name']=$this->compressImage($_FILES['upload_thumb']['tmp_name'],$_FILES['upload_thumb']['tmp_name'],50);
                   
        		
                        $extension=pathinfo($_FILES['upload_thumb']['name'], PATHINFO_EXTENSION);
                    		
                    	$channel_video_thumb_name='channel_thumb_'.$broadcast_id.'_'.strtotime(date("Y-m-d H:i:s")).'_'.$user_id.'.'.$extension;
                    		
            
            				$url2='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/channel_video_thumb/'.$channel_video_thumb_name;
            				
            				$ch = curl_init($url2);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            				curl_setopt($ch, CURLOPT_POST, 1);
                            $args['file'] = new CurlFile($_FILES['upload_thumb']['tmp_name'],'image/jpeg',$_FILES['upload_thumb']['name']);
                            
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            
            				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            				curl_setopt($ch, CURLOPT_HTTPHEADER,
            				array(
            				'Authorization: Bearer '.$platformcraft_token,
            				'Content-Type: multipart/form-data'
            				
            	            ));
            				$result2 = curl_exec($ch);
            				curl_close($ch);
            				$response2=json_decode($result2);
                            
                             $full_video_thumb_url=$response2->download_url;
                        $full_video_thumb_url='https://'.$full_video_thumb_url;
                            
                
		            }   
                
                */
                
                
                
                
                /////////////////////////////////////////////////
                if(!empty($response->id))
                {
                    
                    
                   $full_video_url=$response->download_url;
                   
                   $full_video_url='https://'.$full_video_url;
                   
                    ///$broadcast_hls =$response->hls; 
                    
                    $broadcast_hls="";
                    if(!empty($response->hls))
                    {
                    $broadcast_hls="https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/channel_video/".$channel_video_name."/playlist.m3u8";
                    
                    }
                    
                   $data=array(
        		 	'user_id'=>$user_id,
        		    'broadcast_video'=>$channel_video_name,
        		    'broadcast_video_hls_url'=>$broadcast_hls,
        		    'live_status'=>0,
        		    'full_video_url'=>$full_video_url,
        		    'full_video_thumb_url'=>$full_video_thumb_url,
        		    'created_date'=>date('Y-m-d H:i:s')
		            );
        		
        		
        		$this->db->update('broadcast_details', $data, array('id'=>$broadcast_id));
        		
        		$broadcastid=$this->db->affected_rows();
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    $JSON_ARR = array(
    				'response'=>"Channel video has been saved successfully",
    				'success'=>1,
    				'user_id'=>$user_id,
    				'broadcast_id'=>$broadcast_id
    				);
				    print json_encode($JSON_ARR);
                }
                else
                {
                    
                    $JSON_ARR = array(
    				'response'=>$response,
    				'success'=>0
    				
    				);
				    print json_encode($JSON_ARR);
                }
                
		}	
 	}
	
	
	
	
	
	
	
	
	function delete1_broadcast11111_old()
    {
        $broadcast_id=$this->input->post('broadcast_id');

        if( empty($broadcast_id))
        {
            $JSON_ARR= array(
				'response'=>"Broadcast id is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
        $this->db->where('live_status', 1);
		$this->db->where('id', $broadcast_id);
		$broadcast_delete=$this->db->delete('broadcast_details'); 
        
        if(!empty($broadcast_delete))
        {
                $JSON_ARR= array(
				'response'=>"This broadcast has been deleted successfully",
				'broadcast_id'=>$broadcast_id,
				'success'=>1
				
				);
			    print json_encode($JSON_ARR);
                
            
        }
        else
        {
            
                $JSON_ARR= array(
				'response'=>"This broadcast has not been deleted successfully",
				'broadcast_id'=>$broadcast_id,
				'success'=>0
				
				);
			    print json_encode($JSON_ARR);

            
        }

	
    }	
	
	
	
	
	
	
		public function get_artist_Season()
	{
		$live_id= $this->uri->segment(3);
		if(empty($live_id) )
		{
			$JSON_ARR[] = array(
			'response'=>"Artist id not found"
			);
				print json_encode($JSON_ARR);
				die();
		}	
		
		$result_season= $this->Api_model->get_artist_season_of_series($live_id);
		
	
		
		$season_content=[];
		foreach($result_season as $row_season )
		{
			$result_episode= $this->Api_model->get_artist_episode_of_season($row_season['artist_season_id']);
			
		
			$episode_content=[];
			foreach($result_episode as $row_episode)
			{
				
				/*
				$result_ad_time= $this->Api_model->Get_episodeAdvertisement_Time($row_episode['episode_id']);

				$adtime_content=[];
				if(!empty($result_ad_time))
				{
    				foreach($result_ad_time as $row_ad_time )
    				{
    				  $adtime_content[]= array(
					 "ad_time_id" => $row_ad_time['id'],
					 "videos_id"=>$row_ad_time['videos_id'],
					 "add_time"=>$row_ad_time['add_time']
				      );	
    				    
    				}
				}
				*/
				
				
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "artist_song_episode_id" => $row_episode['artist_episode_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['artist_episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url']
				 
				);	
			}
			
			$season_content[]= array(
			"season_id" => $row_season['artist_season_id'],
			"name" => $row_season['title'],
			"episode_content"=>$episode_content
			);	
		

		}

		$ALL_JSON_ARR = array(
				"launch"=>'Artist',
				'contents'=>$season_content
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	
	
	
	
	
	
	
	
	public function tests3bucket_test()
 	{
        require_once(APPPATH.'libraries/aws/s3bucket_configuration.php');
        
        $bucket = 'happywatch';
        $key=$_FILES['thumb']['name'];
       
			$result_thumb=$s3Client->putObject(array(
				'Bucket'     => $bucket,
				'SourceFile' => $_FILES['thumb']['tmp_name'],
				'Key'        => $key
			));
       
        

 	}
 	
 	
 	
 	public function testsms()
 	{
 	     $from = '16268668783';
 	    
 	    $to = '+918010255769';
                        
                        $message = array(
                            'text' => 'Happy Watch 99 Code : 123456  Valid For 5 minutes'
                        );
                         $response = $this->nexmo->send_message($from, $to, $message);
 	    
 	    print_r($response);
 	}
 	
 	 
 	
 	
 	
 	public function test1()
 	{
 	    
 	     $url='https://api.cdnvideo.ru/cdn/api/v1/'.ACCOUNT_NAME.'/resource/live/4018300281906177909_f0rau5hn6iw';
        				$ch2 = curl_init($url);
        				curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        				curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        				curl_setopt($ch2, CURLOPT_HTTPHEADER,
        				array(
        				'cdn-auth-token:'. CDN_AUTH_TOKEN,
        				'Content-Type: application/json'
        	            ));
        				$result_get = curl_exec($ch2);
        				curl_close($ch2);
        				$response_channel=json_decode($result_get);
                        
                        
                        echo '<pre>';
                        print_r($response_channel);
                        
                        echo "=============";
                        
                        //print_r($response_channel->settings);
                        
                        
                        foreach($response_channel->settings as $row)
                        {
                            //print_r($row);
                            //exit;
                            
                        echo "==". $show_dist_protocol=$row->dist_protocol;
                        echo "==".$show_domain=$row->domain;
                        echo "==".$show_application=$row->application;
                        echo "==".$show_master_stream=$row->master_stream;
                        echo "==".$show_pub_protocol=$row->pub_protocol;
                        echo "==".$show_primary=$row->primary;
                        echo "==".$show_backup  =$row->backup;
                                        
                            //print_r($row->domain);
                        }
                        
                        
                       // print_r($response_channel->streams);
                        
                        
                        foreach($response_channel->streams as $row1)
                        {
                         // print_r($row1->stream_name);
                         
                         
                         echo "***". $show_stream_name=$row1->stream_name;
                        echo "***".$show_password=$row1->password; 	
                         
                         
                    }
                        
                        
                        exit;
 	    
 	    
 	    
 	}
 	
 	
 	public function get_platformcraft_token()
 	{

                $postdata=  platformcraft_login_pass;
				$url='https://auth.platformcraft.ru/token';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,
				array(
				'Content-Type: application/x-www-form-urlencoded',
				'Content-Type: application/json'
	            ));
				$result = curl_exec($ch);
				curl_close($ch);
				$response=json_decode($result);
				
				//$status=$response->status;
				//$task_id=$response->task_id;
                //$resource_id=$response->resource_id;
                
        
        //print_r($response);
 	    
 	    if(!empty($response->access_token))
 	    {
 	    return $response->access_token;
 	    }
 	    else
 	    {
 	        return '';
 	    }
 	    
 	}
 	
 	
 	public function get_cdnvideo_token()
 	{
 	
 	
 	            $postdata=cdnvideo_login_pass;
				$url='https://api.cdnvideo.ru/app/oauth/v1/token/';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,
				array(
				'Content-Type: application/json'
	            ));
				$result = curl_exec($ch);
				curl_close($ch);
				$response=json_decode($result);
				
				//$status=$response->status;
				//$task_id=$response->task_id;
                //$resource_id=$response->resource_id;
                
        
        //print_r($response);
 	    
 	    if(!empty($response->token))
 	    {
 	    return $response->token;
 	    }
 	    else
 	    {
 	        return '';
 	    }
 	    
 	    
 	}
 	
 	
 	
 	public function register_old()
	{
		error_reporting(E_ALL);

            // Display errors in output
           ini_set('display_errors', 1);
		//require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
		require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
		
		
		
		$message = 'This message is sent from a Amazon SNS code sample.';
		$phone = '918010255769';

		try {
			$result = $s3Client->publish([
				'Message' => $message,
				'PhoneNumber' => $phone,
			]);
			
			//print_r($result->MessageId);
			//var_dump($result);
		echo "============";
		    print_r($result);
		    
		} catch (AwsException $e) {
			// output error message if fails
			error_log($e->getMessage());
		} 
		
		
		
	}
 	
 	
 	
 	
 	function compressImage($filename, $destination, $ext="") 
 	{

 	if (preg_match('/jpg|jpeg/i', $ext)) {
        //$filename = imagecreatefromjpeg($filename);
        
        //$imageTmp=$filename;
    } else if (preg_match('/png/i', $ext)) {
        $filename = imagecreatefrompng($filename);
    } else if (preg_match('/gif/i', $ext)) {
        $filename = imagecreatefromgif($filename);
    } else 
    {
 	   $filename = imagecreatefromjpeg($filename);
    }  
 	   



    // quality is a value from 0 (worst) to 100 (best)
    //imagejpeg($imageTmp, $outputImage, $quality);
    //imagedestroy($imageTmp);
 	    
 	    
 	    //$filename=$imageTmp;
 	    
 	    
 	    
 	    
 	    //$width = 440; 
        //$height = 310; 
        
        //$width = 840; 
        $width = 600; 
        $height = 500; 
        
        // File type 
        header('Content-Type: image/jpg'); 
          
         

          
        // Get new dimensions 
        list($width_orig, $height_orig) = getimagesize($filename); 
        
        
        $ratio_orig = $width_orig/$height_orig; 
          
        if ($width/$height > $ratio_orig) { 
            $width =( $height*$ratio_orig)+100; 
        } else { 
            $height = $width/$ratio_orig; 
        } 
        //$width = 600; 
        // Resampling the image  
        $image_p = imagecreatetruecolor($width, $height); 
        
       imageresolution($image_p, 72);

        
        $image = imagecreatefromjpeg($filename); 
          
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                $width, $height, $width_orig, $height_orig); 
          
        // Display of output image 
        imagejpeg($image_p, $destination, 100); 
 	      return $destination;
 	    
 	}
 	

 	
 	
 	
 	
 	
 	
 	/*
 	function compressImage($filename, $destination, $type="") 
 	{
 	    $width = 440; 
        $height = 310; 
          
         if($type=='png')
         {
          $filename = imagecreatefrompng($filename);
         }
        // File type 
        header('Content-Type: image/jpg'); 
          
        // Get new dimensions 
        list($width_orig, $height_orig) = getimagesize($filename); 
          
        $ratio_orig = $width_orig/$height_orig; 
          
        if ($width/$height > $ratio_orig) { 
            $width = $height*$ratio_orig; 
        } else { 
            $height = $width/$ratio_orig; 
        } 
          
        // Resampling the image  
        $image_p = imagecreatetruecolor($width, $height); 
        $image = imagecreatefromjpeg($filename); 
          
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                $width, $height, $width_orig, $height_orig); 
          
        // Display of output image 
        imagejpeg($image_p, $destination, 100); 
 	      return $destination;
 	    
 	}
 	
 	
 	*/
 	
 	function compressImage_old($source, $destination, $quality) { 
    // Get image info 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
     
    // Create a new image from file 
    switch($mime){ 
        
        case 'image/jpg':
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
    } 
     
    // Save image 
    imagejpeg($image, $destination, $quality); 
     
    // Return compressed image 
    return $destination; 
} 

 	
 	
 	
 	function get_broadcast_details_details()
 	{
 	     
 	     $broadcast_id=$this->input->post('broadcast_id');
 	    $user_id=$this->input->post('user_id');

 	     if(empty($broadcast_id))
 	     {
 	         $JSON_ARR= array(
				'response'=>"broadcast Id is required"
				);
    			print json_encode($JSON_ARR);
    			die();
 	     }
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        } 
 	     
 	     
 	  $sql = "select BD.*,C.name,U.name as user_name from broadcast_details BD 
        left join category C on BD.cat_id= C.cat_id
        left join user U on BD.user_id=U.user_id
        where 1  and BD.id='".$broadcast_id."'";
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
    	    $broadcast_contents=[];
			$comment_contents=[];
    	    
    	    
    	    foreach( $broadcasts as $row)
    	    {
                 if($row['live_status']==0 or $row['live_status']=1) 
			   {
			   
        			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                        $res_like = $this->db->query($query_like);
                        $likes= $res_like->result_array();
                        $likes_count=abs($likes[0]['no_likecount']);
                    			    
                		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".$row['id'];
                        $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue="false";
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue='true';
                            }
                            else
                            {
                                $current_user_statue='false';
                            }
                    	}
            	    
                			    //$sql = "SELECT *  FROM broadcast_comments where user_id ='".$row['user_id']."' and broadcast_id='".$row['id']."'";
                				
                				$sql = "SELECT *  FROM broadcast_comments where  broadcast_id='".$row['id']."'";
                				
                				$comment_contents=[];
                				$res = $this->db->query($sql);
                				if ($res->num_rows() > 0) 
                				{
                					
                					
                					$connents = $res->result_array();
                					
                					foreach($connents as $connent)
                					{
                					        $comment_contents[] = array(
                							'user_id'=>$connent['user_id'],
                							'broadcast_id'=>$connent['broadcast_id'],
                							'user_comment'=>$connent['user_comment'],
                							'created_date'=>$connent['created_date'],	  	
                							);
                					
                					}
                				}
                			    
        			   // echo "=======".$row['created_date'];
        			    
        			    
        			    
        			   $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			        /*
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			        */
        			       
        			           $images[]='https://'.$img;
        			           
        			       }            
        			        
        			        
        			        
        			    }
        			    $array_videos=[];
        			    
        			 
			    
			    
                        if(!empty($row['full_video_url']) and $row['live_status']==0) 
    			        {   
                           
        			        $array_videos[]=$row['full_video_url'];
    			        }
        			    elseif($row['live_status']==1)
        			    {
        			        $array_videos[]=$row['broadcast_video'];
        			        
        			        
        			        
        			        
        			        
        			    }
        			    
        			    else
        			    {
        			    
        			    foreach($array_broadcast_video as $video )
        			    {
        			        
        			       if(!empty($video))
        			       {
        	 
            			        /*
            			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                {    
                                    $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                }    
                                
                                */
                                
                                
                                $array_videos[]='https://'.$video;
        			       }  
        			        
        			        
        			        
        			        
        			    }
        			    
        			    }
        			    
        			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
        				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
        			    
        			   
        			$array_videosthumb=[];   
        			
        			
        			if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    $array_videosthumb[] =$row['full_video_thumb_url'];
        			}
        			elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			                  $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			       if(!empty($thumb))
                			       {
                                        
                                        /*
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                        
                                        
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        }    
                	                    */		       
	                
	                                    $array_videosthumb[]=    'https://'.$thumb;
	                                }
                			       ///print_r($array_videosthumb);
                			    }   
			        }
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			    $sharing_count=0;
        			    $view_count=0;
        			        if(!empty($row['id']))
        			        {
        			        
        			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
        			   
        			        $view_count=$this->Api_model->broadcast_view($row['id']); 
        			        }
        			   
        			   
        			   
        
                       //// echo $this->db->last_query();
        
        			    $broadcast_contents[] = array(
        					'broadcast_id'=>$row['id'],
        					'user_id'=>$row['user_id'],
        					'name'=>$row['user_name'],
        					'category_id'=>$row['cat_id'],
        					'category_name'=>$row['name'],
        					'image'=>$images,
        					'video'=>$array_videos,
        					'video_thumbnail'=>$array_videosthumb,
        					'created_date'=>$row['created_date'],
        					'content_description'=>	$row['content_description'],
        					'comment_contents'=>$comment_contents,
        					
        					'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
        					
        					'view_count'=>$view_count,
        					'sharing_count'=>$sharing_count,
        					'self_like'=>$current_user_statue,
        					'like_count'=>$likes_count
        					
        					);
        			
			
			    }
			    
    	
    	    }
     	     		
    			
    			
    			
    			
    		$ALL_JSON_ARR= array(
				'launch'=>'broadcast_details',
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);
            
    			die();
    			
    		}

 	    
 	    
 	}
 	
 	
 	public function add_broadcast_streaming_comments()
    {
        $comment_content=$this->input->post('comment_content');
        $user_id=$this->input->post('user_id');
        $broadcast_id=$this->input->post('broadcast_id');
        
         if(empty($broadcast_id))
 	     {
 	         $JSON_ARR= array(
				'status'=>false,
				 'message'=>"broadcast Id is required"
				);
    			print json_encode($JSON_ARR);
    			die();
 	     }
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        } 
 	     
        
        
        
            $data=array(
        			"comments"=>$comment_content,
        			"user_id"=>$user_id,
        			"broadcast_id"=>$broadcast_id,
        			"created_date"=>date("Y-m-d H:i:s")
        			);	
    	  $save=  $this->Api_model->save_broadcast_streaming_comments($data); 
    	    
    	    if($save>0)
    	    {
    	    $JSON_ARR = array(
					'status'=>true,		
					'comment'=>"comment saved successfull"
							);
			 print json_encode($JSON_ARR);	

    	    }
    	    else
    	    {
    	        $JSON_ARR = array(
							'response'=>"Error"
							);
			    print json_encode($JSON_ARR);	
    	    }
    	    
    	    
        }
      
     function get_broadcast_streaming_comments()
    {
        
         $broadcast_id=$this->input->post('broadcast_id');
        
         if(empty($broadcast_id))
 	     {
 	         $JSON_ARR= array(
				'status'=>false,	
				'response'=>"broadcast Id is required"
				);
    			print json_encode($JSON_ARR);
    			die();
 	     }
 	    
        
        
        
         if(!empty($broadcast_id))
        {
        
                $comments= $this->Api_model->get_broadcast_streaming_comments($broadcast_id); 
                
                //print_r($comments);
                
                
                //echo $this->db->last_query();
                //exit;
                $comment_content=[];
                
                if(!empty($comments))
                {
                    foreach($comments as $comment)
                    { 
                        //print_r($comment);
						///print_r($comment["created_date"]);
						$diff=$this->crud_model->time_elapsed_string(strtotime($comment["created_date"]));
                       
                        $comment_id=$comment["id"];
                        $comment_content[]=array(
                            "date_ago"=>$diff,
                            "comments"=>$comment["comments"],
                            "name"=>$comment["name"],
                            "email"=>$comment["email"]
                            
                            );
                    }
                    
                    
                            $content[]= array(
                		    "launch"=>'broadcast_streaming_comment',
                			"broadcast_id"=>$broadcast_id,
                			"comments"=>$comment_content
                			);	
                			print json_encode( $content);
                }
                
                else
                {
                    
                    $content[]= array(
                			"status"=>true,	    
							"launch"=>'broadcast_streaming_comment',
                			"broadcast_id"=>$broadcast_id,
                			"comments"=>$comment_content
                			);	
                			print json_encode( $content);
                }
        }
        
        
    }
    
    
    	function image_test()
 	{
 	    error_reporting(E_ALL);
ini_set('display_errors', '1');


 	    //require 'vendor/autoload.php';
    require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
    $ffmpeg = FFMpeg\FFMpeg::create(array(
    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
    'ffprobe.binaries' => '/bin/ffprobe',
    'timeout'          => 3600, // The timeout for the underlying process
    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
));
  
  
  
  
  
  $sec = 10;
//$movie = 'test.mp4';
$movie = 'assets/global/movie_poster/love song।_360p.mp4';
$thumbnail = 'assets/global/temp_thumb/thumbnail.jpeg';


$video = $ffmpeg->open($movie);
$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));

echo '<pre>';
print_r($frame);
exit;


$frame->save($thumbnail);
echo '<img src="'.$thumbnail.'">';
  
    
    
  /*  
   
    $video = $ffmpeg->open('video.mpg');
    $video
        ->filters()
        ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
        ->synchronize();
    $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
        ->save('frame.jpg');
    $video
        ->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
        ->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
        ->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
        
*/        
        
     	    
     	    
 	}
 
 	public function movie_view_duration_count()
 	{
        $user_id=$this->input->post('user_id');
        $movie_id=$this->input->post('movie_id');
        
         if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
         if(empty($movie_id))
        {
            $JSON_ARR= array(
				'response'=>"Movie id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
            $data['user_id']=$user_id;
            $data['movie_id']=$movie_id;
            $data['created_date']=date('Y-m-d H:i:s');
            $this->db->insert('movie_view_duration_count', $data);
    		$id = $this->db->insert_id();
    		
    		if(!empty($id))
    		{
        		$JSON_ARR= array(
        		    'id'=>$id,
    				'response'=>"Duration save successfull"
    				
    				);
    			print json_encode($JSON_ARR);
                die();
    		}
            else
            {
                $JSON_ARR= array(
    				'response'=>"Duration not save successfull"
    				
    				);
    			print json_encode($JSON_ARR);
                die();
                
            }
 	    
 	    
 	}
 	
 	
 	
 		public function live_view_duration_count()
 	{
        $user_id=$this->input->post('user_id');
        $live_id=$this->input->post('live_id');
        
         if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
         if(empty($live_id))
        {
            $JSON_ARR= array(
				'response'=>"Live id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
            $data['user_id']=$user_id;
            $data['live_id']=$live_id;
            $data['created_date']=date('Y-m-d H:i:s');
            $this->db->insert('live_view_duration_count', $data);
    		$id = $this->db->insert_id();
    		
    		if(!empty($id))
    		{
        		$JSON_ARR= array(
    				'id'=>$id,
    				'response'=>"Duration save successfull"
    				
    				);
    			print json_encode($JSON_ARR);
                die();
    		}
            else
            {
                $JSON_ARR= array(
    				'response'=>"Duration not save successfull"
    				
    				);
    			print json_encode($JSON_ARR);
                die();
                
            }
 	    
 	    
 	}
 	
 	
 	
 	function replace_broadcast_img_to_godaddy()
 	{
 	    
 	   error_reporting(E_ALL);
ini_set('display_errors', '1');

 	    $sql="select  * from broadcast_details where id >1230 and broadcast_img!='' limit 0,1 " ;
		$query = $this->db->query($sql);
		$result= $query->result_array();
		
		
		foreach($result as $row)
		{
		    
		    
		   print_r($row['broadcast_img']);
		    
		    
		   $array_url= explode(',',$row['broadcast_img']); 
		   
		   foreach($array_url as $row_image)
		   {
		       //echo $row_image;
		       
		       $ext = end(explode('/', $row_image));
		       $filename='http://'.$row_image;
		       $destination='assets/global/broadcast/images/'.$ext;
		       //$ext='png';
		       
		       $this->compress_and_save($filename, $destination, 'png');
		   }
		   
		   
		}
		
		
		//$filename
		//$destination='assets/global/broadcast/images/'.
		//$this->compress_and_save();
		
	}
 	    
 	    
 	    
 	 function compress_and_save($filename, $destination, $ext="") 
 	{

 	if (preg_match('/jpg|jpeg/i', $ext)) {
        //$filename = imagecreatefromjpeg($filename);
        
        //$imageTmp=$filename;
    } else if (preg_match('/png/i', $ext)) {
        $filename = imagecreatefrompng($filename);
    } else if (preg_match('/gif/i', $ext)) {
        $filename = imagecreatefromgif($filename);
    } else 
    {
 	   $filename = imagecreatefromjpeg($filename);
    }  
 	   



    // quality is a value from 0 (worst) to 100 (best)
    //imagejpeg($imageTmp, $outputImage, $quality);
    //imagedestroy($imageTmp);
 	    
 	    
 	    //$filename=$imageTmp;
 	    
 	    
 	    
 	    
 	    //$width = 440; 
        //$height = 310; 
        
        //$width = 840; 
        $width = 600; 
        $height = 500; 
        
        // File type 
        header('Content-Type: image/jpg'); 
          
         

          
        // Get new dimensions 
        list($width_orig, $height_orig) = getimagesize($filename); 
        
        
        $ratio_orig = $width_orig/$height_orig; 
          
        if ($width/$height > $ratio_orig) { 
            $width = $height*$ratio_orig; 
        } else { 
            $height = $width/$ratio_orig; 
        } 
        $width = 600; 
        // Resampling the image  
        $image_p = imagecreatetruecolor($width, $height); 
        
       imageresolution($image_p, 72);

        
        $image = imagecreatefromjpeg($filename); 
          
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                $width, $height, $width_orig, $height_orig); 
          
        // Display of output image 
        imagejpeg($image_p, $destination, 100); 
 	      return $destination;
 	    
 	}
 	   
 	    
 	    
 	    public function add_movie_view()
        {
            $movie_id=$this->input->post('movie_id');
        	$user_id=$this->input->post('user_id');
        	
        	
        	
        	if( empty($movie_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Movie id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
            
    	              
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
        
        
         $data=array(
                 	'user_id'=>$user_id,
                 	'movie_id'=>$movie_id,
                 	'view_date_time'=>date('Y-m-d H:i:s')
                );
            
            $this->db->insert('movie_view', $data);
    		$broadcast_id = $this->db->insert_id();
    		
    		$JSON_ARR[] = array(
			'response'=>"your views has been saved successfully",
			'success'=>1,
			'movie_id'=>$movie_id,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR);

		}
		
 	    
 	    
 	    
 	     public function add_live_view()
        {
            $live_id =$this->input->post('live_id');
        	$user_id=$this->input->post('user_id');
        	
        	
        	
        	if( empty($live_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Live id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
            
    	              
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
        
        
         $data=array(
                 	'user_id'=>$user_id,
                 	'live_id'=>$live_id,
                 	'view_date_time'=>date('Y-m-d H:i:s')
                );
            
            $this->db->insert('live_view', $data);
    		$live_count_id = $this->db->insert_id();
    		
    		$JSON_ARR[] = array(
			'response'=>"your views has been saved successfully",
			'success'=>1,
			'live_id'=>$live_id,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR);

		}
		
 	    
 	    
 	    
 	    
 	     public function add_season_view()
        {
            $episode_id  =$this->input->post('season_id');
        	$user_id=$this->input->post('user_id');
        	
        	
        	
        	if( empty($episode_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Episode id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
            
    	              
            if( empty($user_id))
            {
                $JSON_ARR= array(
    				'response'=>"user_id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
        
        
         $data=array(
                 	'user_id'=>$user_id,
                 	'episode_id'=>$episode_id,
                 	'view_date_time'=>date('Y-m-d H:i:s')
                );
            
            $this->db->insert('season_view', $data);
    		$season_view_id = $this->db->insert_id();
    		
    		$JSON_ARR[] = array(
			'response'=>"your views has been saved successfully",
			'success'=>1,
			'Season_id'=>$episode_id,
            'user_id'=>$user_id 
			);
			print json_encode($JSON_ARR);

		}
		
 
 
 public function movie_viewcount($movie_id=0)
        {
            ///$movie_id=$this->input->post('movie_id');

        	if( empty($movie_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Movie id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        
             $view_count= $this->Api_model->total_view_movie($movie_id);
    	        
	 		$increase_view=$this->Api_model->increase_movie_view($movie_id);			
			$view_count=$view_count+$increase_view;
    		$JSON_ARR = array(
			'success'=>1,
			'movie_id'=>$movie_id,
            'view_count'=>number_format($view_count) 
			);
			print json_encode($JSON_ARR);

		}
		
 	    
   public function  live_viewcount($live_id=0)
        {

        	if( empty($live_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Live id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
	    $view_count= $this->Api_model->total_view_live($live_id); 
	   		
		$increease_view_count= $this->Api_model->increase_live_view($live_id); 	
		$view_count=$view_count+$increease_view_count;   
	
	   		
			//$this->DisplayViews($view_count)
    		$JSON_ARR[] = array(
			'success'=>1,
			'live_id'=>$live_id,
           'view_count'=>number_format($view_count)
			);
			print json_encode($JSON_ARR);

		}
		
 
   public function episode_viewcount($episode_id=0)
        {

        	if( empty($episode_id))
            {
                    
                $JSON_ARR= array(
    				'response'=>"Episode id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();
            }
    	  
        $view_count= $this->Api_model->total_view_season($episode_id);
    	$increease_view_count= $this->Api_model->increase_episode_view($episode_id);	
		
	    $view_count=$view_count+$increease_view_count;


    		$JSON_ARR[] = array(
			'success'=>1,
			'episode'=>$episode_id,
            'view_count'=>number_format($view_count)
			);
			print json_encode($JSON_ARR);

		}



	public function create_uwatch()
    {
	

		$user_id=$this->input->post('user_id');
		$title=$this->input->post('title');
	    $genre_id=	$this->input->post('genre_id');
		$description_long=$this->input->post('description_long');
		$channelpage_id=$this->input->post('channelpage_id');
		$privacy_type=$this->input->post('privacy_type');
		
			

			if(empty($user_id))
		{
		     $JSON_ARR= array(
    			'status'=>false,	
				'message'=>"User id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($title))
		{
		     $JSON_ARR= array(
    				'status'=>false,
				 	'message'=>"Title is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		
		if(empty($genre_id))
		{
		     $JSON_ARR= array(
    				'status'=>false,	
				 	'message'=>"Genre id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		if(empty($description_long))
		{
		     $JSON_ARR= array(
				 	'status'=>false,
    				'message'=>"Description is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($channelpage_id))
		{
		     $JSON_ARR= array(
				 	'status'=>false,
    				'message'=>"channel page id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		
		


		
		$filename=$_FILES['u_watch']['name'];
		 if(empty($filename))
		 {
		            $JSON_ARR= array(
					'status'=>false,
    				'message'=>"Video file is required"
    				
    				);
    			print json_encode($JSON_ARR);
                die();
		 }
		
		
		////////////////save u watch////////////////////////////
		
	
		 
		  
		  $u_watch_video="";
		 if(!empty($filename))
		 {
            $platformcraft_token=$this->get_platformcraft_token();
            
           
            
            $dirName = date( 'YmdHis', time() );
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            $u_watch_video='uwatch_'.$dirName.'.'.$file_ext;
           
		 
		 
		 ///////////////FFMPEG//////////////////////////////////
		 $extension='.jpeg';
		 $u_watch_img='uwatch_'.$dirName. $extension;
		 
		 $u_watch_thumbnail = 'assets/global/u_watch/'.$u_watch_img;

		 	require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
		
			
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                

                $movie=$_FILES['u_watch']['tmp_name'];
                
               
                $video = $ffmpeg->open($movie);
                
			 //$duration = $video->get('duration'); 
				//print_r($duration);
				
			 	//$movie = new Movie($movie);
			 //var_dump($movie->getDuration()); 
				///exit;



                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $frame->save($u_watch_thumbnail);

		     
		 //////////////////////////////////////////////////////    
		   
			 
			if(!empty($_FILES['u_watch']['name']))
		 {
		$tmpextension=pathinfo($_FILES['u_watch']['name'], PATHINFO_EXTENSION);
		copy($_FILES['u_watch']['tmp_name'], 'assets/global/tmp_uwatch_video/' . 'tmp_uwatch_video' .'.'. $tmpextension);	
		}

			 
///////////////*******************/////////////////////////////
			 
	$temp_uwatch=base_url().'assets/global/tmp_uwatch_video/'.'tmp_uwatch_video'.'.'. $tmpextension;			 
	
///$u_watch_video='https://4ykn7pwvnwqg-hls-push.5centscdn.com/content_provider_folder/'.$u_watch_video.'/playlist.m3u8';
			 
	$url='https://api.5centscdn.com/v2/zones/vod/push/4144/import';
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        				curl_setopt($ch, CURLOPT_POST, 1);
                   // $args['file'] = new CurlFile($_FILES['u_watch']['tmp_name'],'video/mp4',$_FILES['u_watch']['name']);
                
			 
			 		$args= array('url' => $temp_uwatch,'filename' => $u_watch_video,'folder' => 'content_provider_folder');
  

			 
			 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'X-API-Key:  558997c55d2bdfd3f567b184045ee72c',
    				'Content-Type: multipart/form-data'
    				
    	            ));
    		    $result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
			 
			 /*
		   
		   
			
			
			
			

		   


$url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/Content_Provider_folder/'.$u_watch_video;
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        				curl_setopt($ch, CURLOPT_POST, 1);
                    $args['file'] = new CurlFile($_FILES['u_watch']['tmp_name'],'video/mp4',$_FILES['u_watch']['name']);
                
			 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'Authorization: Bearer '.$platformcraft_token,
    				'Content-Type: multipart/form-data'
    				
    	            ));
    		    $result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
    				
    		*/
    			
		 }
		
		
		$ffprobe = FFMpeg\FFProbe::create();
		$duration=$ffprobe
			->format($_FILES['u_watch']['tmp_name']) // extracts file informations
			->get('duration');   			 

		

	/////////////////////////////////////////////////
	    if(!empty($response))
    	{
        $data['title']				=	$title;
		$data['description_long']	=	$description_long;
		$data['genre_id']			=	$genre_id;
		$data['url']				=	$u_watch_video;
        $data['created_date']		= date("Y-m-d H:i:s");
		$data['duration']		= $duration;	

		$data['user_id']				=	$user_id;
		$data['channelpage_id']			=	$channelpage_id;
		$data['privacy_type']			=	$privacy_type;
		
		if(!empty($u_watch_img))
		{
		    $data['u_watch_thumb']=$u_watch_img;
		    
		}
		
		


		$this->db->insert('u_watch', $data);
		$u_watch_id = $this->db->insert_id();
		
	    if(!empty($u_watch_id))
	    {
	        	
			
					
							$response_content=array(
							   'u_watch_id'=>$u_watch_id,
							'user_id'=>$user_id
							    
							    );
							

					$JSON_ARR = array(
					'status'=>true,
					'mesage'=>"U Watch has been create successfully",
					'response'=>$response_content
					);
			print json_encode($JSON_ARR);

	    }
    	}
     
    }
 
	
	
	function start_uwatch_live()
    {

		
	/*
		$post_privacy_type=$this->input->post('post_privacy_type');
		$user_id=$this->input->post('user_id');
        $playback_url=$this->input->post('playback_url');	
		$cat_id=	(empty($this->input->post('cat_id'))) ? 0 : $this->input->post('cat_id');
		
		$content_description=(empty($this->input->post('content_description'))) ? '' : $this->input->post('content_description');
	*/	
		
		$user_id=$this->input->post('user_id');
		$title=$this->input->post('title');
	    $genre_id=	$this->input->post('genre_id');
		$content_description=$this->input->post('description_long');
		$channelpage_id=$this->input->post('channelpage_id');
		$post_privacy_type=$this->input->post('privacy_type');
		$playback_url=$this->input->post('playback_url');	


		 if($post_privacy_type>2 OR $post_privacy_type<0  )
		 {
            $JSON_ARR= array(
				'message'=>"Privacy Type is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
			
		if( empty($content_description))
        {
            $JSON_ARR= array(
				'message'=>"Description is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
			




        if( empty($user_id))
        {
            $JSON_ARR= array(
				'message'=>"user id is required",
				'success'=>0,
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
		if(empty($title))
		{
		     $JSON_ARR= array(
    				'status'=>false,
				 	'message'=>"Title is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		
		if(empty($channelpage_id))
		{
		     $JSON_ARR= array(
				 	'status'=>false,
    				'message'=>"channel page id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
    
			 
			 
			 
		
	    $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0) 
		{
			//$row = $res->result_array();
			//return $row;
			$JSON_ARR= array(
					'status'=>false,
					'message'=>"This user does not exist",
					);
			print json_encode($JSON_ARR);
			 die();
		}
		
        if( empty($playback_url))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Playback url is required",
				);
			print json_encode($JSON_ARR);
            die();
        }
        
			/*
            $data['user_id']=$user_id;
            $data['cat_id']=$cat_id;
            $data['content_description']=$content_description;
            $data['created_date']=date('Y-m-d');
            $data['broadcast_video']= $playback_url;
            $data['live_status']=1;
            $data['post_privacy_type']=$post_privacy_type;
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();
			*/
		
		$data['title']				=	$title;
		$data['description_long']	=	$content_description;
		$data['genre_id']			=	abs($genre_id);
		$data['url']				=	$playback_url;
        $data['created_date']		= date("Y-m-d H:i:s");
	
		$data['is_live']				=1;
		$data['user_id']				=	$user_id;
		$data['channelpage_id']			=	$channelpage_id;
		$data['privacy_type']			=	$post_privacy_type;
		
		$this->db->insert('u_watch', $data);
		$u_watch_id = $this->db->insert_id();



            $JSON_ARR = array(
				'status'=>false,
				'message'=>"your data has been saved successfully",
				'u_watch_id'=>$u_watch_id,
				'user_id'=>$user_id,
				'playback_url'=>$playback_url
				);
				print json_encode($JSON_ARR);
    }








	public function uwatch_comments_count($u_watch_id=0)
    {
		$sql = "SELECT COUNT(*) AS cntpost FROM uwatch_comments WHERE u_watch_id='".$u_watch_id."'"; 
        $res = $this->db->query($sql);
    	$connents = $res->result_array();
        $count= $connents[0]['cntpost'];	
		return $count;
	}	

	public function uwatch_share_count($u_watch_id=0)
    {
		/*
		$sql = "SELECT COUNT(*) AS cntpost FROM uwatch_comments WHERE u_watch_id='".$u_watch_id."'"; 
        $res = $this->db->query($sql);
    	$connents = $res->result_array();
        $count= $connents[0]['cntpost'];	
		return $count;
		*/
		return 0;

	}

	
	public function uwatch_like_unlike_count($u_watch_id=0)
    {
		$like_count = "SELECT COUNT(*) AS cntpost FROM uwatch_like_unlike WHERE u_watch_id='".$u_watch_id."'"; 
        $like_count_res = $this->db->query($like_count);
    	$connents = $like_count_res->result_array();
        $like_unlike_count= $connents[0]['cntpost'];	
		return $like_unlike_count;	
		
	}


	public function uwatch_related_video()
    {
		
	    $genre_id=	$this->input->post('genre_id');
		
		if(empty($genre_id))
		{
		     $JSON_ARR= array(
    				'status'=>false,	
				 	'message'=>"Genre id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		 $query = "SELECT * FROM u_watch WHERE genre_id='".$genre_id."' order by rand() limit 0,10 ";
         $res = $this->db->query($query);
         $result_uwatchs= $res->result_array();
		
		$uwatch_content=[];
		foreach($result_uwatchs as $result_uwatch)
		{
			
		$video_url="";
			if(!empty($result_uwatch['url']))
			{
				$video_url="https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/Content_Provider_folder/".$result_uwatch['url']."/playlist.m3u8";
			
			}
			
			$sql = "SELECT * FROM u_watch_view where 
			 u_watch_id='".$result_uwatch['u_watch_id']."'";
			$res = $this->db->query($sql);
			$view_count= $res->num_rows();

		
			$increase_view=$this->Api_model->increase_uwatch_view($result_uwatch['u_watch_id']);			
				$view_count=$view_count+$increase_view;




			$uwatch_content[]= array(
					 "user_id"=>$result_uwatch['user_id'],
					 "name"=>$result_uwatch['name'],
					 "email"=>$result_uwatch['email'],
					 "u_watch_id" => $result_uwatch['u_watch_id'],
					 "title"=>$result_uwatch['title'],
					 "u_watch_url"=>$video_url,
					"duration"=>$result_uwatch['duration'],
					"view_count"=>$view_count,
					"like_unlike_count"=>$this->uwatch_like_unlike_count($result_uwatch['u_watch_id']),
					"uwatch_share_count"=>$this->uwatch_share_count($result_uwatch['u_watch_id']),
					"comment_count"=>$this->uwatch_comments_count($result_uwatch['u_watch_id']),
				
								
					"created_time"=>$this->crud_model->time_elapsed_string($result_uwatch['created_date']),
					 "u_watch_thumb"=>$this->crud_model->get_uwatch_thumb_url( $result_uwatch['u_watch_thumb'])

				   );	
		}
		
		$ALL_JSON_ARR = array(
				'launch'=>'U_Watch',
				'response'=>$uwatch_content
				);
		print json_encode($ALL_JSON_ARR);




		



	}


	public function create_uwatch_view()
    {
		$channelpage_id=$this->input->post('channelpage_id');
		$user_id=$this->input->post('user_id');
	    $u_watch_id=	$this->input->post('u_watch_id');
		if(empty($user_id))
		{
		     $JSON_ARR= array(
    			'status'=>false,	
				'message'=>"User id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($u_watch_id))
		{
		     $JSON_ARR= array(
    			'status'=>false,	
				'message'=>"Uwatch id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($channelpage_id))
		{
		     $JSON_ARR= array(
    			'status'=>false,	
				'message'=>"Channel Page id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}

		
		$data['user_id']				=	$user_id;
		$data['u_watch_id']	=	$u_watch_id;
		$data['channelpage_id']	=	$channelpage_id;
        $data['created_date']		= date("Y-m-d H:i:s");
		$this->db->insert('u_watch_view', $data);
		$u_watch = $this->db->insert_id();
		
	    if(!empty($u_watch))
	    {
	        	
			
					
							$response_content=array(
							   'u_watch_id'=>$u_watch_id,
							'user_id'=>$user_id
							    
							    );
							

					$JSON_ARR = array(
					'status'=>true,
					'mesage'=>"U Watch view has been create successfully",
					'response'=>$response_content
					);
			print json_encode($JSON_ARR);

	    }
		
		
		
	}


	

	 function create_uwatch_like_unlike()
    {
        $user_id=$this->input->post('user_id');
		$uwatch_id=$this->input->post('uwatch_id');
		$type=abs($this->input->post('type'));
        
		 
		if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"User id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
		 
		if( empty($uwatch_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"U watch id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
       
      	
        
        if(!empty($user_id) and !empty($uwatch_id) )
        {
            
         $query = "SELECT COUNT(*) AS cntpost FROM uwatch_like_unlike WHERE u_watch_id=".$uwatch_id." and user_id=".$user_id;
            $res = $this->db->query($query);

    				$connents = $res->result_array();
                    $count= $connents[0]['cntpost'];
                    
		      if($count == 0){
                        
                        $data=array(
                            "u_watch_id"=>$uwatch_id,
    	                    "user_id"=>$user_id,	
    	                    "type"=>abs($type),
    	                    "created_date"=>date('Y-m-d H:i:s')
                            );
                        $this->Api_model->uwatch_like_unlike($data);
                }else {
                    
                        $data=array(
    	                    "type"=>$type
                            );
                    
                  
				  $this->Api_model->update_uwatch_like_unlike($data,$user_id,$uwatch_id);
                }

                $JSON_ARR = array(
					'status'=>true,
					'message'=>"Success",
					'response'=>""
					);
				print json_encode($JSON_ARR);
            
                
		
            
		     

            
            
        }
        else
        {
            
            $JSON_ARR = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
            
        }
        
        
    }
    





 	public function u_watch()
    {
	  
		$page_no=$this->input->post('page');
        $user_id=$this->input->post('user_id');
		if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"User id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }		
		


		$limit=20; 
         //expression ? trueValue : falseValue
         $page=(!empty($page_no)) ? $page_no : 1;
         if(($page==1 or $page==0 ))
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }
		$result_uwatchs= $this->Api_model->u_watch_list($start,$limit);
		
		$uwatch_content=[];
		foreach($result_uwatchs as $result_uwatch)
		{
			
			
			$video_url="";
			if(!empty($result_uwatch['url']))
			{
				//$video_url="https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/Content_Provider_folder/".$result_uwatch['url']."/playlist.m3u8";
			
				$video_url="https://cdn.happywatch99.com/content_provider_folder/".$result_uwatch['url']."/playlist.m3u8";
				
			}
			
			$sql = "SELECT * FROM u_watch_view where 
			 u_watch_id='".$result_uwatch['u_watch_id']."'";
			$res = $this->db->query($sql);
			
			$view_count= $res->num_rows();
			
			$increase_view=$this->Api_model->increase_uwatch_view($result_uwatch['u_watch_id']);			
				$view_count=$view_count+$increase_view;


				
				$uwatch_content[]= array(
					 "user_id"=>$result_uwatch['user_id'],
					 "name"=>$result_uwatch['name'],
					 "email"=>$result_uwatch['email'],
					"channelpage_id"=>$result_uwatch['channel_page_id'],
					"channelpage_name"=>$result_uwatch['channel_page_name'],
					"likeStatus"=>$this->uwatch_like_unlike($user_id,$result_uwatch['u_watch_id']),
					"subscriberStatus"=>$this->channelpage_subscriberStatus($user_id,$result_uwatch['channel_page_id']),
					'profile_image'=>$this->crud_model->get_profile_image_url($result_uwatch['user_id']),
					 "u_watch_id" => $result_uwatch['u_watch_id'],
					"gener_id" => $result_uwatch['genre_id'],
					 "title"=>$result_uwatch['title'],
					 "u_watch_url"=>$video_url,
					"is_live"=>$result_uwatch['is_live'],
					"duration"=>$result_uwatch['duration'],
					"view_count"=>$this->DisplayViews($view_count),
					"like_unlike_count"=>$this->uwatch_like_unlike_count($result_uwatch['u_watch_id']),
					"uwatch_share_count"=>$this->uwatch_share_count($result_uwatch['u_watch_id']),
					"comment_count"=>$this->uwatch_comments_count($result_uwatch['u_watch_id']),
					"created_time"=>$this->crud_model->time_elapsed_string1($result_uwatch['created_date']),
					 "u_watch_thumb"=>$this->crud_model->get_uwatch_thumb_url( $result_uwatch['u_watch_thumb'])

				   );	
		}
		
		$ALL_JSON_ARR = array(
				'launch'=>'U_Watch',
				'contents'=>$uwatch_content
				);
		print json_encode($ALL_JSON_ARR);
	

	 
	}  
	  
	
	public function uwatch_like_unlike($user_id=0,$uwatch_id=0)
	 {
			$status_query = "SELECT type FROM  uwatch_like_unlike WHERE user_id='".$user_id."' and u_watch_id=".abs($uwatch_id);
            $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue=0;
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue=1;
                            }
                            else
                            {
                                $current_user_statue=0;
                            }
                    	}
			
						return $current_user_statue;
	}
	 
	
	public function channelpage_subscriberStatus($user_id=0,$channelpage_id=0)
	 {
			$status_query = "SELECT subscribe FROM  channel_page_subscribe WHERE 
			user_id='".$user_id."' and channel_id=".abs($channelpage_id);
            $res_self = $this->db->query($status_query);
                    	$current_user_statue=0;
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	$user_status = $res_self->result_array();
                        	if($user_status[0]['subscribe']==1)
                            {
                                $current_user_statue=1;
                            }
                            else
                            {
                                $current_user_statue=0;
                            }
                    	}
			
						return $current_user_statue;
	}
	



	 
	 	public function createPopUpNotification()
	    {
		
		
		$user_id=$this->input->post('user_id');
		
		$title=$this->input->post('title');
		
		$message=$this->input->post('message');
			
		$button=$this->input->post('button');
		
		
		if(empty($user_id))
		{
		     $JSON_ARR= array(
    				'response'=>"User id is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($title))
		{
		     $JSON_ARR= array(
    				'response'=>"Title is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($message))
		{
		     $JSON_ARR= array(
    				'response'=>"Message is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($button))
		{
		     $JSON_ARR= array(
    				'response'=>"Button is required",
    				'success'=>0
    				);
    			print json_encode($JSON_ARR);
                die();

		}
				$data=array(
					'user_id'=>$user_id,
				    'title'=>$title,
				    'message'=>$message,
				    'button'=>$button	
				);
				$adminpopup_notification= $this->Api_model->create_adminpopup_notification($data);
				
				if($adminpopup_notification)
				{	
					$JSON_ARR[] = array(
						'response'=>"Admin pop up notification successfully added",
						'success'=>1,
						'id'=>$adminpopup_notification
					);

					print json_encode($JSON_ARR);
					
				}
		else
		{
				$JSON_ARR[] = array(
								'response'=>"Admin pop up notification not successfully added",
								'success'=>0,
								);
						print json_encode($JSON_ARR);
			
		}
		
		
	}
	
 
 	public function getPopUpNotification($user_id="")
 	{
 	     
 	      /*
 	      
 	      SELECT * FROM FOO
WHERE MY_DATE_FIELD >= NOW() - INTERVAL 1 DAY
 	      
 	      $sql = "select AN.*,U.name as user_name from adminpopup_notification AN left join user U on AN.user_id=U.user_id
        where 1  and AN.user_id='".$user_id."'";
        */
        
      $sql = "select * from notification where   create_date >= NOW() - INTERVAL 1 DAY order by create_date desc limit 1 ";
        
        $content=[];
        $res = $this->db->query($sql);
        
        ////$popup_notifications = $res->result_array();
		
		////print_r($popup_notifications);
		///exit;
		
		$flag=0;
		if ($res->num_rows() > 0) 
	    {
            $popup_notifications = $res->result_array();

           
            if($popup_notifications[0]['notification_send']=='ALL')
            {
            $flag=1;
                
            }
            elseif($popup_notifications[0]['notification_send']=='SELECTED')
            {
                
                $is_notified= $this->Api_model->notifaction_send_in_user($user_id,$popup_notifications[0]['id']);
            
                if(!empty($is_notified))
                {
                    $flag=1;
                }
                else
                {
                    $flag=0;
                }
            }
                
			
			//$popup_content[]=array('notification'=>$popup_notifications[0]['notification']);
              if($flag==1)
              {
    	    	$response_content[]=array(
    			"notification"=>$popup_notifications[0]['notification'],
				"time"=>$popup_notifications[0]['create_date']		
    			);
            	  
            	  
            	$JSON_ARR = array(
    			'status'=>true,
            	'message'=>'PopUpNotification',
				'response'=>$response_content
				
    			); 

				   	
              }
              else
              {
                  $JSON_ARR = array(
                      'status'=>false,
	                'response'=>'',
					 'message'=>'No Pop up Notification'
				
					); 
                  
              }
              
              print json_encode($JSON_ARR);
              
	    }
	    else
	    {
	         $JSON_ARR = array(
                      'status'=>false,
	                'response'=>'',
	                'message'=>'No Pop up Notification'
					); 
	        
	        print json_encode($JSON_ARR);
	    }
	    
	   
			
	        
	        
	}
 	
 	
 	
  public function un_save_fav_post()
  {
        $user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id'); 	
		if(empty($user_id))
		{
		     $JSON_ARR= array(
		            'status'=>false,
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($broadcast_id))
		{
		     
		     $JSON_ARR= array(
		            'status'=>false,
    				'response'=>"Broadcast id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		 $delete_fav_post=$this->Api_model->delete_un_save_fav_post($user_id,$broadcast_id);
		  
      
   
      
      if(!empty($delete_fav_post))
      {
          $JSON_ARR = array(
			'status'=>true,
			'message'=>"This post has been UnFavorite successfully",
			);
			print json_encode($JSON_ARR);  

          
          
      }
      else
      {
          $JSON_ARR = array(
			'status'=>false,
			'message'=>"This post has not been UnFavorite successfully",
			);
			print json_encode($JSON_ARR);  

          
          
          
      }
      
      
  }


 	
 	
 	public function saveFavPost()
 	{   //saved_broadcast_fav_post
 	    $user_id=$this->input->post('user_id');
		
		$broadcast_id=$this->input->post('broadcast_id'); //broadcast_id	
		
		if(empty($user_id))
		{
		     $JSON_ARR= array(
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		if(empty($broadcast_id))
		{
		     $JSON_ARR= array(
    				'response'=>"Broadcast id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
		
		
		
		$favourite_flag= $this->Api_model->check_favpost();
				
	
				if($favourite_flag>0)
				{
					$JSON_ARR = array(
					'response'=>"This record already exists"
					);
					print json_encode($JSON_ARR);			
					die();
				}	
		
		
		$data=array(
				"user_id"=>$user_id,
				"broadcast_id"=>$broadcast_id,
				"created_at"=>date("Y-m-d")
			);	
		$sth=$this->Api_model->save_fav_broadcast_post($data);
						if($sth)
						{
							$JSON_ARR = array(
							'response'=>"Added to Broadcast Post favorites!"
							);
							print json_encode($JSON_ARR);
							exit;
						}
		
 	}
 	
 	
 	
 	
 	public function getSavedFavPost()
 	{
 	    
 	     $user_id=$this->input->post('user_id');
		if(empty($user_id))
		{
		     $JSON_ARR= array(
    				'status'=>false,
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	    $broadcast_favourites=$this->Api_model->get_fav_broadcast_post();
 	    
 	    if (!empty($broadcast_favourites)) 
		{  
            
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcast_favourites as $row)
			{
        			    
			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                $res_like = $this->db->query($query_like);
                $likes= $res_like->result_array();
                $likes_count=abs($likes[0]['no_likecount']);
            			    
        		
        		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".abs($row['id']);
                $res_self = $this->db->query($status_query);
            	
            	$current_user_statue="false";
            	if ($res_self->num_rows() > 0) 
            	{
                	
                	$user_status = $res_self->result_array();
                	
                	///print_r($user_status);
                	
                	if($user_status[0]['type']==1)
                    {
                        $current_user_statue='true';
                    }
                    else
                    {
                        $current_user_statue='false';
                    }
            	}
    	    
        			    
        			    
        			    
        			    
        			    $sql = "SELECT *  FROM broadcast_comments where 1 and broadcast_id='". $row['id']."'";
        				$res = $this->db->query($sql);
        				if ($res->num_rows() > 0) 
        				{
        					$connents = $res->result_array();
        					$comment_contents=[];
        					foreach($connents as $connent)
        					{
        					        $comment_contents[] = array(
        							'user_id'=>$connent['user_id'],
        							'broadcast_id'=>$connent['broadcast_id'],
        							'user_comment'=>$connent['user_comment'],
        							'created_date'=>$connent['created_date'],	  	
        							);
        					
        					}
        				}
        			    
			  
			  $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			      
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			      
        			       
        			          
        			           
        			       }            
        			        
        			        
        			        
        			    }

			    
			    
			    
			    $array_videos=[];
			    
			    
			   if($row['live_status']==0) 
			   {
			    
			    
                        if(!empty($row['full_video_url'])) 
    			        {   
                           
                          
                           /*
                            require_once(APPPATH.'libraries/aws/s3bucket_configuration.php');
        
                            $bucket = 'happywatch';
                            $keyname=$row['broadcast_video'];
                           
                           
                           $aws_movie_name= basename($row_movie['url']);
								$cmd = $s3Client->getCommand('GetObject', [
									'Bucket' => $bucket,
									'Key' => $keyname
								]);

								$request = $s3Client->createPresignedRequest($cmd, '+120 minutes');

								// Get the actual presigned-url
								$movie_url= $presignedUrl = (string)$request->getUri();
                           
                           */
                           
                           
                           
                           
                           $array_videos[]=$row['full_video_url'];
                           
                         
    			        }  
                        else
                        {
                           
                                $array_broadcast_video= explode(',', $row['broadcast_video']);
                			    
                			    $array_videos=[];
                			    foreach($array_broadcast_video as $video )
                			    {
                			        
                			       if(!empty($video))
                			       {
                	 
                    			        /*
                    			        if (file_exists('assets/global/broadcast/videos/' . $video))
                                        {    
                                            $array_videos[] = base_url() . 'assets/global/broadcast/videos/' . $video;
                                        
                                            
                                        }    
                                        */
                                        
                                        $array_videos[]='https://'.$video;
                                    
                			       }  
                			        
                			        
                			        
                			        
                			    }
                        }  
            			    
			        }
			        elseif($row['live_status']==1)
			        {
			           $array_videos[]= $row['broadcast_video'];
			        }
			        
			        
			        
			        $array_videosthumb=[];
			        
			        
			        
			        //if(!empty($row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			
        			 if (file_exists('assets/global/channel_thumb/' . $row['full_video_thumb_url']) and !empty($row['full_video_url']))
        			{
        			    $array_videosthumb[] =base_url() .'assets/global/channel_thumb/'.$row['full_video_thumb_url'];
        			}
			        
			        elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			        
			        $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
			        
			        
                			    
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			      
                			      
                			        
                			       if(!empty($thumb))
                			       {

                    			       
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        } 
                                       
                                    
                                    //$array_videosthumb[]='https://'.$thumb;
                                    
                			       }
                			       
                			       
                			       ///print_r($array_videosthumb);
                			    }   
			        
			        }
			        
			        
			        
			    //'image'=>$this->crud_model->get_broadcast_img_url_for_api($row['id']),
				//	'video'=>$this->crud_model->get_broadcast_video_for_api($row['id']),
			    
			        $view_count=0;
			        $sharing_count=0;
			        if(!empty($row['id']))
			        {
			        
			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
			    
			    
			        $view_count=$this->Api_model->broadcast_view($row['id']);
			    
			        }
			    
			    
			    
			    
			    
			    
			    $broadcast_contents[] = array(
					'broadcast_id'=>$row['id'],
					'user_id'=>$row['user_id'],
					'name'=>$row['user_name'],
					
					'profile_image'=>$this->crud_model->get_profile_image_url($row['user_id']),
					
					'category_id'=>$row['cat_id'],
					'category_name'=>$row['name'],
					'image'=>$images,
					'video'=>$array_videos,
					 'video_thumbnail'=>$array_videosthumb,
					'created_date'=>$row['created_date'],
					'content_description'=>	$row['content_description'],
					'comment_contents'=>$comment_contents,
					'view_count'=>$view_count,
					'sharing_count'=>$sharing_count,
					'self_like'=>$current_user_statue,
					'like_count'=>$likes_count,
					'live_status'=> $row['live_status']
					
					);
			
			
			    
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'status'=>true,
				'message'=>'',
				'response'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);

		}
		else
		{
		   $JSON_ARR = array(
		    'status'=>false,     
			'message'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		    
		}
       
 	    
 	    
 	    
 	   
 	    ////////////////////////////////////////////////////////
 	}
 	
 	
 	
 	public function changePostPrivacy()
 	{
 	    $user_id=$this->input->post('user_id');
		$post_privacy_type=$this->input->post('post_privacy_type');
		$broadcast_id=$this->input->post('broadcast_id'); //broadcast_id	
 	    if(empty($user_id))
		{
		     $JSON_ARR= array(
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
	

 	    if($post_privacy_type>2 OR $post_privacy_type<0  )
		{
		     $JSON_ARR= array(
    				'response'=>"Privacy type is required,Privacy type may be (0,1,2)"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	    
 	     if(empty($broadcast_id))
		{
		     $JSON_ARR= array(
    				'response'=>"Broadcast id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	    
 	    
 	    $sql="update broadcast_details set post_privacy_type='".$post_privacy_type."'  where  user_id='".$user_id."' and id='".$broadcast_id."'";
		$res = $this->db->query($sql);
		if($res)
		{
		    $JSON_ARR= array(
    				'response'=>"your post Privacy has been changed successfully",
                    'user_id'=>$user_id
    				);
    			print json_encode($JSON_ARR);
                die(); 
		    
		}
 	    
 	    
 	    
 	}
 	
 	
 	function edit_broadcast_post()
    {
        $user_id=$this->input->post('user_id');
	
        $content_description=$this->input->post('content_description');
        $broadcast_id=$this->input->post('broadcast_id'); 	
		$post_privacy_type=$this->input->post('post_privacy_type'); 	
		
        
        
         if(empty($user_id))
		{
		     $JSON_ARR= array(
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	   
 	    
 	     if(empty($broadcast_id))
		{
		     $JSON_ARR= array(
    				'response'=>"Broadcast id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
        
        
       
        
        
        if(!empty($user_id) )
        {
            
          
            
            $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR[] = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
	
        }
            
            
            /*
            $data['user_id']=$user_id;
           
            $data['content_description']=$content_description;
            $data['created_date']=date('Y-m-d');
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();
            */
            
            $platformcraft_token=$this->get_platformcraft_token();
            $array_image=[];
            for($i=0; $i<count($_FILES['broadcast_img']['name']); $i++) 
            {
            
                if($_FILES['broadcast_img']['name'][$i]!="")
                {
                    
                    //////////////////////////////////////////////////
                 
                 
                 $tmp_image='';
                 $filename=$_FILES['broadcast_img']['name'][$i];
                 $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                   

                  $_FILES['broadcast_img']['tmp_name'][$i]=$this->compressImage($_FILES['broadcast_img']['tmp_name'][$i],$_FILES['broadcast_img']['tmp_name'][$i],$img_ext);
                  

                   //echo "=============111";
                 ///exit; 
                    //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    
                   //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.jpeg';
                   
                   
                   
                   $filename=$_FILES['broadcast_img']['name'][$i];
                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $array_image[]='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    move_uploaded_file($_FILES['broadcast_img']['tmp_name'][$i], 'assets/global/broadcast/images/'.'img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext);
                   
                   
                             

                }
              
            }
            
            $array_video=[];
            
        
           
           
            for($ii=0; $ii<count($_FILES['broadcast_video']['name']); $ii++) 
            {

               if($_FILES['broadcast_video']['name'][$ii]!="")
                { 
                    
                    ///////////////video_thumblain////////////////////////
    			require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                
                $movie=$_FILES['broadcast_video']['tmp_name'][$ii];
                
                $extension='.jpeg';
    		    $tmp_video_thumb='videothumb_'.$broadcast_id .'_'.($ii+1).$extension;   
                    
                
                $thumbnail = 'assets/global/broadcast/video_thumb/'.$tmp_video_thumb;
                $video = $ffmpeg->open($movie);
                /*
                $video->filters()->resize(new FFMpeg\Coordinate\Dimension(300, 310), $mode = RESIZEMODE_SCALE_HEIGHT)->synchronize();

                */
                
                /*
                $video
                ->filters()
                ->resize(new FFMpeg\Coordinate\Dimension(640, 480),3)
                ->synchronize();
                 */           
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                
                $frame->save($thumbnail);
                $array_video_thumb[]=$tmp_video_thumb;

                    
                    $tmp_video_name='';
                    $filename1=$_FILES['broadcast_video']['name'][$ii];
                    $video_ext = pathinfo($filename1, PATHINFO_EXTENSION);
                        
                        
                    ///$array_video[]='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                        
                    $tmp_video_name='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                       
                    
                   $url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video/'.$tmp_video_name;
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    				curl_setopt($ch, CURLOPT_POST, 1);
                    $args['file'] = new CurlFile($_FILES['broadcast_video']['tmp_name'][$ii],'video/mp4',$_FILES['broadcast_video']['name'][$ii]);
                    
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'Authorization: Bearer '.$platformcraft_token,
    				'Content-Type: multipart/form-data'
    				
    	            ));
    				$result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
    				//$array_video[]=$response->download_url;
    			    

    			   // $array_video[]=$response->hls;	
    				if(empty($response->hls))
    				{
    				    $array_video[]=$response->download_url;
    				}
    				else
    				{
    				     $array_video[]="happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/broadcast_video/".$tmp_video_name."/playlist.m3u8";
    				    
    				    
    				}
    				
                }
                

            }
            
            
            
            
           
            
            $str_image= implode(",",$array_image);
            $str_video= implode(",",$array_video);
            $str_video_thumb= implode(",",$array_video_thumb);
            
            
            
            $data2=array(
                "content_description"=>$content_description,
				"post_privacy_type"=> abs($post_privacy_type),
                "broadcast_img"=>$str_image,
                "broadcast_video"=>$str_video,
                "broadcast_video_thumbnail"=>$str_video_thumb
                );
            
            	$this->db->where('id', $broadcast_id);
            	$this->db->where('user_id', $user_id);
			    $this->db->update('broadcast_details', $data2);
                $affected_row=$this->db->affected_rows();
            
            
            $JSON_ARR[] = array(
				'response'=>"your data has been updated successfully",
				'success'=>1,
				'broadcast_id'=>$broadcast_id,
				'user_id'=>$user_id
		
				);
				print json_encode($JSON_ARR);
        
        
        
         
       

    }
    
    public function getmyProfileData($user_id=0)
    {
		
        if(empty($user_id))
		{
		     $JSON_ARR= array(
		            'status'=>false,
    				'message'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		} 
		
		$user_details=$this->Api_model->user_details($user_id);
		
		$c_p_a_status=$this->Api_model->content_provider_application_status($user_id);
		
		$fan_page_details=$this->Api_model->my_fan_page_details($user_id);
		
		//print_r($fan_page_details);
		//echo $this->db->last_query();
		$fanpage_content=array(
		    'fan_page_id'=>$fan_page_details[0]['id'],
		    'fan_page_name'=>$fan_page_details[0]['name']
		    );
		
		$channel_details=$this->Api_model->my_channe_details($user_id);
		
		///print_r($channel_details);
		//exit;
		
		$channel_content=array(
		    'id'=>$channel_details[0]['id'],
		    'name'=>$channel_details[0]['name'],
			'channel_image'=>$this->crud_model->get_channelpage_profile_image_url($channel_details[0]['id']) 
		    );
		
		
		if (!empty($user_details)) 
		{ 	    
				        $response_content=array(
							'user_id'=>intval($user_details[0]['user_id']),
							'user'=>$user_details[0]['name'],
							'useremail'=>$user_details[0]['email'],
							'country_code'=>$user_details[0]['country_code'],
							'mobile'=>$user_details[0]['mobile'],
				            'dob'=>  $user_details[0]['dob'],
				            'name'=>$user_details[0]['name'],
				            'gender'=>$user_details[0]['gender'],
				            
				            
				            'lives_in'=>$user_details[0]['lives_in'],
				            'from_location'=>$user_details[0]['from_location'],
				            'marital_status'=>$user_details[0]['marital_status'],
				            'relationship_status'=>$user_details[0]['relationship_status'],
				            'studied_at'=>$user_details[0]['studied_at'],
				            "relationship_start_date"=>$user_details[0]['relationship_start_date'],
							"relationship_privacy"=>$user_details[0]['relationship_privacy'],
							"relationship_with"=>$user_details[0]['relationship_with'],
				            'working_at'=>$user_details[0]['working_at'],
				            'country'=>$user_details[0]['country'],
				            'channel_content'=>$channel_content,
				            'fanpage_content'=>$fanpage_content,
				            
				             'content_provider_application_status'=>$c_p_a_status,
				            'qr_code_image'=>base_url().'assets/global/user_qr_code/'.$user_details[0]['qr_bar_code_image'],
				            
				            'profile_image'=>$this->crud_model->get_profile_image_url(intval($user_details[0]['user_id']))
							);
							
						
						
							$JSON_ARR= array(
							'status'=>true,
							'message'=>"",
							'response'=>$response_content
							);
							print json_encode($JSON_ARR);  
						
						
						
					
				    
		
		}
        else
        {
            $JSON_ARR= array(
                    'status'=>false,
    				'message'=>"This user doesn't exist"
    				);
    			print json_encode($JSON_ARR);
                die();
        }
        
    }
 	
 	
 	public function getmyFriendsData($user_id=0,$sort_order=0)
 	{
 	   
 	   //$sort_order=$this->input->post('sort_order');
 	   
 	   if(empty($user_id))
		{
		     $JSON_ARR= array(
		            'status'=>false,
    				'message'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		} 
		
		$friend_user_details=$this->Api_model->getmyFriendsData($user_id,$sort_order);
		
		
		//print_r($friend_user_details);
		
		//echo $this->db->last_query();
		
		
		$friend_contents=[];
		if (!empty($friend_user_details)) 
		{ 	    
				     
		foreach($friend_user_details as $user_details )		     
		{		     
		   $mutual_friends_count=0;
		   if(!empty($user_details['user_id']) and !empty($user_id))
		   {
		   $mutual_friends_count=$this->Api_model->mutual_friends($user_id,$user_details['user_id']);
		   
		   }
		   
		        $friend_contents[]=array(
					'user_id'=>intval($user_details['user_id']),
					'user'=>$user_details['name'],
					'useremail'=>$user_details['email'],
					'mutual_friends'=>$mutual_friends_count,
					'country_code'=>$user_details['country_code'],
					'mobile'=>$user_details['mobile'],
		            'dob'=>  $user_details['dob'],
		            'name'=>$user_details['name'],
		            'gender'=>$user_details['gender'],
		            
		            'profile_image'=>$this->crud_model->get_profile_image_url($user_details['user_id'])
					);
					
				
		}		    
		
		
		 $JSON_ARR= array(
    				'status'=>true,
    				'message'=>"My friends data",
    				'response'=>$friend_contents
    				
    				);
    			print json_encode($JSON_ARR);
                die();
		
		
		}
        else
        {
            $JSON_ARR= array(
				'status'=>false,
    				'message'=>"This user have not any friends"
    				);
    			print json_encode($JSON_ARR);
                die();
        }
        
 	}
 	
 
 	public function getmyBroadcastPostsData($user_id=0)
 	{   
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
       
       $user_details=$this->Api_model->user_details($user_id);
       	
       if(empty($user_details))
       {
       	$JSON_ARR= array(
				'response'=>"This user id does not exist"
							
				);
				print json_encode($JSON_ARR);
       
        die(0);
           
       }
       
        $where_as="";
        if($user_id>0)
        {
            $where_as="and BD.user_id='".$user_id."'";    
        }

        $sql = "select BD.*,C.name,U.name as user_name from user U
        left join broadcast_details BD on U.user_id=BD.user_id
        left join category C on BD.cat_id= C.cat_id
        where 1  ".$where_as." order by BD.id desc ";
        
         $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			$broadcast_contents=[];
			$comment_contents=[];
			foreach($broadcasts as $row)
			{
        			    
			   
			    if($row['live_status']==0 or $row['live_status']=1) 
			   {
			   
        			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                        $res_like = $this->db->query($query_like);
                        $likes= $res_like->result_array();
                        $likes_count=abs($likes[0]['no_likecount']);
                    			    
                		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".abs($row['id']);
                        $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue="false";
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue='true';
                            }
                            else
                            {
                                $current_user_statue='false';
                            }
                    	}
            	    
                				$sql = "SELECT *  FROM broadcast_comments where  broadcast_id='".$row['id']."'";
                				
                				$comment_contents=[];
                				$res = $this->db->query($sql);
                				if ($res->num_rows() > 0) 
                				{
                					
                					
                					$connents = $res->result_array();
                					
                					foreach($connents as $connent)
                					{
                					        $comment_contents[] = array(
                							'user_id'=>$connent['user_id'],
                							'broadcast_id'=>$connent['broadcast_id'],
                							'user_comment'=>$connent['user_comment'],
                							'created_date'=>$connent['created_date'],	  	
                							);
                					
                					}
                				}
                			    
        			   $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			       
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			       

        			       }            
        			        
        			        
        			        
        			    }
        			    $array_videos=[];
        			    
        			 
			    
			    
                        if(!empty($row['full_video_url']) and $row['live_status']==0) 
    			        {   
                           
        			        $array_videos[]=$row['full_video_url'];
    			        }
        			    elseif($row['live_status']==1)
        			    {
        			        $array_videos[]=$row['broadcast_video'];
        			        
        			        
        			        
        			        
        			        
        			    }
        			    
        			    else
        			    {
        			    
        			    foreach($array_broadcast_video as $video )
        			    {
        			        
        			       if(!empty($video))
        			       {
                                $array_videos[]='https://'.$video;
        			       }  
        			        
        			        
        			        
        			        
        			    }
        			    
        			    }
        			    

        			$array_videosthumb=[];   
        			

        			if(!empty('assets/global/channel_thumb/' .$row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    //$array_videosthumb[] =$row['full_video_thumb_url'];
        			    $array_videosthumb[] =base_url() .'assets/global/channel_thumb/'.$row['full_video_thumb_url'];
        			}
        			elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			                  $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			       if(!empty($thumb))
                			       {
                                        
                                        
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                        
                                        
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        }    

	                                }
                			    }   
			        }
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			    $sharing_count=0;
        			    $view_count=0;
        			        if(!empty($row['id']))
        			        {
        			        
        			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
        			   
        			        $view_count=$this->Api_model->broadcast_view($row['id']); 
        			        }
        			   
        			   
        			   
        
                       //// echo $this->db->last_query();
        
        			    $broadcast_contents[] = array(
        					'broadcast_id'=>$row['id'],
        					'category_id'=>$row['cat_id'],
        					'category_name'=>$row['name'],
        					'image'=>$images,
        					'video'=>$array_videos,
        					'video_thumbnail'=>$array_videosthumb,
        					'created_date'=>$row['created_date'],
        					'content_description'=>	$row['content_description'],
        					'comment_contents'=>$comment_contents,
        					
        					'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
        					
        					'view_count'=>$view_count,
        					'sharing_count'=>$sharing_count,
        					'self_like'=>$current_user_statue,
        					'like_count'=>$likes_count
        					
        					);
        			
			
			    }
			    
			}
					
            
            
            
            	$ALL_JSON_ARR= array(
				'launch'=>'broadcasting post',
				'user_id'=>$user_details[0]['user_id'],
				'email'=>$user_details[0]['email'],
				'name'=>$user_details[0]['name'],
				'broadcasting_contents'=>$broadcast_contents,
				
				);
		        print json_encode($ALL_JSON_ARR);
            
            
        
		}
		else
		{
		   $JSON_ARR[] = array(
			'response'=>"There is not record"
			);
		    print json_encode($JSON_ARR);
            die();
		}
       
   
 	}
 
 
 
    public function updatemyProfilePicture()
    {
        
        $user_id=$this->input->post('user_id');
        $filename=$_FILES['profile_image']['name'];
		if(!empty($user_id))
		{
			    $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR= array(
								'status'=>false,
								'message'=>"This user does not exist"
								);
						print json_encode($JSON_ARR);
						exit;
						
					}
			
			
			
			
			
			if(empty($filename))
           {
           	$JSON_ARR= array(
    				'status'=>false,
    				'message'=>"Profile image cannot be blank"
    							
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
           }
    			
			
			

					//$data['name']=$name;	
				
                    if(!empty($filename))
                    {
                        $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $uniquesavename=time().uniqid(rand());
                        $unique_image_name=$uniquesavename.'.'.$img_ext;
                        $data['profile_image']=$unique_image_name;
                     move_uploaded_file($_FILES['profile_image']['tmp_name'], 'assets/frontend/profile_image/'.$unique_image_name);
                    }	

						if(!empty($data))
						{	
						$updatesuccess=  $this->Api_model->edit_profile($data,$user_id);
						}
						if($updatesuccess)
						{
							$response_content=array(
							'user_id'=>$user_id,
							'profile_image'=>$this->crud_model->get_profile_image_url($user_id)
							    
							    );
							
							
							$JSON_ARR= array(
							'status'=>true,
							'message'=>"your data has been changed successfully",
						    'response'=>$response_content      
						
							);
							print json_encode($JSON_ARR);  
						}	
						else
						{
							$JSON_ARR= array(
							'status'=>false,
							'message'=>"your data has not been changed successfully"
							
							);
							print json_encode($JSON_ARR);  
							
						}	
		}

		else
		{
			
			$JSON_ARR= array(
			'response'=>"User Id required",
			'success'=>0
			);
			print json_encode($JSON_ARR);	
			
			
		}		

        
        
        
        
    }
 	
 	
 	public function AddFriendUser()
 	{
 	    $user_id=$this->input->post('user_id');
 	    $friend_user_id=$this->input->post('friend_user_id');
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
       
 	    if(empty($friend_user_id))
        {
            $JSON_ARR= array(
				'response'=>"Friend user id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
       
       if(!empty($friend_user_id) and !empty($user_id) )
       {
             $sql = "SELECT * FROM   add_friends  where 1 and   user_id='".$user_id."' and friend_user_id='".$friend_user_id."'" ;
    		$res = $this->db->query($sql);
    		if($res->num_rows()>0)
    		{
    		   $JSON_ARR= array(
				'response'=>"This friend user id already friends"
				);
			print json_encode($JSON_ARR);
            die(); 
    		    
    		}
           
           
       }
       
 	        $data['user_id']=$user_id;
            $data['friend_user_id']=$friend_user_id;
            $data['created_at']=date('Y-m-d');
            
            $this->db->insert('add_friends', $data);
    		$add_friend_id = $this->db->insert_id();
    		
    	    if(!empty($add_friend_id))
	       {
	        	$JSON_ARR = array(
					'response'=>"Friends successfully added",
					'user_id'=>$user_id,
					'friend_user_id'=>$friend_user_id
				);
			    print json_encode($JSON_ARR);

	        }
 	        
 	    
 	    
 	    
 	}
 	
 	public function unFriendUser()
 	{
 	    $user_id=$this->input->post('user_id');
 	    $friend_user_id=$this->input->post('friend_user_id');
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
       
 	    if(empty($friend_user_id))
        {
            $JSON_ARR= array(
				'response'=>"Friend user id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        $delete_friend=$this->Api_model->delete_add_friends($user_id,$friend_user_id);
        
        if(!empty($delete_friend))
        {
                $JSON_ARR= array(
				'response'=>"This friend user id is unfriend successfully",
				'user_id'=>$user_id,
				'friend_user_id'=>$friend_user_id
				
				);
			    print json_encode($JSON_ARR);
        } 
        else       
 	    {
 	         $JSON_ARR= array(
				'response'=>"This friend user id is not unfriend successfully",
				'user_id'=>$user_id,
				'friend_user_id'=>$friend_user_id
				
				);
			    print json_encode($JSON_ARR);
 	        
 	    }
 	    
 	}
 	
 	
 	function random_qrcode() 
 	{
 		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
 		$pass = array(); //remember to declare $pass as an array
 		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
 			for ($i = 0; $i < 14; $i++) {
 				$n = rand(0, $alphaLength);
 				$pass[] = $alphabet[$n];
 			}
 		return implode($pass); //turn the array into a string
 	}

 	
 	
 	public function get_create_myqrcode($user_id=0)
 	{
 	   if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        $sql = "SELECT * FROM   user  where 1 and   user_id='".$user_id."' and qr_bar_code!=''" ;

		$res = $this->db->query($sql);
		if($res->num_rows()>0)
		{
		   $users = $res->result_array();
		   $JSON_ARR= array(
			'user_id'=>$users[0]['user_id'],
			'name'=>$users[0]['name'],
			'email'=>$users[0]['email'],
			 'profile_image'=>$this->crud_model->get_profile_image_url(intval($users[0]['user_id'])) , 
			'qr_code'=>$users[0]['qr_bar_code'],
			'qr_code_image'=>base_url().'assets/global/user_qr_code/'.$users[0]['qr_bar_code_image'],
			);
		print json_encode($JSON_ARR);
        die(); 
		    
		}

			$random_qrcode=$this->random_qrcode();
			$qr_bar_code_image='qr_code_img_'.$user_id.'.png';
			
			 require_once(APPPATH.'libraries/qr_code_master/Ciqrcode.php');
			$ciqrcode_class = new Ciqrcode();
			$qr_code_image = 'assets/global/user_qr_code/'.$qr_bar_code_image;
		  
			$params['data'] = $random_qrcode;
			$params['level'] = 'H';
			$params['size'] = 10;
			$params['savename'] = $qr_code_image;
			$ciqrcode_class->generate($params);

			
			
			$data['qr_bar_code']=$random_qrcode;
            $data['qr_bar_code_image']=$qr_bar_code_image;
            
            $this->db->update('user', $data, array('user_id'=>$user_id));
        	$affected_row=$this->db->affected_rows();
            
            
           
            
            if($affected_row)
            {
                $sql2 = "SELECT * FROM   user  where 1 and   user_id='".$user_id."'" ;
                		$res = $this->db->query($sql2);
        		if($res->num_rows()>0)
        		{
        		   $users = $res->result_array();
        		   $JSON_ARR= array(
        			'user_id'=>$users[0]['user_id'],
        			'name'=>$users[0]['name'],
        			'email'=>$users[0]['email'],
        			'qr_code'=>$users[0]['qr_bar_code'],
        			'qr_code_image'=>base_url().'assets/global/user_qr_code/'.$users[0]['qr_bar_code_image'],
        			);
        		print json_encode($JSON_ARR);
                die(); 
        		}
            }
            else
            {
                $JSON_ARR= array(
        			'user_id'=>$user_id,
        		    'result'=>'QR Code does not generate'
        			);
        		print json_encode($JSON_ARR);
                die(); 
                
            }
            
            
 	 }
 	
 	
 	
 	public function get_myfriends()
 	{
 	    $user_id=$this->input->post('user_id');
 	    $search_string=$this->input->post('search_string');
 	    $sort_order=$this->input->post('sort_order');
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($search_string))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Search string is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        $friends_search=  $this->Api_model->myfriends_search($user_id,$search_string,$sort_order);
        
        //echo $this->db->last_query();
        
        $friend_contents=[];
		if (!empty($friends_search)) 
		{ 	    
				     
		foreach($friends_search as $user_details )		     
		{		     
		        $friend_contents[]=array(
					'user_id'=>intval($user_details['user_id']),
					'user'=>$user_details['name'],
					'useremail'=>$user_details['email'],
					'country_code'=>$user_details['country_code'],
					'mobile'=>$user_details['mobile'],
		            'dob'=>  $user_details['dob'],
		            'name'=>$user_details['name'],
		            'gender'=>$user_details['gender'],
		            
		            'profile_image'=>$this->crud_model->get_profile_image_url(intval($user_details['user_id']))
					);
					
				
		}		    
		
		
		 $JSON_ARR= array(
    				'status'=>true,		
			 		'message'=>"My friends search data",
    				'response'=>$friend_contents
    				
    				);
    			print json_encode($JSON_ARR);
                die();
        
		}

 	}
 	
    public function create_FanPage()
    {
        
        $user_id=$this->input->post('user_id');
 	    $fanpage_name=$this->input->post('fanpage_name');

 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($fanpage_name))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Fan page name is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(!empty($fanpage_name))
        {
        $sql = "SELECT *  FROM fan_page where name ='".trim($fanpage_name)."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$JSON_ARR= array(
							'status'=>false,
							'message'=>"This Fan Page name already exists",
							);
					print json_encode($JSON_ARR);
					die();
					
				}
        }
        
        $data['name']=$fanpage_name;
       	$data['user_id']=$user_id;
       	$data['created_at']=date('Y-m-d');
       	$this->db->insert('fan_page', $data);
    	$fan_page_id = $this->db->insert_id();
            
       	if($fan_page_id)
       	{
       	    
			$resopnse_contents=array(
			'fan_page_id'=>$fan_page_id,
			'fan_page_name'=>$fanpage_name	
		
				);

			$JSON_ARR= array(
				'status'=>true,
				'message'=>"Fan Page name is successfully created ",
				'response'=>$resopnse_contents
				);
			print json_encode($JSON_ARR);
            die();
       	}
        
        
    }
 	
	
	public function get_fanpage_detail($fan_page_id=0)
    {
	
		if(empty($fan_page_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Fan page id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
		$follower_count=$this->Api_model->fanpage_follower_count($fan_page_id);
		$fan_page_details=$this->Api_model->fan_page_details($fan_page_id);
        
		if(!empty($fan_page_details))
				{

		$following_count=$this->Api_model->fanpage_following_count($fan_page_details[0]['user_id']);
		 $response_content=array(
					'name'=>$fan_page_details[0]['name'],
					'user_id'=>$fan_page_details[0]['user_id'],
					'profile_image'=>$this->crud_model->get_fan_profile_image_url($fan_page_details[0]['id']),
					'lives_in'=>$fan_page_details[0]['lives_in'],
					'working_at'=>$fan_page_details[0]['working_at'],
					'website'=>$fan_page_details[0]['website'],
					'phone_mobile'=>$fan_page_details[0]['phone_mobile'],
					'page'=>$fan_page_details[0]['page'],
					'from_location'=>$fan_page_details[0]['from_location'],
			 		'follower_count'=>$follower_count,
					'following_count'=>$following_count
					
					);
        
       
       
       $JSON_ARR= array(
			'status'=>true,
			'message'=>"",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  
        


			




		}
		else
		{
			$JSON_ARR= array(
			'status'=>false,
			'message'=>"This Fan Page does not exist",
			'response'=>''
			);
			print json_encode($JSON_ARR); 
		}

	}

 
 public function get_MyFanPageFollowings()
 {
     $user_id=$this->input->post('user_id');
 	    $search_string=$this->input->post('search_string');
 	    $sort_order=$this->input->post('sort_order');
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        $fanpage_details=  $this->Api_model->get_fanpage_followings($user_id,$sort_order,$search_string);
        
        //echo $this->db->last_query();
       $response_content=[];
        foreach($fanpage_details as $fanpage_detail)
        {
            $response_content[]=array(
               'fan_id'=>$fanpage_detail['fanpage_id'], 
                'fan_name'=>$fanpage_detail['name'] ,
				'image'=>$this->crud_model->get_fan_profile_image_url(intval($fanpage_detail['id'])),
				
				
                );
           
            
        }
     
     
         $JSON_ARR= array(
				'status'=>true,
				'message'=>"fan page list",
				'response'=>$response_content,
				
				);
			print json_encode($JSON_ARR);
     
    }
 
	public function get_MyFanPageFollower()
 {
     //$user_id=$this->input->post('user_id');
 	    $fanpage_id=$this->input->post('fanpage_id');
		$search_string=$this->input->post('search_string');
 	    $sort_order=$this->input->post('sort_order');
 	    
 	    if(empty($fanpage_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Fanpage id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        $fanpage_details=  $this->Api_model->get_MyFanPageFollower($fanpage_id,$sort_order,$search_string);
        
      // echo $this->db->last_query();
       $response_content=[];
        foreach($fanpage_details as $fanpage_detail)
        {
            $response_content[]=array(
               
				'user_id'=>$fanpage_detail['user_id'],
                'name'=>$fanpage_detail['name'],
				'email'=>$fanpage_detail['email'],
				'profile_image'=>$this->crud_model->get_profile_image_url($fanpage_detail['user_id'])
                );
        }
     
     
         $JSON_ARR= array(
				'status'=>true,
				'message'=>"",
				'response'=>$response_content,
				
				);
			print json_encode($JSON_ARR);
     
    }


	



    public function create_channelpage()
    {
        //channel_page
        
        $user_id=$this->input->post('user_id');
 	    $channel_name=$this->input->post('channel_name');
		
		



 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($channel_name))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Channel Name is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(!empty($channel_name))
        {
        $sql = "SELECT *  FROM channel_page where name ='".trim($channel_name)."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$JSON_ARR= array(
						'status'=>false,
							'message'=>"This Channel Name already exists",
							);
					print json_encode($JSON_ARR);
					die();
					
				}
        }
        
		
		if(!empty($user_id))
        {
        $sql = "SELECT *  FROM channel_page where user_id ='".$user_id."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$JSON_ARR= array(
						'status'=>false,
							'message'=>"This Channel Name already exists",
							);
					print json_encode($JSON_ARR);
					die();
					
				}
        }		



        $data['name']=$channel_name;
       	$data['user_id']=$user_id;
       	$data['created_at']=date('Y-m-d');
       	$this->db->insert('channel_page', $data);
    	$fan_page_id = $this->db->insert_id();
            
       	if($fan_page_id)
       	{
       	    $response_content=array(
				'channel_page_id'=>$fan_page_id,
				'channel_page_name'=>$channel_name
							    );
			
				$JSON_ARR= array(
				'status'=>true,
				'message'=>"Channel Name is successfully created",
				'response'=>$response_content
				);
			print json_encode($JSON_ARR);
            die();
       	}
        else
        {
             $JSON_ARR= array(
				 'status'=>false,
				'message'=>"Channel Name is not successfully created "
				);
			 print json_encode($JSON_ARR);
             die();
            
            
        }

    }

	
	public function update_channelpage()
    {
		$channelpage_id=$this->input->post('channelpage_id');
 	    $channel_name=$this->input->post('channel_name');
		
		$channel_type=$this->input->post('channel_type');
		$lives_in=$this->input->post('lives_in');
		$from_location=$this->input->post('from_location');
		$workingat=$this->input->post('workingat');	
		$website=$this->input->post('website');	
		$phone_number=$this->input->post('phone_number');	
		//$joined=$this->input->post('joined');	

			
		$description=$this->input->post('description');	

		if(empty($channelpage_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Channelpage id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

	if(!empty($channelpage_id))
      {
            $channel_details=$this->Api_model->channepage_details($channelpage_id);
			if(empty($channel_details))
			{
			$JSON_ARR= array(
				'status'=>false,
				'message'=>"This channel Page does not exist"
				);
			print json_encode($JSON_ARR);
            die();
        	}
	}

		
	
		if(empty($channel_name))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Channel name is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
		
	if(!empty($channel_name))
    {
    $sql = "SELECT *  FROM channel_page where 	name ='".$channel_name."' and id!='".$channelpage_id."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					$JSON_ARR= array(
						'status'=>false,
							'message'=>"This name already created",
							);
					print json_encode($JSON_ARR);
					die();
					
				}

	}	

	
	
	/*	
		if(empty($channel_type))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Channel type is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

		if(empty($lives_in))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"lives in is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

		if(empty($from_location))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"From location is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		if(empty($workingat))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Workingat is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

		if(empty($website))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Website is required"
				);
			print json_encode($JSON_ARR);
            die();
        }	
		
		if(empty($phone_number))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Phone_number is required"
				);
			print json_encode($JSON_ARR);
            die();
        }	
	
		
		if(empty($description))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Description is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
		*/
		$data['channel_type']=$channel_type;
		$data['name']=$channel_name;
		$data['lives_in']=$lives_in;
		$data['from_location']=$from_location;
		$data['workingat']=$workingat;	
		$data['website']=$website;	
		$data['phone_number']=$phone_number;	
		$data['description']=$description;	

		$this->db->where('id', $channelpage_id);
		$this->db->update('channel_page', $data);
		
		$response_content=array(
				'channel_page_id'=>$channelpage_id
				);


		 $JSON_ARR = array(
				'message'=>"your data has been update successfully",
				'status'=>true,
				'response'=>$response_content
				);
				print json_encode($JSON_ARR);

	}
	

public function get_channelpage_detail($channel_id=0)
    {
	
		if(empty($channel_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Channel id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
	

			
		$channel_details=$this->Api_model->channepage_details($channel_id);
		
		$sql_subscribe = "select count(*) as nos from channel_page_subscribe where channel_id='".$channel_id."'";
         $res_subscribe = $this->db->query($sql_subscribe);
         $channel_page_subscribe = $res_subscribe->result_array();


        
		if(!empty($channel_details))
				{
			
			$channel_about=array(
			'channel_type'=>$channel_details[0]['channel_type'],
			'lives_in'=>$channel_details[0]['lives_in'],
			'from'=>$channel_details[0]['from_location'],
			'workingat'=>$channel_details[0]['workingat'],
			'website'=>$channel_details[0]['website'],
			'phone_number'=>$channel_details[0]['phone_number'],
			'joined'=>$channel_details[0]['created_at'],
			'totatlviews'=>102,
			'description'=>$channel_details[0]['description'],
			); 
			$response_content=array(
				'channel_page_id'=>intval($channel_details[0]['id']),
				'user_id'=>	$channel_details[0]['user_id'],
				'name'=>$channel_details[0]['name'],
				'image'=>base_url() . 'assets/global/channel_page_image/'.$channel_details[0]['image_file'],
				'channel_isverified'=>$channel_details[0]['channel_isverified'],
				'channel_subscriber'=>intval($channel_page_subscribe[0]['nos']),
				 'channel_Videos'=>"",
				'channel_about'=>$channel_about
						
					);
       
       $JSON_ARR= array(
			'status'=>true,
			'message'=>"",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  
        


			




		}
		else
		{
			$JSON_ARR= array(
			'status'=>false,
			'message'=>"This channel Page does not exist",
			'response'=>''
			);
			print json_encode($JSON_ARR); 
		}

	}

public function get_channelpage_uwatch_detail()
    {
	
		$channelpage_id=$this->input->post('channelpage_id');
	
		$page_no=$this->input->post('page');
        $limit=20; 
         //expression ? trueValue : falseValue
         $page=(!empty($page_no)) ? $page_no : 1;
         if($page==1 or $page==0 )
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }
	



		if(empty($channelpage_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Channel page id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
	 $sql = "select * from u_watch where  channelpage_id='".$channelpage_id."' order by created_date desc limit $start,$limit ";
     
       $uwatch_content=[];
        $res = $this->db->query($sql);
         $uwatches = $res->result_array();
		
		foreach ($uwatches as $uwatche)	
		{
			
			$sql_view = "SELECT * FROM u_watch_view where 
			 u_watch_id='".$uwatche['u_watch_id']."'";
			$res_view = $this->db->query($sql_view);
			
			$view_count= $res_view->num_rows();
			
			$increase_view=$this->Api_model->increase_uwatch_view($uwatche['u_watch_id']);			
				$view_count=$view_count+$increase_view;


			if(!empty($uwatche['url']))
			{
				$video_url="https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/Content_Provider_folder/".$uwatche['url']."/playlist.m3u8";
			
			}
			


					$uwatch_content[]= array(
					 "user_id"=>$uwatche['user_id'],
					 "u_watch_id" => $uwatche['u_watch_id'],
					 "title"=>$uwatche['title'],
					 "u_watch_url"=>$video_url,
					"duration"=>$uwatche['duration'],
					"view_count"=>$view_count,
					"created_time"=>$this->crud_model->time_elapsed_string($uwatche['created_date']),
					 "u_watch_thumb"=>$this->crud_model->get_uwatch_thumb_url( $uwatche['u_watch_thumb'])

				   );	
			
       
		}				

		 $sql_channel = "select * from channel_page where id='".$channelpage_id."'";
         $res_channel = $this->db->query($sql_channel);
         $channel_page_rec = $res_channel->result_array();
       
	 	$sql_subscribe = "select count(*) as nos from channel_page_subscribe where channel_id='".$channelpage_id."'";
         $res_subscribe = $this->db->query($sql_subscribe);
         $channel_page_subscribe = $res_subscribe->result_array();
	
		$JSON_ARR= array(
			'status'=>true,
		   	'channel_page_id'=>intval($channelpage_id),
			'channel_name'=>$channel_page_rec[0]['name'],
			'image'=>base_url() . 'assets/global/channel_page_image/'.$channel_page_rec[0]['image_file'],
			'subscriber'=>intval($channel_page_subscribe[0]['nos']),
			'message'=>"",
			'response'=>$uwatch_content
			);
			print json_encode($JSON_ARR);  
        


			




		

	}





    public function  create_fan_page_follow()
    {
        $user_id=$this->input->post('user_id');
 	    $fanpage_id=$this->input->post('fanpage_id');
 	    
 	    
 	    if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($fanpage_id))
        {
            $JSON_ARR= array(
				'response'=>"Fan page id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
         $data['fanpage_id']=$fanpage_id;
       	$data['user_id']=$user_id;
       	$data['created_at']=date('Y-m-d');
       	$this->db->insert('fan_page_follow', $data);
    	$fan_page_follow_id = $this->db->insert_id();
    	
    	if($fan_page_follow_id)
       	{
       	    $JSON_ARR= array(
				'result'=>"Fan page followed successfully",
				'user_id'=>$user_id,
				);
			print json_encode($JSON_ARR);
            die();
       	}
        else
        {
             $JSON_ARR= array(
				'result'=>"Fan page not followed successfully"
				);
			 print json_encode($JSON_ARR);
             die();
            
            
        }
    	
    	

    }




    public function get_app_update_notification($device_name="")
 	{
 	     
 	     if(empty($device_name))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Device name is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
 	     

      $sql = "select * from application_notification where   create_date >= NOW() - INTERVAL 1 DAY order by create_date desc limit 1 ";
        
        $content=[];
        $res = $this->db->query($sql);
        
		$flag=0;
		if ($res->num_rows() > 0) 
	    {
           $notifications = $res->result_array();
           $notification_send= (string) $notifications[0]['notification_send'];

       
            if($notification_send=='ALLDEVICE')
            {
                $flag=1;
            }
            elseif($notification_send==$device_name)
            {
                $flag=1;
            }
            
            
              if($flag==1)
              {
            	
                    $response_content=array(
        			 "device_name"=>$device_name,
					 "notification"=>$notifications[0]['notification'],
					 
        			);
                    	
            	
            	$content= array(
            	    'status'=>true,
            	    'message'=>'application_notification',
					"response"=>$response_content
				   );	
              }
              else
              {
                   $content = array(
                    'status'=>false,
	                'message'=>'Not application notification',
	                'response'=>''
					); 
                  
              }
              
              print json_encode($content);
              
	    }
	    else
	    {
	        $content = array(
                    'status'=>false,
	                'message'=>'Not application notification',
	                'response'=>''
					); 
			print json_encode($content);		
	        
	    }
	    
	    
	}



 function create_broadcast_post()
    {
        $user_id=$this->input->post('user_id');
		$cat_id=$this->input->post('cat_id');    
        $content_description=$this->input->post('content_description');
        $post_privacy_type=$this->input->post('post_privacy_type');
        
        $lat=$this->input->post('lat');
        $long=$this->input->post('long');
        $location_name=$this->input->post('location_name');
        
        
        if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        if(empty($cat_id))
        {
            $JSON_ARR= array(
				'response'=>"Cat id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
         if(empty($post_privacy_type))
        {
            $JSON_ARR= array(
				'response'=>"Post privacy type is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
         if(empty($location_name))
        {
            $JSON_ARR= array(
				'response'=>"Location name is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
        
        
        

        if(!empty($user_id) and !empty($cat_id))
        {
            
          
            
            $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR[] = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
	
	
	

	
		
            $data['post_privacy_type']=$post_privacy_type;
            $data['location_name']=$location_name;
            $data['longitude']=($long=="")  ? "" : trim($long);
            $data['latitude']=($lat=="")  ? "" : trim($lat);
            
            $data['user_id']=$user_id;
            $data['cat_id']=$cat_id;
            $data['content_description']=$content_description;
            $data['created_date']=date('Y-m-d H:i:s');
            
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();
            
            
            $platformcraft_token=$this->get_platformcraft_token();
            

            
            $array_image=[];
            for($i=0; $i<count($_FILES['broadcast_img']['name']); $i++) 
            {
            
                if($_FILES['broadcast_img']['name'][$i]!="")
                {
                    
                    //////////////////////////////////////////////////
                 
                 
                 $tmp_image='';
                 $filename=$_FILES['broadcast_img']['name'][$i];
                 $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                   

                  $_FILES['broadcast_img']['tmp_name'][$i]=$this->compressImage($_FILES['broadcast_img']['tmp_name'][$i],$_FILES['broadcast_img']['tmp_name'][$i],$img_ext);
                  

                   
                   $filename=$_FILES['broadcast_img']['name'][$i];
                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $array_image[]='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    move_uploaded_file($_FILES['broadcast_img']['tmp_name'][$i], 'assets/global/broadcast/images/'.'img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext);
                   
                   
                             

                }
              
            }
            
            $array_video=[];
            
           // print_r($_FILES['broadcast_video']);
           
           
            for($ii=0; $ii<count($_FILES['broadcast_video']['name']); $ii++) 
            {

               if($_FILES['broadcast_video']['name'][$ii]!="")
                { 
                    
                    ///////////////video_thumblain////////////////////////
    			require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                
                $movie=$_FILES['broadcast_video']['tmp_name'][$ii];
                
                $extension='.jpeg';
    		    $tmp_video_thumb='videothumb_'.$broadcast_id .'_'.($ii+1).$extension;   
                    
                
                $thumbnail = 'assets/global/broadcast/video_thumb/'.$tmp_video_thumb;
                $video = $ffmpeg->open($movie);
               
                    
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                
                $frame->save($thumbnail);
                $array_video_thumb[]=$tmp_video_thumb;
    			

                    
                    $tmp_video_name='';
                    $filename1=$_FILES['broadcast_video']['name'][$ii];
                    $video_ext = pathinfo($filename1, PATHINFO_EXTENSION);
                        
                        
                    ///$array_video[]='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                        
                    $tmp_video_name='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                       
                    
                   $url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video/'.$tmp_video_name;
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    				curl_setopt($ch, CURLOPT_POST, 1);
                    $args['file'] = new CurlFile($_FILES['broadcast_video']['tmp_name'][$ii],'video/mp4',$_FILES['broadcast_video']['name'][$ii]);
                    
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'Authorization: Bearer '.$platformcraft_token,
    				'Content-Type: multipart/form-data'
    				
    	            ));
    				$result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
    				//$array_video[]=$response->download_url;
    			    

    			   // $array_video[]=$response->hls;	
    				if(empty($response->hls))
    				{
    				    $array_video[]=$response->download_url;
    				}
    				else
    				{
    				     $array_video[]="happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/broadcast_video/".$tmp_video_name."/playlist.m3u8";
    				    
    				    
    				}
    				
                }
                

            }
            

            $str_image= implode(",",$array_image);
            $str_video= implode(",",$array_video);
            $str_video_thumb= implode(",",$array_video_thumb);
            
            
            
            $data2=array(
                "broadcast_img"=>$str_image,
                "broadcast_video"=>$str_video,
                "broadcast_video_thumbnail"=>$str_video_thumb
                );
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data2);

            $JSON_ARR[] = array(
				'response'=>"your data has been saved successfully",
				'success'=>1,
				'broadcast_id'=>$broadcast_id,
				'user_id'=>$user_id,
				'category_id'=>$cat_id
				);
				print json_encode($JSON_ARR);

        }
        else
        {
            	$JSON_ARR[] = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
        }

    }
   
   
   public function update_Profile_Data()
   {
            $user_id=$this->input->post('user_id');
            $name=trim($this->input->post('name'));
            $email=$this->input->post('email');
			
			$gender=$this->input->post('gender');
			
			$country_name=$this->input->post('country_name');
			
			$country_code=$this->input->post('country_code');
			$mobile=$this->input->post('mobile');
			$dob=trim($this->input->post('dob'));
			
			
			
			$lives_in=trim($this->input->post('lives_in'));
			$from_location=trim($this->input->post('from_location'));
		
    		$marital_status=trim($this->input->post('marital_status'));
    		
    		$relationship_status=trim($this->input->post('relationship_status'));
    		
    		$studied_at=trim($this->input->post('studied_at'));
    		
    		$working_at=trim($this->input->post('working_at'));
	   
			$relationship_start_date=trim($this->input->post('relationship_start_date'));
	   
			$relationship_privacy=trim($this->input->post('relationship_privacy'));   

			$relationship_with=trim($this->input->post('relationship_with'));   



    		
		
		if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        /*
        if(empty($name))
        {
            $JSON_ARR= array(
				'response'=>"Name is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
       */ 
	   
        if(empty($email))
        {
            $JSON_ARR= array(
				'response'=>"Email is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(!empty($email))
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    		{
    		 $JSON_ARR = array(
    		 'response'=>"Not valid email",
    		 );
    					print json_encode($JSON_ARR);
    					die();
    					
    		}
        }    
        
        
        
        
        /*
         if(empty($gender))
        {
            $JSON_ARR= array(
				'response'=>"Gender is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($country_name))
        {
            $JSON_ARR= array(
				'response'=>"Country name is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		if(empty($country_code))
        {
            $JSON_ARR= array(
				'response'=>"Country code is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }	
		*/	
		if(empty($mobile))
        {
            $JSON_ARR= array(
				'response'=>"Mobile is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }		
		
	   

		/*
		if(empty($dob))
        {
            $JSON_ARR= array(
				'response'=>"DOB required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }			
		*/
		if(!empty($user_id))
		{
			    $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR[] = array(
								'response'=>"This user does not exist"
								);
						print json_encode($JSON_ARR);
						die();
						
					}
		}
		
		
		
		if(!empty($email))
		{
		 $sql = "SELECT *  FROM user where 1 and email ='".trim($email)."' and user_id!='".$user_id."'";
			$res_email = $this->db->query($sql);
			if ($res_email->num_rows() > 0) 
			{
				$JSON_ARR = array(
						'response'=>"This email already exist",
						);
				print json_encode($JSON_ARR);
				die();
				
			}
		
		}
		if(!empty($mobile))
		{	
			$sql = "SELECT *  FROM user where 
			1 and  mobile='".trim($mobile)."' and user_id!='".$user_id."'";
			$res_phone = $this->db->query($sql);
			if ($res_phone->num_rows() > 0) 
			{
				//return $row;
				$JSON_ARR[] = array(
						'response'=>"This phone already exist",
						);
				print json_encode($JSON_ARR);
				die();
				
			}
	
		}
			
					
			//"country_code"=>$country_code,
        	

	   		$data=array(
			"email"=>$email,
			"name"=>$name,
			"country"=>$country_name,
			
			"mobile"=>$mobile,
			"gender"=>$gender,
			"dob"=>$dob,
			"lives_in"=>($lives_in=="")  ? "" : trim($lives_in),
			"from_location"=>($from_location=="")  ? "" : trim($from_location),
			"marital_status"=>($marital_status=="")  ? "" : trim($marital_status),
			"relationship_status"=>($relationship_status=="")  ? "" : trim($relationship_status),
			"studied_at"=>($studied_at=="")  ? "" : trim($studied_at),
			"working_at"=>($working_at=="")  ? "" : trim($working_at),
			"relationship_start_date"=>($relationship_start_date=="")  ? "" : trim($relationship_start_date),
			"relationship_privacy"=>($relationship_privacy=="")  ? "" : trim($relationship_privacy),
			"relationship_with"	=>($relationship_with=="")  ? "" : trim($relationship_with)

			);
			
            if(!empty($data))
			{	
			$updatesuccess=  $this->Api_model->edit_profile($data,$user_id);
			}
			if($updatesuccess)
			{
				$JSON_ARR = array(
				'status'=>true,
				'response'=>"your data has been changed successfully",
				'message'=>"",
				'user_id'=>$user_id,
				'name'=>$name,
				'profile_image'=>$this->crud_model->get_profile_image_url($user_id)
				);
				print json_encode($JSON_ARR);  
			}	
			else
			{
				$JSON_ARR= array(
				'status'=>false,
				'response'=>"",
				'message'=>"your data has not been changed successfully",
				);
				print json_encode($JSON_ARR);  
				
			}	
   }
 
 
 	public function change_Password()
	{
	  $current_password=$this->input->post('current_password');
	  $new_password=$this->input->post('new_password');
	  $user_id=$this->input->post('user_id');
	  
	  	if(empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($current_password))
        {
            $JSON_ARR= array(
				'response'=>"Current password is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
	 
	 if(empty($new_password))
        {
            $JSON_ARR= array(
				'response'=>"New password is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
	 
	 
			$user_details=	$this->db->get_where('user', array('user_id'=>$user_id))->row();
			$old_password_encrypted=$user_details->password;
			

			$old_password_submitted_encrypted	=	sha1($current_password);
			
			$new_password_encrypted				=	sha1($new_password);

			if (strlen($new_password) <6)
			{
                $JSON_ARR = array(
    			'response'=>"New Password Must Be At Least 6 Character Long. Please Try Again."
    			);
    			print json_encode($JSON_ARR);      
    			die();	
			
			}

			// CORRECT OLD PASSWORD NEEDED TO CHANGE PASSWORD
			if ($old_password_encrypted 		==	$old_password_submitted_encrypted)
			{
				
				$this->db->update('user', array('password'=>$new_password_encrypted), array('user_id'=>$user_id));
			
	            //$email_to=$user_details->email;
    			//$email_msg	=	"Your new password is : ".$new_password;
    			//$email_sub	=	"Password reset request";
    		    //$this->email_model->Do_email($email_msg , $email_sub , $email_to);
    		

    			$JSON_ARR = array(
        			'status'=>true,
        			'response'=>"your password has been changed successfully",
        			'message'=>""
    			);
    			print json_encode($JSON_ARR);	

			}
			else
			{

			$JSON_ARR = array(
			'status'=>false,
			'response'=>"",
			'message'=>"Old password does not match"
			);
			print json_encode($JSON_ARR);  
			}

	}


public function upload_fanpage_Profile_picture()
    {
        
        $fanpage_id=$this->input->post('fanpage_id');
        $filename=$_FILES['profile_image']['name'];
		
		
		
		if(empty($fanpage_id))
           {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Fan page id cannot be blank"
    							
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
           }
		
		if(!empty($fanpage_id))
		{
			    $sql = "SELECT *  FROM fan_page where 1 and id='".$fanpage_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR= array(
								'status'=>false,
								'message'=>"This Fan does not exist"
								);
						print json_encode($JSON_ARR);
						die(0);
						
					}
			
			
		}
			
			
			if(empty($filename))
           {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Profile image cannot be blank"
    							
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
           }
    			
			
			

					//$data['name']=$name;	
				
                    if(!empty($filename))
                    {
                        $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $uniquesavename=time().uniqid(rand());
                        //$unique_image_name=$uniquesavename.'_'.$fanpage_id.'.'.$img_ext;
                        
                        $unique_image_name=md5($fanpage_id).'_'.$fanpage_id.'.'.$img_ext;
                        $data['profile_image']=$unique_image_name;
                     move_uploaded_file($_FILES['profile_image']['tmp_name'], 'assets/frontend/fan_profile_image/'.$unique_image_name);
                    }	

						if(!empty($data))
						{	
						$updatesuccess=  $this->Api_model->edit_fan_profile($data,$fanpage_id);
						}
						if($updatesuccess)
						{
							
							$response_content=array(
							   'fan_page_id'=>$fanpage_id,
							'profile_image'=>$this->crud_model->get_fan_profile_image_url($fanpage_id) 
							    
							    );
							
							
							
							$JSON_ARR= array(
							'status'=>true,
							'message'=>"your data has been changed successfully",
							'response'=>$response_content
							);
							print json_encode($JSON_ARR);  
						}	
						else
						{
							$JSON_ARR= array(
							'status'=>false,
							'message'=>"your data has not been changed successfully"
							);
							print json_encode($JSON_ARR);  
							
						}	
		}

		
   
 	
 
    public function update_fanpage_Profile_Data()
    {
            $fanpage_id=$this->input->post('fanpage_id');
            $name=trim($this->input->post('name'));
            $page=$this->input->post('page');
			$mobile=$this->input->post('phone_mobile');

			$lives_in=trim($this->input->post('lives_in'));
			$from_location=trim($this->input->post('from_location'));
    		$website=trim($this->input->post('website'));
    		$working_at=trim($this->input->post('working_at'));
    		
    		if(empty($fanpage_id))
            {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Fan page id is required"
    							
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
            }
	
	        if(empty($name))
            {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Name is required"
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
            }
 
			 /*
            
			if(empty($page))
            {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Page is required"
    				);
    				print json_encode($JSON_ARR);
           			
            die(0);
            }

            if(empty($mobile))
            {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Mobile is required"
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
            }
		*/

    $data=array(
	'name'=>$name,
	'lives_in'=>($lives_in=="")  ? "" : trim($lives_in),
	'working_at'=>($working_at=="")  ? "" : trim($working_at),
	'website'=>($website=="")  ? "" : trim($website),
	'phone_mobile'=>$mobile,
	'page'=>$page,
	'from_location'=>($from_location=="")  ? "" : trim($from_location)
    );

            if(!empty($data))
			{	
			$updatesuccess=  $this->Api_model->edit_fan_profile($data,$fanpage_id);
			}
			if($updatesuccess)
			{
				
			$response_content=array(
			'fanpage_id'=> $fanpage_id,
			'name'=>$name,
			);
				
				
			$JSON_ARR = array(
			'status'=>true,
			'message'=>"your data has been updated successfully",
			'response'=>$response_content,
			);
			print json_encode($JSON_ARR);  
			}	
			else
			{
				$JSON_ARR= array(
				'status'=>false,
				'message'=>"your data has not been changed successfully",
				);
				print json_encode($JSON_ARR);  
				
			}	
    }
 
 
 public function get_channel_category()
 {
        $this->db->select('*');
		$this->db->from('category');
		$query = $this->db->get();
		$categoryes= $query->result_array();
     
 
     $response_content=[];
	foreach($categoryes as $row)
    { 
     	$response_content[]=array(
		'id'=>$row['cat_id'],
		'name'=>$row['name']
		);		
    }						
		$JSON_ARR = array(
			'status'=>true,
			'message'=>"",
			'response'=>$response_content,
			);
			print json_encode($JSON_ARR);  
     
     
     
 }
 
 
 public function insert_content_provider_application()
 {
      
      $user_id=$this->input->post('user_id');
      
      $name=$this->input->post('name');
      
      $gender=$this->input->post('gender');
      $country=$this->input->post('country');
      $street=$this->input->post('street');
      $city=$this->input->post('city');
      $state=$this->input->post('state');
      $postal_zip_code=$this->input->post('postal_zip_code');
      $country_code=$this->input->post('country_code');
      
      
      $mm=$this->input->post('mm');
      $yy=$this->input->post('yy');
      $dd=$this->input->post('dd');
      
      $email=$this->input->post('email');
      
      $phone_number=$this->input->post('phone_number');
      $channel_category_id=$this->input->post('channel_category_id');
      
      $channel_description=$this->input->post('channel_description');
      
      $facebook_link=$this->input->post('facebook_link');
      
      $youtube_link=$this->input->post('youtube_link');
      $bank_account_holder_name=$this->input->post('bank_account_holder_name');
      
      $bank_name=$this->input->post('bank_name');
      
      $bank_routing_number=$this->input->post('bank_routing_number');
      
      $bank_account_number=$this->input->post('bank_account_number');
      
      
      
      $agreed_to_terms=$this->input->post('agreed_to_terms');
      
      
      
      if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
		$sql = "SELECT *  FROM content_provider_application where user_id ='".$user_id."'";
				
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					//$row = $res->result_array();
					//return $row;
					$JSON_ARR= array(
							'response'=>"This user already exists",
							'success'=>0
							);
					print json_encode($JSON_ARR);
					die();
				}
				 





        
        if(empty($name))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Name is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($email))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Email is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(!empty($email))
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    		{
    		 $JSON_ARR = array(
    		 'status'=>false,    
    		 'response'=>"Not valid email",
    		 );
    					print json_encode($JSON_ARR);
    					die();
    					
    		}
        }    


        if(empty($phone_number))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Phone/Mobile is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
      
      
        if(empty($gender))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Gender is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($country))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Country is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        

        if(empty($country_code))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Country code is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

        if(empty($state))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"State is required"
				);
			print json_encode($JSON_ARR);
            die();
        }


        if(empty($city))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"City is required"
				);
			print json_encode($JSON_ARR);
            die();
        }



        if(empty($street))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Street is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if(empty($postal_zip_code))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Postal zip code is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
        if(empty($mm))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Month is required"
				);
			print json_encode($JSON_ARR);
            die();
        }    
        
        if(empty($yy))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Year is required"
				);
			print json_encode($JSON_ARR);
            die();
        }    
        
        if(empty($dd))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Day is required"
				);
			print json_encode($JSON_ARR);
            die();
        }    
        
        $dob=$yy.'-'.$mm.'-'.$dd;

        if(empty($channel_category_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Category id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }    
        
        if(empty($channel_description))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Channel description is required"
				);
			print json_encode($JSON_ARR);
            die();
        }    
     
     if(empty($facebook_link))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Facebook link is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
     
    if(empty($youtube_link))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Youtube link is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

        if(empty($bank_account_holder_name))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Bank account holder name is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

    

        if(empty($bank_name))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Bank name is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
    
    
     if(empty($bank_routing_number))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Bank routing number is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
        if(empty($bank_account_number))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Bank account number is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        
        
        
        
        
        
    
    if(empty($agreed_to_terms))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Agreed to terms is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
    
    
    
 $data=array(   
    'name'=>$name,
	'user_id'=>$user_id,
    'gender'=>$gender,
    'country'=>$country,
    'street'=>$street,
    'city'=>$city,
    'state'=>$state,
    'postal_zip_code'=>$postal_zip_code,
    'country_code'=>$country_code,
    'dob'=>$dob,
    'email'=>$email,
    'phone_number'=>$phone_number,
    'category_id'=>$channel_category_id,
    'channel_description'=>$channel_description,
    'facebook_link'=>$facebook_link,
    'youtube_link'=>$youtube_link,
    'bank_account_holder_name'=>$bank_account_holder_name,
    'bank_name'=>$bank_name,
    'bank_routing_number'=>$bank_routing_number,
    'bank_account_number'=>$bank_account_number,
    'status'=>1,
    'agreed_to_terms'=>$agreed_to_terms
    );
     
     
     
     
     
     if(!empty($data))
			{	
			$insertsuccess=  $this->Api_model->content_provider_application($data);
			}
			if($insertsuccess)
			{
				
			$response_content=array(
			'id'=> $insertsuccess,
			'name'=>$name,
			);
				
				
			$JSON_ARR = array(
			'status'=>true,
			'message'=>"your data has been insert successfully",
			'response'=>$response_content,
			);
			print json_encode($JSON_ARR);  
			}	
			else
			{
				$JSON_ARR= array(
				'status'=>false,
				'message'=>"your data has not been changed successfully",
				);
				print json_encode($JSON_ARR);  
				
			}	

 }
 


public function uwatch_channel_page_subscribed()
{
    $user_id=$this->input->post('user_id');
    $channel_id=$this->input->post('channel_id');
    $subscribe=$this->input->post('subscribe');
    
    if(empty($user_id))
    {
        $JSON_ARR= array(
            'status'=>false,
			'response'=>"User id is required"
			
			);
		print json_encode($JSON_ARR);
        die();
    }
    
    if(empty($channel_id))
    {
        $JSON_ARR= array(
            'status'=>false,
			'response'=>"Channel id is required"
			
			);
		print json_encode($JSON_ARR);
        die();
    }  
    
    if($subscribe<>0 and $subscribe<>1 )
    {
        $JSON_ARR= array(
            'status'=>false,
			'response'=>"Subscribe(0/1) is required"
			
			);
		print json_encode($JSON_ARR);
        die();
    }  
     
     
     if($subscribe==1)
     {
        $data=array(  
            "user_id"=>$user_id,
            "channel_id"=>$channel_id,
            "subscribe"=>$subscribe,
            "created_at"=>date('Y-m-d')	
        );
        $this->db->insert('channel_page_subscribe', $data);
        $channelsubscribe_id = $this->db->insert_id();
        
        	
			$JSON_ARR = array(
			'status'=>true,
			'message'=>"channel subscribe successfully",
			);
			print json_encode($JSON_ARR);  

        
     }
     elseif($subscribe==0)
     {
         
        $this->db->where('user_id', $user_id);
		$this->db->where('channel_id',$channel_id);
		$broadcast_delete=$this->db->delete('channel_page_subscribe'); 
        $JSON_ARR = array(
			'status'=>true,
			'message'=>"channel Un subscribe successfully",
			);
			print json_encode($JSON_ARR);  

         
     }
    
    
}



public function get_my_uwatch_channel_subscribers()
{
    $channel_page_id=$this->input->post('channel_page_id');
	$search_string=$this->input->post('search_string');
 	$sort_order=$this->input->post('sort_order');
 	    
 	    if(empty($channel_page_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Channel id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }

     $subscribers_list=  $this->Api_model->myuwatch_channel_subscribers($channel_page_id,$sort_order,$search_string);
    
    
    //print_r($subscribers_list);
    //exit;
    
    $subscribers_contents=[];
    foreach($subscribers_list as $row)
    {
    $subscribers_contents[]=array(
        
        'subscriber_id'=>$row['id'],
		'channel_id'=>$row['channel_id'],
		'subscribe'=>$row['subscribe'],
		'created_at'=>$row['created_at'],
		'user_id'=>$row['user_id'],
		'name'=>$row['name'],
		'email'=>$row['email'],
		'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
		
        
        
        );    
        
        
    }
	
	 $JSON_ARR= array(
                'status'=>true,
				'message'=>'',
		 		'response'=>$subscribers_contents
				
				);
			print json_encode($JSON_ARR);

    
}





    public function search()
	{
		//$search_key=urldecode($this->uri->segment(3));
		
		$search_key=$this->input->post('search_key');
		$user_id=$this->input->post('user_id');
		
		if(empty($search_key))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Search key is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
		
		
		if(!empty($search_key))
		{	
				//$movies		=	$this->crud_model->get_search_result('movie' , $search_key);
				///$series		=	$this->crud_model->get_search_result('series', $search_key);
				
				
				
				///$live=	$this->crud_model->get_search_result_live( $search_key);
				//$live_songs=	$this->crud_model->get_search_result_live_songs( $search_key);
				
				//$u_watch=	$this->crud_model->get_search_result_uwatch( $search_key);
				
				$user_result= $this->Api_model->search_user($search_key,$user_id);
				//echo $this->db->last_query();
				///exit;
				
				$fan_page_result= $this->Api_model->search_fan_page($search_key);
				
				$channel_page_result= $this->Api_model->search_channel_page($search_key);
				
				

            
                $user_contents=[];
            	foreach($user_result as $user_details )		     
		        {		     
		        $user_contents[]=array(
					'user_id'=>intval($user_details['user_id']),
					'user'=>$user_details['name'],
		            'profile_image'=>$this->crud_model->get_profile_image_url(intval($user_details['user_id'])),
		            'type'=>'user'
					);
					
				
		        }		    
            

            $fan_page_contents=[];
            	foreach($fan_page_result as $fanpage_details )		     
		        {		     
		        $fan_page_contents[]=array(
					'fan_page_id'=>intval($fanpage_details['id']),
					'name'=>$fanpage_details['name'],
					'profile_image'=>$this->crud_model->get_fan_profile_image_url($fanpage_details['id']),
					'type' => 'fan_page'
					
					);
					
				
		        }		    
				
		
		$channel_page_contents=[];
            	foreach($channel_page_result as $channelpage_details )		     
		        {		     
		        $channel_page_contents[]=array(
					'fan_page_id'=>intval($channelpage_details['id']),
					'name'=>$channelpage_details['name'],
					'image'=>base_url() . 'assets/frontend/channel_page_image/'.$channelpage_details['image_file'],
						'type' => 'channel_page'
					);
					
				
		        }		    
		
		$output = array_merge($user_contents, $fan_page_contents,$channel_page_contents);
		
			$search_content= array(
			'status'=>true,
			'message'=>'',
			'response'=>$output
			
			);	
			print json_encode($search_content);
		
		}
	}




public function getsearcheduserData()
{
       $user_id=$this->input->post('user_id');
       $searched_user_id=$this->input->post('searched_user_id');    

      if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
         
        if(empty($searched_user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Searched user id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        $user_id=$this->input->post('user_id');
		 $sql = "SELECT * FROM  add_friends 
		where user_id='".$user_id."' and friend_user_id='".$searched_user_id."'";
		
		$is_myfrien=0;
		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
			$is_myfrien=1;
			    
			}
			
		
        
        
        
        
        $user_details=$this->Api_model->user_details($searched_user_id);
        $response_content=array(
							'user_id'=>intval($user_details[0]['user_id']),
							'user'=>$user_details[0]['name'],
							'useremail'=>$user_details[0]['email'],
							'country_code'=>$user_details[0]['country_code'],
							'mobile'=>$user_details[0]['mobile'],
				            'dob'=>  $user_details[0]['dob'],
				            'name'=>$user_details[0]['name'],
				            'gender'=>$user_details[0]['gender'],
				            
				            
				            'lives_in'=>$user_details[0]['lives_in'],
				            'from_location'=>$user_details[0]['from_location'],
				            'marital_status'=>$user_details[0]['marital_status'],
				            'relationship_status'=>$user_details[0]['relationship_status'],
				            'studied_at'=>$user_details[0]['studied_at'],
				            
				            'working_at'=>$user_details[0]['working_at'],
				            
				            'channel_content'=>$channel_content,
				            'fanpage_content'=>$fanpage_content,
				            
				             'content_provider_application_status'=>$c_p_a_status,
				            'qr_code_image'=>base_url().'assets/global/user_qr_code/'.$user_details[0]['qr_bar_code_image'],
				            
				            'profile_image'=>$this->crud_model->get_profile_image_url(intval($user_details[0]['user_id'])),
				            'is_myfrien'=>$is_myfrien,
				            'isBlock'=>0
							);
							
						
						
							$JSON_ARR= array(
							'status'=>true,
							'message'=>"",
							'response'=>$response_content
							);
							print json_encode($JSON_ARR);  
    
}




public function block_user()
{
     $user_id=$this->input->post('user_id');
     $blocking_user_id=$this->input->post('blocking_user_id');
     
     
      if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
    
     if(empty($blocking_user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Blocking user id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
    
    $data=array(
        'user_id'=>$user_id,
        'blocking_user_id'=>$blocking_user_id,
        'created_at'=>date('Y-m-d')
        );
    
    $post_blockId=$this->Api_model->broadcast_post_save_block($data);
    if(!empty($post_blockId))
    {
        
        $response_content=array(
            'post_block_id'=>$post_blockId,
            'user_id'=>$user_id
            );
        
        	$JSON_ARR= array(
			'status'=>true,
			'message'=>"This blocking user id  block successfully",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  

        
    }
    else
    {
        	$JSON_ARR= array(
			'status'=>false,
			'message'=>"This blocking user id does not blocked",
			'response'=>''
			);
			print json_encode($JSON_ARR);  
        
        
    }
    
    
}



public function unblock_user()
{
    $user_id=$this->input->post('user_id');
    $blocking_user_id=$this->input->post('blocking_user_id');
     
     
      if(empty($user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
    
     if(empty($blocking_user_id))
        {
            $JSON_ARR= array(
                'status'=>false,
				'response'=>"Blocking user id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
    $result=$this->Api_model->broadcast_post_block_delete($user_id,$blocking_user_id);
    
    
    if(!empty($result))
    {
       
       $response_content=array(
            'blocking_user_id'=>$blocking_user_id,
            'user_id'=>$user_id
            );
        
       
       
       $JSON_ARR= array(
			'status'=>true,
			'message'=>"This blocking user id has been deleted successfully",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  
        
        
        
    }
    
    
}

public function get_app_qrcode()
{
	$JSON_ARR= array(
			'app_qr_code_image'=>base_url().'assets/global/app_qr_code/happywatch_app_qr_code.png'
			);
	print json_encode($JSON_ARR);  
 
}

	
public function get_app_qrcode_android()
{
	$JSON_ARR= array(
			'app_qr_code_image'=>base_url().'assets/global/app_qr_code/happywatch_app_qr_code_android.png'
			);
	print json_encode($JSON_ARR);  
 
}


public function get_app_qrcode_ios()
{
	$JSON_ARR= array(
			'app_qr_code_image'=>base_url().'assets/global/app_qr_code/happywatch_app_qr_code_ios.png'
			);
	print json_encode($JSON_ARR);  
 
}
	


	
public function upload_channelpage_picture()
    {
        $channelpage_id=$this->input->post('channelpage_id');
        $filename=$_FILES['channelpage_image']['name'];
		
		if(empty($channelpage_id))
           {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"channel page id cannot be blank"
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
           }
		
		if(!empty($channelpage_id))
		{
			    $sql = "SELECT *  FROM channel_page where 1 and id='".$channelpage_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR= array(
								'status'=>false,
								'message'=>"This channel page does not exist"
								);
						print json_encode($JSON_ARR);
						die(0);
						
					}
			
			
			
		}	
			
			if(empty($filename))
           {
           	$JSON_ARR= array(
           	        'status'=>false,
    				'message'=>"Profile image cannot be blank"
    							
    				);
    				print json_encode($JSON_ARR);
           
            die(0);
               
           }
    			
			
			

					//$data['name']=$name;	
				
                    if(!empty($filename))
                    {
                        $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $uniquesavename=time().uniqid(rand());
                        //$unique_image_name=$uniquesavename.'_'.$fanpage_id.'.'.$img_ext;
                        
                        $unique_image_name=md5($fanpage_id).'_'.$channelpage_id.'.'.$img_ext;
                        $data['image_file']=$unique_image_name;
                     move_uploaded_file($_FILES['channelpage_image']['tmp_name'], 'assets/global/channel_page_image/'.$unique_image_name);
                    }	

						if(!empty($data))
						{	
						$updatesuccess=  $this->Api_model->edit_channelpage_profile($data,$channelpage_id);
						}
						if($updatesuccess)
						{
							
							$response_content=array(
							 'channel_page_id'=>$channelpage_id,
							'channel_image'=>$this->crud_model->get_channelpage_profile_image_url($channelpage_id) 
							    
							    );
							
							
							
							$JSON_ARR= array(
							'status'=>true,
							'message'=>"your data has been changed successfully",
							'response'=>$response_content
							);
							print json_encode($JSON_ARR);  
						}	
						else
						{
							$JSON_ARR= array(
							'status'=>false,
							'message'=>"your data has not been changed successfully"
							);
							print json_encode($JSON_ARR);  
							
						}	
		}

		
   
	public function get_user_post()
	{
		
		$user_id=$this->input->post('user_id');
		$page_no=$this->input->post('page');
        $limit=20; 
         //expression ? trueValue : falseValue
         $page=(!empty($page_no)) ? $page_no : 1;
         if($page==1)
         {
            $start = 0;
         }
         else
         {
            $start = ($page-1)*$limit;
         }



		if(empty($user_id))
		{
			$JSON_ARR = array(
			'status'=>false,
			'message'=>"User Id is required",
			
			);
			print json_encode($JSON_ARR);
			 die(0);
		}	

 		$user_details=$this->Api_model->user_details($user_id);
       	
       if(empty($user_details))
       {
       	$JSON_ARR= array(
				'status'=>false,
				'message'=>"This user id does not exist"
							
				);
				print json_encode($JSON_ARR);
       
        die(0);
           
       }
      
	 $sql = "select BD.*,C.name,U.name as user_name from user U
        left join broadcast_details BD on U.user_id=BD.user_id
        left join category C on BD.cat_id= C.cat_id
        
        where 1 and BD.user_id='".$user_id."'  order by BD.id desc limit $start,$limit  ";
        
        
        
        
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
		{ 
            $broadcasts = $res->result_array();
			
			
			//echo $this->db->last_query();
			
			
			$broadcast_contents=[];
			$response_broadcast_contents=[];
			$comment_contents=[];
			$array_all_videos=[];
			$array_all_images=[];
			
			foreach($broadcasts as $row)
			{
        			    
			   
			    if($row['live_status']==0 or $row['live_status']=1) 
			   {
			   
        			    $query_like = "SELECT count(*) as no_likecount FROM broadcast_like_unlike WHERE broadcast_id='".$row['id']."' and type=1";
                        $res_like = $this->db->query($query_like);
                        $likes= $res_like->result_array();
                        $likes_count=abs($likes[0]['no_likecount']);
                    			    
                		$status_query = "SELECT type FROM broadcast_like_unlike WHERE user_id='".$user_id."' and broadcast_id=".abs($row['id']);
                        $res_self = $this->db->query($status_query);
                    	
                    	$current_user_statue="false";
                    	if ($res_self->num_rows() > 0) 
                    	{
                        	
                        	$user_status = $res_self->result_array();
                        	
                        	///print_r($user_status);
                        	
                        	if($user_status[0]['type']==1)
                            {
                                $current_user_statue='true';
                            }
                            else
                            {
                                $current_user_statue='false';
                            }
                    	}
            	    
                				$sql = "SELECT *  FROM broadcast_comments where  broadcast_id='".$row['id']."'";
                				
                				$comment_contents=[];
                				$res = $this->db->query($sql);
                				if ($res->num_rows() > 0) 
                				{
                					
                					
                					$connents = $res->result_array();
                					
                					foreach($connents as $connent)
                					{
                					        $comment_contents[] = array(
                							'user_id'=>$connent['user_id'],
                							'broadcast_id'=>$connent['broadcast_id'],
                							'user_comment'=>$connent['user_comment'],
                							'created_date'=>$connent['created_date'],	  	
                							);
                					
                					}
                				}
                			    
        			   $array_broadcast_img=explode(',', $row['broadcast_img']);
        			   $array_broadcast_video= explode(',', $row['broadcast_video']);
        			    
        			    $images=[];
        			    foreach($array_broadcast_img as $img)
        			    {
        			        
        			       
        			       if(!empty($img))
        			       {
        			       
            			       
            			        if (file_exists('assets/global/broadcast/images/' .$img))
            			        {
                                    $images[] = base_url() . 'assets/global/broadcast/images/' . $img;
									$array_all_images[]=base_url() . 'assets/global/broadcast/images/' . $img;
            			        }
            			       
        			       }            
        			    }
        			    $array_videos=[];
						
                        if(!empty($row['full_video_url']) and $row['live_status']==0) 
    			        {   
                           
        			        $array_videos[]=$row['full_video_url'];
							//$array_all_videos[]=$row['full_video_url'];
    			        }
        			    elseif($row['live_status']==1)
        			    {
        			        $array_videos[]=$row['broadcast_video'];
							//$array_all_videos[]=$row['broadcast_video'];
        			    }
        			    
        			    else
        			    {
        			    
        			    foreach($array_broadcast_video as $video )
        			    {
        			        
        			       if(!empty($video))
        			       {
        	 
                                $array_videos[]='https://'.$video;
							   	//$array_all_videos[]='https://'.$video;
        			       }  
        			        
        			    }
        			    
        			    }
        			$array_videosthumb=[];   
        			
        			
        			
        			if(!empty('assets/global/channel_thumb/' .$row['full_video_thumb_url']) and !empty($row['full_video_url']  ))
        			{
        			    //$array_videosthumb[] =$row['full_video_thumb_url'];
        			    $array_videosthumb[] =base_url() .'assets/global/channel_thumb/'.$row['full_video_thumb_url'];
        			}
        			elseif(!empty($row['broadcast_video_thumbnail']))
			        {
			                  $array_video_thumb= explode(',', $row['broadcast_video_thumbnail']);
                			    
                			    foreach($array_video_thumb as $thumb )
                			    {
                			       if(!empty($thumb))
                			       {
                                        
                                        
                    			        if (file_exists('assets/global/broadcast/video_thumb/' . $thumb))
                                        {    
                                        
                                        
                                            $array_videosthumb[] = base_url() . 'assets/global/broadcast/video_thumb/' . $thumb;
                                        }    
                	                    		       
	                
	                                   //$array_videosthumb[]=    'https://'.$thumb;
	                                }
                			       ///print_r($array_videosthumb);
                			    }   
			        }
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			   
        			    $sharing_count=0;
        			    $view_count=0;
        			        if(!empty($row['id']))
        			        {
        			        
        			        $sharing_count=$this->Api_model->get_sharing_info_count($row['id']);
        			   
        			        $view_count=$this->Api_model->broadcast_view($row['id']); 
        			        }
        			   
        			   
        			   
        
                       //// echo $this->db->last_query();
        			if(!empty($array_videos))
					{	
						$array_all_videos[]=array(
						'video'=>$array_videos,
        				'video_thumbnail'=>$array_videosthumb,
						);						
					}

									


        			    $response_broadcast_contents[] = array(
        					'broadcast_id'=>$row['id'],
        					'user_id'=>$row['user_id'],
        					'post_privacy'=>$row['post_privacy_type'],
        					'name'=>$row['user_name'],
        					'category_id'=>$row['cat_id'],
        					'category_name'=>$row['name'],
        					'image'=>$images,
        					'video'=>$array_videos,
        					'video_thumbnail'=>$array_videosthumb,
        					'created_date'=>$row['created_date'],
        					'content_description'=>	$row['content_description'],
        					'comment_contents'=>$comment_contents,
        					
        					'profile_image'=>$this->crud_model->get_profile_image_url(intval($row['user_id'])),
        					
        					'view_count'=>$view_count,
        					'sharing_count'=>$sharing_count,
        					'self_like'=>$current_user_statue,
        					'like_count'=>$likes_count
        					
        					);
        			
			
			    }
			    
			}
					
            	$ALL_JSON_ARR= array(
				'status'=>true,
				'message'=>'',
				'response'=>$response_broadcast_contents,
				'all_video'=>$array_all_videos,
				'all_images'=>$array_all_images,	
				);
		        print json_encode($ALL_JSON_ARR);
            
		}
		else
		{
		$ALL_JSON_ARR= array(
				'status'=>false,
				'message'=>''
					
				);
		        print json_encode($ALL_JSON_ARR);
		}	

	}	




public function getsearchedfanpageData()
    {
	
	$fan_page_id=$this->input->post('fan_page_id');	
	$user_id=$this->input->post('user_id');	


		if(empty($fan_page_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Fan page id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }

		if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
	


		
			

		//$follower_count=$this->Api_model->fanpage_follower_count($fan_page_id);
		$fan_page_data=$this->Api_model->fan_page_details($fan_page_id);
        
		if(!empty($fan_page_data))
		{

		$is_follow= $this->Api_model->user_fanpage_follow($user_id,$fan_page_id);
		
			$response_content=array(
					'name'=>$fan_page_data[0]['name'],
					'user_id'=>$fan_page_data[0]['user_id'],
					'profile_image'=>$this->crud_model->get_fan_profile_image_url($fan_page_data[0]['id']),
					'lives_in'=>$fan_page_data[0]['lives_in'],
					'working_at'=>$fan_page_data[0]['working_at'],
					'website'=>$fan_page_data[0]['website'],
					'phone_mobile'=>$fan_page_data[0]['phone_mobile'],
					'page'=>$fan_page_data[0]['page'],
					'from_location'=>$fan_page_data[0]['from_location'],
			 		'is_follow'=>$is_follow
					);
        
       
       
       $JSON_ARR= array(
			'status'=>true,
			'message'=>"",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  
		}
		else
		{
			$JSON_ARR= array(
			'status'=>false,
			'message'=>"This Fan Page does not exist",
			'response'=>''
			);
			print json_encode($JSON_ARR); 
		}

	}

	
public function get_searched_channelpage_data()
    {
	$channel_id=$this->input->post('channel_id');	
	$user_id=$this->input->post('user_id');		

		if(empty($channel_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Channel id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		if(empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"User id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }

		$is_subscribe= $this->Api_model->user_channelpage_subscribe($user_id,$channel_id);
		$channel_details=$this->Api_model->channepage_details($channel_id);
        
		if(!empty($channel_details))
				{
			
			$channel_about=array(
			'channel_type'=>$channel_details[0]['channel_type'],
			'lives_in'=>$channel_details[0]['lives_in'],
			'from'=>$channel_details[0]['from_location'],
			'workingat'=>$channel_details[0]['workingat'],
			'website'=>$channel_details[0]['website'],
			'phone_number'=>$channel_details[0]['phone_number'],
			'joined'=>$channel_details[0]['created_at'],
			'totatlviews'=>102,
			'description'=>$channel_details[0]['description'],
			); 
			$response_content=array(
				'channel_page_id'=>intval($channel_details[0]['id']),
				'user_id'=>	$channel_details[0]['user_id'],
				'name'=>$channel_details[0]['name'],
				'image'=>base_url() . 'assets/global/channel_page_image/'.$channel_details[0]['image_file'],
				'channel_isverified'=>$channel_details[0]['channel_isverified'],
				'channel_subscriber'=>11,
				'is_subscribe'=>$is_subscribe,
				'channel_about'=>$channel_about
					);
       
       $JSON_ARR= array(
			'status'=>true,
			'message'=>"",
			'response'=>$response_content
			);
			print json_encode($JSON_ARR);  
        


			




		}
		else
		{
			$JSON_ARR= array(
			'status'=>false,
			'message'=>"This channel Page does not exist",
			'response'=>''
			);
			print json_encode($JSON_ARR); 
		}

	}
	

	
	public function uwatch_description()
	{
		$u_watch_id=	$this->input->post('u_watch_id');
		$description=	$this->input->post('description');
		
		
		if(empty($u_watch_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Uwatch id is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
		if(empty($description))
        {
            $JSON_ARR= array(
				'status'=>false,
				'response'=>"Description is required"
				);
			print json_encode($JSON_ARR);
            die();
        }
		$data['description_long']	=	$description;
		$this->db->where('u_watch_id', $u_watch_id);
		$this->db->update('u_watch', $data);
		
		$JSON_ARR= array(
			'status'=>true,
			'message'=>"Description has been saved successfully",
			'response'=>''
		);
		print json_encode($JSON_ARR);
		
	}
	

	 function uwatch_comments()
    {
        $user_id=$this->input->post('user_id');
		$u_watch_id=$this->input->post('u_watch_id');  
        $comment=$this->input->post('comment');  
    
     
        
        
        
        if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'response'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($u_watch_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'response'=>"Uwatch_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($comment))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"comment is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        
        
        if(!empty($user_id) and !empty($u_watch_id))
        {
            $data['user_id']=$user_id;
            $data['user_comment']=$comment;
            
            $data['u_watch_id']=$u_watch_id;
            $data['created_date']=date('Y-m-d H:i:s');;
 

            $this->db->insert('uwatch_comments', $data);
    		$comment_id = $this->db->insert_id();

				$response_content=array(
					'comment_id'=>$comment_id,
					'user_id'=>$user_id
					);
        			


            $JSON_ARR = array(
				'status'=>true,
				'message'=>"your comment has been saved successfully",
				'response'=>$response_content
				
				);
				print json_encode($JSON_ARR);

            
        }
    }
   

	
	 function get_uwatch_comments()
    {
        $u_watch_id=$this->input->post('u_watch_id');
        
        if( empty($u_watch_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'response'=>"U watch id is required"
					);
				print json_encode($JSON_ARR);
                die();
        }
         $sql = "SELECT uwatch_comments.*, user.user_id,user.name,user.email,user.profile_image,user.loginwith  FROM uwatch_comments left join  user on uwatch_comments.user_id=user.user_id where 1 and uwatch_comments.u_watch_id='". $u_watch_id."'";
    		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$connents = $res->result_array();
				$comment_contents=[];
				foreach($connents as $connent)
				{
				        $comment_contents[] = array(
						'user_id'=>$connent['user_id'],
						'user_name'=>$connent['name'],
						'profile_image'=>$this->crud_model->get_profile_image_url($connent['user_id']),
						'email'=>$connent['email'],
						
						'u_watch_id'=>$connent['u_watch_id'],
						'user_comment'=>$connent['user_comment'],
						'created_date'=>$connent['created_date'],	  	
						);
				
				}
				
			    $ALL_JSON_ARR= array(
				'response'=>$comment_contents,
				);
		        print json_encode($ALL_JSON_ARR);
			}
			else
			{
				
				$JSON_ARR= array(
					'response'=>array()
					);
				
				print json_encode($JSON_ARR);
                die();
			}
    }

	public function uwatch_chennel_page_Privacy()
 	{
 	    $user_id=$this->input->post('user_id');
		$privacy_type=$this->input->post('privacy_type');
		$channel_page_id=$this->input->post('channel_page_id'); 	
 	    if(empty($user_id))
		{
		     $JSON_ARR= array(
				 	'status'=>false,
    				'response'=>"User id is required"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
	

 	    if($privacy_type>2 OR $privacy_type<0  )
		{
		     $JSON_ARR= array(
    				'status'=>false,	
				 	'response'=>"Privacy type is required,Privacy type may be (0,1,2)"

    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	    
 	     if(empty($channel_page_id))
		{
		     $JSON_ARR= array(
				 'status'=>false,
				 'response'=>"Channel page id is required"
    				);
    			print json_encode($JSON_ARR);
                die();

		}
 	    
 	    
 	    
 	   $sql="update  channel_page set privacy_type='".$privacy_type."'  where  user_id='".$user_id."' and id='".$channel_page_id."'";
		$res = $this->db->query($sql);
		$affected_row=$this->db->affected_rows();
		
			
			if($affected_row)
		{
		    $JSON_ARR= array(
    				'status'=>true,
					'message'=>"your Privacy has been changed successfully",
    				);
    			print json_encode($JSON_ARR);
                die(); 
		    
		}
		else
		{
			 $JSON_ARR= array(
    				'status'=>false,
					'message'=>"your Privacy has not been changed successfully",
    				);
    			print json_encode($JSON_ARR);
                die(); 

		}	
 	    
 	    
 	    
 	}
	

	function live_comments()
    {
        $user_id=$this->input->post('user_id');
		$live_id=$this->input->post('live_id');  
        $comment=$this->input->post('comment');  
        
        if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($live_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"live id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($comment))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"comment is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        
        
        if(!empty($user_id) and !empty($live_id))
        {
            $data['user_id']=$user_id;
            $data['user_comment']=$comment;
            
            $data['	live_id']=$live_id;
            $data['created_date']=date('Y-m-d H:i:s');;
 

            $this->db->insert('live_comments', $data);
    		$comment_id = $this->db->insert_id();

			$response_content=array(
					'comment_id'=>$comment_id,
					'user_id'=>$user_id
				);

            $JSON_ARR = array(
				'status'=>true,
				'message'=>"your comment has been saved successfully",
				'response'=>$response_content
			
				);
				print json_encode($JSON_ARR);

            
        }
    }
    	

	 function create_live_like_unlike()
    {
        $user_id=$this->input->post('user_id');
		$live_id=$this->input->post('live_id');
		$type=abs($this->input->post('type'));
        
		 
		if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"User id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
		 
		if( empty($live_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"Live id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
       
      	        
        if(!empty($user_id) and !empty($live_id) )
        {
            
          $query = "SELECT COUNT(*) AS cntpost FROM live_like_unlike WHERE live_id=".$live_id." and user_id=".$user_id;
            $res = $this->db->query($query);

    				$connents = $res->result_array();
                    $count= $connents[0]['cntpost'];
                    
		      if($count == 0){
                        
                        $data=array(
                            "live_id"=>$live_id,
    	                    "user_id"=>$user_id,	
    	                    "type"=>abs($type),
    	                    "created_date"=>date('Y-m-d H:i:s')
                            );
                        $this->Api_model->live_broadcast_like_unlike($data);
                }else {
                    
                        $data=array(
    	                    "type"=>$type
                            );
                    
                  
				  $this->Api_model->update_live_like_unlike($data,$user_id,$live_id);
                }

                $JSON_ARR = array(
					'status'=>true,
					'message'=>"Success",
					'response'=>""
					);
				print json_encode($JSON_ARR);
            
                
		
            
		     

            
            
        }
        else
        {
            
            $JSON_ARR = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
            
        }
        
        
    }
    


	

function create_movie_like_unlike()
    {
        $user_id=$this->input->post('user_id');
		$movie_id=$this->input->post('movie_id');
		$type=abs($this->input->post('type'));
        
		 
		if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"User id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
		 
		if( empty($movie_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"Movie id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
       
      	
        
        if(!empty($user_id) and !empty($movie_id) )
        {
            
          $query = "SELECT COUNT(*) AS cntpost FROM video_like_unlike WHERE movie_id=".$movie_id." and user_id=".$user_id;
            $res = $this->db->query($query);

    				$connents = $res->result_array();
                    $count= $connents[0]['cntpost'];
                    
		      if($count == 0){
                        
                        $data=array(
                            "movie_id"=>$movie_id,
    	                    "user_id"=>$user_id,	
    	                    "type"=>abs($type),
    	                    "created_date"=>date('Y-m-d H:i:s')
                            );
                        $this->Api_model->movie_like_unlike($data);
                }else {
                    
                        $data=array(
    	                    "type"=>$type
                            );
                    
                  
				  $this->Api_model->update_movie_like_unlike($data,$user_id,$movie_id);
                }

                $JSON_ARR = array(
					'status'=>true,
					'message'=>"Success",
					'response'=>""
					);
				print json_encode($JSON_ARR);
            
                
		
            
		     

            
            
        }
        else
        {
            
            $JSON_ARR = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
            
        }
        
        
    }
    


		function movie_comments()
    	{
        $user_id=$this->input->post('user_id');
		$movie_id=$this->input->post('movie_id');  
        $comment=$this->input->post('comment');  
        
        if( empty($user_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"user_id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($movie_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"Movie id is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        if( empty($comment))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'message'=>"comment is required"
					);
				print json_encode($JSON_ARR);
                die();
            
        }
        
        
        
        if(!empty($user_id) and !empty($movie_id))
        {
            $data['user_id']=$user_id;
            $data['user_comment']=$comment;
            $data['movie_id']=$movie_id;
            $data['created_date']=date('Y-m-d H:i:s');;
 

            $this->db->insert('movie_comments', $data);
    		$comment_id = $this->db->insert_id();

			$response_content=array(
					'comment_id'=>$comment_id,
					'user_id'=>$user_id
				);

            $JSON_ARR = array(
				'status'=>true,
				'message'=>"your comment has been saved successfully",
				'response'=>$response_content
			
				);
				print json_encode($JSON_ARR);

            
        }
    }
	
	
	function get_movie_comments()
    {
        $movie_id=$this->input->post('movie_id');
        
        if( empty($movie_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'response'=>"Movie id is required"
					);
				print json_encode($JSON_ARR);
                die();
        }
         $sql = "SELECT   movie_comments.*, user.user_id,user.name,user.email,user.profile_image,user.loginwith  FROM   movie_comments left join  user on   movie_comments.user_id=user.user_id where 1 and  movie_comments.movie_id='". $movie_id."'";
    		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$connents = $res->result_array();
				$comment_contents=[];
				foreach($connents as $connent)
				{
				        $comment_contents[] = array(
						'user_id'=>$connent['user_id'],
						'user_name'=>$connent['name'],
						'profile_image'=>$this->crud_model->get_profile_image_url($connent['user_id']),
						'email'=>$connent['email'],
						
						'movie_id'=>$connent['movie_id'],
						'user_comment'=>$connent['user_comment'],
						'created_date'=>$connent['created_date'],	  	
						);
				
				}
				
			    $ALL_JSON_ARR= array(
				'response'=>$comment_contents,
				);
		        print json_encode($ALL_JSON_ARR);
			}
			else
			{
				
				$JSON_ARR= array(
					'response'=>array()
					);
				
				print json_encode($JSON_ARR);
                die();
			}
    }



    	
 function get_live_comments()
    {
        $live_id=$this->input->post('live_id');
        
        if( empty($live_id))
        {
            
            $JSON_ARR = array(
					'status'=>false,
					'response'=>"Live id is required"
					);
				print json_encode($JSON_ARR);
                die();
        }
         $sql = "SELECT  live_comments.*, user.user_id,user.name,user.email,user.profile_image,user.loginwith  FROM  live_comments left join  user on  live_comments.user_id=user.user_id where 1 and live_comments.live_id='". $live_id."'";
    		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) 
			{
				$connents = $res->result_array();
				$comment_contents=[];
				foreach($connents as $connent)
				{
				        $comment_contents[] = array(
						'user_id'=>$connent['user_id'],
						'user_name'=>$connent['name'],
						'profile_image'=>$this->crud_model->get_profile_image_url($connent['user_id']),
						'email'=>$connent['email'],
						
						'live_id'=>$connent['live_id'],
						'user_comment'=>$connent['user_comment'],
						'created_date'=>$connent['created_date'],	  	
						);
				
				}
				
			    $ALL_JSON_ARR= array(
				'response'=>$comment_contents,
				);
		        print json_encode($ALL_JSON_ARR);
			}
			else
			{
				
				$JSON_ARR= array(
					'response'=>array()
					);
				
				print json_encode($JSON_ARR);
                die();
			}
    }


	
	public function live_share_count()
	{
		$live_id=$this->input->post('live_id'); 	
		$user_id=$this->input->post('user_id'); 
		if( empty($live_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Live id is required"	
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if( empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		 
		 
		  $data=array(
                 	'user_id'=>$user_id,
                 	'live_id'=>$live_id,
                 	'share_date'=>date('Y-m-d H:i:s')
                );
		 
		 $result=  $this->Api_model->save_live_share_info($data);
		 
	
		 if($result)
		 {
		    $JSON_ARR = array(
			'message'=>"Share details has been saved successfully",
			'response'=>array(
			'live_id'=>$live_id,
            'user_id'=>$user_id 
			)
			);
			print json_encode($JSON_ARR); 
		     
		 }
		 else
		 {
		     $JSON_ARR = array(
			'response'=>"Share details has not been saved successfully"
			
			);
			print json_encode($JSON_ARR); 
		     
		     
		     
		 }
		 	 
	}
	
public function movie_share_count()
	{
		$movie_id=$this->input->post('movie_id'); 	
		$user_id=$this->input->post('user_id'); 
		if( empty($movie_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"Movie id is required"	
				);
			print json_encode($JSON_ARR);
            die();
        }
        
        if( empty($user_id))
        {
            $JSON_ARR= array(
				'status'=>false,
				'message'=>"user id is required"
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		 
		 
		  $data=array(
                 	'user_id'=>$user_id,
                 	'movie_id'=>$movie_id,
                 	'share_date'=>date('Y-m-d H:i:s')
                );
		 
		 $result=  $this->Api_model->save_movie_share_info($data);
		 
	
		 if($result)
		 {
		    $JSON_ARR = array(
			'status'=>true,
			'message'=>"Share details has been saved successfully",
			'response'=>array(
			'movie_id'=>$movie_id,
            'user_id'=>$user_id 
			)
			);
			print json_encode($JSON_ARR); 
		     
		 }
		 else
		 {
		     $JSON_ARR = array(
			'response'=>"Share details has not been saved successfully"
			
			);
			print json_encode($JSON_ARR); 
		     
		     
		     
		 }
		 	 
	}
	
	public function get_episode_details()
	{
		//$season_id= $this->uri->segment(3);
		$episode_id=$this->input->post('episode_id'); 	
		//$season_id=$this->input->post('season_id'); 	
		
	
		if(empty($episode_id) )
		{
			$JSON_ARR[] = array(
			'status'=>false,
			'response'=>"Episode id not found"
			);
				print json_encode($JSON_ARR);
				die();
		}
		
		$sql="select season_id from  episode where episode_id='".$episode_id."'" ;
		$query = $this->db->query($sql);
		$episoad_result= $query->result_array();		
		$season_id=$episoad_result[0]['season_id'];
		

			
			$episode_details= $this->Api_model->get_episode_details($episode_id);		
			
			//print_r($episode_details);		
			$episodecontent="";
			foreach($episode_details as $episode_detail)
			{
				 
				$increease_view_count=0;
				$viewcount= $this->Api_model->total_view_season($episode_detail['episode_id']);
				
				$increease_view_count= $this->Api_model->increase_episode_view($episode_detail['episode_id']);	
		
	    $viewcount=$viewcount+$increease_view_count;

				 
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episodecontent= array(
				 "episode_id" => $episode_detail['episode_id'],
				 "title" => $episode_detail['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$episode_detail['episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $episode_detail['url'],
				 "like_unlike_count"=>0,
				 "share_count"=>0,
				 "comment_count"=>0,
				 "view_count"=>number_format($viewcount)
				 
				);	
			}

		




			$result_episode= $this->Api_model->Get_episodeOfSeason_accept_episode($season_id,$episode_id);
			$episode_content=[];
			foreach($result_episode as $row_episode)
			{
				 
				 $view_count= $this->Api_model->total_view_season($row_episode['episode_id']);
				
				$increease_view_count= $this->Api_model->increase_episode_view($row_episode['episode_id']);	
		
	    		$viewcount=$viewcount+$increease_view_count;
				 
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "episode_id" => $row_episode['episode_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url'],
				 "view_count"=>number_format($viewcount)
				 
				);	
			}
			
			$season_content[]= array(
			"episode_content" => $episodecontent,
			"related_episode"=>$episode_content
			);	
		


        

        
        
		$ALL_JSON_ARR = array(
				'status'=>true,
				"response"=> $season_content 
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	

	

	public function get_artist_episode_details()
	{
		
			
		$artist_episode_id=$this->input->post('artist_episode_id'); 
		//$live_id= $this->uri->segment(3);
		if(empty($artist_episode_id) )
		{
			$JSON_ARR = array(
			'status'=>false,	
			'response'=>"Artist episode id not found"
			);
				print json_encode($JSON_ARR);
				die();
		}	
		
			
		$sql="select artist_season_id from  artist_episode where 	artist_episode_id ='".$artist_episode_id."'" ;
		$query = $this->db->query($sql);
		$episoad_result= $query->result_array();		
		$artist_season_id=$episoad_result[0]['artist_season_id'];
		
		
			$artist_episode_content="";
			$artist_episode_details= $this->Api_model->get_artist_episode_details($artist_episode_id);
			//echo $this->db->last_query();
			
			foreach($artist_episode_details as $artist_episode)
			{
				
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $artist_episode_content= array(
				 "artist_song_episode_id" => $artist_episode['artist_episode_id'],
				 "title" => $artist_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$artist_episode['artist_episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $artist_episode['url']
				 
				);	
			}
	



			$result_episode= $this->Api_model->get_artist_episode_of_season_accept_episode($artist_season_id,$artist_episode_id);
			
		
			$episode_content=[];
			foreach($result_episode as $row_episode)
			{
				
				 $ext = pathinfo($row_episode['url'], PATHINFO_EXTENSION);
				 $episode_content[]= array(
				 "artist_song_episode_id" => $row_episode['artist_episode_id'],
				 "title" => $row_episode['title'],
				 "image"=>base_url().'assets/global/episode_thumb/'.$row_episode['artist_episode_id'].'.jpg',
				 "streamFormat"=>$ext,
				 "url" => $row_episode['url']
				 
				);	
			}
			
			$season_content[]= array(
			"artist_episode_content" => $artist_episode_content,
			"rest_artist_episode"=>$episode_content
			);	
		



		$ALL_JSON_ARR = array(
				'status'=>true,
				"response"=> $season_content 
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	

	public function DisplayViews($views)
	{
    if($views > 0){
        if($views <= 999){
            return (string)$views;
        } elseif($views > 999999){
            $display = round($views / 1000000, 2);
            return $display."M";
        } else {
            $display = round($views / 1000, 2);
            return $display."K";
        }
    } else {
        return "0";
    }
}


	
	function upload_channel_page_screen()
	{
		
		$channel_page_id=$this->input->post('channel_page_id');
		
		if(empty($channel_page_id) )
		{
			$JSON_ARR = array(
			'status'=>false,	
			'response'=>"Channel Page id not found"
			);
			print json_encode($JSON_ARR);
			die();
		}	
		
		
			
		$filename=$_FILES['image_file']['name'];
		
		if(empty($filename) )
		{
			$JSON_ARR = array(
			'status'=>false,	
			'response'=>"Channel Page Screen is required"
			);
			print json_encode($JSON_ARR);
			die();
		}	
			
			
			
			
		if(!empty($channel_page_id))
		{
			
			
			    $sql = "SELECT *  FROM channel_page where 1 and id='".$channel_page_id."'";
					$res = $this->db->query($sql);
					if ($res->num_rows() == 0) 
					{
						$JSON_ARR[] = array(
								'status'=>false,
								'response'=>"This Channel Page id does not exist"
								);
						print json_encode($JSON_ARR);
						exit;
						
					}	
		}
		
		
		if(!empty($filename))
		{
			$img_ext = pathinfo($filename, PATHINFO_EXTENSION);
			$uniquesavename=time().uniqid(rand());
			$unique_image_name=$uniquesavename.'_'.$channel_page_id.'.'.$img_ext;
			$data['image_file']=$unique_image_name;
			//print_r($data['image_file']);
			move_uploaded_file($_FILES['image_file']['tmp_name'], 'assets/global/channel_page_image/'.$unique_image_name);
			
		}
		////exit;
		if(!empty($data))
		{	
			
			 $this->db->where('id', $channel_page_id);
             $this->db->update('channel_page', $data);
			 $updatesuccess= $this->db->affected_rows();

		}
			if($updatesuccess)
						{
							$JSON_ARR = array(
							'message'=>"your Channel Page Screen has been changed successfully",
							'status'=>true,
							'response'=>array(
								'channel_page_id'=>$channel_page_id
								),	

							);
							print json_encode($JSON_ARR);  
						}	
						else
						{
							$JSON_ARR = array(
							'response'=>"your Channel Page Screen has not been changed successfully",
							'status'=>false,
							);
							print json_encode($JSON_ARR);  
							
						}	
			

	}	
			
	public function get_all_user($user_id=0)
	{
		if(empty($user_id))
		{
			$JSON_ARR = array(
			'status'=>false,
			'message'=>"User Id is required",
			
			);
			print json_encode($JSON_ARR);
			 die(0);
		}	
		$allusers=$this->Api_model->AllUser($user_id);
		//echo $this->db->last_query();
		//print_r($alluser);
		$adtime_content=[];
		foreach($allusers as $alluser)
		{
			$adtime_content[]= array(
				"user_id" => $alluser['user_id'],
				"user_name"=>$alluser['name'],
				"email"=>$alluser['email']
			);	

		}
		print json_encode($adtime_content);  

	}		
 	
	public function get_relationship_status()
	{
				
		$allresults=$this->Api_model->relationship_status();
		$relationship_content=[];
		foreach($allresults as $allresult)
		{
			$relationship_content[]= array(
				"id" => $allresult['id'],
				"relationship"=>$allresult['relationship']
			);	

		}
		print json_encode($relationship_content);  

	}
	




	  function create_post_flutter()
    {




        $user_id=$this->input->post('user_id');
		$cat_id=$this->input->post('cat_id');    
        $content_description=$this->input->post('content_description');
		 $post_privacy_type=$this->input->post('post_privacy_type');
        

        if(!empty($user_id) and !empty($cat_id))
        {
            
          
            
            $sql = "SELECT *  FROM user where 1 and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() == 0) 
			{

				$JSON_ARR[] = array(
						'response'=>"This user does not exist"
						);
				print json_encode($JSON_ARR);
				exit;
				
			}
	
		
            
            

            $data['user_id']=$user_id;
            $data['cat_id']=$cat_id;
            $data['content_description']=$content_description;
					
			$data['post_privacy_type']=abs($post_privacy_type);
            $data['created_date']=date('Y-m-d H:i:s');
            
            $this->db->insert('broadcast_details', $data);
    		$broadcast_id = $this->db->insert_id();
            
            
            $platformcraft_token=$this->get_platformcraft_token();
            
		
            
            $array_image=[];
            for($i=0; $i<count($_FILES['broadcast_img']['name']); $i++) 
            {
            
                if($_FILES['broadcast_img']['name'][$i]!="")
                {
                    
                    //////////////////////////////////////////////////
                 
                 
                 $tmp_image='';
                 $filename=$_FILES['broadcast_img']['name'][$i];
                 $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                   

                  $_FILES['broadcast_img']['tmp_name'][$i]=$this->compressImage($_FILES['broadcast_img']['tmp_name'][$i],$_FILES['broadcast_img']['tmp_name'][$i],$img_ext);
                  

                   //echo "=============111";
                 ///exit; 
                    //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    
                   //$tmp_image='img_'.$broadcast_id .'_'.($i+1).'.jpeg';
                   
                   
                   
                   $filename=$_FILES['broadcast_img']['name'][$i];
                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $array_image[]='img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext;
                    move_uploaded_file($_FILES['broadcast_img']['tmp_name'][$i], 'assets/global/broadcast/images/'.'img_'.$broadcast_id .'_'.($i+1).'.'.$img_ext);
                   
                   
                             

                }
              
            }
            
            $array_video=[];
            
           // print_r($_FILES['broadcast_video']);
           
           
            for($ii=0; $ii<count($_FILES['broadcast_video']['name']); $ii++) 
            {

               if($_FILES['broadcast_video']['name'][$ii]!="")
                { 
                    
                    ///////////////video_thumblain////////////////////////
    			require_once(APPPATH.'libraries/ffmpeg/vendor/autoload.php');
    
    
                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 1,   // The number of threads that FFMpeg should use
                    ));

                  $sec = 1;
                
                $movie=$_FILES['broadcast_video']['tmp_name'][$ii];
                
                $extension='.jpeg';
    		    $tmp_video_thumb='videothumb_'.$broadcast_id .'_'.($ii+1).$extension;   
                    
                
                $thumbnail = 'assets/global/broadcast/video_thumb/'.$tmp_video_thumb;
                $video = $ffmpeg->open($movie);
                /*
                $video->filters()->resize(new FFMpeg\Coordinate\Dimension(300, 310), $mode = RESIZEMODE_SCALE_HEIGHT)->synchronize();

                */
                
                /*
                $video
                ->filters()
                ->resize(new FFMpeg\Coordinate\Dimension(640, 480),3)
                ->synchronize();
                 */           
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                
                $frame->save($thumbnail);
                $array_video_thumb[]=$tmp_video_thumb;
    			
    	            
                    
                    
                    
                    
                    
                    
                    $tmp_video_name='';
                    $filename1=$_FILES['broadcast_video']['name'][$ii];
                    $video_ext = pathinfo($filename1, PATHINFO_EXTENSION);
                        
                        
                    ///$array_video[]='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                        
                    $tmp_video_name='video_'.$broadcast_id .'_'.($ii+1).'.'.$video_ext;
                       
                    
                   $url='https://filespot.platformcraft.ru/2/fs/container/5eb5a7f60e47cf37ed2fd5b9/object/broadcast_video/'.$tmp_video_name;
    				
    				$ch = curl_init($url);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    				curl_setopt($ch, CURLOPT_POST, 1);
                    $args['file'] = new CurlFile($_FILES['broadcast_video']['tmp_name'][$ii],'video/mp4',$_FILES['broadcast_video']['name'][$ii]);
                    
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    
    				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    				curl_setopt($ch, CURLOPT_HTTPHEADER,
    				array(
    				'Authorization: Bearer '.$platformcraft_token,
    				'Content-Type: multipart/form-data'
    				
    	            ));
    				$result = curl_exec($ch);
    				curl_close($ch);
    				$response=json_decode($result);
    				//$array_video[]=$response->download_url;
    			    

    			   // $array_video[]=$response->hls;	
    				if(empty($response->hls))
    				{
    				    $array_video[]=$response->download_url;
    				}
    				else
    				{
    				     $array_video[]="happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/broadcast_video/".$tmp_video_name."/playlist.m3u8";
    				    
    				    
    				}
    				
                }
                

            }
            
            
            
            
            
            $str_image= implode(",",$array_image);
            $str_video= implode(",",$array_video);
            $str_video_thumb= implode(",",$array_video_thumb);
            
            
            
            $data2=array(
                "broadcast_img"=>$str_image,
                "broadcast_video"=>$str_video,
                "broadcast_video_thumbnail"=>$str_video_thumb
                );
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data2);

            $JSON_ARR[] = array(
				'response'=>"your data has been saved successfully",
				'success'=>1,
				'broadcast_id'=>$broadcast_id,
				'user_id'=>$user_id,
				'category_id'=>$cat_id
				);
				print json_encode($JSON_ARR);

        }
        else
        {
            	$JSON_ARR[] = array(
					'response'=>"Wrong data"
					);
				print json_encode($JSON_ARR);
        }

    }
    
  function testaws_message()
  {
   					//$phone="8010255769"	;	
	  				//$message="test test test test test test test test";
                        require_once(APPPATH.'libraries/aws/sms_configuration.php');
		
                		$message = 'Happy Watch 99 code: '.$data['otp'].'. Valid for 5 minutes.';
                		$phone = +918010255769;
                
                		try {
                			$result = $s3Client->publish([
                				'Message' => $message,
                				'PhoneNumber' => $phone,
                			]);
                			
                			print_r($result);
                			//var_dump($result);
                		} catch (AwsException $e) {
                			// output error message if fails
                			error_log($e->getMessage());
                		} 

	}
	

	
	public function delete_user($user_id=0)
	{

        //$user_id=	(empty($this->input->post('user_id'))) ? '' : $this->input->post('user_id');

		if( empty($user_id))
        {
            $JSON_ARR= array(
				'response'=>"User id is required",
				'success'=>0
				
				);
			print json_encode($JSON_ARR);
            die();
        }
		
		
	    $sql = "SELECT *  FROM user where user_id ='".$user_id."'";
		$res = $this->db->query($sql);
		if ($res->num_rows() == 0) 
		{
			//$row = $res->result_array();
			//return $row;
			$JSON_ARR= array(
					'response'=>"This user does not exist",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();
		}
		
		$this->db->where('type', 0);
		$this->db->where('user_id', $user_id);
		$user_delete=$this->db->delete('user'); 
		
		
		

		if($user_delete)
		{
		$JSON_ARR= array(
					'response'=>"This user has been deleted successfully ",
					'success'=>1
					);
			print json_encode($JSON_ARR);
			 die();
		
		
		}
		else
		{
		$JSON_ARR= array(
					'response'=>"Only customer user will be deleted ",
					'success'=>0
					);
			print json_encode($JSON_ARR);
			 die();

		}	
	
     }







	function testsendmail()
	{
		$email_sub='test send mail';
		$email_msg='test mail test mail test mail test mail test mail test mail';
		$email_to="ratneshk500@gmail.com";
		
		
		

		$this->Email_model->do_email($email_msg , $email_sub , $email_to);
	

	}	

	
 	
}    ///end class
