<?php
class Report_pembayaran_ke_pemasok extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,
	
	// =========== inisialisasi variabel bwt filter baru ========
		$arr_month_name,$gr_filter, $this_year, 
		$search_cat,$tot_akhir_rp, $tot_akhir_dol,$cari,$cari_status,
		$cari_tahun,$cari_bulan,$cari_pemasok, $cari_no_bkbk,$ppn_status
		 ;
		
	// ========== akhir inisialisasi bwt filter ======	


	function report_pembayaran_ke_pemasok () {
		parent::MY_Controller();
		
//		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->model(array('tbl_user','tbl_supplier','tbl_category','tbl_hutang'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
			// =========== untuk bahasa ========================================		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');

		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/pembayaran_barang/laporan','bahasa');
	// =========== (akhir) untuk bahasa ========================================

		
		
		
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

		// =========== inisialisasi ========
			self::$cari_tahun = 0; 
			self::$cari_bulan = 0; 

			self::$cari_pemasok = 0; 		
			self::$cari_no_bkbk = ''; 		

			self::$search_cat =0; 	
			self::$this_year =date("Y");	

			self::$cari_status = 0; 	
			self::$ppn_status = '';
				
			self::$tot_akhir_rp = 0; 		
			self::$tot_akhir_dol = 0; 		
			
		self::$arr_month_name = $this->lang->line('combo_box_array_bulan');
		// ========== akhir inisialisasi ======

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok  = $this->input->post("cari_pemasok");
		if ($this->input->post("cari_no_bkbk"))
			self::$cari_no_bkbk  = $this->input->post("cari_no_bkbk");

		if ($this->input->post("search_cat"))
			self::$search_cat  = $this->input->post("search_cat");



		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");

		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======

		// ================== untuk pilihan PPN / Non PPN =====================		
			if ($this->config->item('m3_ppn') == 'PPN')
				self::$ppn_status = 'ppn_';
		// ================== untuk pilihan PPN / Non PPN =====================
		
		// ============= untuk session id masuk nya ===================
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		// ============= akhir untuk session id masuk nya ===================


		// ============= link untuk manggil viewnya ===================
		
		self::$link_controller = 'mod_report/report_pembayaran_ke_pemasok';
		if (self::$ppn_status == 'ppn_'){			
			self::$link_view = 'purchase/mod_report/pembayaran_ke_pemasok_rep/ppn';
		} else {			
			self::$link_view = 'purchase/mod_report/pembayaran_ke_pemasok_rep';
		}
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		
		// ============= akhir link untuk manggil viewnya ===================


		$data['title_page'] = $this->lang->line("lap_judul");	
		
		$this->load->vars($data);

	}

	function index()
	{
	
		if (self::$cari_status !=''){
			$this->get_report('index');
		}else {
			$this->bwt_view('index');
		}
		
		
	}	
	
	function excel()
	{
		$this->get_report('excel');
	}	
	
		
	function bwt_view($menu)
	{
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;
		$data['cari_no_bkbk']=self::$cari_no_bkbk;		

//		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_pemasok']=self::$cari_pemasok;

		$data['cari_status']=self::$cari_status;
		
		// untuk array combo box nya
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_kategori"] =$this->general->combo_box('kategori');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/main';
			$this->load->view('index',$data);
		} else 	if ($menu == 'excel'){
			$this->load->view( self::$link_view.'/excel',$data);
		}
	
	}
	
	function get_report($menu){
		// inisialisasinya
			$cari_tahun			=self::$cari_tahun;
			$cari_bulan			=self::$cari_bulan;			
			$cari_pemasok 		=self::$cari_pemasok;
			$cari_no_bkbk 		=self::$cari_no_bkbk;


		// ============= query gedenya ===========================

			$sql = "select b.bkbk_no, sup.sup_name, date_format(b.bkbk_date,'%d-%m-%Y') as bkbk_date,
			(select sum(bd.con_dibayar) from prc_bkbk_detail as bd 
			 where bd.bkbk_id = b.bkbk_id and cur_id=1) as pay_rp,
			(select sum(bd.con_dibayar) from prc_bkbk_detail as bd 
			 where bd.bkbk_id = b.bkbk_id and cur_id=2) as pay_dol,
			 ml.legal_name
			from prc_bkbk as b 
			inner join prc_master_supplier as sup on b.sup_id = sup.sup_id
			left join prc_master_legality as ml on ml.legal_id = sup.legal_id
			where 1=1";
			if($cari_bulan != 0)
				$sql .= " and month(b.bkbk_date) = '$cari_bulan' ";
			if($cari_tahun != 0)
				$sql .= " and year(b.bkbk_date) = '$cari_tahun'";
			if($cari_pemasok != 0)
				$sql .= " and sup.sup_id = '$cari_pemasok'";
			if($cari_no_bkbk != '')
				$sql .= " and b.bkbk_no LIKE '%$cari_no_bkbk%'";
				
//				$sql .= " and month(b.bkbk_date) = '$search_month' and year(b.bkbk_date)='$this_year'";
				
				
			$sql .= " order by b.bkbk_date";
		
		$pembayaran=$this->db->query($sql); // EKSEKUSI QUERY

	// =========== bwt pagingnya =================
		$limit = $this->config->item('limit_report');
	//	$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$search_month,$sql,$limit,$uri_segment = 5);
		// ============= akhir pagingnya ===========
		
		// yg pagingnya
		//$data['data_pembayaran']= $paging['result'];
		//$data['no_pos'] = $paging['pagina_pos'];
		
		
		// ==================== hitung saldo akhir ======================
			$tot_rp=0;
			$tot_dol=0;
		
			foreach ( $pembayaran->result() as $row) {
					$tot_rp=$tot_rp + ($row->pay_rp);
					$tot_dol=$tot_dol + ($row->pay_dol);			
			}
			
		// ==================== (akhir) hitung saldo akhir ======================

		
		
	// ============= akhir query gedenya ===========================

		$data['data_pembayaran']= $pembayaran;
		$data['no_pos'] = 0;

		// untuk jumlah total bayar 
			$data["tot_rp"]=$tot_rp;
			$data["tot_dol"]=$tot_dol;

		$data['jumlah_data']=$this->general->hitung_banyak_data($sql); //hitung jumlah data
			
		$this->load->vars($data); // untuk ngirim data ke view nya	
		$this->bwt_view($menu); // untuk ngirim data ke view nya

	}
	

}


?>