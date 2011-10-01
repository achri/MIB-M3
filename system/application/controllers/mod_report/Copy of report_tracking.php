<?php
class Report_tracking extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,$search_status_excel,$search_month_excel,$search_year_excel;

	function report_tracking () {
		parent::MY_Controller();
		
	//	$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('session','pagina_lib'));
		$this->config->load('tables');
		
	// ================== include (manggil) css & js (pake array) ====================

		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/form/jquery.form.js'
		);
		
		$data['extraHeadContent'] = '';
		
		// manggil css
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		
		//manggil js
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
	// ================== akhir include (manggil) css & js (pake array) ====================
		
		
		// ============= untuk session id masuk nya ===================
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		// ============= akhir untuk session id masuk nya ===================


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_tracking';
		self::$link_view = 'purchase/mod_report/tracking_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		// ============= akhir link untuk manggil viewnya ===================


		$data['title_page'] = $this->lang->line('lap_judul');
		
		$this->load->vars($data);



	}

	function index(){
		echo 'dari contro;lllll';
		
		$data["content"] = self::$link_view.'/main';
		$this->load->view('index',$data);

	}
	


}


?>