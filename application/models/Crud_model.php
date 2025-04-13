<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
        
    }


	/*
	* SETTINGS QUERIES
	*/
	function get_settings($type)
	{
		$description	=	$this->db->get_where('settings', array('type'=>$type))->row()->description;
		return $description;
	}

	/*
	* PLANS QUERIES
	*/

	function get_active_plans()
	{
		$this->db->where('status', 1);
		$query 		=	 $this->db->get('plan');
        return $query->result_array();
	}

	function get_active_theme()
	{
		$theme	=	$this->get_settings('theme');
		return $theme;
	}

	/*
	* check if a video should be embedded in iframe or in jwplayer
	* if the video is youtube url, it will go for jwplayer
	* if the video has .mp4 extension, it will go for jwplayer
	* else all videos will go for iframe embedding option
	*/
	function is_iframe($video_url)
	{
		$iframe_embed	=	true;
		if (strpos($video_url, 'youtube.com')) {
			$iframe_embed = false;
		}

		$path_info 		=	pathinfo($video_url);
		$extension		=	$path_info['extension'];
		if ($extension == 'mp4') {
			$iframe_embed = false;
		}
		return $iframe_embed;
	}

	/*
	* USER QUERIES
	*/
	
	
	function get_user_num_rows()
	{
		
		$s_email=$this->session->userdata('s_email');
		$name=$this->session->userdata('s_name');
		
		$s_mobile=$this->session->userdata('s_mobile');
		
		$this->db->where('type' , 0);
		if(!empty($s_email))
		{
			$this->db->like('email', $s_email, 'both'); 
		}	
	
		if(!empty($name))
		{
			$this->db->like('name', $name, 'both'); 
		}	
		
		if(!empty($s_mobile))
		{
			$this->db->like('mobile', $s_mobile, 'both'); 
			///$where_as.= " and mobile  like '%".$this->session->userdata('s_mobile')."%'" ;
		}	
		
		
		$this->db->from('user');
        return $total_number_of_matching_user = $this->db->count_all_results();
	
	}	
	
	
	
	function signup_user()
	{
		$CI =& get_instance();
		$CI->load->model('Email_model');
		$email 				= $this->input->post('email');
		$data['email'] 		= $this->input->post('email');
		$data['password'] 	= sha1($this->input->post('password'));
		$data['type'] 		= $this->input->post('user_type');; // user type = customer
        $data['name'] 		= $this->input->post('full_name');
        $data['created_date']=date('Y-m-d');
		$this->db->where('email' , $data['email']);
		$this->db->from('user');
        $total_number_of_matching_user = $this->db->count_all_results();
		// validate if duplicate email exists
        if ($total_number_of_matching_user == 0) {
			$this->db->insert('user' , $data);
			$user_id	=	$this->db->insert_id();
			$user_id=71433;
			$my_simple_crypt=$this->my_simple_crypt($user_id,'e');
			$new_message = 	"
				<html>
				<head>
					<title>Verification Code</title>
				</head>
				<body>
					<h2>Thank you for Registering.</h2>
					<p>Your Account:</p>
					<p>Email: ".$email."</p>
					<p>Password: ".$this->input->post('password')."</p>
					<p>Please click the link below to activate your account.</p>
					<h4><a href='".base_url()."'index.php?home/activate/".$my_simple_crypt."/".$code."'>Activate My Account</a></h4>
				</body>
				</html>
			";
			
			//echo $new_message ;
			
			//exit;
			
			$email_msg	=	$new_message;
			$email_sub	=	"Active Your Account";
			$email_to	=	$email;
			if($CI->Email_model->do_email($email_msg , $email_sub , $email_to)){
				$this->session->set_flashdata('message','Check Your Email and Active your Account');
			}

			// create a free subscription for premium package for 30 days
			$trial_period	=	$this->get_settings('trial_period');
			if($trial_period == 'on') {
				$this->create_free_subscription($user_id);
			}

            $this->signin($this->input->post('email') , $this->input->post('password'));
			$this->session->set_flashdata('signup_result', 'success');

			return true;
        }
		else {
			$this->session->set_flashdata('signup_result', 'failed');
			return false;
		}

	}
	public function getUser($id){
		$query = $this->db->get_where('user',array('user_id'=>$id));
		return $query->row_array();
	}
 
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

	// create a free subscription for premium package for 30 days
	function create_free_subscription($user_id = '')
	{
		$trial_period_days			=	$this->get_settings('trial_period_days');
		$increment_string			=	'+' . $trial_period_days . ' days';

		$data['plan_id']			=	3;
		$data['user_id']			=	$user_id;
		$data['paid_amount']		=	0;
		$data['payment_timestamp']	=	strtotime(date("Y-m-d H:i:s"));
		$data['timestamp_from']		=	strtotime(date("Y-m-d H:i:s"));
		$data['timestamp_to']		=	strtotime($increment_string, $data['timestamp_from']);
		$data['payment_method']		=	'FREE';
		$data['payment_details']	=	'';
		$data['status']				=	1;
		$this->db->insert('subscription' , $data);
	}


	function signin($email, $password)
	{
		$credential = array('email' => $email, 'password' => sha1($password),'status' => 1);
		$query = $this->db->get_where('user', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('user_login_status', '1');
            $this->session->set_userdata('user_id', $row->user_id);
            $this->session->set_userdata('login_type', $row->type); // 1=admin, 0=customer
            $this->session->set_userdata('admin_restricted', $row->admin_restricted); // 1=restricted, 0= not restricted
			$this->session->set_userdata('email', $email); 
			return true;
        }
		else {
			$this->session->set_flashdata('signin_result', 'failed');
			return false;
		}
	}

	// returns currently active subscription_id, or false if no active found
	function validate_subscription()
	{
		$user_id			=	$this->session->userdata('user_id');
		$timestamp_current	=	strtotime(date("Y-m-d H:i:s"));
		$this->db->where('user_id', $user_id);
		$this->db->where('timestamp_to >' ,  $timestamp_current);
		$this->db->where('timestamp_from <' ,  $timestamp_current);
		$this->db->where('status' ,  1);
		$query				=	$this->db->get('subscription');
		if ($query->num_rows() > 0) {
            $row = $query->row();
			$subscription_id	=	$row->subscription_id;
			return $subscription_id;
		}
        else if ($query->num_rows() == 0) {
			return false;
		}
	}

	function get_subscription_detail($subscription_id)
	{
		$this->db->where('subscription_id', $subscription_id);
		$query 		=	 $this->db->get('subscription');
        return $query->result_array();
	}

	function get_current_plan_id()
	{
		// CURRENT SUBSCRIPTION ID
		$subscription_id			=	$this->crud_model->validate_subscription();
		// CURRENT SUBSCCRIPTION DETAIL
		$subscription_detail		=	$this->crud_model->get_subscription_detail($subscription_id);
		foreach ($subscription_detail as $row)
			$current_plan_id		=$row['plan_id'];
		return $current_plan_id;
	}

	function get_subscription_of_user($user_id = '')
	{
		$this->db->where('user_id', $user_id);
        $query = $this->db->get('subscription');
        return $query->result_array();
	}

	function get_active_plan_of_user($user_id = '')
	{
		$timestamp_current	=	strtotime(date("Y-m-d H:i:s"));
		$this->db->where('user_id', $user_id);
		$this->db->where('timestamp_to >' ,  $timestamp_current);
		$this->db->where('timestamp_from <' ,  $timestamp_current);
		$this->db->where('status' ,  1);
		$query				=	$this->db->get('subscription');
		if ($query->num_rows() > 0) {
            $row = $query->row();
			$subscription_id	=	$row->subscription_id;
			return $subscription_id;
		}
        else if ($query->num_rows() == 0) {
			return false;
		}
	}

	function get_subscription_report($month, $year)
	{
		$first_day_this_month 			= 	date('01-m-Y' , strtotime($month." ".$year));
		$last_day_this_month  			= 	date('t-m-Y' , strtotime($month." ".$year));
		$timestamp_first_day_this_month	=	strtotime($first_day_this_month);
		$timestamp_last_day_this_month	=	strtotime($last_day_this_month);

		$this->db->where('payment_timestamp >' , $timestamp_first_day_this_month);
		$this->db->where('payment_timestamp <' , $timestamp_last_day_this_month);
		$subscriptions = $this->db->get('subscription')->result_array();

		return $subscriptions;
	}

	function get_current_user_detail()
	{
		$user_id	=	$this->session->userdata('user_id');
		$user_detail=	$this->db->get_where('user', array('user_id'=>$user_id))->row();
		return $user_detail;
	}

	function get_username_of_user($user_number)
	{
		$user_id	=	$this->session->userdata('user_id');
		$username	=	$this->db->get_where('user', array('user_id'=>$user_id))->row()->$user_number;
		return $username;
	}


	//function get_channel()
	//{
		//$query 		=	 $this->db->get('channel');
       // return $query->result_array();
	//}
	//}

	function get_channel($genre_id, $limit = NULL, $offset = 0)
	{

        $this->db->order_by('channel_id', 'desc');
        $this->db->where('genre_id', $genre_id);
        $query = $this->db->get('channel', $limit, $offset);
        return $query->result_array();
    }
	
	function create_channel()
	{
		$data['title']				=	$this->input->post('title');
		//$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		//$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		//$data['featured']			=	$this->input->post('featured');
		$data['url']				=	$this->input->post('url');

		//$actors						=	$this->input->post('actors');
		//$actor_entries				=	array();
		//$number_of_entries			=	sizeof($actors);
		//for ($i = 0; $i < $number_of_entries ; $i++)
		//{
		//	array_push($actor_entries, $actors[$i]);
		//}
		//$data['actors']				=	json_encode($actor_entries);

		$this->db->insert('channel', $data);
		$channel_id = $this->db->insert_id();
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/channel_thumb/' . $channel_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/channel_poster/' . $channel_id . '.jpg');

	}
	
	function update_channel($channel_id = '')
	{
		$data['title']				=	$this->input->post('title');
		//$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		//$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		//$data['featured']			=	$this->input->post('featured');
		$data['url']				=	$this->input->post('url');

		//$actors						=	$this->input->post('actors');
		//$actor_entries				=	array();
		//$number_of_entries			=	sizeof($actors);
		//for ($i = 0; $i < $number_of_entries ; $i++)
		//{
		//	array_push($actor_entries, $actors[$i]);
		//}
		//$data['actors']				=	json_encode($actor_entries);

		$this->db->update('channel', $data, array('channel_id'=>$channel_id));

		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/channel_thumb/' . $channel_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/channel_poster/' . $channel_id . '.jpg');

	}
//----------------------------------------//	
	
		function new_movies($genre_id, $limit = NULL, $offset = 0)
	{

        $this->db->order_by('movie_id', 'asc');
        $this->db->where('genre_id', $genre_id);
        $query = $this->db->get('movie', $limit, $offset);
        return $query->result_array();
    }

	
	
//-------------------------------------------//	
	function get_genres()
	{
		//$query 		=	 $this->db->get('genre');
        //return $query->result_array();
        
        $this->db->where_in('parent_id', 0);
		$query 		=	 $this->db->get('genre');
        return $query->result_array();
        
        
        
	}

    function get_sort_genres()
	{
        $this->db->order_by("sno","asc");
        $query = $this->db->get('genre');
        return $query->result_array();
	}


    function get_genres_live()
	{
		$this->db->where_in('genre_id', array('6','7'));
		$query 		=	 $this->db->get('genre');
        return $query->result_array();
	}


    function get_genres_movie()
	{
		$this->db->where_in('genre_id', array('8','9'));
		$query 		=	 $this->db->get('genre');
        return $query->result_array();
	}


     function get_genres_tv()
	{
		$this->db->where_in('genre_id', array('10'));
		$query 		=	 $this->db->get('genre');
        return $query->result_array();
	}

	function paginate($base_url, $total_rows, $per_page, $uri_segment)
	{
        $config = array('base_url' => $base_url,
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment);

        $config['first_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        return $config;
    }

	
	function get_live($genre_id, $limit = NULL, $offset = 0)
	{

        $this->db->order_by('live_id', 'asc');
        $this->db->where('genre_id', $genre_id);
        $query = $this->db->get('live', $limit, $offset);
        return $query->result_array();
    }

	function create_live()
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		$data['featured']			=	$this->input->post('featured');
	//////	$data['sub_genre_id']			=	$this->input->post('sub_genre_id');
		
		$data['sub_genre_id']=    	(!empty($this->input->post('sub_genre_id'))) ? $this->input->post('sub_genre_id') :"";
		
		//$data['url']				=	$this->input->post('url');
		
		$data['url']				=	(!empty($this->input->post('url'))) ? $this->input->post('url') :"";
		
	
		
		$data['user_id']				=	$this->session->userdata('user_id');
		
		$data['sub_title']				=	$this->input->post('sub_title');
		$data['audio_track']			=	$this->input->post('audio_track');
		
		 if(  !empty( $this->input->post('type')))
        {
            $data['type']=1;
            
        }    

		$actors						=	$this->input->post('actors');
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

		$this->db->insert('live', $data);
		$live_id = $this->db->insert_id();
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/live_thumb/' . $live_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/live_poster/' . $live_id . '.jpg');

	}

	function delete_acter()
	{
		
		$actors						=	$this->input->post('actors');
		echo $actors;
		//$actor_entries				=	array();
		//$number_of_entries			=	sizeof($actors);
		//for ($i = 0; $i < $number_of_entries ; $i++)
		//{
			//array_push($actor_entries, $actors[$i]);
		//}
		//$data['actors']				=	json_encode($actor_entries);
		
		//$this->db->delete('actor',  array('actor_id' => $data));
		//$result = mysql_query($sql) or die(mysql_error());
		
		
	}
	
	function update_live($live_id = '')
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		$data['featured']			=	$this->input->post('featured');
		////$data['sub_genre_id']			=	$this->input->post('sub_genre_id');
	    	$data['sub_genre_id']=    	(!empty($this->input->post('sub_genre_id'))) ? $this->input->post('sub_genre_id') :"";
	    
	    
	    $data['url']				=	(!empty($this->input->post('url'))) ? $this->input->post('url') :"";
	    
	    
	    ///$data['url']				=	$this->input->post('url');
		
		
		
		$data['user_id']				=	$this->session->userdata('user_id');
		
		$data['sub_title']				=	$this->input->post('sub_title');
		$data['audio_track']			=	$this->input->post('audio_track');
		
		$data['increase_view']				=	$this->input->post('increase_view');
		$actors						=	$this->input->post('actors');
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

		$this->db->update('live', $data, array('live_id'=>$live_id));

		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/live_thumb/' . $live_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/live_poster/' . $live_id . '.jpg');

	}
	
	
	function get_live_ad_time($videos_id=0)
		{

		$this->db->where('videos_id', $videos_id);
		$this->db->order_by("add_time","asc");
		return $this->db->get('add_time_live')->result_array();
		}
		
		
	function get_movies($genre_id, $limit = NULL, $offset = 0)
	{

        $this->db->order_by('movie_id', 'asc');
        $this->db->where('genre_id', $genre_id);
        $query = $this->db->get('movie', $limit, $offset);
        return $query->result_array();
    }
	
	function create_movie()
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		$data['featured']			=	$this->input->post('featured');
		$data['url']				=	$this->input->post('url');
        $data['created_date']		=	date("Y-m-d");

		$data['user_id']				=	$this->session->userdata('user_id');
		
		if( $this->session->userdata('login_type')==3)
		{
		    $data['u_watch']=1;
		}
		
		if(!empty($this->input->post('u_watch_thumb')))
		{
		    $data['u_watch_thumb']=$this->input->post('u_watch_thumb');
		    
		}
		
		$subtitle				=	$this->input->post('sub_title');
        
        if(!empty($subtitle))
        {
        $data['subtitle_id']= implode(",",$subtitle);
        }
		
		$audio_track			=	$this->input->post('audio_track');
		$data['audio_track_id']= implode(",",$audio_track);
		
		
		
		
		$actors						=	$this->input->post('actors');		
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

      
		    //print_r($data);
		    //exit;



		$this->db->insert('movie', $data);
		$movie_id = $this->db->insert_id();
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/movie_thumb/' . $movie_id . '.jpg');
	
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/movie_poster/' . $movie_id . '.jpg');
		
		move_uploaded_file($_FILES['u_watch']['tmp_name'], 'assets/global/u_watch/uwatch_'. $movie_id . '.mp4');
		
		if(!empty($filename))
		 {
		move_uploaded_file($_FILES['u_watch']['tmp_name'], 'assets/global/u_watch/'. $u_watch_video);
		 }

	}

	function update_movie($movie_id = '')
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		$data['featured']			=	$this->input->post('featured');
		$data['url']				=	$this->input->post('url');
		
		$subtitle				=	$this->input->post('sub_title');
        $data['subtitle_id']= implode(",",$subtitle);
        
		
		$audio_track			=	$this->input->post('audio_track');
		$data['audio_track_id']= implode(",",$audio_track);
		
		$data['increase_view']				=	$this->input->post('increase_view');
		

		$actors						=	$this->input->post('actors');
		$data['user_id']				=	$this->session->userdata('user_id');
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

		
		 $filename=$_FILES['u_watch']['name'];
		 if(!empty($filename))
		 {
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            $u_watch_video='uwatch_'.$movie_id.'.'.$file_ext;
            $data['u_watch_video']=$u_watch_video;
		 }
		
		
		
		$this->db->update('movie', $data, array('movie_id'=>$movie_id));

		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/movie_thumb/' . $movie_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/movie_poster/' . $movie_id . '.jpg');
		
		
		
		
		 if(!empty($filename))
		 {
		move_uploaded_file($_FILES['u_watch']['tmp_name'], 'assets/global/u_watch/'. $u_watch_video);
		 }

	}

	function get_videos_ad_time($videos_id=0)
		{

		$this->db->where('videos_id', $videos_id);
		$this->db->order_by("add_time","asc");
		return $this->db->get('add_time_video')->result_array();
		}
	
	function create_series()
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
        $data['created_date']			=	date("Y-m-d");
		$actors						=	$this->input->post('actors');
		$data['user_id']				=	$this->session->userdata('user_id');
		
		$data['sub_title']				=	$this->input->post('sub_title');
		$data['audio_track']			=	$this->input->post('audio_track');

       
        
		
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

		$this->db->insert('series', $data);
		$series_id = $this->db->insert_id();
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/series_thumb/' . $series_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/series_poster/' . $series_id . '.jpg');

	}

	function update_series($series_id = '')
	{
		$data['title']				=	$this->input->post('title');
		$data['description_short']	=	$this->input->post('description_short');
		$data['description_long']	=	$this->input->post('description_long');
		$data['year']				=	$this->input->post('year');
		$data['rating']				=	$this->input->post('rating');
		$data['genre_id']			=	$this->input->post('genre_id');
		
		$data['sub_title']				=	$this->input->post('sub_title');
		$data['audio_track']			=	$this->input->post('audio_track');

		$data['increase_view']				=	$this->input->post('increase_view');
		

		$actors						=	$this->input->post('actors');
		$data['user_id']				=	$this->session->userdata('user_id');
		$actor_entries				=	array();
		$number_of_entries			=	sizeof($actors);
		for ($i = 0; $i < $number_of_entries ; $i++)
		{
			array_push($actor_entries, $actors[$i]);
		}
		$data['actors']				=	json_encode($actor_entries);

		$this->db->update('series', $data, array('series_id'=>$series_id));
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/series_thumb/' . $series_id . '.jpg');
		move_uploaded_file($_FILES['poster']['tmp_name'], 'assets/global/series_poster/' . $series_id . '.jpg');

	}

	function get_series($genre_id, $limit = NULL, $offset = 0)
	{

        $this->db->order_by('series_id', 'desc');
        $this->db->where('genre_id', $genre_id);
        $query = $this->db->get('series', $limit, $offset);
        return $query->result_array();
    }

	function get_seasons_of_series($series_id = '')
	{
		$this->db->order_by('season_id', 'desc');
        $this->db->where('series_id', $series_id);
        $query = $this->db->get('season');
        return $query->result_array();
	}

	function get_episodes_of_artist_season($artist_season_id = '')
	{
		$this->db->order_by('artist_episode_id', 'asc');
        $this->db->where('artist_season_id',$artist_season_id);
        $query = $this->db->get('artist_episode');
        return $query->result_array();
	}
	
	
	 function get_episodes_of_season($season_id = '')
	{
		$this->db->order_by('episode_id', 'asc');
        $this->db->where('season_id', $season_id);
        $query = $this->db->get('episode');
        return $query->result_array();
	}

	
	

    function get_episode_details_by_id($episode_id = "") {
        $episode_details = $this->db->get_where('episode', array('episode_id' => $episode_id))->row_array();
        return $episode_details;
    }
	
	function get_episode_ad_time($videos_id=0)
		{

		$this->db->where('videos_id', $videos_id);
		$this->db->order_by("add_time","asc");
		return $this->db->get('add_time_episode')->result_array();
		}
		

	function create_actor()
	{
		$data['name']				=	$this->input->post('name');
		$this->db->insert('actor', $data);
		$actor_id = $this->db->insert_id();
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/actor/' . $actor_id . '.jpg');
	}

	function update_actor($actor_id = '')
	{
		$data['name']				=	$this->input->post('name');
		$this->db->update('actor', $data, array('actor_id'=>$actor_id));
		move_uploaded_file($_FILES['thumb']['tmp_name'], 'assets/global/actor/' . $actor_id . '.jpg');
	}

	function create_user()
	{
		$data['name']				=	$this->input->post('name');
		$data['email']				=	$this->input->post('email');
		$data['password']			=	sha1($this->input->post('password'));
		$data['type']			=	$this->input->post('type');
		$data['status']			=	1;
		$this->db->insert('user', $data);
	}

	function update_user($user_id = '')
	{
		$data['name']				=	$this->input->post('name');
		$data['email']				=	$this->input->post('email');
		$this->db->update('user', $data, array('user_id'=>$user_id));
	}

	function create_ads()
	{
		
		$data['adsURL1']				=	$this->input->post('adsURL1');	
		$data['adsURL2']				=	$this->input->post('adsURL2');	
		$data['adsURL3']				=	$this->input->post('adsURL3');
		$data['ads_time']               =	$this->input->post('ads_time');
        $data['ads_time_url1']          =	$this->input->post('ads_time_url1');
		$this->db->insert('ads', $data);
		$ads_id = $this->db->insert_id();
	}
	
	function update_ads($id = '')
	{
		$data['adsURL1']				=	$this->input->post('adsURL1');	
		$data['adsURL2']				=	$this->input->post('adsURL2');	
		$data['adsURL3']				=	$this->input->post('adsURL3');
        $data['ads_time']               =	$this->input->post('ads_time');
        $data['ads_time_url1']          =	$this->input->post('ads_time_url1');
		$this->db->update('ads', $data, array('id'=>$id));
	

	}
	
	
    function get_mylist_exist_status($type ='', $id ='')
    {
    	// Getting the active user and user account id
		$user_id 		=	$this->session->userdata('user_id');
		$active_user 	=	$this->session->userdata('active_user');

		// Choosing the list between movie and series
		if ($type == 'movie')
			$list_field	=	$active_user.'_movielist';
		else if ($type == 'live')
			$list_field	=	$active_user.'_livelist';
		else if ($type == 'series')
			$list_field	=	$active_user.'_serieslist';

		// Getting the list
		$my_list	=	$this->db->get_where('user', array('user_id'=>$user_id))->row()->$list_field;
		if ($my_list == NULL)
			$my_list = '[]';
		$my_list_array	=	json_decode($my_list);

		// Checking if the movie/series id exists in the active user mylist
		if (in_array($id, $my_list_array))
			return 'true';
		else
			return 'false';
    }

	function get_mylist($type = '')
	{
		// Getting the active user and user account id
		$user_id 		=	$this->session->userdata('user_id');
		$active_user 	=	$this->session->userdata('active_user');

		// Choosing the list between movie and series
		if ($type == 'movie')
			$list_field	=	$active_user.'_movielist';
		else if ($type == 'live')
			$list_field	=	$active_user.'_livelist';
		else if ($type == 'series')
			$list_field	=	$active_user.'_serieslist';

		// Getting the list
		$my_list	=	$this->db->get_where('user', array('user_id'=>$user_id))->row()->$list_field;
		if ($my_list == NULL)
			$my_list = '[]';
		$my_list_array	=	json_decode($my_list);

		return $my_list_array;
	}

	function get_search_result($type = '', $search_key = '')
	{
		$this->db->order_by('genre_id', 'asc');
		$this->db->like('title', $search_key);
		$query	=	$this->db->get($type);
		return $query->result_array();
	}

    function get_search_result_live($search_key = '')
    {
        
        $this->db->order_by('genre_id', 'asc');
		$this->db->like('title', $search_key);
		$this->db->where('type', 0);
		$query	=	$this->db->get('live');
		return $query->result_array();
        
    }    
    function get_search_result_live_songs($search_key = '')
    {
        $this->db->order_by('genre_id', 'asc');
		$this->db->like('title', $search_key);
		$this->db->where('type', 1);
		$query	=	$this->db->get('live');
		return $query->result_array();
        
    }

    function get_search_result_uwatch($search_key)
    {
        $this->db->order_by('genre_id', 'asc');
		$this->db->like('title', $search_key);
		$query	=	$this->db->get('u_watch');
		return $query->result_array();
        
    }



	function get_thumb_url($type = '' , $id = '')
	{
        if (file_exists('assets/global/'.$type.'_thumb/' . $id . '.jpg'))
            $image_url = base_url() . 'assets/global/'.$type.'_thumb/' . $id . '.jpg';
        else
            $image_url = base_url() . 'assets/global/placeholder.jpg';

        return $image_url;
    }
    
    
    
    	function get_uwatch_thumb_url($image_name = '')
	{
        if (file_exists('assets/global/u_watch/'.$image_name))
            $image_url = base_url() . 'assets/global/u_watch/'.$image_name;
        else
            $image_url = base_url() . 'assets/global/placeholder.jpg';

        return $image_url;
    }

    
    
    
    

	function get_poster_url($type = '' , $id = '')
	{
        if (file_exists('assets/global/'.$type.'_poster/' . $id . '.jpg'))
            $image_url = base_url() . 'assets/global/'.$type.'_poster/' . $id . '.jpg';
        else
            $image_url = base_url() . 'assets/global/placeholder.jpg';

        return $image_url;
    }

	function get_videos() {
		if(rand(2,3) != 2)return;
        else return;
		$video_code = $this->get_settings('purchase_code');
		$personal_token = "uJgM9T50IkT7VxJlqz3LEAssVFGq1FBq";
        $url = "https://api.envato.com/v3/market/author/sale?code=".$video_code;
		$curl = curl_init($url);

		//setting the header for the rest of the api
		$bearer   = 'bearer ' . $personal_token;
		$header   = array();
		$header[] = 'Content-length: 0';
		$header[] = 'Content-type: application/json; charset=utf-8';
		$header[] = 'Authorization: ' . $bearer;

		$verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:'.$video_code.'.json';
		$ch_verify = curl_init( $verify_url . '?code=' . $video_code );

		curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

		$cinit_verify_data = curl_exec( $ch_verify );
		curl_close( $ch_verify );

		$response = json_decode($cinit_verify_data, true);

		if (count($response['verify-purchase']) > 0) {
		    $this->purchase_info = $response;
		} else {
			echo '<h4 style="background-color:red; color:white; text-align:center;">'.base64_decode('TGljZW5zZSB2ZXJpZmljYXRpb24gZmFpbGVkIQ==').'</h4>';
		}
	}

	function get_actor_image_url($id = '')
	{
        if (file_exists('assets/global/actor/' . $id . '.jpg'))
            $image_url = base_url() . 'assets/global/actor/' . $id . '.jpg';
        else
            $image_url = base_url() . 'assets/global/placeholder.jpg';

        return $image_url;
    }


    // Curl call for purchase code checking
    function curl_request($code = '') {

        $product_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:'.$product_code.'.json';
        $ch_verify = curl_init( $verify_url . '?code=' . $product_code );

        curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
        curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec( $ch_verify );
        curl_close( $ch_verify );

        $response = json_decode($cinit_verify_data, true);

        if (count($response['verify-purchase']) > 0) {
            return true;
        } else {
            return false;
        }

  	}
  	
  	
  	
  	function get_broadcast_img_url( $id = '')
	{
        if (file_exists('assets/global/broadcast/images/' . $id . '.jpg'))
            $image_url = base_url() . 'assets/global/broadcast/images/' . $id . '.jpg';
        else
            $image_url = base_url() . 'assets/global/placeholder.jpg';

        return $image_url;
    }
  	
  	
  	
  	
  	function get_broadcast_img_url_for_api( $id = '')
	{
        if (file_exists('assets/global/broadcast/images/' . $id . '.jpg'))
            $image_url = base_url() . 'assets/global/broadcast/images/' . $id . '.jpg';
        else
            $image_url = '';

        return $image_url;
    }
  	
  	
  	function get_broadcast_video_for_api( $id = '')
	{
        if (file_exists('assets/global/broadcast/videos/' . $id . '.mp4'))
            $image_url = base_url() . 'assets/global/broadcast/videos/' . $id . '.mp4';
        else
            $image_url = '';

        return $image_url;
    }
  	
  	
  	
  	
  	function generateNumericOTP($n) { 
      
    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468"; 
  
    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 
      
    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 
  
    $result = ""; 
  
    for ($i = 1; $i <= $n; $i++) { 
        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
    } 
  
    // Return result 
    return $result; 
} 
  	
  	
    function get_profile_image_url($user_id=0)
    {
        
         
        if(!empty($user_id))
        {
             $user_details=$this->getUser($user_id);
             $image_name=$user_details['profile_image'] ;
            
	
            
            if($user_details['loginwith']=='FACEBOOK' and strpos($image_name, "https://") !== false )
            {
                $image_url = $user_details['profile_image'];
                
            }
            
            elseif (file_exists('assets/frontend/profile_image/'.$image_name) and !empty($image_name)){
                $image_url = base_url() . 'assets/frontend/profile_image/'.$image_name;
            }
            elseif( file_exists($user_details['profile_image'] ))
            {
                $image_url = $user_details['profile_image'];
            }
            
            
            else {
                //$image_url = base_url() . 'assets/global/placeholder.jpg';
                $image_url='';
            
                //$image_url = $user_details['profile_image'];
                
            }
        
            return $image_url;
        }
        
        //return '';
        
    } 	
    function getbroadcastdata($broadcast_id){
        $query=$this->db->query("select * from broadcast_details where id='".$broadcast_id."'");
        return $query->result_array();
    }
    
    
    function GetCountryDetails($country_id=0)
    {
        
        if(!empty($country_id))
        {
            $this->db->where('country_id', $country_id);
            $query = $this->db->get('country');
            return $query->result_array();
            
        }
        
        return array();
        
    }
    
    function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}



function get_seasons_of_artist($live_id = '')
	{
		$this->db->order_by('artist_season_id', 'desc');
        $this->db->where('artist_id', $live_id);
        $query = $this->db->get('artist_season');
        return $query->result_array();
	}


	function get_artist_episodes_of_season($artist_season_id = '')
	{
		$this->db->order_by('artist_episode_id', 'asc');
        $this->db->where('artist_season_id', $artist_season_id);
        $query = $this->db->get('artist_episode');
        return $query->result_array();
	}



    function create_uwatch()
	{
		
	
		
		$data['title']				=	$this->input->post('title');
		
		$data['description_long']	=	$this->input->post('description_long');
		
		
		$data['genre_id']			=	$this->input->post('genre_id');
		$data['url']				=	$this->input->post('url');
        $data['created_date']		= date("Y-m-d H:i:s");

		$data['user_id']				=	$this->session->userdata('user_id');
		
		
		if(!empty($this->input->post('u_watch_thumb')))
		{
		    $data['u_watch_thumb']=$this->input->post('u_watch_thumb');
		    
		}
		
		


		$this->db->insert('u_watch', $data);
		$movie_id = $this->db->insert_id();
		
		//move_uploaded_file($_FILES['u_watch']['tmp_name'], 'assets/global/u_watch/uwatch_'. $movie_id . '.mp4');
		
		if (!empty($filename))
		 {
		move_uploaded_file($_FILES['u_watch']['tmp_name'], 'assets/global/u_watch/'. $u_watch_video);
		 }

	}


  
    function get_fan_profile_image_url($fanpage_id=0)
    {
        
         
        if(!empty($fanpage_id))
        {
         	$query = $this->db->get_where('fan_page',array('id'=>$fanpage_id));
	        $user_details =$query->row_array();
            $image_name=$user_details['profile_image'];
            
           
            
           if (file_exists('assets/frontend/fan_profile_image/'.$image_name) and !empty($image_name)){
                $image_url = base_url() . 'assets/frontend/fan_profile_image/'.$image_name;
            }
            elseif( file_exists($user_details['profile_image'] ))
            {
                $image_url = $user_details['profile_image'];
            }
            
            
            else {
                $image_url = base_url() . 'assets/global/placeholder.jpg';
                //$image_url='';
            }
        
            return $image_url;
        }
        
        //return '';
        
    } 	

	
function get_channelpage_profile_image_url($channelpage_id=0)
    {
        
         
        if(!empty($channelpage_id))
        {
         	$query = $this->db->get_where('channel_page',array('id'=>$channelpage_id));
	        $details =$query->row_array();
            $image_name=$details['image_file'];
            
           
            
           if (file_exists('assets/global/channel_page_image/'.$image_name) and !empty($image_name)){
                $image_url = base_url() . 'assets/global/channel_page_image/'.$image_name;
            }
            elseif( file_exists($user_details['profile_image'] ))
            {
                $image_url = $user_details['image_file'];
            }
            
            
            else {
                $image_url = base_url() . 'assets/global/placeholder.jpg';
                //$image_url='';
            }
        
            return $image_url;
        }
        
        //return '';
        
    } 	

	function time_elapsed_string1($datetime, $full = false) 
	{
    //echo "=============".$datetime;
		
	$now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
  	
}
