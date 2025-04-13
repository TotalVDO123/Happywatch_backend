<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }
	
	
	
	
	
		function reset_password_for_api() {
		// Checking email and mobile  existence
		echo $email_phone		=	$this->input->post('email_phone');
        //$query 		=	$this->db->get_where('user', array('email' => $email));
		
		$sql="select * from  user where email='".$email_phone."' or mobile='".$email_phone."'  and status=1" ;
		$res = $this->db->query($sql);


        echo "==============".$res->num_rows();
        exit;
        if ($res->num_rows() > 0) {
			
			$result= $res->result_array();
			
			// Saving the new password's hashed value into database
			$new_password = substr(md5(rand(100000000, 20000000000)), 0, 7);
        	$new_hashed_password = sha1($new_password);
			$this->db->update('user', array('password' => $new_hashed_password), array('email'=>$email));
			$this->session->set_flashdata('password_reset', 'success');
			
			// Sending user the notification email with new password
			$email_msg	=	"Your new password is : ".$new_password;
			$email_sub	=	"Password reset request";
			$email_to	=	$email;
			$this->do_email($email_msg , $email_sub , $email_to);
        }
		else {
			$this->session->set_flashdata('password_reset', 'failed');
		}
	}
	
	
	
	
	
	
	function reset_password() {
		// Checking email existence
		$email		=	$this->input->post('email');
        $query 		=	$this->db->get_where('user', array('email' => $email));
			
        if ($query->num_rows() > 0) {
			
			// Saving the new password's hashed value into database
			$new_password = substr(md5(rand(100000000, 20000000000)), 0, 7);
        	$new_hashed_password = sha1($new_password);
			$this->db->update('user', array('password' => $new_hashed_password), array('email'=>$email));
			$this->session->set_flashdata('password_reset', 'success');
			
			// Sending user the notification email with new password
			$email_msg	=	"Your new password is : ".$new_password;
			$email_sub	=	"Password reset request";
			$email_to	=	$email;
			$this->do_email($email_msg , $email_sub , $email_to);
        }
		else {
			$this->session->set_flashdata('password_reset', 'failed');
		}
	}
	
	function do_email($msg=NULL, $sub=NULL, $to=NULL, $from=NULL)
	{
		
		$config = array();
        
		
		$config['useragent']	= "CodeIgniter";
        $config['mailpath']		= "/usr/sbin/sendmail"; // or "/usr/sbin/sendmail" ///usr/bin/sendmail
        $config['protocol']		= "smtp";
        $config['smtp_host']	= "localhost";
        $config['smtp_port']	= "25";
        $config['mailtype']		= 'html';
        $config['charset']		= 'utf-8';
        $config['newline']		= "\r\n";
        $config['wordwrap']		= TRUE;

      
		$config['protocol']    = 'smtp';
        $config['smtp_host']    = 'gcam1175.siteground.biz';
        $config['smtp_port']    = '587';
        
		
		
        $config['smtp_user']    = 'verification@happywatch99.com';
        $config['smtp_pass']    = 'CeceKong86';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      

		
		
		
		$this->load->library('email');

        $this->email->initialize($config);

		$site_name	=	$this->db->get_where('settings' , array('type' => 'site_name'))->row()->description;
		if($from == NULL)
			$from		=	$this->db->get_where('settings' , array('type' => 'site_email'))->row()->description;
		
		$this->email->from($from, $site_name);
		$this->email->to($to);
		$this->email->subject($sub);
		
		
		$this->email->message($msg);
		
		$this->email->send();
		
		
		 //$this->email->print_debugger();
		//exit;
		return true;
	
	}
	
	function do_contactemail($content=NULL, $subject=NULL, $userEmail=NULL)
	{
		$to="shailendrasoftindigo@gmail.com";
		$config = array();
        $config['useragent']	= "CodeIgniter";
        $config['mailpath']		= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']		= "smtp";
        $config['smtp_host']	= "localhost";
        $config['smtp_port']	= "25";
        $config['mailtype']		= 'html';
        $config['charset']		= 'utf-8';
        $config['newline']		= "\r\n";
        $config['wordwrap']		= TRUE;

        $this->load->library('email');

        $this->email->initialize($config);

		$this->email->from($userEmail);
		$this->email->to($to);
		$this->email->subject($subject);
		
		
		$this->email->message($content);
		
		$this->email->send();
		
		//echo $this->email->print_debugger();
	}
}
