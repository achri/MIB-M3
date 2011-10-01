<?php
class Report_tracking extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,
	
	$cari_status,$cari_bulan,$cari_tahun,$cari_no_pr,$ppn_status;

	function report_tracking () {
		parent::MY_Controller();
		
//		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->model(array('tbl_user'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/penelusuran_proses/laporan','bahasa');
		
		
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

		// inisialisasi
		self::$cari_status='';
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		self::$cari_no_pr='';
		self::$ppn_status = '';
		// akhir inisisalisasi
		
		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_status"))
			self::$cari_status  = $this->input->post("cari_status");
		if ($this->input->post("cari_no_pr"))
			self::$cari_no_pr  = $this->input->post("cari_no_pr");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_tracking';
		self::$link_view = 'purchase/mod_report/tracking_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;		
		
		// ============= akhir link untuk manggil viewnya ===================

		// ================== untuk pilihan PPN / Non PPN =====================		
			if ($this->config->item('m3_ppn') == 'PPN')
				self::$ppn_status = 'ppn_';
		// ================== untuk pilihan PPN / Non PPN =====================

		$data['title_page'] = $this->lang->line('lap_judul');
		$data['ppn_status'] = self::$ppn_status;
		$this->load->vars($data);



	}

	function index()
	{
			
		if (self::$cari_status !=''){
			$this->rep_tracking('index');
		}else {
			$this->bwt_view('index');
		}
	
	}

	function excel()
	{
		$this->rep_tracking('excel');
	}
	
	function bwt_view($menu){
		

	// =============  untuk pengiriman daTa ke view ==============================
		$data["cari_bulan"] = self::$cari_bulan;
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');

		$data["cari_tahun"] = self::$cari_tahun;
		$data["data_tahun"] =$this->general->combo_box('tahun');
		
		$data["cari_no_pr"] = self::$cari_no_pr;		
		$data["cari_status"] = self::$cari_status;
	// ============= akhir untuk pengiriman daTa ke view ==============================
		
		
		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/main';
			$this->load->view('index',$data);
		}else if ($menu == 'excel'){
			$this->load->view(self::$link_view.'/excel',$data);
		}

	}
	
	function rep_tracking($menu){
		// inisialisasi
		$cari_status=self::$cari_status;
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$cari_no_pr=self::$cari_no_pr;

		// ============= query gedenya ===========================
		
		$sql  = "select pr.pr_no, pr.pr_id 
				   from prc_pr as pr 
				   where pr.pr_status = '1'";
		
		if ($cari_no_pr != '')
			$sql .= " and pr.pr_no LIKE '%$cari_no_pr%' ";
		if ($cari_tahun !=0 )
			$sql .= " and year(pr.pr_date) = '$cari_tahun'";
		if ($cari_bulan !=0 )
			$sql .= " and month(pr.pr_date) = '$cari_bulan'";
		
			
		$sql .= " order by pr.pr_no asc";
		
		$data_pr=$this->db->query($sql); // eksekusi query
		//foreach ($data_pr->result() as $row)
		
		
	// ============= akhir query gedenya ===========================
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data
		$data['data_penelusuran']=$this->db->query($sql); // eksekusi query
		
		
		
		$this->load->vars($data);// ngrirm data dari fungsi ini sendiri
		$this->bwt_view($menu);//manggil fungsi bwt ngirim datanya
	}
	
	


}


?>