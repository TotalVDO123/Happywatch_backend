<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    
    function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('crud_model');
		$this->load->library('session');
		$this->load->model('Api_model');
		$this->load->model('email_model');

		//$this->load->model('Api_model');
		//$this->load->model('auth_model');
	 	//$this->load->model('Section_model');
		//$this->load->model('Gallery_model');
        //Utils::no_cache();
    }

	public function getmovie()
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
			
			//print_r($row_cat);
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>1,
				'genre_name'=>$row_cat['name'],
				'contents'=>$movie_content
				);
		
	  }
	 
		$ALL_JSON_ARR = array(
				"launch"=>'Movie',
				'contents'=>$JSON_ARR
				
				);
		print json_encode($ALL_JSON_ARR);
		//print json_encode($JSON_ARR);

	 
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
	
   


  public function getlive()
   {
	  $result_genre= $this->Api_model->get_genre_live();
	  
	  foreach($result_genre as $row_cat)
	  {
		$result_live= $this->Api_model->GetAll_LiveOfGenre($row_cat['genre_id']);
		$live_content=[];
		foreach($result_live as $row_live)
		{
				$ext = pathinfo($row_live['url'], PATHINFO_EXTENSION);
				$live_content[]= array(
					 "id" => $row_live['live_id'],
					 "title"=>$row_live['title'],
					 "description_short"=>$row_live['description_short'],
					 "description_long"=>$row_live['description_long'],
					 "streamFormat"=>$ext,
					 "movie_url"=>$row_live['url'],
					 "movie_poster"=>base_url().'assets/global/live_poster/'.$row_live['live_id'].".jpg",
					 "movie_thumb"=>base_url().'assets/global/live_thumb/'.$row_live['live_id'].".jpg",
					 "rating"=>$row_live['rating']
				   );	
			
		}
			
			//print_r($row_cat);
			
			$JSON_ARR[] = array(
				"genre_id"=>$row_cat['genre_id'],
				"channel_id"=>2,
				'genre_name'=>$row_cat['name'],
				'contents'=>$live_content
				);

				//print json_encode($JSON_ARR);
		
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
	
	
	
	
	public function signup()	
	{	
			if(	isset($_REQUEST['email']) &&  isset($_REQUEST['password'])  )
			{

				$email=$_REQUEST['email'];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR[] = array(
				 'response'=>"Not valid email"
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
							'response'=>"This user already exists"
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
					$data=array(
					"email"=>$_REQUEST['email'],
					"password"=>sha1($_REQUEST['password'])
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>$signup_id
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
	
	
	public function signin()	
	{		
			
			$email=	$this->input->post('email');
			$password=$this->input->post('password');
			if(	isset($email) &&  isset($password)  )
			{	
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				 $JSON_ARR[] = array(
				 'response'=>"Not valid email"
				 );
							print json_encode($JSON_ARR);
							die();
							
				}
				$result_signin= $this->Api_model->user_signin();
				
				//print_r($result_signin);
				
				if(!empty($result_signin))
				{
					$JSON_ARR[]=array(
							'user_id'=>intval($result_signin[0]['user_id']),
							'useremail'=>$email,
							'is_login'=>"YES"		
							);		
				
							
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR[]=array(
							'useremail'=>"",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
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
							'response'=>"This user already exists",
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
	
	
	
	
	
	
	public function mobile_signup()	
	{	
			if(	isset($_REQUEST['mobile']) )  
			{
				$mobile=$_REQUEST['mobile'];
				$sql = "SELECT *  FROM user where 	mobile ='".trim($mobile)."'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					//$row = $res->result_array();
					//print_r(sha1($row[0]['password']));
					//echo "==============".$row[0]['password'];
					//exit;
					//return $row;
					$JSON_ARR[] = array(
							'response'=>"This mobile number already exists",							'success'=>1
							);
					print json_encode($JSON_ARR);
					
				}
				else
				{
					$password=$this->randomPassword();
					$data=array(
					"mobile"=>trim($mobile),
					"password"=>sha1($password)
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'password'=>$password,
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
	
	
	public function mobile_signin()	
	{		
			$mobile=	$this->input->post('mobile');
			$password=$this->input->post('password');
			if(	isset($mobile) &&  isset($password) )
			{	
				$result_signin= $this->Api_model->user_signin('mobile');
				if(!empty($result_signin))
				{
					$JSON_ARR[]=array(
							'user_id'=>intval($result_signin[0]['user_id']),
							'mobile'=>$result_signin[0]['mobile'],
							'is_login'=>"YES"		
							);		
					print json_encode($JSON_ARR);
				}
				else
				{
						$JSON_ARR[]=array(
							'mobile'=>"",
							'is_login'=>"NO"		
							);		
					print json_encode($JSON_ARR);	
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
	
	
	public function SignupSigninWithFacebook()	
	{	
			if(	isset($_REQUEST['facebookid']) )  
			{
				$facebookid=$_REQUEST['facebookid'];
				$sql = "SELECT *  FROM user where 	facebook_id ='".trim($facebookid)."' and loginwith='FACEBOOK'";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0) 
				{
					
					
				}
				else
				{
					$data=array(
					"facebook_id"=>trim($facebookid),
					"loginwith"=>'FACEBOOK'
					);
			
						$signup_id= $this->Api_model->signup($data);
						if($signup_id){
							$JSON_ARR[] = array(
									'response'=>"Welcome! you have signed up successfully...",
									'user_id'=>$signup_id,
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
	
	
	
	
	
	
	
	public function get_series()
	{
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
				"description_short"=>$row_series['description_short'],
				"description_long"=>$row_series['description_long'],
				"year"=>$row_series['year'],
				"rating"=>$row_series['rating'],
				"series_poster"=>base_url().'assets/global/series_poster/'.$row_series['series_id'].".jpg",
				"series_thumb"=>base_url().'assets/global/series_thumb/'.$row_series['series_id'].".jpg"
				);
			}
			
			$genre_content[]= array(
			"genre_id" => $cat_row['genre_id'],
			"name" => $cat_row['name'],
			"series_content"=>$series_content
			);	
		
		}
		
		
		$ALL_JSON_ARR = array(
				"launch"=>'series',
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

		$ALL_JSON_ARR = array(
				"launch"=>'Season',
				'contents'=>$season_content
				);
			print json_encode($ALL_JSON_ARR);
		
	}
	
	
	
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
	
	
	
	public function search()
	{
		$search_key=$this->uri->segment(3);
		
		if(!empty($search_key))
		{	
				$movies		=	$this->crud_model->get_search_result('movie' , $search_key);
				$series		=	$this->crud_model->get_search_result('series', $search_key);
				//
				$movie_content=[];
				$result_cat="";
				foreach($movies as $row_movie)
				{
				
					$result_cat= $this->Api_model->GetGenre_details($row_movie['genre_id']);
					
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
					 "rating"=>$row_movie['rating']
				   );	
				}
				$series_content=[];
				$result_cat="";
				foreach($series as $row_series)
				{
					$result_cat= $this->Api_model->GetGenre_details($row_movie['genre_id']);
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
		
		
			$search_content= array(
			"search"=>'Search' ,
			"movie"=> $movie_content,
			"series"=> $series_content
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
	
	
	
	public function changepassword()
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
  
	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
		return implode($pass); //turn the array into a string
	}


  
}
