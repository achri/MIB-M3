<?php
class Report_penerimaan_summary extends MY_Controller {
		//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,$arr_month_name,
	
		// =========== inisialisasi variabel bwt filter baru ========
		$gr_filter, $this_year,$search_month, $search_supplier, 
		$search_cat,$tot_akhir_rp, $tot_akhir_dol,$arr_year,$search_year,
		
		$cari_bulan,$cari_tahun, $cari_no_bpb,$cari_status,$cari_no_po,$cari_pemasok,
		$cari_no_sj
		
		 ;
		
	// ========== akhir inisialisasi bwt filter ======	


	function report_penerimaan_summary () {
		parent::MY_Controller();
		
//		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->model(array('tbl_user','tbl_supplier','tbl_category','tbl_hutang'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		

	// =========== untuk bahasa ========================================		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');

		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/penerimaan_barang_summary/laporan','bahasa');
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
		
		
		// ============= untuk session id masuk nya ===================
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		// ============= akhir untuk session id masuk nya ===================

		// =========== inisialisasi ========
			self::$cari_status = 0; 	
			self::$cari_bulan = 0; 
			self::$cari_tahun = 0; 			
			self::$cari_pemasok = 0; 		
			self::$cari_no_bpb =''; 	
			self::$cari_no_po =''; 	
			self::$cari_no_sj='';
//			self::$this_year =date("Y");	
				
			self::$tot_akhir_rp = 0; 		
			self::$tot_akhir_dol = 0; 		
			
	
		// ========== akhir inisialisasi ======

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok  = $this->input->post("cari_pemasok");
		if ($this->input->post("cari_no_bpb"))
			self::$cari_no_bpb  = $this->input->post("cari_no_bpb");
		if ($this->input->post("cari_no_po"))
			self::$cari_no_po  = $this->input->post("cari_no_po");
		if ($this->input->post("cari_no_sj"))
			self::$cari_no_sj  = $this->input->post("cari_no_sj");
			

		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");


		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_penerimaan_summary';
		self::$link_view = 'purchase/mod_report/penerimaan_summary_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;		
		// ============= akhir link untuk manggil viewnya ===================


		$data['title_page'] = $this->lang->line("lap_judul");	
		
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
	
	function bwt_view($menu)
	{
	
		$data['cari_status']=self::$cari_status;
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;


		$data['cari_no_bpb']=self::$cari_no_bpb;
		$data['cari_no_po']=self::$cari_no_po;
		$data['cari_pemasok']=self::$cari_pemasok;
		$data['cari_no_sj']=self::$cari_no_sj;
		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		if($menu == 'index')
		{
			$data["content"] = self::$link_view.'/main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel')
		{
			$this->load->view(self::$link_view.'/excel',$data);
		}
	}



	function get_report($menu){
	// inisialisasi data
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$cari_pemasok=self::$cari_pemasok;

		$cari_no_po=self::$cari_no_po;
		$cari_no_bpb=self::$cari_no_bpb;
		$cari_no_sj=self::$cari_no_sj;


		$tot_akhir_rp=self::$tot_akhir_rp;
		$tot_akhir_dol=self::$tot_akhir_dol;
		$this_year=self::$search_year;
	//	$gr_filter=self::$gr_filter;

			$str_thn			= self::$search_year;
			$bln				= date("n");
			//$str_bln			= date("m");
			$str_bln			=  str_pad(self::$search_month, 2, "0", STR_PAD_LEFT);
			
			$gr_filter	= $str_thn.$str_bln;


		// ============= query gedenya ===========================

		$sql	= "select g.gr_no, g.gr_suratJalan, s.sup_name, 
				   p.po_no, 
				   (select sum(gd.qty * gd.price) from prc_gr_detail as gd 
					where gd.gr_id = g.gr_id and cur_id=1) as tot_rp,
				   (select sum(gd.qty * gd.price) from prc_gr_detail as gd 
					where gd.gr_id = g.gr_id and cur_id=2) as tot_dol
				   from prc_gr as g
				   inner join prc_po as p on p.po_id = g.po_id
				   left join prc_master_supplier as s on p.sup_id = s.sup_id
				   where g.gr_type = 'rec'";
		if($cari_tahun != 0)
				$sql .= " and year(g.gr_date) = '$cari_tahun'";
		if($cari_bulan != 0)
				$sql .= " and month(g.gr_date) = '$cari_bulan'";
		if($cari_pemasok != 0)
			$sql .= " and s.sup_id = $cari_pemasok ";
		
		if($cari_no_bpb!= 0)
				$sql .= " and g.gr_no like '%$cari_no_bpb%'";
		if($cari_no_po != 0)
				$sql .= " and p.po_no like '%$cari_no_po%'";
		if($cari_no_sj != 0)
				$sql .= " and g.gr_suratJalan like '%$cari_no_sj%'";
			
		
		$sql .= " order by g.gr_no";

			
		$penerimaan=$this->db->query($sql); // EKSEKUSI QUERY
	
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
	
		
			foreach($penerimaan->result() as $row){
				$tot_akhir_rp=$tot_akhir_rp+($row->tot_rp);
				$tot_akhir_dol=$tot_akhir_dol+($row->tot_dol);							
			}
		
		
		// =========== bwt pagingnya =================
	//	$limit = $this->config->item('limit_report');
	//	$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$search_month.'/'.	$search_supplier,$sql,$limit,$uri_segment = 6);
		// ============= akhir pagingnya ===========
		
		
		
		// ================= hitung total RP ================
/*
			$sql = "select cur_id, sum(awal) as tot_awal_dol, sum(beli) as tot_beli_dol, sum(bayar) as tot_bayar_dol
				from prc_hutang_bulanan where cur_id=2";
		if($search_month != 0)
			$sql .= " and bln_pos = '$search_month' and thn_pos='$this_year'";
		if($search_supplier != 0)
			$sql .= " and sup_id = $search_supplier ";
		if($search_cat != 0)
			$sql .= " and sup_id in (select sup_id from prc_master_supplier_category where cat_id=$search_cat) ";
		$sql .= " group by cur_id";
	
		$list_hutang_tot_dol=$this->db->query($sql); // EKSEKUSI QUERY
		
		if ($list_hutang_tot_dol->num_rows () > 0)
		{
		
			$row_dol=$list_hutang_tot_dol->row();
			$akhir_tot_dol=($row_dol->tot_awal_dol)+($row_dol->tot_beli_dol)-($row_dol->tot_bayar_dol);
		}
*/
		// ================= akhir hitung total rp ================
	// ============= akhir query gedenya ===========================
		
	// ============= untuk pengiriman daTa ke view ==============================
		$data["data_penerimaan_tot_rp"]=$tot_akhir_rp;
		$data["data_penerimaan_tot_dol"]=$tot_akhir_dol;

		$data["data_penerimaan"]=$penerimaan; // data tanpa paging
		$data['no_pos'] = 0; 
		
		//$data['data_penerimaan']= $paging['result']; // pake paging
	//	$data['no_pos'] = $paging['pagina_pos']; // atrib paging		


	// ============= akhir untuk pengiriman daTa ke view ==============================
		
		
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view		

	}
	


}


?>