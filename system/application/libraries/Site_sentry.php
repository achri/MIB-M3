<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Site Sentry security library for Code Igniter applications
* Author: James Nicol, Glossopteris Web Designs & Development, www.glossopteris.com, April 2006
*
*/

class Site_sentry 
{

	function Site_sentry()
	{
		$this->obj =& get_instance();
	}

	function is_logged_in()
	{
		if ($this->obj->session) {

			//If user has valid session, and such is logged in
			if ($this->obj->session->userdata('logged_in'))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}

		}
		else
		{
			return FALSE;
		}
		
	} 

	function login_routine1()
	{
		$login_result = FALSE;
		
		//Make the input username and password into variables
		$password = md5($this->obj->input->post('usr_pwd'));
		$userid = $this->obj->input->post('usr_id');
		
		$array = array('usr_login' => $userid, 'usr_pwd1' => $password);
		$this->obj->db->where($array); 
		$this->obj->db->from('prc_sys_user');
		$login_result = $this->obj->db->count_all_results();
		
		//If username and password match set the logged in flag in 'ci_sessions'
		if ($login_result==1)
		{
			$credentials = array('usr_login' => $userid, 'login_number'=> '1');
			$this->obj->session->set_userdata($credentials);
			//On success redirect user to default page
			redirect('login/second_login/','location');
		}
		else
		{
			//On error send user back to login page, and add error message
			redirect('login/login_fail/');
		}
	}
	
	function login_routine2()
	{
		$password = md5($this->obj->input->post('usr_pwd'));
		$userid = $this->obj->session->userdata('usr_login');
		$sess_id = $this->obj->session->userdata('session_id');
		
		$array = array('usr_login' => $userid, 'usr_pwd2' => $password);
		$this->obj->db->where($array); 
		$query = $this->obj->db->get('prc_sys_user');
		
		//echo $query->row()->usr_id.' '.$query->row()->ucat_id;
			
		
		//If username and password match set the logged in flag in 'ci_sessions'
		if ($query->num_rows() > 0)
		{
			//GET IP
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])):
				$ips = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else:
				$ips = $_SERVER['REMOTE_ADDR'];
			endif;
			
			$row = $query->row();
			$usr_id = $row->usr_id;
			$ucat_id = $row->ucat_id;
			$credentials = array('login_number'=> '0', 'logged_in'=> '1','usr_id'=>$usr_id,'ucat_id'=>$ucat_id,'sess_prmr_no'=>$sess_id);
			$this->obj->session->set_userdata($credentials);
			
			// SAVE LOGGING USER
			$sql    = "select newTime_log, newIP_log from prc_sys_user where usr_id='$usr_id'";
			$rs_log = $this->obj->db->query($sql);
			$lastTime_log = $rs_log->row()->newTime_log;
			if($lastTime_log=='')
				$lastTime_log = date('Y-m-d H:i:s');
			$lastIP_log   = $rs_log->row()->newIP_log;
			if($lastIP_log=='')
				$lastIP_log = $ips;
			$newIP		   = $ips;
			$sql		   = "update prc_sys_user set lastTime_log='$lastTime_log', lastIP_log='$lastIP_log', 
						  newTime_log=NOW(), newIP_log='$newIP' where usr_id='$usr_id'";
			if ($this->obj->db->query($sql)):
			
				//On success redirect user to default page
				//if ($nav = $this->obj->session->userdata('usr_nav')):
				//	redirect($nav);
				//else:
					redirect('','location');
				//endif;
			endif;
		}
		else
		{
			$array_items = array('usr_login' => '', 'login_number'=> '');
			$this->obj->session->unset_userdata($array_items);
			redirect('login/login_fail/');
		}
		
	}
	
	function log_out(){
		$usr_id = $this->obj->session->userdata('usr_id');
		
		//GET IP
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])):
			$ips = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else:
			$ips = $_SERVER['REMOTE_ADDR'];
		endif;
			
		$session['usr_login'] = '';
		$session['login_number'] = '';
		$session['logged_in'] = '';
		$session['ucat_id'] = '';
		$session['sess_prmr_no'] = '';
		$session['usr_id'] = '';
		$session['client_name'] = '';
		$session['client_image'] = '';
		$session['module_program'] = '';
		$session['module_type'] = '';
		$session['module_package'] = '';
		$session['module_version'] = '';
		$session['module_revision'] = '';
		$this->obj->session->unset_userdata($session);
		
		// SAVE LOGOUT USER
		$newOffTime_log = date('Y-m-d H:i:s');
		$newOffIP_log   = $ips;
		$sql = "update prc_sys_user set offTime_log='$newOffTime_log', offIP_log='$newOffIP_log'  
		where usr_id='$usr_id'";
		if ($this->obj->db->query($sql)):
			redirect('','location');
		endif;
	}

}
?>
