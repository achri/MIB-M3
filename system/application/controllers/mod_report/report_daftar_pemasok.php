<?php
class Report_daftar_pemasok extends MY_Controller {
	private static $link_controller, $link_view, $user_id,
	
	$cari_status,$cari_kategori,$cari_pemasok

	;
	function Report_daftar_pemasok() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library('pro_code');
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('label','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/daftar_pemasok/laporan','bahasa');
				
		$arrayCSS = array (
		'asset/css/themes/start/ui.all.css',
		'asset/css/print_templates.css',
		'asset/css/table/DataView.css'
		);
		
		$arrayJS = array (
		'asset/javascript/jQuery/form/jquery.form.js'
		);
		
		$data['extraHeadContent'] = '';
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
		
	// =========== inisialisasi ========
		self::$cari_status='';
		
		self::$cari_kategori='';			
		self::$cari_pemasok='';					
		
	// =========== (akhir) inisialisasi ========
		
		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_kategori"))
			self::$cari_kategori= $this->input->post("cari_kategori");
		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok= $this->input->post("cari_pemasok");							
		

		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");		
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======
		
		
		self::$link_controller = 'mod_report/report_daftar_pemasok';
		self::$link_view = 'purchase/mod_report/daftar_pemasok_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['title_page'] = $this->lang->line('lap_judul');
		
		$this->load->vars($data);
	}
	
	function index() {	
		
		if (self::$cari_status != ''){
			$this->get_report('index');
		} else {		
			$this->bwt_view('index');
		}		
		
	}
	
	// bwt export ke excel
	function excel() {
		$this->get_report('excel');
	}
	
	
	// untuk lihat viewnya
	function bwt_view($menu)
	{
	
		$data['cari_status']=self::$cari_status;	
		
		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_pemasok']=self::$cari_pemasok;		
		
		$data["data_cari_pemasok"] =$this->general->combo_box('pemasok');
		$data["data_kategori"] =$this->general->combo_box('kategori');
	
		if($menu == 'index')
		{
			$data["content"] = self::$link_view.'/main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel')
		{
			$this->load->view(self::$link_view.'/excel',$data);
		}
	}
	
	function get_report($menu) {
		// ============ inisialisasi variabel =============		
		$cari_kategori =self::$cari_kategori;		
		$cari_pemasok =self::$cari_pemasok;
		
		// ========= (akhir) inisialisasi variabel ============
		
	// =================== query  =============================
		$sql = "SELECT supcat.sup_id, supcat.cat_id, cat.cat_code, sup.sup_name, cat.cat_name  FROM `prc_master_supplier_category` as supcat 
				inner join prc_master_category as cat on cat.cat_id = supcat.cat_id
				inner join prc_master_supplier as sup on sup.sup_id = supcat.sup_id
				where 1=1 ";
		
		// untuk filternya
		if ($cari_kategori != '')
			$sql .= " and cat.cat_id = '$cari_kategori' ";
		if ($cari_pemasok != '')
			$sql .= " and sup.sup_id = '$cari_pemasok' ";
			
		
		$sql .= " ORDER BY sup.sup_name";
				
		$data_pemasok = $this->db->query($sql);
	// =================== (akhir) query  =============================		
	
	
	// =========== bwt pagingnya ================= (non aktif)
	//	$limit = $this->config->item('limit_report');
	//	$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$cari_bulan.'/'.	$cari_tahun,$sql,$limit,$uri_segment = 6);
	// ============= akhir pagingnya ===========
	
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
		$data["data_pemasok"] = $data_pemasok; //tanpa pagiong
		
		$data['no_pos'] = 0;
		
	//	$data['data_pcv']= $paging['result']; // pake paging
		//$data['no_pos'] = $paging['pagina_pos']; // atrib paging
	
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view				
	}

}
?>