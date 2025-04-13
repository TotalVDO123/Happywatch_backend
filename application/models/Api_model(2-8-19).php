<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	


	public function get_genre_movie()
	{
		$sql="select  G.* from movie M  inner join  genre G  on M.genre_id=G.genre_id  group by M.genre_id" ;
		$query = $this->db->query($sql);
		
		return $result= $query->result_array();
	}



		


	public function GetAll_MovieOfGenre($genre_id=0)
	{
		$sql="select * from movie where genre_id='".$genre_id."' order by 	movie_id desc" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}

	public function get_genre_live()
	{
		$sql="select  G.* from  live L  inner join  genre G  on L.genre_id=G.genre_id  group by L.genre_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}


	
	public function GetAll_LiveOfGenre($genre_id=0)
	{
		$sql="select * from live where genre_id='".$genre_id."'" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}

	public function GetAll_Series_Genre()
	{
		$sql="select G.* from series S inner join genre G on S.genre_id=G.genre_id group by S.genre_id order by genre_id" ;
		$query = $this->db->query($sql);
		return $result= $query->result_array();
	}
	
	public function GetAll_SeriesOfGenre($genre_id=0)
	{
		$sql="select * from series where genre_id='".$genre_id."' order by 	series_id" ;
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
	
	
	public function signup($data)
	{
		  $this->db->insert('user',$data);
		  $insert_id = $this->db->insert_id();
		  return $insert_id;
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
			$password=sha1($this->input->post('password'));
			//$sql="select * from  user where mobile='".$mobile."' and password='".$password."' and status=1" ;
			$sql="select * from  user where (SUBSTRING(mobile,-10)='".$mobile."') and password='".$password."' and status=1" ;
		
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
	
	public function Get_liveAdvertisement_Time($video_id=0) 
	{
	    $sql="select * from add_time_live where videos_id='".$video_id."' order by add_time asc ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	}
	
	public function Get_episodeAdvertisement_Time($video_id=0) 
	{
	    $sql="select * from add_time_episode where videos_id='".$video_id."' order by add_time asc ";
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
	
	public function get_watch_later_movie($user_id=0)
	{
		$sql="select M.* from watch_later WL 
		inner join movie M on WL.movie_id= M.movie_id
		where WL.user_id='".$user_id."' and WL.movie_id>0 ";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
	
	
	}
	public function new_released()
	{
		
		$sql = "SELECT * FROM  movie  where 1 and   created_date 
		BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() order by movie_id";
		$res = $this->db->query($sql);
		return $result= $res->result_array();
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

	 
	/** public function get_gallerydetails($id=0)
	{
		
		$this->db->select('*');
		$this->db->from('gallery');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
	
	}
	
	 
	 
    public function check_email($email) {
        $sql = "SELECT * FROM users WHERE email = " . $this->db->escape($email);
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $row = $res->row();
            return $row;
        }
        return null;
    }

	function delete_gallery($id){
		$this->db->where('id', $id);
		$this->db->delete('gallery'); 
	}*/
	
	
}
