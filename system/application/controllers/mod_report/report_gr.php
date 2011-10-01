<?php
class report_gr extends MY_Controller {
	private static $link_controller, $link_view, $user_id, $i, $arr_tot, $arr_inv, $arr_pro, $fsearch_year, $fsearch_month,
	
	// =========== inisialisasi variabel bwt filter baru ========
		$cari_po_no, $cari_pemasok,$cari_pemohon, 
		$cari_satuan, $cari_kategori,$cari_qyt_beli,		
		$cari_kode_barang, $cari_nama_barang, $this_year, $search_year, $search_month, $arr_month_name,$cari_tahun,$cari_bulan,$cari_status,$arr_cat_name,		
		$search_supplier,$status_kb
		;
	// ========== akhir inisialisasi bwt filter ======	
	
	function report_gr() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->load->library('pro_code');
		$this->config->load('tables');
		
	// =========== untuk bahasa ========================================		
		$this->lang->load('label','bahasa');
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/penerimaan_barang/laporan','bahasa');
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
		
		foreach ($arrayCSS as $css):
			$data['extraHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$data['extraHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;
	// ================== akhir include (manggil) css & js (pake array) ====================
		
		// =========== inisialisasi ========
			self::$cari_po_no = ''; 
			self::$cari_pemohon =''; 		
			self::$cari_satuan = ''; 		
			self::$cari_qyt_beli = ''; 		
			
			self::$cari_kode_barang = ''; 		
			self::$cari_nama_barang = ''; 		

			self::$this_year = date("Y");; 		
			self::$search_year = 0; 		
			self::$search_month = 0; 
			
			
			self::$arr_month_name = $this->lang->line('combo_box_array_bulan');
	
	
			self::$search_supplier=0;
			
			
			// yg dipake
			self::$cari_tahun=0;
			self::$cari_bulan=0;
			self::$cari_status='';
			self::$cari_kategori = ''; 		
			self::$cari_pemasok = 0; 		
			self::$status_kb=0;
			self::$i=0;
			self::$arr_cat_name  = array();
		// ========== akhir inisialisasi ======
		
		
		// === masukin variabil yg diisi =======
		// yag di pake
		 if ($this->input->post("status_kb"))
			self::$status_kb = $this->input->post("status_kb");					 
		if ($this->input->post("cari_tahun"))
			self::$cari_tahun = $this->input->post("cari_tahun");			
		if ($this->input->post("cari_bulan"))
			self::$cari_bulan = $this->input->post("cari_bulan");			
		 if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok = $this->input->post("cari_pemasok");					 
		 if ($this->input->post("cari_status"))
			self::$cari_status= $this->input->post("cari_status");					 
		
		if ($this->input->post("search_supplier"))
			self::$search_supplier = $this->input->post("search_supplier");			
		
		 if ($this->input->post("cari_po_no"))
			self::$cari_po_no = $this->input->post("cari_po_no");			
		 if ($this->input->post("cari_satuan"))
			self::$cari_satuan = $this->input->post("cari_satuan");			
		 if ($this->input->post("cari_kategori"))
			self::$cari_kategori = $this->input->post("cari_kategori");					 
		 if ($this->input->post("cari_qyt_beli"))
			self::$cari_qyt_beli = $this->input->post("cari_qyt_beli");	
		 if ($this->input->post("cari_kode_barang"))
			self::$cari_kode_barang = $this->input->post("cari_kode_barang");					 
		 if ($this->input->post("cari_nama_barang"))
			self::$cari_nama_barang = $this->input->post("cari_nama_barang");	
		
				
/*
		 if ($this->input->post("cari_lebih_rinci"))
			self::$cari_lebih_rinci = $this->input->post("cari_lebih_rinci");	
*/
		// ======== akhir masukin variabel =================

		// ============= link untuk manggil viewnya ===================
		
		self::$link_controller = 'mod_report/report_gr';
		self::$link_view = 'purchase/mod_report/gr_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		$data['title_page'] = $this->lang->line('lap_judul');
		// ============= akhir link untuk manggil viewnya ===================
		
		$this->load->vars($data);
	}
	
	function index()
	{
		if (self::$cari_status !=''){
			$this->gr_report('index');
		}else {
			$this->bwt_view('index');
		}
	}	
	
	function excel()
	{
		$this->gr_report('excel');
	}	
	
	function bwt_view($menu)
	{
		$data['cari_tahun']=self::$cari_tahun;
		$data['cari_bulan']=self::$cari_bulan;
		$data['cari_kategori']=self::$cari_kategori;
		$data['cari_pemasok']=self::$cari_pemasok;
		$data['status_kb']=self::$status_kb;
		$data['cari_status']=self::$cari_status;
		
		$data['checklist']=''; // blm di olah
		
		$data['kategori_kosong']='ada';

		// untuk array combo box nya
		$data["nama_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_bulan"] =$this->general->combo_box('nama_bulan');
		$data["data_tahun"] =$this->general->combo_box('tahun');
		$data["data_kategori"] =$this->general->combo_box('kategori');
		$data["data_pemasok"] =$this->general->combo_box('pemasok');

		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/report_gr_main';
			$this->load->view('index',$data);
		} else 	if ($menu == 'excel'){
			$this->load->view( self::$link_view.'/report_gr_excel',$data);
		}
	}
	
	function gr_report($menu)
	{
		$cari_bulan=self::$cari_bulan;
		$cari_tahun=self::$cari_tahun;
		$cari_pemasok=self::$cari_pemasok;
		$cari_kategori=self::$cari_kategori;
		$status_kb=self::$status_kb;
		
		//  ========= bwt filter lewat no bpb ===========
			$str_tahun		=0;
			if (self::$cari_tahun != 0){
				$str_tahun		=  	str_split(self::$cari_tahun,2);
			}
			$str_bulan		=  str_pad($cari_bulan, 2, "0", STR_PAD_LEFT);
	
			if ($cari_tahun == '2009'){
				$filter_dr_no	=$str_tahun[1].$str_bulan; // format no bwt tahun sebelm 2010
			} else {
				$filter_dr_no	=$str_tahun[1].'/'.$str_bulan;
			}
			
		//  ========= bwt filter lewat no bpb ===========
	
		$arr_penerimaan=array();
		
			$sql	= "select g.*, c.con_no,  date_format(g.gr_date,'%d/%m/%Y') as gr_date,
								date_format(g.gr_suratJalanTgl,'%d/%m/%Y') as gr_dateSJ,
								s.sup_name, gd.qty, pro.pro_name, pro.pro_code, um.satuan_name,um.satuan_id, 
								gd.price,cur.cur_symbol, (gd.price * gd.qty)as gd_totprice,
								p.po_no, date_format(p.po_date,'%d-%b') as po_date
				   from prc_gr_detail as gd
				   inner join prc_gr as g on g.gr_id = gd.gr_id
				   inner join prc_po as p on p.po_id = g.po_id
				   inner join prc_master_product as pro on pro.pro_id = gd.pro_id
				   inner join prc_pr_detail as pd on gd.pro_id = pd.pro_id and g.po_id = pd.po_id
				   inner join prc_master_satuan  as um on um.satuan_id = pd.um_id
				   inner join prc_master_currency as cur on gd.cur_id = cur.cur_id				   
				   left join prc_master_supplier as s on p.sup_id = s.sup_id
				   left join prc_contrabon as c on c.con_id = g.con_id
				   where g.gr_type = 'rec' and g.gr_printStatus = 1 ";
		
		if($cari_tahun != 0 ){			
				$sql .= " and g.gr_no like '".$str_tahun[1]."%'";	
			
			if($cari_bulan != 0 ){			
				$sql .= " and g.gr_no like '".$filter_dr_no."%'";	
			}
		}
			
		if($status_kb == 1)
			$sql .= " and g.con_id <> 0";
		elseif($status_kb == 2)
			$sql .= " and g.con_id = 0";
		if($cari_pemasok != 0)
			$sql .= " and s.sup_id = $cari_pemasok";
		if($cari_kategori != 0)
			$sql .= " and pro.pro_code like '".$cari_kategori."%'";
			
		$sql .= " order by g.gr_no";
	
		$total_rp=0;
		$total_dol=0;
	
		foreach($this->db->query($sql) -> result() as $rows):
			
			$arr_penerimaan[self::$i]['gr_date']			=  $rows->gr_date; 
			$arr_penerimaan[self::$i]['po_date']			=  $rows->po_date;
			$arr_penerimaan[self::$i]['po_no']				=  $rows->po_no; 
			$arr_penerimaan[self::$i]['gr_dateSJ']			=  $rows->gr_dateSJ; 
			$arr_penerimaan[self::$i]['gr_no']				=  $rows->gr_no;
			//$arr_penerimaan[self::$i]['usr_name']			=  $rows->usr_name;
			$arr_penerimaan[self::$i]['pro_name']			=  $rows->pro_name;
			$arr_penerimaan[self::$i]['pro_code']			=  $rows->pro_code;
			$arr_penerimaan[self::$i]['sup_name']			=  $rows->sup_name;
			$arr_penerimaan[self::$i]['gr_suratJalan']		=  $rows->gr_suratJalan;
			$arr_penerimaan[self::$i]['qty']				=  $rows->qty;
			$arr_penerimaan[self::$i]['cur_symbol']			=  $rows->cur_symbol;
			$arr_penerimaan[self::$i]['gd_totprice']		=  $rows->gd_totprice;
			$arr_penerimaan[self::$i]['price']				=  $rows->price;		
			$arr_penerimaan[self::$i]['satuan_name']		=  $rows->satuan_name;	
			$arr_penerimaan[self::$i]['satuan_id']			=  $rows->satuan_id;				
			$arr_penerimaan[self::$i]['cat_name']			= $this->nama_kategori($rows->pro_code);
			$arr_penerimaan[self::$i]['con_no']				=  $rows->con_no;
			$arr_penerimaan[self::$i]['con_id']				=  $rows->con_id;
			self::$i++;

			
		// =========== jumlah =================================		
			if ($rows->cur_symbol=='Rp') {
				$total_rp=$total_rp + $rows->gd_totprice;
			}else {	$total_dol=$total_dol + $rows->gd_totprice; }
		// =========== akhir jumlah =================================	
				
		endforeach;		
		
		// kirim data tambahan		
		$data["jumlah_data"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data	
		$data['cari_status']=self::$cari_status;
		
		$data['total_rp'] = $total_rp;
		$data['total_dol'] = $total_dol;
		
		$data['data_penerimaan'] = $arr_penerimaan;
		$data['data_kategori']=self::$arr_cat_name;
		$this->load->vars($data); // untuk ngirim data ke view nya	
		$this->bwt_view($menu); // untuk ngirim data ke view nya
	}	
	
	// buat nampilin nama kategori beradasarkan produk number (pro_code)
	function nama_kategori ($pro_code)
	{
		$cari_kategori=self::$cari_kategori;
	
		$arr_code = explode(".", $pro_code); // mecah kode nya (ex: 01.01.01.01)
		$cat_code = $arr_code[0]; // ngambil data array yg pertama
			
		$sql = "select cat_name from prc_master_category where cat_code='$cat_code' and cat_level=1";

		$rs_cat = $this->db->query($sql);
		$rs_cat2 = $this->db->query($sql)->row();
		$j=0;
		if($rs_cat->num_rows () >0 ) 
		{
			return $rs_cat2->cat_name;
		}
	}
	
}
?>