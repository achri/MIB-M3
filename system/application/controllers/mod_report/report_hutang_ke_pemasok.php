<?php
class Report_hutang_ke_pemasok extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,
	
	// =========== inisialisasi variabel bwt filter baru ========
		$akhir_tot_rp,$akhir_tot_dol,$arr_month_name,$gr_filter, $search_year,$search_month, $search_supplier, 
		$search_cat,$tot_akhir_rp, $tot_akhir_dol,$list_tahun,$cari_status ;
		
	// ========== akhir inisialisasi bwt filter ======	

	

	function report_hutang_ke_pemasok () {
		parent::MY_Controller();
		
//		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->model(array('tbl_user','tbl_supplier','tbl_category','tbl_hutang'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
	// =========== untuk bahasa ========================================		
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');

		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/hutang_ke_pemasok/laporan','bahasa');
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
			self::$search_year = 0; 	
			self::$search_month = 0; 
			self::$search_supplier = 0; 		
			self::$search_cat =0; 	
			//self::$this_year =date("Y");	

				
			self::$tot_akhir_rp = 0; 		
			self::$tot_akhir_dol = 0; 		
			
			self::$akhir_tot_rp=0;
			self::$akhir_tot_dol=0;

		self::$cari_status='';


		self::$arr_month_name = $this->lang->line('combo_box_array_bulan');
		self::$list_tahun = $this->db->query("select distinct thn from prc_sys_counter");
		
		
		// ========== akhir inisialisasi ======

		// ============ masukin variabil yg diisi / yg dikirim dari view =======
		if ($this->input->post("search_year"))
			self::$search_year  = $this->input->post("search_year");
		if ($this->input->post("search_month"))
			self::$search_month  = $this->input->post("search_month");
		if ($this->input->post("search_supplier"))
			self::$search_supplier  = $this->input->post("search_supplier");
		if ($this->input->post("search_cat"))
			self::$search_cat  = $this->input->post("search_cat");
			
		if ($this->input->post("cari"))
			self::$cari_status  = $this->input->post("cari");

		// ============ (akhir) masukin variabil yg diisi / yg dikirim dari view =======
		
		if ($this->input->post("search_year"))
			$search_year  = $this->input->post("search_year");
			
		if ($this->input->post("search_month"))
			$search_month  = $this->input->post("search_month");
		if ($this->input->post("search_supplier"))
			$search_supplier  = $this->input->post("search_supplier");
		if ($this->input->post("search_cat"))
			$search_cat  = $this->input->post("search_cat");


		
		
		
		// ============= untuk session id masuk nya ===================
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		// ============= akhir untuk session id masuk nya ===================


		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_hutang_ke_pemasok';
		self::$link_view = 'purchase/mod_report/hutang_ke_pemasok_rep';
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		
		
		// ============= akhir link untuk manggil viewnya ===================


		$data['title_page'] = $this->lang->line('lap_judul');
		
		$this->load->vars($data);



	}

	function index()
	{
		$this->hutang('index');
	}

// buat export ke excel
	function excel(){
		$this->hutang('excel');

	}


	function hutang($menu){
		$arr_month_name = $this->lang->line('combo_box_array_bulan');
		
		$list_pemasok = $this->db->query("select sup_id, sup_name from prc_master_supplier order by sup_name ASC");
		$list_kategori = $this->db->query("select cat_id, cat_name from prc_master_category where cat_level=1 order by cat_name ASC");


		// ============= query gedenya ===========================
		
		
			$search_month=self::$search_month;
			$search_supplier=self::$search_supplier;
			$search_cat=self::$search_cat;
			$akhir_tot_rp=self::$akhir_tot_rp;
			$akhir_tot_dol=self::$akhir_tot_dol;
			$search_year =self::$search_year;	




		$arr          = array();
		$arr_hutang   = array();
		$sql = "select distinct h.sup_id, sup.sup_name,

			(select awal from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos
			 and cur_id=1 and hd.sup_id=h.sup_id) as awal_rp,
			
			(select awal from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos 
			 and cur_id=2 and hd.sup_id=h.sup_id) as awal_dol,

			(select beli from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos
			 and cur_id=1 and hd.sup_id=h.sup_id) as beli_rp,
			
			(select beli from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos 
			 and cur_id=2 and hd.sup_id=h.sup_id) as beli_dol,
			
			(select bayar from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos 
			 and cur_id=1 and hd.sup_id=h.sup_id) as bayar_rp,
			
			(select bayar from prc_hutang_bulanan as hd 
			 where hd.thn_pos = h.thn_pos and hd.bln_pos=h.bln_pos 
			 and cur_id=2 and hd.sup_id=h.sup_id) as bayar_dol
			 
			
			from prc_hutang_bulanan as h 
			inner join prc_master_supplier as sup on h.sup_id = sup.sup_id
						
			where 1=1";
		if($search_year != 0)
			$sql .= " and thn_pos = '$search_year' ";
		if($search_month != 0)
			$sql .= " and bln_pos = '$search_month' ";
		
		if($search_supplier != 0)
			$sql .= " and sup.sup_id = $search_supplier ";
		if($search_cat != 0)
			$sql .= " and sup.sup_id in (select sup_id from prc_master_supplier_category where cat_id=$search_cat) ";
		$sql .= " order by sup.sup_name";
	
		$list_hutang=$this->db->query($sql); // EKSEKUSI QUERY
		$jumlah_data=$this->hitung_banyak_data($sql); //hitung jumlah data
		// ==================== hitung saldo akhir ======================
			
			
			$j = 0;		
			foreach ($list_hutang->result() as $row){
			
			$data2[$j]['sup_name']=$row->sup_name;
			$data2[$j]['awal_rp']=$row->awal_rp;
			$data2[$j]['awal_dol']=$row->awal_dol;
			$data2[$j]['beli_rp']=$row->beli_rp;
			$data2[$j]['beli_dol']=$row->beli_dol;
			$data2[$j]['bayar_rp']=$row->bayar_rp;
			$data2[$j]['bayar_dol']=$row->bayar_dol;
			
			
			// $arr['awal_rp'] + $arr['beli_rp'] - $arr['bayar_rp'];
			
			
			$data2[$j]['akhir_rp']		= ($row->awal_rp) + ($row->beli_rp) - ($row->bayar_rp) ;
			$data2[$j]['akhir_rp']		= ($row->awal_rp) + ($row->beli_rp) - ($row->bayar_rp) ;

			 /* ======================== jadul=====================
			 // === masukin ke array=====
			$arr['sup_name']=$row->sup_name;
			$arr['awal_rp']=$row->awal_rp;
			$arr['awal_dol']=$row->awal_dol;
			$arr['beli_rp']=$row->beli_rp;
			$arr['beli_dol']=$row->beli_dol;
			$arr['bayar_rp']=$row->bayar_rp;
			$arr['bayar_dol']=$row->bayar_dol;
			
			 
			//array_push($arr_kb, $arr);
			$arr_hutang[$j]['sup_name']		= $arr['sup_name'];
			
			$arr_hutang[$j]['awal_rp']		= $arr['awal_rp'];
			$arr_hutang[$j]['awal_dol']		= $arr['awal_dol'];
	
			$arr_hutang[$j]['beli_rp']		= $arr['beli_rp'];
			$arr_hutang[$j]['beli_dol']		= $arr['beli_dol'];
	
			$arr_hutang[$j]['bayar_rp']		= $arr['bayar_rp'];
			$arr_hutang[$j]['bayar_dol']	= $arr['bayar_dol'];
	
			$arr_hutang[$j]['akhir_rp']		= $arr['awal_rp'] + $arr['beli_rp'] - $arr['bayar_rp'];
			$arr_hutang[$j]['akhir_dol']	= $arr['awal_dol'] + $arr['beli_dol'] - $arr['bayar_dol'];
			
							
			=====================yang dulu==================================   */
			
			$j++;
		}
			
		// ==================== (akhir) hitung saldo akhir ======================

		
		
		
		// ================= hitung total RP ================
		
			$sql_tot = "select cur_id, sum(awal) as tot_awal_rp, sum(beli) as tot_beli_rp, sum(bayar) as tot_bayar_rp
			from prc_hutang_bulanan where cur_id=1";
			if($search_month != 0)
				$sql_tot .= " and bln_pos = '$search_month' and thn_pos='$search_year'";
			if($search_supplier != 0)
				$sql_tot .= " and sup_id = $search_supplier ";
			if($search_cat != 0)
				$sql_tot .= " and sup_id in (select sup_id from prc_master_supplier_category where cat_id=$search_cat) ";
			$sql_tot .= " group by cur_id";
		
		$list_hutang_tot_rp=$this->db->query($sql_tot); // EKSEKUSI QUERY
		
		if ($list_hutang_tot_rp->num_rows() >0){
			$row_rp=$list_hutang_tot_rp->row();
			$akhir_tot_rp=(($row_rp->tot_awal_rp)+($row_rp->tot_beli_rp))-($row_rp->tot_bayar_rp);
		}		
		// ================= akhir hitung total rp ================


		// ================= hitung total RP ================

			$sql = "select cur_id, sum(awal) as tot_awal_dol, sum(beli) as tot_beli_dol, sum(bayar) as tot_bayar_dol
				from prc_hutang_bulanan where cur_id=2";
		//if($search_month != 0)
			//$sql .= " and bln_pos = '$search_month' and thn_pos='$this_year'";
		if($search_year != 0)
			$sql .= " and thn_pos = '$search_year' ";
		if($search_month != 0)
			$sql .= " and bln_pos = '$search_month' ";
		if($search_supplier != 0)
			$sql .= " and sup_id = $search_supplier ";
		if($search_cat != 0)
			$sql .= " and sup_id in (select sup_id from prc_master_supplier_category where cat_id=$search_cat) ";
		$sql .= " group by cur_id";
	
		$list_hutang_tot_dol=$this->db->query($sql); // EKSEKUSI QUERY

		if ($list_hutang_tot_dol->num_rows() >0){
			$row_dol=$list_hutang_tot_dol->row();
			$akhir_tot_dol=(($row_dol->tot_awal_dol)+($row_dol->tot_beli_dol))-($row_dol->tot_bayar_dol);
		}

		// ================= akhir hitung total rp ================
	
		
		
		
	// ============= akhir query gedenya ===========================
	
	
		
	// ============= untuk pengiriman daTa ke view ==============================
	//	$data["data_hutang2"] = $data2;

		$data["data_hutang_tot_akhir_rp"] = $akhir_tot_rp;
		$data["data_hutang_tot_akhir_dol"] = $akhir_tot_dol;

		$data["data_hutang_tot_rp"] = $list_hutang_tot_rp;
		$data["data_hutang_tot_dol"] = $list_hutang_tot_dol;
		
		$data["data_cari_bulan"] = $arr_month_name[self::$search_month]; //untuk nampilin nama bulan yg dipilih

		$data['cari_status']=self::$cari_status;


		$data["data_hutang"] = $list_hutang;
		$data["data_pemasok"] = $list_pemasok;
		$data["data_kategori"] = $list_kategori;
		$data["data_bulan"] = $arr_month_name;
		$data["data_tahun"] = self::$list_tahun;
		$data["search_year"] = $search_year;
		$data["search_month"] = $search_month;
		$data["search_supplier"] = self::$search_supplier;
		$data["search_cat"] = self::$search_cat;
		$data["jumlah_data"] =$jumlah_data;  // ngitung banyak datanya		

	// ============= akhir untuk pengiriman daTa ke view ==============================
		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel'){
			$this->load->view(self::$link_view.'/excel',$data);

		}
	}
	
	
		// bwt ngitung banyaknya datas
	function hitung_banyak_data($sql)
	{
		$query_hitung=$this->db->query($sql);
		$jum_data = 0;
		foreach ($query_hitung->result() as $rows){
			$jum_data++;		
		}	
		return $jum_data;
	}




}


?>