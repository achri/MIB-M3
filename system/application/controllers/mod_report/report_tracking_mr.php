<?php
class Report_tracking_mr extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,
	
	$cari_status,$cari_bulan,$cari_tahun,$cari_no_mr,$i,$arr_pro;

	function Report_tracking_mr () {
		parent::MY_Controller();
		
//		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->model(array('tbl_user'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/penelusuran_proses_mr/laporan','bahasa');
		
		
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
		self::$cari_no_mr='';
		// akhir inisisalisasi
		
		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_status"))
			self::$cari_status  = $this->input->post("cari_status");
		if ($this->input->post("cari_no_mr"))
			self::$cari_no_mr  = $this->input->post("cari_no_mr");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_tracking_mr';
		self::$link_view = 'purchase/mod_report/tracking_mr_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		
		// ============= akhir link untuk manggil viewnya ===================


		$data['title_page'] = $this->lang->line('lap_judul');
		
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
		
		$data["cari_no_mr"] = self::$cari_no_mr;		
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
		$cari_no_mr=self::$cari_no_mr;
		$arr_mr  = array();
		self::$i = 0;
		self::$arr_pro = array();
		// ============= query gedenya ===========================
		
		$sql  = "select mr.mr_no, mr.mr_id 
				   from prc_mr as mr 
				   where mr.mr_status = '1'";
		
		if ($cari_no_mr != '')
			$sql .= " and mr.mr_no LIKE '%$cari_no_mr%' ";
		if ($cari_tahun !=0 )
			$sql .= " and year(mr.mr_date) = '$cari_tahun'";
		if ($cari_bulan !=0 )
			$sql .= " and month(mr.mr_date) = '$cari_bulan'";
		
			
		$sql .= " order by mr.mr_no asc";
		
		$data_mr=$this->db->query($sql); // eksekusi query
		foreach ($data_mr->result() as $arr)
		{
			$arr_mr[self::$i]['mr_no'] = $arr->mr_no;
			$this->produk($arr->mr_id);
			self::$i++;
		}
		
	// ============= akhir query gedenya ===========================
		$data['pro']				= self::$arr_pro;
		$data['mr_no']				= $arr_mr;
		$data["jumlah_data"] 		= $this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data
		$data['data_penelusuran']	= $this->db->query($sql); // eksekusi query
		
		
		
		$this->load->vars($data);// ngrirm data dari fungsi ini sendiri
		$this->bwt_view($menu);//manggil fungsi bwt ngirim datanya
	}
	
	// fungsi untuk melakukan 
	function produk($mr_id)
	{
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$j = 0;
		$sql_pro  = "SELECT distinct mr.mr_id, mr.mr_status, pro.pro_name, mrd.requestStat as status_acc,
							grl.grl_printStat as cetak_form, grl.grl_releaseUser as status_keluar_barang,
							mrd.grl_realisasi as realisasi_barang, mrd.grl_description as alasan_jumlah_barang,
							mrd.is_closed, mrd.qty_use, mrd.note, pro.um_id as satuan_id, sat.satuan_name as nama_satuan,
							sup.sup_name, ml.legal_name, mrd.description, mrdh.mr_usr_note as ket_persetujuan							
					FROM prc_mr as mr
					LEFT JOIN prc_mr_detail as mrd 
					  ON mrd.mr_id = mr.mr_id
					LEFT JOIN prc_mr_detail_history as mrdh 
					  ON mrdh.grl_id = mrd.grl_id
					LEFT JOIN prc_master_product as pro
					  ON pro.pro_id = mrd.pro_id
					LEFT JOIN prc_good_release as grl
					  ON grl.mr_id = mr.mr_id
					LEFT JOIN prc_master_satuan as sat
					  ON sat.satuan_id = mrd.um_id
					LEFT JOIN prc_master_supplier as sup
					  ON sup.sup_id = mrd.sup_id
					LEFT JOIN prc_master_legality as ml
					  ON ml.legal_id = sup.legal_id
					WHERE mr.mr_id = '$mr_id'";
			
		if($cari_tahun != 0)
			$sql_pro .= " and year(mr.mr_date) = '$cari_tahun' ";
		if($cari_bulan != 0)
			$sql_pro .= " and month(mr.mr_date) = '$cari_bulan' ";
			
		$sql_pro .= " ORDER BY pro.pro_name ASC";
		$rs_po = $this->db->query($sql_pro);
		foreach($rs_po->result() as $arr) {
			self::$arr_pro[self::$i][$j]['pro_name']				= $arr->pro_name;
			self::$arr_pro[self::$i][$j]['status_acc']				= $arr->status_acc;
			self::$arr_pro[self::$i][$j]['cetak_form']				= $arr->cetak_form;
			self::$arr_pro[self::$i][$j]['status_keluar_barang']	= $arr->status_keluar_barang;
			self::$arr_pro[self::$i][$j]['realisasi_barang']		= $arr->realisasi_barang;
			self::$arr_pro[self::$i][$j]['alasan_jumlah_barang']	= $arr->alasan_jumlah_barang;
			self::$arr_pro[self::$i][$j]['is_closed']				= $arr->is_closed;
			self::$arr_pro[self::$i][$j]['qty_use']					= $arr->qty_use;
			self::$arr_pro[self::$i][$j]['note']					= $arr->note;
			self::$arr_pro[self::$i][$j]['satuan_id']				= $arr->satuan_id;
			self::$arr_pro[self::$i][$j]['nama_satuan']				= $arr->nama_satuan;
			self::$arr_pro[self::$i][$j]['nama_pemasok']			= $arr->sup_name;
			self::$arr_pro[self::$i][$j]['legalitas_pemasok']		= $arr->legal_name;
			self::$arr_pro[self::$i][$j]['ket_keperluan']			= $arr->description;			
			self::$arr_pro[self::$i][$j]['ket_status_acc_mr']		= $this->general->status('acc_pr',$arr->status_acc); // untuk status acc_mr
			self::$arr_pro[self::$i][$j]['ket_persetujuan']			= $arr->ket_persetujuan;			
			$j++;			
		}

		
		
		$data["jumlah_data_produk"] 		= $this->general->hitung_banyak_data($sql_pro);  // bwt ngitung banyak nya data
		$this->load->vars($data);// ngrirm data dari fungsi ini sendiri		
	}
	


}


?>