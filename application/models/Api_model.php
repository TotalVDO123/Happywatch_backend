<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	


	public function get_genre_movie()
	{
		//$sql="select  G.* from movie M  inner join  genre G  on M.genre_id=G.genre_id  group by M.genre_id " ;
		
	$sql="select  G.* from movie M  inner join  genre G  on M.genre_id=G.genre_id  group by M.genre_id order by G.sno  " ;	
		
		$query = $this->db->query($sql);
		
		return $result= $query->result_array();
	}



		


	public function GetAll_MovieOfGenre($genre_id=0)
	{
		$sql="select * from movie where genre_id='".$genre_id."' order by 	movie_id desc" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}

	public function get_genre_live($start=0,$limit=10)
	{
		//$sql="select  G.* from  live L  inner join  genre G  on L.genre_id=G.genre_id  group by L.genre_id order by G.sno " ;
		
		//$sql="select  G.* from  live L  inner join  genre G  on L.genre_id=G.genre_id  or G.genre_id=39 where L.type=0 group by G.genre_id order by G.sno " ;
		
		///$sql_limit="";
		
		 $sql_limit="limit ".$start. ",". $limit ;
		
		$sql="select  G.* from  live L  inner join  genre G  on L.genre_id=G.genre_id   where 1  group by G.genre_id order by G.sno ".$sql_limit;
			
		
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}


    public function get_music_parent_genre_live()
    {
        $sql="select  G.* from  live L  inner join  genre G  on L.genre_id=G.genre_id where L.type=1 group by L.genre_id order by G.sno " ;
        
        $query = $this->db->query($sql);
		return $result= $query->result_array();
        
    } 

    public function get_music_genre_live($genre_id)
    {
       $sql="select  G.* from  live L  inner join  genre G  on L.sub_genre_id=G.genre_id where L.type=1 and L.genre_id='". $genre_id."' group by L.sub_genre_id order by L.live_id desc " ;
        $query = $this->db->query($sql);
		return $result= $query->result_array(); 
        
    }

	
	public function GetAll_LiveOfGenre($genre_id=0)
	{
		///$sql="select *, count(live_view.id) as view_count from live left join live_view on live.live_id=live_view.live_id  where live.genre_id='".$genre_id."' order by live.live_id desc " ;
		
		$sql="select *, (select count(*) from live_view as lv where lv.live_id=live.live_id  ) as view_count from live  where live.genre_id='".$genre_id."' order by live.live_id desc " ;	
		
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}



	public function GetAll_music_LiveOfGenre($genre_id=0)
	{
		$sql="select * from live where sub_genre_id='".$genre_id."' order by live_id asc " ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}


	public function GetAll_Series_Genre()
	{
		$sql="select G.* from series S inner join genre G on S.genre_id=G.genre_id group by S.genre_id order by genre_id " ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}
	
	public function GetAll_SeriesOfGenre($genre_id=0)
	{
		$sql="select * from series where genre_id='".$genre_id."' order by 	series_id desc" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}
	
	
	
	public function GetAll_SeasonOfSeries($series_id=0)
	{
		$sql="select * from  season where series_id='".$series_id."' order by season_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	
	}
	
	public function GetAll_episodeOfSeason($season_id=0)
	{
		$sql="select * from  episode where season_id='".$season_id."' order by episode_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	
	}
	
	
	public function Get_episodeOfSeason_accept_episode($season_id=0,$episode_id=0)
	{
		$sql="select * from  episode where season_id='".$season_id."' and episode_id!='".$episode_id."' order by episode_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	
	}
	
	
	public function get_episode_details($episode_id=0)
	{
		$sql="select * from  episode where  episode_id='".$episode_id."' order by episode_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	
	}
	
	
	public function GetAll_watch_later_episode($user_id=0)
	{
		//$sql="select * from  episode  where season_id='".$season_id."' order by episode_id" ;
		//$sql="select E.* from watch_later WL inner join  episode E on WL.episode_id=E.episode_id where WL.user_id='".$user_id."' order by WL.episode_id desc" ;

        $sql="select E.* ,SEA.series_id  from watch_later WL inner join  episode E on WL.episode_id=E.episode_id 
		left join  season SEA on E.season_id=SEA.season_id
		where WL.user_id='".$user_id."' order by WL.episode_id desc" ;


		$query = $this->db->query($sql);
		return $result= $query->result_array();
	
	}
	
	
	
	
	public function signup($data)
	{
		  $this->db->insert('user',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	public function otp_active($email,$otps)
	{
		$sql="select * from  user where email='".$email."' and otp='".$otps."' ";
		$res = $this->db->query($sql);
		return $res;
	}
	public function otp_active_success($user_id=0)
	{
		$sql="update user set status=1  where user_id='".$user_id."' ";
		$res = $this->db->query($sql);
		return $res;
	}
	public function otp_delete($user_id=0)
	{
		$sql="update user set otp='NULL' where user_id='".$user_id."'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function user_signin($signin_type='email')
	{
		if($signin_type=='email')
		{		
			$email=	trim($this->input->post('email'));
			$password=sha1($this->input->post('password'));
			$sql="select * from  user where email='".$email."' and password='".$password."' and status=1" ;
		}	
		elseif($signin_type=='mobile')	
		{
			$mobile=trim($this->input->post('mobile'));
			//$mobile=substr($mobile, -10);
			
			$mobile=trim($mobile);
			//$password=sha1($this->input->post('password'));
			//$sql="select * from  user where mobile='".$mobile."' and password='".$password."' and status=1" ;
			///$sql="select * from  user where (SUBSTRING(mobile,-10)='".$mobile."') and password='".$password."' and status=1" ;
		
		    /////$sql="select * from  user where mobile='".$mobile."' and password='".$password."' and status=1" ;
		    
		    $sql="select * from  user where mobile='".$mobile."'" ;
		
		}
		
		
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) 
		{
			//return 1;		
			return $result= $res->result_array();
		}
		else
		{
			return '';
			
		}
		///////return $result= $query->result_array();
	}
	
	
	
	public function user_signinwithmobile()
	{
		
			$mobile=trim($this->input->post('mobile'));
			//$mobile=substr($mobile, -10);
			$password=sha1($this->input->post('password'));
			$mobile=trim($mobile);
		    
		    $sql="select * from  user where mobile='".$mobile."' and password='".$password."' and status=1" ;
		
		
		
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) 
		{
			//return 1;		
			return $result= $res->result_array();
		}
		else
		{
			return '';
			
		}
		///////return $result= $query->result_array();
	}
	
	
	
	
	
	public function GetUser_Subscription_Detail($user_id=0)
	{
		$sql="select S.*,U.name,U.email from subscription S left join user U on S.user_id=U.user_id
		where S.user_id='".$user_id."' and S.status=1 order by payment_timestamp desc limit 1";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	
	
	public function GetGenre_details($genre_id=0)
	{
		$sql="select * from genre where genre_id='".$genre_id."'";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	
	public function GetAdvertisement()
	{
	    $sql="select * from ads limit 1";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	public function Get_Advertisement_Time($video_id=0)
	{
	    $sql="select * from add_time_video where videos_id='".$video_id."' order by add_time asc ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	
	 public function Get_MovieAdv_Time()
    {
        $sql="select * from add_time_video1 limit 1 ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	    
        
    }

	
	
	
	
	
	public function Get_liveAdvertisement_Time($video_id=0) 
	{
	    $sql="select * from add_time_live where videos_id='".$video_id."' order by add_time asc ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	
	   // $sql="select * from add_time_live limit 1 ";
		//$res = $this->db->query($sql);
		//return $result= $res->result_array();
	    
	    
	    
	}

    public function Get_liveAdv_Time()
    {
        $sql="select * from add_time_live limit 1 ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	    
        
    }

	
	public function Get_episodeAdvertisement_Time($video_id=0) 
	{
	    $sql="select * from add_time_episode where videos_id='".$video_id."' order by add_time asc ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	
	
	 public function Get_SeriesAdv_Time()
    {
        $sql="select * from add_time_series limit 1 ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	    
        
    }

	
	
	public function edit_profile($data,$user_id=0)
	{
		
		if(!empty($user_id))
		{
			$this->db->where('user_id', $user_id);
			return $this->db->update('user', $data);
		}
	
		return '';
	}
	

    public function edit_fan_profile($data,$fanpage_id=0)
	{
		
		if(!empty($fanpage_id))
		{
			$this->db->where('id', $fanpage_id);
			return $this->db->update('fan_page', $data);
		}
	
		return '';
	}


	public function edit_channelpage_profile($data,$channelpage_id=0)
	{
		
		if(!empty($channelpage_id))
		{
			$this->db->where('id', $channelpage_id);
			return $this->db->update('channel_page', $data);
		}
	
		return '';
	}
	
	


	public function add_watch_later_movie($data)
	{
		  $this->db->insert('watch_later',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	
	public function update_watch_later_movie($user_id=0,$movie_id=0,$data)
	{
			$this->db->where('user_id', $user_id);
			$this->db->where('movie_id', $movie_id);
			return $this->db->update('watch_later', $data);
	
	}
	
	public function delete_watch_later_movie($user_id=0,$movie_id=0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('movie_id', $movie_id);
		return $this->db->delete('watch_later'); 
	}
	
	
	public function delete_watch_later_episode($user_id=0,$episode_id=0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('episode_id', $episode_id);
		return $this->db->delete('watch_later'); 
	}
	
	
	public function get_watch_later_movie($user_id=0)
	{
		$sql="select M.* from watch_later WL 
		inner join movie M on WL.movie_id= M.movie_id
		where WL.user_id='".$user_id."' and WL.movie_id>0 order by M.movie_id desc ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();

	}
	public function new_released()
	{
		
		$sql = "SELECT * FROM   movie  where 1 and   created_date 
		BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() order by movie_id desc";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}


	public function new_released_series()
	{
		$sql = "SELECT * FROM  series  where 1 and   created_date 
		BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() order by series_id desc";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}




    public function getgenre()
	{
		$sql="select * from genre  order by name " ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}



    public function getcategory()
    {
        $sql="select * from  category  order by cat_id " ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
        
        
    }
    



    public function create_broadcast_like_unlike($data)
	{
		  $this->db->insert('broadcast_like_unlike',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}

	
	 public function live_broadcast_like_unlike($data)
	{
		  $this->db->insert('live_like_unlike',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	
	
	 public function movie_like_unlike($data)
	{
		  $this->db->insert('video_like_unlike',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	
	
	 public function uwatch_like_unlike($data)
	{
		  $this->db->insert('uwatch_like_unlike',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}

	 public function update_live_like_unlike($data,$user_id=0,$live_id=0)
	{
        
        if($user_id>0 and $live_id>0 )
        {
            $this->db->where('live_id', $live_id);
        	$this->db->where('user_id', $user_id);
        	$this->db->update('live_like_unlike', $data);
        }
	}	
	
	
	public function update_movie_like_unlike($data,$user_id=0,$movie_id=0)
	{
        
        if($user_id>0 and $movie_id>0 )
        {
            $this->db->where('live_id', $live_id);
        	$this->db->where('movie_id', $user_id);
        	$this->db->update('video_like_unlike', $data);
        }
	}	
	
	
	 public function update_uwatch_like_unlike($data,$user_id=0,$uwatch_id=0)
	{
        
        if($user_id>0 and $uwatch_id>0 )
        {
            $this->db->where('u_watch_id', $uwatch_id);
        	$this->db->where('user_id', $user_id);
        	$this->db->update('uwatch_like_unlike', $data);
        }
	}	
	
	
	
    public function update_broadcast_like_unlike($data,$user_id=0,$broadcast_id=0)
	{
        
        if($user_id>0 and $broadcast_id>0 )
        {
            $this->db->where('broadcast_id', $broadcast_id);
        	$this->db->where('user_id', $user_id);
        	$this->db->update('broadcast_like_unlike', $data);
        }
	}
    
    
    
    public function delete_broadcast($user_id=0,$broadcast_id=0)
	{
		
		if(!empty($user_id) and !empty($broadcast_id))
		{
    		$this->db->where('user_id', $user_id);
    		$this->db->where('id', $broadcast_id);
    		return $this->db->delete('broadcast_details'); 
    	}
		
		return "";    
		    
	}
    
    public function delete_broadcast_comments($user_id=0,$broadcast_id=0)
	{
		
		if(!empty($user_id) and !empty($broadcast_id))
		{
    		$this->db->where('user_id', $user_id);
    		$this->db->where('broadcast_id', $broadcast_id);
    		return $this->db->delete('broadcast_comments'); 
    	}
		    
		return "";    
	}
    
public function delete_broadcast_like_unlike($user_id=0,$broadcast_id=0)
	{
		
		if(!empty($user_id) and !empty($broadcast_id))
		{
    		$this->db->where('user_id', $user_id);
    		$this->db->where('broadcast_id', $broadcast_id);
    		return $this->db->delete('broadcast_like_unlike'); 
    	}
    	
    	return "";
		    
		    
	}
    
    
    
    
    public function get_video_broadcast()
    {
	
		
		$broadcastchannel_id=$this->input->post('broadcastchannel_id');
        $user_id=$this->input->post('user_id');
		if(!empty($broadcastchannel_id) and !empty($user_id) )
		{   
    		$this->db->select('*');
    		$this->db->from('video_broadcast_list');
    		$this->db->where('user_id', $user_id);
    		$this->db->where('broadcastchannel_id ', $broadcastchannel_id);
    		$query = $this->db->get();
    		return $query->result_array();
		}   
        else
        {   
            return '';
            
        }   
        
    }
    
    
    
    public function broadcast_view($broadcast_id=0)
    {
        
         if(!empty($broadcast_id))
         {
            $this->db->select('*');
    		$this->db->from('broadcast_view');
    		$this->db->where('broadcast_id', $broadcast_id);
    		$query = $this->db->get();
    		return $query->num_rows();
    	
         }
          else
          {
              return 0;
          }
        
        
    }
    
    
    
    
    public function get_subtitle($subtitle_id=0)
	{
		
		if(!empty($subtitle_id))
		{
			$this->db->select('*');
    		$this->db->from('subtitle');
    		$this->db->where('subtitle_id', $subtitle_id);
    		$query = $this->db->get();
    		return $query->result_array();
    		
		}
	
		return '';
	}

    public function get_audio_track($audio_track_id=0)
	{
		
		if(!empty($audio_track_id))
		{
			$this->db->select('*');
    		$this->db->from('audio_track');
    		$this->db->where('audio_track_id', $audio_track_id);
    		$query = $this->db->get();
    		return $query->result_array();
    		
		}
	
		return '';
	}

    
    
    public function save_share_info($data)
    {
          $this->db->insert('share_details', $data);
    	 return $share_id = $this->db->insert_id();
    }
    
      public function save_live_share_info($data)
    {
          $this->db->insert('live_share_details', $data);
    	 return $share_id = $this->db->insert_id();
    }
    
       public function save_movie_share_info($data)
    {
          $this->db->insert('movie_share_details', $data);
    	 return $share_id = $this->db->insert_id();
    }
    
    
    
    public function get_sharing_info_count($broadcast_id=0)
    {
            
            if(!empty($broadcast_id))
            {
                $this->db->select('*');
        		$this->db->from('share_details');
        		$this->db->where('broadcast_id', $broadcast_id);
        		$query = $this->db->get();
        		return $query->num_rows();
            }
            
    		    return 0;
    		//return $query->result_array();
    }
    
    
   
     public function create_channel($data)
    {
         $this->db->insert('broadcast_live_channel', $data);
    	 return $channel_id = $this->db->insert_id();
    }
    
    
    public function get_channel_list($channel_id=0)
    {
                
                if(!empty($channel_id))
                {
                    $this->db->select('*');
            		$this->db->from('broadcast_live_channel');
            		$this->db->where('channel_id', $channel_id);
            		$query = $this->db->get();
            		return $query->result_array();
                }	
    
            return '';
    }
    
    
    
     public function get_channel_details($user_id=0)
    {
            
            if(!empty($user_id))
            {
                $this->db->select('*');
        		$this->db->from('broadcast_live_channel');
        		$this->db->where('user_id', $user_id);
        		$query = $this->db->get();
        		return $query->result_array();
            }
            
    		    return 0;
    		//return $query->result_array();
    }
    
    
     public function save_channel_video_data($data)
    {
         $this->db->insert('channel_video', $data);
    	 return $channel_id = $this->db->insert_id();
    }
    
    public function save_broadcast_streaming_comments($data)
	{
	   	$this->db->insert('broadcast_streaming_comments' , $data);
	    $inserted_id	=	$this->db->insert_id();
        return $inserted_id;
	}
	
	
	function get_broadcast_streaming_comments($broadcast_id="")
    {
    
    if(!empty($broadcast_id))
    {
        $this->db->select('broadcast_streaming_comments.*,user.name,user.user_id,user.email');
        $this->db->from('broadcast_streaming_comments');
        $this->db->join('user', 'broadcast_streaming_comments.user_id = user.user_id'); 

        $this->db->where(array('broadcast_streaming_comments.broadcast_id'=>$broadcast_id));
        $this->db->order_by('broadcast_streaming_comments.created_date', 'desc');
         $this->db->limit(10);
        $q = $this->db->get()->result_array();
        return $q;
    }
    else
    {
        return "";
        
    }
    
	}
	
    
	public function increase_movie_view($movie_id=0)
	{
	
		$sql="select increase_view from  movie where movie_id='".$movie_id."'" ;
		$res = $this->db->query($sql);
		$increase_view=0;
        if ($res->num_rows() > 0) 
		{
			$result= $res->result_array();
			$increase_view=$result[0]['increase_view'];
		}		
		return $increase_view;
	}	
    
	
	
	public function increase_broadcast_view($broadcast_id=0)
	{
	
		$sql="select increase_view from  broadcast_details where id='".$broadcast_id."'" ;
		$res = $this->db->query($sql);
		$increase_view=0;
        if ($res->num_rows() > 0) 
		{
			$result= $res->result_array();
			$increase_view=$result[0]['increase_view'];
		}		
		return $increase_view;
	}	

	public function increase_uwatch_view($uwatch_id=0)
	{
	
		$sql="select increase_view from   u_watch where u_watch_id='".$uwatch_id."'" ;
		$res = $this->db->query($sql);
		$increase_view=0;
        if ($res->num_rows() > 0) 
		{
			$result= $res->result_array();
			$increase_view=$result[0]['increase_view'];
		}		
		return $increase_view;
	}	
	
	
    public function total_view_movie($movie_id=0)
    {
        
        if(!empty($movie_id))
        {
            $this->db->select('count(*) as tot');
    		$this->db->from('movie_view');
    		$this->db->where('movie_id', $movie_id);
    		
    		$query = $this->db->get();
    		$num = $query->num_rows();
    		if($num > 0)
    		{
    		    $record=  $query->result_array();
    		    return $record[0]['tot'];
    		}
    		else
    		{
    		    return 0;
    		    
    		}
        }
        else
        {
            return 0;
        }
		
		//$record=  $query->result_array();
        
        
        //	$res = $this->db->query($sql);
		//if($res->num_rows() > 0) 
        
       //print_r($num);
        
        
    }
    
	
	
	public function increase_live_view($live_id=0)
	{
	
		$sql="select increase_view from  live where live_id='".$live_id."'" ;
		$res = $this->db->query($sql);
		$increase_view=0;
        if ($res->num_rows() > 0) 
		{
			$result= $res->result_array();
			$increase_view=$result[0]['increase_view'];
		}		
		return $increase_view;
	}	
    
    public function total_view_live($live_id=0)
        {
            if(!empty($live_id))
            {
                $this->db->select('count(*) as tot');
        		$this->db->from('live_view');
        		$this->db->where('live_id', $live_id);
        		
        		$query = $this->db->get();
        		$num = $query->num_rows();
        		if($num > 0)
        		{
        		    $record=  $query->result_array();
        		    return $record[0]['tot'];
        		}
        		else
        		{
        		    return 0;
        		    
        		}
            }
            else
            {
                    return 0;
            }
        }	
		
		
	
	
	public function increase_episode_view($episode_id=0)
	{
	
		/*
		$sql="select S.series_id from season S left join episode EP on S.season_id=EP.season_id   where EP.episode_id='".$episode_id."'" ;
		$res1 = $this->db->query($sql);
		$seriesid=0;
        if ($res1->num_rows() > 0) 
		{
			$result1= $res1->result_array();
			$seriesid=$result1[0]['series_id'];
		}		
		*/
		
		$sql="select increase_view from episode where 	episode_id ='".$episode_id."'" ;
		$res = $this->db->query($sql);
		$increase_view=0;
        if ($res->num_rows() > 0) 
		{
			$result= $res->result_array();
			$increase_view=$result[0]['increase_view'];
		}		
		return $increase_view;
	}	

	
		public function total_view_season($episode_id=0)
        {
            if(!empty($episode_id))
            {
                $this->db->select('count(*) as tot');
        		$this->db->from('season_view');
        		$this->db->where('episode_id', $episode_id);
        		
        		$query = $this->db->get();
        		$num = $query->num_rows();
        		if($num > 0)
        		{
        		    $record=  $query->result_array();
        		    return $record[0]['tot'];
        		}
        		else
        		{
        		    return 0;
        		    
        		}
            }
            else
            {
                    return 0;
            }
        }	
		
		
    
    	public function get_artist_season_of_series($live_id=0)
    	{
    		$sql="select artist_season.*, live.title from  artist_season left join live on artist_season.artist_id=live.live_id   where artist_season.artist_id='".$live_id."' order by artist_season.artist_season_id" ;
    		$query = $this->db->query($sql);
    		return $result= $query->result_array();
    	
    	}
        
    	public function get_artist_episode_of_season($artist_season_id=0)
    	{
    		$sql="select * from artist_episode where artist_season_id='".$artist_season_id."' order by artist_episode_id" ;
    		$query = $this->db->query($sql);
    		return $result= $query->result_array();
    	
    	}
    
    public function get_artist_episode_of_season_accept_episode($artist_season_id=0,$artist_episode_id=0)
    	{
    		$sql="select * from artist_episode where artist_season_id='".$artist_season_id."' and artist_episode_id!='".$artist_episode_id."' order by artist_episode_id" ;
    		$query = $this->db->query($sql);
    		return $result= $query->result_array();
    	
    	}
    
    
	 public function get_artist_episode_details($artist_episode_id=0)
    	{
    		$sql="select * from artist_episode where artist_episode_id ='".$artist_episode_id."' order by artist_episode_id" ;
    		$query = $this->db->query($sql);
    		return $result= $query->result_array();
    	
    	}
    
	
	
	
    
    	public function u_watch_list($start=0,$limit=10)
    	{
    	    //$sql="select  * from movie where u_watch_video!='' order by movie_id desc  " ;
    	    
    	    $sql="select UW.*,U.name,U.email,CP.name as channel_page_name,CP.id as channel_page_id    from  u_watch UW left join user U on UW.user_id=U.user_id  
			left join channel_page CP on UW.channelpage_id=CP.id
			order by created_date desc limit $start,$limit  " ;
    	    
    		$query = $this->db->query($sql);
    		return $result= $query->result_array();
    	}
    
    
    	public function create_adminpopup_notification($data)
	    {
		  $this->db->insert('adminpopup_notification',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	    }
    
    
    public function check_favpost()
	{
		
		$user_id=$this->input->post('user_id');
		$broadcast_id=$this->input->post('broadcast_id'); //broadcast_id	
		
		
		
		if(!empty($user_id) and !empty($broadcast_id))
		{		
			$sql = "SELECT * FROM saved_broadcast_fav_post where broadcast_id	 ='".$broadcast_id."' and user_id='".$user_id."'";
			$res = $this->db->query($sql);
			if ($res->num_rows() > 0) {
				return 1;
			}
			else
			{
				return 0;
			}	

			
		}
	
	
						
	}
    
    public function save_fav_broadcast_post($data) 
	{
	   $this->db->set($data);
	   $this->db->insert('saved_broadcast_fav_post');
	   return $this->db->insert_id();
	}
    
    
    
    public function get_fav_broadcast_post()
	{
		
		$user_id=$this->input->post('user_id');
		 $sql = "SELECT BD.*,C.name,U.name as user_name FROM  saved_broadcast_fav_post FB 
		left join  broadcast_details BD on FB.broadcast_id=BD.id 
		left join user U   on U.user_id=BD.user_id
		left join category C on BD.cat_id= C.cat_id
		
		where 1 and FB.user_id='".$user_id."'";
		$res = $this->db->query($sql);
			if ($res->num_rows() > 0) {
				$row = $res->result_array();
				return $row;
			}
			return '';
		
	}
    
    
    
    

//$sql = "SELECT * FROM  videos  where 1 and publication=1 and  created_date //BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() order by total_view desc ";


	/**public function get_userID($username="")
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $username);
		$query = $this->db->get();
		return $query->row();

    }	*/
	
	
	
	/*public function get_gallery($movie_id=1)
    {
	
		$this->db->select('*');
		$this->db->from('movie');
		$this->db->where('movie_id', $movie_id);
		$query = $this->db->get();
		return $query->result_array();
    }
*/
	/**public function get_data_update()
	{
		$this->db->select('reaccess_flag');
		$this->db->from('reaccess');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	
    
    * Count the number of rows
    * @param int $manufacture_id
    * @param int $search_string
    * @param int $order
    * @return int
    */
   /** function count_gallery($manufacture_id=null, $search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('gallery');
		$this->db->where('user_id', $this->session->userdata['logged_in']['users_id']);
		//if($manufacture_id != null && $manufacture_id != 0){
		//	$this->db->where('manufacture_id', $manufacture_id);
		//}
		//if($search_string){
		//	$this->db->like('description', $search_string);
		//}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

	
	
	
	 
    public function save_gallery($data,$id=0) {
        
		if($id>0)
		{
			$this->db->where('id', $id);
			$this->db->update('gallery', $data);
  
		  
		}
		else
		{	
			$this->db->insert('gallery', $data);
			$users_id = $this->db->insert_id();
		}
		
        if ($this->db->affected_rows() > 0) {
            $notif['message'] = 'Saved successfully';
            $notif['type'] = 'success';
            unset($_POST);
        } else {
            $notif['message'] = 'Something wrong !';
            $notif['type'] = 'danger';
        }
        return $notif;
    }

    /*
     * 
     */

	public function user_details($user_id=0)
    {
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('user_id', $user_id);
		//$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->result_array();
    }
    
     public function delete_add_friends($user_id=0,$friend_user_id=0)
	{
		
		if(!empty($user_id) and !empty($friend_user_id))
		{
    		$this->db->where('user_id', $user_id);
    		$this->db->where('friend_user_id', $friend_user_id);
    		return $this->db->delete('add_friends'); 
    	}
		
		return "";    
		    
	}
    
    public function getmyFriendsData($user_id=0,$sort_order=0)
    {
        ///add_friends

        $this->db->select('user.*');
		$this->db->from('add_friends');
		$this->db->join('user', 'add_friends.friend_user_id = user.user_id', 'left');
		$this->db->where('add_friends.user_id', $user_id);
		$this->db->where('user.status', 1);
		
		if($sort_order==1)
		{
		  $this->db->order_by('add_friends.created_at', 'desc');  
		}
		elseif($sort_order==2)
		{
		    
		    $this->db->order_by('user.name', 'ASC');  
		    
		}
		elseif($sort_order==0)
		{
		    
		    $this->db->order_by('add_friends.created_at', 'ASC');  
		    
		}
		
		
		$query = $this->db->get();
		return $query->result_array();
    }
	
	
	public function myfriends_search($user_id=0,$search_string="",$sort_order=0)
	{
	    $this->db->select('user.*');
		$this->db->from('add_friends');
		$this->db->join('user', 'add_friends.friend_user_id = user.user_id', 'left');
		//$this->db->where('add_friends.user_id', $user_id);
		
		//$this->db->like('name', $name);
        //$this->db->or_like('surname', $surname);

		

		//this->db->like('(user.name', $search_string,'before' ); 
		///$this->db->or_like('user.email', $search_string,'before')";
		
		$this->db->where("(user.name LIKE '%".$search_string."%' OR user.email LIKE  '%".$search_string."%')", NULL, FALSE);
		
		
		$this->db->where('add_friends.user_id', $user_id);
		$this->db->where('user.status', 1);
		
		if($sort_order==1)
		{
		  $this->db->order_by('add_friends.created_at', 'DESC');  
		}
		elseif($sort_order==2)
		{
		    
		    $this->db->order_by('user.name', 'ASC');  
		    
		}
		elseif($sort_order==0)
		{
		    
		    $this->db->order_by('add_friends.created_at', 'ASC');  
		    
		}
		
		$query = $this->db->get();
		return $query->result_array();
	    
	    
	}
	
	public function mutual_friends($user_id=0,$friend_user_id=0)
	{
	    
	      $sql=" SELECT * FROM add_friends as AF1 inner join add_friends as AF2 on AF1.friend_user_id=AF2.friend_user_id where AF2.user_id='".$friend_user_id."' and AF1.user_id='".$user_id."'";
		   	$query = $this->db->query($sql);     
		   
           return $query->num_rows();
	    
	    
	}
	
	
	public function search_user($search_string="",$user_id=0)
	{
	   
	   
	    ///$where_as="and U.user_id not in (SELECT user_id FROM broadcast_post_block_unblock_user where blocking_user_id='".$user_id."')";
	   
	   
	    $this->db->select('user.*');
		//$this->db->like('user.name', $search_string,'before' ); 
		//$this->db->or_like('user.email', $search_string,'before');
	
	
	
	
		
		$this->db->from('user');
	
		$this->db->where('user.status', 1);
		$this->db->where("user_id NOT IN (SELECT user_id FROM broadcast_post_block_unblock_user where blocking_user_id='".$user_id."')", NULL, FALSE);
		
		//	$this->db->like('(user.name', $search_string ); 
		//$this->db->or_like('user.email', $search_string).")";
	
	$this->db->where("(user.name LIKE '%".$search_string."%' OR user.email LIKE  '%".$search_string."%')", NULL, FALSE);
	
//	$where_con = array('user.name' => $search_string , 'user.email ' => $search_string);
//	$this->db->like($where_con);
	
		$query = $this->db->get();
		return $query->result_array();
	    
	    
	}
	
	public function search_fan_page($search_string="")
	{
	    $this->db->select('*');
		$this->db->from('fan_page');
		$this->db->like('name', $search_string ); 
		
		$query = $this->db->get();
		return $query->result_array();
	    
	    
	}
	
	public function search_channel_page($search_string="")
	{
	    $this->db->select('*');
		$this->db->from('channel_page');
		$this->db->like('name', $search_string ); 
		
		$query = $this->db->get();
		return $query->result_array();
	    
	    
	}
	
	
	
	public function notifaction_send_in_user($user_id=0,$notification_id=0)
	{
	    
	     $sql = "select * from notification_user where user_id='".$user_id."' and notification_id='".$notification_id."'";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
            return 1;
	    }
	    else
	    {
	       return ''; 
	    }
	    
	}
	
	
	public function content_provider_application($data)
	{
		  $this->db->insert('content_provider_application',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	
	public function get_fanpage_followings($user_id=0,$sort_order=0,$search_string="")
	{
	    if(!empty($user_id))
	    {
    	    /*
			$this->db->select('fan_page_follow.*,fan_page.name,user.*');
    		$this->db->from('fan_page');
    		$this->db->join('fan_page_follow', 'fan_page.id=fan_page_follow.fanpage_id', 'left');
			$this->db->join('user', 'fan_page_follow.user_id = user.user_id', 'left');
			$this->db->where('fan_page.user_id', $user_id ); 
			*/
			
			$this->db->select('fan_page_follow.*,fan_page.name');
    		$this->db->from('fan_page_follow');
    		$this->db->join('fan_page', 'fan_page_follow.fanpage_id = fan_page.id', 'left');
			$this->db->where('fan_page_follow.user_id', $user_id ); 
			
			if(!empty($search_string))
			{	
			$this->db->where("(fan_page.name LIKE '%".$search_string."%' )", NULL, FALSE);
			}

			
			



    		if($sort_order==1)
    		{
    		  $this->db->order_by('fan_page_follow.created_at', 'DESC');  
    		}
    		elseif($sort_order==2)
    		{
    		    $this->db->order_by('user.name', 'ASC');  
    		}
			elseif($sort_order==0)
    		{
    		    $this->db->order_by('fan_page_follow.created_at', 'ASC');  
    		}

			

    		$query = $this->db->get();
    		return $query->result_array();
	    }
	}
	
	
	


public function user_fanpage_follow($user_id=0,$fanpage_id=0)
	{
	    if(!empty($user_id) and !empty($fanpage_id))
	    {
    	    
			
			
			$this->db->select('*');
    		$this->db->from('fan_page_follow');
			$this->db->where('user_id', $user_id ); 
			$this->db->where('fanpage_id', $fanpage_id ); 
			
    		$query = $this->db->get();
			//$query->result_array();
			//echo $this->db->last_query();
			
			//echo "===========".$query->num_rows();			

			if ($query->num_rows() > 0) 
	    	{
				return $query->num_rows();
			}
			else
			{
				return 0;
			}

    		
	    }
		else
		{
			return 0;
		}	
	}


	
	public function fanpage_following_count($user_id=0)
	{
		$sql = "select * from fan_page_follow where user_id='".$user_id."'";
        $res = $this->db->query($sql);
		return $res->num_rows(); 	
		

	}
	
	public function fanpage_follower_count($fanpage_id=0)
	{
		$sql = "select * from fan_page_follow where fanpage_id='".$fanpage_id."'";
        $res = $this->db->query($sql);
		return $res->num_rows();  
	}

	

	public function get_MyFanPageFollower($fanpage_id=0,$sort_order=0,$search_string="")
	{
	    if(!empty($fanpage_id))
	    {
    	    $this->db->select('fan_page_follow.*,fan_page.name,user.*');
    		$this->db->from('fan_page_follow');
    		$this->db->join('fan_page', 'fan_page_follow.fanpage_id = fan_page.id', 'left');
    		
			$this->db->join('user', 'fan_page_follow.user_id = user.user_id', 'left');
			
			$this->db->where('fan_page_follow.fanpage_id', $fanpage_id ); 
			
			if(!empty($search_string))
			{	
			$this->db->where("(user.name LIKE '%".$search_string."%' )", NULL, FALSE);
			}



    		if($sort_order==1)
    		{
    		  $this->db->order_by('fan_page_follow.created_at', 'DESC');  
    		}
    		elseif($sort_order==2)
    		{
    		    $this->db->order_by('fan_page.name', 'ASC');  
    		}
			elseif($sort_order==0)
    		{
    		    $this->db->order_by('fan_page_follow.created_at', 'ASC');  
    		}

			

    		$query = $this->db->get();
    		return $query->result_array();
	    }
	}
		








	public function myuwatch_channel_subscribers($channel_page_id=0,$sort_order=0,$search_string="")
	{
	    
	    if(!empty($channel_page_id))
	    {
	        $this->db->select('channel_page_subscribe.*,user.name,user.email');
    		$this->db->from('channel_page_subscribe');
    	
    		$this->db->join('user', 'channel_page_subscribe.user_id = user.user_id', 'left');
    		
    		$this->db->where('channel_page_subscribe.channel_id', $channel_page_id ); 
    		
			if(!empty($search_string))
			{
			$this->db->where("(user.name LIKE '%".$search_string."%' OR user.email LIKE  '%".$search_string."%')", NULL, FALSE);
			}	

			if($sort_order==1)
    		{
    		  $this->db->order_by('channel_page_subscribe.created_at', 'DESC');  
    		}
    		elseif($sort_order==2)
    		{
    		     $this->db->order_by('user.name', 'ASC');  
    		}
			elseif($sort_order==0)
			{
				$this->db->order_by('channel_page_subscribe.created_at', 'ASC');  
			}	
    		$query = $this->db->get();
    		return $query->result_array();

	    }     
	}
	
	
	
	public function delete_un_save_fav_post($user_id=0,$broadcast_id=0)
	{	
		if(!empty($user_id) and !empty($broadcast_id))
		{
		    $this->db->where('user_id', $user_id);
    		$this->db->where('broadcast_id', $broadcast_id);
    		return $this->db->delete('saved_broadcast_fav_post'); 
		}
		else
		{
		    
		   return ''; 
		}
	}
	
	public function content_provider_application_status($user_id=0)
	{
	   $sql = "select * from content_provider_application where user_id='".$user_id."'";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
         
             $record=  $res->result_array();
              if($record[0]['status']==0)
              {
                  return 'Pending';
              }
             elseif($record[0]['status']==1)
             {
                 return 'Approved';
             }
             elseif($record[0]['status']==2)
             {
                 return 'Rejected';
             }
            
        
            
	    }
	    else
	    {
	       return 'Not Submitted'; 
	    } 
	    
	    
	    
	}
	
	public function my_fan_page_details($user_id=0)
	{
	   $sql = "select * from  fan_page where user_id='".$user_id."' limit 1";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
          return $res->result_array();
	    }
	    else
	    {
	        return array();
	    }
	}
	

	public function fan_page_details($fan_page_id=0)
	{
	   $sql = "select * from  fan_page where id='".$fan_page_id."' limit 1";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
          return $res->result_array();
	    }
	    else
	    {
	        return array();
	    }
	}

	

	
	public function my_channe_details($user_id=0)
	{
	    
	     $sql = "select * from  channel_page where user_id='".$user_id."' limit 1";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
          return $res->result_array();
	    }
	    else
	    {
	        return array();
	    }
	    
	    
	    
	}
	
	
	public function channepage_details($channel_id=0)
	{
	    
	     $sql = "select * from  channel_page where id='".$channel_id."' limit 1";
        $res = $this->db->query($sql);
		if ($res->num_rows() > 0) 
	    {
          return $res->result_array();
	    }
	    else
	    {
	        return array();
	    }
	    
	    
	    
	}


	
	public function broadcast_post_save_block($data)
	{
		  $this->db->insert('broadcast_post_block_unblock_user',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
	}
	
	public function broadcast_post_block_delete($user_id=0,$blocking_user_id=0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('blocking_user_id', $blocking_user_id);
		return $this->db->delete('broadcast_post_block_unblock_user'); 
	}
	
	
	public function user_channelpage_subscribe($user_id=0,$channel_id=0)
	{
	    if(!empty($user_id) and !empty($channel_id))
	    {
    	    
			
			
			$this->db->select('*');
    		$this->db->from('channel_page_subscribe');
			$this->db->where('user_id', $user_id ); 
			$this->db->where('channel_id', $channel_id ); 
			
    		$query = $this->db->get();
			//$query->result_array();
			//echo $this->db->last_query();
			
			//echo "===========".$query->num_rows();			

			if ($query->num_rows() > 0) 
	    	{
				return 1;
			}
			else
			{
				return 0;
			}

    		
	    }
		else
		{
			return 0;
		}	
	}
	
		public function AllUser($user_id=0)
		{
			$this->db->select('*');
    		$this->db->from('user');
			$this->db->where('user_id!=', $user_id );
			$this->db->where('name!=', '' );
			$query = $this->db->get();
    		return $query->result_array();
		}
	
		
		public function relationship_status()
		{
			$this->db->select('*');
    		$this->db->from('relationship_status');
			$this->db->order_by('id', 'asc');
			$query = $this->db->get();
    		return $query->result_array();
		}
	



	
}
