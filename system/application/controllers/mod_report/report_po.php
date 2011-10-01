<?php
/*
	Tanggal : 17 Maret 2010
	Perubahan : status po untuk menu nya

*/

class report_po extends MY_Controller {
	//deklarasi variabelnya,, (bwt ntr dipanggilnya pke "self::<var>"  ex: self::$link_view= 'tes';
	private static $link_controller, $link_view, $user_id,$search_status_excel,$search_month_excel,$search_year_excel,$search_cat,
	// variabel global filter
		$cari_tahun,$cari_bulan,$cari_kategori,$cari_kelompok,
		$cari_kode_barang,$cari_nama_barang,$cari_status,$tes,
		$cari_pemasok,$cari_po_no,$search_month,$search_year,$search_status,
		$ppn_status		
		;	
	
	function report_po() {
		parent::MY_Controller();
		
		$this->load->model(array('tbl_po','tbl_user'));
		$this->load->library(array('session','pagina_lib','general'));
		$this->config->load('tables');
		
		// LANGUAGE
		$this->lang->load('tables','bahasa');
		$this->lang->load('general','bahasa');
		$this->lang->load('label','bahasa');
		
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/po/laporan','bahasa');
		
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
			$data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". base_url()."asset/css/themes/start/ui.all.css\" />\n";

		
		
		// THIS SESSION USER LOGIN
		self::$user_id = $this->session->userdata("usr_id");
		$user_name	= $this->tbl_user->get_user(self::$user_id)->row()->usr_name;
		
		self::$search_cat =0; 	


	// =========== inisialisasi ========
	
		self::$cari_pemasok='';
		self::$cari_po_no='';
		self::$search_month=0;
		self::$search_year=0;
		self::$search_status='';
		self::$search_cat='';
		
		self::$cari_tahun=0;
		self::$cari_bulan=0;
		self::$cari_kategori=0;
		self::$cari_kode_barang='';
		self::$cari_nama_barang='';
		self::$cari_kelompok='';

		self::$cari_status='';
		self::$ppn_status='';
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

		// emergrnci
		
				
		if ($this->input->post("search_cat"))
			self::$search_cat = $this->input->post("search_cat");					 

		if ($this->input->post("cari_pemasok"))
			self::$cari_pemasok = $this->input->post("cari_pemasok");					 
		 if ($this->input->post("cari_po_no"))
			self::$cari_po_no = $this->input->post("cari_po_no");
		if ($this->input->post("search_month"))
			self::$search_month  = $this->input->post("search_month");
		if ($this->input->post("search_year"))
			self::$search_year  = $this->input->post("search_year");
		if ($this->input->post("search_status"))
			self::$search_status  = $this->input->post("search_status");
		// ========== akhir emrgnci ==============================

		// ================== untuk pilihan PPN / Non PPN =====================		
			if ($this->config->item('m3_ppn') == 'PPN')
				self::$ppn_status = 'ppn_';
		// ================== untuk pilihan PPN / Non PPN =====================

		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_report/report_po';
		
		if (self::$ppn_status == 'ppn_'){			
			self::$link_view = 'purchase/mod_report/po_rep/ppn';
		} else {			
			self::$link_view = 'purchase/mod_report/po_rep';
		}
		$data['link_controller'] = self::$link_controller;
		$data['link_view'] = self::$link_view;
		// ============= (akhir) link untuk manggil viewnya ===================
		
		$data['usr_name'] = $user_name;		
		$data['title_page'] = $this->lang->line('lap_judul');
		
		$this->load->vars($data);
	}
	
	
	function index() {	
		if (self::$cari_status !=''){
			$this->rep_po('index');
		}else {
			$this->bwt_view();
		}
	}
	
	function excel() {		
		$this->rep_po('excel');
	}
	
	function bwt_view(){
		$list_kategori = $this->db->query("select cat_id, cat_name from prc_master_category where cat_level=1 order by cat_name ASC");
		$arr_month_name = $this->lang->line('combo_box_array_bulan');
		$arr_year = $this->db->query("select distinct year(po_date) as year from prc_po order by po_date");
	
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
	
	
		$data['seldate'] = 0;
		$data["data_kategori"] = $list_kategori;
		$data["search_cat"] = self::$search_cat;
	
		$data["data_pemasok"] = $this->general->combo_box('pemasok');
	
		
		$data["data_year"] = $arr_year;
		
		$data["data_bulan"] = $arr_month_name;
		$data["search_status"] = self::$search_status;
		$data["search_month"] = self::$search_month;
		$data["search_year"] = self::$search_year;
		
		$data['cari_po_no'] = self::$cari_po_no; 
		$data['cari_pemasok'] = self::$cari_pemasok; 
		
		$data['search_status_nama'] =self::$search_status ; 
		$data["content"] = self::$link_view.'/report_po_main';
		$this->load->view('index',$data);
		
	}
	
	function rep_po($menu) {
		
		$search_month=self::$search_month;
		$search_year=self::$search_year;
		$search_status=self::$search_status;
		$search_cat=self::$search_cat;
		$cari_po_no = self::$cari_po_no;
		$cari_pemasok = self::$cari_pemasok; 
		$cari_kategori = self::$cari_kategori; 

		$search_cat=self::$search_cat;
		$list_kategori = $this->db->query("select cat_id, cat_name from prc_master_category where cat_level=1 order by cat_name ASC");		
		
		$this_year	  = date("Y");
		
		$arr_month_name = $this->lang->line('combo_box_array_bulan');
		
		if ($search_month == '')
			$search_month  = 0;
			
		if ($search_year == '')
			$search_year  = 0;
			
			
		// pas pke cara achri balik lagi,,, anehhh,, jadi weh pke care ini
		$search_status = $this->input->post("search_status");
		if($search_status == '')
		 $search_status = 'A';

  	    $data['cari_berdasar_po_no']='';
		$data['cari_berdasar_pemasok'] ='';
		
					 
		
		if($search_year == 0)
		  $year_selected = $this->lang->line('lap_tahun_dipilih');
		else
		  $year_selected = $search_year;
		  
		// ==== bwt seleksi tampilin data apa yg diseleksi =========
		if(($search_year == '' && $search_month == '')||($search_year == 0 && $search_month == 0))
			$seldate = $this->lang->line('lap_semua_tanggal');
		else
			$seldate = '<font color="red">'.$arr_month_name[$search_month].' '.$year_selected.'</font> ';
				
		$data['seldate'] = $seldate;
    	 // ==== akhir bwt seleki ============
		 
		 
		 if ($cari_po_no != '' )
		 {
			 $data['cari_berdasar_po_no'] = $this->lang->line('lap_no').'<font color="red">'.$cari_po_no.'</font>';		 	
		 }
		 
		 if ($cari_pemasok != '' )
		 {
			 $data['cari_berdasar_pemasok'] = $this->lang->line('lap_no').'<font color="red">'.$cari_pemasok.'</font>';		 	
		 }
		 
		 
		// ===== bwt querynya =====================	 
		$arr_year = $this->db->query("select distinct year(po_date) as year from prc_po order by po_date");
		
		
		$sql	 = "SELECT p.po_id, p.po_no, p.po_status,p.po_note, date_format(p.po_date,'%d-%m-%Y') as po_date, 
					s.sup_name,cur.cur_digit,ml.legal_name,
					(select sum((pd.qty * pd.price) - (pd.qty * pd.price * (pd.discount/100))) from prc_pr_detail as pd
					 where pd.po_id = p.po_id and cur_id=1) as tot_rp,
		
					(select sum((pd.qty * pd.price) - (pd.qty * pd.price * (pd.discount/100))) from prc_pr_detail as pd
					 where pd.po_id = p.po_id and cur_id=2) as tot_dol,
		
					(select sum((gd.qty * gd.price) - (gd.qty * gd.price * (gd.discount/100))) 
					 from prc_gr_detail as gd
					 inner join prc_gr as gr
						on gr.gr_id = gd.gr_id
					 where gr.po_id = p.po_id and cur_id=1) as rec_rp,
		
					(select sum((gd.qty * gd.price) - (gd.qty * gd.price * (gd.discount/100))) from prc_gr_detail as gd
					 inner join prc_gr as gr
						on gr.gr_id = gd.gr_id
					 where gr.po_id = p.po_id and cur_id=2) as rec_dol
					FROM prc_po as p
					left join prc_master_supplier as s on s.sup_id = p.sup_id
					left join prc_master_currency as cur on cur.cur_id = p.cur_id
					left join prc_master_legality as ml on ml.legal_id = s.legal_id					
					where 1=1";
					
		if($search_year != 0)
				$sql .= " and year(p.po_date) = '$search_year'";
		if($search_month != 0)
				$sql .= " and month(p.po_date) = '$search_month'";
		if($search_status != 'A')
				$sql .= " and p.po_status = '$search_status'";
		if($cari_pemasok != 0)
				$sql .= " and p.sup_id ='$cari_pemasok'";
		if($cari_po_no != '')
				$sql .= " and p.po_no LIKE '%$cari_po_no%'";
		if($cari_kategori != 0)
			$sql .= " and s.sup_id in (select sup_id from prc_master_supplier_category where cat_id=$cari_kategori) ";
		
		$sql .= " order by p.po_no ";
		// ========== akhir query =====================
	
		// =========== bwt pagingnya =================
		$limit = $this->config->item('limit_report');
		$paging = $this->pagina_lib->pagina(self::$link_controller.'/index/'.$search_month.'/'.	$search_year.'/'.$search_status,$sql,$limit,$uri_segment = 7);
		// ============= akhir pagingnya ===========
		
//		$data['data_po']= $paging['result']; // pke paging
	//	$data['no_pos'] = $paging['pagina_pos'];


		//tanpa paging
		$data['data_po']= $this->db->query($sql);
		$data['no_pos'] = 0;

		// ===================== jumlah total ========================
		$total_po_rp=0;
		$total_po_dol=0;		
		$total_diterima_rp=0;
		$total_diterima_dol=0;

	//	foreach($paging['result']-> result() as $rows_total): // total sesuai pagingnya

		foreach($this->db->query($sql)-> result() as $rows_total):
			$total_po_rp=$total_po_rp + ($rows_total->tot_rp);
			$total_po_dol=$total_po_dol + ($rows_total->tot_dol);
			
			$total_diterima_rp=$total_diterima_rp + ($rows_total->rec_rp);
			$total_diterima_dol=$total_diterima_dol + ($rows_total->rec_dol);
		
		endforeach;
		
		$data['total_po_rp']=$total_po_rp;
		$data['total_po_dol']=$total_po_dol;		
		$data['total_diterima_rp']=$total_diterima_rp;		
		$data['total_diterima_dol']=$total_diterima_dol;		

		$data['total_selisih_rp']=$total_po_rp-$total_diterima_rp;
		$data['total_selisih_dol']=$total_po_dol-$total_diterima_dol;				
		
		// ===================== (akhir) jumlah total ========================

		// =========== buat hidung banyak data yg diselsi
		$query_hitung_po=$this->db->query($sql);
		$jum_po = 0;
		foreach ($query_hitung_po->result() as $rows){
			$jum_po++;		
		}		
		$data["jumlah_po"] = $jum_po;
		
	
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
	
	
	
		$data["data_kategori"] = $list_kategori;
		$data["search_cat"] = self::$search_cat;
	
		$data["data_pemasok"] = $this->general->combo_box('pemasok');
	
		
		$data["data_year"] = $arr_year;
		
		$data["data_bulan"] = $arr_month_name;
		$data["search_status"] = $search_status;
		$data["search_month"] = $search_month;
		$data["search_year"] = $search_year;
		
		$data['cari_po_no'] = $cari_po_no; 
		$data['cari_pemasok'] = self::$cari_pemasok; 

		$search_status_nama = '';
		if ($search_status == 0 ){
			$search_status_nama=$this->lang->line('combo_box_status_buka');			
		}else if ($search_status == 1 ){
			$search_status_nama=$this->lang->line('combo_box_status_tutup');			
		}
		
		
		$data['search_status_nama'] = $search_status_nama; 
		
		if ($menu == 'index'){
			$data["content"] = self::$link_view.'/report_po_main';
			$this->load->view('index',$data);
		} else if ($menu == 'excel'){
			$this->load->view(self::$link_view.'/report_po_excel',$data);		
		}		
	}
	
	function get_detail($po_id,$po_no1 = '00',$po_no2 = '00',$po_no3 = '00000',$for = 'view') 
	{		
		$po_status=$this->uri->segment(8);
		$lihat_untuk =$this->uri->segment(9);
		$po_no = $po_no1.'/'.$po_no2.'/'.$po_no3;
		$judul ='';
		if ($po_status == 0) {
			$judul = $this->lang->line('lap_detail_judul_buka');
		}else if ($po_status == 1) {
			$judul = $this->lang->line('lap_detail_judul_tutup');
		}		
		$data['title_page']  = $judul."<strong>$po_no</strong>";
		
		// untuk PPN 10%
		$nilai_ppn=0;
		
		if (self::$ppn_status == 'ppn_')
			$nilai_ppn = 0.1;
		
		if($po_id == '') {
			echo "<script language='javascript'>\n";
			echo "alert('$this->lang->line('lap_detail_alert_po_tidak_ditemukan')')\n";
			echo "location.href='index.php/$link_controller/index\n";
			echo "</script>";
		}
		
		//----select from po--------------------
		$sql  = "select p.po_no, date_format(p.po_date,'%d-%m-%Y') as po_date, 
						sup_name, ml.legal_name
				 from prc_po as p
		         inner join prc_master_supplier as s on s.sup_id = p.sup_id
				 left join prc_master_legality as ml on ml.legal_id = s.legal_id			
		         where po_id='$po_id'";
		$rs   = $this->db->query($sql);
		if ($rs->num_rows() > 0):
		foreach($rs->result() as $arr):
			$data['po_no'] = $arr->po_no;
			$data['po_date'] = $arr->po_date;
			$data['sup_name'] = $arr->sup_name;			
			$data['legal_name'] = $arr->legal_name;			
		endforeach;
		endif;
		
		//----Select from po_detail-------------
		$sql	  = "select pr.qty, pr.qty_terima, pr.qty_retur, pr.auth_note,pr.discount,pr.auth_no,
							cur.cur_digit, cur.cur_symbol,pr.price as harga_satuan, 
							(pr.price * pr.qty * (pr.discount/100)) as diskon,
							((((pr.price * pr.qty) - (pr.price * pr.qty * (pr.discount/100)))) * '$nilai_ppn' ) as nilai_ppn,
							( ((pr.price * pr.qty) - (pr.price * pr.qty * (pr.discount/100))) + 
							  ((((pr.price * pr.qty) - (pr.price * pr.qty * (pr.discount/100)))) * '$nilai_ppn' ) ) as sub_total,
														
							abs((pr.qty_terima - pr.qty_retur - pr.qty)) as qty_remain,						
							
							CASE WHEN(pr.qty_terima - pr.qty_retur - pr.qty) < 0 THEN 'KURANG'  
							WHEN (pr.qty_terima - pr.qty_retur - pr.qty) > 0 THEN 'LEBIH'
							ELSE 'O.K'
							END as qty_status, 
							
							pr.price, (pr.price * pr.qty) as amount, pro.pro_code, 
							pro.pro_name,pr.um_id, pr.cur_id, m.satuan_name 
					 from prc_pr_detail as pr
					 inner join prc_master_product as pro on pr.pro_id = pro.pro_id 
					 inner join prc_master_satuan as m on pr.um_id = m.satuan_id
					 left join prc_master_currency as cur on cur.cur_id = pr.cur_id
					
					 where pr.po_id='$po_id'";
		//$arr	  = array();
		//$arr_po   = array();
		$arr_po		  = $this->db->query($sql);
		//$i = 0;
		//while($arr = $rs->FetchRow()) {
		//	array_push($arr_po,$arr);
		//}		
		// ======== jumlah harga produk =====================
		
		$total			= 0;				
		$tot_diskon		= 0;		
		$tot_nilai_ppn	= 0;
		
		foreach($arr_po ->result() as $rows_po)
		{		
			$total			= $total + $rows_po->sub_total;			
			$tot_diskon 	= $tot_diskon + $rows_po->diskon;			
			$tot_nilai_ppn	= $tot_nilai_ppn + $rows_po->nilai_ppn;
		}
		
		$data['total']=$total;		
		$data['tot_diskon']=$tot_diskon;
		$data['tot_nilai_ppn']=$tot_nilai_ppn;
		
		// ======== (akhir) jumlah harga produk =====================
		
		$sql	= "select d.*, p.pro_code, p.pro_name, g.*, date_format(g.gr_date,'%d-%m-%Y') as gr_date from prc_gr_detail as d 
				   inner join prc_gr as g on g.gr_id = d.gr_id
				   inner join prc_master_product as p on p.pro_id = d.pro_id
				   where g.po_id = '$po_id'
				   order by g.gr_date";
		//$arr_bpb = array();
		$arr_bpb		 = $this->db->query($sql);
		//	while($arr = $rs->FetchRow()) {
		//	array_push($arr_bpb,$arr);
		//}
		
		$data['po_status'] = $po_status;
		$data["po_detail"] = $arr_po;
		$data["po_id"] = $po_id;
		$data["bpb"] = $arr_bpb;
		
		// ================== bwt ngirim ke excelnya ======
		$data["po_id_excel"]=$po_id;
		$data["po_status_excel"]=$po_status;	
		// ===================================================
		if ($lihat_untuk == 'view'){
			$data['content'] = self::$link_view.'/report_po_detail';
			$this->load->view('index',$data);
		} else if ($lihat_untuk == 'excel'){
			$data['content'] = self::$link_view.'/report_po_detail_excel';$this->load->view(self::$link_view.'/report_po_detail_excel',$data);		
		}

	}
	
	function close_po() {
		
		$po_id			= $this->input->post("po_id");
		$po_note		= $this->input->post("po_note");
		
		$sql = "update prc_po set po_status='1', po_note='$po_note' where po_id='$po_id'";
		$this->db->query($sql);
		
		//--get credit term--
		$sql = "select b.term_days from prc_po a, prc_master_credit_term b where a.term_id=b.term_id and a.po_id='$po_id'";
		$rs = $this->db->query($sql);
		if($rs) {
			$term_credit = $rs->row()->term_days;
		}
		
		//--check is there any kontrabon not print yet--
		$sql = "select count(con_id) as jum_kb from prc_contrabon  
				where con_printStat='0'";
		$rs  = $this->db->query($sql);
		if($rs) {
			$jum_kb = $rs->row()->jum_kb;
		}
		
		if($jum_kb == 0) {
			$due_date      = mktime(0, 0, 0, date("m")  , date("d")+$term_credit, date("Y"));
			$str_due_date  = date("Y-m-d", $due_date);
		
			$sql = "update prc_contrabon set con_dueDate='$str_due_date' where po_id='$po_id'";
			if ($this->db->query($sql)):
				echo $this->lang->line('pesan-berhasil');
			endif;
		}
	}
}
?>