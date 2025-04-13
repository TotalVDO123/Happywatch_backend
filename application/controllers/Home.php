<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	// constructor
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('crud_model');
		$this->load->model('email_model');
		$this->load->library('session');
		$this->load->helper('directory');

	}

    
	// Home browsing page
	public function index()
	{ 
	   
		$this->login_check();
		$page_data['page_name']		=	'landing';
		$page_data['page_title']	=	'Welcome';
		$this->load->view('frontend/index', $page_data);
	}

	function signup()
	{
		//$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$signup_result = $this->crud_model->signup_user();
			if ($signup_result == true){
				sleep(2);
				redirect(base_url().'index.php?home/signup' , 'refresh');
				// $trial_period	=	$this->crud_model->get_settings('trial_period');
				// if ($trial_period == 'on')
				// 	redirect(base_url().'index.php?browse/switchprofile' , 'refresh');
				// else if ($trial_period == 'off')
				// 	redirect(base_url().'index.php?browse/youraccount' , 'refresh');
			}
			else if ($signup_result == false){
				redirect(base_url().'index.php?home/signup' , 'refresh');
			}

		}
		$page_data['page_name']		=	'signup';
		$page_data['page_title']	=	'Sign up';
		$this->load->view('frontend/index', $page_data);

	}
	public function activate($id){
		
		$id =  $this->uri->segment(3);
		$code = $this->uri->segment(4);
 
		//fetch user details
		$user = $this->crud_model->getUser($id);
 
		//if code matches
		if($user['code'] == $code){
			//update user active status
			$data['status'] = true;
			$query = $this->crud_model->activate($data, $id);
 
			if($query){
				$this->session->set_flashdata('message', 'User activated successfully');
				redirect(base_url().'index.php?home/signin' , 'refresh');
			}
			else{
				$this->session->set_flashdata('message', 'Something went wrong in activating account');
				redirect(base_url().'index.php?home/signin' , 'refresh');
			}
		}
		else{
			$this->session->set_flashdata('message', 'Cannot activate account. Code didnt match');
		}
	}

	function signin($param1 = "")
	{
		//$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$email 			= $this->input->post('email');
			$password 		= $this->input->post('password');
			$signin_result 	= $this->crud_model->signin($email, $password);
			if ($signin_result == true)
			{
				if ($this->session->userdata('login_type') == 1)
					redirect(base_url().'index.php?admin/' , 'refresh');
				else if ($this->session->userdata('login_type') == 2)
					redirect(base_url().'index.php?admin/' , 'refresh');
					
				else if ($this->session->userdata('login_type') == 3)
					redirect(base_url().'index.php?admin/' , 'refresh');
					
				else if ($this->session->userdata('login_type') == 0)
					redirect(base_url().'index.php?browse/switchprofile' , 'refresh');
			}
			else if ($signin_result == false){
				if ($param1 == 'admin') {
					$this->session->set_flashdata('error_message', get_phrase('Login_failed'));
					redirect(base_url().'index.php?home/signin/admin' , 'refresh');
				}else {
					redirect(base_url().'index.php?home/signin' , 'refresh');
				}
			}
		}
		if ($param1 == 'admin') {
			$this->load->view('backend/login.php');
		}else {
			$page_data['page_name']		=	'signin';
			$page_data['page_title']	=	'Sign in';
			$this->load->view('frontend/index', $page_data);
		}
	}

	function forget()
	{
		$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$signup_result = $this->email_model->reset_password();
			redirect(base_url().'index.php?home/forget' , 'refresh');
		}
		$page_data['page_name']		=	'forget';
		$page_data['page_title']	=	'Forget Password';
		$this->load->view('frontend/index', $page_data);
	}

	function support()
	{
		
		if (isset($_POST) && !empty($_POST))
		{
			
			
			//$signup_result = $this->email_model->do_contactemail();
			//redirect(base_url().'index.php?general/support' , 'refresh');
			
			
			
			$name = $_POST["userName"];
			$email = $_POST["userEmail"];
			$subject = $_POST["subject"];
			$content = $_POST["content"];

			$toEmail = "support@happywatch99.com";
			$mailHeaders = "From: " . $name . "<". $email .">\r\n";
			if(mail($toEmail, $subject, $content, $mailHeaders)) {
				echo '<script>alert("your message has been sent successfully")</script>'; 
			}
		}
		
		$page_data['page_name']		=	'CustomerSupport';
		$page_data['page_title']	=	'Customer Support';
		$this->load->view('frontend/index', $page_data);
	}

	
	function signout()
	{
		$this->session->set_userdata('user_login_status', '');
        $this->session->set_userdata('user_id', '');
        $this->session->set_userdata('login_type', '');
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url().'index.php?home/signin', 'refresh');
	}

	function login_check()
	{
		if ($this->session->userdata('user_login_status') == 1)
			redirect(base_url().'index.php?browse/home' , 'refresh');
	}
	
	
	function playbroadcast($broadcast_id=0)
	{
     	$page_data['page_name']		=	'playbroadcast';
    	$page_data['page_title']	=	'Play Broadcast';
        $page_data['broadcast_data']=$this->crud_model->getbroadcastdata($broadcast_id);
    	$page_data['broadcast_id']		=	$broadcast_id;
    	
    
    	
    	$this->load->view('frontend/index', $page_data); 
    }
    
    
    	function check_md5()
	{
	    $video_url='https://happywatch99-cache.cdnvideo.ru/happywatch99/Encoded/Movies/Khmer_Movies/Chuob_Soy/Chuob_Soy.m3u8';
	    
	    $tmp_url= str_replace ("=", "", strtr(base64_encode (md5 ("Eik4Eethahv3Eibe: 1601887654: ".$video_url, TRUE)), "+ /", "-_")); 
        echo "==".$tmp_url;   //p8I3rjrBPibszG2cZ78/Rw
	    echo "<br>";
	    
	    echo 'https://happywatch99-cache.cdnvideo.ru/happywatch99/Encoded/Movies/Khmer_Movies/Chuob_Soy/Chuob_Soy.m3u8?CWSecret='.$tmp_url.'&CWTime=1601887654';
	    
	    
	  //final url  https://happywatch99-cache.cdnvideo.ru/happywatch99/Encoded/Movies/Khmer_Movies/Chuob_Soy/Chuob_Soy.m3u8?CWSecret=p8I3rjrBPibszG2cZ78/Rw&CWTime=1601887654
	    
	    
	}
    

    function md5check2()
    {
        $video_url='https://happywatch99-cache.cdnvideo.ru/happywatch99/Encoded/Movies/Khmer_Movies/Chuob_Soy/Chuob_Soy.m3u8';
        
        $hash=md5("aliyuncdnexp/Chuob_Soy.m3u855CE8100" );
        
        echo $hash;  //cba5db178cbdede3541df0833da66d18
        
        echo '<br>';
        
        echo 'http://live-happywatch99-test.cdnvideo.ru/Chuob_Soy.m3u8?CWSecret=cba5db178cbdede3541df0833da66d18&CWTime=55CE8100';
        
        https://happywatch99-cache.cdnvideo.ru/happywatch99/Encoded/Movies/Khmer_Movies/Chuob_Soy/cba5db178cbdede3541df0833da66d18/Chuob_Soy.m3u8
        
        
        
        
    }
    
    
    function exreact_broascast_img_name()
    {
        exit;
        //$sql="select  * from broadcast_details where id >1128 and broadcast_img!='' limit 0,1 " ;
        
        $sql="select  * from broadcast_details where id >1128 and broadcast_img!='' " ;
	$query = $this->db->query($sql);
	$result= $query->result_array();
	
	foreach($result as $row)
	{
	   //print_r($row['broadcast_img']);
		
	   $array_url= explode(',',$row['broadcast_img']); 
	   echo $broadcast_id=$row['id'];
	   
	   $img_name=array();
	   foreach($array_url as $row_image)
	   {
		   //echo $row_image;
		   
		   $ext = end(explode('/', $row_image));
		   $img_name[]=$ext;
		   
		 
		} 
			
			$image_name=implode(",",$img_name);
			//echo "=========".$image_name;
			
			$data['broadcast_img_1']=$image_name;
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data);
            
			
	}	 
        
        
    }




    function exreact_video_thumb_img_name()
    {
        exit;
        //$sql="select  * from broadcast_details where id >1128 and broadcast_img!='' limit 0,1 " ;
        
        $sql="select  * from broadcast_details where id >1128 and broadcast_video_thumbnail!='' " ;
	$query = $this->db->query($sql);
	$result= $query->result_array();
	
	
	//print_r($result);
	//exit;
	
	foreach($result as $row)
	{
	   //print_r($row['broadcast_img']);
		
	   $array_url= explode(',',$row['broadcast_video_thumbnail']); 
	   echo $broadcast_id=$row['id'];
	   
	   $img_name=array();
	   foreach($array_url as $row_image)
	   {
		   //echo $row_image;
		   
		   $ext = end(explode('/', $row_image));
		   $img_name[]=$ext;
		   
		 
		} 
			
			echo $broadcast_id.",";
			$image_name=implode(",",$img_name);
			//echo "=========".$image_name;
			
			$data['broadcast_video_thumbnail_1']=$image_name;
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data);
            
			
	}	 
        
        
    }



function exreact_channel_thumb_img_name()
    {
       exit;
        //$sql="select  * from broadcast_details where id >1128 and broadcast_img!='' limit 0,1 " ;
        
        $sql="select  * from broadcast_details where id >1128 and full_video_thumb_url!='' " ;
	$query = $this->db->query($sql);
	$result= $query->result_array();
	
	
	//print_r($result);
	//exit;
	
	foreach($result as $row)
	{
	   //print_r($row['broadcast_img']);
		
	   $array_url= explode(',',$row['full_video_thumb_url']); 
	   echo $broadcast_id=$row['id'];
	   
	   $img_name=array();
	   foreach($array_url as $row_image)
	   {
		   //echo $row_image;
		   
		   $ext = end(explode('/', $row_image));
		   $img_name[]=$ext;
		   
		 
		} 
			
			echo $broadcast_id.",";
			$image_name=implode(",",$img_name);
			//echo "=========".$image_name;
			
			$data['full_video_thumb_url_1']=$image_name;
            
            	$this->db->where('id', $broadcast_id);
			    $this->db->update('broadcast_details', $data);
            
			
	}	 
        
        
    }

	
	

}
