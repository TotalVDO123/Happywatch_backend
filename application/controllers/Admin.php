<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	// constructor
	function __construct()
	{
		
		parent::__construct();
		//ini_set('display_errors', 1);
		$this->load->database();
		$this->load->model('crud_model');
		$this->load->library('session');
		$this->admin_login_check();
	}
	
	public function index()
	{
		$this->dashboard();
	}
	
	function dashboard()
	{
        
		$page_data['page_name']		=	'dashboard';
		$page_data['page_title']	=	'Dashboard';
		$this->load->view('backend/index', $page_data);
	}

	// WATCH LIST OF GENRE, MANAGE THEM
	function genre_list()
	{
		$page_data['page_name']		=	'genre_list';
		$page_data['page_title']	=	'Manage Genre';
		$this->load->view('backend/index', $page_data);
	}

	// CREATE A NEW GENRE
	function genre_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			
			//print_r($_REQUEST);
			///exit;
			$data['name']			=	$this->input->post('name');
			$data['parent_id']			=	$this->input->post('genre_parent_id');
			$this->db->insert('genre', $data);
			
			if($this->input->post('genre_parent_id'))
			{	
			
			$data1['parent']			=1;
			$this->db->update('genre', $data1, array('genre_id' => $this->input->post('genre_parent_id')));
			}
			redirect(base_url().'index.php?admin/genre_list' , 'refresh');
		}
		$page_data['page_name']		=	'genre_create';
		$page_data['page_title']	=	'Create Genre';
		$this->load->view('backend/index', $page_data);
	}

	// EDIT A GENRE
	function genre_edit($genre_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			
			//print_r($_REQUEST);
			///exit;
			
			$data['name']			=	$this->input->post('name');
			//$data['parent_id']			=	$this->input->post('genre_parent_id');
			$this->db->update('genre', $data,  array('genre_id' => $genre_id));
			redirect(base_url().'index.php?admin/genre_list' , 'refresh');
		}
		$page_data['genre_id']		=	$genre_id;
		$page_data['page_name']		=	'genre_edit';
		$page_data['page_title']	=	'Edit Genre';
		$this->load->view('backend/index', $page_data);
	}

	

	
	
	
	
	
	
	// DELETE A GENRE
	function genre_delete($genre_id = '')
	{
		$this->db->delete('genre',  array('genre_id' => $genre_id));
		redirect(base_url().'index.php?admin/genre_list' , 'refresh');
	}

	// WATCH LIST OF channel, MANAGE THEM
	function channel_list()
	{
		$page_data['page_name']		=	'channel_list';
		$page_data['page_title']	=	'Manage channel';
		$this->load->view('backend/index', $page_data);
	}

	// CREATE A NEW channel
	function channel_create()
	{
		{
		
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_channel();
			redirect(base_url().'index.php?admin/channel_list' , 'refresh');	
		}
		$page_data['page_name']		=	'channel_create';
		$page_data['page_title']	=	'Create channel';
		$this->load->view('backend/index', $page_data);
		}
		//if (isset($_POST) && !empty($_POST))
		//{
			//$data['name']			=	$this->input->post('name');
			//$this->db->insert('channel', $data);
			//redirect(base_url().'index.php?admin/channel_list' , 'refresh');
		//}
		//$page_data['page_name']		=	'channel_create';
		//$page_data['page_title']	=	'Create channel';
		//$this->load->view('backend/index', $page_data);
	}

	// EDIT A channel
	function channel_edit($channel_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_channel($channel_id);
			redirect(base_url().'index.php?admin/channel_list' , 'refresh');
		}
		$page_data['channel_id']		=	$channel_id;
		$page_data['page_name']		=	'channel_edit';
		$page_data['page_title']	=	'Edit channel';
		$this->load->view('backend/index', $page_data);
		
		//if (isset($_POST) && !empty($_POST))
		//{
			//$data['name']			=	$this->input->post('name');
			//$this->db->update('channel', $data,  array('channel_id' => $channel_id));
			//redirect(base_url().'index.php?admin/channel_list' , 'refresh');
		//}
		//$page_data['channel_id']		=	$channel_id;
		//$page_data['page_name']		=	'channel_edit';
		//$page_data['page_title']	=	'Edit channel';
		//$this->load->view('backend/index', $page_data);
	}

	// DELETE A channel
	function channel_delete($channel_id = '')
	{
		$this->db->delete('channel',  array('channel_id' => $channel_id));
		redirect(base_url().'index.php?admin/channel_list' , 'refresh');
	}
	
	// WATCH ads OF ads, MANAGE THEM
	function ads_list()
	{
		$page_data['page_name']		=	'ads_list';
		$page_data['page_title']	=	'Manage Advertisement';
		$this->load->view('backend/index', $page_data);
	}
	// CREATE A NEW live
	function ads_create()
	{
		
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_ads();
			redirect(base_url().'index.php?admin/ads_list' , 'refresh');	
		}
		$page_data['page_name']		=	'ads_create';
		$page_data['page_title']	=	'Create ads';
		$this->load->view('backend/index', $page_data);
	}
	// EDIT A ads
	function ads_edit($id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_ads($id);
			redirect(base_url().'index.php?admin/ads_list' , 'refresh');
		}
		$page_data['id']		=	$id;
		$page_data['page_name']		=	'ads_edit';
		$page_data['page_title']	=	'Edit ads';
		$this->load->view('backend/index', $page_data);
	}

	// DELETE A ads
	function ads_delete($id = '')
	{
		$this->db->delete('ads',  array('id' => $id));
		redirect(base_url().'index.php?admin/ads_list' , 'refresh');
	}
	
	
	// WATCH LIST OF Live, MANAGE THEM
	function live_list()
	{
		$page_data['page_name']		=	'live_list';
		$page_data['page_title']	=	'Manage Live TV';
		$this->load->view('backend/index', $page_data);
	}
	// CREATE A NEW live
	function live_create()
	{
		
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_live();
			redirect(base_url().'index.php?admin/live_list' , 'refresh');	
		}
		$page_data['page_name']		=	'live_create';
		$page_data['page_title']	=	'Create Live';
		$this->load->view('backend/index', $page_data);
	}
	// EDIT A live
	function live_edit($live_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_live($live_id);
			redirect(base_url().'index.php?admin/live_list' , 'refresh');
		}
		$page_data['live_id']		=	$live_id;
		$page_data['page_name']		=	'live_edit';
		$page_data['page_title']	=	'Edit live';
		$this->load->view('backend/index', $page_data);
	}

	// DELETE A live
	function live_delete($live_id = '')
	{
		$this->db->delete('live',  array('live_id' => $live_id));
		redirect(base_url().'index.php?admin/live_list' , 'refresh');
	}
	
	// set ads tim in live add_episode_time
	function add_live_time(){
        //if ($this->session->userdata('admin_is_login') != 1)
        //    redirect(base_url(), 'refresh');
       

	    // start menu active/inactive section
        //$this->session->unset_userdata('active_menu');
        //$this->session->set_userdata('active_menu', '6');
        // end menu active/inactive section
        
		//$data['add_time_data']   = $this->crud_model->get_live_ad_time($id);
		
		$data['add_time_data'] 		=	 $this->db->get('add_time_live')->row();
		$data['page_name']      = 'add_live_time';
        $data['page_title']     = 'add live time'; 
		$this->load->view('backend/index', $data);
    }
	

	function update_live_time()
	{
		///print_r($this->input->post('etime'));
		//$video_id=$this->input->post('vid');
		
		//$i=0;
		//$this->db->where('videos_id', $video_id);
		
		//print_r($_REQUEST);
		
		
		
		$tmp_preroll=0;
		$is_preroll=$this->input->post('is_preroll');
		if(!empty($is_preroll))
		{
		    $tmp_preroll=1;
		}
		$add_time=$this->input->post('add_time');

		 $this->db->where('manage_adv_time', 'live');
		$this->db->delete('add_time_live');
		
		$data = array(
				'manage_adv_time'=>'live',
				'add_time'=>$add_time,
				'is_preroll'=>$tmp_preroll
			);
		$this->db->insert('add_time_live', $data);
		
		
		//$this->session->set_flashdata('success', 'Time save successed');
		//redirect("backend/add_videos_time/".$video_id);
		redirect(base_url().'index.php?admin/add_live_time/', 'refresh');
	}
	
	// WATCH LIST OF MOVIES, MANAGE THEM
	function movie_list()
	{
		$page_data['page_name']		=	'movie_list';
		$page_data['page_title']	=	'Manage Movies';
		$this->load->view('backend/index', $page_data);
	}

	// CREATE A NEW MOVIE
	function movie_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_movie();
			redirect(base_url().'index.php?admin/movie_list' , 'refresh');	
		}
		$page_data['page_name']		=	'movie_create';
		$page_data['page_title']	=	'Create movie';
		$this->load->view('backend/index', $page_data);
	}

    function upload_u_watch()
	{
         $filename=$_FILES['u_watch']['name'];
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
                     
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $frame->save($u_watch_thumbnail);

		 
		     
		 //////////////////////////////////////////////////////    
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
    				
    				if(!empty($response))
    				{
    				    echo $u_watch_video.'|||||'.$u_watch_img;
    				    
    				}
    				
    				/*
    				echo "https://happywatch99-vod-hls.cdnvideo.ru/happywatch99-vod/_definst_/mp4:happywatch99/Content_Provider_folder/".$u_watch_video."/playlist.m3u8";
		     
		        */
		 }
		 
		
		 
		 
	}
	// EDIT A MOVIE
	function movie_edit($movie_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_movie($movie_id);
			redirect(base_url().'index.php?admin/movie_list' , 'refresh');
		}
		$page_data['movie_id']		=	$movie_id;
		$page_data['page_name']		=	'movie_edit';
		$page_data['page_title']	=	'Edit Movie';
		$this->load->view('backend/index', $page_data);
	}

	// set ads tim in movie
	function add_videos_time($id=0){
        if ($this->session->userdata('admin_is_login') != 1)
           // redirect(base_url(), 'refresh');
       

	   // start menu active/inactive section
        //$this->session->unset_userdata('active_menu');
        //$this->session->set_userdata('active_menu', '6');
        // end menu active/inactive section
        
		////$data['add_time_data']   = $this->crud_model->get_videos_ad_time($id);
		
		$data['add_time_data'] 		=	 $this->db->get('add_time_video1')->row();
		
	
		
		
		$data['page_name']      = 'add_videos_time';
        $data['page_title']     = 'add videos time'; 
        $data['vid']     = $id; 
		$this->load->view('backend/index', $data);
    }
	

	function save_videos_time()
	{
		///print_r($this->input->post('etime'));
		//$video_id=$this->input->post('vid');
		
		//$i=0;
		$this->db->where('manage_adv_time', 'video');
		$this->db->delete('add_time_video1');
		/*
		foreach($this->input->post('etime') as $row)
		{
		
		if(!empty($row))
		{	
			$data = array(
				'videos_id'=>$video_id,
				'add_time'=>$row,
				'type'=>$this->input->post('type')[$i],
				'mode'=>$this->input->post('mode')[$i]
			);
		$this->db->insert('add_time_video', $data);
		}
		$i++;	
		}
		*/
		
		$tmp_preroll=0;
		$is_preroll=$this->input->post('is_preroll');
		if(!empty($is_preroll))
		{
		    $tmp_preroll=1;
		}
		$add_time=$this->input->post('add_time');
		
		
			$data = array(
				'manage_adv_time'=>'video',
				'add_time'=>$add_time,
				'is_preroll'=>$tmp_preroll
			);
		$this->db->insert('add_time_video1', $data);
		
		
		//$this->session->set_flashdata('success', 'Time save successed');
		//redirect("backend/add_videos_time/".$video_id);
		redirect(base_url().'index.php?admin/add_videos_time/' , 'refresh');
	}
	
	
	
	function add_series_time($id=0)
	{
		$data['add_time_data'] 		=	 $this->db->get('add_time_series')->row();
		$data['page_name']      = 'add_series_time';
        $data['page_title']     = 'add series time'; 
		$this->load->view('backend/index', $data);
    }
	
	
	function save_series_time()
	{
		$this->db->where('manage_adv_time', 'series');
		$this->db->delete('add_time_series');
		
		
		$is_preroll=$this->input->post('is_preroll');
		$tmp_preroll=0;
		if(!empty($is_preroll))
		{
		    $tmp_preroll=1;
		}
		$add_time=$this->input->post('add_time');
		
		
			$data = array(
				'manage_adv_time'=>'series',
				'add_time'=>$add_time,
				'is_preroll'=>$tmp_preroll
			);
		$this->db->insert('add_time_series', $data);
		
		
		//$this->session->set_flashdata('success', 'Time save successed');
		//redirect("backend/add_videos_time/".$video_id);
		redirect(base_url().'index.php?admin/add_series_time/' , 'refresh');
	}
	
	
	
	
	
	// DELETE A MOVIE
	function movie_delete($movie_id = '')
	{
		$this->db->delete('movie',  array('movie_id' => $movie_id));
		redirect(base_url().'index.php?admin/movie_list' , 'refresh');
	}
	
	// WATCH LIST OF SERIESS, MANAGE THEM
	function series_list()
	{
		$page_data['page_name']		=	'series_list';
		$page_data['page_title']	=	'Manage TV Series';
		$this->load->view('backend/index', $page_data);
	}



	function music_list()
	{
		$page_data['page_name']		=	'artist_list';
		$page_data['page_title']	=	'Manage Music';
		$this->load->view('backend/index', $page_data);
	}





	// CREATE A NEW SERIES
	function series_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_series();
			redirect(base_url().'index.php?admin/series_list' , 'refresh');
		}
		$page_data['page_name']		=	'series_create';
		$page_data['page_title']	=	'Create TV Series';
		$this->load->view('backend/index', $page_data);
	}



	function artist_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			//$this->crud_model->create_series();
			$this->crud_model->create_live();
			
			
			//redirect(base_url().'index.php?admin/music_list' , 'refresh');
			redirect(base_url().'index.php?admin/live_list' , 'refresh');
		}
		$page_data['page_name']		=	'artist_create';
		$page_data['page_title']	=	'Create Artist';
		$this->load->view('backend/index', $page_data);
	}


	// EDIT A SERIES
	function series_edit($series_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_series($series_id);
			redirect(base_url().'index.php?admin/series_edit/'.$series_id , 'refresh');
		}
		$page_data['series_id']		=	$series_id;
		$page_data['page_name']		=	'series_edit';
		$page_data['page_title']	=	'Edit TV Series';
		$this->load->view('backend/index', $page_data);
	}



    function artist_edit($live_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			//$this->crud_model->update_series($series_id);
			
			$this->crud_model->update_live($live_id);

			redirect(base_url().'index.php?admin/artist_edit/'.$live_id , 'refresh');
		}
		$page_data['live_id']		=	$live_id;
		$page_data['page_name']		=	'artist_edit';
		$page_data['page_title']	=	'Edit Artist';
		$this->load->view('backend/index', $page_data);
	}




	// DELETE A SERIES
	function series_delete($series_id = '')
	{
		$this->db->delete('series',  array('series_id' => $series_id));
		redirect(base_url().'index.php?admin/series_list' , 'refresh');
	}


    function artist_series_delete($live_series_id = '')
	{
		$this->db->delete('live',  array('live_id' => $live_series_id));
		redirect(base_url().'index.php?admin/live_list' , 'refresh');
	}


	// CREATE A NEW SEASON
	function season_create($series_id = '')
	{
		$this->db->where('series_id' , $series_id);
		$this->db->from('season');
        $number_of_season 	=	$this->db->count_all_results();
		
		$data['series_id']	=	$series_id;
		$data['name']		=	'Season ' . ($number_of_season + 1);
		$this->db->insert('season', $data);
		redirect(base_url().'index.php?admin/series_edit/'.$series_id , 'refresh');
		
	}



    function artist_season_create($live_id = '')
	{
		$this->db->where('live_id' , $live_id);
		$this->db->from('live');
        $number_of_season 	=	$this->db->count_all_results();
		// artist_id=live_id
		$data['artist_id']	=	$live_id;
		//$data['name']		=	'Artist ' . ($number_of_season + 1);
		$data['name']		=	'Artist' ;
		$this->db->insert('artist_season', $data);
		redirect(base_url().'index.php?admin/artist_edit/'.$live_id , 'refresh');
		
	}




	// EDIT A SEASON
	function season_edit($series_id = '', $season_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['title']			=	$this->input->post('title');
			$this->db->update('series', $data,  array('series_id' => $series_id));
			redirect(base_url().'index.php?admin/series_edit/'.$series_id , 'refresh');
		}
		$series_name				=	$this->db->get_where('series', array('series_id'=>$series_id))->row()->title;
		$season_name				=	$this->db->get_where('season', array('season_id'=>$season_id))->row()->name;
		$page_data['page_title']	=	'Manage episodes of ' . $season_name . ' : ' . $series_name;
		$page_data['season_name']	=	$this->db->get_where('season', array('season_id'=>$season_id))->row()->name;
		$page_data['series_id']		=	$series_id;
		$page_data['season_id']		=	$season_id;
		$page_data['page_name']		=	'season_edit';
		$this->load->view('backend/index', $page_data);
	}



// EDIT ARTIST SEASON
function artist_season_edit($live_id = '', $artist_season_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['title']			=	$this->input->post('title');
			$this->db->update('live', $data,  array('live_id' => $live_id));
			redirect(base_url().'index.php?admin/artist_edit/'.$live_id , 'refresh');
		}
		$series_name				=	$this->db->get_where('live', array('live_id'=>$live_id))->row()->title;
		
		$season_name				=	$this->db->get_where('artist_season', array('artist_id'=>$live_id))->row()->name;
		
		$page_data['page_title']	=	'Manage Songs of ' . $season_name . ' : ' . $series_name;
		$page_data['season_name']	=	$this->db->get_where('artist_season', array('artist_season_id'=> $artist_season_id))->row()->name;
		$page_data['live_id']		=	$live_id;
		$page_data['artist_season_id']		=	$artist_season_id;
		$page_data['page_name']		=	'artist_season_edit';
		$this->load->view('backend/index', $page_data);
	}


	// DELETE A SEASON
	function season_delete($series_id = '', $season_id = '')
	{
		$this->db->delete('season',  array('season_id' => $season_id));
		redirect(base_url().'index.php?admin/series_edit/'.$series_id , 'refresh');
	}



	function artist_season_delete($series_id = '', $season_id = '')
	{
		$this->db->delete('artist_season',  array('artist_season_id' => $season_id));
		redirect(base_url().'index.php?admin/artist_edit/'.$series_id , 'refresh');
	}


	// CREATE A NEW EPISODE
	function episode_create($series_id = '', $season_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['title']			=	$this->input->post('title');
			$data['url']			=	$this->input->post('url');
		    $data['created_date']		=	date("Y-m-d");
			$data['season_id']		=	$season_id;
			$this->db->insert('episode', $data);
			$episode_id = $this->db->insert_id();
			move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/episode_thumb/' . $episode_id . '.jpg');
			redirect(base_url().'index.php?admin/season_edit/'.$series_id.'/'.$season_id , 'refresh');
		}
	}

	
	
	function songs_episode_create($live_id = '', $artist_season_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['title']			=	$this->input->post('title');
			$data['url']			=	$this->input->post('url');
		    $data['created_date']		=	date("Y-m-d");
			$data['artist_season_id']		=	$artist_season_id;
			$this->db->insert('artist_episode', $data);
			$srtist_episode_id = $this->db->insert_id();
			move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/episode_thumb/' . $srtist_episode_id . '.jpg');
			redirect(base_url().'index.php?admin/artist_season_edit/'.$live_id.'/'.$artist_season_id , 'refresh');
		}
	}

	
	
	
	// CREATE A NEW EPISODE
	
	function episode_edit($series_id = '', $season_id = '', $episode_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['title']			=	$this->input->post('title');
			$data['url']			=	$this->input->post('url');
			
			$data['increase_view']			=	$this->input->post('increase_view');
			
			$data['season_id']		=	$season_id;
			$this->db->update('episode', $data, array('episode_id'=>$episode_id));
			move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/episode_thumb/' . $episode_id . '.jpg');
			redirect(base_url().'index.php?admin/season_edit/'.$series_id.'/'.$season_id , 'refresh');
		}
	}
	
	
	
	
	function songs_episode_edit($live_id = '', $artist_season_id = '', $artist_episode_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{

			$data['title']			=	$this->input->post('title');
			$data['url']			=	$this->input->post('url');
			$data['artist_season_id']		=$artist_season_id;
			
		
			
	$this->db->update('artist_episode', $data, array('artist_episode_id'=>$artist_episode_id));
			
		
			move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/episode_thumb/' .  $srtist_episode_id  . '.jpg');
			
		
			
			redirect(base_url().'index.php?admin/artist_season_edit/'.$live_id.'/'.$artist_season_id , 'refresh');
		}
	}

	// DELETE AN EPISODE
	function episode_delete($series_id = '', $season_id = '', $episode_id = '')
	{
		$this->db->delete('episode',  array('episode_id' => $episode_id));
		redirect(base_url().'index.php?admin/season_edit/'.$series_id.'/'.$season_id , 'refresh');
	}
	
	
	function songs_episode_delete($live_id = '', $artist_season_id = '', $artist_episode_id = '')
	{
		$this->db->delete('artist_episode',  array('artist_episode_id' => $artist_episode_id ));
		redirect(base_url().'index.php?admin/artist_season_edit/'.$live_id.'/'.$artist_season_id , 'refresh');
	}
	
	// set ads tim in episode add_episode_time
	function add_episode_time($id=0){
        if ($this->session->userdata('admin_is_login') != 1)
           // redirect(base_url(), 'refresh');
       

	   // start menu active/inactive section
        //$this->session->unset_userdata('active_menu');
        //$this->session->set_userdata('active_menu', '6');
        // end menu active/inactive section
        
		$data['add_time_data']   = $this->crud_model->get_episode_ad_time($id);
		$data['page_name']      = 'add_episode_time';
        $data['page_title']     = 'add episode time'; 
        $data['vid']     = $id; 
		$this->load->view('backend/index', $data);
    }
	

	function save_episode_time()
	{
		///print_r($this->input->post('etime'));
		$video_id=$this->input->post('vid');
		
		$i=0;
		$this->db->where('videos_id', $video_id);
		$this->db->delete('add_time_episode');
		foreach($this->input->post('etime') as $row)
		{
		
		if(!empty($row))
		{	
			$data = array(
				'videos_id'=>$video_id,
				'add_time'=>$row,
				'type'=>$this->input->post('type')[$i],
				'mode'=>$this->input->post('mode')[$i]
			);
		$this->db->insert('add_time_episode', $data);
		}
		$i++;	
		}
		//$this->session->set_flashdata('success', 'Time save successed');
		//redirect("backend/add_videos_time/".$video_id);
		redirect(base_url().'index.php?admin/add_episode_time/'.$video_id , 'refresh');
	}
	
	
	// WATCH LIST OF ACTORS, MANAGE THEM
	function actor_list()
	{
		$page_data['page_name']		=	'actor_list';
		$page_data['page_title']	=	'Manage Actors';
		$this->load->view('backend/index', $page_data);
	}

	// CREATE A NEW ACTOR
	function actor_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_actor();
			redirect(base_url().'index.php?admin/actor_list' , 'refresh');
		}
		$page_data['page_name']		=	'actor_create';
		$page_data['page_title']	=	'Create Actor';
		$this->load->view('backend/index', $page_data);
	}

	// EDIT A ACTOR
	function actor_edit($actor_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_actor($actor_id);
			redirect(base_url().'index.php?admin/actor_list' , 'refresh');
		}
		$page_data['actor_id']		=	$actor_id;
		$page_data['page_name']		=	'actor_edit';
		$page_data['page_title']	=	'Edit Actor';
		$this->load->view('backend/index', $page_data);
	}

	// DELETE A ACTOR
	function actor_delete($actor_id = '')
	{
		$this->db->delete('actor',  array('actor_id' => $actor_id));
		redirect(base_url().'index.php?admin/actor_list' , 'refresh');
	}
	
	// WATCH LIST OF PRICING PACKAGES, MANAGE THEM
	function plan_list()
	{
		$page_data['page_name']		=	'plan_list';
		$page_data['page_title']	=	'Manage Membership Package';
		$this->load->view('backend/index', $page_data);
	}

	// EDIT A ACTOR
	function plan_edit($plan_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['name']			=	$this->input->post('name');
			$data['price']			=	$this->input->post('price');
			$data['status']			=	$this->input->post('status');
			$this->db->update('plan', $data,  array('plan_id' => $plan_id));
			redirect(base_url().'index.php?admin/plan_list' , 'refresh');
		}
		$page_data['plan_id']		=	$plan_id;
		$page_data['page_name']		=	'plan_edit';
		$page_data['page_title']	=	'Edit plan';
		$this->load->view('backend/index', $page_data);
	}
	
	// WATCH LIST OF USERS, MANAGE THEM
	function user_list()
	{
		$s_email=$this->input->post('s_email');
		$s_mobile=$this->input->post('s_mobile');
		$name=$this->input->post('name');
		
		if (isset($_POST) && !empty($_POST))
		{
			
			//if(empty($name) or empty($s_email))
			//{	
			
			//$this->session->set_userdata('s_email', '');
			//$this->session->set_userdata('s_name', '');
			
			$this->session->set_userdata('s_email', $s_email);
			$this->session->set_userdata('s_name', $name);
			
			$this->session->set_userdata('s_mobile', $s_mobile);
			
			
			
		}
		
		$total_rows = $this->crud_model->get_user_num_rows();
		$this->load->library("pagination");
		$config 					= array();
		$config["base_url"] 		= base_url() . "index.php?admin/user_list/";
		$config["total_rows"] 		= $total_rows;
		$config["per_page"] 		= 24;
		$config["uri_segment"] 		= 3;
		
		
		//$config['enable_query_strings'] = FALSE;
		//$config['page_query_string'] = TRUE;
		
		//$config['page_query_string'] = FALSE;
		//$config['query_string_segment'] = 'page';
		
		/*
		$config['full_tag_open'] 	= '<div class="pagination-container text-center"><ul class ="pagination">';
		$config['full_tag_close'] 	= '</ul></div><!--pagination-->';

		$config['first_link'] 		= '«';
		$config['first_tag_open'] 	= '<li>';
		$config['first_tag_close'] 	= '</li>';

		$config['last_link'] 		= '»';
		$config['last_tag_open'] 	= '<li>';
		$config['last_tag_close'] 	= '</li>';

		$config['next_link'] 		= '&rarr;';
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';

		$config['prev_link'] 		= '&larr;';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';

		$config['cur_tag_open'] 	= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 	= '</a><div class="pagination-hvr"></div></li>';

		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '<div class="pagination-hvr"></div></li>';
		
		*/
		
		//config for bootstrap pagination class integration
       /*
		$config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
		
		*/
		
		$config['full_tag_open'] = '<ul class="pagination">';        
    $config['full_tag_close'] = '</ul>';        
    $config['first_link'] = 'First';        
    $config['last_link'] = 'Last';        
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';        
    $config['first_tag_close'] = '</span></li>';        
    $config['prev_link'] = '&laquo';        
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';        
    $config['prev_tag_close'] = '</span></li>';        
    $config['next_link'] = '&raquo';        
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';        
    $config['next_tag_close'] = '</span></li>';        
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';        
    $config['last_tag_close'] = '</span></li>';        
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';        
    $config['cur_tag_close'] = '</a></li>';        
    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';        
    $config['num_tag_close'] = '</span></li>';
		
		
		$config['page_query_string'] = FALSE;
		$this->pagination->initialize($config);
		//$page 						= $this->input->get('per_page');   
        
		 $page=$this->uri->segment(3);
		
		
		//echo "=============**".$page;
		
		//$this->db->like('title', 'match', 'both');
		
		
		//$users=$this->db->limit($config["per_page"],$page)->order_by('created_date', 'desc')->get_where('user', array('type'=>0))->result_array();
		//$data["all_published_videos"] = $this->common_model->get_videos($filter,$config["per_page"], $page);
		
		
		//$s_email=$this->input->post('email');
		
		///$name=$this->input->post('name');
		
		//echo "=======================================================".$s_email;
		
		
		
		
		$where_as="";
		if(!empty($this->session->userdata('s_email')))
		{
			$where_as.= " and email  like '%".$this->session->userdata('s_email')."%'" ;
		}	
	
		if(!empty($this->session->userdata('s_name')))
		{
			$where_as.= " and name  like '%".$this->session->userdata('s_name')."%'" ;
		}	
		
		if(!empty($this->session->userdata('s_mobile')))
		{
			$where_as.= " and mobile  like '%".$this->session->userdata('s_mobile')."%'" ;
		}	

		
		
		
		
		$sql="select  * from user where type=0 ".$where_as." order by created_date desc limit ".abs($page).",". $config["per_page"] ;	
		
		$query = $this->db->query($sql);
		
		$users= $query->result_array();
		
		
		$page_data["links"] 				= $this->pagination->create_links();
		
		
		
		
		
		
		
		
		///$users = $this->db->limit(10,0)->order_by('created_date', 'desc')->get_where('user', array('type'=>0))->result_array();
		
		//echo $this->db->last_query();
		//exit;
		
		
		//echo "-------------------------------------------------------------------------------------------".$this->session->userdata('s_name');	
		$page_data['s_email']		=	$this->session->userdata('s_email');			
		$page_data['s_name']		=	$this->session->userdata('s_name');			
		
		$page_data['s_mobile']		=	$this->session->userdata('s_mobile');			
		
		$page_data['users']		=	$users;
		$page_data['page_name']		=	'user_list';
		$page_data['page_title']	=	'Manage User';
		$this->load->view('backend/index', $page_data);
	}
	
	// CREATE A NEW USER
	function user_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_user();
			redirect(base_url().'index.php?admin/user_list' , 'refresh');
		}
		$page_data['page_name']		=	'user_create';
		$page_data['page_title']	=	'Create User';
		$this->load->view('backend/index', $page_data);
	}
	
	// EDIT A USER
	function user_edit($edit_user_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->update_user($edit_user_id);
			redirect(base_url().'index.php?admin/user_list' , 'refresh');
		}
		$page_data['edit_user_id']	=	$edit_user_id;
		$page_data['page_name']		=	'user_edit';
		$page_data['page_title']	=	'Edit User';
		$this->load->view('backend/index', $page_data);
	}
	
	// DELETE A USER
	function user_delete($user_id = '')
	{
		$this->db->delete('user',  array('user_id' => $user_id));
		$this->db->delete('broadcast_details',  array('user_id' => $user_id));
		redirect(base_url().'index.php?admin/user_list' , 'refresh');
	}
	
	// WATCH SUBSCRIPTION, PAYMENT REPORT
	function report($month = '', $year = '')
	{
		if ($month == '')
			$month	=	date("F");
		if ($year == '')
			$year = date("Y");
		
		$page_data['month']			=	$month;
		$page_data['year']			=	$year;
		$page_data['page_name']		=	'report';
		$page_data['page_title']	=	'Customer Subscription & Payment Report';
		$this->load->view('backend/index', $page_data);
	}
	
	// WATCH LIST OF FAQS, MANAGE THEM
	function faq_list()
	{
		$page_data['page_name']		=	'faq_list';
		$page_data['page_title']	=	'Manage FAQ';
		$this->load->view('backend/index', $page_data);
	}

	// CREATE A NEW FAQ
	function faq_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['question']		=	$this->input->post('question');
			$data['answer']			=	$this->input->post('answer');
			$this->db->insert('faq', $data);
			redirect(base_url().'index.php?admin/faq_list' , 'refresh');
		}
		$page_data['page_name']		=	'faq_create';
		$page_data['page_title']	=	'Create FAQ';
		$this->load->view('backend/index', $page_data);
	}

	// EDIT A FAQ
	function faq_edit($faq_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['question']		=	$this->input->post('question');
			$data['answer']			=	$this->input->post('answer');
			$this->db->update('faq', $data,  array('faq_id' => $faq_id));
			redirect(base_url().'index.php?admin/faq_list' , 'refresh');
		}
		$page_data['faq_id']		=	$faq_id;
		$page_data['page_name']		=	'faq_edit';
		$page_data['page_title']	=	'Edit FAQ';
		$this->load->view('backend/index', $page_data);
	}

	// DELETE A FAQ
	function faq_delete($faq_id = '')
	{
		$this->db->delete('faq',  array('faq_id' => $faq_id));
		redirect(base_url().'index.php?admin/faq_list' , 'refresh');
	}

	// EDIT SETTINGS
	function settings()
	{
		if (isset($_POST) && !empty($_POST))
		{
			// Updating website name
			$data['description']		=	$this->input->post('site_name');
			$this->db->update('settings', $data,  array('type' => 'site_name'));
			
			// Updating website email
			$data['description']		=	$this->input->post('site_email');
			$this->db->update('settings', $data,  array('type' => 'site_email'));
			
			// Updating trial period enable/disable
			$data['description']		=	$this->input->post('trial_period');
			$this->db->update('settings', $data,  array('type' => 'trial_period'));
			
			// Updating trial period number of days
			$data['description']		=	$this->input->post('trial_period_days');
			$this->db->update('settings', $data,  array('type' => 'trial_period_days'));
			
			// Updating website language settings
			$data['description']		=	$this->input->post('language');
			$this->db->update('settings', $data,  array('type' => 'language'));
			
			// Updating website theme settings
			$data['description']		=	$this->input->post('theme');
			$this->db->update('settings', $data,  array('type' => 'theme'));
			
			// Updating website paypal merchant email
			$data['description']		=	$this->input->post('paypal_merchant_email');
			$this->db->update('settings', $data,  array('type' => 'paypal_merchant_email'));
			
			// Updating invoice address
			$data['description']		=	$this->input->post('invoice_address');
			$this->db->update('settings', $data,  array('type' => 'invoice_address'));
			
			// Updating envato purchase code
			$data['description']		=	$this->input->post('purchase_code');
			$this->db->update('settings', $data,  array('type' => 'purchase_code'));
			
			// Updating privacy policy
			$data['description']		=	$this->input->post('privacy_policy');
			$this->db->update('settings', $data,  array('type' => 'privacy_policy'));
			
			// Updating refund policy
			$data['description']		=	$this->input->post('refund_policy');
			$this->db->update('settings', $data,  array('type' => 'refund_policy'));
			
			// Updating stripe publishable key
			$data['description']		=	$this->input->post('stripe_publishable_key');
			$this->db->update('settings', $data,  array('type' => 'stripe_publishable_key'));
			
			// Updating stripe secret key
			$data['description']		=	$this->input->post('stripe_secret_key');
			$this->db->update('settings', $data,  array('type' => 'stripe_secret_key'));
			
			move_uploaded_file($_FILES['logo']['tmp_name'], 'assets/global/logo.png');
						
			redirect(base_url().'index.php?admin/settings' , 'refresh');
		}
		
		$page_data['site_name']				=	$this->db->get_where('settings',array('type'=>'site_name'))->row()->description;
		$page_data['site_email']			=	$this->db->get_where('settings',array('type'=>'site_email'))->row()->description;
		$page_data['trial_period']			=	$this->db->get_where('settings',array('type'=>'trial_period'))->row()->description;
		$page_data['trial_period_days']		=	$this->db->get_where('settings',array('type'=>'trial_period_days'))->row()->description;
		$page_data['theme']					=	$this->db->get_where('settings',array('type'=>'theme'))->row()->description;
		$page_data['paypal_merchant_email']	=	$this->db->get_where('settings',array('type'=>'paypal_merchant_email'))->row()->description;
		$page_data['invoice_address']		=	$this->db->get_where('settings',array('type'=>'invoice_address'))->row()->description;
		$page_data['purchase_code']			=	$this->db->get_where('settings',array('type'=>'purchase_code'))->row()->description;
		$page_data['privacy_policy']		=	$this->db->get_where('settings',array('type'=>'privacy_policy'))->row()->description;
		$page_data['refund_policy']			=	$this->db->get_where('settings',array('type'=>'refund_policy'))->row()->description;
		$page_data['stripe_publishable_key']=	$this->db->get_where('settings',array('type'=>'stripe_publishable_key'))->row()->description;
		$page_data['stripe_secret_key']		=	$this->db->get_where('settings',array('type'=>'stripe_secret_key'))->row()->description;
		
		$page_data['page_name']				=	'settings';
		$page_data['page_title']			=	'Website Settings';
		$this->load->view('backend/index', $page_data);
	}
	
	function manage_language($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] = $param2;
		}
		if ($param1 == 'update_phrase') {
			$language = $param2;
			$total_phrase = $this->input->post('total_phrase');
			for ($i = 1; $i < $total_phrase; $i++) {
				//$data[$language]  =   $this->input->post('phrase').$i;
				$this->db->where('phrase_id', $i);
				$this->db->update('language', array($language => $this->input->post('phrase' . $i)));
			}
			redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/' . $language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT',
					'null' => FALSE
				)
			);
			$this->dbforge->add_column('language', $fields);

			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));

			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		
		$page_data['page_name']				=	'manage_language';
		$page_data['page_title']			=	'Multi - language settings';
		$this->load->view('backend/index', $page_data);
	}
	
	function account()
	{
		$user_id	=	$this->session->userdata('user_id');
		
		if (isset($_POST) && !empty($_POST))
		{
			$task	=	$this->input->post('task');
			if ($task == 'update_profile')
			{
				$data['name']				=	$this->input->post('name');
				$data['email']				=	$this->input->post('email');
				$this->db->update('user', $data, array('user_id'=>$user_id));
				redirect(base_url().'index.php?admin/account' , 'refresh');
			}
			else if ($task == 'update_password')
			{
				$old_password_encrypted				=	$this->crud_model->get_current_user_detail()->password;
				$old_password_submitted_encrypted	=	sha1($this->input->post('old_password'));
				$new_password						=	$this->input->post('new_password');
				$new_password_encrypted				=	sha1($this->input->post('new_password'));
				
				// CORRECT OLD PASSWORD NEEDED TO CHANGE PASSWORD
				if ($old_password_encrypted 		==	$old_password_submitted_encrypted)
				{
					$this->db->update('user', array('password'=>$new_password_encrypted), array('user_id'=>$user_id));
					$this->session->set_flashdata('status', 'password_changed');
				}
				redirect(base_url().'index.php?admin/account' , 'refresh');
			}
		}
		$page_data['page_name']				=	'account';
		$page_data['page_title']			=	'Manage Account';
		$this->load->view('backend/index', $page_data);
	}
	

	function admin_login_check()
	{
		$logged_in_user_type			=	$this->session->userdata('login_type');
		if ($logged_in_user_type == 0)
		{
			redirect(base_url().'index.php?home/signin' , 'refresh');
		}
	}
	
	
	function broadcast_list()
	{
	    $page_data['page_name']		=	'broadcast_list';
		$page_data['page_title']	=	'Manage Posts';
		$this->load->view('backend/index', $page_data);
	}

	function post_edit($broadcast_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			$data['content_description']			=	$this->input->post('content_description');
			$data['increase_view']			=	$this->input->post('increase_view');
			$this->db->update('broadcast_details', $data,  array('id' => $broadcast_id));
			
			redirect(base_url().'index.php?admin/broadcast_list' , 'refresh');
		}
		$page_data['broadcast_id']		=	$broadcast_id;
		$page_data['page_name']		=	'post_edit';
		$page_data['page_title']	=	'Edit Post';
		$this->load->view('backend/index', $page_data);
	}
	
	


	

	function broadcast_deletee($broadcast_id = 0,$user_id=0)
	{
		//$this->db->delete('genre',  array('genre_id' => $genre_id));
		$this->load->model('Api_model');
		$this->Api_model->delete_broadcast($user_id,$broadcast_id);
        $this->Api_model->delete_broadcast_comments($user_id,$broadcast_id);
        $this->Api_model->delete_broadcast_like_unlike($user_id,$broadcast_id);

		redirect(base_url().'index.php?admin/broadcast_list' , 'refresh');
	}


    function dynamic_genre_list()
	{
		$page_data['page_name']		=	'dynamic_genre_list';
		$page_data['page_title']	=	'Manage Dynamic Genre';
		$this->load->view('backend/index', $page_data);
	}



    function ajax_genre_update_sno()
    {
       $position=$this->input->post('position');
        
        
        $i=1;

        foreach($position as $k=>$v){
            $data=array(
                'sno'=>$i
                );
            
            $this->db->update('genre', $data,  array('genre_id' => $v));
            $i++;
        }



        
        
        //print_r($position);
        
        //$this->db->update('user', array('password'=>$new_password_encrypted), array('user_id'=>$user_id));
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
 	
    
    
     function uwatch_list($uwatch_id = '')
	{
		$page_data['page_name']		=	'uwatch_list';
		$page_data['page_title']	=	'Manage U Watch';
		$this->load->view('backend/index', $page_data);
	}

	function uwatch_edit($uwatch_id = '')
	{
		if (isset($_POST) && !empty($_POST))
		{
			
		
			
			$data['title']			=	$this->input->post('title');
			$data['description_long']			=	$this->input->post('description_long');
			$data['increase_view']			=	$this->input->post('increase_view');
			$this->db->update('u_watch', $data,  array('u_watch_id' => $uwatch_id));
			redirect(base_url().'index.php?admin/uwatch_list' , 'refresh');
		}
		$page_data['uwatch_id']		=	$uwatch_id;
		$page_data['page_name']		=	'uwatch_edit';
		$page_data['page_title']	=	'Edit U Watch';
		$this->load->view('backend/index', $page_data);
	}

	
	
	function uwatch_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			$this->crud_model->create_uwatch();
			redirect(base_url().'index.php?admin/uwatch_list' , 'refresh');	
		}
		$page_data['page_name']		=	'uwatch_create';
		$page_data['page_title']	=	'Create U Watch';
		$this->load->view('backend/index', $page_data);
	}
	

	function uwatch_delete($uwatch_id = '')
	{
		$this->db->delete('u_watch',  array('u_watch_id' => $uwatch_id));
		redirect(base_url().'index.php?admin/uwatch_list' , 'refresh');
	}
    
	

    function notification_list()
    {
        $page_data['page_name']		=	'notification_list';
		$page_data['page_title']	=	'Manage Custom notification';
		$this->load->view('backend/index', $page_data);
    }
    
    
    
    function notification_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			
			
		$array_user_id=	$this->input->post('user_id');
		
		
		
		$notification_send="";
			if($array_user_id[0]=='ALL')
			{
			  
			    $notification_send='ALL';	
			}
			else
			{
			    $notification_send='SELECTED';	
			}
			
			
			
			$user_id	=	$this->session->userdata('user_id');
			$data['notification']	=	$this->input->post('notification');
			$data['create_date']    =	   date('Y-m-d H:i:s');
			$data['created_by']    =	   $user_id;
			$data['notification_send']=$notification_send;
		
			
			$this->db->insert('notification', $data);
			$notification_id = $this->db->insert_id();
			
			if($notification_send=='SELECTED')
			{
    			foreach($array_user_id as $row)
    			{
        			
        			$data2=array(
        			    'user_id'=>$row,
        			    'notification_id'=>$notification_id,
        			    'created_at'=>date('Y-m-d')
        			    );
        			$this->db->insert('notification_user', $data2);
    		    }
    			
    		}
			redirect(base_url().'index.php?admin/notification_list' , 'refresh');
		}
		$page_data['page_name']		=	'notification_create';
		$page_data['page_title']	=	'Create Custom Notification';
		$this->load->view('backend/index', $page_data);
	}
    
    
    	function notification_delete($notification_id = '')
	    {
    		$this->db->delete('notification',  array('id' => $notification_id));
    		
    		$this->db->delete(' notification_user',  array('notification_id' => $notification_id));
    		redirect(base_url().'index.php?admin/notification_list' , 'refresh');
	    }
    
    
    
    
    function application_notification_list()
    {
        
       $page_data['page_name']		=	'application_notification_list';
		$page_data['page_title']	=	'Manage Application notification';
		$this->load->view('backend/index', $page_data); 
        
    }
    
    function application_notification_create()
	{
		if (isset($_POST) && !empty($_POST))
		{
			
		
		$array_user_id=	$this->input->post('user_id');
		
			
			$user_id	=	$this->session->userdata('user_id');
			$data['notification']	=	$this->input->post('notification');
			$data['create_date']    =	   date('Y-m-d H:i:s');
			$data['created_by']    =	   $user_id;
			
			$data['notification_send'] = $this->input->post('notification_send');
			
			
			$this->db->insert('application_notification', $data);
			$notification_id = $this->db->insert_id();
			redirect(base_url().'index.php?admin/application_notification_list' , 'refresh');
		}
		$page_data['page_name']		=	'application_notification_create';
		$page_data['page_title']	=	'Create Application Notification';
		$this->load->view('backend/index', $page_data);
	}

    
    function application_notification_delete($application_notification_id = '')
	    {
    		$this->db->delete('application_notification',  array('id' => $application_notification_id));
    		
    		
    		redirect(base_url().'index.php?admin/application_notification_list' , 'refresh');
	    }
    
    
	function ajax_subgenre()
    {
    	$ajax_str="";
		$parent_id=$this->input->post('parent_id');
		$this->db->where('parent_id',$parent_id);
		$query 		=	 $this->db->get('genre');
        $genres= $query->result_array();
		foreach ($genres as $row2)
		{	
		$ajax_str.='<option value="'. $row2['genre_id'].'">';
		$ajax_str.=$row2['name'];
		$ajax_str.='</option>';
		}	
		
		echo $ajax_str;

	}
}
