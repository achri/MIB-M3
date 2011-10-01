<?php
class Report_daftar_barang extends MY_Controller {
	private static $link_controller, $link_view, $user_id,
	
	$cari_status,$cari_bulan,$cari_tahun, $cari_status_produk, 
	$cari_kode,$cari_kategori,$cari_kelas,$cari_grup,$cari_produk,$bersihkan

	;
	function Report_daftar_barang() {
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
		$this->lang->load('mod_report/daftar_barang/laporan','bahasa');
				
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
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		
		self::$cari_status_produk='';
		self::$cari_kode='';				
		self::$cari_kategori='';	
		self::$cari_kelas='';				
		self::$cari_grup='';
		self::$cari_produk='';			
		
		self::$bersihkan='';			
	// =========== (akhir) inisialisasi ========
		
		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun  = $this->input->post("cari_tahun");
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan  = $this->input->post("cari_bulan");

		if ($this->input->post("cari_kode")) 
			self::$cari_kode= $this->input->post("cari_kode");							
		if ($this->input->post("cari_kategori"))
			self::$cari_kategori= $this->input->post("cari_kategori");
		if ($this->input->post("cari_kelas"))
			self::$cari_kelas= $this->input->post("cari_kelas");							
		if ($this->input->post("cari_grup"))
			self::$cari_grup= $this->input->post("cari_grup");
		if ($this->input->post("cari_produk"))
			self::$cari_produk= $this->input->post("cari_produk");
		if ($this->input->post("cari_status_produk"))
			self::$cari_status_produk= $this->input->post("cari_status_produk");


		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");
		if ($this->input->post("bersihkan"))
			self::$bersihkan  = $this->input->post("bersihkan");
		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======
		
		
		self::$link_controller = 'mod_report/report_daftar_barang';
		self::$link_view = 'purchase/mod_report/daftar_barang_rep';
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
	
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;

		$data['cari_kode']=self::$cari_kode;
		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_kelas']=self::$cari_kelas;
		$data['cari_grup']=self::$cari_grup;
		$data['cari_produk']=self::$cari_produk;
		$data['cari_status_produk']=self::$cari_status_produk;
		
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_kategori"] =$this->general->combo_box('kategori');
		$data["data_kelas"] =$this->general->combo_box('kelas');
		$data["data_grup"] =$this->general->combo_box('grup');
		
	
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
		$cari_bulan=self::$cari_bulan;
		$cari_tahun=self::$cari_tahun;
		
		$cari_status_produk=self::$cari_status_produk;
		
		$cari_kode=self::$cari_kode;		
		$cari_kategori =self::$cari_kategori;		
		$cari_kelas =self::$cari_kelas;
		$cari_grup=self::$cari_grup;
		$cari_produk =self::$cari_produk;		
		// ========= (akhir) inisialisasi variabel ============
		
	// =================== query produknya =============================
		$sql = "SELECT pro.pro_code, pro.pro_name, pro.pro_status 
				FROM `prc_master_product` as pro where 1=1 ";
		
		if ($cari_status_produk != '')
			$sql .= " and pro.pro_status = '$cari_status_produk' ";		
		if ($cari_produk != '')
			$sql .= " and pro.pro_name like '%$cari_produk%' ";		
		if ($cari_kode != '')
			$sql .= " and pro.pro_code like '%$cari_kode%' ";		
		if ($cari_kategori != '')
			$sql .= " and pro.pro_code like '$cari_kategori.%' ";		
		if ($cari_kelas != '')
			$sql .= " and pro.pro_code like '$cari_kelas%' ";		
		if ($cari_grup != '')
			$sql .= " and pro.pro_code like '$cari_grup%' ";		
		
		$sql .= " ORDER BY pro.pro_name";
				
		$data_produk = $this->db->query($sql);
	// =================== (akhir) query produknya =============================		
		
	// ================ ngambil datanya ====================
		// inisialisasi variabelnya
		$i = 0;
		$array_produk = array();
		$kode_produk = '';
		
		$array_produk[$i]['kode_produk'] = '';
		$array_produk[$i]['kategori_produk'] = '';
		$array_produk[$i]['kelas_produk'] = '';
		$array_produk[$i]['grup_produk'] = '';
		$array_produk[$i]['nama_produk'] = '';
		$array_produk[$i]['status_produk'] = '';
		
		
		foreach ($data_produk->result () as $row_produk)
		{
			$kode_produk = $row_produk->pro_code;
			$array_produk[$i]['kode_produk'] = $row_produk->pro_code;
			$array_produk[$i]['kategori_produk'] = $this->kategori_produk($kode_produk); // panggil fungsi kategori_roduk untuk mendapatkan datanya
			$array_produk[$i]['kelas_produk'] = $this->kelas_produk($kode_produk); // panggil fungsi kelas_produk untuk mendapatkan datanya
			$array_produk[$i]['grup_produk'] = $this->grup_produk($kode_produk); // panggil fungsi grup_produk untuk mendapatkan datanya
			$array_produk[$i]['nama_produk'] = $row_produk->pro_name;
			$array_produk[$i]['status_produk'] = $row_produk->pro_status;
			
			$i++;
		
		}
			
	
	// ================ (akhir) ngambil datanya ====================
	
	
	// =========== bwt pagingnya ================= (non aktif)
		$limit = $this->config->item('limit_report');
		$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$cari_bulan.'/'.	$cari_tahun,$sql,$limit,$uri_segment = 6);
	// ============= akhir pagingnya ===========
	
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
		$data["data_produk"] = $data_produk; //tanpa pagiong
		
		$data["array_produk"] = $array_produk; //data yang pake array
		
		
		$data['no_pos'] = 0;
		
	//	$data['data_pcv']= $paging['result']; // pake paging
		//$data['no_pos'] = $paging['pagina_pos']; // atrib paging
	
		$this->load->vars($data); // ngririm data yang ada di fungsi ini
		$this->bwt_view($menu); // bwt ngirim ke view				
	}
	
	
	// fungsi untuk menentukan kategorinya
	function kategori_produk ($pro_code)
	{
		$cat_name='';
		$arr_code = explode(".", $pro_code); // untuk dipecah kodenya produknya
		$class_code = $arr_code[0];
		$sql = "select cat_name from prc_master_category where cat_code='$class_code' and cat_level='1'";
		
		// ======== bqt ngecek ada ga nya datanya ===
		if ($this->db->query($sql)->num_rows() > 0){
			$rs = $this->db->query($sql)->row();
			$cat_name=$rs->cat_name;
		}		
		return $cat_name;	
	}
	
	// fungsi untuk menentukan kelas
	function kelas_produk ($pro_code)
	{
		$cat_name='';
		$arr_code = explode(".", $pro_code); // untuk dipecah kodenya produknya
		$class_code = $arr_code[0].'.'.$arr_code[1];
		$sql = "select cat_name from prc_master_category where cat_code='$class_code' and cat_level='2'";
		
		// ======== bqt ngecek ada ga nya datanya ===
		if ($this->db->query($sql)->num_rows() > 0){
			$rs = $this->db->query($sql)->row();
			$cat_name=$rs->cat_name;
		}		
		return $cat_name;	
	}
	
	// fungsi untuk menentukan grup
	function grup_produk ($pro_code)
	{
		$cat_name='';
		$arr_code = explode(".", $pro_code); // untuk dipecah kodenya produknya
		$class_code = $arr_code[0].'.'.$arr_code[1].'.'.$arr_code[2];
		$sql = "select cat_name from prc_master_category where cat_code='$class_code' and cat_level='3'";
		
		// ======== bqt ngecek ada ga nya datanya ===
		if ($this->db->query($sql)->num_rows() > 0){
			$rs = $this->db->query($sql)->row();
			$cat_name=$rs->cat_name;
		}
		
		return $cat_name;
	
	}

}
?>