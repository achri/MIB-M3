<?php
class report_inv extends MY_Controller {
	private static $link_controller, $link_view, $user_id, $arr_pro, $arr_inv, $i,
	
	// variabel global filter
		$cari_tahun,$cari_bulan,$cari_kategori,$cari_kelompok,
		$cari_kode_barang,$cari_nama_barang,$cari_status,$tes;	

	
	
	function report_inv() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('pro_code','general'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/persediaan_barang/laporan','bahasa');
		
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
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
	// ================== akhir include (manggil) css & js (pake array) ====================


	// =========== inisialisasi ========
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		self::$cari_kategori=0;
		self::$cari_kode_barang='';
		self::$cari_nama_barang='';
		self::$cari_kelompok='';

		self::$cari_status='';
	// =========== akhir inisialisasi ========

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");
		if ($this->input->post("cari_kategori"))
			self::$cari_kategori  = $this->input->post("cari_kategori");
		if ($this->input->post("cari_kode_barang"))
			self::$cari_kode_barang  = $this->input->post("cari_kode_barang");
		if ($this->input->post("cari_nama_barang"))
			self::$cari_nama_barang  = $this->input->post("cari_nama_barang");
		if ($this->input->post("cari_kelompok"))
			self::$cari_kelompok  = $this->input->post("cari_kelompok");
			
		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======


		


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_inv';
		self::$link_view = 'purchase/mod_report/inv_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		// ============= akhir link untuk manggil viewnya ===================
		$data['title_page'] = $this->lang->line('lap_judul');
			
		$this->load->vars($data);
	}
	
	
	function index() {
		
		if (self::$cari_status !=''){
			$this->get_report('index');
		}else {
			$this->bwt_view();
		}
		
	}
	
	function excel() {
			
			$this->get_report('excel');
	}

	
	function bwt_view(){
		// =================== ngirim data ke view ==================
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;
		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_kode_barang']=self::$cari_kode_barang;
		$data['cari_nama_barang']=self::$cari_nama_barang;
		$data['cari_kelompok']=self::$cari_kelompok;
		
		$data['cari_status']=self::$cari_status;
		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_kategori"] =$this->general->combo_box('kategori');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		// =================== ngirim data ke view ==================		
		$data["content"] = self::$link_view.'/report_inv_main';
		//$data["content"] = self::$link_view.'/test';
		$this->load->view('index',$data);
	
	}
		
	function get_report($menu) {
	
		$cari_kategori=self::$cari_kategori;
		
		self::$i = 0;
		
		self::$arr_pro  = array();
		self::$arr_inv  = array();
		
		$arr      = array();
		$arr_cat  = array();
		
		$sql	  = "select cat_id, cat_code, cat_name from prc_master_category where cat_level='1' ";
		// bwt filternya
		if($cari_kategori!= 0)
				$sql .= " and cat_id = '$cari_kategori'";
		
		$sql .= "order by cat_code";
		
		$rs      = $this->db->query($sql);

		$data["jumlah_data_kategori"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data

		foreach($rs->result() as $arr) {
			$arr_cat[self::$i]['cat_name'] = $arr->cat_name;
			$this->get_pro($arr->cat_code);
			self::$i++;
		}
		

		// =================== ngirim data ke view ==================
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;
		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_kode_barang']=self::$cari_kode_barang;
		$data['cari_nama_barang']=self::$cari_nama_barang;
		$data['cari_kelompok']=self::$cari_kelompok;
		
		$data['cari_status']=self::$cari_status;
		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_kategori"] =$this->general->combo_box('kategori');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');
		
		

		// =================== ngirim data ke view ==================		
		$data["cat"] = $arr_cat;
		$data["pro"] = self::$arr_pro;
		$data["stock"] = self::$arr_inv;
		
		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/report_inv_main';
			//$data["content"] = self::$link_view.'/test';
			$this->load->view('index',$data);
		} else if ($menu == 'excel'){
				$this->load->view(self::$link_view.'/report_inv_excel',$data);
			}

	}
	
	
	function get_pro($cat_code) {
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$j = 0;
		$sql = "select p.cat_id, p.pro_id, p.pro_code, p.pro_name, p.is_stockJoin, p.pro_min_reorder, u.satuan_name from prc_master_product as p
				inner join prc_master_satuan as u
					on u.satuan_id = p.um_id
				where pro_code like '$cat_code.%' and p.pro_status='active'";
		if($cari_tahun != 0)
			$sql .= " and year(p.rec_dateEdited) = '$cari_tahun' ";
		if($cari_bulan != 0)
			$sql .= " and month(p.rec_dateEdited) = '$cari_bulan' ";
				
		$sql .= " order by p.pro_code";
		$rs_po = $this->db->query($sql);
		
		foreach($rs_po->result() as $arr) {
			self::$arr_pro[self::$i][$j]['kelas']           = $this->get_kelas($arr->pro_code);
			self::$arr_pro[self::$i][$j]['group']           = $this->get_group($arr->cat_id);
			self::$arr_pro[self::$i][$j]['pro_code']        = $arr->pro_code;
			self::$arr_pro[self::$i][$j]['pro_name']        = $arr->pro_name;
			self::$arr_pro[self::$i][$j]['satuan_name']     = $arr->satuan_name;
			self::$arr_pro[self::$i][$j]['pro_id']          = $arr->pro_id;
			self::$arr_pro[self::$i][$j]['pro_min_reorder'] = $arr->pro_min_reorder;
			self::$arr_pro[self::$i][$j]['is_stockJoin']    = $arr->is_stockJoin;
			self::$this->detail_inv($arr->pro_id,$j);
			$j++;
		}
		$data["jumlah_data_produk"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data
		$this->load->vars($data);
		
	}
	
	function get_kelas($pro_code) {
		$arr_code = explode(".", $pro_code);
		$class_code = $arr_code[0].".".$arr_code[1];
		$sql = "select cat_name from prc_master_category where cat_code='$class_code' and cat_level='2'";
		$rs = $this->db->query($sql)->row();
		return $rs->cat_name;
	}
	
	function get_group($cat_id) {
		$cat_name= '';
		$cari_kelompok=self::$cari_kelompok;
		$sql = "select cat_name from prc_master_category where cat_id='$cat_id' and cat_level='3'";
		if($cari_kelompok != '')
			$sql .= " and cat_name LIKE '%$cari_kelompok%' ";
		
		
		
		// ======== bqt ngecek ada ga nya datanya ===
		if ($this->db->query($sql)->num_rows() > 0){
			$rs = $this->db->query($sql)->row();
			$cat_name=$rs->cat_name;
		}
		return $cat_name;
	}
	
	function detail_inv($pro_id,$j) {
		$k = 0;
		$sql = "select i.inv_end, s.sup_name from prc_inventory as i
				left join prc_master_supplier as s
					on s.sup_id = i.sup_id
				where i.pro_id='$pro_id'";
		$rs_det_inv = $this->db->query($sql);
		
		foreach($rs_det_inv->result() as $arr) {
			self::$arr_inv[self::$i][$j][$k]['sup_name']	= $arr->sup_name;
			self::$arr_inv[self::$i][$j][$k]['inv_end']	= $arr->inv_end;
			
			$k++;
		}
		self::$tes='ugugugug';
		
	}

}
?>