<?php

class Login extends MY_Controller {

	function Login()
	{
		parent::MY_Controller();
		$this->load->helper('string');
		$this->load->library('encrypt');
		$this->lang->load('mod_login/login','bahasa');
		
		$data['login_username'] = $this->lang->line('login_label_user');
		$data['login_password'] = $this->lang->line('login_label_pass');
		$data['login_submit'] = $this->lang->line('login_button_submit');
		$data['login_reset'] = $this->lang->line('login_button_reset');
		
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/form/login_form.css',
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/core/jquery-1.3.2.js',
		'asset/javascript/jQuery/ui/jquery-ui-1.7.2.custom.js',
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
		$this->load->vars($data);
	}

	// --------------------------------------------------------------------

	function index()
	{
		$data['extraHeadContent2'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_id').focus();
					})
				</script>\n";
				
		$usr_id = $this->input->post('usr_id');
		$login_number = $this->session->userdata('login_number');

		if (isset($usr_id) && $usr_id != '' && $login_number != 1)
		{
			$this->site_sentry->login_routine1();
		}
		else if($login_number == 1) {
			$this->site_sentry->login_routine2();
		}
		else
		{
			
			$data['login_msg'] = $this->lang->line('login_msg_1');
			$data['user_val']="";
			$data['user_readonly']="";
			$this->load->view('login/index', $data);
		}
	}

	// --------------------------------------------------------------------
	function second_login()
	{
			$usr_login = $this->session->userdata('usr_login');
			$data['extraHeadContent2'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_pwd').focus();
					})
				</script>\n";
			
			$data['login_msg'] = $this->lang->line('login_msg_3');
			$data['user_val']= $usr_login;
			$data['user_readonly']="readonly";
			$this->load->view('login/index', $data);
	}
	
	function login_fail()
	{
			$data['extraHeadContent2'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_id').focus();
					})
				</script>\n";
			
			$data['login_msg'] = $this->lang->line('login_msg_2');
			$data['user_val']="";
			$data['user_readonly']="";
			$this->load->view('login/index', $data);
	}
	
	function log_out()
	{
		$this->site_sentry->log_out();
	}

	// --------------------------------------------------------------------
}
 
?>