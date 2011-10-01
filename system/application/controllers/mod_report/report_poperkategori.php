<?php
class report_poperkategori extends MY_Controller {
	private static $aa, $link_controller, $link_view, $user_id, $arr_pro, $arr_mr, $i, $search_month,
	$arr_tot,
	
	// variabel global filter
		$cari_tahun,$cari_bulan,$cari_kategori,$cari_kelompok,
		$cari_kode_barang,$cari_nama_barang,$cari_status,$tes;	
	
	
	function report_poperkategori() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('pro_code','general'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/po_perkategori/laporan','bahasa');
	
	
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
	
	// ================== akgir include (manggil) css & js (pake array) ====================
	
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
		self::$link_controller = 'mod_report/report_poperkategori';
		self::$link_view = 'purchase/mod_report/poperkategori_rpt';
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
		$data["content"] = self::$link_view.'/poperkategori';
		$this->load->view('index',$data);
	
	}
		
	
	
	
	function get_report($menu) {
		$cari_kategori=self::$cari_kategori;

		$action      = $this->input->post("action");
		$pro_id      = $this->input->post("pro_id");
		self::$search_month = $this->input->post("search_month");

		$arr_cat = array();
		self::$arr_pro = array();
		self::$arr_mr = array();

		self::$i = 0;
			
		//$arr      = array();
		$arr_cat  = array();
		self::$arr_pro  = array();
		self::$arr_mr   = array();
		$sql	  = "select cat_id, cat_code, cat_name from prc_master_category where cat_level='1' ";
		// bwt filternya
		if($cari_kategori!= 0)
				$sql .= " and cat_id = '$cari_kategori'";
		
		$sql .= "order by cat_code";
		
		
		$rs      = $this->db->query($sql);
		foreach($rs->result() as $arr) {
			$arr_cat[self::$i]['cat_name'] = $arr->cat_name;
			$this->get_pro($arr->cat_code);
			$this->cek_subtot($arr->cat_code);
			self::$i++;
		}
		
		// =====================================================
		
		
		
		
		
		
		// =====================================================
		
		
		
			
		$data["jumlah_data_kategori"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data
				
		$data['pro'] = self::$arr_pro;
		$data['mr_det'] = self::$arr_mr;
		$data['cat'] = $arr_cat;
		$data['aa'] = self::$aa;
		
		$arr_month_name = $this->lang->line('combo_box_array_bulan');
		
		
		$data['cari_status']=self::$cari_status;
		$data['data_month'] = $arr_month_name;
		$data['search_month'] = self::$search_month;	
		$data['search_month_excel'] = self::$search_month;		
		
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
		
		
		
		
		if ($menu == 'index'){
		$data['content'] = self::$link_view.'/poperkategori';
		$this->load->view('index',$data);
		} else if ($menu == 'excel'){
				$this->load->view(self::$link_view.'/excel',$data);
			}
		
		
		
	}

	function cek_subtot($cat_code) {
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;

		$j = 0;
		$sql = "SELECT cur.cur_symbol, sum( pd.qty * pd.price ) as total
				FROM prc_master_product as p
				INNER JOIN prc_pr_detail AS pd
					ON pd.pro_id = p.pro_id
				INNER JOIN prc_master_currency AS cur 
					ON cur.cur_id = pd.cur_id
				INNER JOIN prc_po as po
					ON pd.po_id = po.po_id
				WHERE p.pro_code like '$cat_code.%' and pd.po_id <> 0 ";
				if($cari_tahun != 0)
					$sql .= " and year(po.po_date) = '$cari_tahun'";
				if($cari_bulan != 0)
					$sql .= " and month(po.po_date) = '$cari_bulan'";
				$sql .= " GROUP BY pd.cur_id";
				
				
		$rs_tot = $this->db->query($sql);
		foreach($rs_tot->result() as $arr) {
			self::$arr_tot[self::$i][$j]['cur_symbol']   = $arr->cur_symbol;
			self::$arr_tot[self::$i][$j]['total']        = $arr->total;
			$j++;
		}
	}



	function get_pro($cat_code) {
		$cari_tahun=self::$cari_tahun;
		$cari_bulan=self::$cari_bulan;
		$j = 0;
		
	$sql = "select p.pro_id, p.pro_code, p.pro_name, gd.qty, gd.price, (gd.price*gd.qty) as sub_total,
			po.po_no, cur.cur_symbol, s.sup_name, usr.usr_name,po.po_date,p.um_id,
			u.satuan_name from prc_master_product as p
			inner join prc_gr_detail as gd
				on gd.pro_id = p.pro_id
			inner join prc_gr as gr
				on gd.gr_id = gr.gr_id
			inner join prc_po as po
				on po.po_id = gr.po_id
			inner join prc_master_currency as cur
				on cur.cur_id = gd.cur_id
			inner join prc_master_satuan as u
				on u.satuan_id = p.um_id
			inner join prc_master_supplier as s
				on s.sup_id = po.sup_id
			inner join prc_pr_detail as pd
				on po.po_id = pd.po_id and pd.pro_id = p.pro_id
			inner join prc_pr as pr
				on pd.pr_id = pr.pr_id
			inner join prc_sys_user as usr
				on pr.pr_requestor = usr.usr_id
			where p.pro_code like '$cat_code.%' and pd.po_id <> 0 ";
		
		
		/*
		$sql = "select p.cat_id, p.pro_id, p.pro_code, p.pro_name, p.is_stockJoin, p.pro_min_reorder, u.satuan_name from prc_master_product as p
				inner join prc_master_satuan as u
					on u.satuan_id = p.um_id
				where pro_code like '$cat_code.%' and p.pro_status='active'";
			*/
		if($cari_tahun != 0)
			$sql .= " and year(po.po_date) = '$cari_tahun' ";
		if($cari_bulan != 0)
			$sql .= " and month(po.po_date) = '$cari_bulan' ";
		
		$tot_rp=0;
		$tot_dol=0;
				
		$sql .= " order by p.pro_code";
		$rs_po = $this->db->query($sql);
		foreach($rs_po->result() as $arr) {
			self::$arr_pro[self::$i][$j]['pro_code']        = $arr->pro_code;
			self::$arr_pro[self::$i][$j]['pro_name']        = $arr->pro_name;
			self::$arr_pro[self::$i][$j]['satuan_name']     = $arr->satuan_name;
			self::$arr_pro[self::$i][$j]['pro_id']          = $arr->pro_id;
			self::$arr_pro[self::$i][$j]['po_no']       	= $arr->po_no;
			self::$arr_pro[self::$i][$j]['usr_name']        = $arr->usr_name;
			self::$arr_pro[self::$i][$j]['sup_name']        = $arr->sup_name;
			self::$arr_pro[self::$i][$j]['pro_id']       	= $arr->pro_id;
			self::$arr_pro[self::$i][$j]['qty']       		= $arr->qty;
			self::$arr_pro[self::$i][$j]['satuan_id']       = $arr->um_id;
			self::$arr_pro[self::$i][$j]['cur_symbol']      = $arr->cur_symbol;
			self::$arr_pro[self::$i][$j]['price']         	= $arr->price;
			self::$arr_pro[self::$i][$j]['sub_total'] 		= $arr->sub_total;
			self::$arr_pro[self::$i][$j]['class_name'] 		= 	$this->get_kelas($arr->pro_code);
			
			if($arr->cur_symbol =='Rp'){
				$tot_rp=$tot_rp+$arr->sub_total;
			}else {
				$tot_dol=$tot_dol+$arr->sub_total;
			}
			
		//	$this->detail_inv($arr->pro_id,$j);
			$j++;

		}
		$data['total_rp']=$tot_rp;
		$data['total_dol']=$tot_dol;

		$data["jumlah_data_produk"] =$this->general->hitung_banyak_data($sql);  // bwt ngitung banyak nya data
		$this->load->vars($data);
		
	}

	function get_kelas($pro_code) {
		$arr_code = explode(".", $pro_code);
		$class_code = $arr_code[0].".".$arr_code[1];
		$sql = "select cat_name from prc_master_category where cat_code='$class_code' and cat_level='2'";
		
		if ( $this->db->query($sql)->num_rows () >0 ){
			$rs = $this->db->query($sql);
			foreach($rs->result() as $row){
				$nama=$row->cat_name;
			}
			return $name=$row->cat_name;
		}else { return $name=''; }
	}




	function detail_inv($pro_id,$j) {
		//$this_month = date('j');
		$k = 0;
		//sum( md.qty ) as qty_mr
		$sql = "SELECT inv.bal_price,md.pro_id, sup.sup_name, md.qty as qty_mr
				FROM prc_mr_detail as md
				INNER JOIN prc_mr as m 
					on m.mr_id = md.mr_id
				LEFT JOIN prc_master_supplier as sup
					on sup.sup_id = md.sup_id
				LEFT JOIN prc_inventory as inv
					on inv.pro_id = md.pro_id
				WHERE md.pro_id = '$pro_id' ";
		if (!empty(self::$search_month)):
			$sql .= "and month(m.mr_date) = '".self::$search_month."' ";
		endif;
		
		$sql .= "and (md.requestStat='1' or md.requestStat='4') 
				GROUP BY md.pro_id, md.sup_id";
		$rs_det_mr = $this->db->query($sql);
		foreach($rs_det_mr->result() as $arr) {
			self::$arr_mr[self::$i][$j][$k]['sup_name']	= $arr->sup_name;
			self::$arr_mr[self::$i][$j][$k]['qty_mr']	= $arr->qty_mr;
			self::$arr_mr[self::$i][$j][$k]['bal_price']= $arr->bal_price;
			$k++;
		}
		//self::$aa = $pro_id.'|'.self::$i.'|'.self::$search_month;
	}

}
?>