<?php
class Master_posting_hutang extends MY_Controller {
	public static $link_view, $link_controller,$search_year,$search_month;
	function master_posting_hutang() {
		parent::MY_Controller();
		
	// ============ manggil helper ============
		$this->load->library('general');
		
	// ================== manggil bahasa ====================
		$this->lang->load('general','bahasa');
		$this->lang->load('mod_report/laporan_umum','bahasa');
		$this->lang->load('mod_report/pembayaran_barang/laporan','bahasa');

	// ================== manggil model tabelnya ====================		
	//	$this->load->model(array('tbl_hutang','tbl_supplier','tbl_gr','tbl_bkbk'));
	
		// ============= link untuk manggil viewnya ===================
		self::$link_controller = 'mod_master/master_posting_hutang';
		self::$link_view = 'purchase/mod_master/posting_hutang'; 
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;

		// ============= (akhir )link untuk manggil viewnya ===================

		// =======================  inisialisasi =======================
			self::$search_year=0;
			self::$search_month=0;
		
		// =======================  (akhir) inisialisasi =======================
	
		// ============ buat array combo box ===============	
			$data['data_tahun']=$this->general->combo_box('tahun');

		// ============ (akhir) buat array combo box ===============
		
		
		//	============ inputan ========
		if ($this->input->post("search_year"))
			self::$search_year = $this->input->post("search_year");			
			
		if ($this->input->post("search_month"))
			self::$search_month = $this->input->post("search_month");			
			
		//	============ (akhrr) inputan ========


		// JUDUL HALAMAN
		$data['page_title'] = $this->lang->line('hutang_judul');
		
		$this->load->vars($data);
	}
	
	function index() {
		
		if ($this->input->post("post")){
			$this->post_hutang();
			$this->data_hutang();	
		}else {
			$this->data_hutang();			
		}			


	}
	
	
	
	function data_hutang()
	{
		$search_year=self::$search_year;
		$search_month=self::$search_month;

		
	// untuk array combo box data bulan 	
		$sql = "select distinct date_format(gr_date,'%M') as mon_name, date_format(gr_date,'%c') as mon_num from prc_gr where post_stat=0 and year(gr_date) = $search_year order by gr_date asc limit 1";		
		
//$sql = "select distinct date_format(bkbk_date,'%M') as mon_name, date_format(bkbk_date,'%c') as mon_num from prc_bkbk where post_stat=0 and year(bkbk_date) = $search_year order by bkbk_date asc limit 1";		
		$data['data_bulan']=$this->db->query($sql);;

		
	// ======================  pengiriman data ke view =====================			
		
		$data['search_year'] = $search_year;
		$data['search_month'] = $search_month;
		$data['content'] = self::$link_view.'/view';
		
	// ====================== akhir pengiriman data ke view =====================

		$this->load->view('index',$data);	

	
	}	
	
	function post_hutang ()
	{
		$search_year=self::$search_year;
		$search_month=self::$search_month;
	
		$sql_sup = "select sup_id from prc_master_supplier";
		
		foreach($this->db->query($sql_sup) -> result() as $row_sup):
			$sup_id = $row_sup->sup_id;
			
			
			// untuk cek apakah supplier sebelumnya sudah di post belum untuk bulan yang dipilih			
				$sql_hutang_pernah = "SELECT * from prc_hutang_bulanan as hb
									  WHERE hb.sup_id ='$sup_id' and hb.bln_pos = '$search_month'
									        and hb.thn_pos='$search_year' ";
				$data_hutang_pernah = $this->db->query($sql_hutang_pernah);
				if ($data_hutang_pernah->num_rows() == 0)
				{
					// ============== untuk hutang akhir / awal (insert) ========================
					$this->hutang_awal($sup_id,1); // untuk rupiah
					$this->hutang_awal($sup_id,2); // untuk dolar	
				}
			// ( akhir ) untuk cek apakah supplier sebelumnya sudah di post belum untuk bulan yang dipilih			
			
				// untuk update data hutang 
				$this->hutang_beli($sup_id); //hutang beli
				$this->hutang_bayar($sup_id); //hutang bayar
			
		endforeach; // akhir foreach untuk queary sql_sup
			
		// untuk updae status hutangnya
		$sql = "update prc_gr set post_stat=1 where month(gr_date)=$search_month and  year(gr_date) = $search_year";
		$this->db->query($sql);

		$sql = "update prc_bkbk set post_stat=1 where month(bkbk_date)=$search_month and year(bkbk_date) = $search_year";
		$this->db->query($sql);
		// (akhir) untuk updae status hutangnya
			
	}// akhir fungsi post hutang
	
	// fungsi untuk hitung hutang awal beradasarkan sup_id dan currency id
	function hutang_awal ($sup_id,$cur_id)
	{
		// inisialisasi variabel
		$prev_month			= self::$search_month - 1;
		$search_month		= self::$search_month;
		$search_year		= self::$search_year;
		
		$tahun_sekarang 	= date('Y');
		$selisih_tahun 		= 0;
		$search_year2		= 0;
		$prev_month2		= 0;		
		
		
		// untuk tahun sebelumnya atau setelahnya
		if ($search_year != $tahun_sekarang)
		{
			// untuk tahun yang dipilih kurang dari tahun sekarang
			if ($search_year < $tahun_sekarang)
			{
				$selisih_tahun		= self::$search_year-$tahun_sekarang;							
				$search_year2		= self::$search_year-$selisih_tahun;
			} else if ($search_year > $tahun_sekarang) // untuk tahun depan (tp ga mungkin ??)
			{
				$selisih_tahun		= $tahun_sekarang-self::$search_year;							
				$search_year2		= self::$search_year+$selisih_tahun;			
			}
			
			$prev_month2		= 12;
			
			$sql_hutang_akhir = "select awal,beli,bayar from prc_hutang_bulanan 
						    where sup_id='$sup_id' and cur_id='$cur_id' and bln_pos = '$prev_month2'
							and thn_pos='$search_year2'";
		} else {
			$sql_hutang_akhir = "select awal,beli,bayar from prc_hutang_bulanan 
						    where sup_id='$sup_id' and cur_id='$cur_id' and bln_pos = '$prev_month'
							and thn_pos='$search_year'";
		}			
			$data_hutang = $this->db->query($sql_hutang_akhir);
			if($data_hutang-> num_rows() > 0 ) {
				foreach ($data_hutang->result() as $row_akhir):
					$akhir_prev = $row_akhir->awal + $row_akhir->beli - $row_akhir->bayar ;
				endforeach;
			}
			else {
				$akhir_prev = 0;
			}
			
			$sql_hutang_input = "insert into prc_hutang_bulanan (bln_pos,thn_pos,sup_id,awal,cur_id) values('$search_month','$search_year',$sup_id,'$akhir_prev','$cur_id')";			
			$this->db->query($sql_hutang_input);
	} // akhir fungsi hutang awal
	
		
	function hutang_beli ($sup_id)
	{
		$search_year=self::$search_year;
		$search_month=self::$search_month;
		
	
		$sql_hutang_beli  = "select sup.sup_name, sup.sup_id, cur.cur_id, cur.cur_symbol, 
					sum(gd.qty * gd.price*(100 - gd.discount)/100) as tot_beli from prc_gr as gr 
					inner join prc_gr_detail as gd
						on gd.gr_id = gr.gr_id
					inner join prc_po as po
						on gr.po_id = po.po_id
					inner join prc_master_supplier as sup
						on po.sup_id = sup.sup_id
					inner join prc_master_currency as cur
						on cur.cur_id = gd.cur_id
					where year(gr.gr_date) = $search_year and 
						   month(gr_date) = $search_month and 
						   sup.sup_id='$sup_id'
					group by po.sup_id, gd.cur_id";
	
	$data_hutang_beli = $this->db->query($sql_hutang_beli);
	
			if($data_hutang_beli-> num_rows() > 0 ) {
			foreach($data_hutang_beli->result () as $rows_beli):
				$tot_beli = $rows_beli->tot_beli ;
				$cur_id = $rows_beli->cur_id;
					
				$sql_update_hutang = "update prc_hutang_bulanan set beli='$tot_beli'
									  where sup_id='$sup_id' and cur_id='$cur_id' and 
									  thn_pos='$search_year' and bln_pos='$search_month'"; 
				$this->db->query($sql_update_hutang);
				
			endforeach;
		}
	
	} // akhir hutang beli
	
	
	function hutang_bayar ($sup_id)
	{
		$search_year=self::$search_year;
		$search_month=self::$search_month;
		
	
		$sql_bayar =   "SELECT sup.sup_name, sup.sup_id, cur.cur_id, cur.cur_symbol, 
					sum( bd.con_dibayar ) AS tot_bayar
					FROM prc_bkbk AS b
					INNER JOIN prc_bkbk_detail AS bd ON bd.bkbk_id = b.bkbk_id
					INNER JOIN prc_master_supplier AS sup ON b.sup_id = sup.sup_id
					INNER JOIN prc_master_currency AS cur ON cur.cur_id = bd.cur_id
					WHERE month( b.bkbk_date ) = $search_month and sup.sup_id=$sup_id
					GROUP BY b.sup_id, bd.cur_id";
	
		$data_hutang_bayar = $this->db->query($sql_bayar);
		
	if($data_hutang_bayar-> num_rows() > 0 ) {
		foreach($data_hutang_bayar->result() as $rows_bayar):
			$tot_bayar = $rows_bayar->tot_bayar;
			$cur_id = $rows_bayar->cur_id;
		
			$sql_update_bayar = "update prc_hutang_bulanan set bayar='$tot_bayar'
								  where sup_id=$sup_id and cur_id='$cur_id' and 
								  thn_pos='$search_year' and bln_pos='$search_month'"; 
			$this->db->query($sql_update_bayar);
		endforeach;
	}
	
	} // akhir fungsi hutang bayar
	
}
?>